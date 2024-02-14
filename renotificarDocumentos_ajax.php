<?php

include_once('includes/Seguridad.php');
include_once("includes/enviocorreosBD.php");

//Opcion del AJAX para el Vista Previa
$page = new empresas();

class empresas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $enviocorreosBD;
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
    	$this->enviocorreosBD = new enviocorreosBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->enviocorreosBD->usarConexion($conecc);
		
		//Consultar las empresas que se tengan disponibles de ese tipo de contrato y esa empresa
		$datos = $_REQUEST;

		$dt = new DataTable();
		$array = array ();

		$this->enviocorreosBD->renotificar($datos);
		$this->mensajeError = $this->enviocorreosBD->mensajeError;

		if( $this->mensajeError != '')
			echo $this->mensajeError;
		else{
			//if( count($dt->data) > 0 ){
            //echo json_encode($dt->data);
            $array = array(
                'estado'=>true
            );
            $array = $this->utf8_converter($array);
            echo json_encode($array);
			/*}
			else
				echo '';*/
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