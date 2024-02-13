<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once('includes/generar_manualesBD.php');
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("includes/documentosBD.php");
include_once("includes/flujofirmaBD.php");
include_once("includes/ContratosDatosVariablesBD.php");
include_once("includes/empresasBD.php");
include_once("includes/procesosBD.php");
include_once("includes/usuariosmantBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/estadocivilBD.php");
include_once("includes/estadoempleadoBD.php");
include_once("includes/documentosdetBD.php");
include_once("generar.php");
include_once("includes/rolesBD.php");

require_once('includes/tcpdf/examples/lang/ita.php');
require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/examples/tcpdf_include.php');
require_once('config.php');

include_once("includes/importarBD.php");
// Llamamos las clases necesarias PHPExcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');
require_once('includes/importarExcel_GenerarMasivo.php');

// creamos la instacia de esta clase
$page = new generar_fichapersonal();


class generar_fichapersonal {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $generar_fichapersonalBD;
	private $tiposdocumentosBD;
	private $tipoFirmasBD;
	private $documentosBD;
	private $flujofirmaBD;
	private $importarMasivo;
	private $ContratosDatosVariablesBD;
	private $importarBD;
	private $empresasBD;
	private $procesosBD;
	private $usuariosmantBD;
	private $centroscostoBD;
	private $estadocivilBD;
	private $estadoempleadoBD;
	private $documentosdetBD;
	private $rolesBD;
	
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para juntar los mensajes de alerta 
	private $mensajeAd="";
	// para asignar el idCategoria a un nuevo registro 
	private $idCategoria="";
	
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

	private $html = ""; //Variable que almacenara el texto completo del documento
	private $ruta = "";
	private $contrato_html = ""; //Contrato en HTML
	private $tabla_anexo = ""; //Tabla del Anexo 
	private $anexo_html = ""; //Anexo en HTML
	private $firmantes_tabla = ""; //Tabla de Firmantes en HTML
	private $firmantes_completos; //Arreglo de Firmantes de un Documento
	private $firmantes_empresa;
	private $firmantes_cliente;
	private $firmantes_notaria;
	private $empleado;
	private $ordinal = array(); //Ordonal de Tabla
	private $tipo_con = 0;
	private $band;

	//Datos para importar Excel
	private $datos_contrato_marco;
	private $datos_anexo;
	private $datos_renting;
	private $inputFileType;
	private $objReader;
	private $objPHPExcel;
	private $sheet;
	private $total ="";
	private $vacio = 0;
	private $strSqlIniInsert	="";
	private $strSqlIniUpdate	="";
	private $strSqlIniSelect	="";
	private $strSql		="";
	private $valorCelda;
	private $Llave;

	//Iconos 
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $verde1 		= '<div style="text-align: center;" title="Datos completos">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Datos completos" 		alt="En el plazo"></i></div>';
	
	private $orientacion = 'P';

