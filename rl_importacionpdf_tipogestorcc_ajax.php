<?php

include_once('includes/Seguridad.php');
include_once("includes/rl_tipogestorccBD.php");

//Opcion del AJAX para el Vista Previa
$page = new rl_tipogestorcc();

class rl_tipogestorcc {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $rl_tipogestorccBD;
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
		/*$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}*/

		// instanciamos del manejo de tablas
    	$this->rl_tipogestorccBD = new rl_tipogestorccBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->rl_tipogestorccBD->usarConexion($conecc);
		
		//Consultar las plantillas que se tengan disponibles de ese tipo de contrato y esa empresa
		$datos = $_REQUEST;

		$dt = new DataTable();
		$array = array ();

		$this->rl_tipogestorccBD->obtenerxplantilla($datos,$dt);
		$this->mensajeError = $this->rl_tipogestorccBD->mensajeError;

		if( $this->mensajeError != '')
			echo $this->mensajeError;
		else{
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