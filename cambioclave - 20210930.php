<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/cambioclaveBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new cambioclave();

class cambioclave {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $cambioclaveBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $mensajeOK="";

	private $nroopcion=0; //número de opción este debe estar en la tabla opcionessistema

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
		
		$this->opcion = "Cambio Contrase&ntilde;a ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "Cambio Contrase&ntilde;a";
		$this->opcionnivel2 = "";
		
	


		// instanciamos del manejo de tablas
		$this->cambioclaveBD = new cambioclaveBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();

		
		$conecc = $this->bd->obtenerConexion();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->cambioclaveBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		$this->enviar();

		// desconectamos
		$this->bd->desconectar();
	}

	private function enviar()
	{
		
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
					case "Cambiar":
					// enviamos los datos del formulario a guardar

					$datos=$_REQUEST;
					$datos["usuarioid"]=$this->seguridad->usuarioid;
					
					//print_r ($datos);
					$datos["claveant"] = hash('sha256', trim($datos["claveant"]));
					$datos["clavenew"] = hash('sha256', trim($datos["clavenew"]));
					$datos["claverep"] = hash('sha256', trim($datos["claverep"]));

    				// enviamos los datos del formulario a guardar
     				if ($this->cambioclaveBD->cambioclave($datos))
					{
						$this->mensajeOK = 'Su contrase&ntilde;a ha sido cambiada';
						
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						$this->pagina->agregarDato("solicitudes","");
						$this->pagina->imprimirTemplate('templates/cambioclave.html');
						include("includes/opciones_fin.php");
						return;
					}
     				// sino guardamos el mensaje de error
 					$this->mensajeErrorr.=$this->cambioclaveBD->mensajeError;
 					$this->mensajeError = $this->mensajeErrorr;
					break;

				case "Volver":

					// nos devolvemos al lugar especificado
					header('Location: index.php');
					return;
			}
		}

		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
	
		$campos[0]=$_REQUEST;
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// agregamos a la pagina lo que llego del formulario
		 $this->pagina->agregarDato("solicitudes","");
		// se imprime el formulario



		$this->pagina->imprimirTemplate('templates/cambioclave.html');

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


