<?php

include_once('includes/Seguridad.php');
include_once("includes/documentosdetBD.php");

//Opcion del AJAX para buscar todos los documentos pendientes por firma 
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosdetBD;
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

		$this->documentosdetBD = new documentosdetBD();
		
		$conecc = $this->bd->obtenerConexion();
		$this->documentosdetBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$fecha = date('dmY_hms');
		
		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt); //print_r($datos);
		$this->mensajeError=$this->documentosdetBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";
		
		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp."_".$fecha.".".$extension;
		
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$ruta = $ruta.$nombrearchivo;
		
		if( $this->mensajeError == ''  )
			echo $ruta;
		else
			echo '';
	
		$this->bd->desconectar();
		exit;
	}
}

?>