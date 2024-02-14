<?php

include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");

//Opcion del AJAX para buscar todos los documentos pendientes por firma 
$page = new cargaMenu();

class cargaMenu {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
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

		$this->opcionesxtipousuarioBD	 = new opcionesxtipousuarioBD();

		$conecc = $this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
	
		$dt = new DataTable();
		$resultado = array();
		$datos = $_REQUEST;
		
		//para mostrar opciones en el menu
        $datos["tipousuarioid"]=$this->seguridad->tipousuarioid;
        $this->opcionesxtipousuarioBD->Listado($datos,$dt);
        $this->mensajeError = $this->opcionesxtipousuarioBD->mensajeError;
		
		if( $this->mensajeError != '')
			echo $this->mensajeError;
		else{
			if( count($dt->data) > 0){
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