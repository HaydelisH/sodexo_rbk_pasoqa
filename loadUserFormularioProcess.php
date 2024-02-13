<?php
error_reporting(E_ERROR);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/usuariosBD.php");
include_once("includes/importarBD.php");
include_once("includes/respuesta_importarBD.php");
include_once("includes/parametrosBD.php");
include_once("includes/formularioPlantillaBD.php");
require_once('generar.php');
//include_once("includes/centroscostoBD.php");

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

// creamos la instacia de esta clase
$importar = new importar();
class importar
{
    // Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $respuesta_importarBD;
	private $parametrosBD;
	private $formularioPlantillaBD;

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
	private $nombreNemotecnicoArchivo = 'formulario_';

	private $contIntentosCurl = 5;

	private $IdArchivo = null;

	function __construct()
	{
		$datos = $_REQUEST;
		$this->IdArchivo = $datos['IdArchivo'];
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
		if ($datos['accion'] == 'LOAD' || $datos['accion'] == 'LOOP0' || !isset($datos['accion']) || $datos['accion'] == 'KILL' || $datos['accion'] == 'UNO' || $datos['accion'] == 'ESTADO')
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
		$this->formularioPlantillaBD = new formularioPlantillaBD();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		$this->importarBD->usarConexion($conecc);
		$this->respuesta_importarBD->usarConexion($conecc);
		$this->parametrosBD->usarConexion($conecc);
		$this->formularioPlantillaBD->usarConexion($conecc);
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
			case 'UNO':
			{
				$this->procesarUno();
				break;
			}
			case 'ESTADO':
			{
				$this->getEstadoSubida();
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
		$datos = $_REQUEST;
		$datos["usuarioid"] = $this->seguridad->usuarioid; 
		$this->importarBD->eliminar($datos);
        echo json_encode(array('estado'=>$this->highestRow - 1));
	}

	private function getEstadoSubida()
	{
        if ($this->procesoActivo())
        {
            $this->getDataGrilla();
			//$formulario[0]['highestRow'] = $importar->highestRow - 1;
			echo json_encode(array('highestRow'=>$this->highestRow - 1));
        }
		else
		{
			$formulario[0]['highestRow'] = '';
			echo json_encode(array('highestRow'=>''));
		}
	}

	public function procesoActivo()
	{
		$this->setNombreFichero();
		return file_exists($this->path . $this->nombreArchivoSubida);
	}

	private function setNombreFichero()
	{
		$datos = $_REQUEST;
		$datos['usuarioid'] = isset($this->seguridad->usuarioid) ? $this->seguridad->usuarioid : $datos['usuarioid'];
		$this->nombreArchivoSubida = "{$this->nombreNemotecnicoArchivo}{$this->IdArchivo}_{$datos['usuarioid']}";//.{$this->nombreExtensionArchivoSubida}";
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
		$datos=$_REQUEST;//$_REQUEST es para pasarle el IdArchivo que viene desde el HTML.
		$this->importarBD->contarColumnasTabla($datos,$dt);//Realiza el llamado al SP de importarBD 
		$this->total= $dt->data[0]["total"];//para rescatar una variable de la base de datos
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

	private function procesar()
	{
		set_time_limit(0);
		$filaActual = (isset($_REQUEST['fila']) ? $_REQUEST['fila'] : 2);
		$tiempoInicial = time();
		//sleep(298);
        $datos = $_REQUEST;
		$datos['usuarioid'] = isset($this->seguridad->usuarioid) ? $this->seguridad->usuarioid : $datos['usuarioid'];
		/*$this->respuesta_importarBD->cuenta(array(
			'usuarioingid'=>$datos['usuarioid'],
			'IdArchivo'=>$datos['IdArchivo']
		),$dt);*/
		$this->setNombreFichero();
		$this->getDataGrilla();
		$this->matchColumn();
		for($fila = $filaActual; $fila <= $this->highestRow; $fila++)
		{
			$this->graba_log_curl('INICIO FILA PROCESADA ' . $fila);
			$this->mensajeError = '';
            $data = array();
			for($col = 0; $col <= $this->highestColumnIndex - 1; $col++)
			{
                $colString 				= PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
				$columnaFila 			= $colString.$fila;
				$valorCelda = $this->sheet->getCell($columnaFila)->getValue();
				for( $c= 0; $c < count($this->camposArchivoDet->data); $c++)
				{
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
								//A2021
								date_default_timezone_set('America/Santiago'); 
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
						$data[$this->camposArchivoDet->data[$c]['Nombre']] = $valorCelda;
					}
                }
			}
			if ($this->mensajeError == '')
			{
				$this->mensajeError = '';
				//var_dump($datos);
				// Aplico el orden de los firmantes si aplica
				if (isset($datos['Firmantes_Emp']))
				{
					if (count($datos['Firmantes_Emp']) > 1)
					{
						$d['Firmantes_Emp'] = array();
						for ($i = 0; $i < count($datos['Firmantes_Emp']); $i++)
						{
							$d['Firmantes_Emp'][$datos["orden_{$datos['Firmantes_Emp'][$i]}"] - 1] = $datos['Firmantes_Emp'][$i];
						}
						for ($i = 0; $i < count($d['Firmantes_Emp']); $i++)
						{
							$data['Firmantes_Emp'][$i] = $d['Firmantes_Emp'][$i];
						}
					}
					else{
						$data['Firmantes_Emp'] = $datos['Firmantes_Emp'];
					}
				}
				//var_dump($data['Firmantes_Emp']);
                $data['RutEmpresa'] = $datos['RutEmpresa'];
                //$data['LugarPagoid'] = $datos['LugarPagoid'];
                //$data['idCentroCosto'] = $datos['idCentroCosto'];
				//$data['idCargoEmpleado'] = $datos['idCargoEmpleado'];
				$data['idFormulario'] = $datos['idFormulario'];
				$respuesta = $this->ProcesarExcel($data);
				//Guardamos el resultado de carga
				$dat = array(
					'usuarioid'=>$datos['usuarioid'],
					'fila'=>$fila,
					'resultado'=>($respuesta['estado'] ? 'OK' : 'ERROR'),
					'observaciones'=>html_entity_decode($respuesta['mensaje']),
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
					'tipotransaccion'=>'Nuevo Registro',
					'IdArchivo'=>$datos['IdArchivo']
				);
			}
			$this->importarBD->agregar($dat);
			$this->graba_log_curl('FIN FILA PROCESADA ' . $fila);
			if (time() -  $tiempoInicial > LIMITE_PROCESA_EXCEL * 60)
			{
				$this->graba_log_curl('ultima fila '.$fila.' usuario '.$datos['usuarioid']);
				$datos['fila'] = $fila + 1;
				$this->llamadaCurl($datos);
				exit;
			}
        }
		unlink($this->path.$this->nombreArchivoSubida);
	}

	private function llamadaCurl($datos)
	{
		$this->graba_log_curl("LIMITE PROCESA_EXCEL ".LIMITE_PROCESA_EXCEL." usuario ".$datos['usuarioid']);
		$limite_adicional = LIMITE_PROCESA_EXCEL + 1;//para que el curl espere 1 minuto mas de lo configurado para generar 
		//$this->graba_log_curl("limite adicional ".$limite_adicional);
		$curl_limite = $limite_adicional * 60;

		$this->parametrosBD->obtener(array('idparametro'=>'url_curl'),$dt2);
		$this->mensajeError.=$this->parametrosBD->mensajeError;
		$url= (isset($dt2->data[0]['parametro']) ? $dt2->data[0]['parametro'] : "http://localhost/SMU_RBK") . "/loadUserFormularioProcess.php";
		$this->graba_log_curl("url:".$url." usuario ".$datos['usuarioid']);
		$parametros = "fila={$datos['fila']}&usuarioid={$datos['usuarioid']}&idFormulario={$datos['idFormulario']}&IdArchivo={$datos['IdArchivo']}&accion=LOOP";
		
		
		
		
		$this->graba_log_curl("parametros:".$parametros);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $parametros);
		//curl_setopt($ch, CURLOPT_FAILONERROR, true);
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT,  $curl_limite);
		if( curl_exec($ch) === false )
		{
			$intentos++;
			$this->graba_log_curl("Curl : ".curl_errno($ch)." ".curl_error($ch)." intentos:".$intentos." usuario ".$datos['usuarioid']);
			if ($intentos < $this->contIntentosCurl)
			{
				$this->graba_log_curl(" Reintento Curl ".$intentos." usuario ".$datos['usuarioid']);
				$this->contIntentosCurl++;
				$this->llamadaCurl($datos);
			}

		}

