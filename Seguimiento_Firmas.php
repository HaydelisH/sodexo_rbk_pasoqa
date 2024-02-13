<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

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
include_once("includes/contratofirmantesBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("includes/comentariosBD.php");
include_once("includes/tipoGeneracionBD.php");

include_once("includes/estadosworkflowBD.php");



$page = new seguimiento_firmas();

function semaforodetalle ($fechacreacion,$fechaultimafirma,$diasmax)
{

	$calculos = new calculos();
	
	$res = $calculos->semaforodetalle($fechacreacion,$fechaultimafirma,$diasmax);
	
	return $res;
}

function semaforototal ($fechacreacion,$idwf)
{

	$calculos = new calculos();
	
	$res = $calculos->semaforototal($fechacreacion,$idwf);
	
	return $res;
}


class seguimiento_firmas{

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
	private $contratofirmantesBD;
	private $tipoFirmasBD;
	private $comentariosBD;
	private $tipogeneracionBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
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

		$this->opcion = "Seguimiento de firmas ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Seguimiento de Firmas</li>";
		
		// instanciamos del manejo de tablas
		$this->docvigentesBD = new docvigentesBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->empresasBD = new empresasBD();
		$this->estadocontratosBD = new estadocontratosBD();
		$this->feriadosBD 	= new feriadosBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->estadosworkflowBD = new estadosworkflowBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		$this->comentariosBD = new comentariosBD();
		$this->tipogeneracionBD = new tipogeneracionBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->docvigentesBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->estadocontratosBD->usarConexion($conecc);
		$this->feriadosBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->estadosworkflowBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->comentariosBD->usarConexion($conecc);
		$this->tipogeneracionBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r($_POST);
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
			case "AGREGAR_COM":
				$this->agregar_com();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "MODIFICAR_COM":
				$this->modificar_com();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "DETALLE":
				$this->detalle();
				break;
			case "APROBAR":
				$this->aprobar();
				break;
			case "VERDOCUMENTO":
				$this->verdocumento();
				break;
			case "VERDOCUMENTO_F": //Documento firmado
				$this->verdocumento_firmado();
				break;
			case "RECHAZAR":
				$this->rechazar();
				break;
			case "POR_FIRMA":
				$this->por_firma();
				break;
			case "FIRMADOS":
				$this->firmados();
				break;
			case "CANCELAR":
				$this->cancelar();
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
		$formulario[0]["docvigentes"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/docvigentes_FormularioAgregar.html');
	}

	private function agregar_com(){

		$datos = $_POST;

		$dt = new DataTable();

		//Completamos los datos faltantes
		$datos["RutUsuario"] = $this->seguridad->usuarioid;
		$datos["Comentario"] = $this->TildesHtml($datos["Comentario"]);

		$this->comentariosBD->agregar($datos);
		$this->mensajeError = $this->comentariosBD->mensajeError;
		$this->detalle();
		
	}

	//Accion de modificar un registro 
	private function modificar()
	{	
		$datos = $_POST;
		//print_r($datos);
		$this->docvigentesBD->modifica_estado_contrato($datos);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->detalle();
		return;

	}

	//Accion de modificar un registro 
	private function modificar_com()
	{	
		$datos = $_POST;

		$datos["RutUsuario"] = $this->seguridad->usuarioid;
		$datos["Comentario"] = $datos["coment"];
		$datos["idContrato"] = $datos["idDocumento"];
		
		$this->comentariosBD->modificar($datos);
		$this->mensajeError.=$this->comentariosBD->mensajeError;
		$this->detalle();
		return;

	}
	
	//Accion de modificar un registro 
	private function rechazar()
	{	
		$datos = $_POST;
		//print_r($datos);
		$this->docvigentesBD->rechazar($datos);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->detalle();
		return;

	}

	//Accion de modificar un registro 
	private function cancelar()
	{	

		$datos = $_POST;
		
		$this->docvigentesBD->rechazarPendienteTerminado($datos);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->detalle();
		return;

	}
	
	//Accion de eliminar un comentario
	private function eliminar()
	{
		$datos = $_POST;

		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->comentariosBD->eliminar($datos)){
			$this->detalle();
			return;
		}
		//Pasamos el error si hubo
		$this->mensajeError.=$this->comentariosBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->detalle();
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
        //print_r($datos);
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
		$formulariox[0]["listado"] = $dt->data;
		$registros = count($formulariox[0]["listado"]);

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
	
		if ( $registros==0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
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
		$this->pagina->imprimirTemplate('templates/seguimiento_firmas_Listado.html');
	}

	//Detalle del Documento
	private function detalle(){ 

		//Instanciar la clase

		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt7 = new DataTable();

		$datos = $_POST;
		
		$this->docvigentesBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulario=$dt->data;

		$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt2);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
	    $formulario[0]["contratofirmantes"] = $dt2->data;

	    $this->docvigentesBD->obtenerb64($datos,$dt7);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;

		$this->tipogeneracionBD->listado($dttipos);
		$this->mensajeError.=$this->tipogeneracionBD->mensajeError;
		$formulario[0]["TipoGeneracion"]=$dttipos->data;

		$estado = 0;
		$datos["personaid"] = $this->seguridad->usuarioid;
		//para visualizar botones segun estado
		if($dt->leerFila())
		{
			$estado = $dt->obtenerItem("idEstado");
			$datos["idEstado"] = $estado;
			$tipofirma 	= $dt->obtenerItem("idTipoFirma");
			$idwf		= $dt->obtenerItem("idWF");
		}
		//Firmantes en ese orden
		$this->contratofirmantesBD->ObtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
		
		if(isset($dt3->data[0]["RutFirmante"]))
		{
			$aux = $dt3->data[0]["RutFirmante"];
		}

		if ($tipofirma == 1 && $estado < 6)
		{
			$datos["idworkflow"] = $idwf;
			$this->estadosworkflowBD->listado_sr($datos,$dt4);
			$this->mensajeError.=$this->estadosworkflowBD->mensajeError;
			$formulario[0]["cambioestado"][0]	= "";
			$formulario[0]["idestado"]	= $estado;
			$formulario[0]["cambioestado"][0]["estadocontratos"] = $dt4->data;
		}
		if ($estado < 6)
		{
			$formulario[0]["cancelar_documento"] = "";
		}

		//Comentarios del documento
		$array = array ("idContrato" => $datos["idDocumento"]);
		$this->comentariosBD->obtener($array, $dt6);
		$this->mensajeError = $this->comentariosBD->mensajeError;

		if( count($dt6->data) > 0 ){
			foreach ($dt6->data as $key => $value) {
				$dt6->data[$key]["RutUsuario_Act"] = $this->seguridad->usuarioid;
			}
			$formulario[0]["comentarios"] = $dt6->data;
		}

		//Para visualizar boton de Documento Firmado (Carga Manual)
		if($dt7->leerFila())
		{
			$doc_firmado = $dt7->obtenerItem("NombreArchivo_f");
		}

		if( $doc_firmado != "" ){ //Si Existe algun documento firmado
			
			$formulario[0]["documento_f"][0]	= "";
		}
		
		//Pasamos los datos a la pagina
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/seguimiento_firmas_Detalle.html');
	}

	private function aprobar()
	{ 
		$datos = $_POST;	
		
		$this->docvigentesBD->modificar_estado($datos);
		$this->mensajeError=$this->docvigentesBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->listado();
		return;
	}
	
	public function verdocumento()
	{
		//print_r ($_POST);
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->docvigentesBD->obtenerb64($datos,$dt); //print_r($datos);
		$this->mensajeError=$this->docvigentesBD->mensajeError;
				
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
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Documento.html');				
	}

	public function verdocumento_firmado()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->docvigentesBD->obtenerb64($datos,$dt); 
		$this->mensajeError=$this->docvigentesBD->mensajeError;

		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo_f");
			$extension 	= $dt->obtenerItem("Extension_f");
			$archivob64	= $dt->obtenerItem("documento_f");
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
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Documento.html');				
	}

	
	//Mostrar listado de los registro disponibles
	public function semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$pdtferiados)
	{  
				
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
		
		for ($fl = 0; $fl < count($dtflujos->data); $fl++) 
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

	//Listado de Documentos en proceso por firma
	public function por_firma(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;
       // print_r($_POST);

        $datos["usuarioid"]=$this->seguridad->usuarioid;
        //print_r($datos);
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["idEstado"]=-1;

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->total($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"] = $dt->data;
		
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
			}
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

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Listado de Documentos Firmados
	public function firmados(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;
       // print_r($_POST);

        $datos["usuarioid"]=$this->seguridad->usuarioid;
        //print_r($datos);
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["idEstado"]=6;

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->total($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"] = $dt->data;
		
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
			}
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

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Sustituir acentos
	public static function TildesHtml($cadena) 
    { 
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }
	
}
	
?>
