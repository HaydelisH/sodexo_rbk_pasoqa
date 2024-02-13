<?php

error_reporting(E_ALL);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/DeclaracionFirmaElectonicaBD.php");
include_once("includes/tiposusuariosBD.php");
include_once('includes/Seguridad.php');

// creamos la instacia de esta clase
$page = new autoevaluacionriesgorev_excel();

class autoevaluacionriesgorev_excel {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $gestionesBD;
	private $DeclaracionFirmaElectonicaBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";

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
		$nombrearchivo = "reporte_declaracion_firma_electronica_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Listado Reporte Declaracion Firma Electronica"));

		// instanciamos del manejo de tablas
		$this->DeclaracionFirmaElectonicaBD = new DeclaracionFirmaElectonicaBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		
		$conecc=$this->bd->obtenerConexion();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->DeclaracionFirmaElectonicaBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		// pedimos el listado
		$datos=$_POST;
	
		$datos["pagina"]="1";
		$datos["decuantos"]="9999999";	
				
		
		//busco el total de paginas
		$this->DeclaracionFirmaElectonicaBD->listado($datos,$dt);
		$this->mensajeError.=$this->DeclaracionFirmaElectonicaBD->mensajeError;
		
		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
            utf8_decode("idDocumento"),
            utf8_decode("empleadoid"),
            utf8_decode("nombre"),
            utf8_decode("nomestado"),
            utf8_decode("fechaCarga"),
            utf8_decode("correoNotificacionPorConcentimiento"),
        );

		$descripciones = array(
            utf8_decode("NRO DOCUMENTO"),
            utf8_decode("RUT EMPLEADO"),
            utf8_decode("NOMBRE EMPLEADO"),
            utf8_decode("ESTADO FORMULARIO"),
            utf8_decode("FECHA APLICACION"),
            utf8_decode("CORREO FIRMA ELECTRONICA"),
        );

		$tipos = array(
            "normal",
            "normal",
            "normal",
            "normal",
            "normal",
            "normal",
		);
		//$tipos = array("normal","normal","normal","normal","normal","normal","normal");
		$ancho = array(
            "20",
            "15",
            "20",
            "20",
            "30",
            "60"
        );
		//	$ancho = array("10","25","20","20","30","30","30");
		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
		
	}

}
?>