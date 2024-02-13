<?php
error_reporting(E_ERROR);
// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/importarBD.php");

class importarMasivo 
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
	}
	  
	private function ProcesarScript($arr_CamposTabla,$insert,$idContrato)
	{
		$strinsert 		= "";
		$encontrados 	= 0;
		$cantidadcolumnas= count($this->valorCelda);
		$tipotransaccion = "";
		
		$strselect.=$select;
		$strinsert.=$insert.$idContrato;
		$strupdate.=$update;
		
		for($col = 0; $col <= $cantidadcolumnas-1; $col++)
		{
			if( $arr_CamposTabla->data[$col]["GuardaEnSql"] == 1 )
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
				$strinsert.=",";
			}
		}
		
		$strinsert = substr($strinsert, 0, -1);
		$strinsert.= ")";
		
		$this->importarBD->Grabar($strinsert);
		$tipotransaccion = "Nuevo Registro";
		
		$this->mensajeError.=$this->importarBD->mensajeError;
		if($this->mensajeError!="")
		{
			$tipotransaccion = "";
		}
		
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

			if ($valor != "")
			{
				$cantidad++;
			}
		}
		return $cantidad;
	}

	private function validarInfoConfiguracion($arr_CamposTabla,$arr_valorCelda,&$mensajeError, &$vacio)
	{
		$error = 0;
		//$cantidadcolumnas= count($this->valorCelda);
		$cantidadcolumnas= count($arr_valorCelda);
		$this->vacio = 0;
		$columnareal = 0;
		$this->strSql="";
		
		for($col = 0; $col <= $cantidadcolumnas-1; $col++)
		{
			$columnareal++;
			//rescatando tipoDato de la tabla
			$TipoDato = $arr_CamposTabla->data[$col]["TipoDato"];
			$Ancho = $arr_CamposTabla->data[$col]["Ancho"];
			$Obligatorio = $arr_CamposTabla->data[$col]["Obligatorio"];
			$GuardaEnSql = $arr_CamposTabla->data[$col]["GuardaEnSql"];

			//rescata valor celda
			$valorCelda = $arr_valorCelda[$col];

			//cuenta valor celda vacia
			if (trim($valorCelda) == "")
			{
				$this->vacio++;
				$vacio = $this->vacio;
			}
			
			//valida tipodato true false
			$resultadoTipoDato = $this->validaTipoDato($valorCelda,$TipoDato);

			//validacion tipo dato
			if( !$resultadoTipoDato)
			{
				//si es distinto a espacio
				if (trim($valorCelda) != "")
				{
					$this->mensajeError.=" tipo de dato en columna ".$columnareal;
					$mensajeError .= $this->mensajeError;
					$error++;
				}
			}

			 //valida ancho celda
			$resultadoAnchoCelda = $this->validaAnchoCelda($valorCelda,$Ancho);
			
			if( !$resultadoAnchoCelda )
			{
			  $this->mensajeError.=" excede largo en columna ".$columnareal;
			  $mensajeError .= $this->mensajeError;
			  $error++;
			}

			//valida si es dato obligatorio
			$resultadoDatoObligatorio = $this->validarDatoObligatorio($valorCelda,$Obligatorio);

			if($resultadoDatoObligatorio == true)
			{
				//si nos fue bien en la validacion de obligatorio y viene en blanco dejamos el valor de la en NULL
				if (strlen ($arr_valorCelda[$col]) < 1)
				{
					$arr_valorCelda[$col] = "NULL";
				}
			}
			else
			{
				$this->mensajeError.=" dato requerido en columna ".$columnareal;
				$mensajeError . $this->mensajeError;
				$error++;
			}
		}
		 
		//devuelve como nos fue en esta funcion
		if ($error == 0)
		{
			return true;
		}
		else
		{	
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

		if ($Obligatorio == 1 && $valorCelda == "")
		{
			return false;
		}
	   
		return true;
	}

	private function graba_log($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimp'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	private function graba_log_resultado($mensaje)
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