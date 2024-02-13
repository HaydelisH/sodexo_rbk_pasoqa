<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/excelemplBD.php");

// Llamamos las clases necesarias PHPEcel
require_once('includes/PHPExcel/Classes/PHPExcel.php');

// creamos la instacia de esta clase
$page = new excelempl();

class excelempl {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $panelBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $infoconsulta="";
	
	private $nroopcion=30; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;
	
	
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

	private $valorcelda;
	private $Llave;

	private $filasprocesadas;
	private $highestRow;
	private $lineainicio; 
	private $cantidadaprocesar; 
	private $cantidadcolumnas;
	private $tipodato;
	private $largo;
	private $obligatorio;
	private $solicitado;
	private $usuarioid;

	private $personas_select_ini;
	private $personas_update_ini;
	private $personas_insert_ini;
	private $empleados_select_ini;
	private $empleados_update_ini;
	private $empleados_insert_ini;

	private $personas_select;
	private $personas_update;
	private $personas_update_fin;
	private $personas_insert;
	private $empleados_select;
	private $empleados_update;
	private $empleados_update_fin;
	private $empleados_insert;

	private $tipo_transaccion;
	private $colconfig;	
	
	private $con_plantillas = true;
	private $con_seguridad  = true;
	
	private $protocolo;
	private $rolexcel;
	private $estadoexcel;
	
	private $xempleadoid;
	private $xempresaid;
	
	private $xautollamados;
	
	private $xtotalfilas;

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
			
			if ($_POST["accion"]=="ESTADO" || $_POST["accion"]=="PROCESO")
			{
				$this->con_plantillas = false;
			}
			
			if ($_POST["accion"]=="PROCESO")
			{
				if (isset($_POST["usuarioid"]))
				{
					$this->con_seguridad = false;
				}
			}
			
