<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/generar_manualesBD.php");

include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

require_once('includes/tcpdf/examples/lang/ita.php');
require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/examples/tcpdf_include.php');
require_once('config.php');

// creamos la instacia de esta clase
$page = new generar_manuales();


class generar_manuales {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $generar_manualesBD;
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
		$this->generar_manualesBD = new generar_manualesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->generar_manualesBD->usarConexion($conecc);
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
			case "BUSCAR_NOTARIA":
				$this->buscar_notaria();
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
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
					// enviamos los datos del formulario a guardar
					if ($this->generar_manualesBD->agregar($_REQUEST,$dt))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						//Imprimir la plantillas
						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;
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
					$dt6 = new DataTable();
					$dt7 = new DataTable();
					$dt8 = new DataTable();

					//Completamos los datos de la Empresa
					if ( $_REQUEST["RutEmpresa_Gama"] != "" ){
						$emp = array ("RutEmpresa" => $_REQUEST["RutEmpresa_Gama"]);
					}
					else{
						$emp = array ("RutEmpresa" => $_REQUEST["RutEmpresa"]);
					}
					
					$this->generar_manualesBD->obtenerRazonSocial($emp,$dt);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Completamos los datos de la Empresa Cliente 
					$cli = array ("RutEmpresaC" => $_REQUEST["RutEmpresa"]); 
					$this->generar_manualesBD->obtenerRazonSocialC($cli,$dt1);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Empresa
					$this->generar_manualesBD->obtenerFirmantes($emp,$dt3);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Cliente
					$this->generar_manualesBD->obtenerFirmantesC($cli,$dt4);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos si la Empresa tiene Plantilla
					$this->generar_manualesBD->listadoPlantillas($emp,$dt5);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos flujos de firmas 
					$this->generar_manualesBD->listadoFlujos($dt6); 
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Tipos de firma 
					$this->generar_manualesBD->listadoTipoFirmas($dt7);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Modelos de Contratos 
					$this->generar_manualesBD->listado($dt8);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos 
					if( $_REQUEST["modelo_contrato"] != 0 ){
						$this->generar_manualesBD->obtenerModeloContrato($_REQUEST,$dt4);
						$this->mensajeError.=$this->generar_manualesBD->mensajeError;
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

					//Sustituir etiquetas del html 
					if ( count ($dt3->data) > 0 ){
						foreach ($dt3->data as $key => $value) {
							$dt3->data[$key]["Personeria"] = strip_tags($dt3->data[$key]["Personeria"]);
						}
					}
					else{
						if ( $dt->data[0]["TipoEmpresa"] == 1 ){
							$this->mensajeAd= "La Empresa ".$dt->data[0]["RazonSocial"]." seleccionada no tiene firmantes";
						}else{
							$this->mensajeAd= "La Empresa ".$dt->data[0]["RazonSocialC"]." seleccionada no tiene firmantes";
						}
					}

					if ( count ($dt4->data) > 0 ){
						foreach ($dt4->data as $key => $value) {
							$dt4->data[$key]["Personeria"] = strip_tags($dt4->data[$key]["Personeria"]);
						}
					}
					else{
						if ( $dt1->data[0]["TipoEmpresa"] == 1 ){
							$this->mensajeAd= "La Empresa ".$dt1->data[0]["RazonSocial"]." seleccionada no tiene firmantes";
						}else{
							$this->mensajeAd= "La Empresa ".$dt1->data[0]["RazonSocialC"]." seleccionada no tiene firmantes";
						}
					}

					$dt->data[0]["input_not"] = $_REQUEST["input_not"];
					$dt->data[0]["input_aval"] = $_REQUEST["input_aval"];

					//Consultamos las empresas clientes 
					$this->generar_manualesBD->listado($dt2);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Reasignar resultados
					$formulario = $dt->data; 
					$formulario[0]["Modelo_Contrato"] = $dt2->data;
					$formulario[0]["Flujo"] = $dt6->data;
					$formulario[0]["TipoFirmas"] = $dt7->data;
					$formulario[0]["Modelo_Contrato"] = $dt8->data;

					//Firmantes
					$formulario[0]["firmantes_empresa"] = $dt3->data;
					$formulario[0]["firmantes_cliente"] = $dt4->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
					$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregar.html');
					return;

		case "BUSCAR_N": 
	
					//Inicializamos las variables
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
					$dt10 = new DataTable();
					$dt11 = new DataTable();

					//Completamos los datos de la Empresa
					$this->generar_manualesBD->obtenerRazonSocial($_REQUEST,$dt);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Completamos los datos de la Empresa Cliente 
					$this->generar_manualesBD->obtenerRazonSocialC($_REQUEST,$dt1);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Completamos los datos de la Notaria
					$this->generar_manualesBD->obtenerRazonSocialN($_REQUEST,$dt2);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Empresa
					$this->generar_manualesBD->obtenerFirmantes($_REQUEST,$dt3);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Cliente
					$this->generar_manualesBD->obtenerFirmantesC($_REQUEST,$dt4);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Notaria
					$this->generar_manualesBD->obtenerFirmantesN($_REQUEST,$dt5);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos flujos de firmas 
					$this->generar_manualesBD->listadoFlujos($dt6); 
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					$flu = array ("idWF" => $_REQUEST["Flujo"]);
					$this->generar_manualesBD->obteneFlujoFirma($flu,$dt7); 
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Tipos de firma 
					$this->generar_manualesBD->listadoTipoFirmas($dt8);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					$this->generar_manualesBD->obtenerTipoFirma($_REQUEST,$dt9);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Modelos de Contratos 
					$this->generar_manualesBD->listado($dt10);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Modelos de Contratos 
					$this->generar_manualesBD->obtenerModeloContrato($_REQUEST,$dt11);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Reasignamos datos
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
					}
					$dt->data[0]["Notaria"] = $dt2->data[0]["RutEmpresaN"]." ".$dt2->data[0]["RazonSocialN"];
					$dt->data[0]["RutEmpresaN"] = $dt2->data[0]["RutEmpresaN"];
					$dt->data[0]["Flujo"] = $dt6->data;
					$dt->data[0]["idWF"] = $dt7->data[0]["idwf"];
					$dt->data[0]["NombreWF"] = $dt7->data[0]["nombrewf"];
					$dt->data[0]["TipoFirmas"] = $dt8->data;
					$dt->data[0]["idTipoFirma"] = $dt9->data[0]["idTipoFirma"];
					$dt->data[0]["Descripcion"] = $dt9->data[0]["Descripcion"];
					$dt->data[0]["input_not"] = $_REQUEST["input_not"];
					$dt->data[0]["input_aval"] = $_REQUEST["input_aval"];
					$dt->data[0]["input_emp"] = $_REQUEST["input_emp"];
					$dt->data[0]["input_cli"] = $_REQUEST["input_cli"];
					$dt->data[0]["idMC"] = $dt11->data[0]["idMC"];
					$dt->data[0]["DescripcionMC"] = $dt11->data[0]["DescripcionMC"];

