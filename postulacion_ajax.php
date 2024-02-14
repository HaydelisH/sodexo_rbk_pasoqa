<?php

include_once('includes/Seguridad.php');
include_once("includes/PostulacionBD.php");
include_once("includes/PersonasBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new postulacion();

class postulacion {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $PostulacionBD;
	private $PersonasBD;
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
		/*$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}*/
		$this->PostulacionBD = new PostulacionBD();
		$this->PersonasBD = new PersonasBD();

		$conecc = $this->bd->obtenerConexion();
		$this->PostulacionBD->usarConexion($conecc);
		$this->PersonasBD->usarConexion($conecc);

		$dt = new DataTable();
		$array = array ();
		$datos = $_REQUEST;
		//Consultar el tipo de firma que tiene asociada el usuario
		if ( $this->PostulacionBD->obtener($datos,$dt)){
			if( count($dt->data) > 0) 
			{
				$array = $dt->data[0];
				$array = $this->utf8_converter($array);
				echo json_encode($array);
			}
			else
			{
 				if ( $this->PersonasBD->obtener($datos,$dt)){
					if( count($dt->data) > 0) 
					{
						$array['nombre'] = $dt->data[0]['nombre'] . ($dt->data[0]['appaterno'] != '' ? ' ' . $dt->data[0]['appaterno'] : '') . ($dt->data[0]['apmaterno'] != '' ? ' ' . $dt->data[0]['apmaterno'] : '');
						$array['email'] = $dt->data[0]['correo'];
						$array['telefono'] = $dt->data[0]['fono'];
						$array = $this->utf8_converter($array);
						echo json_encode($array);
					}
				}
				else
				{
					echo $this->PersonasBD->mensajeError; 
				}
			}
		}
		else
		{
			echo $this->PostulacionBD->mensajeError; 
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