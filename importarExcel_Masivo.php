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
		if ($datos['accion'] == 'LOAD' || $datos['accion'] == 'LOOP0' || !isset($datos['accion']) || $datos['accion'] == 'KILL')
		{
			if (!$this->seguridad->sesionar()) 
			{
				echo 'Mensaje | Debe Iniciar sesi�n!';
				exit;
			}
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
			case 'LOAD':
			{
				$this->load();
				break;
			}
			case 'LOOP':
			case 'LOOP0':
			{
				$this->procesar();
				break;
			}
			case 'KILL':
			{
				$this->eliminarArchivo();
				break;
			}
		}
	}
	
	private function eliminarArchivo()
	{
		$this->setNombreFichero();
		if (file_exists($this->path.$this->nombreArchivoSubida))
		{
			unlink($this->path.$this->nombreArchivoSubida);
		}
	}

	private function matchColumn()
	{
		$datos = $_POST;
		$this->importarBD->obtenerConfimpArchivoDet($datos,$dt2);
		$this->camposArchivoDet=$dt2; //datos de la tabla confiDet
		for($col = 0; $col <= $this->highestColumnIndex - 1; $col++)
		{
			$colString 				= PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
			$columnaFila 			= "{$colString}1";
			$valorCelda = $this->sheet->getCell($columnaFila)->getValue();
			//$this->graba_log("EXCEL->{$valorCelda}");
			for( $c= 0; $c < count($this->camposArchivoDet->data); $c++)
			{
				//$aux = $this->camposArchivoDet->data[$c]['NombreExterno'];
				//$aux = mb_convert_encoding($aux, 'ISO-8859-1');
				//$aux = iconv('ISO-8859-1', 'UTF-8', $aux);
				//$this->graba_log("TABLA->{$aux}");
				if (strcmp(strtolower($valorCelda), iconv('ISO-8859-1', 'UTF-8', strtolower($this->camposArchivoDet->data[$c]['NombreExterno']))) == 0)
				{
					$this->camposArchivoDet->data[$c]['ColumnaPlanilla'] = $colString;
					//$this->graba_log("MATCH EXCEL-TABLA->{$colString}->{$valorCelda}");
				}
				//$this->graba_log("BASE->{$camposArchivoDet->data[$c]['Nombre']}");
				//$this->graba_log(strtolower($valorCelda)." EXTERNO ".strtolower($this->camposArchivoDet->data[$c]['NombreExterno']).strcmp(strtolower($valorCelda), iconv('ISO-8859-1', 'UTF-8', strtolower($this->camposArchivoDet->data[$c]['NombreExterno']))));
			}
		}
	}

	private function procesar()
	{
		set_time_limit(0);
		$filaActual = (isset($_POST['fila']) ? $_POST['fila'] : 2);
		$tiempoInicial = time();
		$datos = $_POST;
		$datos['usuarioid'] = isset($this->seguridad->usuarioid) ? $this->seguridad->usuarioid : $datos['usuarioid'];
		/*$this->respuesta_importarBD->cuenta(array(
			'usuarioingid'=>$datos['usuarioid'],
			'IdArchivo'=>$datos['IdArchivo']
		),$dt);*/
		$this->setNombreFichero();
		$this->getDataGrilla();
		$this->matchColumn();
		$generar = new generar();
		for($fila = $filaActual; $fila <= $this->highestRow; $fila++)
		{
			//$this->graba_log_curl('INICIO FILA PROCESADA ' . $fila);
			$this->mensajeError = '';
			$data = array();
			$data['idPlantilla'] = $datos['idPlantilla'];
			$data['idProceso'] = $datos['idProceso'];
			$data['RutEmpresa'] = $datos['RutEmpresa'];
			
			//Cambiar el orden de los firmantes 
			$rut_1 = '';
			$rut_2 = '';
			$rut_3 = '';
			$cant_f = 0;
			
			$cant_f = count($datos["Firmantes_Emp"]);

			if( $cant_f > 1 ){
				foreach( $datos["Firmantes_Emp"] as $key => $value){
				
					$var ='';
					$var = 'orden_';
					$var .= $value; 
		
					if( $datos[$var] == 1 ){
						$rut_1 = $value;
					}else if( $datos[$var] == 2 ){
						$rut_2 = $value;
					}else{
						$rut_3 = $value;
					}
				}
				
				$array = array();
				
				if( strlen($rut_1 ) > 0 ) $array[0] = $rut_1;
				if( strlen($rut_2 ) > 0 ) $array[1] = $rut_2;
				if( strlen($rut_3 ) > 0 ) $array[2] = $rut_3;

				$datos["Firmantes_Emp"] = array();
				$datos["Firmantes_Emp"] = $array;
			}
			//Fin
		
			$data['Firmantes_Emp'] = $datos['Firmantes_Emp'];
			$data['idFirma'] = $datos['idFirma'];
			$data['idTipoGeneracion'] = '3';	
            date_default_timezone_set('America/Santiago');				
			for($col = 0; $col <= $this->highestColumnIndex - 1; $col++)
			{
				$colString 				= PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
				$columnaFila 			= $colString.$fila;
				$valorCelda = $this->sheet->getCell($columnaFila)->getValue();
				for( $c= 0; $c < count($this->camposArchivoDet->data); $c++)
				{	//$this->graba_log($this->camposArchivoDet->data[$c]['ColumnaPlanilla']." : ".$valorCelda);
					if (isset($this->camposArchivoDet->data[$c]['ColumnaPlanilla']) ? $this->camposArchivoDet->data[$c]['ColumnaPlanilla'] == $colString : false)
					{
						//$this->graba_log("Antes de preformatear celda->{$this->camposArchivoDet->data[$c]['ColumnaPlanilla']}->{$valorCelda}");
						$this->clearData($valorCelda, $this->camposArchivoDet->data[$c]);
						//$this->graba_log("Despues de preformatear celda->{$this->camposArchivoDet->data[$c]['ColumnaPlanilla']}->{$valorCelda}");
						//$resultadoTipoDato = '';
						
						if (!($this->camposArchivoDet->data[$c]['Obigatorio']))
                        {
                            if ($valorCelda != '')
                            {
								//Inicio*** Actualizacion de formato de fecha de Excel 
								//Convertir formato de fecha en la fecha aceptada 
								if ( is_numeric($valorCelda) && $this->camposArchivoDet->data[$c]['TipoDato'] == 3 )
								{	
									$valorCelda = date(VAR_FORMATO_FECHA, strtotime("+1 day", PHPExcel_Shared_Date::ExcelToPHP($valorCelda)));
									//$valorCelda = date(VAR_FORMATO_FECHA, PHPExcel_Shared_Date::ExcelToPHP($valorCelda));
								}   
								else if ( !is_numeric($valorCelda) && $this->camposArchivoDet->data[$c]['TipoDato'] == 3 ){
									//Si viene con '/' que lo reemplace con guión
									if( strpos($valorCelda,'/') ){
										$valorCelda = str_replace('/','-',$valorCelda);
									}
								}
								//Fin***
								
                                $resultadoTipoDato = $this->validaTipoDato($valorCelda, $this->camposArchivoDet->data[$c]['TipoDato']);
                                if (!$resultadoTipoDato)
                                {
                                    $this->mensajeError.="Error en tipo de dato de la columna <b>{$colString}</b><br>";
                                }
                                $resultadoAnchoCelda = $this->validaAnchoCelda($valorCelda, $this->camposArchivoDet->data[$c]['Ancho']);
                                if (!$resultadoAnchoCelda)
                                {
                                    $this->mensajeError.="Error el dato de la columna <b>{$colString}</b> excede el largo<br>";
                                }
                            }
                        }
						
						if ($valorCelda != '')
						{	
							//Inicio*** Actualizacion de formato de fecha de Excel 
							//Convertir formato de fecha en la fecha aceptada 
							if ( is_numeric($valorCelda) && $this->camposArchivoDet->data[$c]['TipoDato'] == 3 )
							{	
								$valorCelda = date(VAR_FORMATO_FECHA, strtotime("+1 day", PHPExcel_Shared_Date::ExcelToPHP($valorCelda)));
								//$valorCelda = date(VAR_FORMATO_FECHA, PHPExcel_Shared_Date::ExcelToPHP($valorCelda));
							}   
							else if ( !is_numeric($valorCelda) && $this->camposArchivoDet->data[$c]['TipoDato'] == 3 ){
								//Si viene con '/' que lo reemplace con guión
								if( strpos($valorCelda,'/') ){
									$valorCelda = str_replace('/','-',$valorCelda);
								}
							}
						}
						
						$data[$this->camposArchivoDet->data[$c]['Nombre']] = $valorCelda;
					}
				}
			}
			if ($this->mensajeError == '')
			{
				$generar->mensajeError = '';
				//$this->graba_log(implode(",",$data));
						
				$respuesta = $generar->GenerarDocumento($data);
				//Guardamos el resultado de carga
				$dat = array(
					'usuarioid'=>$datos['usuarioid'],
					'fila'=>$fila,
					'resultado'=>($respuesta['estado'] ? 'OK' : 'ERROR'),
					'observaciones'=>html_entity_decode($respuesta['mensaje'] . ($respuesta['estado'] ? ", con ID {$respuesta['data']}" : '')),
					'tipotransaccion'=>'Nuevo Registro',
					'IdArchivo'=>$datos['IdArchivo']
				);
			}
			else
			{
				$dat = array(
					'usuarioid'=>$datos['usuarioid'],
					'fila'=>$fila,
					'resultado'=>'ERROR',
					'observaciones'=>$this->mensajeError,
					//'observaciones'=>html_entity_decode($respuesta['mensaje'] . ($respuesta['estado'] ? ", con ID {$respuesta['data']}" : '')),
					'tipotransaccion'=>'Nuevo Registro',
					'IdArchivo'=>$datos['IdArchivo']
				);
			}
			$this->importarBD->agregar($dat);
			$this->graba_log_curl('FIN FILA PROCESADA ' . $fila);
			
			$cantigenerado++;
			
			if ($cantigenerado >50)
			{
				sleep(2);
				$cantigenerado = 0;
				
			}
			
			if (time() -  $tiempoInicial > LIMITE_PROCESA_EXCEL * 60)
			{
				$datos['fila'] = $fila + 1;
				$this->llamadaCurl($datos);
				exit;
			}
		}
		unlink($this->path.$this->nombreArchivoSubida);
	}

	private function llamadaCurl($datos)
	{
		$this->parametrosBD->obtener(array('idparametro'=>'url_curl'),$dt2);
		$this->mensajeError.=$this->parametrosBD->mensajeError;
		$url= (isset($dt2->data[0]['parametro']) ? $dt2->data[0]['parametro'] : "http://localhost/SMU_RBK") . "/importarExcel_Masivo.php";
		$this->graba_log_curl("url:".$url);
		$parametros = "fila={$datos['fila']}&usuarioid={$datos['usuarioid']}&idPlantilla={$datos['idPlantilla']}&idProceso={$datos['idProceso']}&RutEmpresa={$datos['RutEmpresa']}&IdArchivo={$datos['IdArchivo']}&idFirma={$datos['idFirma']}&accion=LOOP";
		for ($i = 0; $i < count($datos['Firmantes_Emp']); $i++)
		{
			$parametros .= "&Firmantes_Emp[]={$datos['Firmantes_Emp'][$i]}";
		}
		$this->graba_log_curl("parametros:".$parametros);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $parametros);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		if( curl_exec($ch) === false )
		{
			if (((curl_errno($ch) == 28 && stripos(curl_error($ch), 'Resolving') !== false ) || (curl_errno($ch) == 28 && stripos(curl_error($ch), 'SSL connection') !== false )) && $contIntentosCurl < INTENTOS_CURL)				   
			//if (curl_errno($ch) == 28 && stripos(curl_error($ch), 'Resolving') !== false && $contIntentosCurl < INTENTOS_CURL)
			{
				$contIntentosCurl++;
				$this->llamadaCurl($datos);
			}
			$this->graba_log_curl("Curl : ".curl_errno($ch)." ".curl_error($ch));
		}
		curl_close($ch);
	}

	private function clearData(&$valor, $dataConfig)
	{
		$valor = trim($valor);
		switch ($dataConfig['TipoDato'])
		{
			case '2': // Numero
			{
				$valor = preg_replace('/[\.]/i', ',', $valor);
				break;
			}
		}
	}

	private function validaAnchoCelda($valorCelda,$Ancho)
	{
		$str = $valorCelda;
		$strvalidar = trim($str);//quitamos los espacios de inicio y final que contiene cada columna
	
		$intvalidar = strlen($strvalidar);//cuenta cuantas letras contiene cada columna y muestra
	  
		if($intvalidar > $Ancho)
		{
			return False;
		}
		else
		{
			return True;
		}
	
		return False;
	}

	private function validaTipoDato($valorCelda, $TipoDato)
	{
		if($TipoDato == 1) //String
		{ 	
			$valorCelda = (string)$valorCelda;
			if(!is_string($valorCelda)) 
			{
				return False;
			}
			return True;
		}
	  
		if($TipoDato == 2) //Numerico
		{
			if(is_numeric(preg_replace('/[,]/i', '.', $valorCelda)) )
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 3 ) //Fecha
		{	
			if ( $this->validateDate($valorCelda, VAR_FORMATO_FECHA) )
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 4 ) // Rut Chileno xxxxxxxx-x
		{
			if ( $this->valida_rut($valorCelda))
			{
				return true;
			}
			return false;
		}
		
		if( $TipoDato == 5 ) //Correo 
		{
			//if( filter_var($valorCelda, FILTER_VALIDATE_EMAIL) )
			if( $this->is_valid_email($valorCelda) )
			{	
				return true;
			}
			return false;
		}
		return false;
	}

	//Valida Fecha
	private function validateDate($date, $format ='d-m-Y')
	{	//$this->graba_log("FECHAS :",$date);
		$anio = '';
		$fecha_arr = explode('-',$date);
		if ($format == 'd-m-Y')
		{
			$anio = $fecha_arr[2];
		}
		else
		{
			$anio = $fecha_arr[0];
		}
		
		if ($anio < 1900)
		{
			return false;	
		}
		
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	private function valida_rut($rut)
	{
	    $rut = preg_replace('/[^k0-9]/i', '', $rut);
	    $dv  = substr($rut, -1);
	    $numero = substr($rut, 0, strlen($rut)-1);
	    $i = 2;
	    $suma = 0;
	    foreach(array_reverse(str_split($numero)) as $v)
	    {
	        if($i==8)
	            $i = 2;
	        $suma += $v * $i;
	        ++$i;
	    }
	    $dvr = 11 - ($suma % 11);
	    
	    if($dvr == 11)
	        $dvr = 0;
	    if($dvr == 10)
	        $dvr = 'K';
	    if($dvr == strtoupper($dv))
	        return true;
	    else
	        return false;
	}

	private function is_valid_email($str)
	{
		return (false !== strpos($str, "@") && false !== strpos($str, "."));	 
	}

	private function load()
	{
		if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
		{
			$fileName = $_SERVER['HTTP_X_FILE_NAME'];
			$contentLength = $_SERVER['CONTENT_LENGTH'];
		}
		else
		{
			throw new Exception("Error al recuperar encabezados");
		}
		
		if (!$contentLength > 0) 
		{
			throw new Exception('Ning�n archivo cargado');
		}
		//is_dir pregunta si existe el directorio
		//mkdir crea la carpeta
		if (!is_dir($this->path))
		{
			if(!mkdir($this->path, 0777, true)) 
			{       
				$mensaje = 'Error al crear carpeta temporal del proceso';
				exit;
			}
		}
		$trozos = explode(".", $fileName); //$fileName nombre del archivo
		$this->nombreExtensionArchivoSubida = end($trozos); 
		$this->nombreExtensionArchivoSubida = strtolower($this->nombreExtensionArchivoSubida); //strtolower -> Devuelve string con todos los caracteres alfab�ticos convertidos a min�sculas.
		// mostramos la extension del archivo
		if ($this->nombreExtensionArchivoSubida != "xlsx" && $this->nombreExtensionArchivoSubida !="xls")
		{
			print ("Error, solo se pueden subir archivos Excel!");
			exit;
		}
		$this->setNombreFichero();
		
		if (!file_exists($this->path . $this->nombreArchivoSubida))
		{
			file_put_contents(
				$this->path . $this->nombreArchivoSubida,
				file_get_contents("php://input")
			);
		}
		else{
			print ("Error, solo se puede ejecutar este proceso una vez por usuario");
			exit;
		}
		// le otorga un atributo de lectura con 0777
		chmod($this->path.$this->nombreArchivoSubida, 0777);

		//para archivo Excel
		$this->getDataGrilla();
		if($this->resultadoContColumnas != $this->total)
		{
			echo("Error, la cantidad de columnas del archivo importado no corresponde a la configuracion");
			unlink($this->path.$this->nombreArchivoSubida);
			exit;
		}
		//eliminamos resultado de carga por el codigo del usuario
		$datos = $_POST;
		$datos["usuarioid"] = $this->seguridad->usuarioid; 
		$this->importarBD->eliminar($datos);
		echo json_encode(array('highestRow'=>$this->highestRow - 1));
	}

	public function procesoActivo()
	{
		$this->setNombreFichero();
		return file_exists($this->path . $this->nombreArchivoSubida);
	}

	private function setNombreFichero()
	{
		$datos = $_POST;
		$datos['usuarioid'] = isset($this->seguridad->usuarioid) ? $this->seguridad->usuarioid : $datos['usuarioid'];
		$this->nombreArchivoSubida = "{$this->nombreNemotecnicoArchivo}{$datos['usuarioid']}";//.{$this->nombreExtensionArchivoSubida}";
	}

	public function getDataGrilla()
	{
		$archivo = $this->path . $this->nombreArchivoSubida; // se pasan los datos del archivo y el nombre de la carpeta donde este guardado.
		$this->inputFileType = PHPExcel_IOFactory::identify($archivo); //le pasa el contenido del documento y dice que es extencion excel.
		$this->objReader = PHPExcel_IOFactory::createReader($this->inputFileType); //?
		$this->objPHPExcel = $this->objReader->load($archivo); //?
		$this->sheet = $this->objPHPExcel->getSheet(0); //hoja
		$this->highestRow = $this->sheet->getHighestRow(); //dice cuantas filas son, ejemplo 2
		$this->highestColumn = $this->sheet->getHighestColumn(); //dice hasta que campos llega, ejemplo hasta la E
		$this->highestColumnIndex = PHPExcel_Cell::columnIndexFromString($this->highestColumn); // transforma el campo E, que seria el total a numero
		//Realiza el llamado a la funcion contarColumnas
		$this->resultadoContColumnas=$this->contarColumnas($this->highestColumnIndex);
		$datos=$_POST;//$_POST es para pasarle el IdArchivo que viene desde el HTML.
		$this->importarBD->contarColumnasTabla($datos,$dt);//Realiza el llamado al SP de importarBD 
		$this->total= $dt->data[0]["total"];//para rescatar una variable de la base de datos
		
		//A2022================================================================================
		if( $this->highestRow > 3001 ){
			print "Error, recuerde que no puede generar mas de 3000 documentos por proceso, favor revise y suba nuevamente.";
			if (file_exists($archivo))
			{
				unlink($archivo);
			}
			exit;
		}
		//A2022================================================================================
	}

	//cuenta la cantidad de columnas del archivo  que vengan con datos
	private function contarColumnas($cantidadcolumnas)
	{
		$cantidad = 0;

		for ($col = 0; $col < $cantidadcolumnas; $col++) //va recorriendo celda por celda
		{
			$colString = PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
			$columnaFila = $colString."1";
			$valor = $this->sheet->getCell($columnaFila)->getValue();

			if ($valor != "")
			{
				$cantidad++;
			}
		}
		return $cantidad;
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

	private function graba_log_curl ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logCURL'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);
	}
}
?>