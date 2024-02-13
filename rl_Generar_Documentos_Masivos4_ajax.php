<?php

include_once('includes/Seguridad.php');
include_once("includes/rl_proveedoresBD.php");

//Opcion del AJAX para el Vista Previa
$page = new clientes();

class clientes {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $rl_proveedoresBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";
	// funcion contructora, al instanciar
	function __construct()
	{
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{		
			echo 'Mensaje | No hay conexión con la base de datos!';
			exit;
		}
		
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}

		// instanciamos del manejo de tablas
    	$this->rl_proveedoresBD = new rl_proveedoresBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->rl_proveedoresBD->usarConexion($conecc);
		
		//Consultar las clientes que se tengan disponibles de ese tipo de contrato y esa empresa
		$datos = $_REQUEST;

		$dt = new DataTable();

		// aqui se obtienen los datos del Firmante
		$this->rl_proveedoresBD->obtenerFirmantes($datos,$dt); 
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;

		if( $this->mensajeError != '')
			echo $this->mensajeError;
		else{
				
			if( count($dt->data) > 0) {
				
				$array 	= $dt->data;
				$array 	= $this->utf8_converter($array);
				$this->Graba_Log(json_encode($array));
				echo json_encode($array);
				
			}
			else
				echo '1';
		}

		$this->bd->desconectar();
		exit;
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
	
	private function Graba_Log($log)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logajax4_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		   die("Problemas en la creacion");
		if (trim($log) != "")
		{
		fputs($ar,@date("H:i:s")." ".$log);
		}
		else
		{
			fputs($ar," ");
		}
		fputs($ar,"\n");
		fclose($ar);		
	}
}

?>