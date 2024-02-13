<?php
error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
//include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/correoBD.php");



$page = new correos();

class correos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $correoBD;
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
    	$this->correoBD = new correoBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->correoBD->usarConexion($conecc);
		
		
		$dt = new DataTable();
		// pedimos el listado
		//print_r ($_REQUEST);

		$this->correoBD->obtener($_REQUEST,$dt);

		if($dt->leerFila())
		{

			$cantidad= "<h3>Correo</h3>";
			$Descripcion = $dt->obtenerItem("Descripcion");
			$Asunto = $dt->obtenerItem("Asunto");
			$CC = $dt->obtenerItem("CC");
			$CCo = $dt->obtenerItem("CCo");
			$cuerpo = $dt->obtenerItem("Cuerpo");
			$salida = $cantidad." | "
			."<h4>Descripcion: </h4>".$Descripcion."<br/>"
            ."<h4>Asunto: </h4>".$Asunto."<br/>"
			."<h4>CC: </h4>".$CC."<br/>"
			."<h4>CCo: </h4>".$CCo."<br/>"
			."<h4>Contenido: </h4>".$cuerpo."<br/>";
			
		}
		else
		{
			$salida = "Mensaje | No hay informacion";
		}
		
		echo utf8_encode($salida);
		$this->bd->desconectar();
		
		exit;
		
	}


}		
?>