<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// creamos la instacia de esta clase
$page = new prueba();

class prueba {

	
	private $mensajeError="";
	private $orientacion = 'P';

	// funcion contructora, al instanciar
	function __construct()
	{
		$this->procesar();
	}

	private function procesar()
	{
		//print ("<br>INICIO<br>");
		$html = "";
		$fp = fopen("tmp/HTML-SMU.txt", "r");
		while(!feof($fp)) 
		{
			$html.= fgets($fp);
		}

		fclose($fp);
		
		//print ($html);
		
		try 
		{ 
			$this->graba_log("inicio");
			# Instanciamos un objeto de la clase DOMPDF.
			$mipdf = new Dompdf();
			 
			# Definimos el tamaño y orientación del papel que queremos.
			# O por defecto cogerá el que está en el fichero de configuración.
			$mipdf ->set_paper("A4", "portrait");
			
			$this->graba_log("antes de load");
			# Cargamos el contenido HTML.
			$mipdf ->load_html(utf8_decode($html));
			$this->graba_log("despues de load");
			# Renderizamos el documento PDF.
			$mipdf ->render();
			$this->graba_log("despues de render");
			# Enviamos el fichero PDF al navegador.
			$mipdf ->stream('FicheroEjemplo.pdf');

		} catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
		
		$this->graba_log("fin");
	}


	private function graba_log ($mensaje){
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logdompdf_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s")." ".$mensaje);
	   	fputs($ar,"\n");
  		fclose($ar);			
	}	


}
?>