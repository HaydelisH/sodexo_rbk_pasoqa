<?php
// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/respuesta_importarBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new respuesta_importar_masivo();


class respuesta_importar_masivo{

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $respuesta_importarBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	//private $usuarioid="";
	
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	
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

		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "Respuesta de importacion Masiva ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Respuesta</li>";
		
		// instanciamos del manejo de tablas
		$this->respuesta_importarBD = new respuesta_importarBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->respuesta_importarBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			$this->listado();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
	
		
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica siempre va
		switch ($_REQUEST["accion"])
		{
			case "BUSCAR":
				$this->listado();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos["usuarioingid"]=$this->seguridad->usuarioid;

		$this->respuesta_importarBD->listado($datos,$dt);
		$this->mensajeError.=$this->respuesta_importarBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		
		$mensajeNoDatos="";
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/respuesta_importar_masivo_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}//Fin de clase
?>
