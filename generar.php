<?php
error_reporting(E_ERROR);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/accesoxusuarioBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/lugarespagoBD.php");
#include_once("includes/departamentosBD.php");
include_once("includes/ContratosDatosVariablesBD.php");
include_once("includes/empleadosBD.php");
include_once("includes/subclausulasBD.php");
include_once("includes/flujofirmaBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/procesosBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/estadocivilBD.php");
include_once("generarpdf.php");
require_once('Config.php');  
require_once('Variables.php');

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$page = new generar();

class generar 
{
	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $generarBD;
	private $documentosBD;
	private $empresasBD;
	private $accesoxusuarioBD;
	private $ContratosDatosVariablesBD;
	private $empleadosBD;
	private $subclausulasBD;
	private $flujofirmaBD;
	private $PlantillasBD;
	private $procesosBD;
	private $centroscostoBD;
	private $lugarespagoBD ;
	#private $departamentosBD ;
	private $estadocivilBD;

	// para juntar los mensajes de error
	public $mensajeError="";
	// para juntar los mensajes de Ok
	private $IdArchivo="";
	private $mensajeOK="";

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

	private $html = ""; //Variable que almacenara el texto completo del documento
	private $contrato_html = ""; //Contrato en HTML
	private $tabla_anexo = ""; //Tabla del Anexo 
	private $anexo_html = ""; //Anexo en HTML
	private $firmantes_tabla = ""; //Tabla de Firmantes en HTML
	private $firmantes_completos; //Arreglo de Firmantes de un Documento
	private $firmantes_empresa;
	private $firmante_empleado;
	private $firmantes_cliente;
	private $firmantes_notaria;
	private $ordinal = array(); //Ordinal de Tabla
	private $proveedores = array();
	private $tipo_con = 0;
	private $band;
	private $orientacion = 'portrait';
	private $empleado;
	private $subclausulas;
	private $rut_empresa;

  	// funcion contructora, al instanciar
  	function __construct()
  	{
	    // creamos una instacia de la base de datos
	    $this->bd = new ObjetoBD();
	    $this->pagina = new Paginas();
	    // nos conectamos a la base de datos
	    if (!$this->bd->conectar())
	    { 
	      
	      echo 'Mensaje | No hay conexión con la base de datos!';
	      exit;
	    }

		// instanciamos del manejo de tabla
		$this->documentosBD = new documentosBD();
		$this->ContratosDatosVariablesBD = new ContratosDatosVariablesBD();
		$this->empresasBD = new empresasBD();
		$this->accesoxusuarioBD = new accesoxusuarioBD();
		$this->empleadosBD = new empleadosBD();
		$this->subclausulasBD = new subclausulasBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->procesosBD = new procesosBD();
		$this->centroscostoBD = new centroscostoBD();
		$this->estadocivilBD = new estadocivilBD();
		$this->lugarespagoBD = new lugarespagoBD();
		#$this->departamentosBD = new departamentosBD();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->ContratosDatosVariablesBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->accesoxusuarioBD->usarConexion($conecc);
		$this->empleadosBD->usarConexion($conecc);
		$this->subclausulasBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->lugarespagoBD->usarConexion($conecc);
		#$this->departamentosBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);

	}

	//Generar Documento completo 
	public function GenerarDocumento($datos){

		//print_r($datos);
		$this->documentosBD->mensajeError = '';
		$this->empresasBD->mensajeError = '';
		$this->accesodocxperfilBD->mensajeError = '';
		$this->ContratosDatosVariablesBD->mensajeError = '';
		$this->empleadosBD->mensajeError = '';
		$this->subclausulasBD->mensajeError = '';
		$this->flujofirmaBD->mensajeError = '';
		$this->PlantillasBD->mensajeError = '';
		$this->procesosBD->mensajeError = '';
		$this->centroscostoBD->mensajeError = '';
		$this->lugarespagoBD->mensajeError = '' ;
		$this->departamentosBD->mensajeError = '' ;
		$this->estadocivilBD->mensajeError = '';
		//Validacion por importacion 
		if( $datos['lugarpagoid'] != '' && $datos["idCentroCosto"] == '' ){
			$datos["idCentroCosto"] = $datos['lugarpagoid'];
		}

		$respuesta = array();
		//Validar si firmante y empleado son iguales
		$respuesta = $this->validaFirmanteEmpleado($datos);
		if( $respuesta['estado'] ){
			//->Documento
			$respuesta = $this->validarDatosDocumento($datos);
			
			if( $respuesta['estado'] ){

				//->Variables
				$respuesta = array();
				$respuesta = $this->validarDatosVariables($datos);

				if ( $respuesta['estado'] ){

					//->Empleado
					$respuesta = array();
					if( $datos['idTipoGeneracion'] == 5 ){
						$respuesta['estado'] = true;
					}else{
						$respuesta = $this->validarDatosEmpleado($datos);
					}
					if( $respuesta['estado'] ){

						//->Firmantes
						$respuesta = array();
						$respuesta = $this->validarDatosFirmantes($datos);


						if (isset($datos["pdf64"]))
						{
							if ($datos["pdf64"] != "")
							{
								$respuesta = array();
								$respuesta = $this->ImportarPDF($datos);
								
							}
							else
							{
								if( $respuesta['estado'] ){
									//Vamos a generar
									$respuesta = array();
									$respuesta = $this->GenerarDocumentoCompleto($datos);
								}
								
							}
						}
						else
						{
							if( $respuesta['estado'] ){
								//Vamos a generar
								$respuesta = array();
								if( GENERADOR_PDF == 'SERVICIO' ){
									$respuesta = $this->GenerarDocumentoConServicio($datos);
								}else{
									$respuesta = $this->GenerarDocumentoCompleto($datos);
								}
							}
						}
					}
				}

			}
		}
		return $respuesta;
		
	}

