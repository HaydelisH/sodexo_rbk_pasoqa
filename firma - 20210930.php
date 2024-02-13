<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once('includes/Paginas.php');
// y la seguridad
include_once('includes/Seguridad.php');

include_once('includes/parametrosBD.php');
include_once('includes/documentosdetBD.php');
include_once('includes/firmantesBD.php');
include_once('includes/contratofirmantesBD.php');
include_once('includes/formularioPlantillaBD.php');
include_once('Config.php');

//Firma RBK
include_once('includes/firmarbk.php');
//Firma dec5
include_once('dec5.php');

$page = new firma();

class firma {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para juntar los mensajes de error
	public $mensajeError='';

	public $seguridad;
	
	private $firmarbk;
	private $parametrosBD;
	private $firmantesBD;
	private $contratofirmantesBD;
	private $formularioPlantillaBD;
	
	private $url;
	private $token;
	private $companyId;
	private $machineId;
	private $username;
	private $password;	
	private $countryCode;	
	private $type;	

	private $token_sesion;

	//Datos para la firma
	private $signers_roles;
	private $signers_ruts;
	private $signers_ruts_sin_guion;
    private $signers_order;	
    private $signers_type_sign;

    private $datos;
	private $signers_institutions;
	private $signers_emails;
	private $signers_type;
	private $signers_notify;	
	private $nombre_archivo;
	private $archivo_codificado;
	private $valor_arr;

	private $dec5;
	private $band = 0;
	private $firma;
	
	private $mensajeRol="";
	private $orden = 0;
	private $session_rbk;
	private $id_rbk;
	private $respuesta;

	// funcion contructora, al instanciar
	function __construct()
	{

	// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			
			return;
		}

