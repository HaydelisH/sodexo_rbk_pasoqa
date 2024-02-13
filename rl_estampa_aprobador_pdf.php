<?php

include_once('includes/Seguridad.php');
include_once("includes/docvigentesBD.php");
include_once("includes/personasBD.php");

require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/tcpdi.php');

$page = new rl_estampa_aprobador_pdf();

class rl_estampa_aprobador_pdf {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $intentos;

	// funcion contructora, al instanciar
	function __construct()
	{
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			exit;
		}

		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar())
		{
			exit;
		}
		
		$this->docvigentesBD = new docvigentesBD();
		$this->PersonasBD 	 = new PersonasBD();

		$conecc = $this->bd->obtenerConexion();
		$this->docvigentesBD->usarConexion($conecc);
		$this->PersonasBD->usarConexion($conecc);
		
		/*$datos = $_POST;
		$this->marca_documento($datos);

		$this->bd->desconectar();*/
	}
	
	public function marca_documento ($datos)
	{
		//$this->graba_log('inicio marca documento');
		
		$dt = new DataTable();
		$dt2 = new DataTable();
		
		$ruta 		= '';
		$archivo 	= '';
		$ruta 		= dirname(__FILE__); 
		$ruta 		= str_replace("\\","/",$ruta);
		$carpetaproceso = 'aprob_'.$this->seguridad->usuarioid;
		$ruta		= $ruta."/tmp/".$carpetaproceso."/";
		$archivo 	= $ruta."marcaaprobador_".$datos["idDocumento"].".pdf";
		$archivom 	= $ruta."marcaaprobadorm_".$datos["idDocumento"].".pdf";	
		$base64		= '';
		
		$carpeta	= './tmp/'.$carpetaproceso;
		$resultado  = $this->crear_carpeta($carpeta);
		if ($resultado == false)
		{	
			$this->graba_log('error al crear carpeta '.$carpeta);
			return '';
		}	
		
		$this->eliminar_archivos($ruta."liq*");
		$this->eliminar_archivos($ruta."marcaaprobador*");
		
		$this->docvigentesBD->Obtener($datos,$dt);
		if(!$dt->leerFila())
		{
			return '';
		}
		
		$documento 	= '';
		$documento 	= $dt->obtenerItem("documento");

		$archivof 	= fopen($archivo, "wb" ); 
		fwrite($archivof, base64_decode($documento)); 
		fclose($archivof); 
		
		if (!file_exists($archivo))
		{
			return '';
		}
	
		$this->PersonasBD->obtener($datos,$dt2);
		if(!$dt2->leerFila())
		{
			return '';
		}
		
		$nombre 	= '';
		$appaterno	= '';
		$apmaterno	= '';
		$paginas 	= 0;

		$nombre 	= $dt2->obtenerItem("nombre");
		$appaterno 	= $dt2->obtenerItem("appaterno");
		$apmaterno 	= $dt2->obtenerItem("apmaterno");
		
		$nombre 	= $nombre.' '.$appaterno.' '.$apmaterno;
		
		$nompdf	= "marcaaprobador_".$datos["idDocumento"].".pdf";
		$this->intentos = 0;
		//$this->graba_log("ruta proceso split ".$ruta." nompdf ".$nompdf);
		$this->InicioProcesoSplit($ruta,$nompdf);
		if (!file_exists($ruta."liq_1.pdf"))
		{
			$this->graba_log('error servicio no genero archivo '.$ruta."liq_1.pdf");
			return '';
		}
	
		$pagecount = count(glob($ruta.'{liq_*.pdf}',GLOB_BRACE));
		//$this->graba_log("cantidad:".$pagecount);
		try
		{
			$new_pdf	= new TCPDI();
			
			for ($i = 1; $i < $pagecount + 1; $i++)
			{
				$archivo = $ruta."liq_".$i.".pdf";
				$paginas = $new_pdf->setSourceFile($archivo);	
				$new_pdf->SetPrintHeader(false);//no muestra linea en encabezado
				$new_pdf->SetPrintFooter(false);//no muestra linea en pie
				
				$tplIdx = $new_pdf->importPage(1);
				$size = $new_pdf->getTemplateSize($tplIdx);
				
				$orientation = ($size['h'] > $size['w']) ? 'P' : 'L';
				if ($orientation == "P") 
				{
					$new_pdf->AddPage($orientation, array($size['w'], $size['h']));
				} 
				else 
				{
					$new_pdf->AddPage($orientation, array($size['h'], $size['w']));
				}
				
				$new_pdf->useTemplate($new_pdf->importPage(1));	
				$new_pdf->SetFont('Courier', '', 8);
				$new_pdf->SetXY(2, 110);
				$new_pdf->Rotate(90);
				$new_pdf->Write(0, 'aprobado por '.$nombre);
			}
			
			$new_pdf->Output($archivom, "F");
			$new_pdf->close();	
		}
		catch (Exception $e) 
		{
			$this->graba_log("exception:".$e);
			
			$this->eliminar_archivos($ruta."liq*");
			$this->elimina_archivo($archivo);
			$this->elimina_archivo($archivom);
			
			return '';
		}		
		
		$archivoaux = file_get_contents($archivom);
		$base64 	= base64_encode($archivoaux);
		
		$this->eliminar_archivos($ruta."liq*");
		$this->elimina_archivo($archivom);
		$this->elimina_archivo($archivo);
		
		return $base64;
	}
	
	private function elimina_archivo($archivo)
	{
		if (file_exists($archivo))
		{
			unlink($archivo); //elimino el documento
		}
	}
	
	private function graba_log($info)
	{
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\apr_'.@date("Ymd").'.TXT';
		$ar=@fopen($nomarchivo,"a");
	  	@fputs($ar,@date("H:i:s",$time)." ".$info);
	   	@fputs($ar,"\n");
  		@fclose($ar);
		
	}	
	
	private function InicioProcesoSplit($ruta,$archivo)
	{
		$this->intentos++;
		$this->EjecutaServicioSplit($ruta,$archivo);
		if (!file_exists($ruta."liq_1.pdf"))
		{
			if ( $this->intentos < 4 )
			{
				$this->InicioProcesoSplit($ruta,$archivo);
			}
		}
	}
	
	private function EjecutaServicioSplit($ruta,$archivo)
	{
		$url 			= SERVICIO_SPLIT;
		//$this->graba_log("url:".$url);
		$ruta 			= str_replace("/","\\",$ruta);
		$archivo_pdf 	= $ruta.$archivo;
		//$this->graba_log("arch pdf:".$archivo_pdf);
		$carpeta_split 	= $ruta;
		//$this->graba_log("carpeta split:".$carpeta_split);
		$xml= "<Root>";
		$xml.="<Documento>".$archivo_pdf."</Documento>";
		$xml.="<SaveTo></SaveTo><ID></ID><MultiPage></MultiPage>";
		$xml.="<TmpFolder>".$carpeta_split."</TmpFolder>";
		$xml.="</Root>";
	
		//$this->graba_log("parametros xml:".$xml);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $xml);
		
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_exec($ch);
		curl_close($ch);
		//$this->graba_log('resultado:'.$result.' parametros:'.$parametros);
	}
	
	private function crear_carpeta($end_directory)
	{
		try
		{
			$end_directory = $end_directory ? $end_directory : './';
			$new_path = preg_replace('/[\/]+/', '/', $end_directory);
			
			if (!is_dir($new_path))
			{
				// crea directorio si no existe
				mkdir($new_path, 0777, true);
			}
		} 
			catch (Exception $e) {
				$this->mensajeError = 'Error, al crear carpeta: '.$e->getMessage();
				return false;
		}
		
		return true;
		
	}
	
	private function eliminar_archivos($ruta)
	{
		$files = glob($ruta); //obtenemos todos los nombres de los ficheros segun ruta
		foreach($files as $file)
		{
			if(is_file($file))
			unlink($file); //elimino el documento
		}
	}
}

?>