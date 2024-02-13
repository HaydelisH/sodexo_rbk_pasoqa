<?php
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);

// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/importacion_firmaBD.php");
include_once("includes/parametrosBD.php");

include_once("generar.php");


//para proceso de separado de pdf
include ('includes/pdftotext/PdfToText.phpclass');

require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/tcpdi.php');

$page = new importacion_firma_split();

class importacion_firma_split {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $importacion_firmaBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	
	private $hojasxdocumento;
	private $rutadescartar;
	private $rutadescartar_arr;
	private $frasevalidacion;
	private $pdfaprocesar;
	private $contIntentosCurl = 5;
	private $carpetaproceso;
	private $rutasitio;
	private $cantpdf;
	private $protocolo = 'https';
	
	// funcion contructora, al instanciar
	function __construct()
	{
			
		// revisamos si la accion es volver desde el listado principal
		if (isset($_POST["accion"]))
		{
			// si lo es
			if ($_POST["accion"]=="Volver")
			{
				// nos devolvemos al lugar especificado
				header('Location: index.php');
				return;
			}
		}
		
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{	
			$this->graba_log_error("ERROR, al relizar la conexión a la base de datos");
			echo 'ERROR, al relizar la conexión a la base de datos';
			exit;			
		}
		
		
		if (!isset($_POST["usuarioid"]))
		{
			// creamos la seguridad
			$this->seguridad = new Seguridad($this->pagina,$this->bd);
			// si no funciona hay que logearse
			if (!$this->seguridad->sesionar()) 
			{
				$this->graba_log_error("ERROR, Debe Iniciar sesión!");
				echo 'ERROR, Debe Iniciar sesión!';
				exit;
			}
		}
		
		/*
		$infovariables 	= array_keys($_POST); 	// obtiene los nombres de las variables
		$infovalores 	= array_values($_POST);	// obtiene los valores de las variables
		$cantparametros = count($_POST);
		for($i=0;$i<$cantparametros;$i++){
			$infoconsulta.= $infovariables[$i].'='.$infovalores[$i].'|';
			$this->graba_log ("del request:".$infovariables[$i].' - '.$infovalores[$i]);
		}
		*/
		// instanciamos del manejo de tablas
    	$this->importacion_firmaBD = new importacion_firmaBD();
		$this->parametrosBD = new parametrosBD();
		// si se pudo abrir entonces usamos la conexion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->importacion_firmaBD->usarConexion($conecc);
		$this->parametrosBD->usarConexion($conecc);
		
		$ruta = "";
		$ruta = dirname(__FILE__); 
		$ruta = str_replace("\\","/",$ruta);
		$this->rutasitio = $ruta;				
		
		if (!isset($_POST["accion"]))
		{
			$this->proceso();
			return;
		}
		
		switch ($_POST["accion"])
		{
			case "ESTADO":
				$this->estado();
				return;	
	
			case "ESTADOREPROCESO":
				$this->estado_reproceso();
				return;					

			case "VALIDAR":
				$this->validar();
				return;			

			case "REPROCESO":
				$this->reproceso();
				return;	

			case "KILL":
				$this->eliminar_marca();
				return;					
		}
		
	}
	
	private function eliminar_marca()
	{
		//$this->graba_log("ELIMINAR MARCA ");
		$datos["usuarioid"] = $this->seguridad->rut;
		$this->carpetaproceso = 'liq_'.$datos["usuarioid"];
		$this->eliminapdf($datos);
	}
	
