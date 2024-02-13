<?php

include_once('includes/Seguridad.php');
include_once("includes/documentosBD.php");
include_once("includes/contratofirmantesBD.php");

//Opcion del AJAX para el Vista Previa
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosBD;
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
		$this->contratofirmantesBD = new contratofirmantesBD();

		$conecc = $this->bd->obtenerConexion();
		$this->contratofirmantesBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_POST;
		
		$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
		
		$salida= '';

		foreach ($dt->data as $key => $value) {
			foreach ($dt->data[$key] as $key1 => $value1) {
				if ( is_int($key1) )$salida.=$value1."|";
			}
		}
		echo utf8_encode($salida);

		$this->bd->desconectar();
		exit;
	}
}

?>