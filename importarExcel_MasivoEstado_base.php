<?php
error_reporting(E_ERROR);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/importarBD.php");
include_once("includes/respuesta_importarBD.php");
include_once("includes/parametrosBD.php");
require_once('generar.php');
require_once('Config.php');  

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$importar = new importar();

class importar 
{
	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $importarBD;
	private $respuesta_importarBD;
	private $parametrosBD;

	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $IdArchivo="";
	private $mensajeOK="";

	private $inputFileType;
	private $objReader;
	private $objPHPExcel;
	private $sheet;

	private $total ="";

	private $vacio = 0;
	private $strSqlIniInsert	="";
	private $strSqlIniUpdate	="";
	private $strSqlIniSelect	="";
	private $strSql		="";

	private $valorCelda;
	private $Llave;
	private $Obligatorio;

	private $html = ""; //Variable que almacenara el texto completo del documento
	private $ruta = "";
	private $contrato_html = ""; //Contrato en HTML
	private $tabla_anexo = ""; //Tabla del Anexo 
	private $anexo_html = ""; //Anexo en HTML
	private $firmantes_tabla = ""; //Tabla de Firmantes en HTML
	private $firmantes_completos; //Arreglo de Firmantes de un Documento
	private $firmantes_empresa;
	private $firmante_empleado;
	private $firmantes_cliente;
	private $firmantes_notaria;
	private $ordinal = array(); //Ordinal de Tabla
	private $proveedores = array();
	private $tipo_con = 0;
	private $band;
	private $orientacion = 'portrait';
	private $empleado;
	private $subclausulas;
	private $rut_empresa;
	private $path = 'tmp/';
	private $nombreNemotecnicoArchivo = 'generarDocumentoMasivo_';
	private $nombreArchivoSubida = '';
	private $nombreExtensionArchivoSubida = '';

	private $contIntentosCurl = 0;

	// funcion contructora, al instanciar
	function __construct()
	{
		$datos = $_REQUEST;
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		$this->pagina = new Paginas();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{ 
			echo 'Mensaje | No hay conexi�n con la base de datos!';
			exit;
		}
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
        if (!$this->seguridad->sesionar()) 
        {
            echo 'Mensaje | Debe Iniciar sesi�n!';
            exit;
        }
		// instanciamos del manejo de tablas
		$this->importarBD = new importarBD();
		$this->respuesta_importarBD = new respuesta_importarBD();
		$this->parametrosBD = new parametrosBD();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		$this->importarBD->usarConexion($conecc);
		$this->respuesta_importarBD->usarConexion($conecc);
		$this->parametrosBD->usarConexion($conecc);
		$dt = new DataTable();
		switch($datos['accion'])
		{
			case 'ESTADO':
			{
				$this->getEstado();
				break;
			}
		}
	}
	
	private function getEstado()
	{
        $datos = $_REQUEST;
		$this->setNombreFichero();
		$dt = new DataTable();
		
		$datos["usuarioingid"]=$this->seguridad->usuarioid;

        $this->respuesta_importarBD->cuenta($datos,$dt);
		$this->mensajeError.=$this->respuesta_importarBD->mensajeError;
		
		echo json_encode(array(
			'actual'=>$dt->data[0]['cuenta']
		));
	}

    private function setNombreFichero()
	{
		$datos = $_REQUEST;
		$datos['usuarioid'] = isset($this->seguridad->usuarioid) ? $this->seguridad->usuarioid : $datos['usuarioid'];
		$this->nombreArchivoSubida = "{$this->nombreNemotecnicoArchivo}{$datos['usuarioid']}";//.{$this->nombreExtensionArchivoSubida}";
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimp'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	private function graba_log_resultado ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimpresultado'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	//Graba log de entradas 
	private function graba_log_html ($mensaje){

		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logHTML'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

}
?>