<?php

include_once('includes/Seguridad.php');
include_once("includes/plantillasBD.php");

//Opcion del AJAX para el Vista Previa
$page = new empresas();

class empresas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $plantillasBD;
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
    	$this->plantillasBD = new plantillasBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->plantillasBD->usarConexion($conecc);
		
		//Consultar las empresas que se tengan disponibles de ese tipo de contrato y esa empresa
		$datos = $_REQUEST;
		$datos['PREFIJO_VAR'] = PREFIJO_VAR;
		$datos['SUFIJO_VAR'] = SUFIJO_VAR;
		$datos['SEPARADOR'] = SEPARADOR;
		$datos['VAR_FECHA_LARGA'] = VAR_FECHA_LARGA;
		$datos['VAR_FECHA_CORTA'] = VAR_FECHA_CORTA;
		$datos['VAR_FECHA_LARGA_ST'] = VAR_FECHA_LARGA_ST;
		$datos['VAR_FECHA_CORTA_ST'] = VAR_FECHA_CORTA_ST;
		$datos['VAR_FECHA_INDEFINIDA'] = VAR_FECHA_INDEFINIDA;
		$datos['VAR_NUM_A_LETRAS_MAYUS'] = VAR_NUM_A_LETRAS_MAYUS;
		$datos['VAR_NUM_A_LETRAS_MINUS'] = VAR_NUM_A_LETRAS_MINUS;
		$datos['VAR_NUM_A_LETRAS_MIXTO'] = VAR_NUM_A_LETRAS_MIXTO;
		$datos['VAR_NUM_SEPARADOR_MILES'] = VAR_NUM_SEPARADOR_MILES;
		$datos['VAR_VACIA'] = VAR_VACIA;
		$datos['SOLO'] = 'DATOS';

		$dt = new DataTable();
		$array = array ();

		$this->plantillasBD->getVariablesPlantilla($datos,$dt);
		$this->mensajeError = $this->plantillasBD->mensajeError;

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