					//Reasignar resultados
					$formulario = $dt->data; 
					$formulario[0]["Flujo"] = $dt6->data;
					$formulario[0]["TipoFirmas"] = $dt8->data;
					$formulario[0]["Modelo_Contrato"] = $dt10->data;
					$formulario[0]["idDocumento_Gama"] = $_REQUEST["idDocumento_Gama"];

					//Firmantes
					$formulario[0]["firmantes_empresa"] = $dt3->data;
					$formulario[0]["firmantes_cliente"] = $dt4->data;
					$formulario[0]["firmantes_notaria"] = $dt5->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
					$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregar.html');
					return;

				case "BUSCAR_1": 
					//Inicializamos las variables
					$dt = new DataTable();
					$dt1 = new DataTable();
					$dt2 = new DataTable();
					$dt3 = new DataTable();
					$dt4 = new DataTable();
					$dt5 = new DataTable();
					$dt6 = new DataTable();
					$dt7 = new DataTable();
					$dt8 = new DataTable();

					//Completamos los datos de la Empresa
					$this->generar_manualesBD->obtenerRazonSocial($_REQUEST,$dt);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Completamos los datos de la Empresa Cliente 
					$this->generar_manualesBD->obtenerRazonSocialC($_REQUEST,$dt1);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Empresa
					$this->generar_manualesBD->obtenerFirmantes($_REQUEST,$dt3);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Firmantes la Cliente
					$this->generar_manualesBD->obtenerFirmantesC($_REQUEST,$dt4);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos si la Empresa tiene Plantilla
					$this->generar_manualesBD->listadoPlantillas($_REQUEST,$dt5);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos flujos de firmas 
					$this->generar_manualesBD->listadoFlujos($dt6); 
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Tipos de firma 
					$this->generar_manualesBD->listadoTipoFirmas($dt7);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos Modelos de Contratos 
					$this->generar_manualesBD->listado($dt8);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Buscamos 

