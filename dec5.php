<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);


// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/dec5BD.php");


// creamos la instacia de esta clase
$page = new dec5();


class dec5 {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $dec5BD;
	
	public $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	
	public $resAddDoc="";
	
	private $urldec5;
	private $sesion_id_rut;
	private $sesion_id_name;
	private $parametro;
	private $api_key;
	private $institution;
	private $respuesta;
	
	private $url_token="";
	
	// para asignar el idCategoria a un nuevo registro 
	
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
	
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			
			return;
		}

		// creamos la seguridad
	//	$this->seguridad2 = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
	//	if (!$this->seguridad2->sesionar()) return;

		$this->dec5BD = new dec5BD();
			
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->dec5BD->usarConexion($conecc);
		
			// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "AddDoc":
				$this->adddoc();
				break;
				
		}
	

	}
	
	public function ObtenerUrlToken (&$resultado)
	{	
		$result["url_token"] 	= "";
		$result["institution"] 	= "";
		
		if (!$this->leer_parametro("institution"))
		{
			$resultado	= $result;	
			$this->mensajeError = "Falta informaci&oacute;n del Token (institution)";
			return false;		
		}
		$result["institucion"]	= $this->parametro;
		
		if (!$this->leer_parametro("url_token"))
		{	
			$resultado	= $result;
			$this->mensajeError = "Falta informaci&oacute;n del Token (url_token)";
			$resultado	= $result;	
			return false;		
		}
		$result["url_token"] = $this->parametro;
		
		$resultado	= $result;	
		return true;
	}
	
	private function LoginUsuario ($datos)
	{
		if ($this->leer_parametro("url"))
			$this->urldec5	= $this->parametro;
		
		if ($this->leer_parametro("api_key"))
			$this->api_key	= $this->parametro;
		
		if ($this->leer_parametro("institution"))
			$this->institution	= $this->parametro;
		
		if ($this->leer_parametro("url_token"))
			$this->url_token	= $this->parametro;
		
		$url 					= "api/v1/auth/login";
		$jsonData				= "";
		$jsonData["user_rut"] 	= $datos["user_rut"];
		$jsonData["user_pin"] 	= $datos["user_pin"];
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al realizar el Login del Usuario ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "NO hubo respuesta al realizar el Login del Usuario";
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error en Login del Usuario".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$this->respuesta = $result;
			return false;
		}
		
		if ($result["status"] = 200)
		{
			$this->sesion_id_rut = $result["session_id"];//print("session:".$this->sesion_id);
			return true;
		}		
	}
	
	//login Aplicación DEC
	private function Login()
	{	
		$url 		= "";
		$user_rut 	= "";
		$user_pin 	= "";
		
		if ($this->leer_parametro("url"))
			$this->urldec5	= $this->parametro;
		
		if ($this->leer_parametro("api_key"))
			$this->api_key	= $this->parametro;
		
		$institucion = "";
		if ($this->leer_parametro("institution"))
			$this->institution	= $this->parametro;
		
		if ($this->leer_parametro("user_name"))
			$user_name		= $this->parametro;
		
		if ($this->leer_parametro("user_pin"))
			$user_pin		= $this->parametro;

		
		if ($this->mensajeError != "")
		{
			//print ("error:".$this->mensajeError);
			return false;
		}
		
		$url 		= "api/v1/auth/login";
		
		$jsonData				= "";
		$jsonData["user_name"] 	= $user_name;
		$jsonData["user_pin"] 	= $user_pin;
		//print_r ($jsonData);
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al realizar el Login ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "NO hubo respuesta al realizar el Login";
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error en Login ".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$this->respuesta = $result;
			return false;
		}
		
		if ($result["status"] = 200)
		{
			$this->sesion_id_name = $result["session_id"];//print("session:".$this->sesion_id);
			return true;
		}

	}
	
	public function ValidaUsuario($datos,&$resultado)
	{
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}	

		$url 						= "api/v1/users";
		$jsonData["user_rut"] 		= "";
		$jsonData["user_rut"] 		= $datos["user_rut"];
		$jsonData["extra"] 			= $datos["extra"];
		$jsonData["session_id"] 	= $this->sesion_id_name;
	
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al validar usuario ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "NO hubo respuesta al validar usuario";
			$resultado = $this->respuesta["message"];
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al validar usuario ".$result["status"]." ".$result["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
	
		$resultado 	= $result;
		
		$this->LogOut();
		
		return true;
	}
	
	public function RegistrarUsuario ($datos,&$resultado)
	{	
		$dt = new DataTable();	
		
		if ($this->leer_parametro("url"))
			$this->urldec5	= $this->parametro;
		
		if ($this->leer_parametro("api_key"))
			$this->api_key	= $this->parametro;
		
		$institucion = "";
		if ($this->leer_parametro("institution"))
			$this->institution	= $this->parametro;
			
		$url = "api/v1/auth/register";
		
		$jsonData["user_rut"] 		= $datos["user_rut"];
		$jsonData["user_name"] 		= $datos["user_name"];
		$jsonData["user_lastname"] 	= $datos["user_lastname"];
		$jsonData["user_birthday"] 	= $datos["user_birthday"];
		$jsonData["user_gender"] 	= $datos["user_gender"];
		$jsonData["user_phone"] 	= $datos["user_phone"];
		$jsonData["user_email"] 	= $datos["user_email"];
		$jsonData["serial_number"] 	= $datos["serial_number"];
		$jsonData["institution"] 	= $this->institution;
		
		$datos2["send"] = 0;
		$jsonData["email_template"]	= $datos2;

		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al registrar usuario ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "NO hubo respuesta al registrar usuario ";
			$resultado = $this->respuesta["message"];
			return false;
		}
			
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al registrar usuario ".$result["status"]." ".$result["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
				
		$url = "api/v1/auth//create_pin";
		
		$jsonData["user_rut"] 		= $datos["user_rut"];
		$jsonData["serial_number"] 	= $datos["serial_number"];
		$jsonData["token"] 			= $result["result"]["token"];
		$jsonData["pin"] 			= $datos["pin"];
		$jsonData["send"]			= 0; //no envia correo

		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "Se registro el usuario, pero no hubo respuesta al crear el pin ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "Se registro el usuario, pero no hubo respuesta al crear el pin ";
			$resultado = $this->respuesta["message"];
			return false;
		}
			
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Se registro el usuario, pero hubo problema al crear pin ".$result["status"]." ".$result["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
		
		
		$resultado 	= $result;
		
		return true;
	}
	
	public function ObtenerDocumento ($datos,&$resultado)
	{
		$dt = new DataTable();	
		
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		$url							= "api/v1/documents";
		$jsonData						= "";
		$jsonData["code"] 				= $datos["code"];
		$jsonData["institution"] 		= $this->institution;
		$jsonData["extra"] 				= "signers,file";
		$jsonData["session_id"] 		= $this->sesion_id_name;		
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al obtener el documento ";
			$result["status"] 	= "0";
			$result["message"] 	= "NO hubo respuesta al obtener el documento ";
			$resultado = $result;
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al obtener documento ".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
	
		$resultado = $result;
		
		$this->LogOut();
		
		return true;
	}
	
	public function FirmaPin($datos,&$resultado)
	{	
		$dt = new DataTable();	
		
		//login name
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		//login rut
		if(!$this->LoginUsuario($datos))
		{
			
			$resultado = $this->respuesta;
			return false;
		}
		
		
		if (isset($datos["code"]))
		{
			if ($datos["code"] == "")
			{
				if (!$this->AddDoc($datos,$dt))
				{
					$resultado = $dt;
					return false;
				}
				else
				{
					$datos["code"] = $dt["result"]["code"];	
				}
			}
		}
		
		

		$url							= "api/v1/sign";
		$jsonData						= "";
		$jsonData["user_rut"] 			= $datos["user_rut"];
		$jsonData["user_pin"] 			= $datos["user_pin"];
		$jsonData["user_role"] 			= $datos["user_role"];
		$jsonData["user_institution"] 	= $datos["user_institution"];	
		$jsonData["code"] 				= $datos["code"];		
		$jsonData["type"] 				= "0";		
		$jsonData["session_id"] 		= $this->sesion_id_rut;
		
		$parametrosjson = json_encode($jsonData);
		
		$result2 = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result2["status"]))
		{
			$this->mensajeError					= "NO hubo respuesta al firmar el documento ";
			$this->respuesta["status"] 			= "0";
			$this->respuesta["message"] 		= "NO hubo respuesta al firmar el documento";
			$this->respuesta["result"]["code"]	= $datos["code"];
			$resultado = $this->respuesta;
			return false;
		}
		
		if ($result2["status"] != 200)
		{
			$this->mensajeError = "Error al firmar ".$result2["status"]." ".$result2["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$result2["result"]["code"] 	= $datos["code"];
			$resultado = $result2;
			return false;
		}
				
		$result = $result2;
		$result["result"]["code"] 		= $datos["code"];
		
		$url							= "api/v1/documents";
		$jsonData						= "";
		$jsonData["code"] 				= $datos["code"];
		$jsonData["institution"] 		= $this->institution;
		$jsonData["extra"] 				= "signers,file";
		$jsonData["session_id"] 		= $this->sesion_id_name;	
		
		$parametrosjson = json_encode($jsonData);
		
		$result3 = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (isset($result3["status"]))
		{
			if ($result3["status"] == 200)
			{
				$result = $result3;
			}

		}		
			
		$resultado 	= $result;
		
		$this->LogOut();
		
		$this->LogOut_Rut();
		
		return true;
	}
	
	public function ModificarDatos($datos,&$resultado)
	{	
		$dt = new DataTable();	
		
		//login rut
		if(!$this->LoginUsuario($datos))
		{
			
			$resultado = $this->respuesta;
			return false;
		}
		
		$url							= "api/v1/users/edit";
		
		$jsonData						= "";
		$jsonData["user_rut"] 			= $datos["user_rut"];
		$jsonData["user_name"] 			= $datos["user_name"];
		$jsonData["user_lastname"] 		= $datos["user_lastname"];
		$jsonData["user_email"] 		= $datos["user_email"];
		$jsonData["user_phone"] 		= $datos["user_phone"];
		$jsonData["session_id"] 		= $this->sesion_id_rut;
		
		$parametrosjson = json_encode($jsonData);
		
		$result2 = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result2["status"]))
		{
			$this->mensajeError					= "NO hubo respuesta al modificar datos ";
			$this->respuesta["status"] 			= "0";
			$this->respuesta["message"] 		= "NO hubo respuesta al modificar datos";
			$resultado = $this->respuesta;
			return false;
		}
		
		if ($result2["status"] != 200)
		{
			$this->mensajeError = "Error al modificar datos ".$result2["status"]." ".$result2["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result2;
			return false;
		}
				
					
		$resultado 	= $result2;
		
		$this->LogOut_Rut();
		
		return true;
	}	

	
	public function FirmaHuella($datos,&$resultado)
	{	
		$dt = new DataTable();	
		
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		if (isset($datos["code"]))
		{
			if ($datos["code"] == "")
			{
				if (!$this->AddDoc($datos,$dt))
				{
					$resultado = $dt;
					return false;
				}
				else
				{
					$datos["code"] = $dt["result"]["code"];	
				}
			}
		}
		
		$url							= "api/v1/sign/finger";
		$jsonData						= "";
		$jsonData["user_rut"] 			= $datos["user_rut"];
		$jsonData["user_role"] 			= $datos["user_role"];
		$jsonData["user_institution"] 	= $datos["user_institution"];
		$jsonData["code"] 				= $datos["code"];		
		$jsonData["type"] 				= "0";		
		$jsonData["audit"] 				= $datos["audit"];		
		$jsonData["session_id"] 		= $this->sesion_id_name;
		
		$parametrosjson = json_encode($jsonData);
		
		$result2 = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result2["status"]))
		{
			$this->mensajeError					= "NO hubo respuesta al firmar el documento ";
			$this->respuesta["status"] 			= "0";
			$this->respuesta["message"] 		= "NO hubo respuesta al firmar el documento";
			$this->respuesta["result"]["code"]	= $datos["code"];
			$resultado = $this->respuesta;
			return false;
		}
		
		if ($result2["status"] != 200)
		{
			$this->mensajeError = "Error al firmar ".$result["status"]." ".$result["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$result2["result"]["code"] 	= $datos["code"];
			$resultado = $result2;
			return false;
		}
				
		$result = $result2;
		$result["result"]["code"] 		= $datos["code"];
		
		$url							= "api/v1/documents";
		$jsonData						= "";
		$jsonData["code"] 				= $datos["code"];
		$jsonData["institution"] 		= $this->institution;
		$jsonData["extra"] 				= "signers,file";
		$jsonData["session_id"] 		= $this->sesion_id_name;	
		
		$parametrosjson = json_encode($jsonData);
		
		$result3 = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (isset($result3["status"]))
		{
			if ($result3["status"] == 200)
			{
				$result = $result3;
			}

		}		
			
		$resultado 	= $result;
		
		$this->LogOut();
		
		return true;
	}


	public function FirmaToken($datos,&$resultado)
	{
		if(!$this->Login())
		{
			return false;
		}
	}

	
	public function RecuperarClave($datos,&$resultado)
	{
		
		$dt = new DataTable();	
		
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		$url							= "api/v1/auth/recover_password";
		$jsonData						= "";
		$jsonData["user_rut"] 			= $datos["user_rut"];
		$jsonData["serial_number"] 		= $datos["serial_number"];		
		$jsonData["institucion"] 		= $this->institution;
		$datos2["send"] = 0;
		$jsonData["email_template"]	= $datos2;
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al recuperar clave ";
			$result["status"] 	= "0";
			$result["message"] 	= "NO hubo respuesta al recuperar clave ";
			$resultado = $result;
			return false;
		}		
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al recuperar clave ".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}		

		$this->LogOut();
		
		$resultado = $result;
		
		return true;
	}
	
	private function AddDoc($datos,&$resultado)
	{
		
		$type_code = "";
		if ($this->leer_parametro("type_code"))
			$type_code	= $this->parametro;
		
		$file_mime = "";
		if ($this->leer_parametro("file_mime"))
			$file_mime	= $this->parametro;
			
		$name = "Contrato";
		
			
		$url 	= "api/v1/documents/create";
		
		$jsonData					= "";
		$jsonData["type_code"] 		= $type_code;
		$jsonData["name"] 			= $name;
		$jsonData["institution"] 	= $this->institution;
		$jsonData["signers_roles"] 	= $datos["signers_roles"];
		$jsonData["signers_institutions"] 	= $datos["signers_institutions"];
		$jsonData["signers_emails"] = $datos["signers_emails"];
		$jsonData["signers_ruts"] 	= $datos["signers_ruts"];
		$jsonData["signers_type"] 	= $datos["signers_type"];
		$jsonData["signers_order"]	= $datos["signers_order"];
		$jsonData["signers_notify"]	= $datos["signers_notify"];
		$jsonData["file"]			= $datos["file"];
		$jsonData["file_mime"]		= $file_mime;
		$jsonData["session_id"]	 	= $this->sesion_id_name;
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al crear el documento ";
			$result["status"] 	= "0";
			$result["message"] 	= "NO hubo respuesta al crear el documento ";
			$resultado = $result;
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error en Login ".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
	
		$resultado = $result;
		
		return true;
	}
	
	public function CambiarPin($datos,&$resultado)
	{
		$dt = new DataTable();	
		
		if ($this-> RecuperarClave($datos,$dt))
		{
			$token = $dt["result"]["token"];
		}
		else
		{
			$this->mensajeError = "Error al cambiar pin ".$dt["status"]." ".$dt["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $dt;
			return false;
		}
		
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		$url = "api/v1/auth//create_pin";
		
		$jsonData["user_rut"] 		= $datos["user_rut"];
		$jsonData["serial_number"] 	= $datos["serial_number"];
		$jsonData["token"] 			= $token;
		$jsonData["pin"] 			= $datos["pin"];
		$jsonData["send"]			= 0; //no envia correo

		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al cambiar pin ";
			$this->respuesta["status"] 	= "0";
			$this->respuesta["message"] = "NO hubo respuesta al cambiar pin ";
			$resultado = $this->respuesta["message"];
			return false;
		}
			
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al cambiar pin ".$result["status"]." ".$result["message"]." ";//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
			
		$resultado 	= $result;
		
		return true;
	}
	
	
	private function EnvioDec5($url,$xapikey,$parametrosjson)
	{
		$url 	= $this->urldec5.$url;
		
		$trace = "";
		$trace.= "".chr(13).chr(10);
		$trace.= "-------------".chr(13).chr(10);
		$trace.= "INFO ENTRADA".chr(13).chr(10);
		$fechahorainicio	= @date("d-m-Y H:i:s");
		$trace.= "hora inicio..:".$fechahorainicio.chr(13).chr(10);
		$trace.= "api-key......:".$xapikey.chr(13).chr(10);
		$trace.= "url..........:".$url.chr(13).chr(10);
		$trace.= "json entrada.:".$parametrosjson.chr(13).chr(10);
		
		
		//print ($url." ".$xapikey." ".$parametrosjson."<br>");
		$ch = curl_init();
		// definimos la URL a la que hacemos la petición
		curl_setopt($ch, CURLOPT_URL,$url);
		// indicamos el tipo de petición: POST
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");      
		// definimos cada uno de los parámetros
		curl_setopt($ch, CURLOPT_POSTFIELDS,$parametrosjson);

		//el header en el post
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-API-KEY: '.$xapikey)); 
		
		
		//Establecemos un tiempo máximo de respuesta:
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		// recibimos la respuesta y la guardamos en una variable
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
	
		$res 		= curl_exec($ch);
		
		$fechahorafin	= @date("d-m-Y H:i:s");
		$trace.= "".chr(13).chr(10);
		$trace.= "INFO SALIDA".chr(13).chr(10);
		$trace.= "hora fin....:".$fechahorafin.chr(13).chr(10);	
		
		$datetime1 = new DateTime($fechahorafin);
		$datetime2 = new DateTime($fechahorainicio);
		$diferencia = date_diff($datetime1, $datetime2);
		$trace.= "tiempo resp.:".$diferencia->format("%H:%I:%S").chr(13).chr(10);
		
		if (!curl_errno($ch)) 
		{
			$trace.= "json salida.:".$res.chr(13).chr(10);
		}
		else
		{
			$trace.= "error envio.:".curl_error($ch).chr(13).chr(10);
		}
		
		$trace.= "-------------".chr(13).chr(10);
		$this->graba_log($trace);
		$array = json_decode($res,true);
		//print("-<br>");print_r($array);
		return $array;
	}
	
	private function LogOut()
	{
		//cierra sesion usuario name
		$jsonData	= "";
		$jsonData["session_id"] = $this->sesion_id_name;
		$url 	= "api/v1/auth/logout";
		$parametrosjson = json_encode($jsonData);
		
		$this->EnvioDec5($url,$this->api_key,$parametrosjson);
	}
	
	private function LogOut_Rut()
	{
		//cierra sesion usuario rut
		$jsonData	= "";
		$jsonData["session_id"] = $this->sesion_id_rut;
		$url 	= "api/v1/auth/logout";
		$parametrosjson = json_encode($jsonData);
		
		$this->EnvioDec5($url,$this->api_key,$parametrosjson);
	}
	
	private function leer_parametro($parametro)
	{	
		$dt = new DataTable();
		
		$datos["idparametro"] = $parametro;
		$this->dec5BD->Obtener($datos,$dt);
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
	
	
	public function ObtenerSesionUsuario ($datos,&$resultado)
	{
		if ($this->leer_parametro("url"))
			$this->urldec5	= $this->parametro;
		
		if ($this->leer_parametro("api_key"))
			$this->api_key	= $this->parametro;
		
		if ($this->leer_parametro("institution"))
			$this->institution	= $this->parametro;
		
		if ($this->leer_parametro("url_token"))
			$this->url_token	= $this->parametro;
		
		$url 					= "api/v1/auth/login";
		$jsonData				= "";
		$jsonData["user_rut"] 	= $datos["user_rut"];
		$jsonData["user_pin"] 	= $datos["user_pin"];
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al realizar el Login del Usuario ";
			$resultado["status"] 	= "0";
			$resultado["message"] = "NO hubo respuesta al realizar el Login del Usuario";
			return false;
		}
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error en Login del Usuario".$result["status"]." ".$result["message"];//print("error:".utf8_decode($this->mensajeError));
			$resultado = $result;
			return false;
		}
		
		if ($result["status"] == 200)
		{
			$resultado = $result;
			return true;
		}		
	}
	
	public function AgregarRol ($datos,&$resultado)
	{
		$dt = new DataTable();	
		
		if(!$this->Login())
		{
			$resultado = $this->respuesta;
			return false;
		}
		
		$url						= "api/v1/users/add_role";
		$jsonData					= "";
		$jsonData["user_rut"] 		= $datos["user_rut"];
		$jsonData["role"] 			= $datos["role"];		
		$jsonData["institution"]	= $this->institution;
		$jsonData["email"]			= $datos["email"];
		$jsonData["session_id"]		= $this->sesion_id_name;
		
		$parametrosjson = json_encode($jsonData);
		
		$result = $this->EnvioDec5($url,$this->api_key,$parametrosjson);
		
		if (!isset($result["status"]))
		{
			$this->mensajeError = "NO hubo respuesta al agregar rol ";
			$result["status"] 	= "0";
			$result["message"] 	= "NO hubo respuesta al agregar rol ";
			$resultado = $result;
			return false;
		}		
		//print_r($result);
		
		if ($result["status"] != 200)
		{
			$this->mensajeError = "Error al agregar rol ".$result["status"]." ".$result["message"]." ";
			if (isset($result["result"]["errors"]["email"]))
			{
				$this->mensajeError .= " [".$result["result"]["errors"]["email"]."]"; 
			}
			$resultado = $result;
			return false;
		}		

		$this->LogOut();
		
		$resultado = $result;
		
		return true;
	}
	
	private function graba_log ($detalle)
	{	
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logdec'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,$detalle);
	   	fputs($ar,"\n");
  		fclose($ar);			
	}	
}
?>
