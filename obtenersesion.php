<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

include_once("dec5.php");

// creamos la instacia de esta clase
$page = new obtenersesion();

class obtenersesion {

	private $bd;
	private $mensajeError="";
	private $dec5;

	// funcion contructora, al instanciar
	function __construct()
	{
		$this->dec5 = new dec5();
		
		$resultado = $this->obtenersesion();
		
		if ($resultado["status"] != 200)
		{
			$respuesta = $resultado["status"]."|".$resultado["message"];
		}
		else
		{
			$respuesta = $resultado["status"]."|".$resultado["session_id"];
		}
		
		print ($respuesta);
	}

	private function obtenersesion()
	{
		$datos 	= $_POST;
		
		$datos["user_rut"] 	= $datos["rut"];
		$datos["user_pin"]	= $datos["pin"];
		$this->dec5->ObtenerSesionUsuario($datos,$dt);
		
		return $dt;
	}



}
?>