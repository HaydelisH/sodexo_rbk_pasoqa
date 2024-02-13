<?php
error_reporting(E_ERROR);
// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/importarBD.php");
// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');
$page = new importar();

class importar 
{
  // Para armas la pagina
  private $pagina;
  // para la conexion a la base de datos
  private $bd;
  // para el manejo de las tablas
  private $importarBD;
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

  // funcion contructora, al instanciar
  function __construct()
  {
    // creamos una instacia de la base de datos
    $this->bd = new ObjetoBD();
    $this->pagina = new Paginas();
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

	// instanciamos del manejo de tablas
	  $this->importarBD = new importarBD();

	// si se pudo abrir entonces usamos la conecion en nuestras tablas  
	$conecc = $this->bd->obtenerConexion();
	$this->importarBD->usarConexion($conecc);

	$dt = new DataTable();

	if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
	{
		$fileName = $_SERVER['HTTP_X_FILE_NAME'];
		$contentLength = $_SERVER['CONTENT_LENGTH'];
	} else throw new Exception("Error al recuperar encabezados");

	$path = 'tmp/';

	if (!$contentLength > 0) 
	{
		throw new Exception('Ningún archivo cargado');
	}
		
    //is_dir pregunta si existe el directorio
    //mkdir crea la carpeta
    if (!is_dir($path))
    {

		if(!mkdir($path, 0777, true)) 
		{       
			$mensaje = 'Error al crear carpeta temporal del proceso';
			exit;
		}
    }

    $trozos = explode(".", $fileName); //$fileName nombre del archivo
    $extension = end($trozos); 
    $extension = strtolower($extension); //strtolower -> Devuelve string con todos los caracteres alfabéticos convertidos a minúsculas.

    // mostramos la extension del archivo
    if ($extension != "xlsx" && $extension !="xls")
    {
		print ("Error, solo se pueden subir archivos Excel!");

        exit;
    }
     
	file_put_contents(
          $path . $fileName,
          file_get_contents("php://input")
	);
	
      // le otorga un atributo de lectura con 0777
	chmod($path.$fileName, 0777);

	//para archivo Excel
	$archivo = $path . $fileName; // se pasan los datos del archivo y el nombre de la carpeta donde este guardado.
	$this->inputFileType = PHPExcel_IOFactory::identify($archivo); //le pasa el contenido del documento y dice que es extencion excel.
	$this->objReader = PHPExcel_IOFactory::createReader($this->inputFileType); //?
	$this->objPHPExcel = $this->objReader->load($archivo); //?
	$this->sheet = $this->objPHPExcel->getSheet(0); //hoja

	$highestRow = $this->sheet->getHighestRow(); //dice cuantas filas son, ejemplo 2


	$highestColumn = $this->sheet->getHighestColumn(); //dice hasta que campos llega, ejemplo hasta la E
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // transforma el campo E, que seria el total a numero


	//Realiza el llamado a la funcion contarColumnas
	$resultadoContColumnas=$this->contarColumnas($highestColumnIndex);
	$this->graba_log('resultado cuenta Columnas:'.$resultadoContColumnas);

	$datos=$_REQUEST;//$_REQUEST es para pasarle el IdArchivo que viene desde el HTML.
	$this->importarBD->contarColumnasTabla($datos,$dt);//Realiza el llamado al SP de importarBD 
	$this->total= $dt->data[0]["total"];//para rescatar una variable de la base de datos
    //$this->graba_log('tota columnas configuracion:'.$this->total);

	if($resultadoContColumnas != $this->total)
	{
		echo("Error, la cantidad de columnas del archivo importado no corresponde a la configuracion");
		exit;
	}

	$this->importarBD->obtenerConfimpArchivo($datos,$dt3);
	$camposArchivo=$dt3; //trae los datos de la tabla configuracion

	//comienza el armado de las consultas
	$this->strSqlIniSelect = "SELECT * FROM  ".$camposArchivo->data[0]["Entidad"];
	$this->strSqlIniInsert = "INSERT INTO ".$camposArchivo->data[0]["Entidad"]."(";
	$this->strSqlIniUpdate = "UPDATE ".$camposArchivo->data[0]["Entidad"]." SET ";
	
	//obtenemos informacion del detalle de la configuracion;
	$this->importarBD->obtenerConfimpArchivoDet($datos,$dt2);
	$camposArchivoDet=$dt2; //datos de la tabla confiDet
		
	//se continua con la creacion del script para insertar y aprovechamos de grabar los campos en caso de actualizar y los campos llaves

	for( $c= 0; $c < count($camposArchivoDet->data); $c++)
	{
	//$this->graba_log('GuardaEnSql:'.$camposArchivoDet->data[$c]["GuardaEnSql"]);
	
	  
		$campostabla[$c] = $camposArchivoDet->data[$c]["Nombre"];
		
		//para obtener los campos llaves para el select
		$esllave =  $camposArchivoDet->data[$c]["EsLlave"];
		
		if ($esllave == 1)
		{
			//$this->graba_log('c2:'.$c.' '.'esllave:'.$esllave." ".$campostabla[$f]);
			$this->Llave[$c] = $camposArchivoDet->data[$c]["Nombre"];
		}
	//$this->graba_log('esllave:'.$esllave." ".$campostabla[$f]);
		
		//los campos de la tabla para utilizar en update
		$campostabla[$c] = $camposArchivoDet->data[$c]["Nombre"];
		
	if($camposArchivoDet->data[$c]["GuardaEnSql"] == 1)
	   {
			$this->strSqlIniInsert.=$camposArchivoDet->data[$c]["Nombre"].",";
		
		}
	
	}
	//para quitar ultima coma del script de insertar
	$this->strSqlIniInsert = substr($this->strSqlIniInsert, 0, -1);
	$this->strSqlIniInsert.=") values (";
	//$this->graba_log('inicio string insert:'.$this->strSqlIniSelect);
	
	
	//eliminamos resultado de carga por el codigo del usuario
    $datos["usuarioid"] = $this->seguridad->usuarioid; 
	$this->importarBD->eliminar($datos);

	for($fila=2; $fila <= $highestRow; $fila++)// se recorre segun la cantidad de filas
	{
		//$this->graba_log('fila :'.$fila);	 
		for ($col = 0; $col < $resultadoContColumnas; $col++)//va recorriendo celda por celda
		{ 
			$colString 				= PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
			$columnaFila 			= $colString.$fila;
			$this->valorCelda[$col] = $this->sheet->getCell($columnaFila)->getValue();
			//$this->graba_log('prueba valor resultado :'.$this->valorCelda[$col]);
		}

		//valida configuracion según validaciones del detalle de la tabla
		$respuestaValidacion = $this->validarInfoConfiguracion($camposArchivoDet);
		
		//$this->graba_log('resp validacion:'.$respuestaValidacion);
		//si las columnas estan vacias llegamos al fin del excel
		if ($this->vacio == $resultadoContColumnas)
		{
			//$this->graba_log('cantidad de celdas vacias:'.$this->vacio);
			break;// se supone que sale del ciclo y no continua la linea siguiente
		}
 		
 		$tipotransaccion = "";
		//$this->graba_log('resp validacion 2:'.$respuestaValidacion);
		if ($this->mensajeError == "")//revisar
		{	
			$tipotransaccion = $this->ProcesarScript($camposArchivoDet);
		}
		
		//aqui se deduce como nos va en cada fila ok o error
		if ($this->mensajeError == "")
		{
			$resultado= "OK";
		}
		else
		{
			$resultado= "ERROR";
		}
		
		//grabamos el resultado de la importacion
		$datos["fila"] = $fila;
		$datos["resultado"] = $resultado;
		//remplazar
		$this->mensajeError=str_replace("'", "`", $this->mensajeError);
		$this->mensajeError=str_replace(",", ";", $this->mensajeError);

		$datos["observaciones"] = substr($this->mensajeError,0,499);
		$datos["tipotransaccion"] = $tipotransaccion;
		$this->importarBD->agregar($datos);
		
		
		//$this->graba_log_resultado($resultado);
	}
  }
  
  
private function ProcesarScript($arr_CamposTabla)
{
	
	
	$strselect 		= "";
	$strupdate 		= "";
	$strinsert 		= "";
	$strcondicion 	= "";
	$encontrados 	= 0;
	$cantidadcolumnas= count($this->valorCelda);
	$tipotransaccion = "";
	
	$strselect.=$this->strSqlIniSelect;
	$strinsert.=$this->strSqlIniInsert;
	$strupdate.=$this->strSqlIniUpdate;
	for($col = 0; $col <= $cantidadcolumnas-1; $col++)
	{
				//$this->graba_log('tipo dato:'.$arr_CamposTabla->data[$col]["TipoDato"]);
				//para armar la condicion para select y update
//$this->graba_log('col:'.$col.' '.'Llave[$col]:'.$this->Llave[$col]." ".'CamposTabla:'.$arr_CamposTabla->data[$col]["Nombre"]." ".'valor celda'.$this->valorCelda[$col]);

				if ($this->Llave[$col] != "")
				{
					//$this->graba_log('Llave[$col]2:'.$this->Llave[$col]);
					//$this->graba_log('encontrado1:'.$encontrados);
					if ($encontrados == 0)
					{	//$this->graba_log('encontrado2:'.$encontrados);
						if ($arr_CamposTabla->data[$col]["TipoDato"] == 1)
						{
							$strcondicion.= " WHERE ".$arr_CamposTabla->data[$col]["Nombre"]." = '".$this->valorCelda[$col]."'";
						}
						else
						{
							$strcondicion.= " WHERE ".$arr_CamposTabla->data[$col]["Nombre"]." = ".$this->valorCelda[$col];
						}
						//$this->graba_log('strcondicion:'.$strcondicion);
					}
					else
					{	
						//$this->graba_log('encontrado3:'.$encontrados);
						if ($arr_CamposTabla->data[$col]["TipoDato"] == 1)
						{
							$strcondicion.= " AND ".$arr_CamposTabla->data[$col]["Nombre"]." = '".$this->valorCelda[$col]."'";
						}
						else
						{
							$strcondicion.= " AND ".$arr_CamposTabla->data[$col]["Nombre"]." = ".$this->valorCelda[$col];
						}
					}
					
					$encontrados++;
				}
				else
				{
					if($arr_CamposTabla->data[$col]["GuardaEnSql"]== 1)
					{
					//para armar parte del script del update 
						if ($arr_CamposTabla->data[$col]["TipoDato"] == 1)
						{
							if($this->valorCelda[$col] == "NULL")
							{
								$strupdate.= $arr_CamposTabla->data[$col]["Nombre"]." = ".$this->valorCelda[$col];
							}
							else
							{
								$strupdate.= $arr_CamposTabla->data[$col]["Nombre"]." = '".$this->valorCelda[$col]."'";
							}
						}
						else
						{
							$strupdate.= $arr_CamposTabla->data[$col]["Nombre"]." = ".$this->valorCelda[$col];
						}
						
						$strupdate.=",";
					}
				}
				
				
			if($arr_CamposTabla->data[$col]["GuardaEnSql"]== 1)
			{
				//para armar parte del script del insert  
				if ($arr_CamposTabla->data[$col]["TipoDato"] == 1)
				{
					if($this->valorCelda[$col] == "NULL")
					{
						$strinsert.= $this->valorCelda[$col];
					}
					else
					{
						$strinsert.= "'".$this->valorCelda[$col]."'";
					}
				}
				else
				{
					$strinsert.= $this->valorCelda[$col];
					
				}
				
				//$this->graba_log('strinsert:'.$strinsert);
				$strinsert.=",";
			}
			
	}
	
	
	$strselect.= $strcondicion;
	
	$strupdate = substr($strupdate, 0, -1);
	$strupdate.= $strcondicion;
	
	$strinsert = substr($strinsert, 0, -1);
	$strinsert.= ")";
	
	$this->importarBD->Consultar($strselect,$dt);
	$this->mensajeError.=$this->importarBD->mensajeError;
	if($dt->leerFila())
	{
		$this->importarBD->Grabar($strupdate);
		$tipotransaccion = "Registro Actualizado";
	}
	else
	{
		$this->importarBD->Grabar($strinsert);
		$tipotransaccion = "Nuevo Registro";
	}

	$this->mensajeError.=$this->importarBD->mensajeError;
	if($this->mensajeError!="")
	{
		$tipotransaccion = "";
	}
	
	$this->graba_log('script select:'.$strselect);
	$this->graba_log('script update:'.$strupdate);
	$this->graba_log('script insert:'.$strinsert);
	
	return $tipotransaccion;

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
		//$this->graba_log('valor columnas:'.$valor."-");

		if ($valor != "")
		{
			$cantidad++;
		}
	}
	return $cantidad;
}



