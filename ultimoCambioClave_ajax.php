<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

include_once('includes/Seguridad.php');
include_once("includes/cambioclaveBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new ultimoCambioClave();

class ultimoCambioClave {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $cambioclaveBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	public $resultado = array();
	//private $nombrearchivo="";
	//private $fechahoy="";
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
		if (isset($_POST['accion']) ? !($_POST['accion'] == 'WEB') : true )
		{
			if (!$this->seguridad->sesionar()) 
			{
				echo 'Mensaje | Debe Iniciar sesión!';
				exit;
			}
		}
		$this->cambioclaveBD = new cambioclaveBD();

		$conecc = $this->bd->obtenerConexion();
		$this->cambioclaveBD->usarConexion($conecc);

		$dt = new DataTable();
        $array = array();
        $datos = $_POST;
        $datos["usuarioid"]=$this->seguridad->usuarioid;
		//var_dump($datos);
		//$this->cambioclaveBD->deshabiliarCuentasInactivas();
        $this->cambioclaveBD->ultimoCambioClave($datos, $dt);
       //var_dump($dt->data[0]['mensaje']);
        $this->mensajeError = $this->cambioclaveBD->mensajeError;
		if ($this->mensajeError == '')
		{
            $resul = json_decode(utf8_encode($dt->data[0]['mensaje']), true);
            //var_dump($resul);
            //var_dump(json_decode('{"estado":"notificar","mensaje":"Recomendamos cambiar su contraseña, han pasado mas de 30 dias desde el ultimo cambio"}'));
            echo json_encode(array('exito'=>true, 'mensaje'=>$resul));
        }
        else{
            echo json_encode(array('exito'=>false, 'mensaje'=>$this->mensajeError));
        }
		$this->bd->desconectar();
		//exit;
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