					if( $_REQUEST["modelo_contrato"] != 0 ){
						$this->generar_manualesBD->obtenerModeloContrato($_REQUEST,$dt4);
						$this->mensajeError.=$this->generar_manualesBD->mensajeError;
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

					//Sustituir etiquetas del html 
					if ( count ($dt3->data) > 0 ){
						foreach ($dt3->data as $key => $value) {
							$dt3->data[$key]["Personeria"] = strip_tags($dt3->data[$key]["Personeria"]);
						}
					}
					else{
					
							if ( $dt->data[0]["TipoEmpresa"] == 1 ){
								$this->mensajeAd= "La Empresa ".$dt->data[0]["RazonSocial"]." seleccionada no tiene firmantes";
							}else{
								$this->mensajeAd= "La Empresa ".$dt->data[0]["RazonSocialC"]." seleccionada no tiene firmantes";
							}
					}

					if ( count ($dt4->data) > 0 ){
						foreach ($dt4->data as $key => $value) {
							$dt4->data[$key]["Personeria"] = strip_tags($dt4->data[$key]["Personeria"]);
						}
					}
					else{
						if ( $_REQUEST["RutEmpresaC"] != "" ){
							if ( $dt1->data[0]["TipoEmpresa"] == 1 ){
								$this->mensajeAd= "La Empresa ".$dt1->data[0]["RazonSocial"]." seleccionada no tiene firmantes";
							}else{
								$this->mensajeAd= "La Empresa ".$dt1->data[0]["RazonSocialC"]." seleccionada no tiene firmantes";
							}
						}
					}

					$dt->data[0]["fecha"] = $_REQUEST["fecha"];
					$dt->data[0]["FirEmpresa"] = $FirEmpresa;
					$dt->data[0]["FirEmpresaC"] = $FirEmpresaC;
					$dt->data[0]["PlaEmpresa"] = $PlaEmpresa;

					//Consultamos las empresas clientes 
					$this->generar_manualesBD->listado($dt2);
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;

					//Reasignar resultados
					$formulario = $dt->data; 
					$formulario[0]["Modelo_Contrato"] = $dt2->data;
					$formulario[0]["Flujo"] = $dt6->data;
					$formulario[0]["TipoFirmas"] = $dt7->data;
					$formulario[0]["Modelo_Contrato"] = $dt8->data;

					//Pasar datos al formulario
					$this->pagina->agregarDato("formulario",$formulario);
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
					$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregar.html');
					return;
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		
		//Inicialiamos la variable de tipo Tabla 
		$dt = new DataTable();
		$dt1 = new DataTable();

		//Buscamos flujos de firmas 
		$this->generar_manualesBD->listadoFlujos($dt); 
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Buscamos Tipos de firma 
		$this->generar_manualesBD->listadoTipoFirmas($dt1);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Reasignamos resultado a variable
		$formulario[0]["Flujo"] = $dt->data;
		$formulario[0]["TipoFirmas"] = $dt1->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregar.html');
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  

		//Inicialiamos la variable de tipo Tabla 
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Buscamos flujos de firmas 
		$this->generar_manualesBD->listadoFlujos($dt); 
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Buscamos Tipos de firma 
		$this->generar_manualesBD->listadoTipoFirmas($dt1);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Buscamos Tipos de firma 
		$this->generar_manualesBD->listado($dt2);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Reasignamos resultado a variable
		$formulario[0]["Flujo"] = $dt->data;
		$formulario[0]["TipoFirmas"] = $dt1->data;
		$formulario[0]["Modelo_Contrato"] = $dt2->data;

		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregar.html');
	}

