<?php
error_reporting(E_ALL ^ E_NOTICE);

// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar

include_once('firma.php');

$page = new consulta_usuario();

class consulta_usuario {

	
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

		$datos = $_POST;
			
		$this->firma->ObtenerEnrolado($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
		
		/*Array ( 
			[code] => PERSON 
			[type] => GET 
			[subtype] => AUTHFACTOR 
			[createdAt] => 2019-08-13T12:44:56.884 
			[id] => 156571469688400213 
			[data] => Array ( 
				digitalIdentity] => Array ( 
					[personId] => 0 
					[identityDocuments] => Array ( 
						[0] => Array ( 
							[type] => ID 
							[countryCode] => CL 
							[personalNumber] => 261313162 
							) 
						) 
					[checksum] => 08f809f8ff06f4f7010cf4f909f1fc00 
				) 
				[info] => Array ( 
					[hasKBA] => 1 
					[hasFingerprints] => 
					[hasPin] => 1 
					) 
			) 
			[status] => Array ( 
				[status] => Array ( 
					[timestamp] => 2019-08-13T16:44:56.923Z 
					[code] => 200 
					transactionId] => 156571469688400213 
				) 
			) 
		)*/
		
		if ($this->mensajeError == "")
		{
			$respuesta['hasFingerprints']= $dt["data"]["info"]["hasFingerprints"];
			$respuesta['hasPin'] = $dt["data"]["info"]["hasPin"];
	
		}
		
		echo json_encode($respuesta);
				
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