			//$this->graba_log("accion ".$_POST["accion"]." plantillas:".$this->con_plantillas." seguridad:".$this->con_seguridad);
			
		}
		
		// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = new Paginas();
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			// lo agregamos a la pagina
			$this->pagina->agregarDato('mensajeError',$this->mensajeError);

			if ($this->con_plantillas)
			{
				// mostramos el encabezado
				$this->pagina->imprimirTemplate('templates/encabezado.html');
				$this->pagina->imprimirTemplate('templates/encabezadoFin.html');


				// imprimimos el template
				$this->pagina->imprimirTemplate('templates/puroError.html');
				// Imprimimos el pie
				$this->pagina->imprimirTemplate('templates/piedepagina.html');
				// y nos vamos	
			}
			return;
		}

		if ($this->con_seguridad)
		{
			// creamos la seguridad
			$this->seguridad = new Seguridad($this->pagina,$this->bd);
			// si no funciona hay que logearse
			if (!$this->seguridad->sesionar()) return;
		}
		
		$this->opcion = "Importar Excel Trabajadores";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-file-excel-o";
		$this->opcionnivel1 = "Importar Excel Trabajadores";
		$this->opcionnivel2 = "<li>Importar Excel Trabajadores</li>";

		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->excelemplBD = new excelemplBD();
		 
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->excelemplBD->usarConexion($conecc);
		
		if ($this->con_plantillas)
		{
			//se construye el menu
			include("includes/opciones_menu.php");
		}
		
		//$this->graba_log("llego");	
		if (!isset($_POST["accion"]))
		{
			$this->listado();
			// el pie
			if ($this->con_plantillas)
			{
			$this->imprimirFin();
			// y salimos
			}
			return;
		}
		else
		{
			//$this->graba_log("accion ".$_POST["accion"]);
		}
		
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "IMPORTAR":
				$this->importar();
				break;
				
			case "DETALLE":
				$this->detalle();
				break;
				
		    case "ESTADO":
				$this->estado();
				break;
				
		    case "PROCESO":
				$this->proceso();
				break;
				
			case "INICIO":
				$this->listado();
				break;				
		}

		if ($this->con_plantillas)
		{
			// e imprimimos el pie
			$this->imprimirFin();
		}

	}
	
	private function estado()
	{
		$totalfilas = $_POST["totalfilas"];
				
		$this->filas_procesadas();
		$this->filasprocesadas = $this->filasprocesadas + 1;
		if ($this->filasprocesadas < $totalfilas)
		{
			echo 'OK|'.$this->filasprocesadas.'|'.$totalfilas;
		}
		else
		{
			//$this->elimina_archivo($rutayarchivo);
			echo 'FIN';
		}
	
	
		
		$this->bd->desconectar();
						
		exit;
	}
	
	private function filas_procesadas()
	{
		$dt = new DataTable();
		
		$sql = "SELECT COUNT(*) AS cantidad FROM ConfImpResultado WHERE usuarioid = '".$this->seguridad->rut."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
			
		$this->filasprocesadas = 0;
		if($dt->leerFila())
		{
			 $this->filasprocesadas = $dt->obtenerItem("cantidad");
		}		
	}

	private function elimina_archivo ($rutayarchivo)
	{
		//$this->graba_log("va eliminar archivo ".$rutayarchivo);
		if(is_file($rutayarchivo))
		{
			
			if (!unlink($rutayarchivo))
			{
				$this->graba_log("No pudo eliminar archivo ".$rutayarchivo);
			}
			
		}		
	}
		
	private function listado()
	{
		$usuarioid = $this->seguridad->rut;
		$rutayarchivo = $this->deducir_nombre_archivo($usuarioid);
		
		if ($rutayarchivo != '')
		{
			//$this->informacion_excel($rutayarchivo);
			$this->filas_procesadas();
			$this->total_filas();
			$this->pagina->agregarDato("archivoxls","archivo.xls");
			$this->pagina->agregarDato("filasprocesadas",$this->filasprocesadas + 1);
			$this->pagina->agregarDato("totalfilas",$this->xtotalfilas);
		}
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/excelempl_inicio.html');

	}

	private function total_filas()
	{
		$dt = new DataTable();
		
		$sql = "SELECT total AS total FROM ConfImpEncabezado WHERE proceso = 'excelempl' AND usuarioid = '".$this->seguridad->rut."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
			
		$this->xtotalfilas = 0;
		if($dt->leerFila())
		{
			 $this->xtotalfilas = $dt->obtenerItem("total");
			 $this->graba_log ("total ".$this->xtotalfilas);
		}		
		else
		{
			$this->graba_log ("sin total ".$this->xtotalfilas);
		}
	}
	
	private function importar()
	{
		//$this->graba_log ("rut seguridad ".$this->seguridad->rut);	
		
		$this->valida_importacion();
		
		if ($this->mensajeError == '')
		{
			$this->inicializa_tablas();
			if ($this->mensajeError == '')
			{
				$this->pagina->agregarDato("archivoxls","archivo.xls");
				$this->pagina->agregarDato("filasprocesadas","0");
				$this->pagina->agregarDato("totalfilas",$this->highestRow);
				
				$datos["usuarioid"] = $this->seguridad->rut;
				$datos["inicial"] = 'inicial';
				$this->autollamada($datos);
			}
			else
			{
				$this->graba_log('error al inicializar tablas '.$this->mensajeError);
			}
		}
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/excelempl_inicio.html');

	}
	
	private function inicializa_tablas ()
	{
		$sql = "DELETE FROM ConfImpResultado WHERE usuarioid = '".$this->seguridad->rut."'";
		$this->excelemplBD->Grabar($sql);
		$this->mensajeError = $this->excelemplBD->mensajeError;	
		
		$sql = "DELETE FROM ConfImpEncabezado WHERE proceso = 'excelempl' AND usuarioid = '".$this->seguridad->rut."'";
		$this->excelemplBD->Grabar($sql);
		$this->mensajeError = $this->excelemplBD->mensajeError;	

		$sql = 'INSERT INTO ConfImpEncabezado (proceso,usuarioid,total,fechainicio,observacion)';
		$sql.= "VALUES ('excelempl','".$this->seguridad->rut."',".$this->highestRow.",'".date("Ymd H:i:s")."','');";
		//$sql.= "VALUES ('excelempl','".$this->seguridad->rut."',".$this->highestRow.",'".date("d-m-Y H:i:s")."','');";
		$this->excelemplBD->Grabar($sql);
		$this->mensajeError.= $this->excelemplBD->mensajeError;	
	}
	
	private function marca_fin()
	{
		$sql = "UPDATE ConfImpEncabezado SET fechatermino = '".date("Ymd H:i:s")."' WHERE proceso = 'excelempl' AND usuarioid = '".$this->usuarioid."';";
		$this->excelemplBD->Grabar($sql);
		$this->mensajeError = $this->excelemplBD->mensajeError;	
		//$this->graba_log("fin:".$sql);
	}
	
	private function valida_importacion()
	{
		if (!isset($_FILES['archivo']['tmp_name']))
		{
			$this->mensajeError.= "Error, no se ha adjuntado un archivo Excel";
			return;
		}
		
		$trozos = explode(".", $_FILES['archivo']['name']); // nombre del archivo
		$extension = end($trozos); 
		$extension = strtolower($extension); //strtolower -> Devuelve string con todos los caracteres alfabéticos convertidos a minúsculas.

		// mostramos la extension del archivo
		if ($extension != "xlsx" && $extension !="xls")
		{
			$this->mensajeError.= "Error, solo se pueden subir archivos Excel! ";
			return;
		}
		
		$carpeta	= "./tmp";
		$carpetatmp	= "./tmp/";
		$archivo 	= "";
		$resultado  = $this->crear_carpeta($carpeta);
		if ($resultado == true)
		{
							
			$archivoxls = "excelempl_".$this->seguridad->rut.".".$extension;
			
			$rutayarchivo = $carpetatmp.$archivoxls;
			if (!COPY ($_FILES['archivo']['tmp_name'], $rutayarchivo))
			{
				$this->mensajeError.= "Error, problemas para copiar el documento";
				return;
			}	
		
		}	
		else
		{
			$this->mensajeError.= "Error, al crear carpeta temporal";
			return;		
		}

		$usuarioid = $this->seguridad->rut;
		$rutayarchivo = $this->deducir_nombre_archivo($usuarioid);
		
		$this->informacion_excel($rutayarchivo);
		
		$this->configuracion();
$this->graba_log($this->highestColumnIndex." _ ".$this->cantidadcolumnas);
		if($this->highestColumnIndex != $this->cantidadcolumnas)
		{
			$this->mensajeError.= "Error, la cantidad de columnas del archivo importado no corresponde, contiene:".$this->highestColumnIndex." configurado:".$this->cantidadcolumnas;
			$this->elimina_archivo($rutayarchivo);
			return;
		}
	}
	
	private function detalle()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		$datos["usuarioingid"]=$this->seguridad->usuarioid;

		$sql = "SELECT fila,resultado,observaciones,tipotransaccion FROM ConfimpResultado WHERE usuarioid = '".$datos["usuarioingid"]."' ORDER BY fila";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$this->mensajeError.=$this->excelemplBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/excelempl_respuesta.html');
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
	
	private function deducir_nombre_archivo($usuarioid)
	{
		$ruta = "";
		$ruta = dirname(__FILE__); 
		$ruta = str_replace("\\","/",$ruta);
		$ruta = $ruta;		
		
		$carpetatmp	= $ruta."/tmp/";
		
		$archivoxls  = "excelempl_".$usuarioid.".xls";
		$archivoxlsx = "excelempl_".$usuarioid.".xlsx";	
		
		$rutayarchivo = "";
		
		if (file_exists($carpetatmp.$archivoxls))
		{
			$rutayarchivo = $carpetatmp.$archivoxls;
		}
		
		if (file_exists($carpetatmp.$archivoxlsx))
		{
			$rutayarchivo = $carpetatmp.$archivoxlsx;
		}
			
		return $rutayarchivo;
	}
	
	private function informacion_excel($rutayarchivo)
	{	
		try 
		{
			$archivo =  $rutayarchivo; // se pasan los datos del archivo y el nombre de la carpeta donde este guardado.
			$this->inputFileType = PHPExcel_IOFactory::identify($archivo); //le pasa el contenido del documento y dice que es extencion excel.
			$this->objReader = PHPExcel_IOFactory::createReader($this->inputFileType); //?
			$this->objPHPExcel = $this->objReader->load($archivo); //?
			$this->sheet = $this->objPHPExcel->getSheet(0); //hoja

			$this->highestRow = $this->sheet->getHighestRow(); //dice cuantas filas son, ejemplo 2


			$this->highestColumn = $this->sheet->getHighestColumn(); //dice hasta que campos llega, ejemplo hasta la E
			$this->highestColumnIndex = PHPExcel_Cell::columnIndexFromString($this->highestColumn); // transforma el campo E, que seria el total a numero
$this->graba_log("Columnas leidas hasta la :".$this->highestColumn." | Cantidad equivalente a : ".$this->highestColumnIndex);
		
		} 
		catch (Exception $e) 
		{
			$this->graba_log ('problemas al leer excel : '.$e->getMessage());
		}		
		
	}
	
	private function configuracion()
	{
		$this->lineainicio 			= 2;	 	//fila donde comienza a procesar
		$this->cantidadaprocesar	= 500;	 	//cantidad de filas a procesar por lotes
		$this->protocolo			= 'https://';//protocolo
		
		//*************aqui configuramos 
		//nombre|tipo|largo|obligatorio|solicitado|campo tabla personas|campos tabla empleados
		//tipo:1=string 2:numero 3=rut 4=fecha  
		//obligatorio:1=obligatorio, o sea debe venir un valor
		//solicitado:1=tiene que venir como columna presente en la planilla
		
		/*standard
		$dato[0]  = "rut trabajador		|3|10	|1|1|personaid		|empleadoid";
		$dato[1]  = "nombre				|1|250	|1|1|nombre			|";
		$dato[2]  = "ap. paterno		|1|50	|1|1|appaterno		|";
		$dato[3]  = "ap. materno		|1|50	|0|1|apmaterno		|";
		$dato[4]  = "nacionalidad		|1|10	|0|0|nacionalidad	|";
		$dato[5]  = "estado civil		|1|10	|0|0|estadocivil	|";
		$dato[6]  = "fecha nacimiento	|4|10	|0|0|fechanacimiento|";
		$dato[7]  = "comuna				|1|10	|0|0|comuna			|";
		$dato[8]  = "ciudad				|1|10	|0|0|ciudad			|";
		$dato[9]  = "region				|1|10	|0|0|region			|";
		$dato[10] = "correo				|1|60	|0|1|correo			|";
		$dato[11] = "fono				|1|10	|0|0|fono			|";
		$dato[12] = "rut empresa		|3|10	|1|1|				|empresaid";
		$dato[13] = "rol				|2|10	|1|1|				|rolid";
		$dato[14] = "estado				|2|10	|1|1|				|estado";
		$dato[15] = "div.personal		|1|14	|1|1|				|centrocostoid";
		$dato[16] = "lugar de pago		|1|14	|1|0|				|lugarpagoid";
		$dato[17] = "departamento		|1|14	|1|0|				|departamentoid";
		*/
		
		$dato[0] = "RUT									|3|10	|1|1|personaid			|empleadoid";
		$dato[1] = "NOMBRES								|1|250	|1|1|nombre				|";
		$dato[2] = "APELLIDO PATERNO					|1|50	|1|1|appaterno			|";
		$dato[3] = "APELLIDO MATERNO					|1|50	|0|1|apmaterno			|";
		$dato[4] = "RUT EMPRESA							|3|10	|1|1|					|RutEmpresa";
		//	$dato[5] = "RAZON SOCIAL						|3|10	|0|0|					|";
		$dato[5] = "CODCENCO							|1|14	|1|1|					|centrocostoid";
		$dato[6] = "NOMBRE CENCO						|1|50	|0|0|					|nombrecentrocosto";
		$dato[7] = "CODLUGPAG							|1|14	|1|1|					|lugarpagoid";
		$dato[8] = "NOMBRE LUGPAG						|1|50	|0|0|					|nombrelugarpago";
		$dato[9] = "ROL									|2|10	|1|1|					|rolid";
		$dato[10] = "ESTADO								|1|10	|1|1|					|idEstadoEmpleado";
		$dato[11] = "Correo Electronico Personal		    |1|60	|0|1|correo				|";

		//codigo rol, el indice es el codigo como queda en la tabla, el nombre como debe venir en la  planilla
		$this->rolexcel[0]	= "GENERAL";	//0=publico
		$this->rolexcel[1]	= "PRIVADO";	//1=privado
		
		//codigo estado,el indice es el codigo como queda en la tabla, el nombre como debe venir en la  planilla
		$this->estadoexcel[0]	= "ACTIVO";		//0=activo
		$this->estadoexcel[1]	= "FINIQUITADO";	//1=finiquitado
		
		$this->inicio_script_sql ($dato);
	}
	
	private function inicio_script_sql($dato)
	{
		//inicio script para consulta
		$this->personas_select_ini  = "SELECT personaid  FROM personas  WHERE personaid   = ";
		$this->empleados_select_ini = "SELECT empleadoid FROM empleados ";
		
		//inicio script para insert
		$this->personas_insert_ini  = "INSERT INTO personas  (";
		$this->empleados_insert_ini = "INSERT INTO empleados (";

		//inicio script para update
		$this->personas_update_ini  = "UPDATE personas  SET ";
		$this->empleados_update_ini = "UPDATE empleados SET ";
		
		$this->colconfig = count($dato);
		$cantcol=0;
		for ($d = 0; $d < $this->colconfig; $d++)//va recorriendo dato por dato de la configuración
		{ 
			$dato_arr = explode("|",$dato[$d]);
			$this->nombre_columna[$d]	= trim($dato_arr[0]);
			$this->tipodato[$d] 		= trim($dato_arr[1]);
			$this->largo[$d] 			= trim($dato_arr[2]);
			$this->obligatorio[$d] 		= trim($dato_arr[3]);
			$this->solicitado[$d] 		= trim($dato_arr[4]);
			$this->campo_persona[$d] 	= trim($dato_arr[5]);
			$this->campo_empleado[$d] 	= trim($dato_arr[6]);
			
			$cantcol++;
			
			if ($this->solicitado[$d] == 1)
			{	//$this->graba_log("campo persona:".$this->campo_persona[$d]);
				
				//arma los script para insert de persona y empleado, rescata los nombres de los campos de la tabla
				if (trim($this->campo_persona[$d])  != '')
					$this->personas_insert_ini.=  $this->campo_persona[$d].",";
				
				if (trim($this->campo_empleado[$d]) != '')
					$this->empleados_insert_ini.= $this->campo_empleado[$d].",";
			}
		}	
		
		//$this->graba_log("sel persona:".$this->personas_insert);
		
		//para quitar ultima coma del script de insertar
		$this->personas_insert_ini  = substr($this->personas_insert_ini,  0, -1);
		$this->empleados_insert_ini = substr($this->empleados_insert_ini, 0, -1);
		
		//completar los datos de personas
		$this->personas_insert_ini.=",Eliminado";
		
		//finaliza armado script para insert
		$this->personas_insert_ini.=  ") VALUES (";
		$this->empleados_insert_ini.= ") VALUES (";
		
		$this->cantidadcolumnas = $cantcol;		
	}
	
	private function proceso()
	{	
		$this->graba_log ("se inicia proceso");	
		if (isset($_POST["usuarioid"]))
		{
			$this->usuarioid = $_POST["usuarioid"];
		}
		else
		{
			$this->usuarioid = $this->seguridad->rut;
			$this->graba_log ("se inicia por REACTIVACION");	
		}
		
		$this->graba_log ("usuario ".$this->usuarioid);	
		
		$rutayarchivo = $this->deducir_nombre_archivo($this->usuarioid);
		$this->graba_log ("rutaarchivo ".$rutayarchivo);	
		if ($rutayarchivo == '')
		{
			$this->bd->desconectar();
			$this->graba_log ("Error, no existe archivo ".$rutayarchivo);	
			exit;
		}
	
		$this->informacion_excel($rutayarchivo);
		
		$this->configuracion();
		
		$sql = "SELECT COUNT(*) AS cantidad FROM ConfImpResultado WHERE usuarioid = '".$this->usuarioid."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			$this->bd->desconectar();
			exit;
		}	
		
		if($dt->leerFila())
		{
			if ($dt->obtenerItem("cantidad") > 0)
			{
				$this->lineainicio = $dt->obtenerItem("cantidad");
				$this->lineainicio++;
			}
		}
		
		$this->recorre_excel($rutayarchivo);
	}
	
	private function recorre_excel($rutayarchivo)
	{
		$this->graba_log ("inicio recorre filas inicia desde ".$this->lineainicio);
		$this->graba_log ("hasta ".$this->highestRow);
		$filasleidas = 0;
		for($fila=$this->lineainicio; $fila <= $this->highestRow; $fila++)// se recorre segun la cantidad de filas
		{
			$filasleidas++;
			$mensaje = '';
			$this->inicializa_script();
			
			$columnafila = 0;
			$solicitado  = 0;
			for ($col = 0; $col < $this->colconfig ; $col++)//va recorriendo celda por celda
			{ 
				$solicitado				= $this->solicitado[$col];
				//$this->graba_log('solicitado: '.$solicitado." ".$this->campo_persona[$col]);
				if ($solicitado == 1)
				{
					$colString 				= PHPExcel_Cell::stringFromColumnIndex($columnafila);//transforma de numero a letra
					$columnaYfila 			= $colString.$fila;
				
					$valorcelda				= $this->sheet->getCell($columnaYfila)->getValue();
					$valorcelda				= str_replace("'","´",$valorcelda);
					//$this->graba_log('prueba valor resultado :'.$valorcelda." colfila:".$columnaYfila." col:".$col);
					$tipodato	= $this->tipodato[$col];
					if ($tipodato == 4)
					{
						if (is_numeric($valorcelda))
						{
							$valorcelda = date("d-m-Y", strtotime("+1 day", PHPExcel_Shared_Date::ExcelToPHP($valorcelda)));
						}		
						else
						{
							$valorcelda = str_replace("/","-",$valorcelda);
						}
						//$this->graba_log('fecha :'.$valorcelda);
					}
					
					$mensaje = $this->valida($valorcelda,$tipodato,$col);
					
					if ($mensaje != '')
					{
						//$this->graba_log('mensaje: '.$mensaje.' '.$this->nombre_columna[$col]);
						break;
					}
					
					$this->arma_script_sql($tipodato, $valorcelda,$col);
								
					
				}
				
				$columnafila++;
			}	
			
			
			if ($mensaje == '')
			{
				$this->finaliza_script();
				
				$mensaje = $this->graba();
				
				if ($mensaje == ''){ $resultado = 'OK';} else {$resultado = 'ERROR';$this->tipo_transaccion='';}
				$this->graba_resultado ($fila,$resultado,$mensaje,$this->tipo_transaccion);
			}
			else
			{
				$this->graba_resultado ($fila,'ERROR',$mensaje,'');
			}
			
			if ($filasleidas == $this->cantidadaprocesar)
			{
				$this->graba_log ("llego a tope recorre filas ".$filasleidas);
				$datos["usuarioid"] = $this->usuarioid;
				$resul = $this->autollamada($datos);
				if ($resul == false)
				{
					$this->graba_log ("segundo intento");
					$resul = $this->autollamada($datos);
					if ($resul == false)
					{
						$this->graba_log ("tercer intento");
						$resul = $this->autollamada($datos);
					}
				}
				
				$this->bd->desconectar();
				exit;	
			}
		
		}	
		
		$this->elimina_archivo($rutayarchivo);
		
		$this->marca_fin();
		
		$this->bd->desconectar();
			
		$this->graba_log ("llego al fin recorre filas");
		
		exit;
	}

	private function graba_resultado ($fila,$resultado,$mensaje,$tipo_transaccion)
	{
		$mensaje = str_replace("'","´",$mensaje);
		$mensaje = str_replace(",",".",$mensaje);
		$sql = "INSERT INTO ConfImpResultado (usuarioid,fila,resultado,observaciones,tipotransaccion) VALUES (";
		$sql.= "'".$this->usuarioid."',".$fila.",'".$resultado."',"."'".$mensaje."',"."'".$tipo_transaccion."');";
		$this->excelemplBD->Grabar($sql);
		$errsql = $this->excelemplBD->mensajeError;
		//$this->graba_log("graba resultado: ".$sql);
		if ($errsql != "")
		{
			$this->graba_log("error resultado:".$errsql);
		}
	}

	private function finaliza_script()
	{
		$wempleado = '';
		$this->personas_insert  = substr($this->personas_insert,  0, -1);
		$this->empleados_insert = substr($this->empleados_insert, 0, -1);

		$this->personas_update  = substr($this->personas_update,  0, -1);
		$this->empleados_update = substr($this->empleados_update, 0, -1);	
		
		$this->personas_insert.= ",0);";
		$this->empleados_insert.= ");";
		
		$wempleado = " WHERE empleadoid  ='".$this->xempleadoid."'"; //AND empresaid = '".$this->xempresaid."'";
		$this->empleados_select.= $wempleado;
		$this->empleados_update.= $wempleado;
		
		$this->personas_update.=  $this->personas_update_fin.";";
		
	}

	private function graba()
	{
		$this->tipo_transaccion = "";
		/*$this->graba_log ("empleados_insert ".$this->empleados_insert);
		$this->graba_log ("personas_insert  ".$this->personas_insert);
		$this->graba_log ("empleados_update ".$this->empleados_update);
		$this->graba_log ("personas_update  ".$this->personas_update);
		$this->graba_log ("personas_select  ".$this->personas_select);
		$this->graba_log ("empleados_select ".$this->empleados_select);	*/
		
		$dt = new DataTable();
		$this->excelemplBD->mensajeError = "";
		
		$sql = $this->personas_select;
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar persona '.$mensaje;;
		}	
		
		if (!$dt->leerFila())
		{
			//$this->graba_log ("insert persona");	
			$sql = $this->personas_insert;
		}
		else
		{
			//$this->graba_log ("update persona");	
			$sql = $this->personas_update;
		}
		
		$this->excelemplBD->Grabar($sql);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al grabar persona '.$mensaje;;
		}

		
		$sql = $this->empleados_select;
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar trabajador '.$mensaje;;
		}	
		
		if (!$dt->leerFila())
		{
			$sql = $this->empleados_insert;
			$this->tipo_transaccion = "Nuevo registro";
		}
		else
		{
			$sql = $this->empleados_update;
			$this->tipo_transaccion = "Registro modificado";
		}
		//$this->graba_log("sql empl: ".$sql);
		$this->excelemplBD->Grabar($sql);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al grabar empleado '.$mensaje;
		}

		return '';
			
	}

	private function inicializa_script()
	{
		$this->empleados_insert = $this->empleados_insert_ini;
		$this->personas_insert 	= $this->personas_insert_ini;
		$this->empleados_update = $this->empleados_update_ini;
		$this->personas_update 	= $this->personas_update_ini;
		$this->personas_select 	= $this->personas_select_ini;
		$this->empleados_select = $this->empleados_select_ini;	
		$this->personas_update_fin 	= '';
		$this->empleados_update_fin = '';
		$this->xempleadoid='';
		$this->xempresaid='';
	}

	private function arma_script_sql ($tipodato, $valorcelda,$col)
	{
		$valorcelda = trim($valorcelda);
		
		//codificamos rol y estado, en caso de que el cliente tenga otros valores
		if ($this->campo_empleado[$col] == 'rolid')
		{
			$valorcelda = $this->codifica_rol($valorcelda) == 0 ? 2 : 1;
		}
				
		if ($this->campo_empleado[$col] == 'estado' || $this->campo_empleado[$col] == 'idEstadoEmpleado')
		{
			$valorcelda = $this->codifica_estado($valorcelda) == 0  ? 'A' : 'F';
		}
		//fin	
		
		//si no es obligatorio y no viene un valor, contatenamos NULL a script sql
		if ($this->obligatorio[$col] == 0 && $valorcelda == "")
		{
			if ($this->campo_persona[$col] != '')
			{
				$this->personas_insert.= 'NULL,';
				$this->personas_update.= $this->campo_persona[$col].' = NULL,';	
			}
			
			if ($this->campo_empleado[$col] != '')
			{
				$this->empleados_insert.= 'NULL,';
				$this->empleados_update.= $this->campo_empleado[$col].' = NULL,';	
			}
			return;
		}
		
		if ($tipodato != 2)
		{	
			if ($this->campo_persona[$col] != '')
			{
				$this->personas_insert.= "'".$valorcelda."',";
				if ($this->campo_persona[$col] != 'personaid' && $this->campo_persona[$col] != '')
				{
					$this->personas_update.= $this->campo_persona[$col]." = '".$valorcelda."',";
				}
			}
		}
		else
		{
			if ($this->campo_persona[$col] != '')
			{
				$this->personas_insert.= $valorcelda.",";
				if ($this->campo_persona[$col] != 'personaid' && $this->campo_persona[$col] != '')
				{
					$this->personas_update.= $this->campo_persona[$col]." = ".$valorcelda.",";
				}
			}
		}
				
		if ($tipodato != 2)
		{
			if ($this->campo_empleado[$col] != '')
			{
				$this->empleados_insert.= "'".$valorcelda."',";
				if ($this->campo_empleado[$col] != 'empleadoid')// && $this->campo_empleado[$col] != 'empresaid')
				{
					$this->empleados_update.= $this->campo_empleado[$col]." = '".$valorcelda."',";
				}
			}
		}
		else
		{
			if ($this->campo_empleado[$col] != '')
			{
				$this->empleados_insert.= $valorcelda.",";
				if ($this->campo_empleado[$col] != 'empleadoid')// && $this->campo_empleado[$col] != 'empresaid')
				{
					$this->empleados_update.= $this->campo_empleado[$col]." = ".$valorcelda.",";
				}
			}
		}		
		
		if ($this->campo_persona[$col] == 'personaid')
		{
			$this->personas_select.= "'".$valorcelda."';";
			$this->personas_update_fin.= " WHERE personaid = '".$valorcelda."'";
		}
		
		if ($this->campo_empleado[$col] == 'empleadoid')
		{
			$this->xempleadoid = $valorcelda;
		}
		
		if ($this->campo_empleado[$col] == 'empresaid')
		{
			$this->xempresaid = $valorcelda;
		}
		
		
	}
	
	private function autollamada($datos)
	{
		$tiempoespera = 2000;
		if (isset($datos["inicial"]))
		{
			$tiempoespera = 1;
		}
		$url = $this->ObtenerURL();
			
		$parametros = "accion=PROCESO";
		$parametros.="&usuarioid=".$datos["usuarioid"];
		
		$this->graba_log("param:".$parametros);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $parametros);
		
		if (substr($url, 0, 8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 4);
		}
		
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, $tiempoespera);

		curl_exec($ch);
		
		if (!isset($datos["inicial"]))
		{
			if (curl_errno($ch)) 
			{
				$error_msg = curl_error($ch);
				$this->graba_log('curl error:'.$error_msg." tiempo conf:".$tiempoespera);
				return false;
			}	
			else
			{
				$this->graba_log('curl ok');
			}
		}
		
		curl_close($ch);
		
		return true;
	}
	
	private function ObtenerURL(){
		
		$http = $this->protocolo;
		
		$url=$http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		$this->graba_log('url:'.$url);
		return $url;
	}	

	private function valida($valorcelda,$tipodato,$col)
	{
		
		
		if (trim($valorcelda) == "")
		{
			if ($this->obligatorio[$col] == 1)
			{
				return 'el dato es obligatorio columna:'.$this->nombre_columna[$col]." contenido celda:".$valorcelda;
			}
			else
			{
				//si no es obligatorio y no viene un valor, salimos de la validacion.
				return '';
			}
			
		}
		
		$campo = $this->campo_empleado[$col];
		if ($campo == 'rolid')
		{
			$codigorol = -1;
			$codigorol = $this->codifica_rol($valorcelda);
			//$codigorol = $valorcelda;
			
			if ($codigorol == -1)
			{
				return 'rol no corresponde '.$valorcelda;
			}
			
			$valorcelda = $codigorol;
		}
		
		if ($campo == 'estado')
		{
			$codigoestado = -1;
			$codigoestado = $this->codifica_estado($valorcelda);
			
			if ($codigoestado == -1)
			{
				return 'estado no corresponde '.$valorcelda;
			}
			
			$valorcelda = $codigoestado;
		}
		
		$mensaje = $this->valida_tipodato($valorcelda,$tipodato,$col);
		if ($mensaje != '')
		{
			return $mensaje;
		}
		
		$max_largo_campo = $this->largo[$col];
		$resp = $this->valida_largo($valorcelda,$max_largo_campo);
		if ($resp == false)
		{
			return 'largo del contenido de la celda supera lo permitido columna:'.$this->nombre_columna[$col]." contenido celda:".$valorcelda." largo configurado:".$max_largo_campo;
		}
		

		
		if ($this->solicitado[$col] == 1)
		{
			if ($campo == 'centrocostoid')
			{
				$mensaje = $this->valida_centrocosto($valorcelda,$col);
				if ($mensaje != '')
				{
					return $mensaje;
				}
			}
		}
		
		if ($this->solicitado[$col] == 1)
		{
			if ($campo == 'lugarpagoid')
			{
				$mensaje = $this->valida_lugarpago($valorcelda,$col);
				if ($mensaje != '')
				{
					return $mensaje;
				}
			}
		}
		
		if ($this->solicitado[$col] == 1)
		{
			if ($campo == 'departamentoid')
			{
				$mensaje = $this->valida_departamento($valorcelda,$col);
				if ($mensaje != '')
				{
					return $mensaje;
				}
			}
		}
		
		if ($this->solicitado[$col] == 1)
		{
			if ($campo == 'empresaid')
			{
				$mensaje = $this->valida_empresa($valorcelda,$col);
				if ($mensaje != '')
				{
					return $mensaje;
				}
			}
		}
		
		return '';
	}

	private function valida_centrocosto ($valorcelda,$col)
	{
		$nombre = $this->nombre_columna[$col];
		
		$dt = new DataTable();
		
		$sql = "select centrocostoid from centroscosto where centrocostoid = '".$valorcelda."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar '.$nombre;
		}
		
		if(!$dt->leerFila())
		{
			return 'no existe '.$nombre." contenido celda:".$valorcelda;
		}
		
		return '';
	}

	private function valida_lugarpago ($valorcelda,$col)
	{
		$nombre = $this->nombre_columna[$col];
		
		$dt = new DataTable();
		
		$sql = "select lugarpagoid from lugarespago where lugarpagoid = '".$valorcelda."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar '.$nombre." dato:".$valorcelda;
		}
		
		if(!$dt->leerFila())
		{
			return 'no existe '.$nombre." contenido celda:".$valorcelda;
		}
		
		return '';
	}

	private function valida_departamento ($valorcelda,$col)
	{
		$nombre = $this->nombre_columna[$col];
		
		$dt = new DataTable();
		
		$sql = "select departamentoid from departamentos where departamentoid = '".$valorcelda."'";
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar '.$nombre." dato:".$valorcelda;
		}
		
		if(!$dt->leerFila())
		{
			return 'no existe '.$nombre." contenido celda:".$valorcelda;
		}
		
		return '';
	}

	private function valida_empresa ($valorcelda,$col)
	{
		$nombre = $this->nombre_columna[$col];
		
		$dt = new DataTable();
		
		$sql = "select empresaid from empresas where empresaid = '".$valorcelda."'";
		
		$this->excelemplBD->ConsultarX($sql,$dt);
		$mensaje=$this->excelemplBD->mensajeError;
		if ($mensaje != '')
		{
			return 'problemas al consultar '.$nombre." (".$valorcelda.")";
		}
		
		if(!$dt->leerFila())
		{
			return 'no existe '.$nombre." contenido celda:".$valorcelda;
		}
		
		return '';
	}

	private function valida_tipodato($valorcelda,$tipodato,$col)
	{
		//tipos:1=string 2:numero 3=rut 4=fecha
		$nombre = $this->nombre_columna[$col];
		
		if($tipodato == 1)
		{
			if(!is_string($valorcelda)) 
			{
				return  $this->nombre_columna[$col].' debe ser un valor alfanúmerico ('.$valorcelda.")";
			}
			return '';
		}
		
		if($tipodato == 2)
		{
			if(is_numeric($valorcelda) )
			{
				return '';
			}
			return $this->nombre_columna[$col].' debe ser un valor númerico ('.$valorcelda.")";
		}
		
		if($tipodato == 3)
		{
			$res = $this->valida_rut($valorcelda);
			if ($res == false)
			{
				return $this->nombre_columna[$col].' rut no valido ('.$valorcelda.")";
			}
			
			return '';
		}
		
		if($tipodato == 4)
		{
			$res = $this->valida_fecha($valorcelda);
			
			if ($res == false)
			{
				return $this->nombre_columna[$col].' fecha no valida ('.$valorcelda.")";
			}
			
			return '';
			
		}
	  
		
	}

	private function valida_largo($valorcelda,$Ancho)
	{
		$str = $valorcelda;
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


	private function validarDatoObligatorio($valorcelda,$Obligatorio)
	{
		$valorcelda = trim($valorcelda);
		//print($DatoObligatorio);

		if ($Obligatorio == 1 && $valorcelda == "")
		{
			return false;
		}
	   
		return true;
	}

	private function valida_fecha ($fecha)
	{
		$format = "d-m-Y";$format = "d-m-Y";
		$d = DateTime::createFromFormat($format, $fecha);
		return $d && $d->format($format) == $fecha;			
	}

	private function valida_rut($rut)
	{
		$rut=trim($rut);
		$pos = strpos($rut, "-");
		if ($pos === false) {return false;}
		
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

	private function codifica_rol ($valorcelda)
	{	//$this->graba_log("***".$valorcelda);
		$codigorol = -1;
		for ($r = 0; $r < count($this->rolexcel); $r++)
		{	
			if ( strtolower($this->rolexcel[$r]) == strtolower(trim($valorcelda)) )
			{	
				$codigorol = $r;
				break;
			}
		}	

		return $codigorol;
	}
	
	private function codifica_estado ($valorcelda)
	{
		$codigoestado = -1;
		for ($e = 0; $e < count($this->estadoexcel); $e++)
		{ 
			if ( strtolower($this->estadoexcel[$e]) == strtolower(trim($valorcelda)) )
			{
				$codigoestado = $e;
				break;
			}
		}	

		return $codigoestado;
	}
	
	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/excelempl_'.@date("Ymd").'.TXT';
		$ar=@fopen($nomarchivo,"a");
		//die ("Problemas en la creacion");
		@fputs($ar,@date("H:i:s")." ".$mensaje);
		@fputs($ar,"\n");
		@fclose($ar);      
	}
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


	

}
?>