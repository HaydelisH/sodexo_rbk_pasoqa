<?php

include_once('includes/Seguridad.php');
include_once("includes/plantillasBD.php");

//Opcion del AJAX para el Vista Previa
$page = new plantillas();

class plantillas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $plantillasBD;
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
    	$this->plantillasBD = new plantillasBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->plantillasBD->usarConexion($conecc);
		
		$dt = new DataTable();
		// pedimos el listado

		$datos = $_POST;
		$array = array ();

		$this->plantillasBD->obtenerDatosFirmantesPlantilla($datos,$dt);
		$this->mensajeError = $this->plantillasBD->mensajeError;

		if( $this->mensajeError != ''){
			echo $this->mensajeError;
		}else{
			if( count($dt->data) > 0){
			
				//echo json_encode($dt->data);
				$array = $dt->data;
				$array = $this->utf8_converter($array);
				echo json_encode($array);
			}
			else
				echo '';
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