	// funcion contructora, al instanciar
	function __construct()
	{

		// revisamos si la accion es volver desde el listado principal
		if (isset($_REQUEST["accion"]))
		{
			// si lo es
			if ($_REQUEST["accion"]=="Volver")
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

		$this->opcion = "Generaci&oacute;n individual ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Generaci&oacute;n individual</li>";
		
		// instanciamos del manejo de tablas
		$this->generar_manualesBD = new generar_manualesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->tiposdocumentosBD = new tiposdocumentosBD();
		$this->tipoFirmasBD = new tipoFirmasBD;
		$this->documentosBD = new documentosBD;
		$this->flujofirmaBD = new flujofirmaBD;
		$this->importarBD = new importarBD();
		$this->importarMasivo = new importarMasivo();//Metodos que ocupa el excel para la subida y el procesamiento de los script 
		$this->ContratosDatosVariablesBD = new ContratosDatosVariablesBD();
		$this->empresasBD = new empresasBD();
		$this->procesosBD = new procesosBD();
		$this->usuariosmantBD = new usuariosmantBD();
		$this->centroscostoBD = new centroscostoBD();
		$this->estadocivilBD = new estadocivilBD();
		$this->estadoempleadoBD = new estadoempleadoBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->rolesBD = new rolesBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->generar_manualesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->documentosBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->importarBD->usarConexion($conecc);
		$this->ContratosDatosVariablesBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		$this->usuariosmantBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);
		$this->estadoempleadoBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->rolesBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			$this->agregar();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_REQUEST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "LISTADO":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "GENERAR":
				$this->generar();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		$datos = $_REQUEST;

		//Inicialiamos la variable de tipo Tabla 
		$dt = new DataTable();

		$this->centroscostoBD->listado($dt);
		$this->mensajeError.= $this->centroscostoBD->mensajeError;
		$formulario[0]["CentrosCostos"] = $dt->data;

		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		//Tipo de Documento
		$this->tiposdocumentosBD->Todos($dt);
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
		$formulario[0]["TipoDocumentos"] = $dt->data;

		//Proceso 
		$this->procesosBD->listado($dt);
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$formulario[0]["idProceso"] = $dt->data;

		//Listado de Tipo de firmas 
		$this->tipoFirmasBD->listado($dt);
		$this->mensajeError .= $this->tipoFirmasBD->mensajeError;
		$formulario[0]["idFirma"] = $dt->data;

		//Listado de Estado Civil
		$this->estadocivilBD->listado($dt);
		$this->mensajeError .= $this->estadocivilBD->mensajeError;
		$formulario[0]["EstadoCivil"] = $dt->data;
		
		//Listado de Estado Empleado
		$this->estadoempleadoBD->listado($dt);
		$this->mensajeError .= $this->estadoempleadoBD->mensajeError;
		$formulario[0]["EstadosEmpleado"] = $dt->data;

		//Listado de Roles
		$this->rolesBD->listado($dt);
		$this->mensajeError .= $this->rolesBD->mensajeError;
		$formulario[0]["Roles"] = $dt->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/generar_documentos_PorFicha_FormularioAgregar.html');
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		$datos = $_REQUEST;

		$dt = new DataTable;

		//Preparamos los datos necesarios para la consulta 
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		//busco el total de paginas
		$this->usuariosmantBD->Total($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"] = round($dt->data[0]["totalreg"]);
		
		$this->usuariosmantBD->Listado($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		if ($datos["pagina_ultimo"]==0)
		{
			$this->mensajeOK="No hay información para la consulta realizada.";
		}else{
			$mensajeNoDatos="";
			$this->pagina->agregarDato("pagina",$datos["pagina"]);
			$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
			$formulario[0]=$datos;
			$formulario[0]["listado"]=$formulariox[0]["listado"];

			//Buscar opciones del usuario 
			$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
			$datos["opcionid"] = 'usuariosmant.php';
			$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

			if( $dt->data ){
				$crea = $dt->data[0]["crea"];
				$modifica = $dt->data[0]["modifica"];
				$elimina = $dt->data[0]["elimina"];
			
			}

			$num = count($formulario[0]["listado"]);

			if ( $crea     ) $formulario[0]["crear"][0]	   = "";
			for ( $i = 0 ; $i < $num ; $i++ ){
				if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
				if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
		
			}

			$this->pagina->agregarDato("formulario",$formulario);
		}

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/generar_documentos_PorFicha_Listado.html');
	}

	//Generar un documento individual 
	private function generar(){

		$datos = $_REQUEST;
	
		//Agregar el estado a empleado
		$datos['estado'] = $datos['idEstadoEmpleado'];
		$datos['EstadoEmpleado'] = $datos['idEstadoEmpleado'];
		
		//Cambiar el orden de los firmantes 
		$rut_1 = '';
		$rut_2 = '';
		$rut_3 = '';
		$cant_f = 0;
		
		$cant_f = count($datos["Firmantes_Emp"]);

		if( $cant_f > 1 ){
			foreach( $datos["Firmantes_Emp"] as $key => $value){
			
				$var ='';
				$var = 'orden_';
				$var .= $value; 
	
				if( $datos[$var] == 1 ){
					$rut_1 = $value;
				}else if( $datos[$var] == 2 ){
					$rut_2 = $value;
				}else{
					$rut_3 = $value;
				}
			}
			
			$array = array();
			
			if( strlen($rut_1 ) > 0 ) $array[0] = $rut_1;
			if( strlen($rut_2 ) > 0 ) $array[1] = $rut_2;
			if( strlen($rut_3 ) > 0 ) $array[2] = $rut_3;

			$datos["Firmantes_Emp"] = array();
			$datos["Firmantes_Emp"] = $array;
		}
		//Fin

		$generar = new generar();

		$respuesta = array();
		$respuesta = $generar->GenerarDocumento($datos);

		if( $respuesta['estado'] ){
			
			$idDocumento = '';
			$idDocumento = $respuesta['data'];
			$this->mensajeOK = $respuesta['mensaje']." con el Nro de Documento: <b>".$idDocumento."</b>";
			$this->verdocumento($idDocumento);

		}else{

			$this->mensajeError = $respuesta['mensaje'];
			$this->agregar();
		}
	}

	//Ver documento actualizado
	private function verdocumento($idDocumento)
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

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
		
		$nombrearchivo =	$nomarchtmp.".".$extension;
			
		$subcarpeta = "./".CARPETA."/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
		
		$rutayarch  = "";
		$ruta = "./".CARPETA."/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0] = $datos; 
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_documentos_PorFicha_documento.html');				
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>