	public function validaFirmanteEmpleado($datos){
		//Validar si firmante y empleado son iguales
		//var_dump($datos['Firmantes_Emp']);
		if( count($datos['Firmantes_Emp']) ){
			
			foreach( $datos['Firmantes_Emp'] As $key => $value ){
				
				if ( $datos['newusuarioid'] == $value ){
					$respuesta = array();
					$this->mensajeError = 'Error el Representante y el Empleado deben ser distintos, verifique los datos generaci&oacute;n';
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}
			}
		}
		$this->mensajeOK = "La validacion de firmantes fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar Datos del Documento
	public function validarDatosDocumento($datos){

		$dt =  new DataTable();

		//Validar los campos obligatorios 

		if ( strlen($datos['idPlantilla']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] no debe estar vac&iacute;o, este campo pertenece al identificador del Plantilla";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idPlantilla'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->PlantillasBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if( strlen($datos['idProceso']) == 0 ) {
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] no debe estar vac&iacute;o, este campo pertenece al identificador del Proceso";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idProceso'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->procesosBD->obtener($datos, $dt);
		if ( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['RutEmpresa']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] no debe estar vac&iacute;o,";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['RutEmpresa'], 4 ) ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->empresasBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}else{
			if( count($dt->data) == 0 ){
				$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] no es de una empresa registrada";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
		}

		$this->mensajeOK = "La validacion de los datos del Documento fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar datos de las Variables 
	public function validarDatosVariables($datos){

		$dt = new DataTable();

		$datos['rutusuario'] = $datos['newusuarioid'];
		if ( strlen($datos['rutusuario']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo [rutusuario] no debe estar vac&iacute;o, este campo pertenece al  rut del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['rutusuario'], 4 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo [rutusuario] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['idCentroCosto']) == 0 ){
			if( $datos['idTipoGeneracion'] != 5 ){
				$this->mensajeError = "Function validarDatosVariables : El campo [idCentroCosto] no debe estar vac&iacute;o, este campo pertenece al identificador del Centro de Costo al que pertenece el trabajador";
			}else{
				#$this->mensajeError = "El trabajador no tiene centro de costo asignado, es posible que no se le haya generado un Documento anteriormente";
				$this->mensajeError = "Falta informacion del trabajador, ejecute carga de trabajadores";
			}
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato((string)$datos['idCentroCosto'], 1 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo [idCentroCosto]  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if( isset($datos['FechaInicio'])){
			// Parche fecha inferior como limite
			if ( strlen($datos['FechaInicio']) > 0 ){
				if (strtotime($datos['FechaInicio']) < strtotime(FECHA_MINIMA_SISTEMA))
				{
					$this->mensajeError = "Function validarDatosVariables : El campo [FechaInicio] posee un valor demasiado antiguo";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}
			}
		}

		if( isset($datos['FechaTermino'])){
			// Parche fecha inferior como limite
			if ( strlen($datos['FechaTermino']) > 0 ){
				if (strtotime($datos['FechaTermino']) < strtotime(FECHA_MINIMA_SISTEMA))
				{
					$this->mensajeError = "Function validarDatosVariables : El campo [FechaTermino] posee un valor demasiado antiguo";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}
			}
		}

		#if( isset($datos['FechaTermino2'])){
			#// Parche fecha inferior como limite
			#if ( strlen($datos['FechaTermino2']) > 0 ){
				#if (strtotime($datos['FechaTermino2']) < strtotime(FECHA_MINIMA_SISTEMA))
				#{
					#$this->mensajeError = "Function validarDatosVariables : El campo [FechaTermino2] posee un valor demasiado antiguo";
					#$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					#return $respuesta;
				#}
			#}
		#}							
		//Validar la combinacion - NIVELES: Empresa-centroscosto
		$datos["empresaid"]  = $datos['RutEmpresa'];
		#lugarespago
		if ( strlen($datos['LugarPagoid']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo [lugarpagoid] no debe estar vac&iacute;o, este campo pertenece al identificador del Centro de Costo al que pertenece el trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		if ( ! $this->validaTipoDato($datos['LugarPagoid'], 1 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo [lugarpagoid]  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		
		$datos['lugarpagoid'] = $datos['LugarPagoid'];
		$this->lugarespagoBD->obtener($datos,$dt);
		$this->mensajeError = $this->lugarespagoBD->mensajeError;
		if( $this->mensajeError != ''  ){
			$this->mensajeError .= " - Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Lugar de Pago seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
		if( count($dt->data) == 0){
			$this->mensajeError = "Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Lugar de Pago seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
		#departamento












		#centroscosto
		//$this->centroscostoBD->obtenerEmpresa($datos,$dt);
		//$this->centroscostoBD->obtenerPorLugarPago($datos,$dt);
		$this->centroscostoBD->obtenerCCPorNivel3($datos,$dt);
		$this->mensajeError .= $this->centroscostoBD->mensajeError;
		if( $this->mensajeError != ''  ){
			$this->mensajeError .= " - Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Centro de Costo seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
		if( count($dt->data) == 0){
			$this->mensajeError = "Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Centro de Costo seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "La validacion de los datos del Documento Variables fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar campos del empleado 
	public function validarDatosEmpleado($datos){

		$datos['personaid'] = $datos['newusuarioid'];
		if ( strlen($datos['personaid']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [personaid] no debe estar vac&iacute;o, este campo pertenece al  rut del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['personaid'],4 ) ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [personaid] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['nombre']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [nombre] no debe estar vac&iacute;o, este campo pertenece al  nombre del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['nombre'],1 )){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [nombre] debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		
		if( $datos['idTipoGeneracion'] != 5 ){
			if ( strlen($datos['fechanacimiento']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [fechanacimiento] no debe estar vac&iacute;o, este campo pertenece a la Fecha de Nacimiento del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['fechanacimiento'], 3)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [fechanacimiento] debe tener el formato de fecha ".VAR_FORMATO_FECHA;
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
			
			if ($datos['rolid'] != 1 && $datos['rolid'] != 2 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [rolid] debe ser 1 (Privado) o 2 (Publico)";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
		}

		// Parche fecha inferior como limite
		#if ( strlen($datos['fechanacimiento']) > 0 ){
			#if (strtotime($datos['fechanacimiento']) < strtotime(FECHA_MINIMA_SISTEMA))
			#{
				#$this->mensajeError = "Function validarDatosEmpleado : El campo [fechanacimiento] posee un valor demasiado antiguo";
				#$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				#return $respuesta;
			#}
		#}

		if ( strlen($datos['correo']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [correo] no debe estar vac&iacute;o, este campo pertenece al correo del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['correo'], 5)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo [correo] debe ser una direcci&oacute;n v&aacute;lida";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if( $datos['idTipoGeneracion'] != 5 ) {
			if ( strlen($datos['nacionalidad']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [nacionalidad] no debe estar vac&iacute;o, este campo pertenece a la nacionalidad del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['nacionalidad'], 1)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [nacionalidad]  debe ser una cadena de caracteres";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
			$datos["estadocivil"] = $datos["idEstadoCivil"]; 
			if ( strlen($datos['estadocivil']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [estadocivil] no debe estar vac&iacute;o, este campo pertenece al Estado Civil del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['estadocivil'], 2)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [estadocivil]  debe ser num&eacute;rico";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
 
			$this->estadocivilBD->obtener($datos,$dt);
			if( count( $dt->data) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [estadocivil] tiene un valor que no existe";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( strlen($datos['direccion']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [direccion] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['direccion'], 1)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [direccion]  debe ser una cadena de caracteres";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
		
			if ( strlen($datos['comuna']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [comuna] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['comuna'], 1)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [comuna]  debe ser una cadena de caracteres";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( strlen($datos['ciudad']) == 0 ){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [ciudad] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['ciudad'], 1)){
				$this->mensajeError = "Function validarDatosEmpleado : El campo [ciudad]  debe ser una cadena de caracteres";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
		}

		if(! isset($datos['estado']) )
			$datos['estado'] = ESTADO_EMPLEADO;

		$this->mensajeOK = "La validacion de los datos del Empleado fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar datos de los firmantes 
	public function validarDatosFirmantes($datos){

		$dt = new DataTable();
		
		//Buscar Flujo de firma
		$this->PlantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->PlantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			if ( $dt->leerFila()){
				$datos['idWF'] = $dt->obtenerItem('idWF');
			}
		}

		//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        $firmante_empresa = 0;

      	if( count($dt->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt->data as $key => $value) {

		        //Si Estado: Pendiente por firma de Empresa 
		        if(( $dt->data[$key]["Nombre"] == 'Pendiente por firma Representante') || ($dt->data[$key]["idEstado"] == 2 )){

		        	$firmante_empresa = 1;
		        }
				
				//Si Estado: Pendiente por firma de Empresa 2
		        if(( $dt->data[$key]["Nombre"] == 'Pendiente por firma Representante 2') || ($dt->data[$key]["idEstado"] == 10 )){

		        	$firmante_empresa = 1;
		        }
		    }
		}

		if( $firmante_empresa == 1 ){

			foreach ($datos['Firmantes_Emp'] as $key => $value) {
				if ( strlen($value) == 0 ){
					$this->mensajeError = "Function validarDatosFirmantes : El firmante no debe estar vac&iacute;o";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}

				if ( ! $this->validaTipoDato($value,4 ) ){
					$this->mensajeError = "Function validarDatosFirmantes : El rut del firmante debe tener el siguiente formato. Ejemplo: 12345678-9";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}		
			}

			$this->mensajeOK = "La validacion de los datos de los Firmantes fue correcta ";
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
			return $respuesta;

		}else{

			$this->mensajeOK = "El flujo de firma de este documento no tiene firmantes por la Empresa ";
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
			return $respuesta;
		}
	}
   
    //Validar tipos de Datos
	public function validaTipoDato($valorCelda,$TipoDato){


		if($TipoDato == 1) //String
		{
			if(!is_string((string)$valorCelda)) 
			{
				return False;
			}
			return True;
		}
	  
		if($TipoDato == 2) //Numerico
	    {
			if(is_numeric($valorCelda) )
			{
				return true;
			}
			return false;
	    }
		
		if( $TipoDato == 3 ) //Fecha
		{	
			if ( $this->validateDate($valorCelda, VAR_FORMATO_FECHA) )
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 4 ) // Rut Chileno xxxxxxxx-x
		{
			if ( $this->valida_rut($valorCelda))
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 5 ) //Correo 
		{ 
			//if( filter_var($valorCelda, FILTER_VALIDATE_EMAIL) )			
			if( $this->is_valid_email($valorCelda) )
			{	
				return true;
			}
			return false;
		}

	    return false;
	}
	
	private function is_valid_email($str)
	{
	  return (false !== strpos($str, "@") && false !== strpos($str, "."));
	}

	//Crear un nuevo Documento
	public function crearDocumento($datos){

		//Generar Documento nuevo 
		//$datos = $_REQUEST;
		$dt = new DataTable();

		if (!isset($datos['idFirma']))
		{
			$datos['idFirma'] = TIPO_FIRMA_DOC;
		}
		$datos['idTipoFirma'] = $datos['idFirma'];

		switch ($datos['idTipoFirma'])
		{
			case 1: // Manual
			{
				$datos['idEstado'] = APROBAR_MANUAL ? 1 : 4; // Generado manualmente
				break;
			}
			case 2: // Electronica
			{
				$datos['idEstado'] = 1; // Generado en espera de aprobacion
				break;
			}
		}

		if( $datos['fechadocumento'] == '' ){
			//$datos["FechaCreacion"] = date("d-m-Y H:i:s");
			$datos['FechaCreacion'] = date(VAR_FORMATO_FECHA." H:i:s");
		}
		else{
			$datos['FechaCreacion'] = $datos['fechadocumento'];
		}

		if ( $datos['idTipoGeneracion'] == '' )
			$datos['idTipoGeneracion'] = TIPO_GENERACION;

		//Buscar Flujo de firma
		$this->PlantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->PlantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			if ( $dt->leerFila()){
				$datos['idWF'] = $dt->obtenerItem('idWF');
			}
		}

		//Guardar los Datos del Documento 
		$this->documentosBD->agregar($datos,$dt);
		$this->mensajeError.= $this->documentosBD->mensajeError;     
		$datos["idDocumento"] = $dt->data[0]["idDocumento"];
		$idDocumento = $dt->data[0]["idDocumento"];
		$this->graba_log($this->mensajeError);
		if( $this->mensajeError == "" ){
			$this->mensajeOK = "Se ha generado correctamente su documento, bajo el c&oacute;digo : ".$idDocumento;
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $idDocumento);
			return $respuesta;
		}
		else{
			$this->mensajeError = "No se pudo completar la generaci&oacute;n de su documento, intente nuevamente";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
	}

	private function utf8_converter($array)
	{
	    array_walk_recursive($array, function(&$item, $key){
	        if(!mb_detect_encoding($item, 'utf-8', true)){
				$item = utf8_encode($item);
	        }
	    });
	    return $array;
	}

	public function GenerarDocumentoCompleto($datos){

		//Codificar arreglo 
		if( isset ( $datos['fichaid'] )){
			$datos = $this->utf8_converter($datos);
		}

		//Crear documento 
		$respuesta = array();
		$respuesta = $this->crearDocumento($datos);

		if( $respuesta['data'] > 0 ){
			
			$datos['idDocumento'] = $respuesta['data'];
			$idDocumento = $respuesta['data'];

			//Agrego las variables del Documento 
			$respuesta = array();
			$respuesta = $this->agregarVariables($datos);
			
			if( $respuesta['codigo'] == 200 ){
	
				//Agrego al empleado
				$respuesta = array();
				$respuesta = $this->agregarEmpleado($datos);
		
				if( $respuesta['codigo'] == 200 ){
				
					//Crear Plantilla 
					$respuesta = array();
					$respuesta = $this->crearPlantilla($datos);

					if( $respuesta['codigo'] == 200 ){

						//Generar PDF
						$html = '' ;
						$html = $respuesta['data'];
						$respuesta = array();
						$respuesta = $this->generarPDF($idDocumento, $html);

						if( $respuesta['codigo'] == 200 ){
							
							//Actualizo el documento en la Base de datos 
							$respuesta = array();
							$respuesta = $this->agregarDocumentoGenerado($idDocumento);

							if( $respuesta['codigo'] == 200 ){
								
								$this->mensajeOK = 'El documento se ha generado correctamente';
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
							}
						}
					}
				}
			}
		}

		return $respuesta;	
	}
	
	//Construir respuesta 
	private function construirRespuesta($codigo, $msj, $estado, $data = ''){
		
		$respuesta = array();
		$respuesta['codigo']  = $codigo;
		$respuesta['mensaje'] = $msj;
		$respuesta['estado']  = $estado;
		$respuesta['data'] = $data;
		return $respuesta;
	}

	//Crear Plantilla completa 
	public function crearPlantilla($datos){

		/*$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;*/
		//$datos2 = $datos;
		$dt = new DataTable();
		$html = '';

		//Construir Firmantes 
		$firmantes_completos = array();
		$this->crearFirmantes($datos,$firmantes_completos);

		if( count($firmantes_completos) > 0 ){

			//Crear Plantillas 
			$this->construirPlantilla($datos,$html);

			if( $html != '' ){

				$resultado_html = '';
				$resultado_html = $html;

				//Si es de firma manual
				if( $datos['idFirma'] == 1 ){

					$firmantes_tabla = '';
					$this->construirTablaFirmantes($datos['idDocumento'],$firmantes_completos, $firmantes_tabla);
					
					//Unir texto y tabla de Firmantes	
					$resultado_html .= $firmantes_tabla;
				}

				$resultado_html .= "</body></html>";




				//Codificacion internacional del texto
				$texto = utf8_encode($resultado_html);
				//Sustituir acentos 
				$texto_completo = ContenedorUtilidades::TildesHtml($texto);
				$this->mensajeOK = "La Plantilla fue creada de forma exitosa";
				$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $texto_completo);
				return $respuesta;

			}else{
				$this->mensajeError == '' ? $this->mensajeError = "Ha ocurrido un error inesperado, no se pudo generar la Plantilla del Documento" : $this->mensajeError;
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}
		}else{
			$this->mensajeError == '' ? $this->mensajeError = "Ha ocurrido un error inesperado, no se pudo agregar los firmantes del Documento" : $this->mensajeError;
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}		
	}

	//Construir Arreglos de los Firmantes
	private function crearFirmantes($datos,&$resultado){

		//Variables que faltan 
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt8 = new DataTable();
		$dtx9 = new DataTable();
		
		//Tipo de empresa 
		$tipoEmpresa = 0;
		//$datos["idDocumento"] = $idDocumento;

	    //Buscar datos que faltan de la Empresa
	    if ( $datos["RutEmpresa"] !='' ){
	       	$this->documentosBD->obtenerRazonSocial($datos,$dt4);
	       	$this->mensajeError.=$this->documentosBD->mensajeError;
	    }
	   
		$this->documentosBD->obtener($datos, $dtx9);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		if ($this->mensajeError == "")
		{
			if( $dtx9->leerFila() )
			{
				$datos["idWF"] = $dtx9->obtenerItem('idWF');
			}		
		}
	
      	//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt8);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        //Auxiliar para cuando tiene estado de aprobacion
        $orden = 0;
        $identificador = "";
		$identificador = "RUT N&deg;";
		$datos['Representantes'] = 0;
		$datos['Empleado'] = 0;
		$nombre = '';
		$estado = '';
		$cont = 0;
		
		if( count($dt8->data) > 0 )
		{
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
			foreach ($dt8->data as $key => $value) 
			{
				$nombre = $dt8->data[$key]["Nombre"];
				$estado = $dt8->data[$key]["idEstado"];

				if( is_numeric($key) )
				{
					//Si Estado: Pendiente por firma de Empresa 
					if(( $nombre == 'Pendiente por firma Representante') && ($estado == 2 ) )
					{
						//Firmantes de la Empresa
						$f_empresa = count($f_empresa) > 0 ? $f_empresa : array();
						$empresa_aux = array();

						// foreach ($datos["Firmantes_Emp"] as $i => $valor) {

						if( $datos["Firmantes_Emp"][$cont] != '' )
						{
							//Datos faltantes 
							$empresa_aux = array ( 
								"idDocumento" => $datos["idDocumento"], 
								"RutEmpresa" => $datos["RutEmpresa"], 
								"RutFirmante" => $datos["Firmantes_Emp"][$cont], 
								"idEstado" => $dt8->data[$key]["idEstadoWF"],
								"Orden" => $dt8->data[$key]["Orden"]
							);	      	
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError .= $this->documentosBD->mensajeError;
							//Buscar datos
							if(  $datos["Firmantes_Emp"][$cont] != '' ){
								$array = array( "RutEjecutivo" => $datos["Firmantes_Emp"][$cont] );
								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];
							//Completar el arreglo
							$nuevo = array( 
								"nombre" => ucwords(strtolower($dt3->data[0]["nombre"])).' '.ucwords(strtolower($dt3->data[0]["appaterno"])).' '.ucwords(strtolower($dt3->data[0]["apmaterno"])) , 
								"rut" => $identificador.$datos["Firmantes_Emp"][$cont]
							);
							//Agregar al final 
							array_push($f_empresa, $nuevo);
							if($this->mensajeError == "" ) $datos['Representantes']++;
							$cont++;
						}
						//}
					} //Fin del Si Estado: Pendiente por firma de Empresa 
					//Si Estado: Pendiente por firma de Empresa pero un segundo Representante 
					if(($nombre == 'Pendiente por firma Representante 2') && ($estado == 10 ) )
					{
						//Firmantes de la Empresa
						$f_empresa = count($f_empresa) > 0 ? $f_empresa : array();
						$empresa_aux = array();

						//foreach ($datos["Firmantes_Emp"] as $i => $valor) {

						if( $datos["Firmantes_Emp"][$cont] != '' )
						{

							//Datos faltantes 
							$empresa_aux = array ( 
								"idDocumento" => $datos["idDocumento"], 
								"RutEmpresa" => $datos["RutEmpresa"], 
								"RutFirmante" => $datos["Firmantes_Emp"][$cont], 
								"idEstado" => $dt8->data[$key]["idEstadoWF"],
								"Orden" => $dt8->data[$key]["Orden"]
							);	  
					
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError .= $this->documentosBD->mensajeError;

							//Buscar datos
							if(  $datos["Firmantes_Emp"][$cont] != '' ){
								$array = array( "RutEjecutivo" => $datos["Firmantes_Emp"][$cont] );

								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];

							//Completar el arreglo
							$nuevo = array( 
								"nombre" => ucwords(strtolower($dt3->data[0]["nombre"])).' '.ucwords(strtolower($dt3->data[0]["appaterno"])).' '.ucwords(strtolower($dt3->data[0]["apmaterno"])) , 
								"rut" => $identificador.$datos["Firmantes_Emp"][$cont]
							);
							//Agregar al final 
							array_push($f_empresa, $nuevo);
							if($this->mensajeError == "" ) $datos['Representantes']++;
							$cont++;
						}
					//}
					} //Fin del Si Estado: Pendiente por firma de Empresa 
					
					//Si Estado: Pendiente por firma de Empresa pero un tercer Representante 
					if(($nombre == 'Pendiente por firma Representante 3') && ($estado == 11 ) )
					{
						//Firmantes de la Empresa
						$f_empresa = count($f_empresa) > 0 ? $f_empresa : array();
						$empresa_aux = array();

						//foreach ($datos["Firmantes_Emp"] as $i => $valor) {

						if( $datos["Firmantes_Emp"][$cont] != '' )
						{

							//Datos faltantes 
							$empresa_aux = array ( 
								"idDocumento" => $datos["idDocumento"], 
								"RutEmpresa" => $datos["RutEmpresa"], 
								"RutFirmante" => $datos["Firmantes_Emp"][$cont], 
								"idEstado" => $dt8->data[$key]["idEstadoWF"],
								"Orden" => $dt8->data[$key]["Orden"]
							);	 
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError .= $this->documentosBD->mensajeError;

							//Buscar datos
							if(  $datos["Firmantes_Emp"][$cont] != '' ){
								$array = array( "RutEjecutivo" => $datos["Firmantes_Emp"][$cont] );

								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];

							//Completar el arreglo
							$nuevo = array( 
								"nombre" => ucwords(strtolower($dt3->data[0]["nombre"])).' '.ucwords(strtolower($dt3->data[0]["appaterno"])).' '.ucwords(strtolower($dt3->data[0]["apmaterno"])) , 
								"rut" => $identificador.$datos["Firmantes_Emp"][$cont]
							);
							//Agregar al final 
							array_push($f_empresa, $nuevo);
							if($this->mensajeError == "" ) $datos['Representantes']++;
							$cont++;
						}
					//}
					} //Fin del Si Estado: Pendiente por firma de Empresa 
					//Si Estado: Pendiente por firma de Empleado 
					if(($nombre == 'Pendiente por firma Empleado') && ($estado == 3 ))
					{
						//Firmantes del empleado 
						$f_empleado = array();
						$empleado_aux = array();
						if( $datos['personaid'] != '' )
							$datos['rutusuario'] = $datos['personaid'];
						if( $datos['newusuarioid'] != '' )
							$datos['rutusuario'] = $datos['newusuarioid'];
						//Buscar datos
						if ( $datos['rutusuario'] != '' ){
							$array = array( "RutEjecutivo" => $datos['rutusuario']);
							$this->documentosBD->obtenerPersona($array, $dt3);
							$this->mensajeError.=$this->documentosBD->mensajeError;
							//Completar arreglo
							$nuevo = array( 
								"nombre" => ucwords(strtolower($dt3->data[0]["nombre"])).' '.ucwords(strtolower($dt3->data[0]["appaterno"])).' '.ucwords(strtolower($dt3->data[0]["apmaterno"])) , 
								"rut" => $identificador.$datos['rutusuario'] 
							);
							//Datos faltantes 
							$empleado_aux = array ( 
								"idDocumento" => $datos["idDocumento"], 
								"RutEmpresa" =>$datos['rutusuario'], 
								"RutFirmante" => $datos['rutusuario'], 
								"idEstado" => $dt8->data[$key]["idEstadoWF"],
								"Orden" => $dt8->data[$key]["Orden"]
							);
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empleado_aux);
							$this->mensajeError.=$this->documentosBD->mensajeError;
							//Agregar al final
							array_push($f_empleado, $nuevo); 
							if($this->mensajeError == '' ) $datos['Empleado']++;
						}  
					}//Fin de Estado: Pendiente por firma de Empleado 
				}//Fin del IF
	        }//Fin del Foreach de los Estados del WF
		}
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();
		//Si el flujo tiene Empresa
		//if( $datos['Representantes'] == 1 ) array_push($firmantes_completos, $f_empresa);
		if( $datos['Representantes'] > 0 ) array_push($firmantes_completos, $f_empresa);

		//Si el flujo tiene Empleado
		if( $datos['Empleado'] == 1 ) array_push($firmantes_completos, $f_empleado);

		$resultado = array();
		$resultado = $firmantes_completos; 

		return $resultado;
	}
	
	//Construir Plantilla
	private function construirPlantilla($datos, &$resultado){
		
		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
		
		//$datos['idDocumento'] = $idDocumento;
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		/*//Buscar la Plantilla del documento
		$this->documentosBD->obtener($datos,$dt);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $dt->leerFila() ){
			$idPlantilla = $dt->obtenerItem('idPlantilla');
		}*/

		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		if( $datos['idPlantilla'] > 0 ){

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;
			if( $this->mensajeError != '' || count($dt->data) == 0 ){
				return $this->mensajeError !=  '' ? $this->mensajeError : $this->mensajeError = "La plantilla seleccionado no tiene clausulas asociadas" ;
			}

			//Agregamos etiquetas 
			$tags = '<html>';
			$html = $tags;

			//Agregamos el Estilo a la Plantilla 
			$style = '';
			$style = '<style>@page {'.$papel.'} @media print {'.$papel.'}';
			$style .= ESTILO_PDF.'</style>';
			$html .= $style;

			//Agregamos el encabezado 
			$encabezado = '';
			$encabezado = '<body><div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';
			$html .= $encabezado;

			//Variables
			$num = 0;
			$contenido = '';
			$this->ordinal = array();
			//Si encuentra la variable vacia
			$var = new variables();
			$variables_resultado = array();
			$array_valores = array();
			$array_variables = array();
			$var->construirVariablesValoresTodas($datos,$variables_resultado);
			$var->arregloMultiASimple($variables_resultado['variables'], $array_variables);
			$var->arregloMultiASimple($variables_resultado['valores'], $array_valores);

			//Construir Plantilla con las Clausulas
			if( count($dt->data) > 0){

				foreach ($dt->data as $i => $value) {
    
					$clausula = '';
					$aux = '';
					
					//Si estan el titulo y encabezado activos 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){

						if( !$var->buscarVariablesVacias($dt->data[$i]["Texto"],$array_variables,$array_valores)){

							$clausula = '';
							$this->ordinal[$num] = $dt->data [$i]["idClausula"];
							ContenedorUtilidades::numerosOrdinales(count($this->ordinal),$resultado);
							$clausula = "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong> ";

							if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
								$aux = substr($dt->data[$i]["Texto"],30);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
								$aux = substr($dt->data[$i]["Texto"],32);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
								$aux = substr($dt->data[$i]["Texto"],3);
							}
							else{
								$aux = $dt->data[$i]["Texto"];
							}
							if ( $aux != '' ){
								$clausula .= $aux;	        		
								$num++;
							}
						}else{
							$clausula = '';
						}	
					}

					//Si estan el titulo y encabezado inactivos 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
						if( !$var->buscarVariablesVacias($dt->data[$i]["Texto"],$array_variables,$array_valores)){
							$aux = $dt->data[$i]["Texto"];
							$clausula = $aux; 
						}else{
							$clausula = '';
						}
					}

					//Si esta el encabezado activo y el titulo no 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
						
						if( !$var->buscarVariablesVacias($dt->data[$i]["Texto"],$array_variables,$array_valores)){
							$this->ordinal[$num] = $dt->data [$i]["idClausula"];
							ContenedorUtilidades::numerosOrdinales(count($this->ordinal),$resultado);
							$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
							if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
								$aux = substr($dt->data[$i]["Texto"],30);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
								$aux = substr($dt->data[$i]["Texto"],32);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
								$aux = substr($dt->data[$i]["Texto"],3);
							}
							else{
								$aux = $dt->data[$i]["Texto"];
							}
							if ( $aux != '' ){
								$clausula .= $aux;	        		
								$num++;
							}
						}else{
							$clausula = '';
						}	
					}

					//Si el titulo esta activo y el encabezado no 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){

						if( !$var->buscarVariablesVacias($dt->data[$i]["Texto"],$array_variables,$array_valores)){
							$clausula = "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";

							if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
								$aux = substr($dt->data[$i]["Texto"],30);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
								$aux = substr($dt->data[$i]["Texto"],32);
							}
							else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
								$aux = substr($dt->data[$i]["Texto"],3);
							}
							else{
								$aux = $dt->data[$i]["Texto"];
							}
							if ( $aux != '' ){
								$clausula .= $aux;	        		
							}
						}else{
							$clausula = '';
						}
					}

					//Si encuentra la variable vacia
					$resultado_html = '';

					if( $var->buscarVariablesVacias($clausula,$array_variables,$array_valores)){
						$this->graba_log("RESULTADO DE BUSCAR VARIABLES VACIAS");
						$clausula = '';
						//$num--;
					}//else{
						//Sustituir Variables
						//$var->sustituirVariables($clausula,$array_variables,$array_valores,$datos,$resultado_html);
						//$clausula = $resultado_html;
					//}

					//Agregar clausulas
					$contenido .= $clausula;
				}

				//Limpiar el HTML
				$aux = '';
				$aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><img>');
				$html .= $aux;
				$html .= "</div>";

				//Sustituir las variables de la Plantilla 
				$resultado = '' ;
				$var->sustituirVariables($html,$array_variables,$array_valores,$datos,$resultado);
				$html = '';
				$html = $resultado;
				
				//Sustituir las variables de la Plantilla 
				$resultado = '' ;
				$var->sustituirVariables($html,$array_variables,$array_valores,$datos,$resultado);	   
				
				return $resultado;
			}
			else{
				if( count($dt->data) == 0 ){
					$this->mensajeError = "La Plantilla seleccionada no tiene Clausulas asociadas, no se puede generar un Documento en Blanco";
				}
				return false;
			}
        }
        else{
			$this->mensajeError = "Error, no se puede leer el id de la plantilla seleccionada";
        	return false;
        }
	}
		
	//Construir tabla de Firmates de un Documento 
	private function construirTablaFirmantes($id,$datos,&$resultado){

		//Declarar variable para firmantes 
		$tabla_firmantes = ''; 
		$num = 0;
		$div = 0;
		//$encabezado = '<div><table border="0" width="100%" style="page-break-inside:avoid; padding-top: 1cm; "><tbody><tr>';
		$encabezado = '<div><table border="0" width="100%" style="page-break-inside:avoid; padding-top: 1cm; "><tbody>';

		$dt = new DataTable();
		$array = array( "idDocumento" => $id );
		$this->documentosBD->obtenerFirmantesXDocumento($array,$dt); 
		$this->mensajeError = $this->documentosBD->mensajeError;

		if (count($datos) > 0)
		{
			$lista = array();
			foreach ($datos as $i => $value) 
			{
				foreach ($datos[$i] as $j => $value) 
				{
					if (count($datos[$i]) > 0)
					{
						array_push($lista, $datos[$i][$j]);
					}
				}
			}
			//array_shift($lista);
			//array_shift($lista);
			//array_push($lista, $lista[2]);	
			//array_push($lista, $lista[2]);	
			for ($i = 0, $nuevaFila = true; $i < count($lista); $i+=2)
			{
				$tabla_firmantes .= $i == 0 ? $encabezado : '';
				$tabla_firmantes .= '<tr>';

				if (isset($lista[$i + 1]))
				{
					$tabla_firmantes .= '<td align="center"><p style="line-height: 1.3"><strong>';
					$tabla_firmantes .= '<br>';
					$tabla_firmantes .= '_________________________________<br>';
					foreach ($lista[$i] as $llave=>$valor)
					{
						$tabla_firmantes .= "{$valor}<br>";
					}
					$tabla_firmantes .= '</strong></p></td>';
					$tabla_firmantes .= '<td align="center"><p style="line-height: 1.3"><strong>';
					$tabla_firmantes .= '<br>';
					$tabla_firmantes .= '_________________________________<br>';
					foreach ($lista[$i + 1] as $llave=>$valor)
					{
						$tabla_firmantes .= "{$valor}<br>";
					}
					$tabla_firmantes .= '</strong></p></td>';
				}
				else
				{
					$tabla_firmantes .= '<td colspan="2" align="center"><p style="line-height: 1.3"><strong>';
					$tabla_firmantes .= '<br>';
					$tabla_firmantes .= '_________________________________<br>';
					foreach ($lista[$i] as $llave=>$valor)
					{
						$tabla_firmantes .= "{$valor}<br>";
					}
					$tabla_firmantes .= '</strong></p></td>';
				}
				$tabla_firmantes .= '</tr>';
			}
			$tabla_firmantes .= $i == count($lista) - 1 ? '</tbody></table></div>' : '';
			#$tabla_firmantes .= '</tbody></table></div>';
		}

		//Reasignamos al atributo de la clase 
		return $resultado = $tabla_firmantes; 
		//FIN
	}
	
	//Generar el documento en PDF, recibe el idDocumento
	public function generarPDF($idDocumento,$html){
		try {	
				$dompdf = new Dompdf();
				
				$dompdf->loadHtml($html);
				
				// (Optional) Setup the paper size and orientation
				$dompdf->set_paper('Letter', $this->orientacion);
							
				// Render the HTML as PDF
				$dompdf->render();
	  	
	  			$dompdf->getCanvas()->page_text(293, 750, "{PAGE_NUM}", $font, 10, array(0,0,0));
										
				$pdf = $dompdf->output();
				
				//Asignar ruta del documento a generar
				$ruta = dirname(__FILE__).'/'.CARPETA.'/'.NOMBRE_DOC.'_'.$idDocumento.'.pdf';	
					
				file_put_contents($ruta, $pdf);

				$this->mensajeOK = "El PDF del Documento solicitado, se genero correctamente";
				$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
				return $respuesta;
			
			
			} catch (Exception $e) {
				echo  'Excepción capturada: ',  $e->getMessage(), "\n";
			}
	}
	
	//Sustituir acentos &nbsp;
	/*private static function TildesHtml($cadena){ 

        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","°","º","> <","<span> "),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","&deg;","&deg;",">&nbsp;<","<span>&nbsp;"), $cadena);     
    }*/
	
	//Valida Fecha
	private function validateDate($date, $format = VAR_FORMATO_FECHA){	
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	//Valida Tipo de Rut Chileno 
	private function valida_rut($rut)
	{
		$pos = strpos($rut, "-");
		if ($pos === false) 
		{
			return false;
		}		
		
	    $rut = preg_replace('/[^k0-9]/i', '', $rut);
	    $dv  = substr($rut, -1);
	    $numero = substr($rut, 0, strlen($rut)-1);
	    $i = 2;
	    $suma = 0;
	    foreach(array_reverse(str_split($numero)) as $v)
	    {
	        if($i==8)
	            $i = 2;
	        $suma += $v * $i;
	        ++$i;
	    }
	    $dvr = 11 - ($suma % 11);
	    
	    if($dvr == 11)
	        $dvr = 0;
	    if($dvr == 10)
	        $dvr = 'K';
	    if($dvr == strtoupper($dv))
	        return true;
	    else
	        return false;
	}

	//Convertir a formato de fecha larga 
	private function convertirFechaLarga($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%d de %B de %Y", strtotime($fecha));
		#return ucwords($resultado);
		return $resultado;
	}

	//Convertir a formato de fecha corta 
	private function convertirFechaCorta($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%B de %Y", strtotime($fecha));
		return ucwords($resultado);
		#return $resultado;
	}

	//Agregar variables de documento 
	public function agregarVariables($datos){

		$dt = new DataTable();

		$this->ContratosDatosVariablesBD->agregar($datos);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(300,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "Las variables del Documento han sido agregadas correctamente";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;

	}

	//Agregar empleado  
	public function agregarEmpleado($datos){

		$dt = new DataTable();

		$datos['personaid'] = $datos['newusuarioid'];
		$datos['usuarioid'] = $datos['newusuarioid'];
											   
		$datos['estadocivil'] = $datos['idEstadoCivil'];

		if( ! isset($datos['rolid'])){
			$datos['rolid'] = ROL;
		}

		if( ! isset($datos['TipoCorreo'])){
			$datos['TipoCorreo'] = CODIGO_CORREO_USUARIO_NUEVO;
		}
		
		if( ! isset($datos['TipoFirma'])){
			$datos['TipoFirma'] = TIPO_FIRMA_EMPLEADO;
		}
		
		if( ! isset($datos['TipoUsuario'])){
			$datos['TipoUsuario'] = PERFIL_USUARIO;
		}
		
		//Buscar si existe el usuario  
		$this->empleadosBD->agregarConUsuario($datos);
		$this->mensajeError .= $this->empleadosBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(300,$this->mensajeError,false);
			return $respuesta;
		}

		$this->mensajeOK = "El empleado ha sido agregado correctamente";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;		
	}

	//Agregar empleado  
	public function agregarDocumentoGenerado($idDocumento){

		$dt = new DataTable();
		$ruta = dirname(__FILE__).'/'.CARPETA.'/'.NOMBRE_DOC.'_'.$idDocumento.'.pdf';

		//Guardar registro del archivo codificado
		$archivo = file_get_contents($ruta);

		//Preparar datos necesarios 
		$datos['idDocumento'] = $idDocumento;
		$datos["NombreArchivo"] = "Documento_". $idDocumento;
		$datos["Extension"] = "pdf";
		$datos["documento"] = base64_encode($archivo);//el archivo en base 64

		//Ejecutar el SP
		$this->documentosBD->agregarDocumento($datos);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(300,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "El documento se ha actualizado en la base de datos correctamente";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
		
	}

	//Funcion para devolver numeros ordinales 
	public function numerosALetras($xcifra)

	//------    CONVERTIR NUMEROS A LETRAS         ---------------
	//------    Máxima cifra soportada: 18 dígitos con 2 decimales
	//------    999,999,999,999,999,999.99
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE BILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE MILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE PESOS 99/100 M.N.
	//------    Creada por:                        ---------------
	//------             ULTIMINIO RAMOS GALÁN     ---------------
	//------            uramos@gmail.com           ---------------
	//------    10 de junio de 2009. México, D.F.  ---------------
	//------    PHP Version 4.3.1 o mayores (aunque podría funcionar en versiones anteriores, tendrías que probar)

	{
	    $xarray = array(0 => "Cero",
	        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
	        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
	        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
	        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
	    );
		//
	    $xcifra = trim($xcifra);
	    $xlength = strlen($xcifra);
	    $xpos_punto = strpos($xcifra, ".");
	    $xaux_int = $xcifra;
	    $xdecimales = "00";
	    if (!($xpos_punto === false)) {
	        if ($xpos_punto == 0) {
	            $xcifra = "0" . $xcifra;
	            $xpos_punto = strpos($xcifra, ".");
	        }
	        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
	    }

	    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
	    $xcadena = "";
	    for ($xz = 0; $xz < 3; $xz++) {
	        $xaux = substr($XAUX, $xz * 6, 6);
	        $xi = 0;
	        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
	        $xexit = true; // bandera para controlar el ciclo del While
	        while ($xexit) {
	            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
	                break; // termina el ciclo
	            }

	            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
	            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
	            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
	                switch ($xy) {
	                    case 1: // checa las centenas
	                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
	                            
	                        } else {
	                            $key = (int) substr($xaux, 0, 3);
	                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
	                                if (substr($xaux, 0, 3) == 100)
	                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
	                            }
	                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
	                                $key = (int) substr($xaux, 0, 1) * 100;
	                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
	                                $xcadena = " " . $xcadena . " " . $xseek;
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 0, 3) < 100)
	                        break;
	                    case 2: // checa las decenas (con la misma lógica que las centenas)
	                        if (substr($xaux, 1, 2) < 10) {
	                            
	                        } else {
	                            $key = (int) substr($xaux, 1, 2);
	                            if (TRUE === array_key_exists($key, $xarray)) {
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux);
	                                if (substr($xaux, 1, 2) == 20)
	                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3;
	                            }
	                            else {
	                                $key = (int) substr($xaux, 1, 1) * 10;
	                                $xseek = $xarray[$key];
	                                if (20 == substr($xaux, 1, 1) * 10)
	                                    $xcadena = " " . $xcadena . " " . $xseek;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 1, 2) < 10)
	                        break;
	                    case 3: // checa las unidades
	                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
	                            
	                        } else {
	                            $key = (int) substr($xaux, 2, 1);
	                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
	                            $xsub = $this->subfijo($xaux);
	                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                        } // ENDIF (substr($xaux, 2, 1) < 1)
	                        break;
	                } // END SWITCH
	            } // END FOR
	            $xi = $xi + 3;
	        } // ENDDO

	        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
	        if (trim($xaux) != "") {
	            switch ($xz) {
	                case 0:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN BILLON ";
	                    else
	                        $xcadena.= " BILLONES ";
	                    break;
	                case 1:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN MILLON ";
	                    else
	                        $xcadena.= " MILLONES ";
	                    break;
	                case 2:
	                    if ($xcifra < 1) {
	                        $xcadena = "CERO ";
	                    }
	                    if ($xcifra >= 1 && $xcifra < 2) {
	                        $xcadena = "UN ";
	                    }
	                    if ($xcifra >= 2) {
	                        $xcadena.= " "; //
	                    }
	                    break;
	            } // endswitch ($xz)
	        } // ENDIF (trim($xaux) != "")
	        // ------------------      en este caso, para México se usa esta leyenda     ----------------
	        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	    } // ENDFOR ($xz)
	    return trim($xcadena);
	}

	public function ImportarPDF($datos)
	{
		//Crear documento 
		$respuesta = array();
		$respuesta = $this->crearDocumento($datos);

		if( $respuesta['data'] > 0 ){
			
			$datos['idDocumento'] = $respuesta['data'];
			$idDocumento = $respuesta['data'];

			//Agrego las variables del Documento 
			$respuesta = array();
			$respuesta = $this->agregarVariables($datos);
			
			if( $respuesta['codigo'] == 200 ){
	
				//Agrego al empleado
				$respuesta = array();
				$respuesta = $this->agregarEmpleado($datos);
		
				if( $respuesta['codigo'] == 200 ){
					
						$this->crearFirmantes($datos,$firmantes_completos);

						if( count($firmantes_completos) < 1 )
						{
							
							$this->mensajeError = "Ha ocurrido un error inesperado, no se pudo agregar los firmantes del Documento.";
							$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
							return $respuesta;
							
						}

					/*
					//Crear Plantilla 
					$respuesta = array();
					$respuesta = $this->crearPlantilla($datos);
					
					if( $respuesta['codigo'] == 200 ){

						//Generar PDF
						$html = '' ;
						$html = $respuesta['data'];
						$respuesta = array();
						$respuesta = $this->generarPDF($idDocumento, $html);

						if( $respuesta['codigo'] == 200 ){
							
							//Actualizo el documento en la Base de datos 
							$respuesta = array();
							$respuesta = $this->agregarDocumentoGenerado($idDocumento);

							if( $respuesta['codigo'] == 200 ){
								
								$this->mensajeOK = 'El documento se ha generado correctamente';
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
							}
						}
					}
					*/
					
					$respuesta = array();
					$pdf64 = $datos["pdf64"];
					$respuesta = $this->agregarDocumentoGeneradoImportacion($idDocumento,$pdf64);	
					if( $respuesta['codigo'] == 200 ){
								
						$this->mensajeOK = 'El documento se ha generado correctamente';
						$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
					}				
				}
			}
		}

		return $respuesta;			
	}
	

	//Agregar empleado  
	public function agregarDocumentoGeneradoImportacion($idDocumento,$pdf64){

		$dt = new DataTable();
		$ruta = dirname(__FILE__).'/'.CARPETA.'/'.NOMBRE_DOC.'_'.$idDocumento.'.pdf';

		//Preparar datos necesarios 
		$datos['idDocumento'] = $idDocumento;
		$datos["NombreArchivo"] = "Documento_". $idDocumento;
		$datos["Extension"] = "pdf";
		$datos["documento"] = $pdf64;//el archivo en base 64

		//Ejecutar el SP
		$this->documentosBD->agregarDocumento($datos);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(300,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "El documento se ha actualizado en la base de datos correctamente";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
		
	}
	// END FUNCTION

	public function subfijo($xx)
	{ // esta función regresa un subfijo para la cifra
	    $xx = trim($xx);
	    $xstrlen = strlen($xx);
	    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
	        $xsub = "";
	    //
	    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
	        $xsub = "MIL";
	    
	    return $xsub;
	}

	//Graba log de entradas 
	private function graba_log ($mensaje){

		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimp'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	//Graba logs de resultado 
	private function graba_log_resultado ($mensaje){

		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimpresultado'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	public function GenerarDocumentoConServicio($datos){

		//Crear documento 
		$respuesta = array();
		$respuesta = $this->crearDocumento($datos);

		$this->graba_log_resultado(implode(",",$respuesta));
		if( $respuesta['data'] > 0 ){
			
			$datos['idDocumento'] = $respuesta['data'];
			$idDocumento = $respuesta['data'];

			//Agrego las variables del Documento 
			$respuesta = array();
			$respuesta = $this->agregarVariables($datos);


			if( $respuesta['codigo'] == 200 ){
	
				//Agrego al empleado
				$respuesta = array();
				$respuesta = $this->agregarEmpleado($datos);

				if( $respuesta['codigo'] == 200 ){
				
					//Crear Plantilla 
					$respuesta = array();
					$respuesta = $this->crearPlantillaCompleta($datos);
					#$respuesta = $this->crearPlantillaParaServicio($idDocumento, $datos['idFirma'], $datos);

					if( $respuesta['codigo'] == 200 ){

						//Generar PDF
						$html = '' ;
						$html = $respuesta['data'];
						$respuesta = array();
						$respuesta = $this->generarPDFConServicio($idDocumento);

						if( $respuesta['codigo'] == 200 ){
							
							//Actualizo el documento en la Base de datos 

							$respuesta = array();
							$respuesta = $this->agregarDocumentoGeneradoConServicio($idDocumento);

							if( $respuesta['codigo'] == 200 ){
								
								$this->mensajeOK = 'El documento se ha generado correctamente';
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
							}
						}
					}
				}
			}
		}

		return $respuesta;	

	}

	//Crear Plantilla completa 
	public function crearPlantillaCompleta($datos){

		//$datos = $_REQUEST;
		//$datos2 = $empleado;
		//$datos2['idDocumento'] = $idDocumento;
		$idDocumento = $datos['idDocumento'];
		$dt = new DataTable();
		$html = '';

		//Construir Firmantes 
		$firmantes_completos = array();
		//$this->crearFirmantes($idDocumento,$datos,$firmantes_completos);
		$this->crearFirmantes($datos,$firmantes_completos);


		if( count($firmantes_completos) > 0 ){

			//Crear Plantillas 
			$this->construirPlantillaCompleta($datos,$html);

			if( $html != '' ){
					
				$resultado_html = '';
				$resultado_html = $html;

				//$this->sustituirVariables($idDocumento,$html,$datos,$resultado_html); 
							
				//Si es de firma manual
				if( $datos['idFirma'] == 1 ){

					$firmantes_tabla = '';
					$this->construirTablaFirmantes($datos['idDocumento'],$firmantes_completos, $firmantes_tabla);
					
					//Unir texto y tabla de Firmantes	
					$resultado_html .= $firmantes_tabla;
				}

				$resultado_html .= "</body></html>";




			
				//Codificacion internacional del texto
				//$texto = utf8_encode($resultado_html);
				$texto = $resultado_html;
				//Sustituir acentos 
				$texto_completo = ContenedorUtilidades::TildesHtml($texto); 
									
				//Generar un archivo de html
				$archivo = fopen(RUTA_GENERACION_ARCHIVO.NOMBRE_DOC.'_'.$datos['idDocumento'].".html", "w");
				fwrite($archivo, $texto_completo);
				fclose($archivo);

				if( $archivo ) {
					$this->mensajeOK = "La Plantilla fue creada de forma exitosa";
					$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $texto_completo);
					return $respuesta;
				}
				$this->mensajeError = $archivo;
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}else{
				if( $this->mensajeError == '' ){
					$this->mensajeError = "Ha ocurrido un error inesperado, no se pudo generar la Plantilla del Documento";
				}
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}
		}else{
			$this->mensajeError == '' ? $this->mensajeError = "Ha ocurrido un error inesperado, no se pudo agregar los firmantes del Documento" : $this->mensajeError;
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}

	}

	//Construir Plantilla
	private function construirPlantillaCompleta($datos, &$resultado){

		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
		
		//$datos['idDocumento'] = $idDocumento;
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		if ( ! isset($datos['idPlantilla'] )){
			//Buscar la Plantilla del documento
			$this->documentosBD->obtener($datos,$dt);
			$this->mensajeError .= $this->documentosBD->mensajeError;

			if( $dt->leerFila() ){
				$datos['idPlantilla'] = $dt->obtenerItem('idPlantilla');
			}
		}

		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		if( $datos['idPlantilla'] > 0 ){

			//$datos['idPlantilla'] = $idPlantilla;

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;
			if( $this->mensajeError != '' || count($dt->data) == 0 ){
				return $this->mensajeError !=  '' ? $this->mensajeError : $this->mensajeError = "La plantilla seleccionado no tiene clausulas asociadas" ;
			}

			//Agregamos etiquetas 
			$tags = '<html>';
			$html = $tags;

			//Agregamos el Estilo a la Plantilla 
			$style = '';
			$style = '<style>@page {'.$papel.'} @media print {'.$papel.'}';
			$style .= ESTILO_PDF.'</style>';
			$html .= $style;

			//Agregamos el encabezado 
			$encabezado = '';
			$encabezado = '<body><div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';
			$html .= $encabezado;

			//Variables
			$num = 1;
			$ultimo_num = 0;
			$contenido = '';

			//Si encuentra la variable vacia
			$var = new variables();
			$variables_resultado = array();
			$array_valores = array();
			$array_variables = array();
			$var->construirVariablesValoresTodas($datos,$variables_resultado);
			$var->arregloMultiASimple($variables_resultado['variables'], $array_variables);
			$var->arregloMultiASimple($variables_resultado['valores'], $array_valores);

			//Construir Plantilla con las Clausulas
			if ($datos["pdf64"] == ""){
			
				if( count($dt->data) > 0){

					foreach ($dt->data as $i => $value) {
					
						$clausula = '';
						$aux = '';
						
						/*if( $num <= 0 || $num < $ultimo_num ){
							$num = $ultimo_num++;
						}*/
						$this->graba_log("Num : ".$num." Ultimo num: ".$ultimo_num);		
						if ($dt->data[$i]["SaltoPagina"] == 1)
						{
							$clausula ='<DIV style="page-break-before:always"></DIV>';
						}
					
						//Si estan el titulo y encabezado activos 
						if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){
							
							$this->ordinal[$num] = $dt->data [$i]["idClausula"];
							ContenedorUtilidades::numerosOrdinales($num,$resultado);
							$ultimo_num = $num;
							
							if( INLINE_ENCABEZADO_ORDINAL == 1 ){
								$clausula .= "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong>";
								
								if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
									$aux = substr($dt->data[$i]["Texto"],30);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
									$aux = substr($dt->data[$i]["Texto"],32);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
									$aux = substr($dt->data[$i]["Texto"],3);
								}
							}else{
								$clausula .= "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong></p> ";
								$aux = $dt->data[$i]["Texto"];
							}

							if ( $aux != '' ){
								$clausula .= $aux;	        		
								$num++;
							}
						}

						//Si estan el titulo y encabezado inactivos 
						if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
							$aux = $dt->data[$i]["Texto"];
							$clausula .= $aux; 
						}

						//Si esta el encabezado activo y el titulo no 
						if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){

							$this->ordinal[$num] = $dt->data [$i]["idClausula"];
							ContenedorUtilidades::numerosOrdinales($num,$resultado);
							$ultimo_num = $num;
							
							if( INLINE_ENCABEZADO_ORDINAL == 1 ){
								$clausula .= "<p><strong><u>".$resultado."</u></strong>: ";
								
								if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
									$aux = substr($dt->data[$i]["Texto"],30);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
									$aux = substr($dt->data[$i]["Texto"],32);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
									$aux = substr($dt->data[$i]["Texto"],3);
								}
							}else{
								$clausula .= "<p><strong><u>".$resultado."</u></strong>: </p>";
								$aux = $dt->data[$i]["Texto"];
							}
							
							if ( $aux != '' ){
								$clausula .= $aux;	        		
								$num++;
							}
						}
				
						//Si el titulo esta activo y el encabezado no 
						if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){

							if( INLINE_ENCABEZADO_ORDINAL == 1 ){
								$clausula .= "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";
								if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
									$aux = substr($dt->data[$i]["Texto"],30);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
									$aux = substr($dt->data[$i]["Texto"],32);
								}
								if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
									$aux = substr($dt->data[$i]["Texto"],3);
								}
							}else{
								$clausula .= "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>:</p> ";
								$aux = $dt->data[$i]["Texto"];
							}
							if ( $aux != '' ){
								$clausula .= $aux;	        		
							}
						}

