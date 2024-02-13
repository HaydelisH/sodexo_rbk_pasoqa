<?php
error_reporting(E_ERROR);

include_once('includes/Seguridad.php');
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
		$datos = $_POST;
		$datos["personaid"] = $datos["usuarioid"];
		$salida = '';

		//Consultar el tipo de firma que tiene asociadael usuario
		$this->documentosdetBD->obtenerTipoFirma($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( $this->mensajeError != '' ){
			$salida = 'Ha ocurrido un error inesperado, intente nuevamente';
		}

		if( $dt->leerFila() )
		{
			$tipofirma = $dt->obtenerItem("Descripcion");
		}

		if ( trim($tipofirma) == "" )
		{
			$salida = 'Ud. ya realiz&oacute; su firma o no es firmante de este Documento';
		}
		else{
			$tf = '';
			switch( $tipofirma ){
				case 'Pin' : 
					$tf = strtoupper($tipofirma);
					break;
				case 'Huella' : 
					$tf = 'FINGERPRINT';
					break;
				case 'Pin o Huella' : 
					$tf = '';
					break;
				//Agregar los tipos de firma necesarios 
			}
			$salida = $tf;
		}

		echo $salida;

		$this->bd->desconectar();
		exit;
	}
}

?>