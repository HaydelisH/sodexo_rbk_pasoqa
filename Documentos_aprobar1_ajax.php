<?php

include_once('includes/Seguridad.php');
include_once("includes/documentosBD.php");

//Opcion del AJAX para buscar todos los documentos pendientes por firma 
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

		$this->documentosBD	 = new documentosBD();

		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_REQUEST;

		//Separar los idDocumentos
		$docs = explode(',',$datos['docs']);
		$aux = array();
		$aux_2 = array();
		$aux_3 = array();
		$resultado = array();

		foreach ($docs as $key => $value) {

			$aux = array( "idDocumento" => $value );
			
			$this->documentosBD->ObtenerAprobar($aux,$dt);
			
			if( count($dt->data) > 0 ){
				array_push($resultado, $dt->data[0]);
			}
		}

		echo json_encode($resultado);
	
		$this->bd->desconectar();
		exit;
	}
}

?>