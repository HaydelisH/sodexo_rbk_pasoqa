<?php

class firmarbk {

	public $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	
	private $respuesta;
	
		
	// funcion contructora, al instanciar
	function __construct()
	{

	
	}
	
	public function EnviaSolicitud($url, $tipo_solicitud, $token_sesion, $tokenempresa,$parametrosjson,&$resultado)
	{
		$arr_reult = $this->EnviaProveedorFirma($url, $tipo_solicitud, $token_sesion, $tokenempresa, $parametrosjson);
		
		if (is_array($arr_reult))
		{
		//vamos a recorrer el array para obtener el codigo y mensaje de respuesta
			$this->obtener_respuesta($arr_reult);
		}
		else
		{
			if ($arr_reult != "")
			{
				$this->mensajeError = "Error no definido ".$arr_reult;
				$this->respuesta["code"] 	= "0";
				return false;
			}
			else
			{
				$this->mensajeError = "NO hubo respuesta desde la api";
				$this->respuesta["code"] 	= "0";
				return false;				
			}
		}
	
		if (!isset($this->respuesta["code"]))
		{
			$this->mensajeError = "NO hubo respuesta desde la api";
			$this->respuesta["code"] 	= "0";
			return false;
		}
			
		if ($this->respuesta["code"] != "200")
		{
			$this->mensajeError = "Error api ".$this->respuesta["code"]." ".$this->respuesta["message"];
			$resultado = $arr_reult;
			return false;
		}
	
		$resultado 	= $arr_reult;
		
		return true;		
	}
	

	private function EnviaProveedorFirma($url, $tipo_solicitud, $tokensesion, $tokenempresa, $parametrosjson)
	{
				
		$trace = "";
		$trace.= "".chr(13).chr(10);
		$trace.= "-------------".chr(13).chr(10);
		$trace.= "INFO ENTRADA".chr(13).chr(10);
		$fechahorainicio	= @date("d-m-Y H:i:s");
		$trace.= "hora inicio....:".$fechahorainicio.chr(13).chr(10);
		$trace.= "X-Auth-Token...:".$tokenempresa.chr(13).chr(10);
		$trace.= "X-Session-Token:".$tokensesion.chr(13).chr(10);
		$trace.= "url............:".$url.chr(13).chr(10);
		$trace.= "json entrada...:".$parametrosjson.chr(13).chr(10);
		
		
		//print ($url." ".$xapikey." ".$parametrosjson."<br>");
		$ch = curl_init();
		// definimos la URL a la que hacemos la petición
		curl_setopt($ch, CURLOPT_URL,$url);
		
		// indicamos el tipo de petición: POST
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $tipo_solicitud);      
		// definimos cada uno de los parámetros
		curl_setopt($ch, CURLOPT_POSTFIELDS,$parametrosjson);

		//el header en el post
		$headers[] = 'Content-Type: application/json';
		if ($tokensesion != "")
		{
			$headers[] = 'X-Session-Token: '.$tokensesion;
		}
		if ($tokenempresa != "")
		{
			$headers[] = 'X-Auth-Token: '.$tokenempresa;
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
		
		
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
		
		if (!is_array($array))
		{
			if ($res != "")
			{
				$array = strip_tags($res);
			}
			else
			{
				$array = curl_error($ch);
			}
		}
			
		return $array;
	}
	

	
	private function graba_log ($detalle)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logfirma_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,$detalle);
	   	fputs($ar,"\n");
  		fclose($ar);			
	}	
	
	private function obtener_respuesta($matriz)
	{
				
		foreach($matriz as $key=>$value)
		{
			if (is_array($value))
			{
		
				$this->obtener_respuesta($value);
			}
			else
			{  
						
				if ($key == "code" || $key == "status" )
				{	
					$this->respuesta["code"] = $value;
					if ($value == "200")
					{
						break;
					}
				}
				
				if ($key == "message")
				{		
					$this->respuesta["message"] = $value;
				}
				
				if ($key == "debugMessage")
				{		
					$this->respuesta["message"].= " ".$value;
					break;
				}
			}
		}
				
	} 
}
?>
