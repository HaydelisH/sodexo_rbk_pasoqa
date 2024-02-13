<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once('includes/Paginas.php');
// y la seguridad
include_once('includes/Seguridad.php');

include_once('includes/firmaBD.php');
// creamos la instacia de esta clase

include_once('includes/firmarbk.php');

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
	private $firmaBD;
	
	private $url;
	private $token;
	private $companyId;
	private $machineId;
	private $username;
	private $password;	
	private $countryCode;	
	private $type;	

	private $token_sesion;

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

		$this->firmaBD = new firmaBD();
			
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->firmaBD->usarConexion($conecc);
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
		$arr["code"] 		= "PERSON";	
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
		$arr4["identityDocuments"][0]["countryCode"]	= $this->countryCode;
		$arr4["identityDocuments"][0]["type"]			= $this->type;
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
		$arr["code"] 		= "PERSON";	
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
		$arr["code"] 		= "PERSON";	
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
		if ($this->leer_parametro("token"))
			$this->token = $this->parametro;
	
		$institucion = "";
		if ($this->leer_parametro("url"))
			$this->url	= $this->parametro;
		
		if ($this->leer_parametro("companyId"))
			$this->companyId	= $this->parametro;
		
		if ($this->leer_parametro("username"))
			$this->username		= $this->parametro;
		
		if ($this->leer_parametro("password"))
			$this->password		= $this->parametro;
		
		if ($this->leer_parametro("countryCode"))
			$this->countryCode	= $this->parametro;
		
		if ($this->leer_parametro("type"))
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
		$this->firmaBD->Obtener($datos,$dt);
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
}

?>