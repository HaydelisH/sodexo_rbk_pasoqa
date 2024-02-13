<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
//include_once("includes/importarBD.php");
include_once("includes/respuesta_importarBD.php");
//include_once("includes/parametrosBD.php");

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

// creamos la instacia de esta clase
$page = new Postulacion();
class Postulacion {
    // Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	//private $respuesta_importarBD;
	//private $parametrosBD;
	//private $postulacionBD;

    // para juntar los mensajes de error
	private $mensajeError="";

	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	/*private $nroopcion=5; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
    private $ver=0;*/

    private $path = 'tmp/';
	private $nombreNemotecnicoArchivo = 'blackList_';
    
	private $contIntentosCurl = 0;

	function __construct()
	{
		$datos = $_POST;
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
		//$this->importarBD = new importarBD();
		$this->respuesta_importarBD = new respuesta_importarBD();
		//$this->parametrosBD = new parametrosBD();
		//$this->postulacionBD = new postulacionBD();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		//$this->importarBD->usarConexion($conecc);
		$this->respuesta_importarBD->usarConexion($conecc);
		//$this->parametrosBD->usarConexion($conecc);
		//$this->postulacionBD->usarConexion($conecc);
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
        $datos = $_POST;
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
		$datos = $_POST;
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
}
?>