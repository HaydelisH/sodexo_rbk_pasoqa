<?php

// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/firmasdocBD.php");

//Opcion del AJAX para el Vista Previa de los Firmantes de un documento
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $firmasdoc;
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
    	$this->firmasdocBD = new firmasdocBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->firmasdocBD->usarConexion($conecc);
		
		$dt = new DataTable();
		
		$datos = $_REQUEST; 
		$datos["pagina"] = 1;
		$datos["decuantos"] = $datos["total_registros"] + 1;

		$this->firmasdocBD->listado($datos,$dt);
		$this->mensajeError.=$this->firmasdocBD->mensajeError;

		if( $this->mensajeError ) {
			echo $this->mensajeError;
			$this->bd->desconectar();
			exit;
		}

		$salida = '';

		if( count($dt->data)){
			foreach ($dt->data as $key => $value) {
				if( $dt->data[$key]["idContrato"] ){
					$salida .= $dt->data[$key]["idContrato"].'|';
				}
			}
		}

		$salida = substr($salida,0,-1);
		echo $salida; 
		$this->bd->desconectar();
		exit;
	}
}

?>