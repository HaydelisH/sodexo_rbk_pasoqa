<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");


// creamos la instacia de esta clase
$page = new crearpin();

class crearpin {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $panelBD;
	private $firmasdocBD;
	private $documentosBD;
	private $docvigentesBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $infoconsulta="";
	
	private $nroopcion=0; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;

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
				
		$this->opcion = "Crear Pin";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Crear Pin</li>";

		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
	
		//se construye el menu
		include("includes/opciones_menu.php");

		// mostramos el listado
		$this->listado();

		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function listado()
	{	
		$dt = new DataTable();
	
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;

		
		$formulario[0]	= $datos;
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/crearpin.html');
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


	

}
?>