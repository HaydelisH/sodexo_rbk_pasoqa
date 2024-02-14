<?php

include_once('includes/Seguridad.php');
include_once("includes/firmantescentrocostoBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new centroscosto();
class centroscosto {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $firmantescentrocostoBD;
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
        
        $this->firmantescentrocostoBD = new firmantescentrocostoBD();

        $conecc = $this->bd->obtenerConexion();
        $this->firmantescentrocostoBD->usarConexion($conecc);
        
        $datos = $_REQUEST;
        switch ($_POST["accion"])
		{
			/*case "AGREGAR":
                $dt = new DataTable();
				
				$this->firmantescentrocostoBD->agregar($datos,$dt); 
				$this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
				if( $this->mensajeError == '' )
				{
					$this->mensajeOK = "Se agrego la plantilla con &eacute;xito";
                }
				break;*/
            case "PRINCIPAL":
				$dt = new DataTable();
				//Agregar a fichas
				$this->firmantescentrocostoBD->obtenerUsuarioPrincipalExistente($datos,$dt); 
				$this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
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
				break;
			case "ELIMINAR":
                $dt = new DataTable();
				
				$this->firmantescentrocostoBD->eliminar($datos,$dt); 
				$this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
				if( $this->mensajeError == '' )
				{
					$this->mensajeOK = "Se elimino con &eacute;xito";
                }
				break;
			case "VALIDAR":
                $dt = new DataTable();
                //Agregar a fichas
				$this->firmantescentrocostoBD->validar($datos,$dt); 
                $this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
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
                break;
			case "LISTAR":
                $dt = new DataTable();
                //Agregar a fichas
				$this->firmantescentrocostoBD->listar($datos,$dt); 
                $this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
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
                break;
			case "LISTAR0":
                $dt = new DataTable();
                //Agregar a fichas
				$this->firmantescentrocostoBD->listar2($datos,$dt); 
                $this->mensajeError .= $this->firmantescentrocostoBD->mensajeError;
				
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
                break;
        }
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