<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosBD.php");

include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

require_once('includes/tcpdf/examples/lang/ita.php');
require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/examples/tcpdf_include.php');
require_once('config.php');

// con librerias dompdf
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
// fin


// creamos la instacia de esta clase
$page = new documentos();


class documentos {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosBD;
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
	private $ordinal = array(); //Ordonal de Tabla
	private $tipo_con = 0;
	private $band;

	//Array de los modelos de contrato
	private $datos_contrato_marco;
	private $datos_anexo;
	private $datos_renting;

	//Iconos 
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $verde1 		= '<div style="text-align: center;" title="Datos completos">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Datos completos" 		alt="En el plazo"></i></div>';
	private $amarillo1	= '<div style="text-align: center;" title="Datos faltantes">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Datos faltantes" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';
	
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

		$this->opcion = "Documentos ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Documentos</li>";
		
		// instanciamos del manejo de tablas
		$this->documentosBD = new documentosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->documentosBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			$this->listado();
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
			case "BUSCAR_EMPRESA":
				$this->buscar_empresa();
				break;
			case "BUSCAR_CLIENTE":
				$this->buscar_cliente();
				break;
			case "BUSCAR_EJECUTIVO":
				$this->buscar_ejecutivo();
				break;
			case "BUSCAR_SUPERVISOR":
				$this->buscar_supervisor();
				break;
			case "BUSCAR_PLANTILLA":
				$this->buscar_plantilla();
				break;
			case "BUSCAR_NOTARIA":
				$this->buscar_notaria();
				break;
			case "BUSCAR_PROYECTO":
				$this->buscar_proyecto();
				break;
			case "BUSCAR_PROYECTO_CM":
				$this->buscar_proyecto_cm();
				break;
			case "BUSCAR_PROVEEDOR":
				$this->buscar_proveedor();
				break;
			case "SIGUIENTE":
				$this->siguiente();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "ATRAS_CM":
				$this->atras_cm();
				break;
			case "FIRMANTES":
				$this->firmantes();
				break;
			case "FIRMANTES_ANEXO":
				$this->firmantes_anexo();
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
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
					// enviamos los datos del formulario a guardar
					if ($this->documentosBD->agregar($_REQUEST))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						//Imprimir la plantillas
						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->documentosBD->mensajeError;
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					//Imprimir la plantillas
					$this->pagina->modificar();
					break;

				case "BUSCAR": 
					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt2 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();

					//Completamos los datos de la Empresa
					$emp = array ("RutEmpresa" => $_REQUEST["RutEmpresa_Gama"]);
					$this->documentosBD->obtenerRazonSocial($emp,$dt);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los datos de la Empresa Cliente 
					$cli = array ("RutEmpresaC" => $_REQUEST["RutEmpresa"]); 
					$this->documentosBD->obtenerRazonSocialC($cli,$dt1);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos Firmantes la Empresa
					$this->documentosBD->obtenerFirmantes($emp,$dt3);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos Firmantes la Cliente
					$this->documentosBD->obtenerFirmantesC($cli,$dt4);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos si la Empresa tiene Plantilla
					$this->documentosBD->listadoPlantillas($emp,$dt5);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos 

					if( $_REQUEST["modelo_contrato"] != 0 ){
						$this->documentosBD->obtenerModeloContrato($_REQUEST,$dt4);
						$this->mensajeError.=$this->documentosBD->mensajeError;
					}
					else{
						$this->pagina->agregarDato("idMC",0);
						$this->pagina->agregarDato("DescripcionMC","(Seleccione)");
					}
				
					//Si Empresa no tiene firmantes
					if( count($dt3->data) == 0 ){
						$FirEmpresa = 1 ;
					}
					else{
						$FirEmpresa = 0;
					}
					//Si Empresa no tiene Plantillas asociadas 
					if( count($dt5->data) == 0 ){
						$PlaEmpresa = 1;
					}
					else{
						$PlaEmpresa = 0;
					}
					
					if ( count($dt1->data) > 0 ){
						if ( $dt1->data[0]["TipoEmpresa"] == 2 ){
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
							$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
						}
						else{
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
							$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
						}
											
						if( $dt4->data[0]["idMC"] > 0 ){
							$dt->data[0]["idMC"] = $dt4->data[0]["idMC"];
						    $dt->data[0]["DescripcionMC"] = $dt4->data[0]["DescripcionMC"];
						    $this->pagina->agregarDato("idMC",$dt4->data[0]["idMC"]);
						    $this->pagina->agregarDato("DescripcionMC",$dt4->data[0]["DescripcionMC"]);
						}
						//Si la Empresa Cliente no tiene firmantes 
						if( count($dt4->data) == 0 ){
							$FirEmpresaC = 1;
						}
						else{
							$FirEmpresaC = 0;
						}
					}
					$dt->data[0]["fecha"] = $_REQUEST["fecha"];
					$dt->data[0]["FirEmpresa"] = $FirEmpresa;
					$dt->data[0]["FirEmpresaC"] = $FirEmpresaC;
					$dt->data[0]["PlaEmpresa"] = $PlaEmpresa;

					//Consultamos las empresas clientes 
					$this->documentosBD->listado($dt2);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Reasignar resultados
					$formulario = $dt->data; 
					$formulario[0]["Modelo_Contrato"] = $dt2->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
					$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar.html');
					return;

				case "BUSCAR_1": 
					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();

					//Completamos los datos de la Empresa
					$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los datos de la Empresa Cliente 
					$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos si la Empresa tiene Plantilla
					$this->documentosBD->listadoPlantillas($_REQUEST,$dt5);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscamos Firmantes la Empresa
					$this->documentosBD->obtenerFirmantes($_REQUEST,$dt3);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos 

					if( $_REQUEST["modelo_contrato"] != 0 ){
						$this->documentosBD->obtenerModeloContrato($_REQUEST,$dt4);
						$this->mensajeError.=$this->documentosBD->mensajeError;
					}
					else{
						$this->pagina->agregarDato("idMC",0);
						$this->pagina->agregarDato("DescripcionMC","(Seleccione)");
					}
				
					//Si Empresa no tiene firmantes
					if( count($dt3->data) == 0 ){
						$FirEmpresa = 1 ;
					}
					else{
						$FirEmpresa = 0;
					}

					//Si Empresa no tiene Plantillas asociadas 
					if( count($dt5->data) == 0 ){
						$PlaEmpresa = 1;
					}
					else{
						$PlaEmpresa = 0;
					}
					
					if ( count($dt1->data) > 0 ){

						if ( $dt1->data[0]["TipoEmpresa"] == 2 ){
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
							$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
						}
						else{
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
							$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
						}

						if( $dt4->data[0]["idMC"] > 0 ){
							$dt->data[0]["idMC"] = $dt4->data[0]["idMC"];
						    $dt->data[0]["DescripcionMC"] = $dt4->data[0]["DescripcionMC"];
						    $this->pagina->agregarDato("idMC",$dt4->data[0]["idMC"]);
						    $this->pagina->agregarDato("DescripcionMC",$dt4->data[0]["DescripcionMC"]);
						}
						//Si la Empresa Cliente no tiene firmantes 
						if( count($dt4->data) == 0 ){
							$FirEmpresaC = 1;
						}
						else{
							$FirEmpresaC = 0;
						}
					}
					$dt->data[0]["fecha"] = $_REQUEST["fecha"];
					$dt->data[0]["FirEmpresa"] = $FirEmpresa;
					$dt->data[0]["FirEmpresaC"] = $FirEmpresaC;
					$dt->data[0]["PlaEmpresa"] = $PlaEmpresa;

					//Consultamos las empresas clientes 
					$this->documentosBD->listado($dt2);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Reasignar resultados
					$formulario = $dt->data; 
					$formulario[0]["Modelo_Contrato"] = $dt2->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
					$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar.html');
					return;

				case "BUSCAR_E_S":
		
					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt2 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();

					//Completamos los datos de la Empresa
					$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
					$this->mensajeError.=$this->documentosBD->mensajeError;
					
					//Completamos los datos de la Empresa Cliente 
					$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					if( $_REQUEST["modelo_contrato"] != 0 ){
						$this->documentosBD->obtenerModeloContrato($_REQUEST,$dt4);
						$this->mensajeError.=$this->documentosBD->mensajeError;
					}
					else{
						$this->pagina->agregarDato("idMC",0);
						$this->pagina->agregarDato("DescripcionMC","(Seleccione)");
					}

					if( $_REQUEST["FormasPago"] != 0 ){
						$this->documentosBD->obtenerFormaPago($_REQUEST,$dt5);
						$this->mensajeError.=$this->documentosBD->mensajeError;
					}
					else{
						$this->pagina->agregarDato("idFormaPago",0);
						$this->pagina->agregarDato("FormaPago","(Seleccione)");
					}

					if ( count($dt1->data) > 0 ){

						if ( $dt1->data[0]["TipoEmpresa"] == 2 ){
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
							$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
						}
						else{
							$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
							$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
						    $dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
						}
						$dt->data[0]["fecha"] = $_REQUEST["fecha"];
						$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];

