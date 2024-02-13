<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once('includes/Paginas.php');
// y la seguridad
include_once('includes/Seguridad.php');
// creamos la instacia de esta clase
$page = new usuarios();

class usuarios {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para juntar los mensajes de error
	private $mensajeError='';

	public $seguridad;

	// funcion contructora, al instanciar
	function __construct()
	{
		/* *INI* Parche anti ataque XSS*/
		$_REQUEST = array_map("ContenedorUtilidades::sanitizacion", $_REQUEST);
		/* *FIN* Parche anti ataque XSS*/

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
		$_REQUEST["index"]="index"; //marca para evitar doble sesion
	
		//if ($this->seguridad->administrador){
			if (isset($_REQUEST["mensajeError"]))
				//$_REQUEST["mensajeError"]=rawurldecode(urldecode($_REQUEST["mensajeError"]));
				header('Location:inicio.php?mensajeError='.rawurlencode($_REQUEST["mensajeError"]));

			include('inicio.php');
			return;
		//}


		$this->pagina->agregarDato("usuario_nombre",$this->seguridad->nombre);



		// mostramos el encabezado
		$this->pagina->imprimirTemplate('templates/encabezado2.html');

		$this->pagina->imprimirTemplate('templates/micuenta.html');

		$this->pagina->imprimirTemplate('templates/encabezadoFin.html');

		// Imprimimos el pie
		$this->pagina->imprimirTemplate('templates/piedepagina.html');

		// desconectamos
		$this->bd->desconectar();

	}

}
?>