		$this->parametrosBD = new parametrosBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->firmantesBD = new firmantesBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		$this->formularioPlantillaBD = new formularioPlantillaBD();
			
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->parametrosBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		$this->formularioPlantillaBD->usarConexion($conecc);
	}
	
	public function Login(&$resultadotrx)
	{
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "USER";	
		$arr["type"] 		= "START";
		$arr["subtype"] 	= "SESSION";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		
		//vamos a obtener los parametros necesarios para envío de consultas a la api
		$res = $this->obtener_parametros();
		if ($res == false)
		{
			return;
		}
				
		$arr2["companyId"] 	= $this->companyId;
		$this->machineId	= "";
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/session";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, '',$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		if ($this->mensajeError == "")
		{	
			//print_r ($resultado);
			$this->token_sesion = $resultadotrx["client"]["token"];
			
			$resultadotrx["client"]["companyId"]   = $this->companyId;
			$resultadotrx["client"]["machineId"]   = $this->machineId;
			$resultadotrx["client"]["username"]    = $this->username;
			$resultadotrx["client"]["password"]    = $this->password;
			$resultadotrx["client"]["url"]  	   = $this->url;
			$resultadotrx["client"]["countryCode"] = $this->countryCode;
			$resultadotrx["client"]["type"] 	   = $this->type;
			$resultadotrx["client"]["apiKey"] 	   = $this->token;
			
			
			return true;
		}
		
		
		return false;
		
	}
	
	public function Status($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "STATUS";
		$arr["subtype"] 	= "SESSION";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr3["digitalSignature"]["sessionId"] = $datos["sessionId"];
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/signature/status";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;
	}
	
	public function ObtenerRoles($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "GET";
		$arr["subtype"] 	= "ROLE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["personalNumber"] = $datos["personalNumber"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/role/get";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;
	}
	
	public function AgregarRol($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "ADD";
		$arr["subtype"] 	= "ROLE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["personalNumber"]= $datos["personalNumber"];
		
		$arr4["roles"][0]["code"] = $datos["rol"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/role/";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
			
		return true;
	}	
	
	
	public function EliminarRol($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a convertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "REMOVE";
		$arr["subtype"] 	= "ROLE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["personalNumber"] = $datos["personalNumber"];
		
		$arr4["roles"][0]["code"] = $datos["rol"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/role/";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "DELETE";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, $this->token_sesion, $this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;
	}	
	
	//Prepara datos para Cargar
	public function prepararDatosDocumento($datos, &$resultado){

		$documento = array();
	
		//Documento
		$documento["documentosdatos"][0]["documentobase64"]	= $datos['file'];
		$documento["documentosdatos"][0]["documentotipo"]  = $datos['type_doc'];

		//Firmantes
		foreach ($datos['signers_ruts_sin_guion'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmanteid"] = $value;
		}

		foreach ($datos['signers_roles'] as $key => $value) {//PENDIENTE ASIGNAR ROL
			$documento["firmantesdatos"][$key]["firmanterol"] = trim($value);
		}

		foreach ($datos['signers_type_sign'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmantetipofirma"] = $value;
		}

		foreach ($datos['signers_order'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmanteorden"] = $value;
		}

		$documento['sessionid'] = $datos['sessionid'];

		//Ir a Cargar Documento		
		$this->CargarDocumento($documento,$dt);
		$this->mensajeError.=$this->mensajeError;

		$resultado = $dt;
		return $resultado;
	}
	
	public function CargarDocumento($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a convertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "CREATE";
		$arr["subtype"] 	= "SESSION";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["sessionId"]	= $this->operacionId_fechahora();
			
		///
		$cantidadfirmantes = count($datos["firmantesdatos"]);
		for ($f=0; $f < $cantidadfirmantes; $f++) 
		{
			$arr9["countryCode"] 				= $this->countryCode;
			$arr9["personalNumber"] 			= $datos["firmantesdatos"][$f]["firmanteid"];
			$arr9["type"] 						= $this->type;
		
			$arr8[$f]["identityDocument"] 			= $arr9;
			$arr8[$f]["authenticationFactorsCode"] 	= $datos["firmantesdatos"][$f]["firmantetipofirma"];
		
			$arr10["code"] 				= $datos["firmantesdatos"][$f]["firmanterol"];
			$arr10["type"]				= "";
			$arr8[$f]["role"] 			= $arr10;
		
			$arr8[$f]["signingOrder"]	= $datos["firmantesdatos"][$f]["firmanteorden"];
		
			$arr4["signers"]			= $arr8;
		}
		
		$cantidaddocumentos = count($datos["documentosdatos"]);
		$arr4["documentTotal"]		= $cantidaddocumentos;
		for ($d=0; $d < $cantidaddocumentos; $d++) 
		{
			$arr7["format"]				= "application/pdf";
			$arr7["data"]				= $datos["documentosdatos"][$d]["documentobase64"];				
			$arr6["content"]			= $arr7;
			$arr6["referenceName"]		= "document";
			$arr6["name"]				= $datos["documentosdatos"][$d]["documentotipo"];	
			$arr4["documents"][$d] 		= $arr6;
							
			$arr3["digitalSignature"] 	= $arr4;
			
		}
		$arr["data"] 				= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/signature/json";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	


	public function CargarDocumentoSinSesion($datos,&$resultadotrx)
	{	
		//Buscar parametros necesarios 
		$res = $this->obtener_parametros();
		if ($res == false)
		{
			return;
		}
		$this->token_sesion = $datos['sessionid'];
	
		//armado de array que corresponde a la cabezera del json a convertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "CREATE";
		$arr["subtype"] 	= "SESSION";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["sessionId"]	= $this->operacionId_fechahora();
			
		///
		$cantidadfirmantes = count($datos["firmantesdatos"]);
		for ($f=0; $f < $cantidadfirmantes; $f++) 
		{
			$arr9["countryCode"] 				= $this->countryCode;
			$arr9["personalNumber"] 			= $datos["firmantesdatos"][$f]["firmanteid"];
			$arr9["type"] 						= $this->type;
		
			$arr8[$f]["identityDocument"] 			= $arr9;
			$arr8[$f]["authenticationFactorsCode"] 	= $datos["firmantesdatos"][$f]["firmantetipofirma"];
		
			$arr10["code"] 				= $datos["firmantesdatos"][$f]["firmanterol"];
			$arr10["type"]				= "";
			$arr8[$f]["role"] 			= $arr10;
		
			$arr8[$f]["signingOrder"]	= $datos["firmantesdatos"][$f]["firmanteorden"];
		
			$arr4["signers"]			= $arr8;
		}
		
		$cantidaddocumentos = count($datos["documentosdatos"]);
		$arr4["documentTotal"]		= $cantidaddocumentos;
		for ($d=0; $d < $cantidaddocumentos; $d++) 
		{
			$arr7["format"]				= "application/pdf";
			$arr7["data"]				= $datos["documentosdatos"][$d]["documentobase64"];				
			$arr6["content"]			= $arr7;
			$arr6["referenceName"]		= "document";
			$arr6["name"]				= $datos["documentosdatos"][$d]["documentotipo"];	
			$arr4["documents"][$d] 		= $arr6;
							
			$arr3["digitalSignature"] 	= $arr4;
			
		}
		$arr["data"] 				= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);

		$urlmetodo 			= $this->url."/api/v1/signature/json";

		$this->firmarbk = new firmarbk();
	
		$tipo_solicitud = "POST";

		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	

	//Prepara datos para Cargar
	public function prepararDatosparaFirmar($datos, &$resultado){

		$firmante = array();
		
		$firmante["authenticated"]  = $datos["authenticated"];
		$firmante["id"] 			= $datos["id"];
		$firmante["sign"] 			= $datos["sign"];
		$firmante["subtype"] 		= $datos["subtype"];
		$firmante["type"] 			= $datos["type"];
		
		if( isset($datos['firmaTercero']) )
			$firmante["operadorid"] 	= $datos["operadorid"];
		else
			$firmante["operadorid"] = $datos["RutFirmante"];

		$firmante["firmanteid"] 	= $datos["RutFirmante"];
		$firmante["sessionId"] 	    = $datos["session_doccode"];

		$firmante['sessionid'] = $datos['sessionid'];

		$this->FirmarDocumento($firmante,$dt);
		$this->mensajeError.=$this->mensajeError;	

		$resultado = $dt;
		return $resultado;
	}
	
	public function FirmarDocumento($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a convertir
		$arr_enc["companyId"] 	= $this->companyId;
		$arr_enc["machineId"] 	= $this->machineId;
		$arr_enc["operationId"]	= $this->operacionId_fechahora();
		$arr_enc["username"]  	= $this->username;
				
		$arr_idd0["countryCode"] 	= $this->countryCode;
		$arr_idd0["personalNumber"] = $datos["operadorid"];
		$arr_idd0["type"] 			= $this->type;
		$arr_idd["identityDocument"]= $arr_idd0;
		$arr_enc["operator"]  		= $arr_idd;
		
		$arr["client"] 				= $arr_enc;
		//fin armado 
		
		$arr["code"] 				= "SIGN";
		$arr["createdAt"]			= $this->crea_createdAt();
		$arr["id"]						= $this->operacionId_fechahora();	
		$arr["subtype"]					= "SESSION";
		$arr["type"]					= "SIGN";
		
		$arr_aut0["authenticated"]	= $datos["authenticated"];
		$arr_aut0["id"]				= $datos["id"];
		$arr_aut0["sign"]			= $datos["sign"];
		$arr_aut0["subtype"]		= $datos["subtype"];
		$arr_aut0["type"]			= $datos["type"];
		
		$arr_data0["authenticationResult"]= $arr_aut0;
		
		$arr_ide0["countryCode"]	= $this->countryCode;
		$arr_ide0["personalNumber"]	= $datos["firmanteid"];
		$arr_ide0["type"]			= $this->type;
		
		$arr_ide["identityDocuments"][0]= $arr_ide0;
				
		$arr_data0["digitalIdentity"]  	= $arr_ide;
		
		$arr_ses0["sessionId"]			= $datos["sessionId"];
		$arr_data0["digitalSignature"]  = $arr_ses0;
		
		$arr["data"]					= $arr_data0;
		
		
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/signature/sign";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	
	
	public function FirmarDocumentoSinSesion($datos,&$resultadotrx)
	{
		//Buscar parametros necesarios 
		$res = $this->obtener_parametros();
		if ($res == false)
		{
			return;
		}
		$this->token_sesion = $datos['sessionid'];

		//armado de array que corresponde a la cabezera del json a convertir
		$arr_enc["companyId"] 	= $this->companyId;
		$arr_enc["machineId"] 	= $this->machineId;
		$arr_enc["operationId"]	= $this->operacionId_fechahora();
		$arr_enc["username"]  	= $this->username;
				
		$arr_idd0["countryCode"] 	= $this->countryCode;
		$arr_idd0["personalNumber"] = $datos["operadorid"];
		$arr_idd0["type"] 			= $this->type;
		$arr_idd["identityDocument"]= $arr_idd0;
		$arr_enc["operator"]  		= $arr_idd;
		
		$arr["client"] 				= $arr_enc;
		//fin armado 
		
		$arr["code"] 				= "SIGN";
		$arr["createdAt"]			= $this->crea_createdAt();
		
		$arr_aut0["authenticated"]	= $datos["authenticated"];
		$arr_aut0["id"]				= $datos["id"];
		$arr_aut0["sign"]			= $datos["sign"];
		$arr_aut0["subtype"]		= $datos["subtype"];
		$arr_aut0["type"]			= $datos["type"];
		
		$arr_data0["authenticationResult"]= $arr_aut0;
		
		$arr_ide0["countryCode"]	= $this->countryCode;
		$arr_ide0["personalNumber"]	= $datos["firmanteid"];
		$arr_ide0["type"]			= $this->type;
		
		$arr_ide["identityDocuments"][0]= $arr_ide0;
				
		$arr_data0["digitalIdentity"]  	= $arr_ide;
		
		$arr_ses0["sessionId"]			= $datos["sessionId"];
		$arr_data0["digitalSignature"]  = $arr_ses0;
		
		$arr["data"]					= $arr_data0;
		
		$arr["id"]						= $this->operacionId_fechahora();	
		$arr["subtype"]					= "SESSION";
		$arr["type"]					= "SIGN";
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/signature/sign";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	

	public function DescargarDocumento($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "GET";
		$arr["subtype"] 	= "DOCUMENT";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["sessionId"]	= $datos["sessionId"];
		$arr4["documents"][0]["id"]	= $datos["id"];
		
		$arr3["digitalSignature"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/document/get";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	
			
	private function crea_createdAt()
	{
		$now = (string)microtime();
		$now = explode(' ', $now);
		$mm = explode('.', $now[0]);
		$mm = $mm[1];
		$now = $now[1];
		$segundos = $now % 60;
		$segundos = $segundos < 10 ? "$segundos" : $segundos;
	 
		return (date("Y-m-d")."T".date("H:i:s").".". substr($mm,0,3));
	}
	
	private function operacionId_fechahora()
	{
		$id = "";
		$now = (string)microtime();
		$now = explode(' ', $now);
		$mm = explode('.', $now[0]);
		$mm = $mm[1];
		$now = $now[1];
		$segundos = $now % 60;
		$segundos = $segundos < 10 ? "$segundos" : $segundos;
		
		$id = 	strval((date("Ymd").date("His").substr($mm,0,3)));
		return $id;
	}
	
	
	private function obtener_parametros()
	{
		if ($this->leer_parametro("frbk_token"))
			$this->token = $this->parametro;
	
		$institucion = "";
		if ($this->leer_parametro("frbk_url"))
			$this->url	= $this->parametro;
		
		if ($this->leer_parametro("frbk_companyId"))
			$this->companyId	= $this->parametro;
		
		if ($this->leer_parametro("frbk_username"))
			$this->username		= $this->parametro;
		
		if ($this->leer_parametro("frbk_password"))
			$this->password		= $this->parametro;
		
		if ($this->leer_parametro("frbk_countryCode"))
			$this->countryCode	= $this->parametro;
		
		if ($this->leer_parametro("frbk_type"))
			$this->type	= $this->parametro;
		
		if ($this->mensajeError != "")
		{
			//print ("error:".$this->mensajeError);
			return false;
		}
		
		return true;
	}
	
	
	private function leer_parametro($parametro)
	{	
		$dt = new DataTable();
		
		$datos["idparametro"] = $parametro;
		$this->parametrosBD->Obtener($datos,$dt);
		if($dt->leerFila())
		{
			$this->parametro = $dt->obtenerItem("parametro");
			return true;
		}
		else
		{
			$this->mensajeError.= "Parametro ".$parametro." no encontrado en base de datos ";
			return false;
		}
		
	}

	//FIRMA DEC
	public function firmar_pin($idDocumento, $usuarioid){
		
		$datos = $_REQUEST;
		$datos["idDocumento"] = $idDocumento;
		$datos['RutFirmante'] = $usuarioid;

		if(! $this->TieneUnaFirma($idDocumento) ){

			//Actualizamos
			$this->band = 0; 
				
			//Buscamos los datos necesarios
			$this->cargarDocumentoConRoles($idDocumento);
		
			//quiere decir que hubo algún tipo de error al crear rol al cargar el documento
			if ($this->mensajeRol != "")
			{
				$this->mensajeError.= $this->mensajeRol;
				return false;
			}

			if ( $this->datos["file"] != "" ){
				
				//Voy a buscar firmantes
				$this->cargarFirmante($idDocumento, $usuarioid);

				//Me voy a firmar
			 	$this->dec5 = new dec5();
		
			 	//Firma PIN
				$this->dec5->FirmaPin($this->datos,$dt);
				$this->mensajeError.=$this->dec5->mensajeError;

				if( $this->mensajeError ){
					$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}

				//Asignamos variables
				if( $dt["result"][0]["code"] ){
					$datos["DocCode"] = $dt["result"][0]["code"];
					$datos["documento"] = $dt["result"][0]["file"];
					
					//Fecha de firma de DEC
					$fecha_actual = '';

					$res = array();
					$res = $dt["result"][0]["signers"];

					foreach ($res as $key => $value) {
						foreach ($res[$key] as $key_1 => $value_1) {
						
							 if( $res[$key]['rut'] == $this->datos['user_rut'] && $res[$key]['order'] == $this->orden){
							 	$fecha_actual = $res[$key]['date'];
							 }
						}
					}
					
					$ejemplo = ''; 
					if( $fecha_actual != '' ){ //Si tiene fecha de firma
						$ejemplo = str_replace('/','-', $fecha_actual); 
					}else{
						$ejemplo = date("d-m-Y H:i:s");
					}
					$datos["FechaFirma"] = $ejemplo; 
				}
				//Si todo fue bien
				if( ($dt["status"] == 200) && ($dt["message"] == "Success") ) {
				    
				    //Actualizar en la BD
					$this->actualizaDocumento($datos['idDocumento'],$datos["DocCode"]);
				  	$this->actualizarFirma($datos['idDocumento'],$datos["RutFirmante"],$datos["FechaFirma"],$datos["documento"]);
				  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				 	$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true);
					return $respuesta;
				}
				else{
					if( $dt["status"] == 400 ){
						if($dt["result"]["code"] != "" ){ 
							//Actualizar en la BD
							$datos["DocCode"] = $dt["result"]["code"];
							$this->actualizaDocumento($datos['idDocumento'],$datos["DocCode"]);
						}	
					}
					$this->mensajeError .= $dt["message"];
					$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}//Fin del else
			}
		 	else{
				
		 		$this->mensajeError .= "No se pudo completar la carga del Documento";
		 		$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
				return $respuesta;
		 	}
		}else{
			//Actualizamos 
			$this->band = 1;
			
			//Buscamos los datos necesarios
		 	$this->cargarFirmante($idDocumento,$usuarioid);

		 	//Me voy a firmar
		 	$this->dec5 = new dec5();
	
		 	//Firma PIN
			$this->dec5->FirmaPin($this->datos,$dt);
			$this->mensajeError.=$this->dec5->mensajeError;

			if( $this->mensajeError ) {
				$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
				return $respuesta;
			}
	
			//Actualizar orden del firmante
			$this->orden--;

			//Asignamos variables
			$datos["DocCode"] = $dt["result"][0]["code"];
			$datos["documento"] = $dt["result"][0]["file"];
			
			//Fecha de firma de DEC
			$fecha_actual = '';

			$res = array();
			$res = $dt["result"][0]["signers"];

			foreach ($res as $key => $value) {
				foreach ($res[$key] as $key_1 => $value_1) {
				
					 if( $res[$key]['rut'] == $this->datos['user_rut'] && $res[$key]['order'] == $this->orden){
					 	$fecha_actual = $res[$key]['date'];
					 }
				}
			}
			
			$ejemplo = ''; 
			if( $fecha_actual != '' ){ //Si tiene fecha de firma
				$ejemplo = str_replace('/','-', $fecha_actual); 
			}else{
				$ejemplo = date('d-m-Y H:i:s');
			}
			$datos["FechaFirma"] = $ejemplo;

			//Si todo fue bien
			if( ($dt["status"] == 200) && ($dt["message"] == "Success") ) {
			    //Actualizar en la BD
				$this->actualizaDocumento($datos['idDocumento'],$datos["DocCode"]);
			  	$this->actualizarFirma($datos["FechaFirma"], $datos["documento"],$datos["RutFirmante"]);
			  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
			  	$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true);
				return $respuesta;
			}
			else{
				if( $dt["status"] == 400 ){
					if($dt["result"]["code"] != "" ){ 
						//Actualizar en la BD
						$datos["DocCode"] = $dt["result"]["code"];
						$this->actualizaDocumento($datos['idDocumento'],$datos["DocCode"]);
					}
					$this->mensajeError .= $dt["message"];
				    $respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}
			}
			
		}//Fin del Else Grande
	}

	//Firmar RBK
	public function firmar_rbk($documento, $usuarioid){
		
		$datos = $_REQUEST;
		$datos["idDocumento"] = $documento['idDocumento'];
		$datos['RutFirmante'] = $usuarioid;
		$datos['operadorid'] = $documento['operadorid'];
		$datos['firmaTercero'] = $documento['firmaTercero'];

		$respuesta = array();
		
		$idDocumento = $documento['idDocumento'];

		if(! $this->TieneUnaFirma($idDocumento) && ! $this->consultarDocCode($idDocumento) ){

			//Actualizamos
			$this->band = 0; 
				
			//Buscamos los datos necesarios
			//$this->cargarDocumentoSinRoles($idDocumento);
			$this->cargarDocumentoConRoles_RBK($idDocumento);

			if ( $this->datos["file"] != "" ){

				$this->datos['sessionid'] = $documento['sessionid'];

				$this->prepararDatosDocumento($this->datos, $dt);

				if( $this->mensajeError != '' ){
					$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}

				if ( $this->buscarDatosDocumentosRBK($dt) ){

					if( $this->ejecutarFirma($datos) ){

						//Consultar si documento ya esta firmado

						$this->documentosdetBD->Obtener($datos, $dt);
						$this->mensajeError .= $this->documentosdetBD->mensajeError;

						if( $this->mensajeError == '' ){

							if( $dt->leerFila()){
								$estado_firmado = $dt->obtenerItem("idEstado"); //Estado del Documento
							}

							if( $estado_firmado == 6 ){//El documento esta firmado

								if ( $this->envioGestor($idDocumento)) {
							  		//OK
									$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
									$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
									return $respuesta;
								}else{
									$this->mensajeError = 'No se pudo completar el envio del documento al Gestor';
									$respuesta = $this->construirRespuesta(300, $this->mensajeError, false);
									return $respuesta;
								}
							}else{
								$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true);
								return $respuesta;
							}
						}else{
							$respuesta = $this->construirRespuesta(300, $this->mensajeError, false);
							return $respuesta;
						}
					}
					else{
						$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
						return $respuesta;
					}
				}
			}
		}else{
			
			//Actualizamos
			$this->band = 1; 

			if( $this->ejecutarFirma($datos) ){
			
				$this->documentosdetBD->Obtener($datos, $dt);
				$this->mensajeError .= $this->documentosdetBD->mensajeError;

				if( $this->mensajeError == '' ){

					if( $dt->leerFila()){
						$estado_firmado = $dt->obtenerItem("idEstado"); //Estado del Documento
					}
					if( $estado_firmado == 6 ){//El documento esta firmado

						if ( $this->envioGestor($datos["idDocumento"])) {
							//OK
							$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
							$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
							return $respuesta;
						}else{
							$this->mensajeError = 'No se pudo completar el envio del documento al Gestor';
							$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
							return $respuesta;
						}
					}else{
						$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
						$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
						return $respuesta;
					}
				}

			}else{
				$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
				return $respuesta;
			}

		}
	}

	private function construirRespuesta($codigo, $msj, $estado, $data = '' ){
		$respuesta = array();
		$respuesta['codigo']  = $codigo;
		$respuesta['mensaje'] = $msj;
		$respuesta['estado']  = $estado;
		$respuesta['data'] = $data;
		return $respuesta;
	}
 
	private function ejecutarFirma($datos){	

		//Variables para subida del Documento
		$dt = new DataTable(); 
	
		$idDocumento = $datos['idDocumento'];
		$usuarioid = $datos['RutFirmante'];
	
		$this->cargarFirmanteSinRol($idDocumento, $usuarioid);

		if( $this->band == 1 ){

			$this->buscarDatosDeSession($datos['idDocumento']);

			if( $this->mensajeError != '' ){
				return false;
			}
		}

		$this->quitarGuiondelRut($usuarioid, $datos['RutFirmante']);
		$this->quitarGuiondelRut($datos['operadorid'],$datos['operadorid']);
		$datos['session_doccode'] = $this->session_rbk;

		$this->prepararDatosparaFirmar($datos, $dt);

		//Si ocurrio un error en firma
		if( $this->mensajeError != '' ){
   
			//Grabar errores de la firma
			$this->graba_log_validacion("Documento: ".$idDocumento." | Firmante: ".$usuarioid." | Error :".$this->mensajeError);
			$error = $this->mensajeError;
			
			$datos['rut'] =  $datos['RutFirmante'];
			$datos['numero'] = $this->id_rbk;
			
			//Busco si realmente se firmo el Documento
			//Aca se limpia el error
			if( $this->buscarFirmaDocumento($datos)){
			//if( strpos($this->mensajeError, "Documento ya firmado por el firmante")){
				$this->mensajeError = '';
				$this->actualizar($idDocumento);
				return true;
			}
			
			$this->mensajeError = $error;
			if( strpos($this->mensajeError , "Firmante invalido")){
				$this->mensajeError = "El firmante de Rut : $usuarioid, no tiene un rol v&aacute;lido, para realizar la firma a este Documento";
				return false;
			}
   
			return false;
		}
		$this->valor_arr = "";
		$this->obtener_dato_arr($dt,"timestamp");
		
		if ($this->valor_arr != "")
		{
			$fechafirma = $this->valor_arr;	
		}
		$code = $dt['status']['status']['code'];
		if ( $fechafirma != '' && $code == 200 ){
 
			//Si firmo correctamente 
			$datos["id"] = $this->id_rbk;
			$datos['sessionId'] = $this->session_rbk;

			$this->DescargarDocumento($datos,$dt);
			$this->mensajeError.=$this->firma->mensajeError;
			
			if ($this->mensajeError == "")
			{
				$this->valor_arr = "";
				$this->obtener_dato_arr($dt,"data");
				
				if ($this->valor_arr != "")
				{
					$documento = $this->valor_arr;	
				}

				$firmante = $this->datos['user_rut'];
				$id = $datos['idDocumento'];
				$this->convertirFecha($fechafirma, $ff);
	
				if ( $this->actualizarFirma($id,$firmante,$ff, $documento ) ){
					return true;
				}
			}
		}
		return false;
	}

	//Actualizar documento de RBK 
	private function buscarDatosDocumentosRBK($dt){

		$datos = $_REQUEST;

		//Actualizar datos del documento
		$this->valor_arr = "";
		$this->obtener_dato_arr($dt,"sessionId");

		if ($this->valor_arr != "")
		{
			$session = '';
			$id = '';
			$session = $this->valor_arr;
			$id = $dt['data']['digitalSignature']['documents'][0]['id'];
			$doccode = $session.SEPARADOR_DOCCODE.$id;
		
			$this->session_rbk = $session;
			$this->id_rbk = $id;

			if ( $this->actualizaDocumento($datos['idDocumento'],$doccode) )
				return true;
		}
		return false;
	}

	//Buscar datos dentro de la matriz 
	private function obtener_dato_arr($matriz,$variable)
	{
		if( count($matriz) > 0 ){
			foreach($matriz as $key=>$value)
			{
				if (is_array($value))
				{
					$this->obtener_dato_arr($value,$variable);
				}
				else
				{  
					if ($key == $variable)
					{	
						$this->valor_arr = $value;
						break;
					}
				
				}
			}
		}
				
	} 

	//Convertir formato de fecha de timestamp
	private function convertirFecha($fecha_entrada, &$fecha_salida){

		//fecha de entrada : "2019-04-26T21:32:17.355Z"
		$fecha_salida = new DateTime($fecha_entrada);
		$fecha_salida->setTimezone(new DateTimeZone('America/Santiago'));
		$fecha_salida = $fecha_salida->format('Ymd H:i:s');

		return $fecha_salida;
	}

	//Buscar datos del documentos RBK guadado
	private function buscarDatosDeSession($idDocumento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$doccode = $dt->ObtenerItem("DocCode");
			}

			if( $doccode != '' ){
				$codigos = explode(SEPARADOR_DOCCODE,$doccode);
				
				if( count($codigos) < 2 ){
					$this->mensajeError = "El Documento no se ha subido a este gestor de firma";
					return false;
				}

				$this->session_rbk = $codigos[0];
				$this->id_rbk = $codigos[1];

				return true;
			}else{
				$this->mensajeError = "El Documento no se ha subido correctamente";
				return false;
			}
		}
	}

	//Accion de completar los datos del Firmante 
	private function cargarFirmante($idDocumento, $RutFirmante){

		$datos = $_REQUEST;
		$datos["idDocumento"] = $idDocumento;
		$institucion = DEC5_INSTITUCION;

		
		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($datos, $dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		
		if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}
		if( $persona != "" ){
			$num = 0;
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$estado = $dt1->data[$i]["Nombre"];
				$persona = strtoupper($dt1->data[$i]["personaid"]);
				$firmado = $dt1->data[$i]["Firmado"];
				$firmante = strtoupper($RutFirmante);

				//Si es Cliente
				if ( mb_substr_count($estado, "Cliente") >  0 ){
					array_push($this->signers_roles, ROLES_DEC5_REPRESENTANTE_2);
					if(strtoupper($persona) == $firmante && $num == 0 && $firmado == 0 ){
						$this->datos["user_role"] = ROLES_DEC5_REPRESENTANTE_2;// el rol del usuario que firma
						$this->datos["user_institution"] = $institucion;
						$num++;
					}
				}
				//Si es Empresa
				if ( mb_substr_count($estado, "Empresa") >  0 ){
					array_push($this->signers_roles, ROLES_DEC5_REPRESENTANTE);
					if(strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = ROLES_DEC5_REPRESENTANTE;// el rol del usuario que firma
						$this->datos["user_institution"] = $institucion;
						$num++;
					}
				}
				//Si es Aval
				//echo mb_substr_count($estado, "Aval")."<br/>";
				if ( mb_substr_count($estado, "Aval") >  0 ){
					array_push($this->signers_roles, $persona);
					if( strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $persona;// el rol del usuario que firma
						$this->datos["user_institution"] = $persona;
						$num++;
					}
				}
				//Si es Notario
				//echo mb_substr_count($estado, "Notario")."<br/>";
				if ( mb_substr_count($estado, "Notario") >  0 ){
					array_push($this->signers_roles, ROLES_DEC5_NOTARIO);
					if( strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = ROLES_DEC5_NOTARIO;// el rol del usuario que firma
						$this->datos["user_institution"] = $institucion;
						$num++;
					}
				}
				
				
				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = $firmante;//usuario de la persona que firma

			$usuarioid = $firmante;
			$array = array ( "personaid" => $usuarioid,"idDocumento"=>$datos["idDocumento"]);

			//Consultar el tipo de firma que tiene asociadael usuario
			$this->documentosdetBD->obtenerTipoFirma($array,$dt4);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;

			// Si tipofirma es vacio, se asume pin
			$tipofirma = "Pin"; 
			$orden = 0;
			if($dt4->leerFila())
			{
				$tipofirma = $dt4->obtenerItem("Descripcion");
				$orden = $dt4->obtenerItem("Orden");
				$this->orden = $orden;
			}

			if( $tipofirma =="Pin"){
				$this->datos["user_pin"] = $datos["pin"];//clave del usuario que firma
			}
			else{
				$this->datos["user_pin"] = "";
			}

			if( $this->band == 0 ){ //Si es la primera vez 
				$this->datos["code"] = ""; 			}
			else{
				$this->datos["code"] = $dt2->data[0]["DocCode"]; //codigo del documento base64
			}
		}else{
			$this->mensajeError.= "No se pudo obtener los datos del firmante";
		}
	}

	//Cargar un firmante rbk 
	private function cargarFirmanteSinRol($idDocumento, $RutFirmante){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		
		if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			$num = 0;
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$estado = $dt1->data[$i]["Nombre"];
				$cargo = $dt1->data[$i]["Cargo"];
				$persona = $firmante;
				$firmado = $dt1->data[$i]["Firmado"];
				$firmante = strtoupper($RutFirmante);

				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario'){
					array_push($this->signers_roles, "Representantes");
					if(strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 ){
					array_push($this->signers_roles, $persona);
					if(strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				
				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario'){
					array_push($this->signers_roles, "Notarios");
					if( strtoupper($persona) == $firmante && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Notarios";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				
				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);

			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = $firmante;//usuario de la persona que firma
			
			//Quitar el guión de los ruts 
			$rut_sin_guion = '';
			$this->quitarGuiondelRut($firmante,$rut_sin_guion);
			$this->datos["user_rut_sin_guion"] = $rut_sin_guion;

			$usuarioid = $firmante;
			$array = array ( "personaid" => $usuarioid,"idDocumento"=>$datos["idDocumento"]);

			//Consultar el tipo de firma que tiene asociadael usuario
			$this->documentosdetBD->obtenerTipoFirma($array,$dt4);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;

			// Si tipofirma es vacio, se asume pin
			$tipofirma = "Pin"; 
			$orden = 0;
			if($dt4->leerFila())
			{
				$tipofirma = $dt4->obtenerItem("Descripcion");
				$orden = $dt4->obtenerItem("Orden");
				$this->orden = $orden;
			}

			if( $tipofirma =="Pin"){
				$this->datos["user_pin"] = $datos["pin"];//clave del usuario que firma
			}
			else{
				$this->datos["user_pin"] = "";
			}

			if( $this->band == 0 ){ //Si es la primera vez 
				$this->datos["code"] = ""; 			}
			else{
				$this->datos["code"] = $dt2->data[0]["DocCode"]; //codigo del documento base64
			}
		}else{
			$this->mensajeError.= "No se pudo obtener los datos del firmante";
		}
	}

	//Accion de completar todos los datos para subir el Documento 
	private function cargarDocumentoSinRoles($idDocumento){

		$datos = $_REQUEST;
		$datos["idDocumento"] = $idDocumento;

		//Variables para subida del Documento
	    $dt1 = new DataTable(); //Firmantes 
	    $dt2 = new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		$this->signers_type_sign = array();
		$this->signers_ruts_sin_guion = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){

			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$firmante = strtoupper($dt1->data[$i]["personaid"]);
				
				array_push($this->signers_ruts, $firmante );
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$rut_sin_guion = '';
				$this->quitarGuiondelRut($firmante,$rut_sin_guion);
				array_push($this->signers_ruts_sin_guion,$rut_sin_guion);

				//$tipofirma = $dt1->data[$i]["TipoFirma"];
				$tipofirma = '';

				array_push($this->signers_type_sign, $tipofirma);

				$estado = $dt1->data[$i]["Nombre"];
				$cargo = $dt1->data[$i]["Cargo"];
				
				
				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}

				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			$this->datos["signers_type_sign"] = $this->signers_type_sign;
			//$this->datos["type_doc"] = $dt2->data[0]["NombreTipoDoc"];
			$this->datos["type_doc"] = utf8_encode($dt2->data[0]["NombreTipoDoc"]);
			$this->datos["signers_ruts_sin_guion"] = $this->signers_ruts_sin_guion;
			return;
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
			return;
		}
	}

	//Accion de completar todos los datos para subir el Documento 
	private function cargarDocumentoConRoles_RBK($idDocumento){

		$datos = $_REQUEST;
		$datos["idDocumento"] = $idDocumento;

		//Variables para subida del Documento
	    $dt1 = new DataTable(); //Firmantes 
	    $dt2 = new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		$this->signers_type_sign = array();
		$this->signers_ruts_sin_guion = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){

			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$firmante = strtoupper($dt1->data[$i]["personaid"]);
				
				array_push($this->signers_ruts, $firmante );
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$rut_sin_guion = '';
				$this->quitarGuiondelRut($firmante,$rut_sin_guion);
				array_push($this->signers_ruts_sin_guion,$rut_sin_guion);

				//$tipofirma = $dt1->data[$i]["TipoFirma"];
				$tipofirma ='FPORPIN';
				
				array_push($this->signers_type_sign, $tipofirma);

				$estado = $dt1->data[$i]["Nombre"];
				$id = $dt1->data[$i]["idEstado"];
				$cargo = $dt1->data[$i]["Cargo"];
			
				//Si es Empresa
				if ( $id == 2 && $cargo != 'Notario' )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_REPRESENTANTE);
				}
				
				//Si es Empresa pero Representante 2
				if ( $id == 10 )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_REPRESENTANTE_2);
				}
				
				//Si es Empresa pero Representante 3
				if ( $id == 11 )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_REPRESENTANTE_3);
				}
				
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, '');
				}

				//Si es Notario
				if ( $id == 2 && $cargo == 'Notario' )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_NOTARIO);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			$this->datos["signers_type_sign"] = $this->signers_type_sign;
			//$this->datos["type_doc"] = $dt2->data[0]["NombreTipoDoc"];
			$this->datos["type_doc"] = utf8_encode($dt2->data[0]["NombreTipoDoc"]);
			$this->datos["signers_ruts_sin_guion"] = $this->signers_ruts_sin_guion;
			return;
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
			return;
		}
	}

	//Accion de completar todos los datos para subir el Documento 
	private function cargarDocumentoConRoles($idDocumento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		//Variables para subida del Documento
	    $dt1 = new DataTable(); //Firmantes 
	    $dt2 = new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {
				
				$firmante = strtoupper($dt1->data[$i]["personaid"]);

				//valida datos dec5 y crea roles
				$this->ProcesoRolFirmante($firmante);
				if ($this->mensajeRol != "")
				{
					return;
				}
				
				array_push($this->signers_ruts, $firmante );
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$estado = $dt1->data[$i]["Nombre"];
				
				//Si es Cliente
				if ( mb_substr_count($estado, "Cliente") >  0 )
				{
					array_push($this->signers_roles, "Representantes_2");
					array_push($this->signers_institutions, "RUBRIKA");
				}
				//Si es Empresa
				if ( mb_substr_count($estado, "Empresa") >  0 )
				{
					array_push($this->signers_roles, "Representantes");
					array_push($this->signers_institutions, "RUBRIKA");
				}
				//Si es Notario
				if ( mb_substr_count($estado, "Notario") >  0 )
				{
					array_push($this->signers_roles, "Notarios");
					array_push($this->signers_institutions, "RUBRIKA");
				}

				//Si es Aval
				if ( mb_substr_count($estado, "Aval") >  0 ){
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante );
				}

				//Si es representate
				if ( mb_substr_count($estado, "Representante") >  0 )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
		}
	}

	//Actualiza firma en BD
	private function actualizarFirma($idDocumento, $firmante, $fechafirma, $documento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;
		$datos['RutFirmante'] = $firmante;
		$datos['FechaFirma'] = $fechafirma;
		$datos['documento'] = $documento;

		if( $datos["FechaFirma"] == ''){
			$datos["FechaFirma"] = date("d-m-Y H:i:s");
		}
		//Actualiza el estado de firma 
		$this->documentosdetBD->agregarFirma($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( $this->mensajeError != '' ){
			return false;
		}
		else
		{
			$datos['empleadoid'] = $datos['RutFirmante'];
			$datos['usuarioid'] = $datos['RutFirmante'];
			$datos['accion'] = 'FIRMA_FORMULARIO';
			$this->formularioPlantillaBD->formularioCambioEstado($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			$datos['opcionid'] = 'revisionActor1.php';
			$this->formularioPlantillaBD->getActor($datos, $dt);
			$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
			for ($i = 0; $i < count($dt->data); $i++)
			{
				//$data['empleadoFormularioid'] = $datos['empleadoFormularioid'];
				$data['rut'] = $dt->data[$i]['usuarioid'];
				$data['empleadoid'] = $datos['empleadoid'];
				$data['idDocumento'] = $datos['idDocumento'];
				//$data['accion'] = '';
				//$data['tipoCorreo'] = 4;
				//$this->formularioPlantillaBD->sendEmail($data);
				//var_dump($dt->data[$i]['usuarioid']);
				/*
					$this->definicion5["empleadoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
					$this->definicion5["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
					$this->definicion5["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
					$this->definicion5["accion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
				*/
				$this->formularioPlantillaBD->hayConflicto($data);
				$this->mensajeError.=$this->documentosdetBD->mensajeError;
			}
		}
		//Actualiza el documento firmado
		if( $datos['documento'] != '' ){

			$this->documentosdetBD->modificarDocumento($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			if( $this->mensajeError != '' ){
				return false;
			}
		}
	    return true;
	}

	//Actualiza Documento en la BD
	private function actualizaDocumento($idDocumento, $DocCode){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;
		$datos['DocCode'] = $DocCode;

		if ( $datos['DocCode'] != '' ){
			//Actualizar el codigo del documento
			$this->documentosdetBD->modificar($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			if( $this->mensajeError != '' )
				return false;
		}

		return true;
	}



	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/FirmaMasiva_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}
 
	private function graba_log_validacion ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/ErroresFirma_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	private function ProcesoRolFirmante($firmante)
	{
		
		$datos["firmanteid"] 	= $firmante;
		$this->firmantesBD->obtenerXusuario($datos,$dt);
	
		for ($f = 0; $f < count($dt->data); $f++)
		{
			if ($dt->data[$f]["tienerol"] == 0)
			{
				
				if ($dt->data[$f]["TipoEmpresa"] == 1 ) // Gama 
				{
					$this->CreaRol($firmante,"Representantes");
					$this->CreaRol($firmante,"Representantes_2");
				}
			
				if ($dt->data[$f]["TipoEmpresa"] == 2 ) // Clientes
				{
					$this->CreaRol($firmante,"Representantes_2");
				}

				if ($dt->data[$f]["TipoEmpresa"] == 3 ) // Notario
				{
					$this->CreaRol($firmante,"Notarios");
				}	
				
				if ($this->mensajeRol == "")
				{
					$datos["rutempresa"] = $dt->data[$f]["RutEmpresa"];
					$this->firmantesBD->MarcaRol($datos);
				}
				else
				{
					return;
				}
			 
			}
		}
	}

	private function CreaRol($firmanteid,$rol)
	{  

		$datos["user_rut"] 	= $firmanteid;
		$datos["extra"]		= "roles,institutions,emails";
		$this->dec5 = new dec5();
		$this->dec5->ValidaUsuario($datos,$dt);	
		$this->mensajeRol.=$this->dec5BD->mensajeError;
		if ($dt["status"] != 200)
		{
			$this->mensajeRol.=$this->dec5->mensajeError;
			return;
		}
		
		$existerol	= "N";
		$email 		= "";	
		$email		= $dt["result"][0]["email"];
		
		$cantidad = count($dt["result"][0]["roles"]);
		for ($c = 0; $c < $cantidad + 1; $c++)
		{
			//print ("dec:".$dt["result"][0]["roles"][$c]["role"]." rol par:".$rol."<br>");
			if ($dt["result"][0]["roles"][$c]["role"] == $rol)
			{
				$existerol = "S";
			}
		}
		
		if ($existerol == "N")
		{
			$datos["role"] 	= $rol;
			$datos["email"]	= $email;
			if (!$this->dec5->AgregarRol($datos,$dt))
			{	
				$this->mensajeRol.=$this->dec5->mensajeError;
			}
		}
	
	}

	//Consultar si e documento tiene al menos una firma 
	private function TieneUnaFirma($idDocumento){

		//si ya tiene una firma devuelve true
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes
		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$total = $dt->data[0]["total"];
		if( $this->mensajeError ) return;

		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if( $this->mensajeError ) return;

		//Cantidad de firmantes que no han firmado
		$count = 0;
	    $count = count($dt1->data);	

		//si el total de firmas y la cantidad de firmantes es igual, quiere decir que nadie ha firmado
		if( $count == $total )
		{
			return false;
		}
		return true;
	}
	
	private function consultarDocCode($idDocumento){
		
		$datos["idDocumento"] = $idDocumento;
		$dt = new DataTable();
		
		$this->documentosdetBD->obtener($datos,$dt);
		$this->mensajeError = $this->documentosdetBD->mensajeError;
		
		if($this->mensajeError == '' ){
			if( count($dt->data) > 0){
				
				if( $dt->leerFila() ){
					$doccode = $dt->obtenerItem("DocCode");
				}
				
				if( $doccode != '' ){
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	private function quitarGuiondelRut($rut, &$rut_sin_guion){

		$resultado = explode('-',$rut);
		$rut_sin_guion = $resultado[0].$resultado[1];
		return $rut_sin_guion;
	}

	private function envioGestor($idDocumento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$estado = $dt->ObtenerItem("idEstado");
			}

			if( $estado == 6 ){ //Si esta firmado 
				$this->documentosdetBD->agregarGestor($datos);
				$this->mensajeError .= $this->documentosdetBD->mensajeError;

				if( $this->mensajeError == '' )
					return true;
			}
		}
		return false;
	}
	
	public function ObtenerPerfiles($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "PERSON";	
		$arr["type"] 		= "GET";
		$arr["subtype"] 	= "PROFILE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
		 
		//datos del operador
		$arr7["type"]		= $this->type;
		$arr7["countryCode"]= $this->countryCode;
		$arr7["personalNumber"]=  $datos["personalNumber_operador"];
		
		$arr6["identityDocument"]  	= $arr7;	
		$arr2["operator"]  	= $arr6;	
		//fin datos del operador
		
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["personalNumber"] = $datos["personalNumber"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/profile/get";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;
	}
	
	public function AgregarPerfil($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "PERSON";	
		$arr["type"] 		= "ADD";
		$arr["subtype"] 	= "PROFILE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["password"]  	= $this->password;	
		
		//datos del operador
		$arr7["type"]		= $this->type;
		$arr7["countryCode"]= $this->countryCode;
		$arr7["personalNumber"]=  $datos["personalNumber_operador"];
		
		$arr6["identityDocument"]  	= $arr7;	
		$arr2["operator"]  	= $arr6;	
		//fin datos del operador
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["personalNumber"]= $datos["personalNumber"];
		
		$arr4["profiles"][0]["code"] = $datos["perfil"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/profile/";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
			
		return true;
	}	
	
	
	public function EliminarPerfil($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a convertir
		$arr["code"] 		= "PERSON";	
		$arr["type"] 		= "REMOVE";
		$arr["subtype"] 	= "PROFILE";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
		
		//datos del operador
		$arr7["type"]		= $this->type;
		$arr7["countryCode"]= $this->countryCode;
		$arr7["personalNumber"]=  $datos["personalNumber_operador"];
		
		$arr6["identityDocument"]  	= $arr7;	
		$arr2["operator"]  	= $arr6;	
		//fin datos del operador
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["personalNumber"] = $datos["personalNumber"];
		
		$arr4["profiles"][0]["code"] = $datos["perfil"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/profile/";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "DELETE";
		$this->firmarbk->EnviaSolicitud($urlmetodo, $tipo_solicitud, $this->token_sesion, $this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;


	}

	public function ConsultaDocumento($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "SIGN";	
		$arr["type"] 		= "GET";
		$arr["subtype"] 	= "DOCUMENT";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();


		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		//$arr2["password"]  	= $this->password;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		
		//array con info adicional para los datos a enviar en el json
		$arr6["type"]			  =  "ID";
		$arr6["countryCode"]	  =  $this->countryCode;
		$arr6["personalNumber"]   =  $datos["personalNumber"];
		
		$arr5["identityDocument"] =  $arr6;
			
		$arr4["sessionId"]	         = "";
		$arr4["documents"][0]["id"]	 = $datos["id"];
		$arr4["signers"][0] 		 =  $arr5;
		
		$arr3["digitalSignature"] = $arr4;
			
		$arr["data"] 			  =  $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/document/get/signer";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud,$this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		$resultado = $resultadotrx;
		
		return true;
	}	

	public function ObtenerEnrolado($datos,&$resultadotrx)
	{
		$this->Login($resultado);
		
		if ($this->mensajeError != "")
		{
			$resultadotrx = $resultado;
			return false;
		}
		
		//armado de array que corresponde a la cabezera del json a conevertir
		$arr["code"] 		= "PERSON";	
		$arr["type"] 		= "GET";
		$arr["subtype"] 	= "AUTHFACTOR";
		$arr["createdAt"] 	= $this->crea_createdAt();
		$arr["id"] 			= $this->operacionId_fechahora();

		$arr2["operationId"]= $this->operacionId_fechahora();
		$arr2["companyId"] 	= $this->companyId;
		$arr2["machineId"] 	= $this->machineId;
		$arr2["username"]  	= $this->username;
		$arr2["token"]  	= $this->token;	
			
		$arr["client"] 		= $arr2;
		//fin armado 
		
		//array con info adicional para los datos a enviar en el json
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
		$arr4["identityDocuments"][0]["personalNumber"] = $datos["personalNumber"];
		
		$arr3["digitalIdentity"] = $arr4;
		
		$arr["data"] 		= $arr3;
		//fin array
		
		$parametrosjson = json_encode($arr);
		
		$urlmetodo 			= $this->url."/api/v1/person/authFactors/get";
		
		$this->firmarbk = new firmarbk();
		
		$tipo_solicitud = "POST";
		$this->firmarbk->EnviaSolicitud($urlmetodo,$tipo_solicitud, $this->token_sesion,$this->token, $parametrosjson,$resultadotrx);
		$this->mensajeError.=$this->firmarbk->mensajeError;
		
		if ($this->mensajeError != "")
		{
			return false;
		}
		
		return true;
	}	
	
	private function actualizar($idDocumento){

		$datos['idDocumento'] = $idDocumento;
		$id = '';

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;
		
		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$doccode = $dt->ObtenerItem("DocCode");
			}

			if( $doccode != '' ){
				$codigos = array();
				$codigos = explode(SEPARADOR_DOCCODE,$doccode);
				$datos['numero'] = $codigos[1];
			}else{
				return false;
			}

			//Firmantes en ese orden
			$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt);
			$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
			
			if( $this->mensajeError == '' ){

				if ( $dt->leerFila()){
					$datos['rut']= $dt->ObtenerItem("RutFirmante");
				}
			
				//Rut del firmante
				$datos["rut"] = str_replace (".","",$datos["rut"]);
				$arr_rut = explode("-",$datos["rut"]);
			
				//IdDelDocumento
				$datos["personalNumber"] = $arr_rut[0].$arr_rut[1];
				$datos["id"] 			 = $datos["numero"];

				$this->ConsultaDocumento($datos,$dt);
				$this->mensajeError.=$this->mensajeError;
				
				if( $this->mensajeError == '' ){

					$this->valor_arr = "";
					$this->obtener_dato_arr($dt["data"]["digitalSignature"]["documents"][0]["content"],"data");
					if ($this->valor_arr != "")
					{
						$datos["base64"] = $this->valor_arr;
					}
					
					//Buscar firmantes				
					$firmantes = array();
					$firmantes = $dt["data"]["digitalSignature"]["signers"];
			
					foreach( $firmantes as $key => $value ) 
					{
						$firmante = array(); //Datos de un firmante
						
						foreach( $value as $key_1 => $value_1 ) 
						{
							
							switch( $key_1 ){
								case 'identityDocument':						
									$firmante['RutFirmante'] = $value_1["personalNumber"];
									break;
									
								case 'signState':
									$firmante['Estado'] = $value_1;
									break;
									
								case 'signStateDate':
									if ( $firmante['RutFirmante'] != '' && $firmante['Estado'] == 'FIRMADO' ){
										$firmante['FechaFirma'] = date('d-m-Y H:i:s', strtotime($value_1));
									}else{
										$firmante['FechaFirma'] = '';
									}
									break;
							}
						}
								
						if( $firmante['RutFirmante'] != '' && $firmante['FechaFirma'] != '' && $firmante['Estado'] == 'FIRMADO' ) {
							
							//Colocarle de nuevo el guion 
							$firmante['RutFirmante'] = substr($firmante['RutFirmante'], 0, -1)."-".substr($firmante['RutFirmante'], -1);
							
							if( $this->actualizarFirma($datos['idDocumento'],$firmante["RutFirmante"],$firmante["FechaFirma"],$datos["base64"])){
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}
	
	private function buscarFirmaDocumento($datos)
	{	
		$dt_respuesta = new DataTable();
		
		//Rut del firmante
		$datos["rut"] = str_replace (".","",$datos["rut"]);
		$arr_rut = explode("-",$datos["rut"]);
		
		//IdDelDocumento
		$datos["personalNumber"] = $arr_rut[0].$arr_rut[1];
		$datos["id"] 			 = $datos["numero"];
		
		//Limpiamos el error para Descargar
		$this->mensajeError = '';	
		$this->consultaDocumento($datos,$dt_respuesta); 

		if ($this->mensajeError == "")
		{	
			//Buscar firmantes				
			$firmantes = array();
			$firmantes = $dt_respuesta["data"]["digitalSignature"]["signers"];
		
			foreach( $firmantes as $key => $value ) 
			{
				$firmante = array(); //Datos de un firmante
				
				foreach( $value as $key_1 => $value_1 ) 
				{
		
					switch( $key_1 ){
						case 'identityDocument':						
							$firmante['RutFirmante'] = $value_1["personalNumber"];
							break;
							
						case 'signState':
							$firmante['Estado'] = $value_1;
							break;
							
						case 'signStateDate':
							if ( $firmante['RutFirmante'] != '' && $firmante['Estado'] == 'FIRMADO' ){
								$firmante['FechaFirma'] = date('d-m-Y H:i:s', strtotime($value_1));
							}else{
								$firmante['FechaFirma'] = '';
							}
							break;
					}
										
				}
		
				if( $datos['RutFirmante'] == $firmante['RutFirmante'] && $firmante['Estado'] == 'FIRMADO')
					return true;	
			}						
		}
		return false;
	}
	
	public function errores_firma($mensaje, &$respuesta){
		
		$pos = '';
		$error_carga_perfiles = "Carga_Perfiles";
		$error_carga_personas_por_doc = "CARGA_PERSONA_POR_DOC";
		
		if( strpos($mensaje, $error_carga_perfiles) ){
			$respuesta = "El usuario no posee el perfil para realizar la consulta de los perfiles de firma de un tercero";
		}
		else if( strpos($mensaje, $error_carga_personas_por_doc)){
			$respuesta = "El usuario esta no esta enrolado en sistema";
		}
		else if( $mensaje == "Error api 404 "){
			$respuesta = "El usuario no posee el perfil de firma asignado";
		}
		else{
			$respuesta = $mensaje;
		}

	}

	public function rl_asignacionRolDeFirma($datos)
	{

		//var_dump($datos);
		$rut = explode('-', $datos['RutFirmante']);
		$datos["personalNumber"]=$rut[0].$rut[1];
		$this->ObtenerRoles($datos,$dt);
		$cantidad = count($dt["data"]["digitalIdentity"]["roles"]);
		$esta = false;
		for ($r = 0; $r < $cantidad; $r++) 
		{
			if ($dt["data"]["digitalIdentity"]["roles"][$r]["code"] == ROL_CLIENTES)
			{
				$esta = true;
				break;
			}
		}
		if (!$esta)
		{
			$datos["rol"] =	ROL_CLIENTES;
			$this->mensajeError = '';
			$respuesta = $this->AgregarRol($datos,$dt);
		}
		else{
			$respuesta = true;
		}
		return $respuesta;
	}

	public function rl_firmar_rbk($documento, $usuarioid){
		$datos = $_REQUEST;
		$datos["idDocumento"] = $documento['idDocumento'];
		$datos['RutFirmante'] = $usuarioid;
		$datos['operadorid'] = $documento['operadorid'];
		$datos['firmaTercero'] = $documento['firmaTercero'];

		$respuesta = array();
		
		$idDocumento = $documento['idDocumento'];

		if(! $this->rl_TieneUnaFirma($idDocumento) && ! $this->consultarDocCode($idDocumento) ){

			//Actualizamos
			$this->band = 0; 
				
			//Buscamos los datos necesarios
			//$this->cargarDocumentoSinRoles($idDocumento);
			$this->rl_cargarDocumentoConRoles_RBK($idDocumento);

			if ( $this->datos["file"] != "" ){

				$this->datos['sessionid'] = $documento['sessionid'];

				$this->prepararDatosDocumento($this->datos, $dt);

				if( $this->mensajeError != '' ){
					$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}

				if ( $this->buscarDatosDocumentosRBK($dt) ){
					if ($this->rl_asignacionRolDeFirma($datos))
					{
						if( $this->ejecutarFirma($datos) ){

							//Consultar si documento ya esta firmado

							$this->documentosdetBD->Obtener($datos, $dt);
							$this->mensajeError .= $this->documentosdetBD->mensajeError;

							if( $this->mensajeError == '' ){

								if( $dt->leerFila()){
									$estado_firmado = $dt->obtenerItem("idEstado"); //Estado del Documento
								}

								if( $estado_firmado == 6 ){//El documento esta firmado

									$this->envioGestor($idDocumento);
									if ( $this->mensajeError == '' ) {
										//OK
										$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
										$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
										return $respuesta;
									}else{
										$this->mensajeError = 'No se pudo completar el envio del documento al Gestor';
										$respuesta = $this->construirRespuesta(300, $this->mensajeError, false);
										return $respuesta;
									}
								}else{
									$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
									$respuesta = $this->construirRespuesta(200, $this->mensajeOK, true);
									return $respuesta;
								}
							}else{
								$respuesta = $this->construirRespuesta(300, $this->mensajeError, false);
								return $respuesta;
							}
						}
						else{
							$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
							return $respuesta;
						}
					}
					else
					{
						$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
						return $respuesta;
					}
				}
			}
		}else{
			//Actualizamos
			$this->band = 1; 

			if ($this->rl_asignacionRolDeFirma($datos))
			{
				if( $this->ejecutarFirma($datos) ){
				
					$this->documentosdetBD->Obtener($datos, $dt);
					$this->mensajeError .= $this->documentosdetBD->mensajeError;

					if( $this->mensajeError == '' ){

						if( $dt->leerFila()){
							$estado_firmado = $dt->obtenerItem("idEstado"); //Estado del Documento
						}
						if( $estado_firmado == 6 ){//El documento esta firmado

							$this->envioGestor($datos["idDocumento"]);
							if ( $this->mensajeError == '' ) {
								//OK
								$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
								$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
								return $respuesta;
							}else{
								$this->mensajeError .= 'No se pudo completar el envio del documento al Gestor';
								$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
								return $respuesta;
							}
						}else{
							$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
							$respuesta = $this->construirRespuesta(200, $this->mensajeOK,true);
							return $respuesta;
						}
					}

				}else{
					$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
					return $respuesta;
				}
			}
			else
			{
				$respuesta = $this->construirRespuesta(500, $this->mensajeError, false);
				return $respuesta;
			}
		}
	}

	//Accion de completar todos los datos para subir el Documento 
	private function rl_cargarDocumentoConRoles_RBK($idDocumento){

		$datos = $_REQUEST;
		$datos["idDocumento"] = $idDocumento;

		//Variables para subida del Documento
	    $dt1 = new DataTable(); //Firmantes 
	    $dt2 = new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->rl_obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		$this->signers_type_sign = array();
		$this->signers_ruts_sin_guion = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){

			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$firmante = strtoupper($dt1->data[$i]["personaid"]);
				
				//Asignar orden interno
				$var = ''; //var_dump(is_numeric( $dt1->data[$i]["OrdenMismoEstado"]));
				/*if( is_numeric( $dt1->data[$i]["OrdenMismoEstado"] )) $var = "RowNum";
				else $var = "Orden";*/
				if( is_numeric( $dt1->data[$i]["OrdenMismoEstado"] ) || $dt1->data[$i]["OrdenMismoEstado"] != 'NULL'  ) {
					$var = "RowNum";
					$firmante = '';
				}
				else 
					$var = "Orden";

				array_push($this->signers_ruts, $firmante );
				array_push($this->signers_order, $dt1->data[$i][$var]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$rut_sin_guion = '';
				$this->quitarGuiondelRut($firmante,$rut_sin_guion);
				array_push($this->signers_ruts_sin_guion,$rut_sin_guion);

				//$tipofirma = $dt1->data[$i]["TipoFirma"];
				$tipofirma ='FPORPIN';
				
				array_push($this->signers_type_sign, $tipofirma);

				$estado = $dt1->data[$i]["Nombre"];
				$id = $dt1->data[$i]["idEstado"];
				$cargo = $dt1->data[$i]["Cargo"];
			
				//Si es Empresa
				if ( $id == 2 && $cargo != 'Notario' )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_REPRESENTANTE);
				}
				
				//Si es Empresa pero Representante 2
				if ( $id == 10 )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_REPRESENTANTE_2);
				}
				
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, '');
				}
				
				//Si es Cliente/Accionista
				if ( $id == 11 ){
					array_push($this->signers_roles, ROL_CLIENTES);
				}

				//Si es Proveedor
				if ( $id == 12 ){
					//se cambia a cliente
					//array_push($this->signers_roles, ROL_FIRMA_PROVEEDOR);
					array_push($this->signers_roles, ROL_CLIENTES);
				}
				
	
				//Si es Notario
				if ( $id == 2 && $cargo == 'Notario' )
				{
					//array_push($this->signers_roles, $cargo);
					array_push($this->signers_roles, ROLES_RBK_NOTARIO);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			$this->datos["signers_type_sign"] = $this->signers_type_sign;
			//$this->datos["type_doc"] = $dt2->data[0]["NombreTipoDoc"];
			$this->datos["type_doc"] = utf8_encode($dt2->data[0]["NombreTipoDoc"]);
			$this->datos["signers_ruts_sin_guion"] = $this->signers_ruts_sin_guion;
			return;
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
			return;
		}
	}

	//Consultar si e documento tiene al menos una firma 
	private function rl_TieneUnaFirma($idDocumento){

		//si ya tiene una firma devuelve true
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes
		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$total = $dt->data[0]["total"];
		if( $this->mensajeError ) return;

		//Buscar Firmantes
		$this->documentosdetBD->rl_obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if( $this->mensajeError ) return;

		//Cantidad de firmantes que no han firmado
		$count = 0;
	    $count = count($dt1->data);	

		//si el total de firmas y la cantidad de firmantes es igual, quiere decir que nadie ha firmado
		if( $count == $total )
		{
			return false;
		}
		return true;
	}
}

?>