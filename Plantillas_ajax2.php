<?php 

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
		/*$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje : Debe Iniciar sesión!';
			exit;
		}*/

		// instanciamos del manejo de tablas
    	$this->plantillasBD = new plantillasBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->plantillasBD->usarConexion($conecc);
		
		$dt = new DataTable();
		// pedimos el listado

		if ( $this->plantillasBD->agregarClausula($_POST) ) {
			echo "0";
		}
		else{
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			echo '0';
		}	
		$this->bd->desconectar();
		exit;
	}
}

?>