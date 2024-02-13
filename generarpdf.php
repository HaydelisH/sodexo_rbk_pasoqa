<?php
 	//====================================================//
	// Nombre de archivo: GenerarPDF.php 			      //
	// Fecha de creación: 12-09-2019					  //
	// Descripcion: Generación de PDF con servicio        //
	// Proyecto de Rubrika 3.0							  //
	//====================================================//
	
	//Reporte de errores
	error_reporting(E_ERROR);
	ini_set("display_errors", 1);
	
	//Clases necesarias 
	require_once('Config.php');  
	include_once("includes/parametrosBD.php");
	    
	$page = new generarpdf();
	
	class generarpdf
	{
		
		// Para armas la pagina
		private $pagina;
		// para la conexion a la base de datos
		private $bd;
		//Parametros de la tabla 
		private $parametrosBD;
		
		// funcion contructora, al instanciar
		function __construct()
		{
			$this->bd = new ObjetoBD();
			$this->pagina = new Paginas();
			$this->parametrosBD = new parametrosBD();
					
			// nos conectamos a la base de datos
			if (!$this->bd->conectar())
			{ 
				echo 'Mensaje | No hay conexi?n con la base de datos!';
				exit;
			}
			
			$conecc = $this->bd->obtenerConexion();
			$this->parametrosBD->usarConexion($conecc);
		}
		
		//Generar Documento
		public function generar($idDocumento){
			
			$dt = new DataTable();
			
			//Busca parametro de url 
			$this->parametrosBD->obtener(array('idparametro'=>'url_generar_pdf'),$dt);
			$this->mensajeError.=$this->parametrosBD->mensajeError;
	
			//Asignacion de datos necesarios
			$url = $dt->data[0]['parametro']; 
	
			$archivo_html = RUTA_GENERACION_ARCHIVO.NOMBRE_DOC.'_'.$idDocumento.".html";		
			$archivo_pdf = RUTA_GENERACION_ARCHIVO.NOMBRE_DOC.'_'.$idDocumento.".pdf";
			$xml= "<Root>";
			$xml.="<Documento>".$archivo_html."</Documento>";
			$xml.="<SaveTo></SaveTo><ID></ID><MultiPage></MultiPage>";
			$xml.="<TmpFolder>".$archivo_pdf."</TmpFolder>";
			$xml.="</Root>";
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			
			if (substr($url, 0, 8) == 'https://'){
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_SSLVERSION, SSLVERSION);
			}
			
			curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
			curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT_GENERACION);
			$res = curl_exec($ch);
	
			if (curl_errno($ch)) 
			{
				$trace.= "error envio.:".curl_error($ch);
				$this->graba_log($trace);
				return false;
			}
				
			curl_close($ch);
			return true;
		}
		
		//Generar Plantilla en PDF
		public function generarPlantilla($nombre_plantilla){
	
			$dt = new DataTable();
			
			//Busca parametro de url 
			$this->parametrosBD->obtener(array('idparametro'=>'url_generar_pdf'),$dt);
			$this->mensajeError.=$this->parametrosBD->mensajeError;
	
			//Asignacion de datos necesarios
			$url = $dt->data[0]['parametro']; 
	
			$archivo_html = RUTA_GENERACION_ARCHIVO.NOMBRE_PLA.$nombre_plantilla.".html";		
			$archivo_pdf = RUTA_GENERACION_ARCHIVO.NOMBRE_PLA.$nombre_plantilla.".pdf";
			$xml= "<Root>";
			$xml.="<Documento>".$archivo_html."</Documento>";
			$xml.="<SaveTo></SaveTo><ID></ID><MultiPage></MultiPage>";
			$xml.="<TmpFolder>".$archivo_pdf."</TmpFolder>";
			$xml.="</Root>";
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			
			if (substr($url, 0, 8) == 'https://'){
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_SSLVERSION, SSLVERSION);
			}
			
			curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
			curl_setopt($ch, CURLOPT_TIMEOUT, CURL_TIMEOUT_GENERACION);
	
			$res = curl_exec($ch);

			if (curl_errno($ch)) 
			{
				$trace.= "error envio.:".curl_error($ch);
				$this->graba_log($trace);
				return false;
			}
				
			curl_close($ch);
		
			return true;
		}
		//Generar Plantilla en PDF
		public function generarPlantillaPorConsola($nombre_plantilla){
		
			$dt = new DataTable();
			
			//Busca parametro de url 
			$this->parametrosBD->obtener(array('idparametro'=>'url_generar_pdf'),$dt);
			$this->mensajeError.=$this->parametrosBD->mensajeError;

			//Asignacion de datos necesarios
			$url = $dt->data[0]['parametro']; 
		
			//$path = dirname(__FILE__)."/tmp/"; 
			$path = RUTA_GENERACION_ARCHIVO;

			$archivo_html = $path.NOMBRE_PLA.$nombre_plantilla.".html";
			$archivo_pdf = $path.NOMBRE_PLA.$nombre_plantilla.".pdf";
			$rutatmp = $path;
			
			//Direccionamiento y puerto
			$direccionamiento = SOCKET.PUERTO;

			$socket = stream_socket_client($direccionamiento);
			$message = "<Root><Documento>".$archivo_html."</Documento><SaveTo>".$archivo_pdf."</SaveTo><ID/><MultiPage>false</MultiPage><TmpFolder>".$rutatmp."</TmpFolder>";			
			$message .= "<top>".TOP."</top><left>".LEFT."</left><right>".RIGHT."</right><button>".BUTTON."</button>";
			$message .= "</Root>";
			
			if ($socket) {
				$sent = stream_socket_sendto($socket, $message);
				if ($sent > 0) {
					$server_response = fread($socket, 4096);
					$this->graba_log(" Respuesta : ".$server_response." ".@date("H:i:s"));
					stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
					return true;
				}
			} else {
				$trace.= 'Unable to connect to server';
				$this->graba_log($trace);
				stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
				return false;
			}
		}
		
		//Generar Documento en PDF
		public function generarDocumentoPorConsola($idDocumento){

			$dt = new DataTable();
			
			//$path = dirname(__FILE__)."/tmp/"; 
			$path = RUTA_GENERACION_ARCHIVO;

			$archivo_html = $path.NOMBRE_DOC.'_'.$idDocumento.".html";	
			$archivo_pdf = $path.NOMBRE_DOC.'_'.$idDocumento.".pdf";
			$rutatmp = $path;
				
			//Direccionamiento y puerto
			$direccionamiento = SOCKET.PUERTO;

			$socket = stream_socket_client($direccionamiento);
			$message = "<Root><Documento>".$archivo_html."</Documento><SaveTo>".$archivo_pdf."</SaveTo><ID/><MultiPage>false</MultiPage><TmpFolder>".$rutatmp."</TmpFolder>";			
			$message .= "<top>".TOP."</top><left>".LEFT."</left><right>".RIGHT."</right><button>".BUTTON."</button>";
			$message .= "</Root>";
			
			if ($socket) {
				$sent = stream_socket_sendto($socket, $message);
				if ($sent > 0) {
					$server_response = fread($socket, 4096);
					$this->graba_log(" Respuesta : ".$server_response." ".@date("H:i:s"));
					return true;
				}
			} else {
				$trace.= 'Unable to connect to server';
				$this->graba_log($trace);
				return false;
			}
		}
			
		private function numeroPaginasPdf($archivoPDF)
		{
			$stream = fopen($archivoPDF, "r");
			$content = fread ($stream, filesize($archivoPDF));
		 
			if(!$stream || !$content)
				return 0;
		 
			$count = 0;
		 
			$regex  = "/\/Count\s+(\d+)/";
			$regex2 = "/\/Page\W*(\d+)/";
			$regex3 = "/\/N\s+(\d+)/";
		 
			if(preg_match_all($regex, $content, $matches))
				$count = max($matches);
		 
			return $count[0];
		}
		 			    		
		private function graba_log ($mensaje)
		{
			date_default_timezone_set('America/Santiago');
			$nomarchivo = 'logs/log_generacion_pdf_'.@date("Ymd").'.TXT';
			$ar=fopen($nomarchivo,"a") or
			die("Problemas en la creacion");
			fputs($ar,@date("H:i:s")." ".$mensaje);
			fputs($ar,"\n");
			fclose($ar);      
		}
		
		private function graba_log_plantilla ($mensaje)
		{
			date_default_timezone_set('America/Santiago');
			$nomarchivo = 'logs/log_generacion_plantilla_pdf_'.@date("Ymd").'.TXT';
			$ar=fopen($nomarchivo,"a") or
			die("Problemas en la creacion");
			fputs($ar,@date("H:i:s")." ".$mensaje);
			fputs($ar,"\n");
			fclose($ar);      
		}
	}

?>