						if( $dt4->data[0]["idMC"] > 0 ){
							$dt->data[0]["idMC"] = $dt4->data[0]["idMC"];
						    $dt->data[0]["DescripcionMC"] = $dt4->data[0]["DescripcionMC"];
						    $this->pagina->agregarDato("idFormaPago",$dt5->data[0]["idFormaPago"]);
						    $this->pagina->agregarDato("FormaPago",$dt5->data[0]["FormaPago"]);
						}
					}

					//Completamos los datos del Ejecutivo 
					if( $_REQUEST["RutEjecutivo"] != ""){
						$this->documentosBD->obtenerNombreEjecutivo($_REQUEST,$dt2);
						$this->mensajeError.=$this->documentosBD->mensajeError;
						$dt->data[0]["NombreEje"] = $dt2->data[0]["nombre"];
						$dt->data[0]["ApellidoEje"] = $dt2->data[0]["appaterno"]." ".$dt2->data[0]["apmaterno"];
						$dt->data[0]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"]; 
					}
					//Completamos los datos del Supervisor
					if( $_REQUEST["RutSupervisor"] != ""){
						$this->documentosBD->obtenerNombreSupervisor($_REQUEST,$dt3);
						$this->mensajeError.=$this->documentosBD->mensajeError;
						$dt->data[0]["NombreSup"] = $dt3->data[0]["nombre"];
						$dt->data[0]["ApellidoSup"] = $dt3->data[0]["appaterno"]." ".$dt2->data[0]["apmaterno"];
						$dt->data[0]["RutSupervisor"] = $_REQUEST["RutSupervisor"]; 
					}
					$dt->data[0]["idFormaPago"] = $_REQUEST["idFormaPago"];
					$dt->data[0]["fechaInicio"] = $_REQUEST["fechaInicio"];
					$dt->data[0]["fechaFin"] = $_REQUEST["fechaFin"];

					//Consultamos las empresas clientes 
					$this->documentosBD->listado($dt2);

					//Completamos los datos de la Empresa Cliente 
					$this->documentosBD->listadoFormasPago($dt4);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Reasignar resultados
					$formulario = $dt->data;
					$formulario[0]["FormasPago"] = $dt4->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					//Se imprime la plantilla
					$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_CM.html');
					return;

				case "BUSCAR_P":

					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt2 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();
					$dt6 = new DataTable();

					//Completamos los datos de la Plantilla
					$this->documentosBD->obtenerPlantilla($_REQUEST,$dt2);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los datos de la Plantilla
					$this->documentosBD->obtenerTipoFirma($_REQUEST,$dt4);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los tipo de Firmas
					$this->documentosBD->listadoTipoFirmas($dt3);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los datos de la Empresa
					$this->documentosBD->obtenerRazonSocialN($_REQUEST,$dt5);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Consultar si el flujo tiene Aval
					$this->documentosBD->obtenerAval($dt2->data[0],$dt6);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					$dt->data[0]["idPlantilla"] = $dt2->data[0]["idPlantilla"];
					$dt->data[0]["idWF"] = $dt2->data[0]["idWF"];
					$dt->data[0]["idTipoDoc"] = $dt2->data[0]["idTipoDoc"];
					$dt->data[0]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
					$dt->data[0]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"];
					$dt->data[0]["RutSupervisor"] = $_REQUEST["RutSupervisor"];
					$dt->data[0]["fecha"] = $_REQUEST["fecha"];
					$dt->data[0]["fechaInicio"] = $_REQUEST["fechaInicio"];
				    $dt->data[0]["fechaFin"] = $_REQUEST["fechaFin"];
					$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
					$dt->data[0]["FormasPago"] = $_REQUEST["FormasPago"];
					$dt->data[0]["idPla"] = $dt2->data[0]["idPlantilla"]." ".strip_tags( substr($dt2->data[0]["Descripcion_Pl"], 0, 60));
					$dt->data[0]["Notaria"] = $dt5->data[0]["RutEmpresaN"]." ".$dt5->data[0]["RazonSocialN"];
					$dt->data[0]["idContrato"] = $_REQUEST["idContrato"];

					//Verificar si el Flujo tiene Notaria
					if( strpos($dt2->data[0]["NombreWF"],"Notario") > 0){
						$dt->data[0]["not"] = 1;
					}else{
						$dt->data[0]["not"] = 0;
					}

					//Si el flujo de firmas tiene aval 
					if ( $dt6->data[0]["Aval"] == 0 ){
						$dt->data[0]["aval"] = 0;
					}
					else{
						$dt->data[0]["aval"] = 1;
					}

					//Reasignar resultados
					$formulario = $dt->data;
					$formulario[0]["TipoFirmas"] = $dt3->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("idTipoFirma",$dt4->data[0]["idTipoFirma"]);
					$this->pagina->agregarDato("Descripcion",$dt4->data[0]["Descripcion"]);
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);

					//Nos vamos a firmantes
					$this->firmantes();
					return;

				case "BUSCAR_PRO":
		
					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt2 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();
					$dt6 = new DataTable();
					$dt7 = new DataTable();

					//Completamos los datos de la Plantilla
					$this->documentosBD->obtenerDetalle($_REQUEST,$dt1);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscar nombre de Monedas
					$this->documentosBD->obtenerMoneda($dt1->data[0],$dt2);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscar nombre de Monedas
					$this->documentosBD->obtenerMoneda_1($dt1->data[0],$dt3);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscar el listado de equipamientos 
					$this->documentosBD->listadoEquipamiento($dt4);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Buscar el listado de Deducibles
					$this->documentosBD->listadoDeducibles($dt5);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Completamos los datos de las formas de Pago
					$this->documentosBD->listadoFormasPago($dt7);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					//Consultar fecha de ceracion del contrato marco
					if( $_REQUEST["idContrato"] != "" ){
						$this->documentosBD->obtenerDocumento($_REQUEST, $dt6);
						$this->mensajeError.=$this->documentosBD->mensajeError;
					}

					$dt->data[0]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
					$dt->data[0]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
					$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
					$dt->data[0]["proyectos_emp"] = $_REQUEST["proyectos_emp"];
					$dt1->data[0]["NombreMoneda_Tarifa"] = $dt2->data[0]["NombreMoneda"];
					$dt1->data[0]["NombreMoneda_KmsExceso"] = $dt3->data[0]["NombreMoneda"];
					$dt->data[0]["idProyecto"] = $dt1->data[0]["idProyecto"];
					$dt->data[0]["band"] = $_REQUEST["band"];
					$dt->data[0]["fechaFirma"] = $dt6->data[0]["FechaCreacion"];

					//Reasignar resultados
					$formulario = $dt->data;
					$formulario[0]["datos_anexo"] = $dt1->data;
					$formulario[0]["listado_equipamientos"] = $dt4->data;
					$formulario[0]["listado_deducibles"] = $dt5->data;
					$formulario[0]["FormasPago"] = $dt7->data;
				
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					// se imprime el formulario
					$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_A.html');
					
					return;
				case "BUSCAR_PROVEEDOR":

					//Inicializamos las variables
					$dt = new DataTable();
			
					//Buscar el listado de Deducibles
					$array = array ( "RutEmpresa" => $_REQUEST["RutProveedor"]);
					$this->documentosBD->obtenerRazonSocial($array, $dt);
					$this->mensajeError.=$this->documentosBD->mensajeError;

					$dt->data[0]["RutProveedor"] = $dt->data[0]["RutEmpresa"];
					$dt->data[0]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
					$dt->data[0]["RutEmpresa"] = $_REQUEST["RutEmpresaGama"];
					$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
					$dt->data[0]["fecha"] = $_REQUEST["fecha"];
		
					//Reasignar resultados
					$formulario = $dt->data;
					
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					// se imprime el formulario
					$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_F.html');
					
					return;
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		//Opcion de atras 
	    if( $_REQUEST["RutEmpresa"] != "" ){

			//Inicializamos las variables
			$dt = new DataTable();
			$dt1 = new DataTable();
			$dt3 = new DataTable();
			$dt4 = new DataTable();

			//Completamos los datos de la Empresa
			$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;
					
			//Completamos los datos de la Empresa Cliente 
			$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
			$this->mensajeError.=$this->documentosBD->mensajeError;

			//Completamos los datos de los Modelos de Contrato
			$this->documentosBD->listado($d2);
			$this->mensajeError.=$this->documentosBD->mensajeError;

			if( $_REQUEST["modelo_contrato"] != 0 ){
				$this->documentosBD->obtenerModeloContrato($_REQUEST,$dt4);
				$this->mensajeError.=$this->documentosBD->mensajeError;
			}
			else{
				$this->pagina->agregarDato("idMC",0);
				$this->pagina->agregarDato("DescripcionMC","(Seleccione)");
			}
					
			if ( count($dt1->data) > 0 ){
				if ( $dt1->data[0]["TipoEmpresa"] == 2 ){
					$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
					$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
					$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
				}
				else{
					$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
					$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
					$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
				}
				$dt->data[0]["fecha"] = $_REQUEST["fecha"];

				if( $dt4->data[0]["idMC"] > 0 ){
					$dt->data[0]["idMC"] = $dt4->data[0]["idMC"];
				    $dt->data[0]["DescripcionMC"] = $dt4->data[0]["DescripcionMC"];
				    $this->pagina->agregarDato("idMC",$dt4->data[0]["idMC"]);
				    $this->pagina->agregarDato("DescripcionMC",$dt4->data[0]["DescripcionMC"]);
				}
			}

			//Consultamos las empresas clientes 
			$this->documentosBD->listado($dt2);
			$this->mensajeError.=$this->documentosBD->mensajeError;
			
			//Reasignar resultados
			$formulario = $dt->data;
			$formulario[0]["Modelo_Contrato"] = $dt2->data;

			$this->pagina->agregarDato("formulario",$formulario);
			//Pasamos los mensajes a la pagina 
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			// se imprime el formulario
			$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar.html');
			return;
	    }

		//Inicialiamos la variable de tipo Tabla 
		$dt = new DataTable();
		$dt1 = new DataTable();

		if( $_REQUEST["modelo_contrato"] == 0 ){
			$this->pagina->agregarDato("idMC","0");
			$this->pagina->agregarDato("DescripcionMC","(Seleccione)");
		}
			
		//Consultamos las empresas clientes 
		$this->documentosBD->listado($dt);
		//Reasignamos resultado a variable
		$formulario[0]["Modelo_Contrato"]=$dt->data;
		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar.html');
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		//Consultamos las empresas clientes 
		$this->documentosBD->listado($dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Reasignamos resultado a variable 
		$formulario[0]["Modelo_Contrato"] = $dt->data;
		$formulario[0]["fecha"] = date("d-m-Y H:i:s");
		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar.html');
	}

	//Seleccionar la Empresa a la que pertenece 
	private function buscar_empresa(){

		//Declarar e instanciar variables
		$dt = new DataTable();

		//Listado de Empresas Disponibles 
		$_REQUEST["TipoEmpresa"] = 1;
		//Buscar todas las Empresas disponibles
		$this->documentosBD->listadoEmpresas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
			}
		}
		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarEmpresa.html');
	}

	//Seleccionar los Clientes a la que pertenece 
	private function buscar_cliente(){

		//Declarar e instanciar variables
		$dt = new DataTable();

		$_REQUEST["TipoEmpresa"] = 2;
		//Buscar todas las Empresas disponibles
		$this->documentosBD->listadoClientesDiferente($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa_Gama"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
			}
		}
		
		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarClientes.html');
	}

	//Seleccionar el Proyecto del cual quiere generar el documento
	private function buscar_proyecto(){
		
		$this->band = $_REQUEST["band"];

		//Declarar e instanciar variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Listado de Empresas Disponibles 
		$_REQUEST["TipoEmpresa"] = 1;

		//Buscar todas los Proyectos de cada Cliente 
		$this->documentosBD->obtenerListadoProyectos($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["proyectos_emp"] = $_REQUEST["proyectos_emp"];
				$dt->data[$key]["band"] = $_REQUEST["band"];

				$this->documentosBD->obtenerSubproyectoCreado($dt->data[$key], $dt2);
				$this->mensajeError.=$this->documentosBD->mensajeError;
				if ( count($dt2->data) > 0 ){
					$dt->data[$key]["detalle"] = $this->verde1;
				}
				else{
					$dt->data[$key]["detalle"] = $this->amarillo1;
				}
			}
		}

		//Reasignar resultado
		$formulario = $dt->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarProyecto.html');
	}

	//Muestra los Proyectos de un Cliente 
	private function buscar_proyecto_cm(){
		
		//Declarar e instanciar variables
		$dt = new DataTable();
		$dt1 = new DataTable();

		//Buscar todas los Proyectos de cada Cliente 
		$this->documentosBD->obtenerListadoProyectos($_REQUEST,$dt1);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		$dt->data[0]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
		$dt->data[0]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
		$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
		$dt->data[0]["proyectos_emp"] = $_REQUEST["proyectos_emp"];

		//Reasignar resultado
		$formulario = $dt->data;
		$formulario[0]["listado"] = $dt1->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarProyecto_CM.html');
	}

	//Seleccionar el Proveedor 
	private function buscar_proveedor(){
		
		//Declarar e instanciar variables
		$dt = new DataTable();
		$dt1 = new DataTable();

		//Listado de Empresas Disponibles 
		$_REQUEST["TipoEmpresa"] = 4;

		//Buscar todas los Proyectos de cada Cliente 
		$this->documentosBD->listadoEmpresas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresaGama"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];

			}
		}
		//Reasignar resultado
		$formulario = $dt->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarProveedor.html');
	}

	//Seleccionar la plantilla correspondiente al modelo de Contrato 
	private function siguiente(){

		switch ($_REQUEST["modelo_contrato"])
		{
			case 1: //Contrato Marco 
				$this->contrato_marco();
				break;
			case 2: //Anexo
				$this->anexo();
				break;
			case 3: //Contrato de Renting 
				$this->contrato_renting();
				break;
			case 4: //Condiciones especiales
				$this->condiciones_especiales();
				break;
			case 5: //Contrato Financiero-Operativo
				$this->contrato_financiero_operativo();
				break;
		}
	}

	//Volver a la pantalla adecuada
	private function atras(){
		$this->agregar();
	}

	//Volver a la pantalla adecuada
	private function atras_cm(){
		$this->contrato_marco();
	}

	//Creacion de Contrato Marco 
	public function contrato_marco(){
		//Tipo de Contrato
		$this->tipo_con = 1;

		//Inicializamos las variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();

		//Completamos los datos de la Empresa
		$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Completamos los datos de la Empresa Cliente 
		//$cli = array( "RutEmpresa")
		$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de las formas de Pago
		$this->documentosBD->listadoFormasPago($dt2);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Consultar si la empresa tiene Proyectos 
		$this->documentosBD->obtenerListadoProyectos($_REQUEST, $dt3);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		if ( count($dt1->data) > 0 ){

			if ( $dt1->data[0]["TipoEmpresa"] == 2 ){
				$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
				$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
				$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
			}
			else{
				$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
				$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
				$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
			}
			
			$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
			$dt->data[0]["fecha"] = $_REQUEST["fecha"];
			$dt->data[0]["activar_pro"] = count($dt3->data);
		}

		//Reasignar resultados
		$formulario = $dt->data;
		$formulario[0]["FormasPago"] = $dt2->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_CM.html');
	}


	//Creacion de Contrato Marco 
	public function anexo(){
		//Tipo de Contrato
		$this->tipo_con = 2;

		//Inicializamos las variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();

		//Completamos los datos de la Empresa
		$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de la Empresa Cliente 
		$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de las formas de Pago
		$this->documentosBD->listadoFormasPago($dt2);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		$this->documentosBD->obtenerListadoProyectos($_REQUEST,$dt3);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscamos el equipamiento
		$this->documentosBD->listadoEquipamiento($dt4);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de las formas de Pago
		$this->documentosBD->listadoFormasPago($dt6);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( count($dt3->data) == 0 ){
			$proyectos_emp = 1;
		}else{
			$proyectos_emp = 0;
		}
		
		$dt5->data[0]["RutEmpresa"] = $dt->data[0]["RutEmpresa"];
		
		if( $dt1->data[0]["TipoEmpresa"] == 2 ){
			$dt5->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
			$dt5->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
			$dt5->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
		}
		else{
			$dt5->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
			$dt5->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
			$dt5->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
		}
		$dt5->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
		$dt5->data[0]["fecha"] = $_REQUEST["fecha"];
		$dt5->data[0]["proyectos_emp"] = $proyectos_emp;
		$dt5->data[0]["band"] = 1;

		//Reasignar resultados
		$formulario = $dt5->data;
		$formulario[0]["datos_anexo"] = $dt->data;
		$formulario[0]["listado_equipamientos"] = $dt4->data;
		$formulario[0]["FormasPago"] = $dt6->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_A.html');
	}

	//Creacion de Contrato Marco 
	public function contrato_renting(){

		//Tipo de Contrato
		$this->tipo_con = 3;

		//Inicializamos las variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Completamos los datos de la Empresa
		$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Completamos los datos de la Empresa Cliente 
		$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt1);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de las formas de Pago
		$this->documentosBD->listadoFormasPago($dt2);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		if ( count($dt->data) > 0 ){
			
			if( $dt1->data[0]["TipoEmpresa"] == 2 ){
				$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
				$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresaC"];
				$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocialC"];
			}else{
				$dt->data[0]["TipoEmpresa"] = $dt1->data[0]["TipoEmpresa"];
				$dt->data[0]["RutEmpresaC"] = $dt1->data[0]["RutEmpresa"];
				$dt->data[0]["RazonSocialC"] = $dt1->data[0]["RazonSocial"];
			}
			
			$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
			$dt->data[0]["fecha"] = $_REQUEST["fecha"];
		}

		//Reasignar resultados
		$formulario = $dt->data;
		$formulario[0]["FormasPago"] = $dt2->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_CR.html');
	}

	//Contrato de Condiciones Generales
	private function condiciones_especiales(){
		//Nos vamos a firmantes
		$this->firmantes();
	}

	//Contrato Financiero - Operativo 
	private function contrato_financiero_operativo(){

		//Tipo de Contrato
		$this->tipo_con = 5;

		//Inicializamos las variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Completamos los datos de las formas de Pago
		$this->documentosBD->listadoFormasPago($dt2);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		$dt->data[0]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
		$dt->data[0]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
		$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
		$dt->data[0]["fecha"] = $_REQUEST["fecha"];

		//Reasignar resultados
		$formulario = $dt->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregar_F.html');
	}

	//Ir a la pantalla en donde se listan todos los Ejecutivos 
	private function buscar_ejecutivo()
	{  
		$dt = new DataTable();
		//Listado de Ejecutivos
		$this->documentosBD->listadoEjecutivos($dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if ( count($dt->data) ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["idFormaPago"] = $_REQUEST["idFormaPago"];
				$dt->data[$key]["RutEjecutivo"] = $dt->data[$key]["RutEjecutivo"];
				$dt->data[$key]["RutSupervisor"] = $_REQUEST["RutSupervisor"];
				$dt->data[$key]["fechaInicio"] = $_REQUEST["fechaInicio"];
				$dt->data[$key]["fechaFin"] = $_REQUEST["fechaFin"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
				$dt->data[$key]["TipoEmpresa"] = $_REQUEST["TipoEmpresa"];
			}
		}
		$formulario = $dt->data;
		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarEjecutivos.html');
	}


	//Ir a la pantalla en donde se listan todos los Ejecutivos 
	private function buscar_supervisor()
	{  
		$dt = new DataTable();
		//Listado de Ejecutivos
		$this->documentosBD->listadoSupervisor($dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;	

		if ( count($dt->data) ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["idFormaPago"] = $_REQUEST["idFormaPago"];
				$dt->data[$key]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"];
				$dt->data[$key]["RutSupervisor"] = $dt->data[$key]["RutSupervisor"];
				$dt->data[$key]["fechaInicio"] = $_REQUEST["fechaInicio"];
				$dt->data[$key]["fechaFin"] = $_REQUEST["fechaFin"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
				$dt->data[$key]["TipoEmpresa"] = $_REQUEST["TipoEmpresa"];
			}
		}
		$formulario = $dt->data;
		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarSupervisores.html');
	}

	//Seleccionar la Empresa a la que pertenece 
	public function buscar_plantilla(){

		//Declarar e instanciar variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Listado de Empresas Disponibles 
		$this->documentosBD->listadoPlantillas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Quitar las etiquetas de HTML antes de llevar al listado
		if ( count($dt->data) ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["RutEmpresaN"] = $_REQUEST["RutEmpresaN"];
				$dt->data[$key]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"];
				$dt->data[$key]["RutSupervisor"] = $_REQUEST["RutSupervisor"];
				$dt->data[$key]["fechaInicio"] = $_REQUEST["fechaInicio"];
				$dt->data[$key]["fechaFin"] = $_REQUEST["fechaFin"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
				$dt->data[$key]["TipoFirmas"] = $_REQUEST["TipoFirmas"];
				$dt->data[$key]["pro"] = $_REQUEST["pro"];
				$dt->data[$key]["activar_pro"] = $_REQUEST["activar_pro"];
				
				if($dt->data[$key]["Aprobado"] == 1){
					$dt->data[$key]["Aprobado"] = $this->verde;
					$dt->data[$key]["aprob"] = 1;
				}
				else{
					$dt->data[$key]["Aprobado"] = $this->amarillo;
					$dt->data[$key]["aprob"] = 0;
				}

				$this->documentosBD->contarClausulas($dt->data[$key], $dt2);
				$this->mensajeError .= $this->documentosBD->mensajeError;
				//Cantidad de Clausulas
				$dt->data[$key]["Cant"] = $dt2->data[0]["total"];

				if( $_REQUEST["idProyecto"] != "" ){//Anexo 
					$dt->data[$key]["idProyecto"] = $_REQUEST["idProyecto"];
					$dt->data[$key]["fechaFirma"] = $_REQUEST["fechaFirma"];
					$dt->data[$key]["Marca"] = $_REQUEST["Marca"];
					$dt->data[$key]["Modelo"] = $_REQUEST["Modelo"];
					$dt->data[$key]["CiudadEntrega"] = $_REQUEST["CiudadEntrega"];
					$dt->data[$key]["CiudadOperacion"] = $_REQUEST["CiudadOperacion"];
					$dt->data[$key]["CiudadDevolucion"] = $_REQUEST["CiudadDevolucion"];
					$dt->data[$key]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
					$dt->data[$key]["FechaInicio"] = $_REQUEST["FechaInicio"];
					$dt->data[$key]["FechaFinal"] = $_REQUEST["FechaFinal"];
					$dt->data[$key]["Tarifa"] = $_REQUEST["Tarifa"];
					$dt->data[$key]["idMoneda_Tarifa"] = $_REQUEST["idMoneda_Tarifa"];
					$dt->data[$key]["idMoneda_KmsExceso"] = $_REQUEST["idMoneda_KmsExceso"];
					$dt->data[$key]["KmsExceso"] = $_REQUEST["KmsExceso"];
					$dt->data[$key]["KmsContratados"] = $_REQUEST["KmsContratados"];
					$dt->data[$key]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
					$dt->data[$key]["FrecuenciaMantencion"] = $_REQUEST["FrecuenciaMantencion"];
					$dt->data[$key]["FrecuenciaCambio"] = $_REQUEST["FrecuenciaCambio"];
					$dt->data[$key]["Cantidad"] = $_REQUEST["Cantidad"];
					$dt->data[$key]["Porcentaje"] = $_REQUEST["Porcentaje"];
					$dt->data[$key]["Propuesta"] = $_REQUEST["Propuesta"];
					$dt->data[$key]["FechaPropuesta"] = $_REQUEST["FechaPropuesta"];
					$dt->data[$key]["GPS"] = $_REQUEST["GPS"];
					$dt->data[$key]["seleccion"] = $_REQUEST["seleccion"];
					$dt->data[$key]["seleccion_ded"] = $_REQUEST["seleccion_ded"];
					$dt->data[$key]["rut_coordinador"] = $_REQUEST["rut_coordinador"];
					$dt->data[$key]["nombre_coordinador"] = $_REQUEST["nombre_coordinador"];
				}
				if ( $_REQUEST["Patente"] != "" ){ //Contrato Renting 

					$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
					$dt->data[$key]["Marca"] = $_REQUEST["Marca"];
					$dt->data[$key]["Modelo"] = $_REQUEST["Modelo"];
					$dt->data[$key]["Patente"] = $_REQUEST["Patente"];
					$dt->data[$key]["Color"] = $_REQUEST["Color"];
					$dt->data[$key]["VIN"] = $_REQUEST["VIN"];
					$dt->data[$key]["AnnoVehiculo"] = $_REQUEST["AnnoVehiculo"];
					$dt->data[$key]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
					$dt->data[$key]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
					$dt->data[$key]["FechaInicio"] = $_REQUEST["FechaInicio"];
					$dt->data[$key]["FechaFinal"] = $_REQUEST["FechaFinal"];
					$dt->data[$key]["FechaPie"] = $_REQUEST["FechaPie"];
					$dt->data[$key]["FechaPago"] = $_REQUEST["FechaPago"];
					$dt->data[$key]["CuotaPie"] = $_REQUEST["CuotaPie"];
					$dt->data[$key]["MontoCuota"] = $_REQUEST["MontoCuota"];
					$dt->data[$key]["Exceso"] = $_REQUEST["Exceso"];
					$dt->data[$key]["KmsExceso"] = $_REQUEST["KmsExceso"];
					$dt->data[$key]["idRentaMens"] = $_REQUEST["idRentaMens"];
					$dt->data[$key]["RentaMens"] = $_REQUEST["RentaMens"];
				}

				if( $_REQUEST["RutProveedor"] != "" ){ //Contrato Financiero Operativo

					$dt->data[$key]["RutProveedor"] = $_REQUEST["RutProveedor"];
					$dt->data[$key]["FechaInicioPago"] = $_REQUEST["FechaInicioPago"];
					$dt->data[$key]["DetalleBienes"] = $_REQUEST["DetalleBienes"];
					$dt->data[$key]["Moneda"] = $_REQUEST["Moneda"];
					$dt->data[$key]["MontoAdquisicion"] = $_REQUEST["MontoAdquisicion"];
					$dt->data[$key]["CantTotal"] = $_REQUEST["CantTotal"];
					$dt->data[$key]["CantRentas"] = $_REQUEST["CantRentas"];
					$dt->data[$key]["ValorBallon"] = $_REQUEST["ValorBallon"];
					$dt->data[$key]["ValorCompra"] = $_REQUEST["ValorCompra"];
					$dt->data[$key]["DiaPagoMensual"] = $_REQUEST["DiaPagoMensual"];
					$dt->data[$key]["DuracionContrato"] = $_REQUEST["DuracionContrato"];
					$dt->data[$key]["FechaPrepago"] = $_REQUEST["FechaPrepago"];
					$dt->data[$key]["ValorAsegurable"] = $_REQUEST["ValorAsegurable"];
					$dt->data[$key]["ValorIguales"] = $_REQUEST["ValorIguales"];
				}
			}
		}
		
		$formulario = $dt->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("idTipoFirma",$dt1->data["idTipoFirma"]);
		$this->pagina->agregarDato("Descripcion",$dt1->data["Descripcion"]);
		$this->pagina->agregarDato("listado",$formulario);
		
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarPlantillas.html');
	}

	//Seleccionar la Notaria disponibles
	private function buscar_notaria(){

		//Declarar e instanciar variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Listado de Empresas Disponibles 
		$_REQUEST["TipoEmpresa"] = 3;
		//Buscar todas las Empresas disponibles
		$this->documentosBD->listadoEmpresas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Quitar las etiquetas de HTML antes de llevar al listado
		if ( count($dt->data) ){
			foreach ($dt->data as $key => $value) {

				$dt->data[$key]["Titulo_Pl"] = $_REQUEST["Titulo_Pl"];
				$dt->data[$key]["idWF"] = $_REQUEST["idWF"];
				$dt->data[$key]["idTipoDoc"] = $_REQUEST["idTipoDoc"];
				$dt->data[$key]["NombreTipoDoc"] = $_REQUEST["NombreTipoDoc"];
				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"];
				$dt->data[$key]["RutSupervisor"] = $_REQUEST["RutSupervisor"];
				$dt->data[$key]["fechaInicio"] = $_REQUEST["fechaInicio"];
				$dt->data[$key]["fechaFin"] = $_REQUEST["fechaFin"];
				$dt->data[$key]["fecha"] = $_REQUEST["fecha"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
				$dt->data[$key]["TipoFirmas"] = $_REQUEST["TipoFirmas"];
				$dt->data[$key]["idPlantilla"] = $_REQUEST["idPlantilla"];
				$dt->data[$key]["pro"] = $_REQUEST["pro"];
				$dt->data[$key]["aval"] = $_REQUEST["aval"];


				if( $_REQUEST["idProyecto"] != "" ){ //Anexo
					$dt->data[$key]["idProyecto"] = $_REQUEST["idProyecto"];
					$dt->data[$key]["fechaFirma"] = $_REQUEST["fechaFirma"];
					$dt->data[$key]["Marca"] = $_REQUEST["Marca"];
					$dt->data[$key]["Modelo"] = $_REQUEST["Modelo"];
					$dt->data[$key]["CiudadEntrega"] = $_REQUEST["CiudadEntrega"];
					$dt->data[$key]["CiudadOperacion"] = $_REQUEST["CiudadOperacion"];
					$dt->data[$key]["CiudadDevolucion"] = $_REQUEST["CiudadDevolucion"];
					$dt->data[$key]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
					$dt->data[$key]["FechaInicio"] = $_REQUEST["FechaInicio"];
					$dt->data[$key]["FechaFinal"] = $_REQUEST["FechaFinal"];
					$dt->data[$key]["Tarifa"] = $_REQUEST["Tarifa"];
					$dt->data[$key]["idMoneda_Tarifa"] = $_REQUEST["idMoneda_Tarifa"];
					$dt->data[$key]["idMoneda_KmsExceso"] = $_REQUEST["idMoneda_KmsExceso"];
					$dt->data[$key]["KmsExceso"] = $_REQUEST["KmsExceso"];
					$dt->data[$key]["KmsContratados"] = $_REQUEST["KmsContratados"];
					$dt->data[$key]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
					$dt->data[$key]["FrecuenciaMantencion"] = $_REQUEST["FrecuenciaMantencion"];
					$dt->data[$key]["FrecuenciaCambio"] = $_REQUEST["FrecuenciaCambio"];
					$dt->data[$key]["Cantidad"] = $_REQUEST["Cantidad"];
					$dt->data[$key]["Porcentaje"] = $_REQUEST["Porcentaje"];
					$dt->data[$key]["Propuesta"] = $_REQUEST["Propuesta"];
					$dt->data[$key]["FechaPropuesta"] = $_REQUEST["FechaPropuesta"];
					$dt->data[$key]["GPS"] = $_REQUEST["GPS"];
					$dt->data[$key]["seleccion"] = $_REQUEST["seleccion"];
					$dt->data[$key]["seleccion_ded"] = $_REQUEST["seleccion_ded"];
					$dt->data[$key]["rut_coordinador"] = $_REQUEST["rut_coordinador"];
					$dt->data[$key]["nombre_coordinador"] = $_REQUEST["nombre_coordinador"];
				}

				if ( $_REQUEST["Patente"] != "" ){ //Contrato Renting 

					$dt->data[$key]["FormasPago"] = $_REQUEST["FormasPago"];
					$dt->data[$key]["Marca"] = $_REQUEST["Marca"];
					$dt->data[$key]["Modelo"] = $_REQUEST["Modelo"];
					$dt->data[$key]["Patente"] = $_REQUEST["Patente"];
					$dt->data[$key]["Color"] = $_REQUEST["Color"];
					$dt->data[$key]["VIN"] = $_REQUEST["VIN"];
					$dt->data[$key]["AnnoVehiculo"] = $_REQUEST["AnnoVehiculo"];
					$dt->data[$key]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
					$dt->data[$key]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
					$dt->data[$key]["FechaInicio"] = $_REQUEST["FechaInicio"];
					$dt->data[$key]["FechaFinal"] = $_REQUEST["FechaFinal"];
					$dt->data[$key]["FechaPie"] = $_REQUEST["FechaPie"];
					$dt->data[$key]["FechaPago"] = $_REQUEST["FechaPago"];
					$dt->data[$key]["CuotaPie"] = $_REQUEST["CuotaPie"];
					$dt->data[$key]["MontoCuota"] = $_REQUEST["MontoCuota"];
					$dt->data[$key]["Exceso"] = $_REQUEST["Exceso"];
					$dt->data[$key]["KmsExceso"] = $_REQUEST["KmsExceso"];
					$dt->data[$key]["idRentaMens"] = $_REQUEST["idRentaMens"];
					$dt->data[$key]["RentaMens"] = $_REQUEST["RentaMens"];
				}

				if( $_REQUEST["RutProveedor"] != "" ){ //Contrato Financiero Operativo

					$dt->data[$key]["RutProveedor"] = $_REQUEST["RutProveedor"];
					$dt->data[$key]["FechaInicioPago"] = $_REQUEST["FechaInicioPago"];
					$dt->data[$key]["DetalleBienes"] = $_REQUEST["DetalleBienes"];
					$dt->data[$key]["Moneda"] = $_REQUEST["Moneda"];
					$dt->data[$key]["MontoAdquisicion"] = $_REQUEST["MontoAdquisicion"];
					$dt->data[$key]["CantTotal"] = $_REQUEST["CantTotal"];
					$dt->data[$key]["CantRentas"] = $_REQUEST["CantRentas"];
					$dt->data[$key]["ValorBallon"] = $_REQUEST["ValorBallon"];
					$dt->data[$key]["ValorCompra"] = $_REQUEST["ValorCompra"];
					$dt->data[$key]["DiaPagoMensual"] = $_REQUEST["DiaPagoMensual"];
					$dt->data[$key]["DuracionContrato"] = $_REQUEST["DuracionContrato"];
					$dt->data[$key]["FechaPrepago"] = $_REQUEST["FechaPrepago"];
					$dt->data[$key]["ValorAsegurable"] = $_REQUEST["ValorAsegurable"];
					$dt->data[$key]["ValorIguales"] = $_REQUEST["ValorIguales"];
				}
			}
		}
		
		$formulario = $dt->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarNotaria.html');
	}

	//Mostrar los firmantes de cada ente
	private function firmantes(){

		//Instanciamos la clase
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt7 = new DataTable();
		$dt8 = new DataTable();
		$dt9 = new DataTable();

		//Buscamos Firmantes la Empresa
		$this->documentosBD->obtenerFirmantes($_REQUEST,$dt1);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscamos Firmantes la Cliente
		$this->documentosBD->obtenerFirmantesC($_REQUEST,$dt2);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscamos Firmantes la Notaria
		$this->documentosBD->obtenerFirmantesN($_REQUEST,$dt3);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de la Empresa
		$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Completamos los datos de la Empresa Cliente 
		$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de Notaria
		$this->documentosBD->obtenerRazonSocialN($_REQUEST,$dt8);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los tipo de Firmas
		$this->documentosBD->listadoTipoFirmas($dt6);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Completamos los datos de la Plantilla
		$this->documentosBD->obtenerPlantilla($_REQUEST,$dt7);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		//Consultar si el flujo tiene Aval
		$this->documentosBD->obtenerAval($dt7->data[0],$dt9);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		//Si el flujo de firmas tiene aval 
		if ( $dt9->data[0]["Aval"] == 0 ){
			$dt->data[0]["aval"] = 0;
		}
		else{
			$dt->data[0]["aval"] = 1;
		}

		$dt->data[0]["RutEmpresa"] = $_REQUEST["RutEmpresa"];

		//Tipo de modelo de contrato : Anexo
		if( $dt5->data[0]["TipoEmpresa"] == 2 ){
			$dt->data[0]["RutEmpresaC"] = $dt5->data[0]["RutEmpresaC"];
		}else{
			$dt->data[0]["RutEmpresaC"] = $dt5->data[0]["RutEmpresa"];
		}
		
		$dt->data[0]["RutEmpresaN"] = $_REQUEST["RutEmpresaN"];

		//Firmantes de la Empresa
		//Quitar Etiqutas de HTML
		if( count($dt1->data) > 0 ){
			foreach ($dt1->data as $key => $value) {
				$dt1->data[$key]["Personeria"] = strip_tags($dt1->data[$key]["Personeria"]);
			}
			$dt->data[0]["firmantes_empresa"] = $dt1->data;
			$this->firmantes_empresa = array();
			$this->firmantes_empresa = $dt1->data;
		}
		
		//Firmantes del Cliente 
		//Quitar etiquetas de HTML
		if( count($dt2->data) > 0 ){
			foreach ($dt2->data as $key => $value) {
				$dt2->data[$key]["Personeria"] = strip_tags($dt2->data[$key]["Personeria"]);
			}
			$dt->data[0]["firmantes_cliente"] = $dt2->data;
			$this->firmantes_cliente = array();
			$this->firmantes_cliente = $dt2->data;
		}
		
		//Firmantes de la Notaria
		$dt->data[0]["firmantes_notaria"] = $dt3->data;
		$this->firmantes_notaria = array();
		$this->firmantes_notaria = $dt3->data;

		//Datos de formulario
		$dt->data[0]["idPlantilla"] = $dt7->data[0]["idPlantilla"];
		$dt->data[0]["idWF"] = $dt7->data[0]["idWF"];
		$dt->data[0]["idTipoDoc"] = $dt7->data[0]["idTipoDoc"];
		$dt->data[0]["RutEjecutivo"] = $_REQUEST["RutEjecutivo"];
		$dt->data[0]["RutSupervisor"] = $_REQUEST["RutSupervisor"];
		$dt->data[0]["fechaInicio"] = $_REQUEST["fechaInicio"];
		$dt->data[0]["fechaFin"] = $_REQUEST["fechaFin"];
		$dt->data[0]["fecha"] = $_REQUEST["fecha"];
		$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
		$dt->data[0]["FormasPago"] = $_REQUEST["FormasPago"];
		$dt->data[0]["RutEmpresa"] = $dt4->data[0]["RutEmpresa"];
		$dt->data[0]["RazonSocial"] = $dt4->data[0]["RazonSocial"];

		if( $dt5->data[0]["TipoEmpresa"] == 2 ){
			$dt->data[0]["RutEmpresaC"] = $dt5->data[0]["RutEmpresaC"];
			$dt->data[0]["RazonSocialC"] = $dt5->data[0]["RazonSocialC"];
		}else{
			$dt->data[0]["RutEmpresaC"] = $dt5->data[0]["RutEmpresa"];
			$dt->data[0]["RazonSocialC"] = $dt5->data[0]["RazonSocial"];
		}

		$dt->data[0]["RutEmpresaN"] = $dt8->data[0]["RutEmpresaN"];
		$dt->data[0]["RazonSocialN"] = $dt8->data[0]["RazonSocialN"];
		$dt->data[0]["idPla"] = $dt7->data[0]["idPlantilla"]." ".strip_tags( substr($dt7->data[0]["Descripcion_Pl"], 0, 60));
		$dt->data[0]["Notaria"] = $dt8->data[0]["RutEmpresaN"]." ".$dt8->data[0]["RazonSocialN"];
		$dt->data[0]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
		$dt->data[0]["pro"] = $_REQUEST["pro"];

		if( $_REQUEST["modelo_contrato"] == 2 ){//Si es Anexo

			$dt->data[0]["idProyecto"] = $_REQUEST["idProyecto"];
			$dt->data[0]["fechaFirma"] = $_REQUEST["fechaFirma"];
			$dt->data[0]["Marca"] = $_REQUEST["Marca"];
			$dt->data[0]["Modelo"] = $_REQUEST["Modelo"];
			$dt->data[0]["CiudadEntrega"] = $_REQUEST["CiudadEntrega"];
			$dt->data[0]["CiudadOperacion"] = $_REQUEST["CiudadOperacion"];
			$dt->data[0]["CiudadDevolucion"] = $_REQUEST["CiudadDevolucion"];
			$dt->data[0]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
			$dt->data[0]["FechaInicio"] = $_REQUEST["FechaInicio"];
			$dt->data[0]["FechaFinal"] = $_REQUEST["FechaFinal"];
			$dt->data[0]["Tarifa"] = $_REQUEST["Tarifa"];
			$dt->data[0]["idMoneda_Tarifa"] = $_REQUEST["idMoneda_Tarifa"];
			$dt->data[0]["idMoneda_KmsExceso"] = $_REQUEST["idMoneda_KmsExceso"];
			$dt->data[0]["KmsExceso"] = $_REQUEST["KmsExceso"];
			$dt->data[0]["KmsContratados"] = $_REQUEST["KmsContratados"];
			$dt->data[0]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
			$dt->data[0]["FrecuenciaMantencion"] = $_REQUEST["FrecuenciaMantencion"];
			$dt->data[0]["FrecuenciaCambio"] = $_REQUEST["FrecuenciaCambio"];
			$dt->data[0]["Cantidad"] = $_REQUEST["Cantidad"];
			$dt->data[0]["Porcentaje"] = $_REQUEST["Porcentaje"];
			$dt->data[0]["Propuesta"] = $_REQUEST["Propuesta"];
			$dt->data[0]["FechaPropuesta"] = $_REQUEST["FechaPropuesta"];
			$dt->data[0]["GPS"] = $_REQUEST["GPS"];
			$dt->data[0]["seleccion"] = $_REQUEST["seleccion"];
			$dt->data[0]["seleccion_ded"] = $_REQUEST["seleccion_ded"];
			$dt->data[0]["rut_coordinador"] = $_REQUEST["rut_coordinador"];
			$dt->data[0]["nombre_coordinador"] = $_REQUEST["nombre_coordinador"];
		}
		
		if( $_REQUEST["modelo_contrato"] == 3 ){//Si es Renting

			$dt->data[0]["FormasPago"] = $_REQUEST["FormasPago"];
			$dt->data[0]["Marca"] = $_REQUEST["Marca"];
			$dt->data[0]["Modelo"] = $_REQUEST["Modelo"];
			$dt->data[0]["Patente"] = $_REQUEST["Patente"];
			$dt->data[0]["Color"] = $_REQUEST["Color"];
			$dt->data[0]["VIN"] = $_REQUEST["VIN"];
			$dt->data[0]["AnnoVehiculo"] = $_REQUEST["AnnoVehiculo"];
			$dt->data[0]["PeriodoArriendo"] = $_REQUEST["PeriodoArriendo"];
			$dt->data[0]["KmsMensuales"] = $_REQUEST["KmsMensuales"];
			$dt->data[0]["FechaInicio"] = $_REQUEST["FechaInicio"];
			$dt->data[0]["FechaFinal"] = $_REQUEST["FechaFinal"];
			$dt->data[0]["FechaPie"] = $_REQUEST["FechaPie"];
			$dt->data[0]["FechaPago"] = $_REQUEST["FechaPago"];
			$dt->data[0]["CuotaPie"] = $_REQUEST["CuotaPie"];
			$dt->data[0]["MontoCuota"] = $_REQUEST["MontoCuota"];
			$dt->data[0]["Exceso"] = $_REQUEST["Exceso"];
			$dt->data[0]["KmsExceso"] = $_REQUEST["KmsExceso"];
			$dt->data[0]["idRentaMens"] = $_REQUEST["idRentaMens"];
			$dt->data[0]["RentaMens"] = $_REQUEST["RentaMens"];
		}

		if ( $_REQUEST["modelo_contrato"] == 5 ){ //Si es Operativo - Financiero

			$dt->data[0]["RutProveedor"] = $_REQUEST["RutProveedor"];
			$dt->data[0]["FechaInicioPago"] = $_REQUEST["FechaInicioPago"];
			$dt->data[0]["DetalleBienes"] = $_REQUEST["DetalleBienes"];
			$dt->data[0]["Moneda"] = $_REQUEST["Moneda"];
			$dt->data[0]["MontoAdquisicion"] = $_REQUEST["MontoAdquisicion"];
			$dt->data[0]["CantTotal"] = $_REQUEST["CantTotal"];
			$dt->data[0]["CantRentas"] = $_REQUEST["CantRentas"];
			$dt->data[0]["ValorBallon"] = $_REQUEST["ValorBallon"];
			$dt->data[0]["ValorCompra"] = $_REQUEST["ValorCompra"];
			$dt->data[0]["DiaPagoMensual"] = $_REQUEST["DiaPagoMensual"];
			$dt->data[0]["DuracionContrato"] = $_REQUEST["DuracionContrato"];
			$dt->data[0]["FechaPrepago"] = $_REQUEST["FechaPrepago"];
			$dt->data[0]["ValorAsegurable"] = $_REQUEST["ValorAsegurable"];
			$dt->data[0]["ValorIguales"] = $_REQUEST["ValorIguales"];

		}
				
		//Verificar si el Flujo tiene Notaria
		if( strpos($dt7->data[0]["NombreWF"],"Notario") > 0 ){
			$dt->data[0]["not"] = 1;
		}else{
			$dt->data[0]["not"] = 0;
		}

		//Reasignar resultados
		$formulario = $dt->data;
		$formulario[0]["TipoFirmas"] = $dt6->data;

		//Pasamos los datos a la Plantilla 
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FormularioAgregarFirmantes.html');
	}
	
	//Generar el documento en PDF con los datos del formulario 
	private function generar(){
		
		$datos = $_REQUEST;

		//Instancia la clase
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();

		//Datos que faltan al registro 
		$datos["idEstado"] = 1; //Creado
		$datos["FechaCreacion"] = date("d-m-Y H:i:s");
		$datos["idTipoGeneracion"] = 1; //Por Plantilla 
		$datos["idTipoDoc"] = $datos["modelo_contrato"];
		
		//Guardar los Datos del Documento 
		$this->documentosBD->agregar($datos,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError; 

		//Reasignar datos       
		$datos["idContrato"] = $dt->data[0]["idContrato"];
		$idContrato = $dt->data[0]["idContrato"];

		//Sustituir acentos al formato de HTML 
		$_REQUEST["personeria_aval"] = $this->TildesHtml($_REQUEST["personeria_aval"]);

		$this->firmantes_completos = array();

		switch ($datos["modelo_contrato"]) {
			case 1: //Contrato Marco 
					$this->construirFirmantes($idContrato,$this->firmantes_completos);
					$this->construirPlantilla($this->html);
					if( $this->html == false) {
						$this->pagina->agregarDato("mensajeError", "Esta Plantilla no tiene Clausulas asociadas");
					}else{
						$this->sustituirVariables($idContrato,$this->html, $resultado);
					}
				break;
			case 2: //Anexo
					$this->construirFirmantes($idContrato,$this->firmantes_completos);
					$this->orientacion = 'L';	// L = Horizontal
					//Actualizar los datos en la tabla del detalle de Proyecto
					$this->documentosBD->modificar($datos,$dt2);
					$this->mensajeError .= $this->documentosBD->mensajeError;
					//Construimos Plantilla de anexo
					$this->construirPlantilla($this->html);
					if( $this->html == false) {
						$this->pagina->agregarDato("mensajeError", "Esta Plantilla no tiene Clausulas asociadas");
					}else{
						$this->sustituirVariablesAnexo($idContrato,$this->html, $resultado);
					}
				break;
			case 3: //Renting 
					$this->construirFirmantes($idContrato,$this->firmantes_completos);
					$this->construirPlantilla($this->html);
					if( $this->html == false) {
						$this->pagina->agregarDato("mensajeError", "Esta Plantilla no tiene Clausulas asociadas");
					}else{
						$this->sustituirVariables_2($idContrato,$this->html, $resultado);
					}
				break;	
			case 4: //Condiciones Generales
					$this->construirFirmantes($idContrato,$this->firmantes_completos);
					$this->construirPlantilla($this->html);
					if( $this->html == false) {
						$this->pagina->agregarDato("mensajeError", "Esta Plantilla no tiene Clausulas asociadas");
					}else{
						$this->sustituirVariables($idContrato,$this->html, $resultado);
					}
				break;	
			case 5: //Contrato Financiero-Operativo 
					$this->construirFirmantes($idContrato,$this->firmantes_completos);
					$this->construirPlantilla($this->html);
					if( $this->html == false) {
						$this->pagina->agregarDato("mensajeError", "Esta Plantilla no tiene Clausulas asociadas");
					}else{
						$this->sustituirVariables_3($idContrato,$this->html, $resultado);
					}
				break;		
		}

		//Agregar personerias al html
		$personerias = "";
		$this->construirPersonerias($personerias);
		$resultado .= $personerias;

		//Si es de firma manual
		if( $datos["TipoFirmas"] == 1 ){
			$this->construirTablaFirmantes($this->firmantes_completos, $this->firmantes_tabla);
			//Unir texto y tabla de Firmantes	
		    $resultado .= $this->firmantes_tabla;
		}


		//Codificacion internacional del texto
    	$texto_completo = utf8_encode($resultado);

	    //Generar PDF
	    $this->generarPDF($idContrato,$texto_completo); 

		//Guardar registro del archivo codificado
	    $archivoaux = file_get_contents($this->ruta);
		
		//Agregar a Documentos
		$doc_aux = array();
		$doc_aux["idContrato"] = $idContrato;
		$doc_aux["NombreArchivo"] = "Documento_". $idContrato;
		$doc_aux["Extension"] = EXT;
		$doc_aux["documento"] = base64_encode($archivoaux);//el archivo en base 64

		//Ejecutar el SP
	    if( $this->documentosBD->agregarDocumento($doc_aux) ){
	    	$this->mensajeOK= "La generaci&oacute;n fue  exitosa";
		    //Mostrar la vista previa del Documento 
		    $this->verdocumento($idContrato);
		    return;
		}else{
			$this->mensajeError.=$this->documentosBD->mensajeError;
		    // nos vamos al principio
			$this->agregar();
			return;
		} 	

	    /*else{
	    	//Error de la generacion del PDF
	    	$this->mensajeError.=$this->documentosBD->mensajeError;

	    	//Eliminar los datos generados en Contratos, ContratosFirmantes, Documentos y DocumentosVariables
	    	$this->documentosBD->limpiarRegistros($idContrato);
	    	$this->mensajeError.=$this->documentosBD->mensajeError;
	    	
	    	//nos vamos al principio
			$this->agregar();
			return;
	    }*/
	}

	//Sustituir acentos
	public static function TildesHtml($cadena) 
    { 
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }

	//PDF Contrato libreria TCPDF
	private function generarPDF_antes($datos,$html){
		try { 
	        // create new PDF document
			$pdf = new TCPDF($this->orientacion,PDF_UNIT,PDF_FORMATO,true,'UTF-8',false);

			// set document information
			$pdf->SetCreator('');
			$pdf->SetAuthor('');
			$pdf->SetTitle('');
			$pdf->SetSubject('');
			$pdf->SetKeywords('');

			// set default header data
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// set header and footer fonts
			$pdf->setFooterFont(Array(PDF_FPRENOM,'',PDF_FPRETAM));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MIZQ, PDF_MSUP,PDF_MDER);
			$pdf->SetHeaderMargin(PDF_MENC);
			$pdf->SetFooterMargin(PDF_MPIE);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE,PDF_MARGIN_BOTTOM);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------
			// set default font subsetting mode
			//$pdf->setFontSubsetting(true);

			// Set font
			$pdf->SetFont('dejavusans','',8,'',true);

			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();

			$html = utf8_encode($html);
			// Print text using writeHTMLCell()
			$pdf->writeHTML($html, true, false, true, false, '');

			// ---------------------------------------------------------
			// Close and output PDF document

			//Asignar ruta del documento a generar
			$this->ruta = dirname(__FILE__).'/tmp/Documento_'.$datos.'.pdf';	

			//Generar PDF
			$pdf->Output($this->ruta, 'F');

		} catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
	}
	
	// con librerias dompdf
	private function generarPDF($datos,$html){
		//$this->graba_log("html:".$html);
		
		try { 
			
	        // instancia clase dompdf
			$dompdf = new Dompdf();
			
			$dompdf->loadHtml($html);
			
			
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'landscape');
			
			// Render the HTML as PDF
			$dompdf->render();		
							
			$pdf = $dompdf->output();
			
			//Asignar ruta del documento a generar
			$this->ruta = dirname(__FILE__).'/tmp/Documento_'.$datos.'.pdf';	
				
			file_put_contents($this->ruta, $pdf);
			
			
			} catch (Exception $e) {
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
	}
	//fin
	
	//Ver Documento PDF
	private function verdocumento($data){

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;

		$array = array ( "idContrato" => $data);

		$this->documentosBD->obtenerb64($array,$dt);	
		$this->mensajeError=$this->generar_manualesBD->mensajeError;
				
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
		
		$formulario[0]= $array;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_manuales_documento.html');					
	}

	//Construir Plantilla
	private function construirPlantilla(&$resultado){
		
		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = "";

		// Obtenemos los datos de las Clausulas relacionados
		$this->documentosBD->obtenerClausulasPlantillas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

        //Agregamos Titulo de la Plantilla
        $html = '<div align="center">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';

        //Variables
        $num = 1;
     
        //Construir Plantilla con las Clausulas
        if( count($dt->data) > 0){

        	foreach ($dt->data as $i => $value) {
        		//Si, esta activo el encabezado automatico

	        	if( $dt->data[$i]["Encabezado"] == 1){

	        		$this->ordinal[$num] = $dt->data [$i]["idClausula"];
	        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
	        		$dt->data[$i]["Descripcion_Cl"] = "<strong><u>".$resultado."</u></strong>: ";
	        		//Agregar la descripcion de cada Clausula
	        		$html .= $dt->data[$i]["Descripcion_Cl"];
	        		$num++;
	        	}
	        	else{
	        		$dt->data[$i]["Descripcion_Cl"] = " ";
	        	}
	        	//Si , esta activo mostrar el titulo
	        	if( $dt->data[$i]["Titulo"] == 0 ){
	        		$dt->data[$i]["Titulo_Cl"] = " ";
	        	}
	        	else{
	        		$html .= "<strong>".$dt->data[$i]["Titulo_Cl"]."</strong>";
	        	}
	        	$html .= $dt->data[$i]["Texto"];
	        }
        }
        else{
        	return false;
        }
   		//Cerramos la plantilla
    	$html .= '</div>';
    	//Reasignar HTML a un atributo de la clase
	    $resultado = $html;
	    return $resultado;
	    //FIN
	}

	//Construir personerias 
	private function construirPersonerias(&$resultado){

		$texto = "";
		$num = 0;
		$dt = new DataTable();

		$encabezado = "<strong>PERSONERIAS:</strong><br/>";

		//Recorremos los firmantes de Empresas
		$firmantes_emp = array ();
		$firmantes_emp = $_REQUEST["Firmantes_Emp"];

		$firmantes_emp_per = array ();
		$firmantes_emp_per = $_REQUEST["Firmantes_Emp_Per"];

		if( count($firmantes_emp) > 0 ){

			foreach ($firmantes_emp as $key => $value) {

				if ( $firmantes_emp_per[$key] == 1 ){

					$array = array ("RutFirmante" => $firmantes_emp[$key], "RutEmpresa" => $_REQUEST["RutEmpresa"] );	

					$this->documentosBD->obtenerFirmante($array,$dt);				
			        $this->mensajeError.=$this->documentosBD->mensajeError;
					if($dt->leerFila())
					{
						$datos	= $dt->obtenerItem("Personeria");
					}
			
					if( $datos != "" ){
						$num++; 
						$texto .=  "<p>".$num.") ".strip_tags($datos)."</p>";
					}
				}
			}
		}
			
		//Recorremos los firmantes de Clientes
		$firmantes_cli = array ();
		$firmantes_cli = $_REQUEST["Firmantes_Cli"];

		$firmantes_cli_per = array ();
		$firmantes_cli_per = $_REQUEST["Firmantes_Cli_Per"];

		if ( count($firmantes_cli) > 0 ){

			foreach ($firmantes_cli as $key => $value) {

				if ( $firmantes_cli_per[$key] == 1 ){

					$array = array ("RutFirmante" => $firmantes_cli[$key], "RutEmpresa" => $_REQUEST["RutEmpresaC"] );
		
					$this->documentosBD->obtenerFirmante($array,$dt);				
			        $this->mensajeError.=$this->documentosBD->mensajeError;
			
					if($dt->leerFila())
					{
						$datos	= $dt->obtenerItem("Personeria");
					}
			
					if( $datos != "" ){
						$num++; 
						$texto .=  "<p>".$num.") ".strip_tags($datos)."</p>";
					}
				}
			}
		}
		
		if( strlen($texto) > 0 ){
			$resultado = $encabezado.$texto;
		}

		return $resultado;
	}

	//Construir Arreglos de los Firmantes
	private function construirFirmantes($datos,&$resultado){

		//Variables que faltan 
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt8 = new DataTable();

	    //Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar Datos que faltan del Cliente 
       	$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar Datos que faltan del Notaria
       	$this->documentosBD->obtenerRazonSocialN($_REQUEST,$dt6);
       	$this->mensajeError.=$this->documentosBD->mensajeError;
    
      	//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($_REQUEST, $dt8);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        //Auxiliar para cuando tiene estado de aprobacion
        $orden = 0;

		if( count($dt8->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt8->data as $key => $value) {
   	   
		        //Si Estado: Pendiente por firma de Empresa 
		        if( $dt8->data[$key]["Nombre"] == 'Pendiente por firma Empresa'){

		        	//Firmantes de la Empresa
			  		$f_empresa = array();
			  		$empresa_aux = array();

			        foreach ($_REQUEST["Firmantes_Emp"] as $i => $valor) {
			        	//Datos faltantes 
			        	$empresa_aux = array ( "idContrato" => $datos, "RutEmpresa" => $_REQUEST["RutEmpresa"], "RutFirmante" => $_REQUEST["Firmantes_Emp"][$i], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);	        	
			        				        
			        	//Agregara a la tabla
			        	$this->documentosBD->agregarFirmantes($empresa_aux);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;
			        	
			        	//Buscar datos
			        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Emp"][$i] );
			        	$this->documentosBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;

			        	$nombre_emp = "";
			        	
			        	if ( $dt4->data[0]["TipoEmpresa"] == 1 ){
			        		$nombre_emp = $dt4->data[0]["RazonSocial"];
			        	}else{
			        		$nombre_emp = $dt4->data[0]["RazonSocialC"];
			        	}

			        	$identificador = "";
			        	//Verificacion de formato de Rut 
			        	if ( strlen($dt3->data[0]["personaid"]) == 10 && strpos($dt3->data[0]["personaid"],"-")){
			        		$identificador = "RUT N&deg;";
			        	}
			        	else{
			        		$identificador = "N&deg; ";
			        	}
			        	
			        	//Completar el arreglo
			        	$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$_REQUEST["Firmantes_Emp"][$i], "nombre_emp" => "P.p ".$nombre_emp, "rut_emp" => "RUT N&deg;".$_REQUEST["RutEmpresa"]);
						
						//Agregar al final 
						array_push($f_empresa, $nuevo);
		        	}
		        } //Fin del Si Estado: Pendiente por firma de Empresa 

		        //Si Estado: Pendiente por firma de Cliente
		        if( $dt8->data[$key]["Nombre"] == 'Pendiente por firma Cliente'){

		        	//Firmantes del Cliente 
		        	$f_cliente = array();
		        	$cliente_aux = array();

			        foreach ($_REQUEST["Firmantes_Cli"] as $i => $valor) {
			        	//Datos faltantes 
				        $cliente_aux = array ( "idContrato" => $datos, "RutEmpresa" => $_REQUEST["RutEmpresaC"], "RutFirmante" => $_REQUEST["Firmantes_Cli"][$i], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

			        	//Agregara a la tabla
			        	$this->documentosBD->agregarFirmantes($cliente_aux);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;
			        	
			        	//Buscar datos
			        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][$i] );
			        	$this->documentosBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;

			        	$tipo_firmante = 0;
			        	//Verificacion es persona natural 
			        	if ( $_REQUEST["Firmantes_Cli"][$i] == $_REQUEST["RutEmpresaC"] ){
			        		$tipo_firmante = 1;
			        	}
			        	
			        	$nombre_cli = ""; 
			        	//Nombre de empresa 
			        	if( $dt5->data[0]["TipoEmpresa"] == 1 ){
			        		$nombre_cli = $dt5->data[0]["RazonSocial"];
			        	}
			        	else{
			        		$nombre_cli = $dt5->data[0]["RazonSocialC"];
			        	}

			        	$identificador = "";
			        	//Verificacion de formato de Rut
			        	if ( strlen($dt3->data[0]["personaid"]) == 10 && strpos($dt3->data[0]["personaid"],"-")){
			        		$identificador = "RUT N&deg;";
			        	}
			        	else{
			        		$identificador = "N&deg; ";
			        	}

			        	$identificador2 = "";
			        	//Verificacion de formato del rut de la empresa
			        	if ( strlen($_REQUEST["RutEmpresaC"]) == 10 && strpos($_REQUEST["RutEmpresaC"],"-")){
			        		$identificador2 = "RUT N&deg;";
			        	}
			        	else{
			        		$identificador2 = "N&deg; ";
			        	}

			        	if( $tipo_firmante == 1 ){
			        		//Completar arreglo
			        		$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$_REQUEST["Firmantes_Cli"][$i]);
			        	}else{
			        		//Completar arreglo
			        		$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$_REQUEST["Firmantes_Cli"][$i], "nombre_cli" => "P.p ".$nombre_cli, "rut_cli" => $identificador2.$_REQUEST["RutEmpresaC"] );
			        	}
			        	
			        	//Agregar al final
			        	array_push($f_cliente, $nuevo);
			        }
		        } // Fin del Si Estado: Pendiente por firma de Cliente

		         //Si Estado: Pendiente por firma de Aval

		        if( $dt8->data[$key]["Nombre"] == 'Pendiente por firma de Aval'){

		        	//Firmantes del Cliente 
		        	$f_aval = array();
		        	$aval_aux = array();

			        if ( $_REQUEST["rut_aval"] != "" ) {
			        	//Datos faltantes 
			           	$aval_aux = array ( "idContrato" => $datos, "RutEmpresa" => $_REQUEST["RutEmpresaC"], "RutFirmante" => $_REQUEST["rut_aval"], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

				        $datos_aval = array ("personaid" => $_REQUEST["rut_aval"], "nombre" => $_REQUEST["nombre_aval"], "apellido" => $_REQUEST["apellido_aval"], "correo" => $_REQUEST["correo_aval"]);
				      
			        	//Agregar a la tabla
			        	$this->documentosBD->agregarFirmantes($aval_aux);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;

			        	//Agregar aval a personas
			        	$this->documentosBD->agregarPersona($datos_aval);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;
			 		
			 			//Verificar tipo de Firmante
			 			$tipo_aval = 0;

			 			if( $_REQUEST["rut_aval"] == $_REQUEST["RutEmpresaC"] ){
			 				$tipo_aval = 1;
			 			}

			 			if( $tipo_aval == 1 ){
			 				//Completar arreglo
			 				$nuevo = array( "nombre" => $_REQUEST["nombre_aval"].' '.$_REQUEST["apellido_aval"] , "rut" => "RUT N&deg;".$_REQUEST["rut_aval"], "nombre_cli" => "FIADOR Y CODEUDOR SOLIDARIO");
			 			}else{
			 				//Completar arreglo
			        		$nuevo = array( "nombre" => $_REQUEST["nombre_aval"].' '.$_REQUEST["apellido_aval"] , "rut" => 'RUT N&deg;'.$_REQUEST["rut_aval"], "nombre_cli" => "Por ".$nombre_cli, "rut_cli" => 'RUT N&deg;'.$_REQUEST["RutEmpresaC"]."<br>FIADOR Y CODEUDOR SOLIDARIO" );
			 			}
			 	       	
			        	//Agregar al final
			        	array_push($f_aval, $nuevo);
			        }
		        }// Fin del Si Estado: Pendiente por firma de Aval

		        //Si Estado: Pendiente por firma de Notario
		  		if( $dt8->data[$key]["Nombre"] == 'Pendiente por firma Notario'){
		  			
		  			//Firmantes del Notario
		        	$f_notario = array();
		        	$notaria_aux = array();

			        //Si el flujo tiene Notaria 
			        if( $_REQUEST["not"] == 1 ){

			        	//Si el flujo tiene Notaria 
			        	 foreach ($_REQUEST["Firmantes_Not"] as $i => $valor) {
				        	//Datos faltantes 
				        	$notaria_aux = array ( "idContrato" => $datos, "RutEmpresa" => $_REQUEST["RutEmpresaN"], "RutFirmante" => $_REQUEST["Firmantes_Not"][$i], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

				        	//Agregara a la tabla
				        	$this->documentosBD->agregarFirmantes($notaria_aux);
				        	$this->mensajeErro.=$this->documentosBD->mensajeError;

				        	//Buscar datos
				        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Not"][$i] );
				        	$this->documentosBD->obtenerPersona($array, $dt3);
				        	$this->mensajeError.=$this->documentosBD->mensajeError;

				        	//Completar arreglo
				        	$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => 'RUT N&deg;'.$_REQUEST["Firmantes_Not"][$i], "nombre_not" => "P.p ".$dt6->data[0]["RazonSocialN"], "rut_not" => 'RUT N&deg;'.$_REQUEST["RutEmpresaN"]);
				        	//Agregar al final
				        	array_push($f_notario, $nuevo);
			        	}
			        }
		        }//Si Estado: Pendiente por firma de Notario

		        

	        }//Fin del Foreach de los Estados del WF
		}
       
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();
		array_push($firmantes_completos, $f_empresa);
		array_push($firmantes_completos, $f_cliente);

		//Si el flujo tiene Notario
		if( $_REQUEST["not"] == 1 ){
			array_push($firmantes_completos, $f_notario);
		}				

		//Si tiene aval
		if ( $_REQUEST["rut_aval"] != "" ){
			array_push($firmantes_completos, $f_aval);
		} 

		$resultado = array();
		$resultado = $firmantes_completos; 

		return $resultado;
	}

	//Construir tabla de Firmates de un Documento 
	public function construirTablaFirmantes($datos, &$resultado){

		//Declarar variable para firmantes 
		$tabla_firmantes = ''; 
		//Agregar firmantes al documento
    	$tabla_firmantes = '<p><p></p><p></p><p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p>
    	<table border="0" width="100%" style="page-break-inside:avoid;" ><tbody><tr>';
	        $num = 0;
	     	foreach ($datos as $i => $value) {
	       		foreach ($datos[$i] as $j => $value) {
	       		 	$tabla_firmantes.= '<td align="center" >';
	       				foreach ($datos[$i][$j] as $key => $value) {
	       					$tabla_firmantes .= "<p><strong>".$value."</strong></p>";
	       				}
		       		$tabla_firmantes .= '</td>';	       		
	       		 }	
	        } 
       	$tabla_firmantes.= '</tr></tbody></table>';

       //Reasignamos al atributo de la clase 
       return $resultado = $tabla_firmantes; 
       //FIN
	}

	//Sustituir variables del Documento
	public function sustituirVariables($datos, $html,&$resultado){

		//Variables a instanciar 
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt7 = new DataTable();
		$dt8 = new DataTable();
		$dt9 = new DataTable();
		$dt10 = new DataTable();

		//Representantes 
		$dt11 = new DataTable();
		$dt12 = new DataTable();
		$dt13 = new DataTable();
		$dt14 = new DataTable();
		$dt15 = new DataTable();
		$dt16 = new DataTable();

		//Consultas 
		//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerEmpresa($_REQUEST,$dt1);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscar datos del Representante 1
		$cliente = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][0] );
       	$this->documentosBD->obtenerPersona($cliente,$dt11);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 2
		$cliente_1 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][1] );
       	$this->documentosBD->obtenerPersona($cliente_1,$dt12);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 3
		$cliente_2 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][2] );
       	$this->documentosBD->obtenerPersona($cliente_2,$dt13);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 4
		$cliente_3 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][3] );
       	$this->documentosBD->obtenerPersona($cliente_3,$dt14);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 5
		$cliente_4 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][4] );
       	$this->documentosBD->obtenerPersona($cliente_4,$dt15);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       //Buscar datos del Representante 6
		$cliente_5 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][5] );
       	$this->documentosBD->obtenerPersona($cliente_5,$dt16);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar Datos que faltan del Cliente 
       	$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos de la empresa Cliente 
       	$array = array ( "RutEmpresa" => $_REQUEST["RutEmpresaC"]);
       	$this->documentosBD->obtenerEmpresa($array,$dt9);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Obtener nombre de Forma de Pago 
       	$this->documentosBD->obtenerFormasPago($_REQUEST,$dt10);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	if( $dt5->data[0]["TipoEmpresa"] == 2 ) {
       		$rut = $dt5->data[0]["RutEmpresaC"];
       		$nombre = $dt5->data[0]["RazonSocialC"];
       	}
       	else{
       		$rut = $dt5->data[0]["RutEmpresa"];
       		$nombre = $dt5->data[0]["RazonSocial"];
       	}

	    //Nombres de Dias y Meses 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		//Cambio de Fecha
		$dia = date(d);
		$mes = date(m);
		$anio = date(Y);
	
		//Nombre de archivo
		$NombreDoc = "Documento_".$datos.".pdf";

        $array = array ( 

        	//Datos basicos
        	"idContrato" => $datos,
        	"Dia" =>$dia, 
        	"Mes" => $meses[$mes-1], 
        	"Anno" => $anio,
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"FormasPago" => $dt10->data[0]["FormaPago"],
        	"Equipamiento" => "", //Equipamiento
        	"Deducibles" => "", //Deducibles 
        	"Porcentaje"=> "" , //Porcentaje
        	"NombreDoc" => $NombreDoc ,

        	//Representantes del Cliente - 1
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"],

        	//Representantes del Cliente - 2
        	"RutRepresentante_2" => $dt12->data[0]["personaid"],
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"]
        );

        $aux = array (

        	//Datos genericos
        	"Dia" => $dia, //Dia en numeros
        	"Mes" => $meses[$mes-1], //Mes en palabras 
        	"Anno" => $anio, //Año completo, ej.2018
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"idDocumento" => $_REQUEST["idDocumento"],

        	//Datos de Contrato Marco
        	"FechaInicio" => $_REQUEST["fechaInicio"],
        	"FechaFin" => $_REQUEST["fechaFin"],
        	"FormasPago" => $dt10->data[0]["FormaPago"],

        	//Representante del Cliente - 1
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"], 
        	"Nacionalidad_1" => $dt11->data[0]["nacionalidad"],

        	//Representante del Cliente - 2
        	"RutRepresentante_2" => $dt12->data[0]["personaid"], 
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"],
        	"Nacionalidad_2" => $dt12->data[0]["nacionalidad"],

        	//Representantes del Cliente - 3
        	"RutRepresentante_3" => $dt13->data[0]["personaid"],	
        	"Representante_3" => $dt13->data[0]["nombre"]." ".$dt13->data[0]["appaterno"],
        	"Nacionalidad_3" => $dt13->data[0]["nacionalidad"],

        	//Representantes del Cliente - 4
        	"RutRepresentante_4" => $dt14->data[0]["personaid"],
        	"Representante_4" => $dt14->data[0]["nombre"]." ".$dt14->data[0]["appaterno"],
        	"Nacionalidad_4" => $dt14->data[0]["nacionalidad"],

        	//Representantes del Cliente - 5
        	"RutRepresentante_5" => $dt15->data[0]["personaid"],	
        	"Representante_5" => $dt15->data[0]["nombre"]." ".$dt15->data[0]["appaterno"],
        	"Nacionalidad_5" => $dt15->data[0]["nacionalidad"],

        	//Representantes del Cliente - 6
        	"RutRepresentante_6" => $dt16->data[0]["personaid"],
        	"Representante_6" => $dt16->data[0]["nombre"]." ".$dt16->data[0]["appaterno"],
        	"Nacionalidad_6" => $dt16->data[0]["nacionalidad"],

        	//Direccion del Cliente 
        	"Direccion" => $dt9->data[0]["Direccion"], 
        	"Comuna" => $dt9->data[0]["Comuna"] , 
        	"Ciudad" => $dt9->data[0]["Ciudad"],

        	//Otros
        	"DiaSemana" => $dias[date("w")], //Dia de semana 
        	"MesNum" => $mes, //Numero de mes
	
	    	//Datos del Aval 
	    	"Rut_Aval" => $_REQUEST["rut_aval"],
	    	"Nombre_Aval" => $_REQUEST["nombre_aval"]." ".$_REQUEST["apellido_aval"],
	    	"Correo_Aval" => $_REQUEST["correo_aval"],
	    	"Personeria_Aval" => $_REQUEST["personeria_aval"]

	    );

        //Variables @@[RutCliente]@@
		$variables = array ( 
			
			//Datos genericos
			"@@[Dia]@@",
			"@@[Mes]@@",
			"@@[Anno]@@",
			"@@[RutEmpresa]@@",
			"@@[NombreEmpresa]@@",
			"@@[RutCliente]@@",
			"@@[NombreCliente]@@",
			"@@[idDocumento]@@",

			//Datos de Contrato Marco
        	"@@[FechaInicio]@@",
        	"@@[FechaFin]@@",
        	"@@[FormasPago]@@",

        	//Representante del Cliente - 1
        	"@@[RutRepresentante_1]@@",	
        	"@@[Representante_1]@@",
        	"@@[Nacionalidad_1]@@", 

        	//Representante del Cliente - 2
        	"@@[RutRepresentante_2]@@", 
        	"@@[Representante_2]@@",
        	"@@[Nacionalidad_2]@@",

        	//Representante del Cliente - 3
        	"@@[RutRepresentante_3]@@",	
        	"@@[Representante_3]@@",
        	"@@[Nacionalidad_3]@@", 

        	//Representante del Cliente - 4
        	"@@[RutRepresentante_4]@@", 
        	"@@[Representante_4]@@",
        	"@@[Nacionalidad_4]@@",

        	//Representante del Cliente - 5
        	"@@[RutRepresentante_5]@@",	
        	"@@[Representante_5]@@",
        	"@@[Nacionalidad_5]@@", 

        	//Representante del Cliente - 6
        	"@@[RutRepresentante_6]@@", 
        	"@@[Representante_6]@@",
        	"@@[Nacionalidad_6]@@",
	    	 
	    	//Direccion del Cliente 
        	"@@[Direccion]@@",
        	"@@[Comuna]@@",
        	"@@[Ciudad]@@", 

           	//Otros
        	"@@[DiaSemana]@@", //Dia de semana 
        	"@@[MesNum]@@", //Numero de mes
	
	    	//Datos del Aval 
	    	"@@[Rut_Aval]@@",
	    	"@@[Nombre_Aval]@@",
	    	"@@[Correo_Aval]@@",
	    	"@@[Personeria_Aval]@@"
		);

	
		//Guardar en la BD
		$this->documentosBD->agregarVariables($array); 	
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Sustituir en el HTML
	    return $resultado = str_replace($variables,$aux,$html);
	}

	//Sustituir variables del Documento
	public function sustituirVariablesAnexo($datos, $html,&$resultado){

		//Variables a instanciar 
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt7 = new DataTable();
		$dt8 = new DataTable();
		$dt9 = new DataTable();
		$dt10 = new DataTable();

		//Representantes
		$dt11 = new DataTable();
		$dt12 = new DataTable();

		//Consultas 
		//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerEmpresa($_REQUEST,$dt1);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscar Datos que faltan del Cliente 
       	$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos de la empresa Cliente 
       	$array = array ( "RutEmpresa" => $_REQUEST["RutEmpresaC"]);
       	$this->documentosBD->obtenerEmpresa($array,$dt9);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Obtener nombre de Forma de Pago 
       	$this->documentosBD->obtenerFormasPago($_REQUEST,$dt10);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 1
		$cliente = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][0] );
       	$this->documentosBD->obtenerPersona($cliente,$dt11);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 2
		$cliente_1 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][1] );
       	$this->documentosBD->obtenerPersona($cliente_1,$dt12);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	if( $dt5->data[0]["TipoEmpresa"] == 2 ) {
       		$rut = $dt5->data[0]["RutEmpresaC"];
       		$nombre = $dt5->data[0]["RazonSocialC"];
       	}
       	else{
       		$rut = $dt5->data[0]["RutEmpresa"];
       		$nombre = $dt5->data[0]["RazonSocial"];
       	}

	    //Nombres de Dias y Meses 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		//Cambio de Fecha
		$dia = date(d);
		$mes = date(m);
		$anio = date(Y);
	
		//Nombre de archivo
		$NombreDoc = "Documento_".$datos.".pdf";
		$Porcentaje = $_REQUEST["Porcentaje"]." %";
	
        $array = array ( 
        	"idContrato" => $datos, 
        	"Dia" =>$dia, //Dia en numeros
        	"Mes" => $meses[$mes-1],  //Mes en palabras
        	"Anno" => $anio, //Año completo , Ej. 2018
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre,
        	"FormasPago" => $dt10->data[0]["FormaPago"],
        	"Equipamiento" => $_REQUEST["seleccion"], //Equipamiento
        	"Deducibles" => $_REQUEST["seleccion_ded"], //Deducibles 
        	"Porcentaje"=> $Porcentaje , 
        	"NombreDoc" => $NombreDoc,

        	//Representantes del Cliente 
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"],

        	//Representantes del Cliente 
        	"RutRepresentante_2" => $dt12->data[0]["personaid"],
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"]
        );

        $aux = array ( 
        	//Datos genericos
        	"Dia" => $dia, //Dia en numeros
        	"Mes" => $meses[$mes-1], //Mes en palabras 
        	"Anno" => $anio, //Año completo, ej.2018
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"idDocumento" => $_REQUEST["idDocumento"],

        	//Datos para Anexo
        	"idProyecto" => $_REQUEST["idProyecto"],
        	"FechaFirma" => $_REQUEST["fechaFirma"], //Fecha firma
        	"FormasPago" => $dt10->data[0]["FormaPago"], 

        	//Detalle de Anexo
        	"Marca" => $_REQUEST["Marca"] , 
	    	"Modelo" => $_REQUEST["Modelo"] ,
	    	"CiudadEntrega" => $_REQUEST["CiudadEntrega"], 
	    	"CiudadOperacion" => $_REQUEST["CiudadOperacion"] , 
	    	"CiudadDevolucion" => $_REQUEST["CiudadDevolucion"], 
	    	"PeriodoArriendo" => $_REQUEST["PeriodoArriendo"],
	    	"FechaInicio" => $_REQUEST["FechaInicio"] , 
	    	"FechaFinal" => $_REQUEST["FechaFinal"] , 
	    	"Tarifa" => $_REQUEST["Tarifa"], 
	    	"KmsExceso" => $_REQUEST["KmsExceso"] ,
	    	"KmsMensuales" => $_REQUEST["KmsMensuales"] , 
	    	"KmsContratados" => $_REQUEST["KmsContratados"], 
	    	"FrecuenciaMantencion" => $_REQUEST["FrecuenciaMantencion"] , 
	    	"FrecuenciaCambio" => $_REQUEST["FrecuenciaCambio"], 
	    	"Cantidad" => $_REQUEST["Cantidad"], 
	    	 
	    	//Datos Variables
	    	"Porcentaje" => $Porcentaje, //Porcentaje
	    	"NombrePropuesta" => $_REQUEST["Propuesta"] ,//Nombre de propuesta 
        	"FechaPropuesta" => $_REQUEST["FechaPropuesta"] , //Fecha de propuesta
        	"GPS" => $_REQUEST["GPS"], 
        	"Equipamiento" => $_REQUEST["seleccion"], // Equipamientos
        	"Deducibles" => $_REQUEST["seleccion_ded"], //Deducibles
        	"RutCoordinador" => $_REQUEST["rut_coordinador"] , //Rut Cordinador 
        	"NombreCoordinador" => $_REQUEST["nombre_coordinador"],//Nombre coordinador

        	//Otros
        	"DiaSemana" => $dias[date("w")], //Dia de semana 
        	"MesNum" => $mes, //Numero de mes
	
	    	//Datos del Aval 
	    	"Rut_Aval" => $_REQUEST["rut_aval"],
	    	"Nombre_Aval" => $_REQUEST["nombre_aval"]." ".$_REQUEST["apellido_aval"],
	    	"Correo_Aval" => $_REQUEST["correo_aval"],
	    	"Personeria_Aval" => $_REQUEST["personeria_aval"]
	    	);

        //Variables @@[RutCliente]@@
		$variables = array (
			//Datos genericos
			"@@[Dia]@@",
			"@@[Mes]@@",
			"@@[Anno]@@",
			"@@[RutEmpresa]@@",
			"@@[NombreEmpresa]@@",
			"@@[RutCliente]@@",
			"@@[NombreCliente]@@",
			"@@[idDocumento]@@",

			//Datos para Anexo
        	"@@[idProyecto]@@", 
        	"@@[FechaFirma]@@",
        	"@@[FormasPago]@@",

        	//Detalle de Anexo
        	"@@[Marca]@@", 
        	"@@[Modelo]@@",
	    	"@@[CiudadEntrega]@@",
	    	"@@[CiudadOperacion]@@",
	    	"@@[CiudadDevolucion]@@",
	    	"@@[PeriodoArriendo]@@",
	    	"@@[FechaInicio]@@",
	    	"@@[FechaFinal]@@",
	    	"@@[Tarifa]@@",
	    	"@@[KmsExceso]@@",
	    	"@@[KmsMensuales]@@",
	    	"@@[KmsContratados]@@", 
	    	"@@[FrecuenciaMantencion]@@", 
	    	"@@[FrecuenciaCambio]@@", 
	    	"@@[Cantidad]@@", 
	    	 
	    	//Datos Variable
	    	"@@[Porcentaje]@@",
	    	"@@[NombrePropuesta]@@",
        	"@@[FechaPropuesta]@@",
        	"@@[GPS]@@", 
        	"@@[Equipamiento]@@", // Equipamientos
        	"@@[Deducibles]@@", //Deducibles
        	"@@[RutCoordinador]@@", //Rut Cordinador 
        	"@@[NombreCoordinador]@@",//Nombre coordinador

        	//Otros
        	"@@[DiaSemana]@@", //Dia de semana 
        	"@@[MesNum]@@", //Numero de mes
	
	    	//Datos del Aval 
	    	"@@[Rut_Aval]@@",
	    	"@@[Nombre_Aval]@@",
	    	"@@[Correo_Aval]@@",
	    	"@@[Personeria_Aval]@@"
			);

		//Guardar en la BD
		$this->documentosBD->agregarVariables($array); 	
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Sustituir en el HTML
		$resultado =  str_replace($variables,$aux,$html);
	    return $resultado;
	}

	//Sustituir variables del Documento
	public function sustituirVariables_2($datos, $html,&$resultado){

		//Variables a instanciar 
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt7 = new DataTable();
		$dt8 = new DataTable();
		$dt9 = new DataTable();
		$dt10 = new DataTable();

		//Representantes 
		$dt11 = new DataTable();
		$dt12 = new DataTable();
		$dt13 = new DataTable();
		$dt14 = new DataTable();
		$dt15 = new DataTable();
		$dt16 = new DataTable();

		//Consultas 
		//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerEmpresa($_REQUEST,$dt1);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscar datos del Representante 1
		$cliente = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][0] );
       	$this->documentosBD->obtenerPersona($cliente,$dt11);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 2
		$cliente_1 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][1] );
       	$this->documentosBD->obtenerPersona($cliente_1,$dt12);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       		//Buscar datos del Representante 3
		$cliente_2 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][2] );
       	$this->documentosBD->obtenerPersona($cliente_2,$dt13);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 4
		$cliente_3 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][3] );
       	$this->documentosBD->obtenerPersona($cliente_3,$dt14);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 5
		$cliente_4 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][4] );
       	$this->documentosBD->obtenerPersona($cliente_4,$dt15);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       //Buscar datos del Representante 6
		$cliente_5 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][5] );
       	$this->documentosBD->obtenerPersona($cliente_5,$dt16);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar Datos que faltan del Cliente 
       	$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos de la empresa Cliente 
       	$array = array ( "RutEmpresa" => $_REQUEST["RutEmpresaC"]);
       	$this->documentosBD->obtenerEmpresa($array,$dt9);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Obtener nombre de Forma de Pago 
       	$this->documentosBD->obtenerFormasPago($_REQUEST,$dt10);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	if( $dt5->data[0]["TipoEmpresa"] == 2 ) {
       		$rut = $dt5->data[0]["RutEmpresaC"];
       		$nombre = $dt5->data[0]["RazonSocialC"];
       	}
       	else{
       		$rut = $dt5->data[0]["RutEmpresa"];
       		$nombre = $dt5->data[0]["RazonSocial"];
       	}

	    //Nombres de Dias y Meses 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		//Cambio de Fecha
		$dia = date(d);
		$mes = date(m);
		$anio = date(Y);
	
		//Nombre de archivo
		$NombreDoc = "Documento_".$datos.".pdf";;
	
		//Nombre de archivo
		$Porcentaje = $_REQUEST["Porcentaje"]." %";
		$FechaFirma = substr($_REQUEST["FechaCreacion"], 0, -8);

        $array = array ( 
        	
        	//Datos basicos
        	"idContrato" => $datos,
        	"Dia" =>$dia, 
        	"Mes" => $meses[$mes-1], 
        	"Anno" => $anio,
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"FormasPago" => $dt10->data[0]["FormaPago"],
        	"Equipamiento" => "", //Equipamiento
        	"Deducibles" => "", //Deducibles 
        	"Porcentaje"=> "" , //Porcentaje
        	"NombreDoc" => $NombreDoc ,

        	//Representantes del Cliente 
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"],

        	//Representantes del Cliente 
        	"RutRepresentante_2" => $dt12->data[0]["personaid"],
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"]
        );

        $aux = array ( 

			//Datos genericos
        	"Dia" => $dia, //Dia en numeros
        	"Mes" => $meses[$mes-1], //Mes en palabras 
        	"Anno" => $anio, //Año completo, ej.2018
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"idDocumento" => $_REQUEST["idDocumento"],

			//Datos
        	"FormasPago" => $dt10->data[0]["FormaPago"], 

        	//Datos del Contrato Renting
        	"Marca" => $_REQUEST["Marca"] , 
        	"Modelo" => $_REQUEST["Modelo"], 
        	"Patente" => $_REQUEST["Patente"], 
        	"Color" => $_REQUEST["Color"], 
        	"VIN" => $_REQUEST["VIN"], 
        	"AnnoVehiculo" => $_REQUEST["AnnoVehiculo"], 
        	"KmsMensuales" => $_REQUEST["KmsMensuales"], 
        	"FechaInicio" => $_REQUEST["FechaInicio"], 
        	"FechaFinal" => $_REQUEST["FechaFinal"], 
        	"FechaPie" => $_REQUEST["FechaPie"], 
        	"FechaPago" => $_REQUEST["FechaPago"], 
        	"CuotaPie" => $_REQUEST["CuotaPie"], 
        	"MontoCuota" => $_REQUEST["MontoCuota"], 
        	"Exceso" => $_REQUEST["Exceso"] , 
        	"KmsExceso" => $_REQUEST["KmsExceso"], 
        	"RentaMens" => $_REQUEST["RentaMens"], 
        	"PeriodoArriendo" => $_REQUEST["PeriodoArriendo"],

        	//Representante del Cliente - 1
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"], 
        	"Nacionalidad_1" => $dt11->data[0]["nacionalidad"],

        	//Representante del Cliente - 2
        	"RutRepresentante_2" => $dt12->data[0]["personaid"], 
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"],
        	"Nacionalidad_2" => $dt12->data[0]["nacionalidad"],

        	//Representantes del Cliente - 3
        	"RutRepresentante_3" => $dt13->data[0]["personaid"],	
        	"Representante_3" => $dt13->data[0]["nombre"]." ".$dt13->data[0]["appaterno"],
        	"Nacionalidad_3" => $dt13->data[0]["nacionalidad"],

        	//Representantes del Cliente - 4
        	"RutRepresentante_4" => $dt14->data[0]["personaid"],
        	"Representante_4" => $dt14->data[0]["nombre"]." ".$dt14->data[0]["appaterno"],
        	"Nacionalidad_4" => $dt14->data[0]["nacionalidad"],

        	//Representantes del Cliente - 5
        	"RutRepresentante_5" => $dt15->data[0]["personaid"],	
        	"Representante_5" => $dt15->data[0]["nombre"]." ".$dt15->data[0]["appaterno"],
        	"Nacionalidad_5" => $dt15->data[0]["nacionalidad"],

        	//Representantes del Cliente - 6
        	"RutRepresentante_6" => $dt16->data[0]["personaid"],
        	"Representante_6" => $dt16->data[0]["nombre"]." ".$dt16->data[0]["appaterno"],
        	"Nacionalidad_6" => $dt16->data[0]["nacionalidad"],

        	//Direccion del Cliente 
        	"Direccion" => $dt9->data[0]["Direccion"], 
        	"Comuna" => $dt9->data[0]["Comuna"] , 
        	"Ciudad" => $dt9->data[0]["Ciudad"],

        	//Otros
        	"DiaSemana" => $dias[date("w")], //Dia de semana 
        	"MesNum" => $mes, //Numero de mes
	
	    	//Datos del Aval 
	    	"Rut_Aval" => $_REQUEST["rut_aval"],
	    	"Nombre_Aval" => $_REQUEST["nombre_aval"]." ".$_REQUEST["apellido_aval"],
	    	"Correo_Aval" => $_REQUEST["correo_aval"],
	    	"Personeria_Aval" => $_REQUEST["personeria_aval"]
	    );

        //Variables 
		$variables = array (

			//Datos genericos
			"@@[Dia]@@",
			"@@[Mes]@@",
			"@@[Anno]@@",
			"@@[RutEmpresa]@@",
			"@@[NombreEmpresa]@@",
			"@@[RutCliente]@@",
			"@@[NombreCliente]@@",
			"@@[idDocumento]@@",

			//Datos
        	"@@[FormasPago]@@",

        	//Datos del Contrato Renting
        	"@@[Marca]@@",
        	"@@[Modelo]@@",
        	"@@[Patente]@@",
        	"@@[Color]@@",
        	"@@[VIN]@@",
        	"@@[AnnoVehiculo]@@", 
        	"@@[KmsMensuales]@@",
        	"@@[FechaInicio]@@",
        	"@@[FechaFinal]@@",
        	"@@[FechaPie]@@",
        	"@@[FechaPago]@@",
        	"@@[CuotaPie]@@",
        	"@@[MontoCuota]@@",
        	"@@[Exceso]@@",
        	"@@[KmsExceso]@@",
        	"@@[RentaMens]@@",
        	"@@[PeriodoArriendo]@@",
			
			//Representante del Cliente - 1
        	"@@[RutRepresentante_1]@@",	
        	"@@[Representante_1]@@",
        	"@@[Nacionalidad_1]@@", 

        	//Representante del Cliente - 2
        	"@@[RutRepresentante_2]@@", 
        	"@@[Representante_2]@@",
        	"@@[Nacionalidad_2]@@",

        	//Representante del Cliente - 3
        	"@@[RutRepresentante_3]@@",	
        	"@@[Representante_3]@@",
        	"@@[Nacionalidad_3]@@", 

        	//Representante del Cliente - 4
        	"@@[RutRepresentante_4]@@", 
        	"@@[Representante_4]@@",
        	"@@[Nacionalidad_4]@@",

        	//Representante del Cliente - 5
        	"@@[RutRepresentante_5]@@",	
        	"@@[Representante_5]@@",
        	"@@[Nacionalidad_5]@@", 

        	//Representante del Cliente - 6
        	"@@[RutRepresentante_6]@@", 
        	"@@[Representante_6]@@",
        	"@@[Nacionalidad_6]@@",
	    	 
	    	//Direccion del Cliente 
        	"@@[Direccion]@@",
        	"@@[Comuna]@@",
        	"@@[Ciudad]@@", 

			//Otros
        	"@@[DiaSemana]@@", //Dia de semana 
        	"@@[MesNum]@@", //Numero de mes
	
	    	//Datos del Aval 
	    	"@@[Rut_Aval]@@",
	    	"@@[Nombre_Aval]@@",
	    	"@@[Correo_Aval]@@",
	    	"@@[Personeria_Aval]@@"
		);

		//Guardar en la BD
		$this->documentosBD->agregarVariables($array); 	
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Sustituir en el HTML
		$resultado =  str_replace($variables,$aux,$html);
	    return $resultado;
	}

	//Sustituir variables del Documento
	public function sustituirVariables_3($datos, $html,&$resultado){

		//Variables a instanciar 
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt7 = new DataTable();
		$dt8 = new DataTable();
		$dt9 = new DataTable();
		$dt10 = new DataTable();

		//Representantes 
		$dt11 = new DataTable();
		$dt12 = new DataTable();
		$dt13 = new DataTable();
		$dt14 = new DataTable();
		$dt15 = new DataTable();
		$dt16 = new DataTable();

		//Consultas 
		//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerRazonSocial($_REQUEST,$dt4);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos que faltan de la Empresa
       	$this->documentosBD->obtenerEmpresa($_REQUEST,$dt1);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Buscar datos del Representante 1
		$cliente = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][0] );
       	$this->documentosBD->obtenerPersona($cliente,$dt11);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 2
		$cliente_1 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][1] );
       	$this->documentosBD->obtenerPersona($cliente_1,$dt12);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 3
		$cliente_2 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][2] );
       	$this->documentosBD->obtenerPersona($cliente_2,$dt13);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 4
		$cliente_3 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][3] );
       	$this->documentosBD->obtenerPersona($cliente_3,$dt14);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos del Representante 5
		$cliente_4 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][4] );
       	$this->documentosBD->obtenerPersona($cliente_4,$dt15);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       //Buscar datos del Representante 6
		$cliente_5 = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][5] );
       	$this->documentosBD->obtenerPersona($cliente_5,$dt16);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar Datos que faltan del Cliente 
       	$this->documentosBD->obtenerRazonSocialC($_REQUEST,$dt5);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Buscar datos de la empresa Cliente 
       	$array = array ( "RutEmpresa" => $_REQUEST["RutEmpresaC"]);
       	$this->documentosBD->obtenerEmpresa($array,$dt9);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	//Obtener nombre de Forma de Pago 
       	$this->documentosBD->obtenerFormasPago($_REQUEST,$dt10);
       	$this->mensajeError.=$this->documentosBD->mensajeError;

       	if( $dt5->data[0]["TipoEmpresa"] == 2 ) {
       		$rut = $dt5->data[0]["RutEmpresaC"];
       		$nombre = $dt5->data[0]["RazonSocialC"];
       	}
       	else{
       		$rut = $dt5->data[0]["RutEmpresa"];
       		$nombre = $dt5->data[0]["RazonSocial"];
       	}

	    //Nombres de Dias y Meses 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		//Cambio de Fecha
		$dia = date(d);
		$mes = date(m);
		$anio = date(Y);
	
		//Nombre de archivo
		$NombreDoc = "Documento_".$datos.".pdf";

        $array = array ( 

        	//Datos basicos
        	"idContrato" => $datos,
        	"Dia" =>$dia, 
        	"Mes" => $meses[$mes-1], 
        	"Anno" => $anio,
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre, 
        	"FormasPago" => $dt10->data[0]["FormaPago"],
        	"Equipamiento" => "", //Equipamiento
        	"Deducibles" => "", //Deducibles 
        	"Porcentaje"=> "" , //Porcentaje
        	"NombreDoc" => $NombreDoc ,

        	//Representantes del Cliente 
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"],

        	//Representantes del Cliente 
        	"RutRepresentante_2" => $dt12->data[0]["personaid"],
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"]
        );

        $aux = array ( 
        	//Datos genericos
        	"Dia" => $dia, //Dia en numeros
        	"Mes" => $meses[$mes-1], //Mes en palabras 
        	"Anno" => $anio, //Año completo, ej.2018
        	"RutEmpresa" => $_REQUEST["RutEmpresa"] , 
        	"RazonSocial" => $dt4->data[0]["RazonSocial"], 
        	"RutEmpresaC" => $rut, 
        	"RazonSocialC" => $nombre,
        	"idDocumento" => $_REQUEST["idDocumento"],

			//Datos
        	"FormasPago" => $dt10->data[0]["FormaPago"], 

        	//Datos de Cntrato Financiero
             "RutProveedor" => $dt9->data[0]["RutEmpresa"] ,
		     "NombreProveedor" => $dt9->data[0]["RazonSocial"],
		     "FechaInicioPago" => $_REQUEST["FechaInicioPago"], 
		     "DetalleBienes" => $_REQUEST["DetalleBienes"], 
		     "MontoAdquisicion" => $_REQUEST["MontoAdquisicion"], 
		     "CantTotal" => $_REQUEST["CantTotal"], 
		     "CantRentas" => $_REQUEST["CantRentas"], 
		     "ValorBallon" => $_REQUEST["ValorBallon"], 
		     "ValorCompra" => $_REQUEST["ValorCompra"] , 
		     "DiaPagoMensual" => $_REQUEST["DiaPagoMensual"], 
		     "DuracionContrato" => $_REQUEST["DuracionContrato"], 
		     "FechaPrepago" => $_REQUEST["FechaPrepago"], 
		     "ValorAsegurable" => $_REQUEST["ValorAsegurable"], 
		     "ValorIguales" => $_REQUEST["ValorIguales"], 

			//Representante del Cliente - 1
        	"RutRepresentante_1" => $dt11->data[0]["personaid"],	
        	"Representante_1" => $dt11->data[0]["nombre"]." ".$dt11->data[0]["appaterno"], 
        	"Nacionalidad_1" => $dt11->data[0]["nacionalidad"],

        	//Representante del Cliente - 2
        	"RutRepresentante_2" => $dt12->data[0]["personaid"], 
        	"Representante_2" => $dt12->data[0]["nombre"]." ".$dt12->data[0]["appaterno"],
        	"Nacionalidad_2" => $dt12->data[0]["nacionalidad"],

        	//Representantes del Cliente - 3
        	"RutRepresentante_3" => $dt13->data[0]["personaid"],	
        	"Representante_3" => $dt13->data[0]["nombre"]." ".$dt13->data[0]["appaterno"],
        	"Nacionalidad_3" => $dt13->data[0]["nacionalidad"],

        	//Representantes del Cliente - 4
        	"RutRepresentante_4" => $dt14->data[0]["personaid"],
        	"Representante_4" => $dt14->data[0]["nombre"]." ".$dt14->data[0]["appaterno"],
        	"Nacionalidad_4" => $dt14->data[0]["nacionalidad"],

        	//Representantes del Cliente - 5
        	"RutRepresentante_5" => $dt15->data[0]["personaid"],	
        	"Representante_5" => $dt15->data[0]["nombre"]." ".$dt15->data[0]["appaterno"],
        	"Nacionalidad_5" => $dt15->data[0]["nacionalidad"],

        	//Representantes del Cliente - 6
        	"RutRepresentante_6" => $dt16->data[0]["personaid"],
        	"Representante_6" => $dt16->data[0]["nombre"]." ".$dt16->data[0]["appaterno"],
        	"Nacionalidad_6" => $dt16->data[0]["nacionalidad"],

			//Direccion del Cliente 
        	"Direccion" => $dt9->data[0]["Direccion"], 
        	"Comuna" => $dt9->data[0]["Comuna"] , 
        	"Ciudad" => $dt9->data[0]["Ciudad"],

        	//Otros
        	"DiaSemana" => $dias[date("w")], //Dia de semana 
        	"MesNum" => $mes, //Numero de mes
	
	    	//Datos del Aval 
	    	"Rut_Aval" => $_REQUEST["rut_aval"],
	    	"Nombre_Aval" => $_REQUEST["nombre_aval"]." ".$_REQUEST["apellido_aval"],
	    	"Correo_Aval" => $_REQUEST["correo_aval"],
	    	"Personeria_Aval" => $_REQUEST["personeria_aval"]
	    );


        //Variables 
		$variables = array ( 

			//Datos genericos
			"@@[Dia]@@",
			"@@[Mes]@@",
			"@@[Anno]@@",
			"@@[RutEmpresa]@@",
			"@@[NombreEmpresa]@@",
			"@@[RutCliente]@@",
			"@@[NombreCliente]@@",
			"@@[idDocumento]@@",

			//Datos
        	"@@[FormasPago]@@",

			//Datos del Contrato Financiero 
			"@@[RutProveedor]@@", 
			"@@[NombreProveedor]@@", 
			"@@[FechaInicioPago]@@", 
			"@@[DetalleBienes]@@", 
			"@@[MontoAdquisicion]@@", 
			"@@[CantTotal]@@", 
			"@@[CantRentas]@@", 
			"@@[ValorBallon]@@", 
			"@@[ValorCompra]@@",
			"@@[DiaPagoMensual]@@", 
			"@@[DuracionContrato]@@",
			"@@[FechaPrepago]@@", 
			"@@[ValorAsegurable]@@", 
			"@@[ValorIguales]@@",

			//Representante del Cliente - 1
        	"@@[RutRepresentante_1]@@",	
        	"@@[Representante_1]@@",
        	"@@[Nacionalidad_1]@@", 

        	//Representante del Cliente - 2
        	"@@[RutRepresentante_2]@@", 
        	"@@[Representante_2]@@",
        	"@@[Nacionalidad_2]@@",

        	//Representante del Cliente - 3
        	"@@[RutRepresentante_3]@@",	
        	"@@[Representante_3]@@",
        	"@@[Nacionalidad_3]@@", 

        	//Representante del Cliente - 4
        	"@@[RutRepresentante_4]@@", 
        	"@@[Representante_4]@@",
        	"@@[Nacionalidad_4]@@",

        	//Representante del Cliente - 5
        	"@@[RutRepresentante_5]@@",	
        	"@@[Representante_5]@@",
        	"@@[Nacionalidad_5]@@", 

        	//Representante del Cliente - 6
        	"@@[RutRepresentante_6]@@", 
        	"@@[Representante_6]@@",
        	"@@[Nacionalidad_6]@@",
	    	 
	    	//Direccion del Cliente 
        	"@@[Direccion]@@",
        	"@@[Comuna]@@",
        	"@@[Ciudad]@@", 

			//Otros
        	"@@[DiaSemana]@@", //Dia de semana 
        	"@@[MesNum]@@", //Numero de mes
	
	    	//Datos del Aval 
	    	"@@[Rut_Aval]@@",
	    	"@@[Nombre_Aval]@@",
	    	"@@[Correo_Aval]@@",
	    	"@@[Personeria_Aval]@@"
		);

		//Guardar en la BD
		$this->documentosBD->agregarVariables($array); 	
       	$this->mensajeError.=$this->documentosBD->mensajeError;

		//Sustituir en el HTML
		$resultado =  str_replace($variables,$aux,$html);
	    return $resultado;
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
	
//graba log del sp
	private function graba_log ($mensaje){
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/pdf_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s")." ".$mensaje);
	   	fputs($ar,"\n");
  		fclose($ar);			
	}	
//fin	
}
?>
