<?php
error_reporting(E_ERROR);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/importarBD.php");
include_once("includes/documentosBD.php");
include_once("includes/empresasBD.php");
//include_once("includes/accesodocxperfilBD.php");
include_once("includes/ContratosDatosVariablesBD.php");
include_once("includes/empleadosBD.php");
include_once("includes/subclausulasBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/lugarespagoBD.php");
require_once('generar.php');
require_once('Config.php');  

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$page = new importar();

class importar 
{
	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $importarBD;
	private $documentosBD;
	private $empresasBD;
	//private $accesodocxperfilBD;
	private $ContratosDatosVariablesBD;
	private $empleadosBD;
	private $subclausulasBD;
	private $centroscostoBD;
	private $lugarespagoBD;

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

  // funcion contructora, al instanciar
  function __construct()
  {
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
	$this->documentosBD = new documentosBD();
	$this->ContratosDatosVariablesBD = new ContratosDatosVariablesBD();
	$this->empresasBD = new empresasBD();
	//$this->accesodocxperfilBD = new accesodocxperfilBD();
	$this->empleadosBD = new empleadosBD();
	$this->subclausulasBD = new subclausulasBD();
	$this->centroscostoBD = new centroscostoBD();
	$this->lugarespagoBD = new lugarespagoBD();

	// si se pudo abrir entonces usamos la conecion en nuestras tablas  
	$conecc = $this->bd->obtenerConexion();
	$this->importarBD->usarConexion($conecc);
	$this->documentosBD->usarConexion($conecc);
	$this->ContratosDatosVariablesBD->usarConexion($conecc);
	$this->empresasBD->usarConexion($conecc);
	//$this->accesodocxperfilBD->usarConexion($conecc);
	$this->empleadosBD->usarConexion($conecc);
	$this->subclausulasBD->usarConexion($conecc);
	$this->centroscostoBD->usarConexion($conecc);
	$this->lugarespagoBD->usarConexion($conecc);
	
	$dt = new DataTable();

	if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) 
	{
		$fileName = $_SERVER['HTTP_X_FILE_NAME'];
		$contentLength = $_SERVER['CONTENT_LENGTH'];
	} else throw new Exception("Error al recuperar encabezados");

	$path = 'tmp/';

