<?php
//error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante generar_manuales
//include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/generar_manualesBD.php");

//Opcion del AJAX para el Vista Previa
$page = new generar_manuales();

class generar_manuales {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $generar_manualesBD;
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
    	$this->generar_manualesBD = new generar_manualesBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->generar_manualesBD->usarConexion($conecc);
		
		$dt = new DataTable();
		$dt1 = new DataTable();
			
		//$this->generar_manualesBD->obtenerNotario($_REQUEST,$dt);
		//$this->generar_manualesBD->obtenerAval($_REQUEST,$dt1);
		$this->generar_manualesBD->obtenerFlujo($_REQUEST,$dt);

		//Inicializamos la variable 
		$salida = "";
		$cli = '0';
		$emp = '0';
		$not = '0';
		$ava = '0';

		if( count($dt->data) > 0 ){

			foreach ($dt->data as $key => $value) {
		
				switch ($dt->data[$key]["idEstado"]) {
					case '2': $cli = '1'; break;
					case '3': $emp = '1'; break;
					case '4': $not = '1'; break;
					case '5': $ava = '1'; break;
				}
			}
		}

		$salida = $cli."|".$emp."|".$not."|".$ava;

		echo $salida;

		$this->bd->desconectar();
		exit;
	}
}

?>