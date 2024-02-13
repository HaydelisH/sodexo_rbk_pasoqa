<?php

include_once('includes/Seguridad.php');
include_once("includes/PostulacionBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new postulacion();

class postulacion {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $PostulacionBD;
	// para juntar los mensajes de error
	private $mensajeError="";

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
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}
		$this->PostulacionBD = new PostulacionBD();

		$conecc = $this->bd->obtenerConexion();
		$this->PostulacionBD->usarConexion($conecc);

		$dt = new DataTable();
        $array = array ();
        $datos = $_POST;
        $datos = json_decode($datos['matriz'], true);
		$ahora = date(VAR_FORMATO_FECHA);
        for ($i = 0; $i < count($datos); $i++)
        {
			$datos[$i]['fechaPostulacionMIN'] = date(VAR_FORMATO_FECHA, strtotime($ahora."- " . VAR_X_MESES_TRAS . " month"));
			$datos[$i]['fechaPostulacion'] = $ahora;
            if ( $this->PostulacionBD->existePostulacion($datos[$i],$dt)){
                if( count($dt->data) > 0) 
                {
					$datos[$i]['existe'] = true;
					$datos[$i]['exito'] = true;
                }
                else
                {
                    $datos[$i]['existe'] = false;
                    if ($this->PostulacionBD->agregarPostulacion($datos[$i]))
                    {
                        $datos[$i]['exito'] = true;
                        $datos[$i]['estadoGeneracion'] = 'Postulacion generada con fecha: ' . $datos[$i]['fechaPostulacion'];
                    }
                    else
                    {
						$datos[$i]['exito'] = false;
						$datos[$i]['estadoGeneracion'] = $this->PostulacionBD->mensajeError;
                    }
                }
            }
            else
            {
                $datos[$i]['exito'] = false;
                $datos[$i]['estadoGeneracion'] = 'HOLA'.$this->PostulacionBD->mensajeError;
            }
        }
            
        if ($this->mensajeError == '')
        {
            $array = $this->utf8_converter($datos);
            echo json_encode($datos);
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