	if (!$contentLength > 0) 
	{
		throw new Exception('Ning�n archivo cargado');
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
    $extension = strtolower($extension); //strtolower -> Devuelve string con todos los caracteres alfab�ticos convertidos a min�sculas.

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

	$datos=$_REQUEST;//$_REQUEST es para pasarle el IdArchivo que viene desde el HTML.
	$this->importarBD->contarColumnasTabla($datos,$dt);//Realiza el llamado al SP de importarBD 
	$this->total= $dt->data[0]["total"];//para rescatar una variable de la base de datos
    
	if($resultadoContColumnas != $this->total)
	{
		echo("Error, la cantidad de columnas del archivo importado no corresponde a la configuracion");
		exit;
	}

	$this->importarBD->obtenerConfimpArchivo($datos,$dt3);
	$camposArchivo=$dt3; //trae los datos de la tabla configuracion
	
	//comienza el armado de las consultas
	$this->strSqlIniInsert = "INSERT INTO ".$camposArchivo->data[0]["Entidad"]."(idDocumento,";
	
	//obtenemos informacion del detalle de la configuracion;
	$this->importarBD->obtenerConfimpArchivoDet($datos,$dt2);
	$camposArchivoDet=$dt2; //datos de la tabla confiDet
	
	for( $c= 0; $c < count($camposArchivoDet->data); $c++)
	{
		$campostabla[$c] = $camposArchivoDet->data[$c]["Nombre"];
		
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

	//eliminamos resultado de carga por el codigo del usuario
    $datos["usuarioid"] = $this->seguridad->usuarioid; 
	$this->importarBD->eliminar($datos);

	for($fila=2; $fila <= $highestRow; $fila++)// se recorre segun la cantidad de filas
	{
		$tipotransaccion = '';
		for ($col = 0; $col < $resultadoContColumnas; $col++)//va recorriendo celda por celda
		{ 
			$colString 				= PHPExcel_Cell::stringFromColumnIndex($col);//tranforma de numero a letra
			$columnaFila 			= $colString.$fila;
			$this->valorCelda[$col] = $this->sheet->getCell($columnaFila)->getValue();
		}

		//Limpieza de caracteres no deceados antes de ejecutar validaciones
		$this->clearData($camposArchivoDet);

		//valida configuracion seg�n validaciones del detalle de la tabla
		$respuestaValidacion = $this->validarInfoConfiguracion($camposArchivoDet);

		//si las columnas estan vacias llegamos al fin del excel
		if ($this->vacio == $resultadoContColumnas)
		{
			break;// se supone que sale del ciclo y no continua la linea siguiente
		}
			
		if( $this->mensajeError == '' ){
			//valida empresa, centro de costo y lugar de pago 
			$empresa = $centrocosto = $lugarpago = '';
			
			$empresa = $_REQUEST['RutEmpresa']; 
			$lugarpago = $this->valorCelda[VAR_LUGARPAGO]; //Lugar de pago 
			$centrocosto = $this->valorCelda[VAR_CENTROCOSTO]; //Centro de costo
			
			$this->validarPerfilamiento($empresa,$centrocosto,$lugarpago);

			if ($this->mensajeError == "")//revisar
			{	
				$tipotransaccion = $this->ProcesarScript($camposArchivoDet);
			}

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
		//$this->mensajeError= "Favor contacte a Soporte, ".substr($this->mensajeError, 0, 50);
			
		$datos["observaciones"] = substr($this->mensajeError,0,499);
		
		if( $datos["observaciones"] == '' ){
			$datos["observaciones"] = "El contrato generado tiene como id :<b> ".$this->band ."</b>";
		}
		
		$datos["tipotransaccion"] = $tipotransaccion;
		$this->importarBD->agregar($datos);
	}
  }
	
private function ProcesarScript($arr_CamposTabla)
{	
	$datos = $_REQUEST;
	
	$strinsert 		= "";
	$strcondicion 	= "";
	$encontrados 	= 0;
	$cantidadcolumnas= count($this->valorCelda);
	$tipotransaccion = "";
	
	$strinsert.=$this->strSqlIniInsert;
	$idDocumento = $this->crearContrato();
	
	if( $idDocumento != 0 ){
		$strinsert.= $idDocumento.",";
	
		for($col = 0; $col <= $cantidadcolumnas-1; $col++)
		{
			//$this->graba_log($this->valorCelda[$col]);
			if($arr_CamposTabla->data[$col]["GuardaEnSql"]== 1)
			{
				//para armar parte del script del insert  
				if ($arr_CamposTabla->data[$col]["TipoDato"] != 2 )
				{
					if($this->valorCelda[$col] == "NULL")
					{
						$strinsert.= $this->valorCelda[$col];
					}
					else
					{	if( $arr_CamposTabla->data[$col]["TipoDato"] == 3 ){
							$this->valorCelda[$col] = date("Y-m-d", strtotime($this->valorCelda[$col]));
							//$this->valorCelda[$col] = date("d-m-Y", strtotime($this->valorCelda[$col]));
							//$newDate = date("d/m/Y", strtotime($originalDate));+
						}
				
				
				
						$strinsert.= "'".$this->valorCelda[$col]."'";
					}
				}
				else
				{
					$strinsert.= $this->valorCelda[$col];
				}
				
				$strinsert.=",";
			}
			
			//Aca guardo los datos del empleado
			switch ($col){
				case '0' : 
							$this->empleado['personaid'] = $this->valorCelda[$col]; 
							
							//Validar si firmante y empleado son iguales
							if( count($datos['Firmantes_Emp']) ){
							
								foreach( $datos['Firmantes_Emp'] As $key => $value ){
						
									if ( $this->empleado['personaid'] == $value ){
										$this->mensajeError = 'Error el Representante y el Empleado deben ser distintos, verifique los datos generaci&oacute;n';
									}
								}
							}
							break;
				case '1' : $this->empleado['nombre'] = $this->valorCelda[$col]; break;
				case '2' : $this->empleado['appaterno'] = $this->valorCelda[$col]; break;
				case '3' : $this->empleado['apmaterno'] = $this->valorCelda[$col]; break;
				case '4' : $this->empleado['nacionalidad'] = $this->valorCelda[$col]; break;
				case '5' : $this->empleado['fechanacimiento'] = $this->valorCelda[$col]; break;
				case '6' : $this->empleado['estadocivil'] = $this->valorCelda[$col]; break;
				case '7' : $this->empleado['direccion'] = $this->valorCelda[$col]; break;
				//Comuna y Ciudad
				case '8' : $this->empleado['comuna'] = $this->valorCelda[$col]; break;
				case '9' : $this->empleado['ciudad'] = $this->valorCelda[$col]; break;

				case '10' : $this->empleado['rolid'] = $this->valorCelda[$col]; break;
				case '11' : $this->empleado['correo'] = $this->valorCelda[$col]; break;
				case '12' : $this->empleado['estado'] = $this->valorCelda[$col]; break;
				//Cargo
				case VAR_CARGO : 
							$this->subclausulas[0]['idSubClausula'] = $this->valorCelda[$col]; 
							$this->subclausulas[0]['idTipoSubClausula'] = 3; //Cargos
							break;
				//Jornada
				case VAR_JORNADA : 
							$this->subclausulas[1]['idSubClausula'] = $this->valorCelda[$col]; 
							$this->subclausulas[1]['idTipoSubClausula'] = 2; //Jornadas
							break;
				
			}
		} 
	
		$strinsert = substr($strinsert, 0, -1);
		$strinsert.= ")";
		
		$this->importarBD->Grabar($strinsert);
		$tipotransaccion = "Nuevo Registro";
		$this->mensajeError.=$this->importarBD->mensajeError;

		if($this->mensajeError!="")
		{
			$tipotransaccion = $this->mensajeError;
		}
		else
		{
			//Agregar datos del empleado 
			
			$rut_arr 	= explode("-",$this->empleado['personaid']);
			$rut_sindv 	= $rut_arr[0];		
					
			$this->empleado["clave"] = hash('sha256', $rut_sindv);
			$this->empleado['TipoCorreo'] = CODIGO_CORREO_USUARIO_NUEVO;
			$this->empleado['TipoFirma'] = TIPO_FIRMA_EMPLEADO;
			$this->empleado['TipoUsuario'] = PERFIL_USUARIO;
					
			$this->empleadosBD->agregarConUsuario($this->empleado);

			//$this->empleadosBD->agregar($this->empleado);

			//Construir Firmantes 
			$this->firmantes_completos = array();
			$this->construirFirmantes($idDocumento,$this->empleado,$this->firmantes_completos);

			//Crear Plantillas 
			$this->construirPlantilla($idDocumento,$this->html);
			
			//Sustituir variables 
			$resultado_html = '';
			$datos2 = $_REQUEST;
			$this->sustituirVariables($idDocumento,$this->html,$datos2,$resultado_html); 
			
			//Si es de firma manual
			if( $datos["idFirma"] == 1 ){
				$this->construirTablaFirmantes($idDocumento,$this->firmantes_completos, $this->firmantes_tabla);
				//Unir texto y tabla de Firmantes	
				$resultado_html .= $this->firmantes_tabla;
			}

			$resultado_html .= "</body></html>";

			//Codificacion internacional del texto
			$texto = utf8_encode($resultado_html);

			//Sustituir acentos 
			$texto_completo = $this->TildesHtml($texto);

			//Generar PDF
			$this->generarPDF($idDocumento,$texto_completo); 

			//Guardar registro del archivo codificado
			$archivoaux = file_get_contents($this->ruta);
				
			//Agregar a Documentos
			$doc_aux = array();
			$doc_aux["idDocumento"] = $idDocumento;
			$doc_aux["NombreArchivo"] = "Documento_". $idDocumento;
			$doc_aux["Extension"] = "pdf";
			$doc_aux["documento"] = base64_encode($archivoaux);//el archivo en base 64

			//Ejecutar el SP
			if( $this->documentosBD->agregarDocumento($doc_aux) ){
				$this->mensajeOK= "La generaci&oacute;n fue exitosa";
				$this->band = $idDocumento;
			}
			else{
				$this->mensajeError.=$this->documentosBD->mensajeError;
			}
		}
	}else{
		$tipotransaccion = "No se completo la generaci&oacute;n del Documento";
		
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

private function clearData($arr_CamposTabla)
{
	$error = 0;
	$cantidadcolumnas= count($this->valorCelda);
	//$this->vacio = 0;
	$this->mensajeError = "";
	//$columnareal = 0;
	//$this->strSql="";
	for($col = 0; $col <= $cantidadcolumnas-1; $col++)
	{
		//rescatando tipoDato de la tabla
		$TipoDato = $arr_CamposTabla->data[$col]["TipoDato"];
		//$Ancho = $arr_CamposTabla->data[$col]["Ancho"];
		//$Obligatorio = $arr_CamposTabla->data[$col]["Obligatorio"];
		//$GuardaEnSql = $arr_CamposTabla->data[$col]["GuardaEnSql"];
		switch ($TipoDato)
		{
			case '2': // Numero
				$this->valorCelda[$col] = preg_replace('/[\.,]/i', '', $this->valorCelda[$col]);
			break;
		}
	}
	/*
	switch ($columnaFila)
	{
		case 'Y2': // Formato celda => General
			var_dump("SUELDO = F(SUELDO) = C(SUELDO)", $columnaFila, $dato);
			//string(30) "SUELDO = F(SUELDO) = C(SUELDO)" string(2) "Y2" string(6) "SUELDO" 
		break;
		case 'Y3': // Formato celda => General -> Numero
			var_dump("100.000 = F(100000) = C(100.000)", $columnaFila, $dato);
			//string(32) "100.000 = F(100000) = C(100.000)" string(2) "Y3" float(100000) 
		break;
		case 'Y4': // Formato celda => General
			var_dump("100,000 = F(100) = C(100)", $columnaFila, $dato);
			//string(25) "100,000 = F(100) = C(100)" string(2) "Y4" float(100) 
		break;
		case 'Y5': // Formato celda => General
			var_dump("100000 = F(100000) = C(100000)", $columnaFila, $dato);
			//string(30) "100000 = F(100000) = C(100000)" string(2) "Y5" float(100000) 
		break;

		case 'Y6': // Formato celda => Texto
			var_dump("SUELDO = F(SUELDO) = C(SUELDO)", $columnaFila, $dato);
			//string(30) "SUELDO = F(SUELDO) = C(SUELDO)" string(2) "Y6" string(6) "SUELDO" 
		break;
		case 'Y7': // Formato celda => Texto [ESTE CASO GUARDA UN REDONDEO QUE NO VE EL USUARIO QUE OPERA EL EXCEL]
			var_dump("100.000 = F(100.000) = C(100.000)", $columnaFila, $dato);
			//string(33) "100.000 = F(100.000) = C(100.000)" string(2) "Y7" string(7) "100.000" 
		break;
		case 'Y8': // Formato celda => Texto
			var_dump("100,000 = F(100,000) = C(100,000)", $columnaFila, $dato);
			//string(33) "100,000 = F(100,000) = C(100,000)" string(2) "Y8" string(7) "100,000" 
		break;
		case 'Y9': // Formato celda => Texto
			var_dump("100000 = F(100000) = C(100000)", $columnaFila, $dato);
			//string(30) "100000 = F(100000) = C(100000)" string(2) "Y9" string(6) "100000" 
		break;

		case 'Y10': // Formato celda => Numero
			var_dump("SUELDO = F(SUELDO) = C(SUELDO)", $columnaFila, $dato);
			//string(30) "SUELDO = F(SUELDO) = C(SUELDO)" string(3) "Y10" string(6) "SUELDO" 
		break;
		case 'Y11': // Formato celda => Numero
			var_dump("100.000 = F(100000) = C(100000)", $columnaFila, $dato);
			//string(31) "100.000 = F(100000) = C(100000)" string(3) "Y11" float(100000) 
		break;
		case 'Y12': // Formato celda => Numero
			var_dump("100,000 = F(100) = C(100)", $columnaFila, $dato);
			//string(25) "100,000 = F(100) = C(100)" string(3) "Y12" float(100) 
		break;
		case 'Y13': // Formato celda => Numero
			var_dump("100000 = F(100000) = C(100000)", $columnaFila, $dato);
			//string(30) "100000 = F(100000) = C(100000)" string(3) "Y13" float(100000) 
		break;
		case 'Y14': // Formato celda => Numero(separador de miles y decimales)
			var_dump("100000 = F(100000) = C(100.000,00)", $columnaFila, $dato);
			//string(34) "100000 = F(100000) = C(100.000,00)" string(3) "Y14" float(100000) 
			break;
		case 'Y15': // Formato celda => Moneda(separador de miles y decimales)
			var_dump("100000 = F(100000) = C($100.000,00)", $columnaFila, $dato);
			//string(35) "100000 = F(100000) = C($100.000,00)" string(3) "Y15" float(100000) 
		break;
		case 'Y16': // Formato celda => Moneda(decimales) [SE CAE FATAL]
		var_dump("100000,23 = F(100000,23) = C($100.000,23)", $columnaFila, $dato);
		//string(41) "100000,23 = F(100000,23) = C($100.000,23)" string(3) "Y16" float(100000,23) 
		break;
	}
	*/
		// En conclusion, cunado el campo de base de datos en un integer, en el excel pueden asignar los siguientes formatos:
			// General
			// Numero (c/s separador de miles y/o c/s decimales)
			// Moneda (c/s decimales)
			// Nunca podra ser Texto
		// Casos a reparar en la data de carga
}

private function validarInfoConfiguracion($arr_CamposTabla)
{
	$error = 0;
	$cantidadcolumnas= count($this->valorCelda);
	$this->vacio = 0;
	$this->mensajeError = "";
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
		$valorCelda = $this->valorCelda[$col];

		//cuenta valor celda vacia
		if (trim($valorCelda) == "")
		{
		  $this->vacio++;
		}
				
		//valida tipodato true false
		$resultadoTipoDato = '';
		$resultadoTipoDato = $this->validaTipoDato($valorCelda,$TipoDato);
	
		//validacion tipo dato
		if( ! $resultadoTipoDato )
		{
			//si es distinto a espacio
			if (trim($valorCelda) != "")
			{
				$this->mensajeError.=" Error en tipo de dato de la columna <b>".$this->columnaExcel($columnareal)."</b><br>";
				$error++;

			}
		}

		 //valida ancho celda
		$resultadoAnchoCelda = $this->validaAnchoCelda($valorCelda,$Ancho);

		if( ! $resultadoAnchoCelda )
		{
		  $this->mensajeError.="Error el dato de la columna <b>".$this->columnaExcel($columnareal)."</b> excede el largo<br>";
		  $error++;
		}

		//valida si es dato obligatorio
		if( $Obligatorio == 1 && $valorCelda == '' && $GuardaEnSql == 1 ){
		
			$this->mensajeError.="Error el dato de la columna <b>".$this->columnaExcel($columnareal)."</b> no puede estar vac&iacute;o<br>";
			$error++;
		}		
	
		if( $Obligatorio == 0 && $valorCelda == '')
		{
			$this->valorCelda[$col] = "NULL";
		}
	}
			
	//devuelve como nos fue en esta funcion
	if ($this->mensajeError == '' )
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
		if(is_numeric($valorCelda) )
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

	private function is_valid_email($str)
	{
	  return (false !== strpos($str, "@") && false !== strpos($str, "."));	 
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

	private function validarPerfilamiento($empresa,$centrocosto,$lugarpago){
		
		$datos = array();
		$datos['empresaid'] = $empresa;
		//$datos['lugarpagoid'] = $centrocosto;
		$datos['idCentroCosto'] = $centrocosto;
		
		if( $empresa != '' && $lugarpago != '' && $centrocosto != '' ){
		
			$dt = new DataTable();
			
			/*$this->lugarespagoBD->obtener($datos,$dt);
			$this->mensajeError = $this->lugarespagoBD->mensajeError;
			$count = count($dt->data);
			
			if( $this->mensajeError != '' || $count == 0 ){
				$this->mensajeError = 'El Centro de Costo <b>'.$centrocosto.'</b> no pertenece a la Empresa <b>'.$empresa.'</b>';
				return false;
			}	*/	
			
			$this->centroscostoBD->obtenerEmpresa($datos,$dt);
			$this->mensajeError = $this->centroscostoBD->mensajeError;
			
			if( $this->mensajeError != '' || count($dt->data) == 0 ){
				$this->mensajeError = 'El Centro de Costo <b>'.$centrocosto.'</b> no pertenece a la Empresa <b>'.$empresa.'</b>';
				return false;
			}
		}
		else
		{
			$this->mensajeError = 'Los Datos de Empresa, Centro de Costo y Lugar de Pago no pueden estar vac&iacute;os';
			return false;
		}
	}

	private function crearContrato(){

		//Generar Documento nuevo 
		$datos = $_REQUEST;
		$dt = new DataTable();
		
		//Datos que faltan al registro 
		$datos["idEstado"] = 1; //Creado
		$datos["FechaCreacion"] = date("d-m-Y H:i:s");
		$datos["idTipoGeneracion"] = 3; //Por archivo de carga
		$datos['idTipoFirma'] = $datos['idFirma'];
		
		//Guardar los Datos del Documento 
		$this->documentosBD->agregar($datos,$dt);
		$this->mensajeError.= $this->documentosBD->mensajeError;     
		$datos["idDocumento"] = $dt->data[0]["idDocumento"];
		$idDocumento = $dt->data[0]["idDocumento"];
		
		if( $this->mensajeError == "" )
			return $idDocumento;
		else
			return 0;
	}
	
	//Construir Arreglos de los Firmantes
	private function construirFirmantes($idDocumento,$empleado,&$resultado){
	
		$datos = $_REQUEST;
		
		//Cambiar el orden de los firmantes 
		$rut_1 = '';
		$rut_2 = '';
		$cant_f = count($datos["Firmantes_Emp"]);
		
		if( $cant_f > 1 ){
		
			foreach( $datos["Firmantes_Emp"] as $key => $value){
			
				$var ='';
				$var = 'orden_';
				$var .= $value; 
			
				if( $datos[$var] == 1 ){
					$rut_1 = $value;
				}else{
					$rut_2 = $value;
				}
			}
			
			$array = array();
			$array[0] = $rut_1;
			$array[1] = $rut_2;
			
			$datos["Firmantes_Emp"] = array();
			$datos["Firmantes_Emp"] = $array;
		}
		//Fin

		//Variables que faltan 
		$dt3 = new DataTable();
		$dt4 = new DataTable();
		$dt5 = new DataTable();
		$dt6 = new DataTable();
		$dt8 = new DataTable();
		$dtx9 = new DataTable();
		
		//Tipo de empresa 
		$tipoEmpresa = 0;
		$datos ["idDocumento"] = $idDocumento;

	    //Buscar datos que faltan de la Empresa
	    if ( $datos["RutEmpresa"] !='' ){
	       	$this->documentosBD->obtenerRazonSocial($datos,$dt4);
	       	$this->mensajeError.=$this->documentosBD->mensajeError;
	    }
	   
		$this->documentosBD->obtener($datos, $dtx9);
		$this->mensajeError.=$this->documentosBD->mensajeError;
		
		if ($this->mensajeError == "")
		{
			if( $dtx9->leerFila() )
			{
				$datos["idWF"] = $dtx9->obtenerItem('idWF');
			}		
		}
	
      	//Buscar Los estados del WorkFlow 
        $this->documentosBD->obtenerEstados($datos, $dt8);
        $this->mensajeError.=$this->documentosBD->mensajeError;

        //Auxiliar para cuando tiene estado de aprobacion
        $orden = 0;
        $identificador = "";
		$identificador = "RUT N&deg;";
		$datos['Representantes'] = 0;
		$datos['Empleado'] = 0;
		$nombre = '';
		$estado = '';
		$cont = 0;
		
		if( count($dt8->data) > 0 ){
			//Recorremos el arreglo resultado de los Estados disponibles del WorkFlow 
	        foreach ($dt8->data as $key => $value) {
			
			$nombre = $dt8->data[$key]["Nombre"];
			$estado = $dt8->data[$key]["idEstado"];
		
			if( is_numeric($key) ){
			
 		        //Si Estado: Pendiente por firma de Empresa 
		        if(( $nombre == 'Pendiente por firma Representante') && ($estado == 2 ) ){
			
		        	//Firmantes de la Empresa
			  		$f_empresa = array();
			  		$empresa_aux = array();
		
			       // foreach ($datos["Firmantes_Emp"] as $i => $valor) {
				
						if( $datos["Firmantes_Emp"][$cont] != '' )
						{
							//Datos faltantes 
							$empresa_aux = array ( "idDocumento" => $idDocumento, "RutEmpresa" => $datos["RutEmpresa"], "RutFirmante" => $datos["Firmantes_Emp"][$cont], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);	        	
												
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError .= $this->documentosBD->mensajeError;

							//Buscar datos
							if(  $datos["Firmantes_Emp"][$i] != '' ){
								$array = array( "RutEjecutivo" => $datos["Firmantes_Emp"][$i] );

								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];
																		
							//Completar el arreglo
							$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$datos["Firmantes_Emp"][$cont], "nombre_emp" => "P.p ".$nombre_emp, "rut_emp" => "RUT N&deg;".$datos["RutEmpresa"]);
							
							//Agregar al final 
							array_push($f_empresa, $nuevo);
						
							if($this->mensajeError == "" ) $datos['Representantes']++;
							$cont++;
						}
		        	//}
		        } //Fin del Si Estado: Pendiente por firma de Empresa 

				//Si Estado: Pendiente por firma de Empresa pero un segundo Representante 
		        if(($nombre == 'Pendiente por firma Representante 2') && ($estado == 10 ) ){
				
		        	//Firmantes de la Empresa
			  		$f_empresa = array();
			  		$empresa_aux = array();
		
			        //foreach ($datos["Firmantes_Emp"] as $i => $valor) {
					
				
						if( $datos["Firmantes_Emp"][$cont] != '' )
						{
						
							//Datos faltantes 
							$empresa_aux = array ( "idDocumento" => $idDocumento, "RutEmpresa" => $datos["RutEmpresa"], "RutFirmante" => $datos["Firmantes_Emp"][$cont], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);	        	
												
							//Agregara a la tabla
							$this->documentosBD->agregarFirmantes($empresa_aux);
							$this->mensajeError .= $this->documentosBD->mensajeError;

							//Buscar datos
							if(  $datos["Firmantes_Emp"][$i] != '' ){
								$array = array( "RutEjecutivo" => $datos["Firmantes_Emp"][$i] );

								$this->documentosBD->obtenerPersona($array, $dt3);
								$this->mensajeError.=$this->documentosBD->mensajeError;
							}
							$nombre_emp = "";
							$nombre_emp = $dt4->data[0]["RazonSocial"];
																		
							//Completar el arreglo
							$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$datos["Firmantes_Emp"][$cont], "nombre_emp" => "P.p ".$nombre_emp, "rut_emp" => "RUT N&deg;".$datos["RutEmpresa"]);
							
							//Agregar al final 
							array_push($f_empresa, $nuevo);
						
							if($this->mensajeError == "" ) $datos['Representantes']++;
						}
		        	//}
		        } //Fin del Si Estado: Pendiente por firma de Empresa 
								
				//Si Estado: Pendiente por firma de Empleado 
				if(($nombre == 'Pendiente por firma Empleado')||($estado == 3 )){

		        	//Firmantes del empleado 
		        	$f_empleado = array();
		        	$empleado_aux = array();

					if( $empleado['personaid'] != '' )
						$empleado['rutusuario'] = $empleado['personaid'];

					//Buscar datos
		        	if ( $empleado['rutusuario'] != '' ){
			        	$array = array( "RutEjecutivo" => $empleado['rutusuario']);
			        	$this->documentosBD->obtenerPersona($array, $dt3);
			        	$this->mensajeError.=$this->documentosBD->mensajeError;

			        	//Completar arreglo
						$nuevo = array( "nombre" => $dt3->data[0]["nombre"].' '.$dt3->data[0]["appaterno"].' '.$dt3->data[0]["apmaterno"] , "rut" => $identificador.$empleado['rutusuario'] );

						//Datos faltantes 
						$empleado_aux = array ( "idDocumento" => $idDocumento, "RutEmpresa" =>$empleado['rutusuario'], "RutFirmante" => $empleado['rutusuario'], "idEstado" => $dt8->data[$key]["idEstadoWF"],"Orden" => $dt8->data[$key]["Orden"]);

						//Agregara a la tabla
						$this->documentosBD->agregarFirmantes($empleado_aux);
						$this->mensajeError.=$this->documentosBD->mensajeError;
			        
						//Agregar al final
						array_push($f_empleado, $nuevo); 
					}  
						
		        }//Fin de Estado: Pendiente por firma de Empleado 
				
			}//Fin del IF
				
	        }//Fin del Foreach de los Estados del WF
		}
       
        //Unir Firmantes en un solo arreglo
	    $firmantes_completos = array();
	
		//Si el flujo tiene Empresa
		//if( $datos['Representantes'] == 1 ) array_push($firmantes_completos, $f_empresa);
		if( $datos['Representantes'] > 0 ) array_push($firmantes_completos, $f_empresa);
				
		//Si el flujo tiene Empleado
		if( $datos['Empleado'] == 1 ) array_push($firmantes_completos, $f_empleado);

		$resultado = array();
		$resultado = $firmantes_completos; 

		return $resultado;
	}
	
	//Sustituir variables del Documento
	private function construirVariables($idDocumento,$datos2,&$resultado){

		$resultado = array();

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE,VAR_REPRESENTANTE_2);

		foreach ($tablas as $key => $value) {
			$resultado_parcial = array();
			$this->buscarVariables($idDocumento,$value,$datos2,$resultado_parcial);
			array_push($resultado, $resultado_parcial);
		}
		
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	private function buscarVariables($idDocumento,$busqueda,$datos2,&$resultado){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();
		$var_busqueda = '';
		$resultado = '';

		//Cambiar el orden de los firmantes 
		$rut_1 = '';
		$rut_2 = '';
		$cant_f = 0;
		
		$cant_f = count($datos2["Firmantes_Emp"]);
		
		if( $cant_f > 1 ){
			foreach( $datos2["Firmantes_Emp"] as $key => $value){
			
				$var ='';
				$var = 'orden_';
				$var .= $value; 
			
				if( $datos[$var] == 1 ){
					$rut_1 = $value;
				}else{
					$rut_2 = $value;
				}
			}
			
			$array = array();
			$array[0] = $rut_1;
			$array[1] = $rut_2;
			
			$datos2["Firmantes_Emp"] = array();
			$datos2["Firmantes_Emp"] = $array;
		}
		//Fin
	
		//Consultamos segun la tabla a consultar

		switch( $busqueda ){
		
			case VAR_DOCUMENTO: 
			
				$this->documentosBD->obtenerVariablesDocumento($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;
				
			case VAR_EMPRESAS: 
			
				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;
				
			case VAR_EMPLEADOS: 
			
				$this->documentosBD->obtenerVariablesEmpleado($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;
				
			case VAR_ARCHIVO:
			
				$this->ContratosDatosVariablesBD->obtener($datos,$dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:
			
				if( $datos2['Firmantes_Emp'][0] != '' ){
					$datos["RutUsuario"] = $datos2['Firmantes_Emp'][0];
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}
				break;
			
			case VAR_REPRESENTANTE_2:
				
				if( $datos2['Firmantes_Emp'][1] != '' ){
					$datos["RutUsuario"] = $datos2['Firmantes_Emp'][1];
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
		}
		
		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_l = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Arroba cero
		
		if( count($dt->data) > 0){

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
					}

					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
					}

					if( strlen($value) > 0 ) {
				
						if ( $this->validateDate($value,'d-m-Y')){
							
							//Si la fecha es Indefinido
							if( $value == VAR_FECHA_IND ){
							
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								
							}else{

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
															
								$fecha_s = $this->convertirFechaLarga($value);		
								$fecha_c = $this->convertirFechaCorta($value);
								
								array_push($aux,$value);
								array_push($aux,VAR_HASTA_EL.$fecha_s);
								array_push($aux,VAR_HASTA_EL.$fecha_c);
								array_push($aux,$fecha_s);
								array_push($aux,$fecha_c);
							}

						}else{
							array_push($variables, $var);
						    array_push($aux, $value);
						}		

						if ( is_numeric($value)){
							
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							
							$numeros = $this->numerosALetras($value);
							
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							
						}				
					}
				}
			}
			$html_resultado = '';
			$this->graba_log("/// VARIABLES - GENERACION MASIVA///");
				
			//Buscamos si existe conincidencia
			foreach ($variables as $key => $value) {

				if ( strstr($html,$value) ){
					//Sustituir en el HTML
					$html = str_replace($variables,$aux,$html);
				}
				//$this->Graba_log($value);
				$this->Graba_log($value." : ".$aux[$key]);
			}

			if( strstr($html,VAR_LOGO)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];

				$logo = $rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_LOGO,$logo,$html);
				$this->Graba_log("Variable Logo : ".VAR_LOGO." | valor: ".$logo);
			}

			if( strstr($html,VAR_RUTA)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];
				
				$ruta = VAR_RUTA_COMPLETA.$rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_RUTA,$ruta,$html);
				$this->Graba_log("Variable Ruta: ".VAR_RUTA." | valor: ".$ruta);
			}	
		}
		
