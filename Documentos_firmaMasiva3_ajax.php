<?php

error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

include_once('includes/Seguridad.php');

//Firma DEC5
include_once('dec5.php');
include_once('firma.php');

//Opcion del AJAX para firma con PIN
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	//Mensaje de error si existe 
	private $mensajeError="";

	//Datos para la firma
	private $signers_roles;
	private $signers_ruts;
    private $signers_order;	

    private $datos;
	private $signers_institutions;
	private $signers_emails;
	private $signers_type;
	private $signers_notify;	
	private $nombre_archivo;
	private $archivo_codificado;

	private $dec5;
	private $band = 0;
	private $firma;
	
	private $mensajeRol="";
	private $orden = 0;

	//funcion contructora, al instanciar
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
		$firma = new firma();

		$conecc = $this->bd->obtenerConexion();
	
		$dt = new DataTable();
		$datos = $_POST;
		$usuarioid = $datos['usuarioid'];

		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if( isset($datos['pin']) ){
					$respuesta = $firma->firmar_pin($datos['idDocumento'],$usuarioid);
				}
				break;
			
			default:
				$respuesta = $firma->firmar_rbk($datos,$usuarioid);
				break;
		}

		if( $respuesta['codigo'] == 200 ) $this->mensajeOK = $respuesta['mensaje'];
		else $this->mensajeError .= $respuesta['mensaje'];

		if( $this->mensajeError ){
			$this->graba_log('idDocumento : '.$datos["idDocumento"].'-Usuario: '.$datos['usuarioid'].'-Tipo de firma: PIN - Error : '.$this->mensajeError);
			echo $this->mensajeError;
		}

		if( $this->mensajeOK ){
			$this->graba_log('idDocumento : '.$datos["idDocumento"].'-Usuario: '.$datos['usuarioid'].'-Tipo de firma: PIN - Ok : '.$this->mensajeOK);
			echo '0';
		}

		$this->bd->desconectar();
		exit;
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/FirmaMasiva_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	
}

?>