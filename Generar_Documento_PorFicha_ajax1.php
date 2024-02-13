<?php

include_once('includes/Seguridad.php');
include_once("includes/lugarespagoBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new personas();

class personas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $lugarespagoBD;
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
		$this->lugarespagoBD = new lugarespagoBD();

		$conecc = $this->bd->obtenerConexion();
		$this->lugarespagoBD->usarConexion($conecc);

		$dt = new DataTable();
		$array = array ();
		$datos = $_POST;
		$datos['empresaid'] = $datos['RutEmpresa'];

		//Consultar el tipo de firma que tiene asociada el usuario
		$datos['RL_LUGARPAGO_DEFECTO'] = RL_LUGARPAGO_DEFECTO;
		$this->lugarespagoBD->listado($datos,$dt);
		$this->mensajeError = $this->lugarespagoBD->mensajeError;

		if( $this->mensajeError == '' ){
		
			//echo json_encode($dt->data);
			$array = $dt->data;
			$array = $this->utf8_converter($array);
			echo json_encode($array);
			
		}else{
			echo $this->mensajeError; 
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
}

?>