private function validarInfoConfiguracion($arr_CamposTabla)
{
	$error = 0;
	$cantidadcolumnas= count($this->valorCelda);
	$this->vacio = 0;
	$this->mensajeError = "";
	$columnareal = 0;
	$this->strSql="";
	//$this->graba_log("cant col :".$cantidadcolumnas);
	
	for($col = 0; $col <= $cantidadcolumnas-1; $col++)
	{
		
		$columnareal++;
		//rescatando tipoDato de la tabla
		$TipoDato = $arr_CamposTabla->data[$col]["TipoDato"];
		$Ancho = $arr_CamposTabla->data[$col]["Ancho"];
		$Obligatorio = $arr_CamposTabla->data[$col]["Obligatorio"];
		$GuardaEnSql = $arr_CamposTabla->data[$col]["GuardaEnSql"];
		//$this->graba_log("validaciones :".$TipoDato." ".$Ancho." ".$Obligatorio);

		//rescata valor celda
		$valorCelda = $this->valorCelda[$col];
		//$this->graba_log("valor celda :".$valorCelda);

		//cuenta valor celda vacia
		if (trim($valorCelda) == "")
		{
		  $this->vacio++;
		}
		
		//valida tipodato true false
		$resultadoTipoDato = $this->validaTipoDato($valorCelda,$TipoDato);
		//$this->graba_log("tipo dato :".$TipoDato." "."Celda :".$valorCelda." true o false".$resultadoTipoDato);
		//validacion tipo dato
		if($resultadoTipoDato == true)
		{
			//$this->graba_log("tipo dato v2:".$TipoDato." "."Celda :".$valorCelda." - "."ok");
		}
		
		else
		{
			//si es distinto a espacio
			if (trim($valorCelda) != "")
			{
				$this->mensajeError.=" tipo de dato en columna ".$columnareal;
				//$this->graba_log("tipo dato :".$TipoDato." "."Celda :".$valorCelda." - "."error");
				$error++;

			}
			

		}

		 //valida ancho celda
		$resultadoAnchoCelda = $this->validaAnchoCelda($valorCelda,$Ancho);
		//$this->graba_log("ancho :".$Ancho." "."Celda :".$valorCelda." true o false".$resultadoAnchoCelda);
		if($resultadoAnchoCelda == true)
		{
		  //$this->graba_log("Ancho :".$Ancho." "."Celda :".$valorCelda."-"."ok");
		}
		else
		{
		  $this->mensajeError.=" excede largo en columna ".$columnareal;
		  //$this->graba_log("Ancho :".$Ancho." "."Celda :".$valorCelda."-"."error");
		  $error++;
		}

		//valida si es dato obligatorio
		$resultadoDatoObligatorio = $this->validarDatoObligatorio($valorCelda,$Obligatorio);
		//$this->graba_log("1-valor celda :".$this->valorCelda[$col]);
		if($resultadoDatoObligatorio == true)
		{
			//$this->graba_log("2-valor celda :".$this->valorCelda[$col]);
			//si nos fue bien en la validacion de obligatorio y viene en blanco dejamos el valor de la en NULL
			if (strlen ($this->valorCelda[$col]) < 1)
			{
				//$this->graba_log("3-valor celda :".$this->valorCelda[$col]);
				$this->valorCelda[$col] = "NULL";
				//$this->graba_log("4-valor celda :".$this->valorCelda[$col]);
			}
		}
		else
		{
			$this->mensajeError.=" dato reqerido en columna ".$columnareal;
			$error++;
		}
	}
	 
	//devuelve como nos fue en esta funcion
	if ($error == 0)
	{
		//$this->graba_log("return true");
		return true;
	}
	else
	{	
		//$this->graba_log("return false");
		return false;
	}

}

private function validaTipoDato($valorCelda,$TipoDato)
{
	if($TipoDato == 1)
	{
		if(!is_string($valorCelda)) 
		{
			return False;
		}
		return True;
	}
  
	if($TipoDato == 2)
    {
		if(is_numeric($valorCelda) )
		{
			return true;
		}
		return false;
    }

    return false;

}

private function validaAnchoCelda($valorCelda,$Ancho)
{
	$str = $valorCelda;
	$strvalidar = trim($str);//quitamos los espacios de inicio y final que contiene cada columna

	$intvalidar = strlen($strvalidar);//cuenta cuantas letras contiene cada columna y muestra
	//print($intvalidar);
  
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


private function validarDatoObligatorio($valorCelda,$Obligatorio)
{
	$valorCelda = trim($valorCelda);
	//print($DatoObligatorio);

	if ($Obligatorio == 1 && $valorCelda == "")
	{
		return false;
    }
   
	return true;
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

}
?>