	private function estado()
	{
		$datos["usuarioid"] = $this->seguridad->rut;
		$this->importacion_firmaBD->ObtenerUltimaPagina($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;		
		if($dt->leerFila())	
		{
			//$this->graba_log("estado pagina ".$dt->data[0]['pagina']);
			echo json_encode(array(
			'actual'=>$dt->data[0]['paginafin']
			));

		}
		else
		{
			echo json_encode(array(
			'actual'=>'0'
			));
		}
	}
	
	private function estado_reproceso()
	{
		$datos["usuarioid"] = $this->seguridad->rut;
		$this->importacion_firmaBD->ObtenerReprocesados($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;		
		if($dt->leerFila())	
		{
			$mensaje = $dt->data[0]['reprocesados']."|";
			//$this->graba_log("LEIDO ESTADO REPROCESO ".$mensaje);
		}
		else
		{
			//$this->graba_log("NO LEIDO ESTADO REPROCESO ".$mensaje);
		}
		print ($mensaje);
		
	}
	
	private function proceso()
	{	
		$datos = $_POST;
		
		$this->graba_log("PROCESO INICIO");
	
		if (!isset($datos["usuarioid"]))
		{	//$this->graba_log("NO viene usuarioid");
			$datos["usuarioid"] = $this->seguridad->rut;
		}
		else
		{
			//$this->graba_log("viene usuarioid");
		}
		
		$this->carpetaproceso = 'liq_'.$datos["usuarioid"];
		//$this->graba_log("carpeta proceso ".$this->carpetaproceso);
		
		$firmantes = '';
		for ($i = 0; $i < count($datos['Firmantes_Emp']); $i++)
		{
			$firmantes.= $datos['Firmantes_Emp'][$i].'|';
		}
		$datos["rutrepresentantes"] = $firmantes;
		
		$this->importacion_firmaBD->ObtenerConfiguracion($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		if(!$dt->leerFila())
		{
			$this->graba_log_error("ERROR, Tipo de Contrato no esta configurado para este proceso");
		}
		else
		{
			$this->hojasxdocumento 		= $dt->data[0]["paginasxdocumento"];
			$this->rutadescartar 		= strtoupper($dt->data[0]["rutadescartar"])."|";
			$this->rutadescartar_arr	= explode("|",$this->rutadescartar);
		}
		$this->graba_log("OK, hojas x documento ".$this->hojasxdocumento." rut a descartar:".$this->rutadescartar);
		
		$ruta = $this->rutasitio;
		$ruta.= '/tmp/'.$this->carpetaproceso;
		$this->pdfaprocesar = $ruta."/imp_".$datos["usuarioid"].'.pdf';
		
		if (!file_exists($this->pdfaprocesar))
		{
			$this->graba_log_error("ERROR, no existe archivo ".$this->pdfaprocesar);
			return;
		}
		else 
		{
			//$this->graba_log("OK, SI existe archivo ".$this->pdfaprocesar);
		}
		
		if (!isset($datos["totalpaginas"]))
		{
			//$new_pdf	= new TCPDI();
			//$pagecount = $new_pdf->setSourceFile($this->pdfaprocesar);	// Cuantas Paginas?
			$pagecount = count(glob($ruta.'/{liq_*.pdf}',GLOB_BRACE));
			//$pagecount--;
			//$this->graba_log("OK, paginas ".$pagecount);
			$datos["totalpaginas"] = $pagecount;
		}
		
		$rutaarray		= explode(".",$this->pdfaprocesar);			// para obtener nombre de archivo sin extension
		$sinextension	= $rutaarray[0];
		//$this->graba_log("OK, ruta sin extension ".$sinextension);
		
		$rutaarch		= $sinextension."_*";

		$this->importacion_firmaBD->ObtenerUltimaPagina($datos,$dt3);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		$pagina 	= 0;
		$paginafin 	= 0;
		if (isset($dt3->data[0]["pagina"]))
		{
			$pagina 	= $dt3->data[0]["pagina"];
			$paginafin 	= $dt3->data[0]["paginafin"];
		} 
		$pagina++;
		$paginafin++;
		$paginainicio = $paginafin;
		
		$this->graba_log("OK, numero hoja a procesar ".$paginafin.' generadas '.$pagina);
		
		$tiempoInicial = time();
		
		$estadomerge = true;
		$this->new_pdf	= new TCPDI();
		$this->graba_log("TOTAL PAGINAS ".$datos["totalpaginas"]);			
		for ($h = $paginafin; $h < $datos["totalpaginas"] + 1; $h++)
		{
			$splitpaginas.= $h."|";
			
			$liqpdf = $ruta.'/liq_'.$h.'.pdf';
			//$this->graba_log("liqpdf ".$liqpdf);
					
			if (!file_exists($liqpdf))	
			{
				$this->graba_log("ERROR, aun no existe pdf a procesar ".$liqpdf);
				$this->bd->desconectar();
				$this->llamadaCurl($datos);//aun no termina el proceso de separar los pdf retomamos el proceso
				exit;
			}
			
			$this->acumula_pdf($liqpdf);
			$hojasprocesadas++;
			if($hojasprocesadas == $this->hojasxdocumento)
			{
				
				$new_filename = $ruta.'/liqproc_'.$pagina.'.pdf';
				//$this->graba_log("a procesar ".$new_filename);
				$this->graba_nueva_pagina($new_filename);
				$empleadoid = $this->identifica_rut($splitpaginas,$ruta);
				$this->graba_log("empleado ".$empleadoid);
				if ($empleadoid == "ERROR")
				{
					$observacion = "ERROR, No es posible obtener rut en hoja nro ".$h;
					$this->graba_log_error("ERROR, No es posible obtener rut en hoja nro ".$h);
					$estado = 0;
					$this->graba_info($empleadoid,$pagina,$paginainicio,$h,$observacion,$estado,$datos);			
				}
				else
				{	
					//$this->graba_log("pagina fin ".$h);
					$this->proceso_envio($empleadoid,$pagina,$paginainicio,$h,$datos,$new_filename,0);
				}
				
				$pagina++;
				$this->new_pdf	= new TCPDI();
				$hojasprocesadas = 0;
				$paginainicio = $h + 1;
				$splitpaginas = '';
			}
			
			if (time() -  $tiempoInicial > LIMITE_PROCESA_EXCEL * 60)
			{
				$this->graba_log('ultima fila '.$fila.' usuario '.$datos['usuarioid']);
				$datos['fila'] = $fila + 1;
				$this->llamadaCurl($datos);
				$this->bd->desconectar();
				exit;
			}
		
		}
		
		$this->graba_log("OK, fin proceso");
		echo "FIN PROCESO";
		$this->eliminapdf($datos);
		exit;
	}
	
	private function graba_nueva_pagina($new_filename)
	{
		try
		{
			$this->new_pdf->Output($new_filename, "F");
			$this->new_pdf->close();
		}
		catch (Exception $e) 
		{
			$this->graba_log_error ('ERROR, al grabar nueva pagina '.$new_filename.' error '.$e->getMessage());
		}
	}
	
	private function acumula_pdf($pdfadiciona)
	{
		$estadomerge = true;
		try
		{
			$this->new_pdf->setSourceFile($pdfadiciona);	
			$this->new_pdf->SetPrintHeader(false);//no muestra linea en encabezado
			$this->new_pdf->SetPrintFooter(false);//no muestra linea en pie
			
			$tplIdx = $this->new_pdf->importPage(1);
			$size = $this->new_pdf->getTemplateSize($tplIdx);
			
			//parametros hoja
			$orientation = ($size['h'] > $size['w']) ? 'P' : 'L';
			if ($orientation == "P") 
			{
				$this->new_pdf->AddPage($orientation, array($size['w'], $size['h']));
			} 
			else 
			{
				$this->new_pdf->AddPage($orientation, array($size['h'], $size['w']));
			}
			$this->new_pdf->useTemplate($this->new_pdf->importPage(1));
		}
		catch (Exception $e) 
		{
			$this->graba_log ('ERROR, Excepción capturada archivo:'.$pdfadiciona.' rut:'.$this->rutaux,  $e->getMessage());
			$estadomerge = false;
		}
		
		return $estadomerge;
	}
	
	private function DeducirUrl()
	{
		$http = $this->protocolo.'://';
		//CSB 19-02-2019 deducimos si es https o http porque pueden ser de las dos maneras
		$pos = strpos($_SERVER['HTTP_HOST'], "8099");
		if ($pos !== false) 
		{
			$http="http://";
		}
		//fin
	
		$url=$http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		return $url;
	}
	
	private function eliminapdf ($datos)
	{
		$file = $this->rutasitio.'/tmp/'.$this->carpetaproceso."/imp_".$datos["usuarioid"].".pdf";
		$this->graba_log("ELIMINAR PDF ".$file);
		if(is_file($file))
		{
			unlink($file); //elimino el documento
		}		
	}
	
	private function llamadaCurl($datos)
	{
		//$this->graba_log("LIMITE PROCESA_EXCEL ".LIMITE_PROCESA_EXCEL." usuario ".$datos['usuarioid']);
		$limite_adicional = LIMITE_PROCESA_EXCEL + 1;//para que el curl espere 1 minuto mas de lo configurado para generar 
		//$this->graba_log_curl("limite adicional ".$limite_adicional);
		$curl_limite = $limite_adicional * 60;
		
		$datos2["idparametro"] = 'url_curl';
		$dt2 = new DataTable();
		$this->parametrosBD->obtener($datos2,$dt2);
		$this->mensajeError.=$this->parametrosBD->mensajeError;
		if($dt2->leerFila())
		{
			$url = $dt2->data[0]['parametro'].'/importacion_firma_split.php';
		}
		else
		{
			$url = $this->DeducirUrl();
		}
		
		$this->graba_log("url:".$url." usuario ".$datos['usuarioid']);
		
		$parametros = "usuarioid={$datos['usuarioid']}&idPlantilla={$datos['idPlantilla']}";
		$parametros.= "&idProceso={$datos['idProceso']}&RutEmpresa={$datos['RutEmpresa']}";
		$parametros.= "&FechaDocumento={$datos['FechaDocumento']}&totalpaginas={$datos['totalpaginas']}";
		$parametros.= "&rutrepresentantes={$datos['rutrepresentantes']}";
		
		$this->graba_log("parametros:".$parametros);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $parametros);
		//curl_setopt($ch, CURLOPT_FAILONERROR, true);
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, $curl_limite);
		if( curl_exec($ch) === false )
		{	
			$intentos++;
			$this->graba_log("Curl : ".curl_errno($ch)." ".curl_error($ch)." intentos:".$intentos." usuario ".$datos['usuarioid']);
			if ($intentos < $this->contIntentosCurl)
			{
				$this->graba_log(" Reintento Curl ".$intentos." usuario ".$datos['usuarioid']);
				$intentos++;
				$this->llamadaCurl($datos);
			}
			
		}
		
		$this->graba_log("Curl OK, reintentos:".$intentos.", usuario:".$datos['usuarioid']);
		curl_close($ch);
	}
	
	
	private function llamadaCurlReproceso($datos)
	{
		$limite_adicional = LIMITE_PROCESA_EXCEL + 1;//para que el curl espere 1 minuto mas de lo configurado para generar 
		$curl_limite = $limite_adicional * 60;
		
		$datos2["idparametro"] = 'url_curl';
		$this->parametrosBD->obtener($datos2,$dt2);
		$this->mensajeError.=$this->parametrosBD->mensajeError;
		if($dt2->leerFila())
		{
			$url = $dt2->data[0]['parametro'].'/importacion_firma_split.php';
		}
		else
		{
			$url = $this->DeducirUrl();
		}
		
		$parametros = "accion=REPROCESO&pagina={$datos['pagina']}}";
				
		$this->graba_log("parametros:".$parametros);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $parametros);
		//curl_setopt($ch, CURLOPT_FAILONERROR, true);
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, $curl_limite);
		if( curl_exec($ch) === false )
		{
			$intentos++;
			$this->graba_log("Curl : ".curl_errno($ch)." ".curl_error($ch)." intentos:".$intentos." usuario ".$datos['usuarioid']);
			if ($intentos < $this->contIntentosCurl)
			{
				$this->graba_log(" Reintento Curl reproceso ".$intentos." usuario ".$datos['usuarioid']);
				$this->llamadaCurlReproceso($datos);
			}
			
		}
		
