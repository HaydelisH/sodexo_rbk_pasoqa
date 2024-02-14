<?php

include_once('includes/Seguridad.php');
include_once("includes/ChecklistDocumentosBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new checkListDocumentos();
class checkListDocumentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $ChecklistDocumentosBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $codigoError=0;

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
        
        $this->ChecklistDocumentosBD = new ChecklistDocumentosBD();

        $conecc = $this->bd->obtenerConexion();
        $this->ChecklistDocumentosBD->usarConexion($conecc);
        
        $datos = $_REQUEST;
        switch ($_POST["accion"])
		{
            case "AGREGAR":
                $dt = new DataTable();
                
                $this->ChecklistDocumentosBD->agregar($datos); 
                $this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
                $this->codigoError = $this->ChecklistDocumentosBD->codigoError;
                
                $array = array('mensaje'=>$this->mensajeError, 'codigo'=>$this->codigoError);
                $array = $this->utf8_converter($array);
                echo json_encode($array);
            break;
            case "ELIMINAR":
                $dt = new DataTable();
				
				$this->ChecklistDocumentosBD->eliminar($datos,$dt); 
				$this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
				
				if( $this->mensajeError == '' )
				{
					$this->mensajeOK = "Se elimino con &eacute;xito";
                }
				break;
			case "checkListDocumentos_listar":
                $dt = new DataTable();
                //Agregar a fichas
                $this->ChecklistDocumentosBD->checkListDocumentos_listar($datos,$dt); 
                $this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
                				
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
			case "tipoMovimiento_listar":
                $dt = new DataTable();
                //Agregar a fichas
                $this->ChecklistDocumentosBD->tipoMovimiento_listar($datos,$dt); 
                $this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
                				
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
			case "tipoGestor_listar":
                $dt = new DataTable();
                //Agregar a fichas
                $this->ChecklistDocumentosBD->tipoGestor_listar($datos,$dt); 
                $this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
                				
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