<?php
error_reporting(E_ERROR);

include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/plantillasBD.php");

//Opcion del AJAX para el Vista Previa
$page = new plantillas();

class plantillas {

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
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}

		// instanciamos del manejo de tablas
    	$this->plantillasBD = new plantillasBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->plantillasBD->usarConexion($conecc);
		
		$dt = new DataTable();
		// pedimos el listado
		$datos = $_POST;

		$this->plantillasBD->obtenerPlantillaPorEmpresas($datos,$dt);
		$this->mensajeError = $this->plantillasBD->mensajeError;

		if( $dt->data[0]['Total'] < 2 ){
			$salida = 0;
		}else{
			$salida = 1;
		}
		
		echo $salida;
		$this->bd->desconectar();
		exit;
	}
}

?>