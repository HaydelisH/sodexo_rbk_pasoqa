<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/firmasdocBD.php");
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
include_once("includes/procesosBD.php");
include_once("includes/documentosdetBD.php");
include_once("includes/procesosBD.php");
include_once("includes/contratofirmantesBD.php");

//GESTOR DE FIRMA 
include_once("firma.php");

$page = new firmasdoc();

class firmasdoc {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $firmasdocBD;
	private $tipoFirmasBD;
	private $tipogeneracionBD;
	private $procesosBD;
	private $documentosdetBD;
	private $contratofirmantesBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	private $mensajeAd="";
	private $idProyecto="";
	
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

		$this->opcion = "Firmar un Documento";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Firmar un Documento</li>";
		
		// instanciamos del manejo de tablas
		$this->firmasdocBD = new firmasdocBD();
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
		$this->procesosBD = new procesosBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->firmasdocBD->usarConexion($conecc);
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
		$this->procesosBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->listado();
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
			case "DETALLE":
				$this->detalle();
				break;
			case "INICIOFIRMA":
				$this->iniciofirma();
				break;
			case "FIRMAR":
				$this->firmar();
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
			$this->firmasdocBD->idMax($dt);
			//Nos traemos el error si hubo
			$this->mensajeError=$this->firmasdocBD->mensajeError;
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
					if ($this->firmasdocBD->agregar($_POST))
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
					$this->mensajeError.=$this->firmasdocBD->mensajeError;
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
		$this->firmasdocBD->listado($dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
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
			$this->firmasdocBD->obtener($_POST,$dt);
			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por siaca hubo
			$this->mensajeError=$this->firmasdocBD->mensajeError;

		}

		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->firmasdocBD->modificar($_POST))
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
					$this->mensajeError.=$this->firmasdocBD->mensajeError;
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
		$this->firmasdocBD->listado($dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
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
		if ($this->firmasdocBD->eliminar($_POST)){
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
		$datos['Firmante'] = $this->seguridad->usuarioid;
		$datos["idTipoFirma"] = 2;

        
		//busco el total de paginas
		$this->firmasdocBD->total($datos,$dt);

		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->firmasdocBD->listado($datos,$dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		if( ! isset($datos['idEstado']))
			$datos["idEstado"] = $formulariox[0]["listado"][0]["idEstado"]; 

		$this->firmasdocBD->listado($datos,$dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		for ($l=0; $l < count($dt->data) ; $l++)
		{
			if ($formulariox[0]["listado"][$l]["idEstado"] != 6)
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
		}		
			
		//Listados de Tipos de documentos
		$this->firmasdocBD->listadoPorTiposDocumentos_filtros($datos,$dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["tiposdocumentos"]=$dt->data;
		
		//Listado de estados
		$this->firmasdocBD->listadoPorEstados_filtros($datos,$dt3);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["estadocontratos"] = $dt3->data;
		
		//Listado de Tipo de firmas 
		$this->firmasdocBD->listadoPorTipoFirmas_filtros($datos,$dtfirmas);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["TipoFirmas"] = $dtfirmas->data;

		//Listado de Procesos
		$this->firmasdocBD->listadoPorProcesos_filtros($datos,$dt4);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$formulariox[0]["Procesos"] = $dt4->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);

		$formulario[0]=$datos;
	
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];	
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		$this->pagina->agregarDato("formulario",$formulario);
		
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_Listado.html');
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

 	//Mostrar Detalle de Firma
 	private function detalle(){

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$datos = $_POST;
	
		$this->documentosdetBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$formulario=$dt->data;
		
		$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt2);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
	    $formulario[0]["contratofirmantes"] = $dt2->data;

		$estado = 0;
		$datos["personaid"] = $this->seguridad->usuarioid;

		//para visualizar botones segun estado
		if($dt->leerFila())
		{
			$estado = $dt->obtenerItem("idEstado");
			$datos["idEstado"] = $estado;
		}

		//Firmantes en ese orden
		$this->contratofirmantesBD->ObtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
		
		if($dt3->leerFila())
		{
			$aux = $dt3->obtenerItem("RutFirmante");
		}

		//Estado 8 = Rechazado y Estado 6 = Aprobado
		if ($estado > 1 && $estado != 6 && $aux!="")//si debe cumplir con mas condiciones agregar
		{
			$formulario[0]["firmar"][0]	= "";
		}		

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_formulario.html');
 	}

 	//INICIO DE FIRMA
 	private function iniciofirma()
	{  
		$dt = new DataTable();
		$dt1 = new DataTable();
		$datos = $_POST;	

		//consulta para deducir tipo de firma pin, token o huella
		$usuarioid = $this->seguridad->usuarioid;
		$datos["personaid"] = $usuarioid;
		
		//Consultar el tipo de firma que tiene asociada el usuario
		$this->documentosdetBD->obtenerTipoFirma($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if($dt1->leerFila())
		{
			$tipofirma = $dt1->obtenerItem("Descripcion");
		}

		if (trim($tipofirma) == "")
		{
			switch (GESTOR_FIRMA) {
				case 'DEC5':
					$tipofirma = TIPO_FIRMA_PORDEFECTO_DEC5;
				default:
					$tipofirma = TIPO_FIRMA_PORDEFECTO_RBK;
			}
		}
		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if ($tipofirma == "Pin")
				{
					$this->InicioFirmaPin();
				}
				
				if ($tipofirma == "Huella")
				{
					$this->InicioFirmaHuella();
				}
				
				if ($tipofirma == "Token")
				{
					$this->InicioFirmaToken();
				}

				if($tipofirma == 'Pin o Huella')
				{
					$this->mensajeError = "Debe configurarle al firmante un solo tipo de firma, Pin o Huella ";
					$this->listado();
				}
				break;
			
			default:
				if ($tipofirma == "Pin" || $tipofirma == "Huella" || $tipofirma == 'Pin o Huella')
				{
					$this->InicioFirma_RBK();
				}
				break;
		}

		if($tipofirma == "Manual")
		{	
			$this->mensajeError = "No podra continuar con el proceso de firma electr&oacute;nica, su tipo de firma es Manual ";
			$this->listado();
		}

		if($tipofirma == "No Firma")
		{	
			$this->mensajeError = "Ud. no tiene permiso para realizar ninguna firma";
			$this->listado();
		}
	}	

	private function InicioFirmaPin()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;

		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";
		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp.".".$extension;
			
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_firma.html');			
	}

	private function InicioFirma_RBK()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp.".".$extension;
			
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 

		//Datos de persona
		$datos["personaid"] = $this->seguridad->usuarioid;

		$this->documentosdetBD->obtenerTipoFirma($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( count($dt->data) > 0){

			foreach ($dt->data[0] as $key => $value) {
				if( $key == 'RutUsuario')
					$datos['RutUsuario'] = $value;
				if( $key == 'Descripcion')
					$datos['TipoFirma'] = $value;
			}
		}

		switch( $datos['TipoFirma'] ){
			case 'Pin' : 
				$datos['TipoFirma'] = strtoupper($datos['TipoFirma']);
				break;
			case 'Huella' : 
				$datos['TipoFirma'] = 'FINGERPRINT';
				break;
			case 'Pin o Huella' : 
				$datos['TipoFirma'] = '';
				break;
			//Agregar los tipos de firma necesarios 
		}
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_firma_rbk.html');	
	}
		
	private function InicioFirmaToken()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		
		$formulario[0] = $datos;	
		
		$this->documentosdetBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		if($dt->leerFila())
		{
			$formulario[0]["documentos"] = $dt->obtenerItem("DocCode");
		}
		
		//obtener rut firmante 
		$rut = $this->seguridad->usuarioid;
		$formulario[0]["rut"]=$rut;
		
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0]["ruta"] 	= $nombrearchivo;
		
		$this->dec5 = new dec5();
		$this->dec5->ObtenerUrlToken($dt);
		$this->mensajeError.=$this->dec5->mensajeError;
		$formulario[0]["uri"] 			= $dt["url_token"];
		$formulario[0]["institucion"]	= $dt["institucion"];
	
		$formulario[0]["botonfirma"]="";
		
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_firma_token.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	private function InicioFirmaHuella()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $nombrearchivo;
		
		$rut = $this->seguridad->usuarioid;
		$formulario[0]["rut"]=$rut;
		
		$formulario[0]["botonfirma"]="";

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_firma_huella.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior				
	}

	//LLama a la clase para firmar 
	private function firmar(){

		$firma = new firma();
		$datos = $_POST;
		$usuarioid = $this->seguridad->usuarioid;

		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if( isset($datos['pin']) ){
					$respuesta = $firma->firmar_pin($datos['idDocumento'],$usuarioid);
				}
				break;
			
			default:
			//print_r($datos);
				$respuesta = $firma->firmar_rbk($datos,$usuarioid);
				break;
		}
		if( $respuesta['codigo'] == 200 ) $this->mensajeOK = $respuesta['mensaje'];
		else {
			if( $respuesta['mensaje'] == 'No se pudo completar el envio del documento al Gestor' ){
				$this->mensajeAd = $respuesta['mensaje'];
			}else{
				$this->mensajeError .= $respuesta['mensaje'];
			}
		}
		$this->verdocumento($datos['idDocumento']);
	}

	//LLama a la clase para firmar 
	private function firmar_token(){

		$firma = new firma();
		$datos = $_POST;

		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if( isset($datos['pin']) ){

					$usuarioid = $this->seguridad->usuarioid;
					$respuesta = $firma->firmar_pin($datos['idDocumento'],$usuarioid);

					if( $respuesta['codigo'] == 200 ) $this->mensajeOK = $respuesta['mensaje'];
					else $this->mensajeError .= $respuesta['mensaje'];

					$this->verdocumento($datos['idDocumento']);
				}
				break;
			
			default:
				$firma->firmar_dec($datos['idDocumento']);
				break;
		}
	}

	//Ver documento actualizado
	private function verdocumento($idDocumento)
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		$datos['idDocumento'] = $idDocumento;
		$fecha = date('dmY_hms');

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt); 
		$this->mensajeError  .= $this->documentosdetBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp."_".$fecha.".".$extension;
		
		//Actualizar nombre de archivo
		//$datos['NombreArchivo'] = $nomarchtmp."_".$fecha;
		//$this->documentosdetBD->modificarNombreArchivo($datos);
			
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_Firmas_documento.html');				
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

	private function imprimirFin2()
	{
		include("includes/opciones_fin2.php");
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
