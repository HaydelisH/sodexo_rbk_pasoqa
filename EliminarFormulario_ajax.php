<?php

include_once('includes/Seguridad.php');
include_once("generar.php");

//include_once("includes/documentosBD.php");
include_once("includes/parametrosBD.php");
include_once("includes/documentosdetBD.php");
include_once("includes/formularioPlantillaBD.php");

//Opcion del AJAX para el Vista Previa
$page = new EliminarFormulario();

class EliminarFormulario {

	// Para armas la pagina
	//private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosdetBD;
	private $formularioPlantillaBD;
	//private $plantillasBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $contIntentosCurl = 0;

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

		// instanciamos del manejo de tablas
    	//$this->documentosBD = new documentosBD();
    	//$this->parametrosBD = new parametrosBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->formularioPlantillaBD = new formularioPlantillaBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		//$this->documentosBD->usarConexion($conecc);
		//$this->parametrosBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->formularioPlantillaBD->usarConexion($conecc);
		
		$dt = new DataTable();
		// pedimos el listado

        $datos = $_REQUEST;

		$datos = $_REQUEST;
		//$datos['idDocumento'] = $datos['idDocumento_el'];

	 	$dt = new DataTable();

		// se envia a eliminar a la tabla con los datos del formulario
		$this->documentosdetBD->eliminar($datos,$dt);

		if( $this->mensajeError == '' ){
			// AQUI ELIMINAR
            if (isset($_POST['empleadoFormularioid']))
            {
                $this->formularioPlantillaBD->deleteIdDocumento($datos, $dt);
                $this->mensajeError .= $this->formularioPlantillaBD->mensajeError;
			}
            $array = array(
                'estado'=>true,
                'idDocumento'=>$datos['idDocumento']
            );
            $array = $this->utf8_converter($array);
            echo json_encode($array);
			//$this->mensajeOK=" El Documento Nro: ".$datos["idDocumento"]." se ha eliminado con &eacute;xito";
		}else{
            $array = array(
                'estado'=>false,
                'mensaje'=>$this->mensajeError
            );
            echo json_encode($array);
			//$this->mensajeError.=$this->documentosdetBD->mensajeError;
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