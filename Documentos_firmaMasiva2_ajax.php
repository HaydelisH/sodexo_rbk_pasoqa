<?php

include_once('includes/Seguridad.php');
include_once("includes/documentosBD.php");
include_once("includes/documentosdetBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
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
		$this->documentosdetBD = new documentosdetBD();

		$conecc = $this->bd->obtenerConexion();
		$this->documentosdetBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos["personaid"] = $datos["usuarioid"];
		$tipofirma = '';

		//Consultar el tipo de firma que tiene asociada el usuario
		if ( $this->documentosdetBD->obtenerTipoFirma($datos,$dt)){
			$tipofirma = $dt->data[0]['Descripcion'];
			echo $tipofirma;
		}else{
			echo $this->documentosdetBD->mensajeError; 
		}

		$this->bd->desconectar();
		exit;
	}
}

?>