	//Seleccionar la Empresa a la que pertenece 
	private function buscar_empresa(){

		//Declarar e instanciar variables
		$dt = new DataTable();

		//Listado de Empresas Disponibles 
		$_REQUEST["TipoEmpresa"] = 1;
		
		//Buscar todas las Empresas disponibles
		$this->generar_manualesBD->listadoEmpresas($_REQUEST,$dt);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregarEmpresa.html');
	}

	//Seleccionar los Clientes a la que pertenece 
	private function buscar_cliente(){

		//Declarar e instanciar variables
		$dt = new DataTable();

		$_REQUEST["TipoEmpresa"] = 2;
		//Buscar todas las Empresas disponibles
		$this->generar_manualesBD->listadoClientesDiferente($_REQUEST,$dt);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		if( count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]["RutEmpresa_Gama"] = $_REQUEST["RutEmpresa"];
			}
		}
		
		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$dt->data);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregarCliente.html');
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
		$this->generar_manualesBD->listadoEmpresas($_REQUEST,$dt);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;
		
		//Quitar las etiquetas de HTML antes de llevar al listado
		if ( count($dt->data) ){
			foreach ($dt->data as $key => $value) {

				$dt->data[$key]["RutEmpresa"] = $_REQUEST["RutEmpresa"];
				$dt->data[$key]["RutEmpresaC"] = $_REQUEST["RutEmpresaC"];
				$dt->data[$key]["TipoFirmas"] = $_REQUEST["TipoFirmas"];
				$dt->data[$key]["Flujo"] = $_REQUEST["Flujo"];
				$dt->data[$key]["input_not"] = $_REQUEST["input_not"];
				$dt->data[$key]["input_aval"] = $_REQUEST["input_aval"];
				$dt->data[$key]["input_emp"] = $_REQUEST["input_emp"];
				$dt->data[$key]["input_cli"] = $_REQUEST["input_cli"];
				$dt->data[$key]["modelo_contrato"] = $_REQUEST["modelo_contrato"];
				$dt->data[$key]["idDocumento_Gama"] = $_REQUEST["idDocumento_Gama"];
			}
		}
		
		$formulario = $dt->data;

		//Enviar datos al HTML
		$this->pagina->agregarDato("listado",$formulario);
		//Pasamos el error a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_manuales_FormularioAgregarNotaria.html');
	}

	//Generar el documento en PDF con los datos del formulario 
	private function generar(){

		$datos = $_REQUEST;

		//Instanciar la clase
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();

		//Datos que faltan al registro 
		$datos["idEstado"] = 1; //Creado
		$datos["FechaCreacion"] = date("d-m-Y H:i:s");
		$datos["idWF"] = $datos["Flujo"];
		$datos["idPlantilla"] = 0;
		$datos["idTipoGeneracion"] = 2; //Subido por PDF 
		$datos["idTipoDoc"] = $datos["modelo_contrato"];
		
		//Guardar los Datos del Documento 
		$this->generar_manualesBD->agregar($datos,$dt);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError; 
		$idContrato = $dt->data[0]["idContrato"];

		//Si hay firmantes 
		if( $datos["Firmantes_Emp"] ){
			//Construir Firmantes 
			$this->firmantes_completos = array();
			$this->construirFirmantes($idContrato,$this->firmantes_completos);
		}
		
		//Revision del archivo subido 
		$ruta = getcwd();
		$ruta .=  "/tmp/"; 
		$ruta .= basename($_FILES['archivo']["name"]); 

		if( $_FILES['archivo']["error"] > 0 ){
			$this->mensajeError .= "Error en la subida del archivo : ".basename($_FILES['archivo']["name"]); 
		}
		else{
			if ( move_uploaded_file($_FILES['archivo']["tmp_name"], $ruta)) {

				/*if ( $subida ) {
	               $this->mensajeOK = "Su archivo : ". basename($_FILES['archivo']["name"]) . " se ha subido con &eacute;xito";
	            } else {
	               $this->mensajeError = "Ha ocurrido un error inesperado en la subida del archivo : ".basename($_FILES['archivo']["name"]); 
	               $this->listado();
	               return;
	            }*/

				//Archivo codificado
			    $archivoaux = file_get_contents($ruta);

			    //Construir datos
			    $doc_aux = array();
		    	$doc_aux["idContrato"] = $idContrato;
				$doc_aux["NombreArchivo"] = str_replace(".pdf","",$_FILES['archivo']["name"]);
				$doc_aux["Extension"] = "pdf";
				$doc_aux["documento"] = base64_encode($archivoaux);//el archivo en base 64

				//Agregar Documentos Variables
				$this->agregarVariables($idContrato);

				//Ejecutar el SP
		        if( $this->generar_manualesBD->agregarDocumento($doc_aux) ){
		        	$this->mensajeOK = "La generaci&oacute;n fue  exitosa";
				    //Mostrar la vista previa del Documento 
				    $array = array ( "idContrato" => $idContrato );
				    $this->verdocumento($array);
				    return;
				}else{
					$this->mensajeError.=$this->generar_manualesBD->mensajeError;
				    // nos vamos al principio
					$this->agregar();
					return;
				} 
			}
			$this->mensajeError = "No se ha podido subir su archivo de forma adecuada, int&eacute;ntelo nuevamente";
			$this->listado();
			return;
		}
	}

	//Ver Documento PDF
	private function verdocumento($data){
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $data;
		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->generar_manualesBD->obtenerb64($datos,$dt); //print_r($datos)		
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
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/generar_manuales_documento.html');					
	}
	
	//Construir Arreglos de los Firmantes
	private function construirFirmantes($datos,&$resultado){

		$datos_1 = $_REQUEST;

		//Variables que faltan 
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt8 = new DataTable();

	    //Buscar datos que faltan de la Empresa
       	$this->generar_manualesBD->obtenerRazonSocial($datos_1,$dt4);
       	$this->mensajeError.=$this->generar_manualesBD->mensajeError;

       	//Buscar Datos que faltan del Cliente 
       	$this->generar_manualesBD->obtenerRazonSocialC($datos_1,$dt5);
       	$this->mensajeError.=$this->generar_manualesBD->mensajeError;

       	//Buscar Datos que faltan del Notaria
       	$this->generar_manualesBD->obtenerRazonSocialN($datos_1,$dt6);
       	$this->mensajeError.=$this->generar_manualesBD->mensajeError;
    
      	//Buscar Los estados del WorkFlow 
      	$datos_1["idWF"] = $datos_1["Flujo"];
        $this->generar_manualesBD->obtenerEstados($datos_1, $dt8);
        $this->mensajeError.=$this->generar_manualesBD->mensajeError;

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
			        	$this->generar_manualesBD->agregarFirmantes($empresa_aux);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;
			        	
			        	//Buscar datos
			        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Emp"][$i] );
			        	$this->generar_manualesBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;
			        	
			        	//Completar el arreglo
			        	$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"] , "rut" => 'RUT '.$_REQUEST["Firmantes_Emp"][$i], "nombre_emp" => "P.p ".$dt4->data[0]["RazonSocial"], "rut_emp" => "RUT ".$_REQUEST["RutEmpresa"]);
						
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
			        	$this->generar_manualesBD->agregarFirmantes($cliente_aux);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;
			        	
			        	//Buscar datos
			        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Cli"][$i] );
			        	$this->generar_manualesBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;

			        	//Completar arreglo
			        	$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"] , "rut" => 'RUT '.$_REQUEST["Firmantes_Cli"][$i], "nombre_cli" => "P.p ".$dt5->data[0]["RazonSocialC"], "rut_cli" => 'RUT '.$_REQUEST["RutEmpresaC"]);
			        	//Agregar al final
			        	array_push($f_cliente, $nuevo);
			        }
		        } // Fin del Si Estado: Pendiente por firma de Cliente

		        //Si Estado: Pendiente por firma de Notario
		  		if( $dt8->data[$key]["Nombre"] == 'Pendiente por firma Notario'){
		  			
		  			//Firmantes del Notario
		        	$f_notario = array();
		        	$notaria_aux = array();

			        //Si el flujo tiene Notaria 
			        //if( $_REQUEST["not"] == 1 ){
					if( $_REQUEST["input_not"] == 1 ){

			        	//Si el flujo tiene Notaria 
			        	 foreach ($_REQUEST["Firmantes_Not"] as $i => $valor) {
				        	//Datos faltantes 
				        	$notaria_aux = array ( "idContrato" => $datos, "RutEmpresa" => $_REQUEST["RutEmpresaN"], "RutFirmante" => $_REQUEST["Firmantes_Not"][$i], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

				        	//Agregara a la tabla
				        	$this->generar_manualesBD->agregarFirmantes($notaria_aux);
				        	$this->mensajeErro.=$this->generar_manualesBD->mensajeError;

				        	//Buscar datos
				        	$array = array( "RutEjecutivo" => $_REQUEST["Firmantes_Not"][$i] );
				        	$this->generar_manualesBD->obtenerPersona($array, $dt3);
				        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;

				        	//Completar arreglo
				        	$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"] , "rut" => 'RUT '.$_REQUEST["Firmantes_Not"][$i], "nombre_not" => "P.p ".$dt6->data[0]["RazonSocialN"], "rut_not" => 'RUT '.$_REQUEST["RutEmpresaN"]);
				        	//Agregar al final
				        	array_push($f_notario, $nuevo);
			        	}
			        }
		        }//Si Estado: Pendiente por firma de Notario

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
			        	$this->generar_manualesBD->agregarFirmantes($aval_aux);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;

			        	//Agregar aval a personas
			        	$this->generar_manualesBD->agregarPersona($datos_aval);
			        	$this->mensajeError.=$this->generar_manualesBD->mensajeError;
			 
			        	//Completar arreglo
			        	$nuevo = array( "nombre" => $_REQUEST["nombre_aval"].' '.$_REQUEST["apellido_aval"] , "rut" => 'RUT '.$_REQUEST["rut_aval"], "nombre_cli" => "P.p ".$dt5->data[0]["RazonSocialC"], "rut_cli" => 'RUT '.$_REQUEST["RutEmpresaC"]);
			        	//Agregar al final
			        	array_push($f_aval, $nuevo);
			        }
		        }// Fin del Si Estado: Pendiente por firma de Aval

	        }//Fin del Foreach de los Estados del WF
		}
       
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();
		array_push($firmantes_completos, $f_empresa);
		array_push($firmantes_completos, $f_cliente);

		//Si el flujo tiene Notario
		if( $_REQUEST["input_not"] == 1 ){
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

	//Agregar las variables del Documento
	private function agregarVariables($idContrato){

		//Inicializamos las variables
		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();

		$datos = $_REQUEST;

		//Completamos los datos de la Empresa
		$emp = array ("RutEmpresa" => $_REQUEST["RutEmpresa"]);
		$this->generar_manualesBD->obtenerRazonSocial($emp,$dt);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		$nombre_empresa = "";

		if( $dt->data[0]["TipoEmpresa"] == 1 ){
			$nombre_empresa = $dt->data[0]["RazonSocial"];
		}else{
			$nombre_empresa = $dt->data[0]["RazonSocialC"];
		}

		//Completamos los datos de la Empresa Cliente 
		$cli = array ("RutEmpresaC" => $_REQUEST["RutEmpresa"]); 
		$this->generar_manualesBD->obtenerRazonSocialC($cli,$dt1);
		$this->mensajeError.=$this->generar_manualesBD->mensajeError;

		$nombre_cliente = "";

		if( $dt1->data[0]["TipoEmpresa"] == 1){
			$nombre_cliente = $dt1->data[0]["RazonSocial"];
		}
		else{
			$nombre_cliente = $dt1->data[0]["RazonSocialC"];
		}

		//Buscar datos de los representantes de la Empresa 
		if( $datos["Firmantes_Cli"] ){

			//Buscar datos del Representante 1
			$cliente = array( "RutEjecutivo" => $datos["Firmantes_Cli"][0] );
	       	$this->generar_manualesBD->obtenerPersona($cliente,$dt3);
	       	$this->mensajeError.=$this->documentosBD->mensajeError;

	       	$rut_r1 = "";
	       	$nombre_r1 = "";

	       	if ( count($dt3->data) > 0 ){
	       		$rut_r1 = $datos["Firmantes_Cli"][0];
	       		$nombre_r1 = $dt3->data[0]["nombre"]." ".$dt3->data[0]["appaterno"];
	       	}

	       	//Buscar datos del Representante 2
			$cliente = array( "RutEjecutivo" => $datos["Firmantes_Cli"][1] );
	       	$this->generar_manualesBD->obtenerPersona($cliente,$dt4);
	       	$this->mensajeError.=$this->documentosBD->mensajeError;

	       	$rut_r2 = "";
	       	$nombre_r2 = "";

	       	if ( count($dt4->data) > 0 ){
	       		$rut_r2 = $datos["Firmantes_Cli"][1];
	       		$nombre_r2 = $dt4->data[0]["nombre"]." ".$dt4->data[0]["appaterno"];
	       	}
		}

		//Nombres de Dias y Meses 
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		//Cambio de Fecha
		$dia = date(d);
		$mes = date(m);
		$anio = date(Y);
		
		$array = array ( 

        	//Datos basicos
        	"idContrato" => $idContrato,
        	"Dia" =>$dia, 
        	"Mes" => $meses[$mes-1], 
        	"Anno" => $anio,
        	"RutEmpresa" => $datos["RutEmpresa"], 
        	"RazonSocial" => $nombre_empresa, 
        	"RutEmpresaC" => $datos["RutEmpresaC"],  
        	"RazonSocialC" => $nombre_cliente, 
        	"FormasPago" => "",//Formas de pago
        	"Equipamiento" => "", //Equipamiento
        	"Deducibles" => "", //Deducibles 
        	"Porcentaje"=> "" , //Porcentaje
        	"NombreDoc" => $_FILES['archivo']["name"], //Nombre del archivo

        	//Representantes del Cliente 
        	"RutRepresentante_1" => $rut_r1,	
        	"Representante_1" => $nombre_r1,

        	//Representantes del Cliente 
        	"RutRepresentante_2" => $rut_r2,
        	"Representante_2" => $$nombre_r2
        );
		
		//Guardar en la BD
		if ( $this->generar_manualesBD->agregarVariables($array) ){
			return true;
		}
		else{
			$this->mensajeError.=$this->generar_manualesBD->mensajeError;
			return false;
		}	
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