						//Si encuentra la variable vacia
						$resultado_html = '';
						if( $var->buscarVariablesVacias($clausula,$array_variables,$array_valores)){
							$clausula = '';
							//$num--;
						}//else{
							//Sustituir Variables
							//$var->sustituirVariables($clausula,$array_variables,$array_valores,$datos,$resultado_html);
							//$clausula = $resultado_html;
						//}
						//Agregar clausulas
						$contenido .= $clausula;
					}

					//Limpiar el HTML
					$aux = '';
					$aux = $contenido;
					$html .= $aux;
					$html .= "</div>";

					//Sustituir las variables de la Plantilla 
					$resultado = '' ;
					$var->sustituirVariables($html,$array_variables,$array_valores,$datos,$resultado);
					$html = '';
					$html = $resultado;

					//Sustituir las variables de la Plantilla 
					$resultado = '' ;
					$var->sustituirVariables($html,$array_variables,$array_valores,$datos,$resultado);
					return $resultado;
				}
				else{
					if( count($dt->data) == 0 ){
						$this->mensajeError = "La Plantilla seleccionada no tiene Clausulas asociadas, no se puede generar un Documento en Blanco";
					}
					return false;
				}
			}else{
				return '';
			}
        }
        else{
			$this->mensajeError = "Error, no se puede leer el id de la plantilla seleccionada";
        	return false;
        }
	}

	//Generar el documento en PDF, recibe el idDocumento
	public function generarPDFconServicio($idDocumento){
	
		$generarpdf  = new generarpdf();

		$this->graba_log("Inicio de generacion por servicio");



		if( $generarpdf->generarDocumentoPorConsola($idDocumento) ){
		#if( $generarpdf->generar($idDocumento) ){
			$this->graba_log("Fin");
			$this->mensajeOK = "El PDF del Documento solicitado, se genero correctamente";
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
			return $respuesta;
		}

		$this->mensajeError = 'Ocurrio un error inesperado en la generaci&oacute;n del PDF, con servicio';
		$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
		return $respuesta;


	}

	//Agregar empleado  
	public function agregarDocumentoGeneradoConServicio($idDocumento){

		$dt = new DataTable();
		$ruta = RUTA_GENERACION_ARCHIVO.NOMBRE_DOC.'_'.$idDocumento.'.pdf';


		//Guardar registro del archivo codificado
		$archivo = file_get_contents($ruta);

		//Preparar datos necesarios 
		$datos['idDocumento'] = $idDocumento;
		$datos["NombreArchivo"] = $idDocumento;
		$datos["Extension"] = "pdf";
		$datos["documento"] = base64_encode($archivo);//el archivo en base 64

		//Ejecutar el SP
		$this->documentosBD->agregarDocumento($datos);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(300,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "El documento se ha actualizado en la base de datos correctamente";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Construir Plantilla
	public function construirPlantillaSinDocumento($idPlantilla,$arreglo_variables,&$resultado){

		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
	   	$datos = array();
		
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		//Asignación de plantilla
		$datos['idPlantilla'] = $idPlantilla;

		if( $datos['idPlantilla'] > 0 ){

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;

			if( $this->mensajeError != '' || count($dt->data) == 0 ){
				return false;
			}

			//Agregamos etiquetas 
			$tags = '<html>';
			$html = $tags;

			//Agregamos el Estilo a la Plantilla 
			$style = '';
			$style = '<style>@page {'.$papel.'} @media print {'.$papel.'}';
			$style .= ESTILO_PDF.'</style>';
			$html .= $style;

			//Agregamos el encabezado 
			$encabezado = '';
			$encabezado = '<body><div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';
			$html .= $encabezado;

			//Variables
			$num = 1;
			$contenido = '';

			//Construir Plantilla con las Clausulas
			if( count($dt->data) > 0){

				foreach ($dt->data as $i => $value) {
				
					$clausula = '';
					$aux = '';
					
					//Si estan el titulo y encabezado activos 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
						ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
						$clausula = "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong> ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						else{
							$aux = $dt->data[$i]["Texto"];
						}
						
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
					}

					//Si estan el titulo y encabezado inactivos 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
						$aux = $dt->data[$i]["Texto"];
						$clausula = $aux; 
					}

					//Si esta el encabezado activo y el titulo no 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
						ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
						$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
							
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						else{
							$aux = $dt->data[$i]["Texto"];
						}
						
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
								
					}
				
					//Si el titulo esta activo y el encabezado no 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){

						$clausula = "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						else if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						else{
							$aux = $dt->data[$i]["Texto"];
						}
						
						if ( $aux != '' ){
							$clausula .= $aux;	        		
						}
					}

					//Si encuentra la variable vacia
					$resultado_html = '';
					$arreglo = array();
					$array_variables = array();
					$array_valores = array();

					$var = new variables();

					$array_variables = $arreglo_variables['variables'];
					$array_valores = $arreglo_variables['valores'];

					if( $var->buscarVariablesVacias($clausula,$array_variables,$array_valores)){
						$clausula = '';
						$num--;
					}

					//Agregar clausulas
					$contenido .= $clausula;
				}

				//Limpiar el HTML
				$aux = '';
				$aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><img>');
				$html .= $aux;
				$html .= "</div>";

				//Sustituir las variables de la Plantilla 
				$resultado = '' ;
				$var->sustituirVariablesSinDocumento($html,$array_variables,$array_valores,$resultado);
				$html = '';
				$html = $resultado;
			
				//Sustituir las variables de la Plantilla 
				$resultado = '' ;
				$var->sustituirVariablesSinDocumento($html,$array_variables,$array_valores,$resultado);	   
			
				return $resultado;
			}
			else{
				if( count($dt->data) == 0 ){
					$this->mensajeError = "La Plantilla seleccionada no tiene Clausulas asociadas, no se puede generar un Documento en Blanco";
				}
				return false;
			}
        }
        else{
        	return false;
        }
	}
	
	//Sustituir variables del Documento
	private function sustituirVariables($idDocumento,$html,$datos2,&$resultado){
		
		$resultado = '';

		//$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE);
		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE,VAR_REPRESENTANTE_2);
		$num = 0;
		
		foreach ($tablas as $key => $value) {
			$this->buscarVariables($idDocumento,$html,$value,$datos2,$resultado);
			if( $resultado != '' )
				$html = $resultado;
				
			//$this->graba_log("RESULTADO (".$num.") : ".$html); $num++;
			$resultado = '';
		}
		
		//VARIABLES DE LA SUBCLAUSULA 
		$this->buscarVariablesSubClausulas($idDocumento,$html,$resultado);
		
		if( $resultado == '' ) $resultado = $html;
		
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	private function buscarVariables($idDocumento,$html,$busqueda,$datos2,&$resultado){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();
		$var_busqueda = '';
		$resultado = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerVariablesDocumento($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerVariablesEmpleado($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->ContratosDatosVariablesBD->obtener($datos,$dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:
			
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][0];
				if( $datos["RutUsuario"] != '' ){
					$datos["RutUsuario"] = $datos2['Firmantes_Emp'][0];
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}
				break;
			
			case VAR_REPRESENTANTE_2:
				
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][1];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
		}

		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula
		$var_formato_sm = ''; //Separador de miles
		$var_formato_indefinida = ''; //Indefinida
		$var_formato_combinado = ''; //Comoinado de /SM@0

		if( count($dt->data) > 0){
			// FechaDinamica
			$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema(); 

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
					}
					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
					}

					if( strlen($value) > 0 ) {
					
						if ( $this->validateDate($value,'d-m-Y')){

							//Si la fecha es Indefinido
							//if( $value == VAR_FECHA_IND ){
						    if( $value == VAR_FECHA_IND || $value == '09-03-1911' ){
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_indefinida);												  
								
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDA_NUEVA);			  
								
							}else{
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_indefinida);												  
								$fecha_s = $this->convertirFechaLarga($value);		
								$fecha_c = $this->convertirFechaCorta($value);
								
								array_push($aux,$value);
								array_push($aux,VAR_HASTA_EL.$fecha_s);
								array_push($aux,VAR_HASTA_EL.$fecha_c);
								array_push($aux,$fecha_s);
								array_push($aux,$fecha_c);
								array_push($aux,VAR_HASTA.$fecha_s);			
							}

						}else{
							array_push($variables, $var);
						    array_push($aux, $value);
						}	
					
						
		//$this->graba_log("***2:".$this->cantidad_caracteres);
						if ( is_numeric($value)){
							
							//Validar si es un decimal o que por error le colocaron ','
							$con_comas = count(explode(',',$value));
							$con_puntos = count(explode('.',$value));
		//$this->graba_log("***1:".$this->cantidad_caracteres);
							$this->cantidad_caracteres = 0;	
							if ( $con_comas == 2 || $con_puntos == 2 ) {
								$this->validarNumeroEnteroDecimal($value,$value); 
								
										//$this->graba_log("*** 1.5:".$value);
							}
							
							array_push($variables,$var);
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							array_push($variables,$var_formato_arroba);
							array_push($variables,$var_formato_sm);//Separador de miles
							array_push($variables,$var_formato_combinado);
		//$this->graba_log("***3:".$this->cantidad_caracteres);
							$con_separador_de_miles = '';
							$con_separador_de_miles = number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES); 
		
							if ( count(explode(',',$con_separador_de_miles)) == 2 ){
								$res = explode (',',$con_separador_de_miles);
								$parte_entera = $this->numerosALetras($res[0]);
								$parte_decimal = $this->numerosALetras($res[1]);
								
								if( $parte_decimal != '' )
									$numeros = $parte_entera." COMA ".$parte_decimal;
								else	
									$numeros = $this->numerosALetras($parte_entera);
							}else{
								$numeros = $this->numerosALetras($value);
							}
							
							array_push($aux,$value);
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							array_push($aux,$value);
							array_push($aux,$con_separador_de_miles);//Separador de miles
							array_push($aux,$con_separador_de_miles);//Separador de miles	
						}
			
					}
				}
			}
		

			$html_resultado = '';
			
			$this->graba_log("/// VARIBLES  ///");
			
			//Buscamos si existe conincidencia
			foreach ($variables as $key => $value) {
				if ( strstr($html,$value) ){
					//Sustituir en el HTML
					$html = str_replace($variables,$aux,$html);
				}
				$this->graba_log($value." : ".$aux[$key]);
			}

			if( strstr($html,VAR_LOGO)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];

				$logo = VAR_RUTA_COMPLETA.$rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_LOGO,$logo,$html);
				
				$this->graba_log(VAR_LOGO." : ".$logo);
			}

			if( strstr($html,VAR_RUTA)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];
				
				$ruta = VAR_RUTA_COMPLETA;
				$html = str_replace(VAR_RUTA,$ruta,$html);
				
				$this->graba_log(VAR_RUTA." : ".$ruta);
			}	
		}
		
		$resultado = $html;
		return $resultado;
	}
	
	//Buscar subclausulas
	private function buscarVariablesSubClausulas($idDocumento,$html,&$resultado){

		$datos = $_REQUEST;
	
		$dt = new DataTable();
		$dt_doc = new DataTable();
		$var_busqueda = '';
		$resultado = '';

		//Buscar subclausulas 
		$datos['idDocumento'] = $idDocumento;

		$this->ContratosDatosVariablesBD->obtener($datos,$dt);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $dt->leerFila() ){
			$jornada = $dt->obtenerItem('Jornada');
			$cargo = $dt->obtenerItem('Cargo');
		}
		
		$this->documentosBD->obtener($datos,$dt_doc);
		$this->mensajeError .= $this->documentosBD->mensajeError;
		
		if($dt_doc->leerFila()){
			$RutEmpresa = $dt_doc->obtenerItem('RutEmpresa');
		}
		
		$array_subclausulas = array();
		
		$array_subclausulas[0]['idSubClausula'] = $cargo; 
		$array_subclausulas[0]['idTipoSubClausula'] = 3; 
		
		$array_subclausulas[1]['idSubClausula'] = $jornada; 
		$array_subclausulas[1]['idTipoSubClausula'] = 2; 
					
		foreach($array_subclausulas as $key => $value){
	
			$this->subclausulasBD->obtener($array_subclausulas[$key],$dt);
			$this->mensajeError = $this->subclausulaBD->mensajeError;
		
			$tipo = '';
			$tipo = $dt->data[0]['TipoSubClausula'];

			if( count($dt->data) > 0 ){

				$variables = array();
				$aux = array();
				$var = '';

				//Construimos el arreglo de variables 
				foreach ($dt->data[0] as $key => $value) {

					if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){

						if( VAR_SUBCLAUSULAS == '') 
							$var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
						else 
							$var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

						if( strlen($value) > 0 ) {
							array_push($variables, htmlentities($var));
							array_push($aux, $value);
						}
					}
				}

				//Buscamos si existe conincidencia
				foreach ( $variables as $key => $value ) {
					
					if ( strstr($html,$value)){
						//Sustituir en el HTML
			    		$html = str_replace($variables,$aux,$html);
					}
					$this->graba_log("Subclausulas : ".$value." - ".$aux[$key]);
				}
			} 
		}
		
		$resultado = $html;
		return $resultado;
	}

	//Construir variables y valores disponibles, devuelve un array con dos arrelos, una de variables y otra de los valores 
	private function construirVariablesValores($datos,&$resultado){

		$resultado = array();
		$valores = array();

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE, VAR_REPRESENTANTE_2);

		//VARIABLES DE LAS CLAUSULAS
		foreach ($tablas as $key => $value) {

			if( $this->mensajeError == '' && is_array($resultado)){
				$this->buscarVariablesValores($datos,$value,$array);
				
				array_push($resultado,$array['variables']);
				array_push($valores,$array['valores']);
				$array = array();
			}else{
				$resultado = false;
			}
		}
		
		$array = array();
			
		//VARIABLES DE LA SUBCLAUSULA 
		$this->buscarVariablesValoresSubClausulas($datos,$array);
	
		array_push($resultado,$array['variables']); 
		array_push($valores,$array['valores']);
						
		$arreglo = array();
		$arreglo['variables'] = $resultado;
		$arreglo['valores'] = $valores;
		
		$resultado = array();
		$resultado = $arreglo;

		return $resultado;
	}
	
	//Validar si el documento tiene variables de un tipo 
	private function buscarVariablesValores($datos,$busqueda,&$resultado){

		$dt = new DataTable();
		$var_busqueda = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerVariablesDocumento($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerVariablesEmpleado($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->ContratosDatosVariablesBD->obtener($datos,$dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:
			
				$datos["RutUsuario"] = $datos['Firmantes_Emp'][0];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}
				break;
			
			case VAR_REPRESENTANTE_2:
				
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][1];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
		}

		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula
		$var_formato_sm = ''; //Separador de miles
		$var_formato_indefinida = ''; //Indefinida
		$var_formato_combinado = ''; //Comoinado de /SM@0
		
		if( count($dt->data) > 0){
			
			// FechaDinamica
			$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema();

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
								
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
					}
					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
					}

										
					if ( $this->validateDate($value,'d-m-Y')){

						//Si la fecha es Indefinido
						//if( $value == VAR_FECHA_IND ){
						if( $value == VAR_FECHA_IND || $value == '09-03-1911' ){	
							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_indefinida);
							
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDA_NUEVA);
							
						}else{
							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_indefinida);
							
							$fecha_s = $this->convertirFechaLarga($value);		
							$fecha_c = $this->convertirFechaCorta($value);
							
							array_push($aux,$value);
							array_push($aux,VAR_HASTA_EL.$fecha_s);
							array_push($aux,VAR_HASTA_EL.$fecha_c);
							array_push($aux,$fecha_s);
							array_push($aux,$fecha_c);
							array_push($aux,VAR_HASTA.$fecha_s);
						}

					}else{
						array_push($variables, $var);
						array_push($aux, $value);
					}	
					
									
				//$this->graba_log("***5:".$this->cantidad_caracteres);					
					if ( is_numeric($value)){
						
						//Validar si es un decimal o que por error le colocaron ','
						$con_comas = count(explode(',',$value));
						$con_puntos = count(explode('.',$value));
		//$this->graba_log("***4:".$this->cantidad_caracteres);	
						$this->cantidad_caracteres = 0;	
						if ( $con_comas == 2 || $con_puntos == 2 ) {
							$this->validarNumeroEnteroDecimal($value,$value); 
						}
						
						array_push($variables,$var_formato_n);
						array_push($variables,$var_formato_m);
						array_push($variables,$var_formato_o);
						array_push($variables,$var_formato_arroba);
						array_push($variables,$var_formato_sm);//Separador de miles
						array_push($variables,$var_formato_combinado);
						
						$numeros = $this->numerosALetras($value);
						
						array_push($aux,$numeros);
						array_push($aux,strtolower($numeros));
						array_push($aux,ucwords(strtolower(($numeros))));
						array_push($aux,$value);
						
//$this->graba_log("***6:".$this->cantidad_caracteres);		
						array_push($aux,number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES));//Separador de miles
						array_push($aux,number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES));//Separador de miles
					}	
					
				}
			}
		}

		$resultado = array();
		$resultado['variables'] = $variables;
		$resultado['valores'] = $aux;

		return $resultado;
	}
	
	//Buscar las variables y valores de subclausulas
	private function buscarVariablesValoresSubClausulas($datos,&$resultado){
	
		$dt = new DataTable();
		$dt_doc = new DataTable();
		$var_busqueda = '';

		//Buscar subclausulas 
		$this->ContratosDatosVariablesBD->obtener($datos,$dt);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $dt->leerFila() ){
			$jornada = $dt->obtenerItem('Jornada');
			$cargo = $dt->obtenerItem('Cargo');
		}
		
		$this->documentosBD->obtener($datos,$dt_doc);
		$this->mensajeError .= $this->documentosBD->mensajeError;
		
		if($dt_doc->leerFila()){
			$RutEmpresa = $dt_doc->obtenerItem('RutEmpresa');
		}
		
		$array_subclausulas = array();
		
		$array_subclausulas[0]['idSubClausula'] = $cargo; 
		$array_subclausulas[0]['idTipoSubClausula'] = 3; 
		
		$array_subclausulas[1]['idSubClausula'] = $jornada; 
		$array_subclausulas[1]['idTipoSubClausula'] = 2; 
					
		foreach($array_subclausulas as $key => $value){
	
			$this->subclausulasBD->obtener($array_subclausulas[$key],$dt);
			$this->mensajeError = $this->subclausulaBD->mensajeError;
		
			$tipo = '';
			$tipo = $dt->data[0]['TipoSubClausula'];

			if( count($dt->data) > 0 ){

				$variables = array();
				$aux = array();
				$var = '';

				//Construimos el arreglo de variables 
				foreach ($dt->data[0] as $key => $value) {

					if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){

						if( VAR_SUBCLAUSULAS == '') 
							$var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
						else 
							$var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

						if( strlen($value) > 0 ) {
							array_push($variables, htmlentities($var));
							array_push($aux, $value);
						}
					}
				}
			} 
		}
		
		$resultado = array();
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;
		return $resultado;
	}
	
	private function arregloMultiASimple($array, &$resultado){
		
		$resultado = array();
		
		foreach( $array as $key => $value ){
			foreach ( $value as $key_1 => $value_1 ){
				array_push($resultado,$value_1);
			}
		}
		return $resultado;
	}
	
	//Buscar variables vacias 
	private function buscarVariablesVacias($html,$variables,$valores){
		
		$cant = count($variables);
		$j = 0;
		
		if ( $cant > 0 ){
			for( $i = 0; $i < $cant; $i++ ){ 

				$aux = '';
				$num_var_vacia = 0;
				$num_var_vacia = strlen(VAR_VACIA);
				
				if( strstr($html, $variables[$i] )){ 
					
					$aux = strstr($variables[$i], VAR_VACIA);
					$aux = substr($aux,0,$num_var_vacia);
				
					if( $aux == VAR_VACIA ){ 
						if( $valores[$i] == 0 ){ 
							$j++;
						}
					}
				}
			}	
		}
		$this->graba_log("HAY VARIABLES VACIAS (*):".$j);
		if( $j > 0 ) return true;
		else return false;
	}
	
	/*private function validarNumeroEnteroDecimal($numero,&$resultado){
		
		//$numero = '25469,02';
        $int = 0;
		$decimal = 0 ;
		$res = array();
		$resultado = '';
		$coma = strpos($numero, ',');
		$punto = strpos($numero,'.');
		
		if( $coma ){
			$res = explode(',',$numero);
		}
		
		if( $punto ){ 
			$res = explode('.',$numero);
		}
		
		if( count($res) == 2 ){
			
			$int = $res[0];
			$decimal = $res[1];
					
			if( $decimal > 0 && $coma ){
				$resultado = str_replace(',','.', $numero);
				$this->cantidad_caracteres = strlen($decimal);
			}
			else{
				$resultado = $int;
				$this->cantidad_caracteres = 0;
			}
			
		}else{
			$resultado = $numero;
			$this->cantidad_caracteres = 0;
		}
	}*/
	
	/*private function validarNumeroEnteroDecimal($numero,&$resultado){
		
		//$numero = '25469,02';
        $int = 0;
		$decimal = 0 ;
		$res = array();
		$resultado = '';
		$coma = strpos($numero, ',');
		$punto = strpos($numero,'.');
		
		if( $coma ){
			$res = explode(',',$numero);
		}
		
		if( $punto ){ 
			$res = explode('.',$numero);
		}
	
		if( count($res) == 2 ){
			
			$int = $res[0];
			$decimal = $res[1];
					
			if( $decimal > 0 && $coma ){
				$resultado = str_replace(',','.', $numero); 
				$this->cantidad_caracteres = strlen($decimal);  
			}
			else if( $decimal > 0 && $punto ){
				$resultado = $numero; 
				$this->cantidad_caracteres = strlen($decimal);  
			}			
		}else{
			$resultado = $numero;
			$this->cantidad_caracteres = 0;  
		}
	}*/
	
	private function validarNumeroEnteroDecimal($numero,&$resultado){
		
		//$numero = '25469,02';
        $int = 0;
		$decimal = 0 ;
		$res = array();
		$resultado = '';
		$coma = strpos($numero, ',');
		$punto = strpos($numero,'.');
		
		if( $coma ){
			$res = explode(',',$numero);
		}
		
		if( $punto ){ 
			$res = explode('.',$numero);
		}
		
		if( count($res) == 2 ){
			
			$int = $res[0];
			$decimal = $res[1];
					
			if( $decimal > 0 && $coma ){
				$resultado = str_replace(',','.', $numero); 
				$this->cantidad_caracteres = strlen($decimal); 
			}
			else if( $decimal > 0 && $punto ){
				$resultado = $numero; 
				$this->cantidad_caracteres = strlen($decimal);  
			}else if ( $decimal <= 0 ){
				$resultado = $int;
				$this->cantidad_caracteres = 0;
			}
		}else{
			$resultado = $numero; 
			$this->cantidad_caracteres = 0;  
		}
	}			

	//Generar Documento completo 
	public function rl_GenerarDocumento($datos){
	
		//Validacion por importacion 
		if( $datos['lugarpagoid'] != '' && $datos["idCentroCosto"] == '' ){
			$datos["idCentroCosto"] = $datos['lugarpagoid'];
		}
	
		//->Documento
		$respuesta = array();
		$respuesta = $this->rl_validarDatosDocumento($datos);
		
		if( $respuesta['estado'] ){

			//->Variables
			$respuesta = array();
			$respuesta = $this->rl_validarDatosVariables($datos);

			if ( $respuesta['estado'] ){

				if( $respuesta['estado'] ){

					//->Firmantes
					$respuesta = array();
					$respuesta = $this->rl_validarDatosFirmantes($datos);

			
					if (isset($datos["pdf64"]))
					{
						if ($datos["pdf64"] != "")
						{
							$respuesta = array();
							$respuesta = $this->rl_ImportarPDF($datos);
							
						}
						else
						{
							if( $respuesta['estado'] ){
								//Vamos a generar
								$respuesta = array();
								$respuesta = $this->GenerarDocumentoCompleto($datos);
							}
							
						}
					}
					else
					{
						if( $respuesta['estado'] ){
							//Vamos a generar
							$respuesta = array();
							$respuesta = $this->GenerarDocumentoCompleto($datos);
						}
					}
				}
			}

		}
		return $respuesta;
		
	}

	//Validar datos de las Variables 
	public function rl_validarDatosVariables($datos){

		$dt = new DataTable();

		if( isset($datos['idCentroCosto'])){
			if ( strlen($datos['idCentroCosto']) == 0 ){
				$this->mensajeError = "Function validarDatosVariables : El campo ['idCentroCosto'] no debe estar vac&iacute;o, este campo pertenece al identificador del Centro de Costo al que pertenece el trabajador";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			if ( ! $this->validaTipoDato($datos['idCentroCosto'], 1 ) ){
				$this->mensajeError = "Function validarDatosVariables : El campo ['idCentroCosto']  debe ser una cadena de caracteres";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}

			//Validar la combinacion
			$datos["empresaid"]     = $datos['RutEmpresa'];
		/*				
			$this->centroscostoBD->obtenerEmpresa($datos,$dt);
			$this->mensajeError .= $this->centroscostoBD->mensajeError;

			if( $this->mensajeError != ''  ){
				$this->mensajeError .= " - Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Centro de Costo seleccioando";
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}
			
			if( count($dt->data) == 0){
				$this->mensajeError = "Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Centro de Costo seleccioando";
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}*/
		}

		if( $datos['RutCliente'] == '' && $datos['RutProveedor'] != '' )
			$datos['RutCliente'] = $datos['RutProveedor'];
		
		/*if ( strlen($datos['lugarpagoid']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['lugarpagoid'] no debe estar vac&iacute;o, este campo pertenece al identificador del Centro de Costo al que pertenece el trabajador";

			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
					
		if ( ! $this->validaTipoDato($datos['lugarpagoid'], 1 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['lugarpagoid']  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		
		$this->lugarespagoBD->obtener($datos,$dt);
		$this->mensajeError .= $this->lugarespagoBD->mensajeError;

		if( $this->mensajeError != ''  ){
			$this->mensajeError .= " - Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Lugar de Pago seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
		
		if( count($dt->data) == 0){
			$this->mensajeError = "Function validarDatosVariables : Acceso denegado, favor revisar la Empresa o Lugar de Pago seleccioando";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}*/
	
		$this->mensajeOK = "La validacion de los datos del Documento Variables fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}
	
	//Construir Arreglos de los Firmantes
	private function rl_crearFirmantes($idDocumento,$datos,&$resultado){
	
		//Variables que faltan 
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt8 = new DataTable();
		$dtx9 = new DataTable();
		
		//Tipo de empresa 
		$tipoEmpresa = 0;
		$datos ["idDocumento"] = $idDocumento;

	    //Buscar datos que faltan de la Empresa
	    if ( $datos["RutEmpresa"] !='' ){
	       	$this->documentosBD->obtenerRazonSocial($datos,$dt4);
	       	$this->mensajeError.=$this->documentosBD->mensajeError;
	    }
	   
		$this->documentosBD->obtener($datos, $dtx9);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if ($this->mensajeError == "")
		{
			if( $dtx9->leerFila() )
			{
				$datos["idWF"] = $dtx9->obtenerItem('idWF');
			}		
		}
	
      	//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt8);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        //Auxiliar para cuando tiene estado de aprobacion
				   
							
								
		$datos['Representantes'] = 0;
		$datos['Empleado'] = 0;
		$datos['Cliente'] = 0;
		$datos['proveedores'] = 0;
		$nombre = '';
		$estado = '';
		$firmantes_emp = array();
		$firmantes_cli = array();
		$firmantes_pro = array();
		$f_empresa = array();
		$f_cliente = array();
		$f_proveedor = array();

		if( count($dt8->data) > 0 )
		{
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
			foreach ($dt8->data as $key => $value)
			{
				$nombre = $dt8->data[$key]["Nombre"];
				$estado = $dt8->data[$key]["idEstado"];
				$conorden = $dt8->data[$key]["ConOrden"];
				$orden = $dt8->data[$key]["Orden"];

				if( is_numeric($key) )
				{
					//Si Estado: Pendiente por firma de Empresa 
					if( $estado == 2 ) 
					{	
						//Buscar si tienen orden interno 
						if( $conorden == 1 ){
							$this->rl_ordenarFirmantes($datos,"Firmantes_Emp",$firmantes_emp);
  
							foreach ($firmantes_emp as $key => $value) {
								if( $value != '' ){
									//Completar los datos que necesito 
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;
									$datos["OrdenMismoEstado"] = $key+1;
									if( $this->rl_asignarFirmante($datos,$conorden)){

										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
		
										//Agregar al final 
										array_push($f_empresa, $datos_firmante);
	  
										if($this->mensajeError == "" ) $datos['Representantes']++;
									}
								}
							}
						}else{
						//Si no , todos los firmantes se agregan al mismo orden 
											  
							$firmantes_emp = $datos["Firmantes_Emp"];
									
							foreach ($firmantes_emp as $key => $value) {
								
								if( $value != '' ){
									//Completar los datos que necesito 
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;
									
									if( $this->rl_asignarFirmante($datos)) {
										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
										//Agregar al final 
										array_push($f_empresa, $datos_firmante);

										if($this->mensajeError == "" ) $datos['Representantes']++;
									}
								}
							}									 
						}
					} //Fin del Si Estado: Pendiente por firma de Empresa 
  
					//Si Estado: Pendiente por firma de Empleado 
					if(($nombre == 'Pendiente por firma Empleado') && ($estado == 3 ))
					{
						//Firmantes del empleado 
						$f_empleado = array();
						$empleado_aux = array();

						if( $datos['personaid'] != '' )
							$datos['rutusuario'] = $datos['personaid'];

						if( $datos['newusuarioid'] != '' )
							$datos['rutusuario'] = $datos['newusuarioid'];

						//Buscar datos
						if ( $datos['rutusuario'] != '' ){
									
							//Completar los datos que necesito
							$aux =  $datos["RutEmpresa"];
																																										   
							$datos["RutEmpresa"] = $datos['rutusuario']; 
							$datos["RutFirmante"] = $datos['rutusuario'];
							$datos['idEstado'] = $estado;
							$datos['Orden'] = $orden;
							
							if( $this->rl_asignarFirmante($datos)) {
								$datos_firmante = array();
								if( $this->rl_completarDatosFirmante($datos['rutusuario'],$datos_firmante)){
										
									//Agregar al final 
									array_push($f_empleado, $datos_firmante); 
									if($this->mensajeError == '' ) $datos['Empleado']++;
								}
							}
							$datos['RutEmpresa'] = $aux;
						}  
   
					}//Fin de Estado: Pendiente por firma de Empleado 

					//Si Estado: Pendiente por firma de Cliente
					if( $estado == 11 )
					{
						//Buscar si tienen orden interno 
						if( $conorden == 1 ){
							$this->rl_ordenarFirmantes($datos,"Firmantes_Cli",$firmantes_cli);

							foreach ($firmantes_cli as $key => $value) {
								if( $value != '' ){
									//Completar los datos que necesito 
									$aux = $datos['RutEmpresa'];
									$datos['RutEmpresa'] = $datos['RutCliente'];
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;
									$datos["OrdenMismoEstado"] = $key+1;
									
									if( $this->rl_asignarFirmante($datos,$conorden)) {
										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
										//Agregar al final 
										array_push($f_cliente, $datos_firmante);
	
										if($this->mensajeError == "" ) $datos['Cliente']++;
									}
									$datos['RutEmpresa'] = $aux;
								}
								
							}
						}else{
							//Si no , todos los firmantes se agregan al mismo orden 
							$firmantes_cli = $datos["Firmantes_Cli"];

							foreach ($firmantes_cli as $key => $value) {
								
								if( $value != '' ){
									//Completar los datos que necesito 
									$aux = $datos['RutEmpresa'];
									$datos['RutEmpresa'] = $datos['RutCliente'];
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;
									
									if( $this->rl_asignarFirmante($datos)) {

										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
										//Agregar al final 
										array_push($f_cliente, $datos_firmante); 

										if($this->mensajeError == "" ) $datos['Cliente']++;
									}
									$datos['RutEmpresa'] = $aux;
								}
							}
						}
					}//Fin de Estado: Pendiente por firma de Emprezsa CLIENTE

					//Si Estado: Pendiente por firma de Proveedor
					if( $estado == 12 )
					{
						//Buscar si tienen orden interno 
						if( $conorden == 1 ){
							$this->rl_ordenarFirmantes($datos,"Firmantes_Cli",$firmantes_pro);

							foreach ($firmantes_pro as $key => $value) {
								if( $value != '' ){
									//Completar los datos que necesito 
									$aux = $datos['RutEmpresa'];
									$datos['RutEmpresa'] = $datos['RutProveedor'];
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;
									$datos["OrdenMismoEstado"] = $key+1;
									
									if( $this->rl_asignarFirmante($datos,$conorden)) {
										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
										//Agregar al final 
										array_push($f_proveedor, $datos_firmante);
	
										if($this->mensajeError == "" ) $datos['proveedores']++;
									}
									$datos['RutEmpresa'] = $aux;
								}
								
							}
						}else{
						//Si no , todos los firmantes se agregan al mismo orden 
							$firmantes_pro = $datos["Firmantes_Cli"];

							foreach ($firmantes_pro as $key => $value) {
								
								if( $value != '' ){
									//Completar los datos que necesito 
									$aux = $datos['RutEmpresa'];
									$datos['RutEmpresa'] = $datos['RutProveedor'];
									$datos["RutFirmante"] = $value;
									$datos['idEstado'] = $estado;
									$datos['Orden'] = $orden;

									if( $this->rl_asignarFirmante($datos)) {

										$datos_firmante = array();
										$this->rl_completarDatosFirmante($value,$datos_firmante);
										//Agregar al final 
										array_push($f_proveedor, $datos_firmante); 

										if($this->mensajeError == "" ) $datos['proveedores']++;
									}
									$datos['RutEmpresa'] = $aux;
								}
							}
						}
					}//Fin de Estado: Pendiente por firma de Emprezsa CLIENTE

				}//Fin del IF
 
	        }//Fin del Foreach de los Estados del WF
		}
	
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();

		//Si el flujo tiene Empresa
		//if( $datos['Representantes'] == 1 ) array_push($firmantes_completos, $f_empresa);
		if( $datos['Representantes'] > 0 ) array_push($firmantes_completos, $f_empresa);
				
		//Si el flujo tiene Empleado
		if( $datos['Empleado'] == 1 ) array_push($firmantes_completos, $f_empleado);

		//Si Flujo tiene Cliente 
		if( $datos['Cliente'] > 0 ) array_push($firmantes_completos, $f_cliente);

		//Si Flujo tiene Proveedores 
		if( $datos['proveedores'] > 0 ) array_push($firmantes_completos, $f_proveedor);

		$resultado = array();
		$resultado = $firmantes_completos;		
					 
		return $resultado;
	}

	private function rl_ImportarPDF($datos){
	//Crear documento 
		$respuesta = array();
		$respuesta = $this->rl_crearDocumento($datos);

		if( $respuesta['data'] > 0 ){
			
			$datos['idDocumento'] = $respuesta['data'];
			$idDocumento = $respuesta['data'];

			//Agrego las variables del Documento 
			$respuesta = array();
			$respuesta = $this->agregarVariables($datos);
			
			if( $respuesta['codigo'] == 200 ){
				
				if( $respuesta['codigo'] == 200 ){
					
						$this->rl_crearFirmantes($idDocumento,$datos,$firmantes_completos);

						if( count($firmantes_completos) < 1 )
						{
							
							$this->mensajeError = "Ha ocurrido un error inesperado, no se pudo agregar los firmantes del Documento";
							$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
							return $respuesta;
							
						}
						
						if (isset($datos["idestadodoc"]))
						{
							$datosresp = array();
							$datosresp["iddocumento"] = $idDocumento;
							$datosresp["idestado"] = 1;
							$this->imprespaldoBD->agregar($datosresp);
							$this->mensajeError= $this->imprespaldoBD->mensajeError;
							if ($this->mensajeError != '')
							{
								$this->mensajeError = "Ha ocurrido un error inesperado, no fue posible grabar marca para docuemntos obligatorios";
								$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
								return $respuesta;			
							}
						}

					/*
					//Crear Plantilla 
					$respuesta = array();
					$respuesta = $this->crearPlantilla($idDocumento, $datos['idFirma'], $datos);
					
					if( $respuesta['codigo'] == 200 ){

						//Generar PDF
						$html = '' ;
						$html = $respuesta['data'];
						$respuesta = array();
						$respuesta = $this->generarPDF($idDocumento, $html);

						if( $respuesta['codigo'] == 200 ){
							
							//Actualizo el documento en la Base de datos 
							$respuesta = array();
							$respuesta = $this->agregarDocumentoGenerado($idDocumento);

							if( $respuesta['codigo'] == 200 ){
								
								$this->mensajeOK = 'El documento se ha generado correctamente';
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
							}
						}
					}
					*/
					
					$respuesta = array();
					$pdf64 = $datos["pdf64"];
					$respuesta = $this->agregarDocumentoGeneradoImportacion($idDocumento,$pdf64);	
					if( $respuesta['codigo'] == 200 ){
								
						$this->mensajeOK = 'El documento se ha generado correctamente';
						$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true, $idDocumento);
					}				
				}
			}
		}

		return $respuesta;			
	}

	private function rl_asignarFirmante($datos,$conorden=0){

		$firmante = array();
		$dt = new DataTable();

		if( $conorden == 1 ){

			$firmante = array ( 
				"idDocumento" => $datos["idDocumento"], 
				"RutEmpresa" => $datos["RutEmpresa"], 
				"RutFirmante" => $datos["RutFirmante"], 
				"idEstado" => $datos["idEstado"],
				"Orden" => $datos["Orden"],
				"OrdenMismoEstado" => $datos["OrdenMismoEstado"]
			);	 

			//Agregara a la tabla
			$this->documentosBD->rl_agregarFirmantesConOrden($firmante);
			$this->mensajeError .= $this->documentosBD->mensajeError;
		}else{

			$firmante = array ( 
				"idDocumento" => $datos["idDocumento"], 
				"RutEmpresa" => $datos["RutEmpresa"], 
				"RutFirmante" => $datos["RutFirmante"], 
				"idEstado" => $datos["idEstado"],
				"Orden" => $datos["Orden"]
			);	 

			//Agregara a la tabla
			$this->documentosBD->agregarFirmantes($firmante);
			$this->mensajeError .= $this->documentosBD->mensajeError;
		}

		if( $this->mensajeError == '' ){
			return true;
		}
		return false;
	}	

	private function rl_ordenarFirmantes($datos, $firmantes, &$resultado){

		$array = array();
		$resultado = array();
		
		$band = 0;

		if( count($datos) > 0 ){

			//Organizar Firmantes 
			foreach( $datos[$firmantes] as $key => $value){

				$var ='';

				if( $firmantes == 'Firmantes_Emp' ) {
					$var = 'orden_emp_';
					$band = 1;
				}
				else{
					$var = 'orden_';
					$band = 0;
				}

				$var .= $value; 
				$array[$var] = $datos[$var];
			}
			//Ordenarlo de menor a mayor
			asort($array);

			foreach ($array as $key => $value) {
				$rut = explode('_', $key);
				if($band == 1)
					array_push($resultado,$rut[2]);
				else
					array_push($resultado,$rut[1]);
			}
			
		}
	}

	private function rl_completarDatosFirmante($rutfirmante, &$resultado){

		$dt = new DataTable();
		$identificador = "RUT N&deg;";

		//Buscar datos
		$array = array( "RutEjecutivo" => $rutfirmante );
		$this->documentosBD->obtenerPersona($array, $dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

		if( $this->mensajeError == '' ){

			$nombre  = ucwords(strtolower($dt->data[0]["nombre"]));
			$apellido = ucwords(strtolower($dt->data[0]["appaterno"]));
			$apellido_paterno = ucwords(strtolower($dt->data[0]["apmaterno"]));

			//Completar el arreglo
			$nuevo = array( 
				"nombre" => $nombre.' '.$apellido.' '.$apellido_paterno, 
				"rut" => $identificador.$rutfirmante
			);
			$resultado = $nuevo;
			return;
		}else{
			return false;
		}
		
	}

	//Validar Datos del Documento
	public function rl_validarDatosDocumento($datos){

		$dt =  new DataTable();

		//Validar los campos obligatorios 

		if ( strlen($datos['idPlantilla']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] no debe estar vac&iacute;o, este campo pertenece al identificador del Plantilla";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idPlantilla'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->PlantillasBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idPlantilla] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if( strlen($datos['idProceso']) == 0 ) {
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] no debe estar vac&iacute;o, este campo pertenece al identificador del Proceso";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idProceso'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->procesosBD->obtener($datos, $dt);
		if ( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [idProceso] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['RutEmpresa']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] no debe estar vac&iacute;o,";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['RutEmpresa'], 4 ) ){
			$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->empresasBD->mensajeError;

		if( $this->mensajeError != '' ){
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}else{
			if( count($dt->data) == 0 ){
				$this->mensajeError = "Function validarDatosDocumento : El campo [RutEmpresa] no es de una empresa registrada";
				$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
				return $respuesta;
			}
		}

		$this->mensajeOK = "La validacion de los datos del Documento fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar datos de los firmantes 
	public function rl_validarDatosFirmantes($datos){

		$dt = new DataTable();
		
		//Buscar Flujo de firma
		$this->PlantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->PlantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			if ( $dt->leerFila()){
				$datos['idWF'] = $dt->obtenerItem('idWF');
			}
		}

		//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        $firmante_empresa = 0;

      	if( count($dt->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt->data as $key => $value) {

		        //Si Estado: Pendiente por firma de Empresa 
		        if(( $dt->data[$key]["Nombre"] == 'Pendiente por firma Representante') || ($dt->data[$key]["idEstado"] == 2 )){

		        	$firmante_empresa = 1;
		        }
				
				//Si Estado: Pendiente por firma de Empresa 2
		        if(( $dt->data[$key]["Nombre"] == 'Pendiente por firma Representante 2') || ($dt->data[$key]["idEstado"] == 10 )){

		        	$firmante_empresa = 1;
		        }
		    }
		}

		if( $firmante_empresa == 1 ){

			foreach ($datos['Firmantes_Emp'] as $key => $value) {
				if ( strlen($value) == 0 ){
					$this->mensajeError = "Function validarDatosFirmantes : El firmante no debe estar vac&iacute;o";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}

				if ( ! $this->validaTipoDato($value,4 ) ){
					$this->mensajeError = "Function validarDatosFirmantes : El rut del firmante debe tener el siguiente formato. Ejemplo: 12345678-9";
					$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
					return $respuesta;
				}		
			}

			$this->mensajeOK = "La validacion de los datos de los Firmantes fue correcta ";
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
			return $respuesta;

		}else{

			$this->mensajeOK = "El flujo de firma de este documento no tiene firmantes por la Empresa ";
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
			return $respuesta;
		}
	}

	//Crear un nuevo Documento
	public function rl_crearDocumento($datos){

		//Generar Documento nuevo 
		//$datos = $_REQUEST;
		$dt = new DataTable();

		if (!isset($datos['idFirma']))
		{
			$datos['idFirma'] = TIPO_FIRMA_DOC;
		}
		$datos['idTipoFirma'] = $datos['idFirma'];

		switch ($datos['idTipoFirma'])
		{
			case 1: // Manual
			{
				$datos['idEstado'] = APROBAR_MANUAL ? 1 : 4; // Generado manualmente
				break;
			}
			case 2: // Electronica
			{
				$datos['idEstado'] = 1; // Generado en espera de aprobacion
				break;
			}
		}

		if( $datos['fechadocumento'] == '' ){
			//$datos["FechaCreacion"] = date("d-m-Y H:i:s");
			$datos['FechaCreacion'] = date(VAR_FORMATO_FECHA." H:i:s");
		}
		else{
			$datos['FechaCreacion'] = $datos['fechadocumento'];
		}

		if ( $datos['idTipoGeneracion'] == '' )
			$datos['idTipoGeneracion'] = TIPO_GENERACION;

		//Buscar Flujo de firma
		$this->PlantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->PlantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			if ( $dt->leerFila()){
				$datos['idWF'] = $dt->obtenerItem('idWF');
			}
		}

		//Guardar los Datos del Documento 
		$this->documentosBD->agregar($datos,$dt);
		$this->mensajeError.= $this->documentosBD->mensajeError;     
		$datos["idDocumento"] = $dt->data[0]["idDocumento"];
		$idDocumento = $dt->data[0]["idDocumento"];
		$this->graba_log($this->mensajeError);
		if( $this->mensajeError == "" ){
			$this->mensajeOK = "Se ha generado correctamente su documento, bajo el c&oacute;digo : ".$idDocumento;
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $idDocumento);
			return $respuesta;
		}
		else{
			$this->mensajeError = "No se pudo completar la generaci&oacute;n de su documento, intente nuevamente";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
	}

}
?>