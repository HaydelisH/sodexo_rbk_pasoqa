<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/registrodecBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

//Firma DEC5
include_once('dec5.php');

// creamos la instacia de esta clase
$page = new registrodec();

class registrodec {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $registrodecBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $mensajeOK="";

	private $nroopcion=0; //número de opción este debe estar en la tabla opcionessistema

	private $dec5;

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
		if (isset($_REQUEST["mensajeError"])) $this->mensajeError.=$_REQUEST["mensajeError"];

		// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = new Paginas();
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		//instanciar la clase dec5
		$this->dec5 = new dec5();

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
		
		$this->opcion = "Recuperar PIN ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "Recuperar PIN";
		$this->opcionnivel2 = "";
		
		// instanciamos del manejo de tablas
		$this->registrodecBD = new registrodecBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();

		$conecc = $this->bd->obtenerConexion();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->registrodecBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		$this->recuperar();

		// desconectamos
		$this->bd->desconectar();
	}

	private function recuperar()
	{
		
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{

			$this->dec5->RecuperarClave($_REQUEST,$dt);

			if (($dt["status"] == 200) && ($dt["message"] =="Success")){
				// Creamos en mensaje de Ok
				$this->mensajeOK = 'Su solicitud se ha enviado a su bandeja de correo';
				//Pasamos el mensaje a la pagina
				$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			}else{
				//Creamos mensaje de error
				$this->mensajeError = 'Ha ocurrido un error inesperado, verifique sus datos e intente nuevamente';
				//Pasamos el error a la pagina 
				$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			}

		}
		$_REQUEST["usuarioid"] = $this->seguridad->usuarioid;

		$this->pagina->agregarDato("personaid",$_REQUEST["usuarioid"]);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/registroDec_RecuperarPIN.html');

		// Imprimimos el pie
		$this->imprimirFin();
	}	
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

}
?>


