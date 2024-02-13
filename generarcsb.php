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
include_once("includes/accesodocxperfilBD.php");
include_once("includes/ContratosDatosVariablesBD.php");
include_once("includes/empleadosBD.php");
include_once("includes/subclausulasBD.php");
include_once("includes/flujofirmaBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/procesosBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/estadocivilBD.php");
require_once('Config.php');  

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$page = new generarcsb();

class generarcsb 
{
	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $generarBD;
	private $documentosBD;
	private $empresasBD;
	private $accesodocxperfilBD;
	private $ContratosDatosVariablesBD;
	private $empleadosBD;
	private $subclausulasBD;
	private $flujofirmaBD;
	private $PlantillasBD;
	private $procesosBD;
	private $centroscostoBD;
	private $estadocivilBD;

	// para juntar los mensajes de error
	private $mensajeError="";
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
	    
	/*
	    $this->seguridad = new Seguridad($this->pagina,$this->bd);
	    // si no funciona hay que logearse
	    if (!$this->seguridad->sesionar()) 
	    {
	      echo 'Mensaje | Debe Iniciar sesión!';
	      exit;
	    }
*/
		// instanciamos del manejo de tabla
		$this->documentosBD = new documentosBD();
		$this->ContratosDatosVariablesBD = new ContratosDatosVariablesBD();
		$this->empresasBD = new empresasBD();
		$this->accesodocxperfilBD = new accesodocxperfilBD();
		$this->empleadosBD = new empleadosBD();
		$this->subclausulasBD = new subclausulasBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->procesosBD = new procesosBD();
		$this->centroscostoBD = new centroscostoBD();
		$this->estadocivilBD = new estadocivilBD();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->ContratosDatosVariablesBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->accesodocxperfilBD->usarConexion($conecc);
		$this->empleadosBD->usarConexion($conecc);
		$this->subclausulasBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);

	}

	//Generar Documento completo 
	public function GenerarDocumento($datos){
	
		//Datos Documento 
		//array ( 'idEstado','idWF','FechaCreacion','idTipoFirma','idPlantilla','idProceso','idTipoGeneracion','RutEmpresa' )
		
		//Datos Variables
		//array ('idDocumento','Rut','CentroCosto','Fecha','AnosServicio','AvisoPrevio','Banco','Cargo','Cenco','Ciudad','Cuenta','DescansosNoCompensados','DescuentosAFC','DireccionSucursal','DomFest','EmbarcacionNombre','Faena','FaenaNombre','FechaIngreso','FechaInicio','FechaTermino','Gratificacion','HorasExtras','Jornada','LeyesSociales','Mes','MontoRemuneracion','Movilizacion','MutuoAcuerdo','NroArticulo','NumAnosServicio','PagoComisiones','SeguroCesantia','SueldoBase','Texto1','TotalDescuentos','TotalHaberes','TotalPagar','VacacionesProporcionales','Viatico' )

		//Datos del empleado
		//array ( 'personaid','nacionalidad','nombre','correo','direccion','fechanacimiento','estadocivil')

		//Datos de los firmantes 
		//array('Firmantes_Emp' => array ('26131316-1','26131316-2'))

	
		//Validar que todos los campos esten correctos 
		//return "prueba";exit;
		//->Documento
		$respuesta = array();
		$respuesta = $this->validarDatosDocumento($datos);
		
		if( $respuesta['estado'] ){

			//->Variables
			$respuesta = array();
			$respuesta = $this->validarDatosVariables($datos);

			if ( $respuesta['estado'] ){

				//->Empleado
				$respuesta = array();
				$respuesta = $this->validarDatosEmpleado($datos);

				if( $respuesta['estado'] ){

					//->Firmantes
					$respuesta = array();
					$respuesta = $this->validarDatosFirmantes($datos);

					if( $respuesta['estado'] ){
						//Vamos a generar
						$respuesta = array();
						$respuesta = $this->GenerarDocumentoCompleto($datos);
					}
				}
			}

		}
		return $respuesta;
		
	}

	//Validar Datos del Documento
	public function validarDatosDocumento($datos){

		$dt =  new DataTable();

		//Validar los campos obligatorios 
	/*
		if ( strlen($datos['idWF']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idWF'] no debe estar vac&iacute;o, este campo pertenece al identificador del flujo de firmas";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idWF'], 2 )  ){
			$this->mensajeError = "Function crearDocumento : El campo ['idWF'] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$datos["idworkflow"] = $datos['idWF'];
		$this->flujofirmaBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idWF'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
*/
		if ( strlen($datos['idPlantilla']) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idPlantilla'] no debe estar vac&iacute;o, este campo pertenece al identificador del Plantilla";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idPlantilla'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idPlantilla'] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->PlantillasBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idPlantilla'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if( strlen($datos['idProceso']) == 0 ) {
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idProceso'] no debe estar vac&iacute;o, este campo pertenece al identificador del Proceso";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idProceso'], 2 )  ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idProceso'] debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->procesosBD->obtener($datos, $dt);
		if ( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosDocumento : El campo ['idProceso'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['RutEmpresa']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['RutEmpresa'] no debe estar vac&iacute;o,";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['RutEmpresa'], 4 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['RutEmpresa'] debe tener el siguiente formato. Ejemplo: 12345678-9";
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
				$this->mensajeError = "Function validarDatosVariables : El campo ['RutEmpresa'] no es de una empresa registrada";
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

		/*if ( strlen($datos['idDocumento']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idDocumento'] no debe estar vac&iacute;o, este campo pertenece al identificador del Documento";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idDocumento'], 2 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idDocumento'] de bebe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->documentosBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idDocumento'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}*/

		$datos['rutusuario'] = $datos['newusuarioid'];
		if ( strlen($datos['rutusuario']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['rutusuario'] no debe estar vac&iacute;o, este campo pertenece al  rut del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['rutusuario'], 4 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['rutusuario'] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['idCentroCosto']) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idCentroCosto'] no debe estar vac&iacute;o, este campo pertenece al identificador del Centro de Costo al que pertenece el trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['idCentroCosto'], 2 ) ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idCentroCosto'] de bebe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->centroscostoBD->obtener($datos,$dt);
		if( count($dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosVariables : El campo ['idCentroCosto'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
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
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['personaid'] no debe estar vac&iacute;o, este campo pertenece al  rut del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['personaid'],4 ) ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['personaid'] debe tener el siguiente formato. Ejemplo: 12345678-9";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['nombre']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['nombre'] no debe estar vac&iacute;o, este campo pertenece al  nombre del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['nombre'],1 )){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['nombre'] debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		
		if ( strlen($datos['fechanacimiento']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['fechanacimiento'] no debe estar vac&iacute;o, este campo pertenece a la Fecha de Nacimiento del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['fechanacimiento'], 3)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['fechanacimiento'] debe tener el formato de fecha ".VAR_FORMATO_FECHA;
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['correo']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['correo'] no debe estar vac&iacute;o, este campo pertenece al correo del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['correo'], 5)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['correo'] debe ser una direcci&oacute;n v&aacute;lida";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['nacionalidad']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['nacionalidad'] no debe estar vac&iacute;o, este campo pertenece a la nacionalidad del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['nacionalidad'], 1)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['nacionalidad']  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
		$datos["estadocivil"] = $datos["idEstadoCivil"]; 
		if ( strlen($datos['estadocivil']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['estadocivil'] no debe estar vac&iacute;o, este campo pertenece al Estado Civil del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['estadocivil'], 2)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['estadocivil']  debe ser num&eacute;rico";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}
 
		$this->estadocivilBD->obtener($datos,$dt);
		if( count( $dt->data) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['estadocivil'] tiene un valor que no existe";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['direccion']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['direccion'] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['direccion'], 1)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['direccion']  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['comuna']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['comuna'] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['comuna'], 1)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['comuna']  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( strlen($datos['ciudad']) == 0 ){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['ciudad'] no debe estar vac&iacute;o, este campo pertenece a la direcci&acute;n del trabajador";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		if ( ! $this->validaTipoDato($datos['ciudad'], 1)){
			$this->mensajeError = "Function validarDatosEmpleado : El campo ['ciudad']  debe ser una cadena de caracteres";
			$respuesta = $this->construirRespuesta(400,$this->mensajeError, false);
			return $respuesta;
		}

		$this->mensajeOK = "La validacion de los datos del Empleado fue correcta ";
		$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true);
		return $respuesta;
	}

	//Validar datos de los firmantes 
	public function validarDatosFirmantes($datos){

		$dt = new DataTable();

		//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        $firmante_empresa = 0;

      	if( count($dt->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt->data as $key => $value) {
   	   
		        //Si Estado: Pendiente por firma de Empresa 
		        if(( $dt->data[$key]["Nombre"] == 'Pendiente por firma Representante') || ($dt8->data[$key]["idEstado"] == 2 )){

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
			if(!is_string($valorCelda)) 
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
		
		if( $TipoDato = 4 ) // Rut Chileno xxxxxxxx-x
		{
			if ( $this->valida_rut($valorCelda))
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 5 ) //Correo 
		{
			if( filter_var($valorCelda, FILTER_VALIDATE_EMAIL) )
			{	
				return true;
			}
			return false;
		}

	    return false;
	}

	//Crear un nuevo Documento
	public function crearDocumento($datos){

		//Generar Documento nuevo 
		//$datos = $_POST;
		$dt = new DataTable();
			
		$datos['idEstado']    = 1; //Creado
		
		if (!isset($datos['idFirma']))
		{
			$datos['idFirma'] = 2;
		}
		
		$datos['idTipoFirma'] = $datos['idFirma'];

		if( $datos['fechadocumento'] == '' )
			$datos['FechaCreacion'] = date(VAR_FORMATO_FECHA." H:i:s");
		else
			$datos['FechaCreacion'] = $datos['fechadocumento'];
		
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
		
		if( $this->mensajeError == "" ){
			$this->mensajeOK = "Se ha generado correctamente su documento, bajo el c&oacute;digo : ".$idDocumento;
			$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $idDocumento);
			return $respuesta;
		}
		else{
			$this->mensajeError = "No se pudo completar la generaci&oacute;n de su documento, intente nuevamente ".$this->mensajeError;
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}
	}

	public function GenerarDocumentoCompleto($datos){

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
	public function crearPlantilla($idDocumento, $TipoFirma, $empleado){

		$datos = $_POST;

		$dt = new DataTable();
		$html = '';

		//Construir Firmantes 
		$firmantes_completos = array();
		$this->crearFirmantes($idDocumento,$empleado,$firmantes_completos);

		if( count($firmantes_completos) > 0 ){

			//Crear Plantillas 
			$this->construirPlantilla($idDocumento,$html);

			if( $html != '' ){
			
				//Sustituir variables 
				$resultado_html = '';
				$this->sustituirVariables($idDocumento,$html, $resultado_html); 

				//Si es de firma manual
				if( $TipoFirma == 1 ){

					$firmantes_tabla = '';
					$this->construirTablaFirmantes($idDocumento,$firmantes_completos, $firmantes_tabla);
					
					//Unir texto y tabla de Firmantes	
					$resultado_html .= $firmantes_tabla;
				}

				$resultado_html .= "</body></html>";

				//Codificacion internacional del texto
				$texto = utf8_encode($resultado_html);

				//Sustituir acentos 
				$texto_completo = $this->TildesHtml($texto);

				$this->mensajeOK = "La Plantilla fue creada de forma exitosa";
				$respuesta = $this->construirRespuesta(200,$this->mensajeOK, true, $texto_completo);
				return $respuesta;

			}else{
				$this->mensajeError = "Ha ocurrido un error inesperado, no se pudo generar la Plantilla del Documento";
				$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
				return $respuesta;
			}
		}else{
			$this->mensajeError = "Ha ocurrido un error inesperado, no se pudo agregar los firmantes del Documento";
			$respuesta = $this->construirRespuesta(500,$this->mensajeError, false);
			return $respuesta;
		}		
	}

	//Construir Arreglos de los Firmantes
	private function crearFirmantes($idDocumento,$empleado,&$resultado){

		$datos = $_POST;

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
        $orden = 0;
        $identificador = "";
		$identificador = "RUT N&deg;";
		$datos['Representantes'] = 0;
		$datos['Empleado'] = 0;

		if( count($dt8->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt8->data as $key => $value) {
   	   
		        //Si Estado: Pendiente por firma de Empresa 
		        if(( $dt8->data[$key]["Nombre"] == 'Pendiente por firma Representante') || ($dt8->data[$key]["idEstado"] == 2 )){

		        	//Firmantes de la Empresa
			  		$f_empresa = array();
			  		$empresa_aux = array();
			  	
			        foreach ($empleado["Firmantes_Emp"] as $i => $valor) {
					
						if( $empleado["Firmantes_Emp"][$i] != '' )
						{
							//Datos faltantes 
							$empresa_aux = array ( "idDocumento" => $idDocumento, "RutEmpresa" => $empleado["RutEmpresa"], "RutFirmante" => $empleado["Firmantes_Emp"][$i], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);	        	
												
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError.=$this->documentosBD->mensajeError;
							
							//Buscar datos
							if(  $empleado["Firmantes_Emp"][$i] != '' ){
								$array = array( "RutEjecutivo" => $empleado["Firmantes_Emp"][$i] );

								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];
																		
							//Completar el arreglo
							$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$empleado["Firmantes_Emp"][$i], "nombre_emp" => "P.p ".$nombre_emp, "rut_emp" => "RUT N&deg;".$empleado["RutEmpresa"]);
							
							//Agregar al final 
							array_push($f_empresa, $nuevo);
							
							if($this->mensajeError == "" ) $datos['Representantes']++;
						}
		        	}
		        } //Fin del Si Estado: Pendiente por firma de Empresa 
				
				//Si Estado: Pendiente por firma de Empleado 
				if(($dt8->data[$key]["Nombre"] == 'Pendiente por firma Empleado')||($dt8->data[$key]["idEstado"] == 3 )){

		        	//Firmantes del empleado 
		        	$f_empleado = array();
		        	$empleado_aux = array();

					if( $empleado['personaid'] != '' )
						$empleado['rutusuario'] = $empleado['personaid'];

					if( $empleado['newusuarioid'] != '' )
						$empleado['rutusuario'] = $empleado['newusuarioid'];

					//Buscar datos
		        	if ( $empleado['rutusuario'] != '' ){
			        	$array = array( "RutEjecutivo" => $empleado['rutusuario']);
			        	$this->documentosBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;

			        	//Completar arreglo
						$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$empleado['rutusuario'] );

						//Datos faltantes 
						$empleado_aux = array ( "idDocumento" => $idDocumento, "RutEmpresa" =>$empleado['rutusuario'], "RutFirmante" => $empleado['rutusuario'], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

						//Agregara a la tabla
						$this->documentosBD->agregarFirmantes($empleado_aux);
						$this->mensajeError.=$this->documentosBD->mensajeError;
			        
						//Agregar al final
						array_push($f_empleado, $nuevo); 
						
						if($this->mensajeError == '' ) $datos['Empleado']++;
					}  
						
		        }//Fin de Estado: Pendiente por firma de Empleado 
				
	        }//Fin del Foreach de los Estados del WF
		}
       
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();
		
		//Si el flujo tiene Empresa
		if( $datos['Representantes'] == 1 ) array_push($firmantes_completos, $f_empresa);
					
		//Si el flujo tiene Empleado
		if( $datos['Empleado'] == 1 ) array_push($firmantes_completos, $f_empleado);

		$resultado = array();
		$resultado = $firmantes_completos; 

		return $resultado;
	}
	
	//Sustituir variables del Documento
	private function sustituirVariables($idDocumento,$html,&$resultado){
		
		$resultado = '';

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE);

		foreach ($tablas as $key => $value) {
			$this->buscarVariables($idDocumento,$html,$value,$resultado);
			$html = $resultado;
			$resultado = '';
		}
		
		//VARIABLES DE LA SUBCLAUSULA 
		$this->buscarVariablesSubClausulas($idDocumento,$html, $resultado);
	
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	private function buscarVariables($idDocumento,$html, $busqueda,&$resultado){

		$datos = $_POST;
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
				$this->documentosBD->obtenerVariablesRepresentante($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_REPRESENTANTE;
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

		if( count($dt->data) > 0){

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
					}

					if( strlen($value) > 0 ) {
					
						if ( $this->validateDate($value,'d-m-Y')){

							//Si la fecha es Indefinido
							if( $value == VAR_FECHA_IND ){
							
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								
							}else{

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
															
								$fecha_s = $this->convertirFechaLarga($value);		
								$fecha_c = $this->convertirFechaCorta($value);
								
								array_push($aux,$value);
								array_push($aux,VAR_HASTA_EL.$fecha_s);
								array_push($aux,VAR_HASTA_EL.$fecha_c);
								array_push($aux,$fecha_s);
								array_push($aux,$fecha_c);
							}

						}else{
							array_push($variables, $var);
						    array_push($aux, $value);
						}	

						if ( is_numeric($value)){
							
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							
							$numeros = $this->numerosALetras($value);
							
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
						}	
					}
				}
			}
		

			$html_resultado = '';

			//Buscamos si existe conincidencia
			foreach ($variables as $key => $value) {
				if ( strstr($html,$value) ){
					//Sustituir en el HTML
					$html = str_replace($variables,$aux,$html);
				}
			}

			if( strstr($html,VAR_LOGO)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];

				$logo = $rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_LOGO,$logo,$html);
			}

			if( strstr($html,VAR_RUTA)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];
				
				$ruta = VAR_RUTA_COMPLETA.$rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_RUTA,$ruta,$html);
			}	
		
			$resultado = $html;
			return $resultado;
		}
	}
	
	//Buscar subclausulas
	private function buscarVariablesSubClausulas($idDocumento,$html,&$resultado){

		$datos = $_POST;
	
		$dt = new DataTable();
		$var_busqueda = '';
		$resultado = '';

		//Buscar subclausulas 
		$datos['idDocumento'] = $idDocumento;

		$this->ContratosDatosVariablesBD->obtener($datos,$dt);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $dt->leerFila() ){
			$jornada = $dt->obtenerItem('Jornada');
		}

		//Jornadas
		if( $jornada != '' ){
			
			$datos['idSubClausula'] = $jornada; 
			$datos['idTipoSubClausula'] = 2; 
	
			$this->subclausulasBD->obtener($datos,$dt);
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

						if( VAR_SUBCLAUSULAS == '') $var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
						else $var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

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
				}
			} 
		}

		$resultado = $html;
		return $resultado;
	}

	//Construir Plantilla
	private function construirPlantilla($idDocumento, &$resultado){
		
		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
		
		$datos['idDocumento'] = $idDocumento;
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		//Buscar la Plantilla del documento
		$this->documentosBD->obtener($datos,$dt);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $dt->leerFila() ){
			$idPlantilla = $dt->obtenerItem('idPlantilla');
		}

		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		if( $idPlantilla > 0 ){

			$datos['idPlantilla'] = $idPlantilla;

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;

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

			        	if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
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

			        	if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
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

			        	if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}

						if ( $aux != '' ){
							$clausula .= $aux;	        		
						}
					}

					//Si encuentra la variable vacia 
					if ( strstr($dt->data[$i]["Texto"],VAR_VACIA) ){
						$clausula = '';
					}
					
					//Agregar clausulas
					$contenido .= $clausula;
				}
	        
				//Limpiar el HTML
				$aux = '';
		        $aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><img>');
				$html .= $aux;
		        $html .= "</div>";

		        $resultado = $html;
		        return $resultado;
			}
			else{
				return false;
			}
        }
        else{
        	return false;
        }
	}
		
	//Construir tabla de Firmates de un Documento 
	private function construirTablaFirmantes($id,$datos,&$resultado){

		//Declarar variable para firmantes 
		$tabla_firmantes = ''; 
		$num = 0;
		$div = 0;
		$encabezado = '<div><table border="0" width="100%" style="page-break-inside:avoid; padding-top: 3cm; "><tbody><tr>';

		$dt = new DataTable();
		$array = array( "idDocumento" => $id );
		$this->documentosBD->obtenerFirmantesXDocumento($array,$dt); 
		$this->mensajeError = $this->documentosBD->mensajeError;

		if( count($datos) > 0 ){
			foreach ($datos as $i => $value) {

			if( count($datos[$i]) > 0 ){
	       		foreach ($datos[$i] as $j => $value) {

	       			if( $num == 0 ){
		     			$tabla_firmantes .= $encabezado;
		     		}
		   			//Mientras no sea el notario, se agrega a la tabla 
					if( $value["nombre_not"] == ""){
			
		       		 	$tabla_firmantes.= '<td align="center"><p style="line-height: 1.3"><strong>';

		       		 		if( count($datos[$i][$j]) > 0){
			       				foreach ($datos[$i][$j] as $key => $value) {
			       					$tabla_firmantes .= $value."<br>";
			       				}
			       			}else{
			       				$tabla_firmantes .= $value."<br>";
			       			}

		       			$num++;
		       			$div++;
			       		$tabla_firmantes .= '</strong></p></td>';

			       		if( $num == 3 ){
			       			$tabla_firmantes .= '</tr></tbody></table></div>';
			       			$num = 0;
				       	}
				    }   		
		       	}	
		    }
			}
		}
       
       if (($div % 3) == 1){
       		$tabla_firmantes .= '</tr></tbody></table></div>';
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
	
	//Sustituir acentos
	private static function TildesHtml($cadena){ 
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }
	
	//Valida Fecha
	private function validateDate($date, $format = VAR_FORMATO_FECHA){	
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	//Valida Tipo de Rut Chileno 
	private function valida_rut($rut){

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
		return ucwords($resultado);
	}

	//Convertir a formato de fecha corta 
	private function convertirFechaCorta($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%B de %Y", strtotime($fecha));
		return ucwords($resultado);
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
		$datos['estadocivil'] = $datos['idEstadoCivil'];

		switch($datos['estadocivil']){
			case '1':
				$datos['estadocivil'] = 'Soltero(a)';
				break;
			case '2':
				$datos['estadocivil'] = 'Casado(a)';
				break;
			case '3':
				$datos['estadocivil'] = 'Divorciado(a)';
				break;
			case '4':
				$datos['estadocivil'] = 'Viudo(a)';
				break;
		}

		if( ! isset($datos['rolid'])){
			$datos['rolid'] = ROL;
		}

		$this->empleadosBD->agregar($datos);
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
	//------    PHP Version o mayores (aunque podría funcionar en versiones anteriores, tendrías que probar)

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
	                        $xcadena = "UNO ";
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

}
?>