<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel_2.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/respuesta_importarBD.php");
include_once('includes/Seguridad.php');

// creamos la instacia de esta clase
$page = new consultageneral_excel();

class consultageneral_excel {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $respuesta_importarBD;
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
		$nombrearchivo = "Reportes_ResultadoPostulacion_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Resultado Postulacion"));
	
		// instanciamos del manejo de tablas
		$this->respuesta_importarBD = new respuesta_importarBD();
		
		$conecc=$this->bd->obtenerConexion();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->respuesta_importarBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

        $datos = $_POST;
		$datos["usuarioingid"]=$this->seguridad->usuarioid;
		$this->respuesta_importarBD->listado($datos,$dt);
		$this->mensajeError.=$this->respuesta_importarBD->mensajeError;	

		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
				"fila",
				"resultado",
				"observaciones",
				"tipotransaccion"
			);

		$descripciones = array(
				"Fila",
				utf8_decode("Resultado"),
				utf8_decode("Observaciones"),
				utf8_decode("TipoTransaccion")
			);
						
		$tipos = array("normal","normal","normal","normal");

		$ancho = array("10","30","130","15");
		
		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
		
	}
}
?>