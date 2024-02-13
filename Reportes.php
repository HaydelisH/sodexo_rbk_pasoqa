<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/docvigentesBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

include_once("includes/PlantillasBD.php");
include_once("includes/empresasBD.php");
include_once("includes/estadocontratosBD.php");

include_once("includes/feriadosBD.php");	
include_once("includes/flujofirmaBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("includes/tipoGeneracionBD.php");


//Generar Excel 
include_once("includes/Excel.php");

$page = new reportes();


class reportes {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
	private $tipoFirmasBD;
	private $tipogeneracionBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	private $idProyecto="";
	//Excel
	private $excel;
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	private $nroopcion=5; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;

	//Iconos 
	private $check = '<i class="fa fa-check DisBtn" aria-hidden="true" style="color:green;" title="Registro Aprobado" alt="Registro Aprobado"></i>';
	private $warning = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:orange;" title="Pendiente por aprobacion" alt="Pendiente por aprobacion"></i>';
	
	private $verde 		= '<div style="text-align: center;" title="En el plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="En el plazo" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Plazo por vencer">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Plazo por vencer" 	alt="Plazo por vencer"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';
	
	// funcion contructora, al instanciar
	function __construct()
	{
		// revisamos si la accion es volver desde el listado principal
		if (isset($_POST["accion"]))
		{
			// si lo es
			if ($_POST["accion"]=="Volver")
			{
				// nos devolvemos al lugar especificado
				header('Location: index.php');
				return;
			}
		}
				// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = new Paginas();
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			// lo agregamos a la pagina
			$this->pagina->agregarDato('mensajeError',$this->mensajeError);

			// mostramos el encabezado
			$this->pagina->imprimirTemplate('templates/encabezado.html');
			$this->pagina->imprimirTemplate('templates/encabezadoFin.html');

			// imprimimos el template
			$this->pagina->imprimirTemplate('templates/puroError.html');
			// Imprimimos el pie
			$this->pagina->imprimirTemplate('templates/piedepagina.html');
			// y nos vamos
			return;
		}

		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "Reportes";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "GESTI&Oacute;N DE PROYECTOS";
		$this->opcionnivel2 = "<li>Reportes</li>";
		
		// instanciamos del manejo de tablas
		$this->docvigentesBD = new docvigentesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->empresasBD = new empresasBD();
		$this->estadocontratosBD = new estadocontratosBD();
		$this->feriadosBD 	= new feriadosBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		$this->tipogeneracionBD = new tipogeneracionBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->docvigentesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->estadocontratosBD->usarConexion($conecc);
		$this->feriadosBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->tipogeneracionBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r($_POST);
		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->listado_p();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "BUSCAR_P":
				$this->listado_p();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		if (!isset($_POST["accion2"]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// buscamos el idCategoria que vamos a asignar
			$this->docvigentesBD->idMax($dt);
			//Nos traemos el error si hubo
			$this->mensajeError=$this->docvigentesBD->mensajeError;
			//Asignar resultado a una variable 
			$this->idCategoria = $dt->data[0]["total"];
			//Asignar la variable al campo en html
		    $this->pagina->agregarDato("idCategoria",$this->idCategoria);
		}

		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "AGREGAR":
					// enviamos los datos del formulario a guardar
					if ($this->docvigentesBD->agregar($_POST))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						//Pasamos el error a la pagina 
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						//Imprimir la plantillas
						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->docvigentesBD->mensajeError;
					//Pasamos el error a la pagina 
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					//Imprimir la plantillas
					$this->pagina->modificar();
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		//Asignamos los datos que recibimos del formulario
		$this->docvigentesBD->listado($dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulario[0]["firmasdoc"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/firmasdoc_FormularioAgregar.html');
	}

	//Accion de modificar un registro 
	private function modificar()
	{	
		if (!isset($_POST["accion2 "]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// obtenemos los datos a modificar
			$this->docvigentesBD->obtener($_POST,$dt);
			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por siaca hubo
			$this->mensajeError=$this->docvigentesBD->mensajeError;

		}

		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->docvigentesBD->modificar($_POST))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
						//Pasamos el error a la pagina 
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						//Agrega un valor vacio a esta accion , para que se quede en la pantalla de Modificar 
						$_POST["accion2"]=" ";
						//Me quedo en el modificar
						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->docvigentesBD->mensajeError;
					//Pasamos el error a la pagina 
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					//Imprimir la plantillas
					$this->modificar();
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_POST;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		$datos2=$_POST;

		//Asignamos los datos que recibimos del formulario
		$this->docvigentesBD->listado($dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulario[0]["firmasdoc"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("formulario",$campos);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/firmasdoc_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->docvigentesBD->eliminar($_POST)){
			//Pasamos el mensaje de Ok
			$this->mensajeOK="Registro Elimnado! Su registro se ha eliminado con exito";
			//Pasamos el error a la pagina 
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			//Pasamos al listado actualizado
			$this->listado();
			return;
		}
		//Pasamos el error si hubo
		$this->mensajeError.=$this->proyectosBD->mensajeError;
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		//Pasamos al listado actualizado
		$this->listado();
		return;
		
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;

        $datos["usuarioid"]=$this->seguridad->usuarioid;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->total($datos,$dt);

		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		for ($l=0; $l < count($dt->data) ; $l++)
		{
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] < 6)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}else{
				$formulariox[0]["listado"][$l]["semaforodetalle"] = "-";
				$formulariox[0]["listado"][$l]["semaforototal"]   =	"-";
			}

			$formulariox["RutEjecutivo"] = $datos["RutEjecutivo"];
		}		

		$this->PlantillasBD->obtenerTiposDocumentos($dt);
		$this->mensajeError.=$this->PlantillasBD->mensajeError;
		$formulariox[0]["tiposdocumentos"]=$dt->data;
				
		$datos2["TipoEmpresa"] = 1;
		$this->empresasBD->listado($datos2,$dt2);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"] = $dt2->data;
		
		$this->estadocontratosBD->listado($dt3);
		$this->mensajeError.=$this->estadocontratosBD->mensajeError;
		$formulariox[0]["estadocontratos"] = $dt3->data;

		$this->tipoFirmasBD->listado($dt4);
		$this->mensajeError.=$this->tipoFirmasBD->mensajeError;
		$formulariox[0]["TipoFirmas"] = $dt4->data;
		
		$this->tipogeneracionBD->listado($dttipos);
		$this->mensajeError = $this->tipogeneracionBD->mensajeError;
		$formulariox[0]["TipoGeneracion"] = $dttipos->data;
	

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];	
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];	
		$formulario[0]["TipoGeneracion"]=$formulariox[0]["TipoGeneracion"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/reportes_Listado.html');
		
	}

	//Mostrar listado de los registro disponibles
	private function listado_p()
	{  
	
		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;

        $datos["usuarioid"]=$this->seguridad->usuarioid;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->totalPorTiempo($datos,$dt);

		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listadoPorTiempo($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		for ($l=0; $l < count($dt->data) ; $l++)
		{
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] < 6)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}else{
				$formulariox[0]["listado"][$l]["semaforodetalle"] = "-";
				$formulariox[0]["listado"][$l]["semaforototal"]   =	"-";
			}

			$formulariox["RutEjecutivo"] = $datos["RutEjecutivo"];
 
			//Los campos para generar
			$dt->data[$key]["idContrato"] = $datos["idContrato"];
			$dt->data[$key]["idProyecto"] = $datos["idProyecto"];
			$dt->data[$key]["Descripcion_Pl"] = $datos["Descripcion_Pl"];
			$dt->data[$key]["idTipoDoc"] = $datos["idTipoDoc"];
			$dt->data[$key]["RazonSocialCliente"] = $datos["RazonSocialCliente"];
			$dt->data[$key]["RutEjecutivo"] = $datos["RutEjecutivo"];
			$dt->data[$key]["RutEmpresa"] = $datos["RutEmpresa"];
			$dt->data[$key]["idEstado"] = $datos["idEstado"];
			$dt->data[$key]["idTipoFirma"] = $datos["idTipoFirma"];
			$dt->data[$key]["fechaInicio"] = $datos["fechaInicio"];
			$dt->data[$key]["fechaFin"] = $datos["fechaFin"];			
		}		

		$this->PlantillasBD->obtenerTiposDocumentos($dt);
		$this->mensajeError.=$this->PlantillasBD->mensajeError;
		$formulariox[0]["tiposdocumentos"]=$dt->data;
				
		$datos2["TipoEmpresa"] = 1;
		$this->empresasBD->listado($datos2,$dt2);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"] = $dt2->data;
		
		$this->estadocontratosBD->listado($dt3);
		$this->mensajeError.=$this->estadocontratosBD->mensajeError;
		$formulariox[0]["estadocontratos"] = $dt3->data;

		$this->tipoFirmasBD->listado($dt4);
		$this->mensajeError.=$this->tipoFirmasBD->mensajeError;
		$formulariox[0]["TipoFirmas"] = $dt4->data;

		$this->tipogeneracionBD->listado($dttipos);
		$this->mensajeError = $this->tipogeneracionBD->mensajeError;
		$formulariox[0]["TipoGeneracion"] = $dttipos->data;
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];	
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];	
		$formulario[0]["TipoGeneracion"]=$formulariox[0]["TipoGeneracion"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/reportes_ListadoPorTiempo.html');
		
	}
	
	//Mostrar listado de los registro disponibles
	public function semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$pdtferiados)
	{  
		//$this->graba_log("pfechacreacion:".$pfechacreacion." pfechaultimafirma".$pfechaultimafirma." dias max:".$pdiasmax);		
		$dt = new DataTable();
	
		//si tiene fecha ultima firma debe ser fecha inicio desde y el día actual es fecha hasta
		if ($pfechaultimafirma != "")
		{
			$fechainicio_num = substr($pfechaultimafirma,6,4).substr($pfechaultimafirma,3,2).substr($pfechaultimafirma,0,2);
		}
		else
		{
			if ($pfechacreacion == "")
			{
				$fechainicio_num 	= date('Ymd');
			}
			else
			{
				$fechainicio_num 	= substr($pfechacreacion,6,4).substr($pfechacreacion,3,2).substr($pfechacreacion,0,2);
			}
		}
		
		$fechatermino_num 	= date('Ymd');
	
		if ($pdiasmax ==  "")
		{
			$pdiasmax = 0;
		}
		
		//$this->graba_log("fecha inicio:".$fechainicio_num." fecha termino".$fechatermino_num." dias max:".$pdiasmax);
		//se formatea fecha para ocupar mas adelante para sumar días
		$fechaaux = substr($fechainicio_num,6,2)."-".substr($fechainicio_num,4,2)."-".substr($fechainicio_num,0,4);
		$fecha = date('d-m-Y', strtotime($fechaaux));
					
		$dias=0;
		for ($f=$fechainicio_num; $f<$fechatermino_num + 1; $f++)
		{
			$numdia = date('w', strtotime($fecha));
			
			//$this->graba_log("fecha :".$fecha." numdia:".$numdia." fechanum:".$fechainicio_num);
			
			//0=domingo 6=sabado
			if ($numdia != 0 && $numdia != 6)
			{
				//$this->graba_log("numdia:".$numdia." count:".count($pdtferiados->data));
				$encontro="N";
				for ($fe = 0; $fe < count($pdtferiados->data); $fe++) 
				{
					$datos["Fecha"] =$fecha;
					//$this->graba_log("feriado:".$pdtferiados->data[$fe]["Feriado"]." fecha:".$datos["Fecha"]);
					
					if ($pdtferiados->data[$fe]["Feriado"] == $datos["Fecha"])
					{
						//$this->graba_log("encontro ".$datos["Fecha"]);
						$encontro="S";
						break;
					}
				}
				
				if ($encontro == "N")
				{
					$dias++;
				}
				
				if ($dias > $pdiasmax)
				{
					break;
				}
				
			}
			
			$fecha =  date("d-m-Y",strtotime($fecha."+ 1 days"));
		}
			
		if ($dias > $pdiasmax )
			return $this->rojo;
		
		if ($dias < $pdiasmax )
			return $this->verde;
		
		if ($dias == $pdiasmax )
			return $this->amarillo;
 	}
	
	public function semaforototal($pfechacreacion,$pidwf,$pdtferiados,$dtflujos)
	{  
		//$this->graba_log("parametros:".$pfechacreacion." ".$pidwf);
		$dt = new DataTable();
		
		if ($pfechacreacion == "")
		{
			$fechacreacion_num = date('Ymd');
		}
		else
		{
			$fechacreacion_num = substr($pfechacreacion,6,4).substr($pfechacreacion,3,2).substr($pfechacreacion,0,2);
		}
		
		$fechaultimafirma_num = date('Ymd');
			
		//$fechacreacion_mmddaaaa = substr($pfechacreacion,3,2)."/".substr($pfechacreacion,0,2)."/".substr($pfechacreacion,6,4);
		//$fecha = strtotime($fechacreacion_mmddaaaa);
		$fecha = date('d-m-Y', strtotime($pfechacreacion));
		
		$diasmax = 0;
		
		//$this->graba_log("cant flujos:".count($dtflujos->data));
		for ($fl = 0; $fl < count($dtflujos->data) ; $fl++) 
		{
			if ($pidwf == $dtflujos->data[$fl]["idWF"])
			{
				$diasmax = $diasmax + $dtflujos->data[$fl]["DiasMax"];
				//$this->graba_log("diasmax:".$diasmax);
			}
		}
		
				
		$dias=0;
				
		for ($f=$fechacreacion_num; $f<$fechaultimafirma_num + 1; $f++)
		{
			
			$numdia = date('w', strtotime($fecha));
			
			//$this->graba_log("fecha :".$fecha." numdia:".$numdia." fechanum:".$fechacreacion_num." count:".count($pdtferiados->data));
			
			//0=domingo 6=sabado
			if ($numdia != 0 && $numdia != 6)
			{
				//$this->graba_log("numdia:".$numdia." count:".count($pdtferiados->data));
				$encontro="N";
				for ($fe = 0; $fe < count($pdtferiados->data); $fe++) 
				{
					$datos["Fecha"] =$fecha;
					//$this->graba_log("feriado:".$pdtferiados->data[$fe]["Feriado"]." fecha:".$datos["Fecha"]);
					
					if ($pdtferiados->data[$fe]["Feriado"] == $datos["Fecha"])
					{
						//$this->graba_log("encontro");
						$encontro="S";
						break;
					}
				}
				
				if ($encontro == "N")	
				{
					$dias++;
				}
				
				if ($dias > $diasmax)
				{
					break;
				}
				
			}
			
			$fecha =  date("d-m-Y",strtotime($fecha."+ 1 days"));
			//$this->graba_log("fecha despues:".$fecha);
		
		}
		
		//return $fechacreacion_num." ".$fechaultimafirma_num." ".$dias;
		
		if ($dias > $diasmax )
			return $this->rojo;
		
		if ($dias < $diasmax )
			return $this->verde;
		
		if ($dias == $diasmax )
			return $this->amarillo;		
 	}	

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/calculo_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	
}

?>
