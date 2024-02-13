<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/docvigentesBD.php");
include_once("includes/tiposusuariosBD.php");
include_once('includes/Seguridad.php');

// creamos la instacia de esta clase
$page = new consultageneral_excel();

class consultageneral_excel {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $gestionesBD;
	private $docvigentesBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";

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
				header('Location: index.php');
				return;
			}
		}

		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// hacemos una instacia del manejo de plantillas (templates)
			$this->pagina = new Paginas();

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

		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);

		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) {$this->pagina = new Paginas(); return;}

		$this->pagina = new Excel();
		
		$fechahoy="";
		$fechahoy=@date("dmY Hms");
		$nombrearchivo = "Reportes_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Listado de Documentos"));

		// instanciamos del manejo de tablas
		$this->docvigentesBD = new docvigentesBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		
		$conecc=$this->bd->obtenerConexion();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->docvigentesBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt1 = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;
		
		foreach ($datos as $key => $value) {
			$this->graba_log($datos[$key]);
		}
		
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["pagina"] = 1;
		$datos["decuantos"] = 10;

		//busco el total de paginas
		$this->docvigentesBD->total($datos,$dt1);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["decuantos"]=$dt1->data[0]["total"] * 10;
		
		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;

		foreach ($dt->data as $key => $value) {
			$dt->data[$key]["Empleado"] = $dt->data[$key]["nombre"].' '.$dt->data[$key]["appaterno"].' '.$dt->data[$key]["apmaterno"];
		}

		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
				"idDocumento",
				"NombreTipoDoc",
				"Proceso",
				"Estado",
				"Firma",
				"FechaCreacion",
				"RutEmpresa",
				"RazonSocial",
				"Rut",
				"Empleado"
			);

		$descripciones = array(
				"idDocumento",
				utf8_decode("NombreTipoDoc"),
				utf8_decode("Proceso"),
				utf8_decode("Estado"),
				utf8_decode("Firma"),
				"FechaCreacion",
				"RutEmpresa",
				utf8_decode("RazonSocial"),
				"Rut",
				utf8_decode("Empleado")
			);

		$tipos = array("normal","normal","normal","normal","normal","normal","normal","normal","normal","normal");

		$ancho = array("10","10","30","15","30","15","30","15","20","30");
		
		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
		
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/Reportes_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

}
?>