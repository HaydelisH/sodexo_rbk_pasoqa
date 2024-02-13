<?php
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/empleadosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/importacion_firmaBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/procesosBD.php");

//para proceso de separado de pdf
include ('includes/pdftotext/PdfToText.phpclass');

require_once('includes/tcpdf/tcpdf.php');
require_once('includes/tcpdf/tcpdi.php');

$page = new importacion_firma();

class importacion_firma {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $empleadosBD;
	// para el manejo de las tablas
	private $separapdfBD;	
	// para juntar los mensajes de error
	private $mensajeError="";
	private $mensajeError2="";
	private $mensajeError3="";
	
	private $mensajeOK="";
	private $User="";
	private $Password="";
	private $Url="";
	
	private $lineastexto;
	private $lineaproceso;
	private $ultimalineafecha;
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	private $nroopcion=10; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;
	
	private $NomPdf="";
	private $carpeta="";
	
	private $nropdf;
	private $pdfaprocesar;
	private $pdfaprocesarcopiar;
	private $rutsindv;
	private $hojasxdocumento;
	private $rutadescartar;
	private $rutadescartar_arr;
	
	private $cantidadpaginas;
	private $rutasitio;
	
	// funcion contructora, al instanciar
	function __construct()
	{

		// revisamos si la accion es volver desde el listado principal
		if (isset($_REQUEST["accion"]))
		{
			// si lo es
			if ($_REQUEST["accion"]=="Volver")
			{
				// nos devolvemos al lugar especificado
				header('Location: importacionpdf.php');
				return;
			}
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

			// mostramos el encabezado
			$this->pagina->imprimirTemplate('templates/encabezado.html');
			$this->pagina->imprimirTemplate('templates/encabezadoFin.html');


			// imprimimos el template
			$this->pagina->imprimirTemplate('templates/puroError.html');
			// Imprimimos el pie
			$this->pagina->imprimirTemplate('templates/piedepagina.html');
			// y nos vamos
			return;
		}
		//print_r ($_REQUEST);
		// creamos la seguridad
		
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;
		
		$this->opcion = "Importaci&oacute;n MasivaPDF";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-file-pdf-o";
		//$this->opcionnivel1 = "Gestor";
		$this->opcionnivel2 = "<li>Importaci&oacute;n Masiva PDF</li>";
		
		// instanciamos del manejo de tablas
		$this->empleadosBD = new empleadosBD();
		$this->empresasBD = new empresasBD();
		$this->importacion_firmaBD = new importacion_firmaBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->tiposdocumentosBD = new tiposdocumentosBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->procesosBD = new procesosBD();
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->empresasBD->usarConexion($conecc);
		$this->empleadosBD->usarConexion($conecc);
		$this->importacion_firmaBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		
		$ruta = "";
		$ruta = dirname(__FILE__); 
		$ruta = str_replace("\\","/",$ruta);
		$this->rutasitio = $ruta;	

		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r ($_REQUEST);
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]) && (!isset($_REQUEST["accion2"])))
		{
			// mostramos el listado
			$this->importa();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
	
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_REQUEST["accion"])
		{
			case "GENERAR":
				$this->procesar();
				break;	

			case "Selrep":
				$this->seleccionar_representante();
				break;						

			case "VOLVER":
				$this->importa();
				break;						
					
		}

		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "PROCESOANTERIOR":
					$this->proceso_anterior();
					break;
			}
			
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function procesar()
	{
		if ($_REQUEST["accion2"] == "Inicio")
		{
			$this->importa();
		}
		
		if ($_REQUEST["accion2"] == "Listado")
		{
			$this->listado();
		}
	
	}
	
	private function proceso_anterior()
	{
		$datos["usuarioid"]=$this->seguridad->rut;
		$this->importacion_firmaBD->Listado($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		$cantidad = 0;
		$noenviados = 0;
		$cantidad = count($dt->data);
		$formulario[0]["listado"]=$dt->data;	
		
		$archivo = $this->rutasitio."/tmp/liq_".$this->seguridad->rut."/imp_".$this->seguridad->rut.".pdf";
		//$this->graba_log("ARCHIVO BOTON ".$archivo);
		if (!file_exists($archivo))
		{	//$this->graba_log("NO EXISTE ARCHIVO BOTON ".$archivo);
			$datos["pagina"] = 0;
			$this->importacion_firmaBD->ObtenerNoEnviado($datos,$dt2);
			$this->mensajeError.=$this->importacion_firmaBD->mensajeError;
			if($dt2->leerFila())
			{
				$noenviados = count($dt2->data);
				$formulario2[0]=$_REQUEST;
				$this->pagina->agregarDato("formulario2",$formulario2);
			}
		}
		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("noenviados",$noenviados);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/importacion_firma_listado.html');
		
	}
	
	private function importa()
	{	
		//print_r ($_REQUEST);
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		$datos["rut"]=$this->seguridad->rut;

		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;		
	
		$this->PlantillasBD->obtenerTiposDocumentos($dt);
		$this->mensajeError.=$this->PlantillasBD->mensajeError;
		$formulariox[0]["tiposdocumentos"]=$dt->data;		
		
		$this->procesosBD->listado($dt);
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$formulariox[0]["procesos"]=$dt->data;	
		
		$ruta = "";
		$ruta = dirname(__FILE__); 
		$ruta = str_replace("\\","/",$ruta);
		$ruta = $ruta;	
		$archivo = $ruta."/tmp/liq_".$this->seguridad->rut."/imp_".$this->seguridad->rut.".pdf";
		if (file_exists($archivo))
		{
			$datos["usuarioid"]=$this->seguridad->rut;
			$this->importacion_firmaBD->ObtenerUltimaPagina($datos,$dt);
			$this->mensajeError.=$this->procesosBD->mensajeError;
			if($dt->leerFila())	
			{
				$datos["highestRow"] = $dt->data[0]["totalpaginas"] + 1;
			}
		}
		
		$formulario[0]=$datos;
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];
		$formulario[0]["representantes"]=$formulariox[0]["representantes"];
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];
		$formulario[0]["procesos"]=$formulariox[0]["procesos"];
		
		//print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/importacion_firma.html');
	}


	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
	
	private function listado()
	{	
		//print_r ($_REQUEST);
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		$datos["usuarioid"]=$this->seguridad->rut;

		$this->empresasBD->Todos($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		if (count($dt->data) > 1)
		{
			for ($a=0; $a<count($dt->data); $a++) {
				$dta->data[$a + 1] = $dt->data[$a];
			}
			$dt->data = $dta->data;
		}
		$formulariox[0]["empresas"]=$dt->data;
		if (count($dt->data) > 1)
		{
			$formulariox[0]["empresas"][0]["empresaid"]="-1";
			$formulariox[0]["empresas"][0]["nombre"]="(Seleccione)";
		}		
		unset($dta);			
	
		$this->importacion_firmaBD->ListaRepresentantes($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;
		if (count($dt->data) > 1)
		{
			for ($a=0; $a<count($dt->data); $a++) {
				$dta->data[$a + 1] = $dt->data[$a];
			}
			$dt->data = $dta->data;
		}
		$formulariox[0]["representantes"]=$dt->data;
		if (count($dt->data) > 1)
		{
			$formulariox[0]["representantes"][0]["Rut"]="-1";
			$formulariox[0]["representantes"][0]["Nombre"]="(Seleccione)";
		}	
		unset($dta);	
		
		$this->importacion_firmaBD->ListaTiposDocumentosMas($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;
		if (count($dt->data) > 1)
		{
			for ($a=0; $a<count($dt->data); $a++) {
				$dta->data[$a + 1] = $dt->data[$a];
			}
			$dt->data = $dta->data;
		}
		$formulariox[0]["tiposdocumentos"]=$dt->data;
		if (count($dt->data) > 1)
		{
			$formulariox[0]["tiposdocumentos"][0]["tipodocumentoid"]="-1";
			$formulariox[0]["tiposdocumentos"][0]["nombre"]="(Seleccione)";
		}			
		unset($dta);
	
		$this->importacion_firmaBD->ListaProcesos($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;
		if (count($dt->data) > 1)
		{
			for ($a=0; $a<count($dt->data); $a++) {
				$dta->data[$a + 1] = $dt->data[$a];
			}
			$dt->data = $dta->data;
		}
		$formulariox[0]["procesos"]=$dt->data;
		if (count($dt->data) > 1)
		{
			$formulariox[0]["procesos"][0]["proceso"]="-1";
			$formulariox[0]["procesos"][0]["descripcion"]="(Seleccione)";
		}			
		unset($dta);	
		
		$this->importacion_firmaBD->Listado($datos ,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;	
		$cantidad = count($dt->data);
		$formulario[0]=$datos;
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];
		$formulario[0]["representantes"]=$formulariox[0]["representantes"];
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];
		$formulario[0]["procesos"]=$formulariox[0]["procesos"];
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		
		//------
		$ruta = "";
		$ruta = dirname(__FILE__); 
		$ruta = str_replace("\\","/",$ruta);
		$ruta = $ruta;		
		
		$archivo = $ruta."/tmp/imp_".$this->seguridad->rut.".pdf";
		
		if (!file_exists($archivo))
		{
			$this->mensajeError.="ERROR, no existe el documento ya previamente importado, debe procesar nuevamente ".$archivo;
		}
		else
		{
			try
			{
				$new_pdf = new FPDI();
				$pagecount = $new_pdf->setSourceFile($archivo);	// Cuantas Paginas?
			}
			catch (Exception $e) 
			{
				$this->mensajeError = 'Error, al leer pdf: '.$e->getMessage();
			}
		}
			
		$this->importacion_firmaBD->ObtenerConfiguracion($datos,$dt);
		$this->mensajeError.=$this->importacion_firmaBD->mensajeError;	
		if(!$dt->leerFila())
		{
			$this->mensajeError.="ERROR, Tipo de Contrato no esta configurado para este proceso";
		}
		else
		{
			$this->hojasxdocumento 		= $dt->data[0]["paginasxdocumento"];
			$cantidaddoc 				= $pagecount / $this->hojasxdocumento;
		}
		
		if ($cantidad != $cantidaddoc)//cantidad = paginas ya importadas, $cantidaddoc = paginas del documento dividido por hojas que debe separar en el pdf
		{
			$this->mensajeError.="ERROR, Quedaron paginas pendientes por importar, debe REPROCESAR.";
			$pdfprocesados	= $cantidad;
			$pdftotal		= $cantidaddoc;
			$reprocesar		= "reprocesar";
			$pdfinicio 		= $cantidad * $this->hojasxdocumento;
			$pdfinicio      = $pdfinicio + 1;
			$this->pagina->agregarDato("pdfprocesados",$pdfprocesados);
			$this->pagina->agregarDato("pdftotal",$pdftotal);
			$this->pagina->agregarDato("reprocesar",$reprocesar);
			$this->pagina->agregarDato("pdfinicio",$pdfinicio);
			
			$pdfaprocesar = "tmp/imp_".$this->seguridad->rut.".pdf";
			$this->pagina->agregarDato("pdfaprocesar",$pdfaprocesar);
			$this->pagina->agregarDato("cantidad",$pagecount);
		}
		else
		{
			$this->pagina->agregarDato("cantidad",$cantidad);
		}
		//-------
		

		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Proceso finalizado favor revisar detalle";
		}
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/importacion_firma_listado.html');
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

}
?>
