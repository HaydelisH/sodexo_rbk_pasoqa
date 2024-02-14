<?php

include_once('includes/Seguridad.php');
include_once("includes/postulacionBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new linkPostulacion_ajax2();

class linkPostulacion_ajax2 {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $postulacionBD;
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
		$this->postulacionBD = new postulacionBD();

		$conecc = $this->bd->obtenerConexion();
		$this->postulacionBD->usarConexion($conecc);

		$dt = new DataTable();
		$array = array ();
		$datos = $_REQUEST;
		$datos['link'] = base64_decode(urldecode($datos['link']));
        $resultado = array();
        if ( $this->postulacionBD->actualizarLink($datos) )
        {
            $resultado['exito'] = true;
            $resultado['mensaje'] = 'Se actualizo el link con exito';
        }
        else
        {
            $resultado['exito'] = false;
            $resultado['mensaje'] = $this->postulacionBD->mensajeError;
		}
$resultado['link'] = $datos['link'];
        $array = $this->utf8_converter($resultado);
        echo json_encode($array);

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