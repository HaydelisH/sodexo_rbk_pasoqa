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
		$datos=$_POST;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["pagina"] = 1;
		$datos["decuantos"] = 10;
		
		$this->docvigentesBD->totalPorTiempo($datos ,$dt1);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["pagina"] = 1;
		$datos["decuantos"] = $dt1->data[0]["total"] * 10;
		
		$this->docvigentesBD->listadoPorTiempo($datos ,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;

		foreach ($dt->data as $key => $value) {
			$dt->data[$key]["Ejecutivo"] = $dt->data[$key]["Nombre_Ejecutivo"].' '.$dt->data[$key]["ApellidoP_Ejecutivo"].' '.$dt->data[$key]["ApellidoM_Ejecutivo"];
		}

		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
				"idContrato",
				"idProyecto",
				"Cantidad",
				"idDocumento_Gama",
				"NombreTipoDoc",
				"FechaCreacion",
				"RutCliente",
				"RazonSocialCliente",
				"RutEmpresaGama",
				"RazonSocialGama",
				"TipoFirma",
				"Nombre",
				"Observacion",
				"Rut_Ejecutivo",
				"Ejecutivo"
			);
		$descripciones = array(
				"Nro Contrato",
				"Id Proyecto",
				"Unidades",
				"Id Documento",
				utf8_decode("Tipo de Documento"),
				"Fecha de creacion",
				utf8_decode("Rut Cliente"),
				utf8_decode("Cliente"),
				utf8_decode("Rut Empresa"),
				utf8_decode("Empresa"),
				utf8_decode("Tipo de Firma"),
				utf8_decode("Estado de Firma"),
				utf8_decode("Observacion"),
				"Rut Ejecutivo",
				utf8_decode("Ejecutivo")
			);
		$tipos = array("normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal");
		$ancho = array("15","15","15","15","30","15","15","30","15","30","15","20","15","20");
		
		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
	}
}
?>