		$resultado = $html;
		return $resultado;
	}
	
	//Buscar subclausulas
	private function buscarVariablesSubClausulas($html,&$resultado){

		$datos = $_REQUEST;
	
		$dt = new DataTable();
		$var_busqueda = '';
		$resultado = '';
		$variables = array();
		$aux = array();
		
		if( count($this->subclausulas) > 0 ){
			
			foreach ($this->subclausulas as $key => $value) {
				
				if ( strlen($this->subclausulas[$key]['idSubClausula']) > 0 ){

					if( $this->subclausulas[$key]['idSubClausula'] != 'NULL '){
						
						$this->subclausulasBD->obtener($this->subclausulas[$key],$dt);
						$this->mensajeError = $this->subclausulaBD->mensajeError;
						

						$tipo = '';
						$tipo = $dt->data[0]['TipoSubClausula'];

						if( count($dt->data) > 0 ){

							$variables = array();
							$aux = array();
							$var = '';

							//Construimos el arreglo de variables 
							foreach ($dt->data[0] as $key => $value) {

								if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){

									if( VAR_SUBCLAUSULAS == '') $var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
									else $var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

									if( strlen($value) > 0 ) {
										array_push($variables, htmlentities($var));
										array_push($aux, $value);
									}
								}
							}

							//Buscamos si existe conincidencia
						
							foreach ( $variables as $key => $value ) {
						
								if ( strstr($html,$value)){
									//Sustituir en el HTML
						    		$html = str_replace($variables,$aux,$html);
								}
								$this->graba_log("SubClausulas : ".$value." - ".$aux[$key]);
							}
						}
					}
				}
			}
		}
		
		//Obligaciones del cargo
		/*$datos_aux = array();
		$datos_aux['idSubClausula'] = $datos['RutEmpresa'].SEPARADOR_SUBCLAUSULAS.$this->subclausulas[0]['idSubClausula'];
		$datos_aux['idTipoSubClausula'] = 5; //Obligaciones
		$obligaciones = $datos_aux['idSubClausula'];
		
		$this->subclausulasBD->obtener($datos_aux,$dt);
		$this->mensajeError = $this->subclausulaBD->mensajeError;
					
		if( count($dt->data) > 0 ){
			
			$variables = array();
			$aux = array();
			$var = '';

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {

				if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){
	
					$var = PREFIJO_VAR.$datos_aux['idSubClausula'].SEPARADOR.$key.SUFIJO_VAR;
					
					if( strlen($value) > 0 ) {
						array_push($variables, htmlentities($var));
						array_push($aux, $value);
					}
				}
			}

			//Buscamos si existe conincidencia
		
			foreach ( $variables as $key => $value ) {
		
				if ( strstr($html,$value)){
					//Sustituir en el HTML
					$html = str_replace($variables,$aux,$html);
				}
				$this->graba_log("Obligaciones : ".$value." - ".$aux[$key]);
			}
		}*/
		//Fin de Obligaciones

		$resultado = $html; 
		return $resultado;
	}

	//Construir Plantilla
	private function construirPlantilla($idDocumento,&$resultado){
		
		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
		
		$datos['idDocumento'] = $idDocumento;
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		//Buscar la Plantilla del documento
		$this->documentosBD->obtener($datos,$dt);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $dt->leerFila() ){
			$idPlantilla = $dt->obtenerItem('idPlantilla');
		}

		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		if( $idPlantilla > 0 ){

			$datos['idPlantilla'] = $idPlantilla;

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;

			//Agregamos etiquetas 
			$tags = '<html>';
			$html = $tags;

	        //Agregamos el Estilo a la Plantilla 
	        $style = '';
	        $style = '<style>@page {'.$papel.'} @media print {'.$papel.'}';
	        $style .= ESTILO_PDF.'</style>';
	        $html .= $style;

	        //Agregamos el encabezado 
	        $encabezado = '';
	        $encabezado = '<body><div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';
	        $html .= $encabezado;

	        //Variables
	        $num = 1;
	        $contenido = '';
	     
	        //Construir Plantilla con las Clausulas
	        if( count($dt->data) > 0){

	        	foreach ($dt->data as $i => $value) {
        		
					$clausula = '';
					$aux = '';
					
					//Si estan el titulo y encabezado activos 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
		        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
		        		$clausula = "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong> ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
					}
					
					//Si estan el titulo y encabezado inactivos 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
						$aux = $dt->data[$i]["Texto"];
						$clausula = $aux; 
					}
					
					//Si esta el encabezado activo y el titulo no 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
		        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
		        		$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
					}
					
					//Si el titulo esta activo y el encabezado no 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){
										
		        		$clausula = "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						if ( $aux != '' ){
							$clausula .= $aux;	        		
						}
					}

					//Si encuentra la variable vacia 
					if ( strstr($dt->data[$i]["Texto"],VAR_VACIA) ){
						$clausula = '';
					}

					//Agregar clausulas
					$contenido .= $clausula;
				}
	        
				//Limpiar el HTML
				$aux = '';
		        $aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><h3><img>');
				$html .= $aux;
		        $html .= "</div>";

		        $resultado = $html;
		        return $resultado;
			}
			else{
				return false;
			}
        }
        else{
        	return false;
        }
	}
		
	//Construir tabla de Firmates de un Documento 
	public function construirTablaFirmantes($id,$datos,&$resultado){

		//Declarar variable para firmantes 
		$tabla_firmantes = ''; 
		$num = 0;
		$div = 0;
		$encabezado = '<div><table border="0" width="100%" style="page-break-inside:avoid; padding-top: 3cm; "><tbody><tr>';

		$dt = new DataTable();
		$array = array( "idDocumento" => $id );
		$this->documentosBD->obtenerFirmantesXDocumento($array,$dt); 
		$this->mensajeError = $this->documentosBD->mensajeError;

		if( count($datos) > 0 ){
			foreach ($datos as $i => $value) {

			if( count($datos[$i]) > 0 ){
	       		foreach ($datos[$i] as $j => $value) {

	       			if( $num == 0 ){
		     			$tabla_firmantes .= $encabezado;
		     		}
		   			//Mientras no sea el notario, se agrega a la tabla 
					if( $value["nombre_not"] == ""){
			
		       		 	$tabla_firmantes.= '<td align="center"><p style="line-height: 1.3"><strong>';

		       		 		if( count($datos[$i][$j]) > 0){
			       				foreach ($datos[$i][$j] as $key => $value) {
			       					$tabla_firmantes .= $value."<br>";
			       				}
			       			}else{
			       				$tabla_firmantes .= $value."<br>";
			       			}

		       			$num++;
		       			$div++;
			       		$tabla_firmantes .= '</strong></p></td>';

			       		if( $num == 3 ){
			       			$tabla_firmantes .= '</tr></tbody></table></div>';
			       			$num = 0;
				       	}
				    }   		
		       	}	
		    }
			}
		}
     	 
       
       if (($div % 3) == 1){
       		$tabla_firmantes .= '</tr></tbody></table></div>';
	   }

	   $this->graba_log($tabla_firmantes);
       //Reasignamos al atributo de la clase 
       return $resultado = $tabla_firmantes; 
       //FIN
	}
	
	//PDF Contrato 
	private function generarPDF($datos,$html){
		try {	
				$dompdf = new Dompdf();
				
				$dompdf->loadHtml($html);
				
				// (Optional) Setup the paper size and orientation
				$dompdf->set_paper( TAMANO_HOJA, $this->orientacion);
							
				// Render the HTML as PDF
				$dompdf->render();
	  	
	  			$dompdf->getCanvas()->page_text(293, 750, "{PAGE_NUM}", $font, 10, array(0,0,0));
										
				$pdf = $dompdf->output();
				
				//Asignar ruta del documento a generar
				$this->ruta = dirname(__FILE__).'/tmp/Documento_'.$datos.'.pdf';	
					
				file_put_contents($this->ruta, $pdf);
			
			
			} catch (Exception $e) {
				echo 'Excepci�n capturada: ',  $e->getMessage(), "\n";
			}
	}
	
	//Sustituir acentos
	public static function TildesHtml($cadena) 
    { 
        return str_replace(array("�","�","�","�","�","�","�","�","�","�","�","�"),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }
	
	//Valida Fecha
	private function validateDate($date, $format ='d-m-Y')
	{	//$this->graba_log("FECHAS :",$date);
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	//Valida Tipo de Rut Chileno 
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

	//Devuelve la columna 
	private function columnaExcel($num){
		switch($num){
			case '1':return 'A';break;
			case '2':return 'B';break;
			case '3':return 'C';break;
			case '4':return 'D';break;
			case '5':return 'E';break;
			case '6':return 'F';break;
			case '7':return 'G';break;
			case '8':return 'H';break;
			case '9':return 'I';break;
			case '10':return 'J';break;
			case '11':return 'K';break;
			case '12':return 'L';break;
			case '13':return 'M';break;
			case '14':return 'N';break;
			case '15':return 'O';break;
			case '16':return 'P';break;
			case '17':return 'Q';break;
			case '18':return 'R';break;
			case '19':return 'S';break;
			case '20':return 'T';break;
			case '21':return 'U';break;
			case '22':return 'V';break;
			case '23':return 'W';break;
			case '24':return 'X';break;
			case '25':return 'Y';break;
			case '26':return 'Z';break;
			case '27':return 'AA';break;
			case '28':return 'AB';break;
			case '29':return 'AC';break;
			case '30':return 'AD';break;
			case '31':return 'AE';break;
			case '32':return 'AF';break;
			case '33':return 'AG';break;
			case '34':return 'AH';break;
			case '35':return 'AI';break;
			case '36':return 'AJ';break;
			case '37':return 'AK';break;
			case '38':return 'AL';break;
			case '39':return 'AM';break;
			case '40':return 'AN';break;
			case '41':return 'AO';break;
			case '42':return 'AP';break;
			case '43':return 'AQ';break;
			case '44':return 'AR';break;
			case '45':return 'AS';break;
			case '46':return 'AT';break;
			case '47':return 'AU';break;
			case '48':return 'AV';break;
			case '49':return 'AW';break;
			case '50':return 'AX';break;
			case '51':return 'AY';break;
			case '52':return 'AZ';break;
			case '53':return 'BA';break;
			case '54':return 'BB';break;
			case '55':return 'BC';break;
			case '56':return 'BD';break;
			case '57':return 'BE';break;
			case '58':return 'BF';break;
			case '59':return 'BG';break;
			case '60':return 'BH';break;
			case '61':return 'BI';break;
			case '62':return 'BJ';break;
			case '63':return 'BK';break;
			case '64':return 'BL';break;
			case '65':return 'BM';break;
			case '66':return 'BN';break;
			case '67':return 'BO';break;
			case '68':return 'BP';break;
			case '69':return 'BQ';break;
			case '70':return 'BR';break;
			case '71':return 'BS';break;
			case '72':return 'BT';break;
			case '73':return 'BU';break;
			case '74':return 'BV';break;
			case '75':return 'BW';break;
			case '76':return 'BX';break;
			case '77':return 'BY';break;
			case '78':return 'BZ';break;
		}
	}

	private function convertirFechaLarga($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%d de %B de %Y", strtotime($fecha));
		return ucwords($resultado);
	}

	private function convertirFechaCorta($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%B de %Y", strtotime($fecha));
		return ucwords($resultado);
	}

	//Funcion para devolver numeros ordinales 
	public function numerosALetras($xcifra)

	//------    CONVERTIR NUMEROS A LETRAS         ---------------
	//------    M�xima cifra soportada: 18 d�gitos con 2 decimales
	//------    999,999,999,999,999,999.99
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE BILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE MILLONES
	// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE PESOS 99/100 M.N.
	//------    Creada por:                        ---------------
	//------             ULTIMINIO RAMOS GAL�N     ---------------
	//------            uramos@gmail.com           ---------------
	//------    10 de junio de 2009. M�xico, D.F.  ---------------
	//------    PHP Version 4.3.1 o mayores (aunque podr�a funcionar en versiones anteriores, tendr�as que probar)

	{
	    $xarray = array(0 => "Cero",
	        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
	        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
	        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
	        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
	    );
		//
	    $xcifra = trim($xcifra);
	    $xlength = strlen($xcifra);
	    $xpos_punto = strpos($xcifra, ".");
	    $xaux_int = $xcifra;
	    $xdecimales = "00";
	    if (!($xpos_punto === false)) {
	        if ($xpos_punto == 0) {
	            $xcifra = "0" . $xcifra;
	            $xpos_punto = strpos($xcifra, ".");
	        }
	        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
	    }

	    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
	    $xcadena = "";
	    for ($xz = 0; $xz < 3; $xz++) {
	        $xaux = substr($XAUX, $xz * 6, 6);
	        $xi = 0;
	        $xlimite = 6; // inicializo el contador de centenas xi y establezco el l�mite a 6 d�gitos en la parte entera
	        $xexit = true; // bandera para controlar el ciclo del While
	        while ($xexit) {
	            if ($xi == $xlimite) { // si ya lleg� al l�mite m�ximo de enteros
	                break; // termina el ciclo
	            }

	            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
	            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres d�gitos)
	            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
	                switch ($xy) {
	                    case 1: // checa las centenas
	                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres d�gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
	                            
	                        } else {
	                            $key = (int) substr($xaux, 0, 3);
	                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es n�mero redondo (100, 200, 300, 400, etc..)
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Mill�n, Millones, Mil o nada)
	                                if (substr($xaux, 0, 3) == 100)
	                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
	                            }
	                            else { // entra aqu� si la centena no fue numero redondo (101, 253, 120, 980, etc.)
	                                $key = (int) substr($xaux, 0, 1) * 100;
	                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
	                                $xcadena = " " . $xcadena . " " . $xseek;
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 0, 3) < 100)
	                        break;
	                    case 2: // checa las decenas (con la misma l�gica que las centenas)
	                        if (substr($xaux, 1, 2) < 10) {
	                            
	                        } else {
	                            $key = (int) substr($xaux, 1, 2);
	                            if (TRUE === array_key_exists($key, $xarray)) {
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux);
	                                if (substr($xaux, 1, 2) == 20)
	                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3;
	                            }
	                            else {
	                                $key = (int) substr($xaux, 1, 1) * 10;
	                                $xseek = $xarray[$key];
	                                if (20 == substr($xaux, 1, 1) * 10)
	                                    $xcadena = " " . $xcadena . " " . $xseek;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 1, 2) < 10)
	                        break;
	                    case 3: // checa las unidades
	                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
	                            
	                        } else {
	                            $key = (int) substr($xaux, 2, 1);
	                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
	                            $xsub = $this->subfijo($xaux);
	                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                        } // ENDIF (substr($xaux, 2, 1) < 1)
	                        break;
	                } // END SWITCH
	            } // END FOR
	            $xi = $xi + 3;
	        } // ENDDO

	        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        // ----------- esta l�nea la puedes cambiar de acuerdo a tus necesidades o a tu pa�s -------
	        if (trim($xaux) != "") {
	            switch ($xz) {
	                case 0:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN BILLON ";
	                    else
	                        $xcadena.= " BILLONES ";
	                    break;
	                case 1:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN MILLON ";
	                    else
	                        $xcadena.= " MILLONES ";
	                    break;
	                case 2:
	                    if ($xcifra < 1) {
	                        $xcadena = "CERO ";
	                    }
	                    if ($xcifra >= 1 && $xcifra < 2) {
	                        $xcadena = "UNO ";
	                    }
	                    if ($xcifra >= 2) {
	                        $xcadena.= " "; //
	                    }
	                    break;
	            } // endswitch ($xz)
	        } // ENDIF (trim($xaux) != "")
	        // ------------------      en este caso, para M�xico se usa esta leyenda     ----------------
	        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	    } // ENDFOR ($xz)
	    return trim($xcadena);
	}

	// END FUNCTION

	public function subfijo($xx)
	{ // esta funci�n regresa un subfijo para la cifra
	    $xx = trim($xx);
	    $xstrlen = strlen($xx);
	    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
	        $xsub = "";
	    //
	    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
	        $xsub = "MIL";
	    
	    return $xsub;
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