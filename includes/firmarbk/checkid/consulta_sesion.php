<?php
error_reporting(E_ALL ^ E_NOTICE);

// y la seguridad
include_once('../includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar

include_once('../firma.php');

$page = new consulta_sesion();

class consulta_sesion {

	
	function __construct()
	{
	
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{	
			echo 'Error al relizar la conexión a la base de datos';
			exit;			
		}
		
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Debe Iniciar sesión!';
			exit;
		}

		
		// instanciamos del manejo de tablas
    	$this->firma = new firma();
				
		$dt = new DataTable();
		// pedimos el listado
		
		$datos=$_REQUEST;
		
		$this->firma->Login($dt);
		$this->mensajeError.=$this->firma->mensajeError;
		
		
		$respuesta=$resultadotrx["client"]["companyId"];
		$respuesta.= "|".$resultadotrx["client"]["machineId"];
		$respuesta.= "|".$resultadotrx["client"]["username"];
		$respuesta.= "|".$resultadotrx["client"]["password"];
		$respuesta.= "|".$resultadotrx["client"]["token"];
		
		print_r($respuesta);
				
	}	
	
	private function graba_log($info)
	{
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\paso'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s",$time)." ".$info);
	   	fputs($ar,"\n");
  		fclose($ar);
	}
	
	private function graba_log_error($info)
	{
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\logerror'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s",$time)." ".$info);
	   	fputs($ar,"\n");
  		fclose($ar);
	}
}		
?>