		$this->graba_log("Curl OK, reintentos reproceso: ".$intentos.", usuario:".$datos['usuarioid']);
		curl_close($ch);
	}
	
	
	private function reproceso()
	{
		$this->graba_log("INICIO REPROCESO ".$this->seguridad->rut);
		$datos=$_POST;
		/*
		$infovariables 	= array_keys($datos); 	// obtiene los nombres de las variables
		$infovalores 	= array_values($datos);	// obtiene los valores de las variables
		$cantparametros = count($datos);
		for($i=0;$i<$cantparametros;$i++){
			$infoconsulta.= $infovariables[$i].'='.$infovalores[$i].'|';
			$this->graba_log ("del request:".$infovariables[$i].' - '.$infovalores[$i]);
		}
		*/
		
		if ($datos["pagina"] == 0)
		{
			$datos["usuarioid"]=$this->seguridad->rut;
			$this->importacion_firmaBD->DesmarcarReproceso($datos,$dt);
			$this->mensajeError.=$this->importacion_firmaBD->mensajeError;			
		}
		
		$datos["usuarioid"]=$this->seguridad->rut;
		$this->importacion_firmaBD->ObtenerNoEnviado($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		$cantidad = count($dt->data);
		
		$tiempoInicial = time();
		for ($h = 0; $h < $cantidad; $h++)
		{
			$datos["RutFirmantes_Emp"] = array();
			if ($dt->data[0]["rutrepresentantes"] != '')
			{
				$i=0;
				$rep_arr = explode('|',$dt->data[0]["rutrepresentantes"]);
				for ($f = 0; $f < count($rep_arr); $f++)
				{
					if  (trim($rep_arr[$f]) != '')
					{
						$datos["RutFirmantes_Emp"][$i]=$rep_arr[$f];
						$i;
					}
				}
				
			}	
			
			$datos["RutEmpresa"] = $dt->data[$h]["empresaid"];
			$datos["FechaDocumento"] = $dt->data[$h]["fechadocumento"];
			$datos['idPlantilla'] = $dt->data[$h]["tipocontrato"];
			$datos['idProceso'] = $dt->data[$h]["procesofirma"];
			$new_filename = $this->rutasitio.'/tmp/liq_'.$datos["usuarioid"].'/liqproc_'.$dt->data[$h]["pagina"].".pdf";
			//$this->graba_log('FILENAME'.$new_filename);
			$this->proceso_envio($dt->data[$h]["empleadoid"],$dt->data[$h]["pagina"],$dt->data[$h]["paginainicio"],$dt->data[$h]["paginafin"],$datos,$new_filename,1);
		
			if (time() -  $tiempoInicial > LIMITE_PROCESA_EXCEL * 60)
			{
				$this->graba_log('ultima fila reproceso'.$fila.' usuario '.$datos['usuarioid']);
				$datos['pagina'] = $dt->data[0]["pagina"];
				$this->llamadaCurlReproceso($datos);
				$this->bd->desconectar();
				exit;
			}
		}
	}
	
	private function proceso_envio($empleadoid,$nropdf,$paginainicio,$paginafin,$datos,$new_filename,$reprocesado)
	{	//$this->graba_log("PROCESO ENVIO".$empleadoid);
		/*
		$infovariables 	= array_keys($datos); 	// obtiene los nombres de las variables
		$infovalores 	= array_values($datos);	// obtiene los valores de las variables
		$cantparametros = count($datos);
		//$this->graba_log ("cantidad de parametros:".$cantparametros);
		for($i=0;$i<$cantparametros;$i++){
			$infoconsulta.= $infovariables[$i].'='.$infovalores[$i].'|';
			$this->graba_log ("del request:".$infovariables[$i].' - '.$infovalores[$i]);
		}
		*/
		$datos["reprocesado"]=$reprocesado;
		$datos["empleadoid"] = $empleadoid;
		$this->importacion_firmaBD->ObtenerEmpleado($datos,$dt);
		$this->mensajeError=$this->importacion_firmaBD->mensajeError;
		if(!$dt->leerFila())
		{	
			$observacion = "No existe trabajador ";
			$this->graba_info($empleadoid,$nropdf,$paginainicio,$paginafin,$observacion,0,$datos );
			return;
		}
		
		/*
		$datos["idPlantilla"]		= $plantillaid;
		$datos["fechadocumento"] 	= $this->fechadocumento;
		$datos["idProceso"]			= $this->procesoid;
		*/
		
		//$this->graba_log($dt->data[0]["nombre"]."_".utf8_encode($dt->data[0]["nombre"])."_".utf8_decode($dt->data[0]["nombre"]));
		
		$datos["fechadocumento"] 	= $datos["FechaDocumento"];
		//$this->graba_log("USUARIO ".$datos["usuarioid"]);
		$datos["rutusuario"] 		= $datos["usuarioid"];
		$datos["newusuarioid"] 		= $dt->data[0]["empleadoid"];
		$datos["nombre"] 			= utf8_encode($dt->data[0]["nombre"]);
		$datos["appaterno"] 		= utf8_encode($dt->data[0]["appaterno"]);
		$datos["apmaterno"] 		= utf8_encode($dt->data[0]["apmaterno"]);
		$datos["LugarPagoid"] 		= $dt->data[0]["lugarpagoid"];
		$datos["idCentroCosto"]		= $dt->data[0]["centrocostoid"];
		$datos["fechanacimiento"]	= $dt->data[0]["fechanacimiento"];
		$datos["correo"]			= $dt->data[0]["correo"];
		$datos["nacionalidad"]		= utf8_encode($dt->data[0]["nacionalidad"]);
		//$datos["idEstadoCivil"]		= $this->obteneridecivil($dt->data[0]["estadocivil"]);
		$datos["idEstadoCivil"]		= $dt->data[0]["estadocivil"]; //HH, estaba trayendo un valor vacio
		$datos["direccion"]			= utf8_encode($dt->data[0]["direccion"]);
		$datos["comuna"]			= utf8_encode($dt->data[0]["comuna"]);
		$datos["ciudad"]			= utf8_encode($dt->data[0]["ciudad"]);
		$datos["paginainicio"]		= $paginainicio;
		$datos["paginafin"]			= $paginafin;
		
		//Tipo de Generacion
		$datos['idTipoGeneracion'] = 5; //Generacion masi de PDF 
		
		$archivo = file_get_contents($new_filename);
		$datos["pdf64"] = base64_encode($archivo);//el archivo en base 64
		
		//$datos["Firmantes_Emp"] = $datos["rutrepresentantes"];
		
		$this->graba_log('VA A GENERAR '.$datos["empleadoid"]);
		$generar = new generar();
		$respuesta = array();
		$respuesta = $generar->GenerarDocumento($datos);
		
		if( $respuesta['estado'] )
		{
			$idDocumento = '';
			$idDocumento = $respuesta['data'];
			$this->mensajeOK = $respuesta['mensaje'].' con el Nro de Documento: <b>'.$idDocumento.'</b>';
			$this->graba_log('OK, al generar documento '.$idDocumento);
			$this->graba_info($empleadoid,$nropdf,$paginainicio,$paginafin,$this->mensajeOK,1,$datos );
		}
		else
		{
			$this->mensajeError = str_replace("'","''",$respuesta['mensaje']);
			$this->graba_log('ERROR, al generar documento '.$this->mensajeError);
			$this->graba_info($empleadoid,$nropdf,$paginainicio,$paginafin,$this->mensajeError,0,$datos );
		}		
		$this->graba_log('FIN GENERAR '.$datos["empleadoid"]);
	}
	
	private function obteneridecivil($descripcion)
	{
		
		if (trim($descripcion) == '')
		{
			return $descripcion;
		}
		
		$estado = 0;
		//$this->graba_log("des ".$descripcion);
		$valor = substr($descripcion, 0, 2); 
		$valor = strtoupper($valor);
		//$this->graba_log("val ".$valor);
		
		if ($valor == "SO")
			$estado = 1;
			
		if ($valor == "CA")
			$estado = 2;
			
		if ($valor == "DI")
			$estado = 3;
			
		if ($valor == "VI")
			$estado = 4;
			
		return $estado;		
	}
	
	private function graba_info($empleadoid,$nropdf,$paginainicio,$paginafin,$observacion,$estado,$datos)
	{
		$datos["pagina"]		= $nropdf;
		$datos["empleadoid"]	= $empleadoid;
		$datos["estado"]		= $estado;
		$datos["observacion"]	= $observacion;
		$datos["paginainicio"]  = $paginainicio;
		$datos["paginafin"]  	= $paginafin;
		
		$this->importacion_firmaBD->Grabar($datos);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		if ($this->mensajeError != "")
		{
			$this->graba_log_error("ERROR, al grabar informacion del rut trabajador ".$this->mensajeError);
		}
	}
		
	private function identifica_rut($splitpaginas,$ruta)
	{
		//$this->graba_log("paginas a buscar ".$splitpaginas);
		$paginas = explode("|",$splitpaginas);
		//$this->graba_log("cantidad de paginas ".count($paginas));
		for ($l = 0; $l < count($paginas); $l++) 
		{
			$pagina = $paginas[$l];
			//$this->graba_log("pagina ".$pagina);
			if ($pagina != '')
			{
				$archivo = $ruta.'/liq_'.$pagina.'.pdf';
				//$this->graba_log("archivo :".$archivo);
				$respuesta = $this->busca_rut($archivo);
				if ($respuesta <> "ERROR")
				{
					return $respuesta;
				}
			}
		}
		
		return "ERROR";
	}
	
	private function busca_rut ($new_filename)
	{	
		$pdf		=  new PdfToText ($new_filename) ;
		$pdftext 	= $pdf -> Text ; 	
		$lineastexto = explode("\n", $pdftext);
		
		$expresion = '/([0-9]{1,2}.[0-9]{3}.[0-9]{3})([ ]*)-([ ]*)([\dkK])|([0-9]{1,2}[0-9]{3}[0-9]{3})([ ]*)-([ ]*)([\dkK])/'; //expresion regular rut
		

		for ($l = 0; $l < count($lineastexto); $l++) 
		{
			$lineastexto[$l]= strtoupper($lineastexto[$l]);	// Para pasar todo a mayuscula y buscar las frases de esta forma
			$lineastexto[$l]= str_replace(' ', '', $lineastexto[$l]);	// Eliminacion de caracteres no deseados en medio del rut como por ejemplo actual, espacios entre el guion
			preg_match($expresion, $lineastexto[$l], $encontrados);
			if (isset($encontrados[0]))
			{
				$rutaux = strtoupper($encontrados[0]);
				$rutempl 	= str_replace(".", "", $rutaux);
				
				if (!in_array($rutempl, $this->rutadescartar_arr)) 
				{
					$rut_arr = explode("-",$rutempl);
					
					$rutsincero = "".(int)$rut_arr[0]."";
					$rutempleado = $rutsincero."-".$rut_arr[1];
					return $rutempleado;
				}				
			}
			
		}
	
		return "ERROR";
	}

	
	private function validar()
	{	
	
		$datos=$_POST;
		
		$this->carpetaproceso = 'liq_'.$this->seguridad->usuarioid;
		$this->graba_log("carpeta proceso ".$this->carpetaproceso);
			
		if ($datos["RutEmpresa"] == "0")
		{
			$this->mensajeError = "Debe seleccionar empresa<br>";
		}
		
		if ($datos["idPlantilla"] == "0")
		{
			$this->mensajeError.= "Debe seleccionar tipo contrato<br>";
		}
		
		if (isset($datos["idProceso"]))
		{
			if ($datos["idProceso"] == "0")
			{
				$this->mensajeError.= "Debe seleccionar proceso firma<br>";
			}
		}
		else
		{
			$this->mensajeError.= "Debe seleccionar proceso firma<br>";
		}
		
		if (trim($datos["FechaDocumento"]) == "")
		{
			$this->mensajeError.= "Debe ingresar Fecha Documento<br>";
		}

		if ($this->mensajeError == "")
		{
			$carpeta	= './tmp/'.$this->carpetaproceso;
			$resultado  = $this->crear_carpeta($carpeta);
			
			if ($resultado == true)
			{	$this->graba_log('crea carpeta SI '.$carpeta);
				$this->eliminar_archivos();
				$this->eliminar_proceso();
			}
			else
			{	$this->graba_log('crea carpeta NO '.$carpeta);
				$this->mensajeError.= "Error, al crear carpeta";
			}
	
			$this->importacion_firmaBD->ObtenerConfiguracion($datos,$dt);
			$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
			if(!$dt->leerFila())
			{
				$this->graba_log("NO configurado");
				$this->mensajeError.= "Error, Tipo de Contrato no esta configurado para este proceso";
			}
			else
			{
				$this->graba_log("SI configurado");
				$this->hojasxdocumento 		= $dt->data[0]["paginasxdocumento"];
				$this->rutadescartar 		= strtoupper($dt->data[0]["rutadescartar"])."|";
				$this->rutadescartar_arr	= explode("|",$this->rutadescartar);
				$this->frasevalidacion		= $dt->data[0]["frasevalidacion"];	
				
				$this->valida_archivo();
			}
			
			
		}
		
		$this->graba_log("error ".$this->mensajeError);
		
		if (!$this->mensajeError == "")
		{	
			print ($this->mensajeError);
			return;
		}
		$this->graba_log("OK ".$this->mensajeError);
		$mensaje = "OK|".$this->cantpdf;
		$this->graba_log("CANTIDAD DE PDF ".$mensaje);
		print ($mensaje);
	
	}
	
	private function valida_archivo()
	{
		

		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fileName = $_SERVER['HTTP_X_FILE_NAME'];
			$contentLength = $_SERVER['CONTENT_LENGTH'];
		}
		else 
		{
			$this->mensajeError.="Error al recuperar encabezados";
		}
		
		if (!$contentLength > 0) 
		{
			$this->mensajeError.="Error no se carg&oacute; el archivo";
		}
			
		$trozos = explode(".", $fileName); //$fileName nombre del archivo
		$extension = end($trozos); 
		$extension = strtolower($extension); //strtolower -> Devuelve string con todos los caracteres alfabéticos convertidos a minúsculas.

		// mostramos la extension del archivo
		if ($extension != "PDF" && $extension !="pdf")
		{
			$this->mensajeError.="Error, solo se pueden subir archivos PDF";
		}
		
		$path = $this->rutasitio;
		$path.= '/tmp/'.$this->carpetaproceso.'/';
		 
		//aqui es donde copia del servidor a la carpeta temporal
		
		$fileName = "imp_".$this->seguridad->rut.".pdf";
		file_put_contents(
			  $path . $fileName,
			  file_get_contents("php://input")
		);
		
    	  // le otorga un atributo de lectura con 0777
		chmod($path.$fileName, 0777);
		
		$this->pdfaprocesar = $path . $fileName;
		$this->graba_log("pdf a procesar ".$this->pdfaprocesar);

		try 
		{
			/*
			$new_pdf	= new TCPDI();
			$new_pdf->AddPage();
			//var_dump($this->pdfaprocesar);			
			$this->cantpdf = $new_pdf->setSourceFile($this->pdfaprocesar);
			$this->cantpdf++;
			
			$tplIdx = $new_pdf->importPage(1);	
			$size = $new_pdf->getTemplateSize($tplIdx);		
			$new_pdf->AddPage("L",array($size['h'], $size['w']));
			$new_pdf->useTemplate($new_pdf->importPage(1));
			
			$new_filename 	= $path."liq_".$this->seguridad->rut."_tmp1".".pdf";					
			$new_pdf->Output($new_filename, "F");					
			$new_pdf->close();	*/
			$this->graba_log("new ".$this->pdfaprocesar);
			$pdf		=  new PdfToText ($this->pdfaprocesar) ;
			$pdftext 	= $pdf -> Text ;  
			//$this->graba_log("this->frasevalidacion ".utf8_encode($this->frasevalidacion));
			//$this->graba_log("El pdf ".$pdftext);
			if( trim($this->frasevalidacion) != '' ){
				$posicion = strrpos(strtoupper($pdftext), utf8_encode($this->frasevalidacion));$this->graba_log("FRASE:".$this->frasevalidacion);
				if ($posicion === false) 
				{
					$this->mensajeError.= "Error, documento pdf importado no corresponde a una liquidacion";
				}
			}
			/*
			if(is_file($new_filename))
			{					
				if (!unlink($new_filename))
				{
					
				}					
			}
			*/
			if ($this->mensajeError == '')
			{
				$this->EjecutaServicioSplit($path,$fileName);
				sleep(3);
				$this->cantpdf = count(glob($path.'/{liq_*.pdf}',GLOB_BRACE));
				$this->cantpdf++;
			}
		}
		catch(Exception $e) 
		{
			 $this->mensajeError.= "Error, libreria TCPDI ".$e->getMessage();
		}
	}
	
	private function EjecutaServicioSplit($ruta,$archivo)
	{
		$url 			= SERVICIO_SPLIT;
		
		$ruta 			= str_replace("/","\\",$ruta);
		$archivo_pdf 	= $ruta.$archivo;
		$this->graba_log("arch pdf:".$archivo_pdf);
		$carpeta_split 	= $ruta;
		$this->graba_log("carpeta split:".$carpeta_split);
		$xml= "<Root>";
		$xml.="<Documento>".$archivo_pdf."</Documento>";
		$xml.="<SaveTo></SaveTo><ID></ID><MultiPage></MultiPage>";
		$xml.="<TmpFolder>".$carpeta_split."</TmpFolder>";
		$xml.="</Root>";
	
		$this->graba_log("parametros xml:".$xml);
		
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
		
	private function eliminar_proceso()
	{
		$datos["usuarioid"] = $this->seguridad->rut;
		$this->importacion_firmaBD->Eliminar($datos);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;			
	}
	
	private function eliminar_archivos()
	{
		$ruta = $this->rutasitio.'/tmp/'.$this->carpetaproceso.'/'."liq*";
		$files = glob($ruta); //obtenemos todos los nombres de los ficheros segun ruta
		foreach($files as $file)
		{
			if(is_file($file))
			unlink($file); //elimino el documento
		}
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
	
	private function graba_log($info)
	{
		
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\split_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s",$time)." ".$info);
	   	fputs($ar,"\n");
  		fclose($ar);
		
	}
		
	private function graba_log_error($info)
	{
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\split_error_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s",$time)." ".$info);
	   	fputs($ar,"\n");
  		fclose($ar);
	}

}		
?>