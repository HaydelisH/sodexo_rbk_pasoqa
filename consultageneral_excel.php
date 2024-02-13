<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/consultageneralBD.php");
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
		$fechahoy=@date("YmdHis");
		$nombrearchivo = "documentos_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Listado de Documentos"));

		// instanciamos del manejo de tablas
		$this->consultageneralBD = new consultageneralBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		
		$conecc=$this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->consultageneralBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		// pedimos el listado
		$datos=$_REQUEST;
		
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;
		
		$this->consultageneralBD->Todo($datos ,$dt);
		$this->mensajeError.=$this->consultageneralBD->mensajeError;
	

		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(utf8_decode("nombreempresa"),utf8_decode("nombrecentrocosto"),"empleadoid",utf8_decode("nombre"),"tipodocumentoid",utf8_decode("nombredocumento"),"nrocontrato","fechadocumento","fechaingreso","fechatermino");
		$descripciones = array("Empresa",utf8_decode("Centro de Costo"),"Rut Trabajador","Nombre Trabajador",utf8_decode("Código Documento"),"nombredocumento",utf8_decode("N° Documento"),utf8_decode("Fecha Documento"),utf8_decode("Fecha Ingreso"), utf8_decode("Fecha Término"));
		$tipos = array("normal","normal","normal","normal","normal","normal","numero","normal","normal","normal");
		$ancho = array("40","30","20","40","20","30","15","20","20","20");
		

		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();

	}

	
}
?>