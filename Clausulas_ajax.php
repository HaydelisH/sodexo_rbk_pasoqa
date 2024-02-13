<?php


error_reporting(E_ERROR);
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/clausulasBD.php");



$page = new clausulas();

class clausulas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $clausulasBD;
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
    	$this->clausulasBD = new clausulasBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->clausulasBD->usarConexion($conecc);
		
		
		$dt = new DataTable();
		// pedimos el listado
		//print_r ($_REQUEST);

		$this->clausulasBD->obtener($_REQUEST,$dt);

		if($dt->leerFila())
		{
			$cantidad 	= "<h3>".$dt->obtenerItem("Titulo_Cl")."</h3>";
			$texto = $dt->obtenerItem("Texto");
			$salida = $cantidad." | ".$texto;
		}
		else
		{
			$salida = "Mensaje | No hay informacion";
		}
		
		echo $salida;
		$this->bd->desconectar();
		
		exit;
		
	}


}		
?>