		$this->graba_log_curl("Curl OK, reintentos:".$intentos.", usuario:".$datos['usuarioid']);
		curl_close($ch);
	}
	
    private function procesarUno()
    {
        $datos = $_REQUEST;
        $datos['usuarioid'] = $this->seguridad->usuarioid;
        $respuesta = $this->ProcesarExcel($datos);
        $dat = array(
            'usuarioid'=>$datos['usuarioid'],
            'fila'=>0,
            'resultado'=>($respuesta['estado'] ? 'OK' : 'ERROR'),
            'observaciones'=>html_entity_decode($respuesta['mensaje']),
            'tipotransaccion'=>'Nuevo Registro',
            'IdArchivo'=>$datos['IdArchivo']
        );
        $this->importarBD->eliminar($datos);
        $this->importarBD->agregar($dat);
        echo json_encode(array(
            'exito'=>true,
            'mensaje'=>$respuesta['mensaje']
        ));
    }

	private function ProcesarExcel($data)
    {
		//var_dump(isset($data['Firmantes_emp']));
		$respuesta['estado'] = true;
		$respuesta['mensaje'] = '';
		//var_dump($data);	// proceso excel	array(2) { ["rut"]=> string(10) "11457163-6" ["idFormulario"]=> string(1) "1" }
		  					// solo uno 		array(5) { ["accion"]=> string(3) "UNO" ["IdArchivo"]=> string(1) "2" ["rut"]=> string(10) "12634720-0" ["idFormulario"]=> string(1) "1" ["usuarioid"]=> string(10) "26131316-2" } 
		$this->formularioPlantillaBD->existe($data, $dt);
        $this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
        if ($this->mensajeError != '')
        {  
			$respuesta['estado'] = false;
            $respuesta['mensaje'] = $this->mensajeError;
        }
        else
        {
			$this->formularioPlantillaBD->getFormularioPlantilla($data, $dtt);
			$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
			if ($this->mensajeError != '')
			{  
				$respuesta['estado'] = false;
				$respuesta['mensaje'] = $this->mensajeError;
			}
			else
			{
				$this->mensajeError = '';
				// Aqui se validan los campos a cargar
				$data['idPlantilla'] = $dtt->data[0]['idPlantilla'];
				$data['idProceso'] = PROCESO_FORMULARIOS;
				$data['newusuarioid'] = $data['rut'];
				$generar = new generar();
				if (isset($data['Firmantes_Emp']))
				{
					$respuesta = $generar->validaFirmanteEmpleado($data);
				}
				if ($respuesta['estado']){
					$respuesta = $generar->validarDatosDocumento($data);
					if( $respuesta['estado'] ){
						$respuesta = $generar->validarDatosVariables($data);
						if ( $respuesta['estado'] ){
							$respuesta = $generar->validarDatosEmpleado($data);
						}
					}
				}
				//var_dump($respuesta['estado']);
				if ($respuesta['estado'])//$this->mensajeError == '')
				{
					$this->formularioPlantillaBD->carga($data, $dt);
					$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
					if ($this->mensajeError != '')
					{
						$respuesta['estado'] = false;
						$respuesta['mensaje'] = $this->mensajeError;
					}
					else
					{
						$data['empleadoFormularioid'] = $dt->data[0]['empleadoFormularioid'];
						$this->formularioPlantillaBD->getByEmpleadoFormularioid($data, $dt);
						$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
						switch ($dt->data[0]['revisaOrigenEstructuraData'])
						{
							case 1: // Caso practico: formulario conflictos de interes en melon_rbk_con
							break;
							case 0: // Caso practico: conflictos de interes (Email) | conflictos de interes (ADC) | conflictos de interes (Above site)
								if (isset($data['Firmantes_Emp']))
								{
									$data['LeFirmantes'] = json_encode($data['Firmantes_Emp']);
								}
								//var_dump($aux);
								$this->formularioPlantillaBD->cargaDatosVariables($data, $dt);
								$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
								$generar = new generar();
								$respuesta = $generar->agregarEmpleado($data);
								/*echo '<p>';
								var_dump($respuesta);
								echo '<p>';*/
								/*if( $respuesta['estado'] === false )
								{
									$respuesta['estado'] = false;
									$respuesta['mensaje'] = $this->mensajeError;
								}*/
							break;
						}
						if ($this->mensajeError != '')
						{
							$respuesta['estado'] = false;
							$respuesta['mensaje'] = $this->mensajeError;
						}
						else
						{
							$respuesta['estado'] = true;
							$respuesta['mensaje'] = 'Se realizo la asignacion de formulario al R.U.T. ' . $data['rut'];
						}
					}
				}
				/*else
				{
					$respuesta['estado'] = false;
					$respuesta['mensaje'] = $this->mensajeError;
				}*/
			}
		}
        return $respuesta;
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
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	private function is_valid_email($str)
	{
		return (false !== strpos($str, "@") && false !== strpos($str, "."));	 
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

    private function matchColumn()
	{
		$datos = $_REQUEST;
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
			}
		}
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