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
		if (isset($_POST["mensajeError"])) $this->mensajeError.=$_POST["mensajeError"];

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
		
		$this->opcion = "Registro DEC ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "Registro DEC";
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

		$this->registrar();

		// desconectamos
		$this->bd->desconectar();
	}

	private function registrar()
	{
		
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			//reasignamos los datos 
			$_POST["user_lastname"] = $_POST["appaterno"]." ".$_POST["apmaterno"];
			$_POST["user_birthday"] = str_replace("/","-",$_POST["user_birthday"]); 

			$this->dec5->RegistrarUsuario($_POST,$dt);
			$this->mensajeError.=$this->dec5->mensajeError;
			if ($this->mensajeError == "")
			{
				if (($dt["status"] == 200))
				{
					$this->mensajeOK = 'Operaci&oacute;n Realizada Correctamente';
				}
			}
		
		}

		$dt = new DataTable();
		$_POST["usuarioid"] = $this->seguridad->usuarioid;

		//Buscar los datos guardados
		$this->registrodecBD->obtener($_POST,$dt);
		$this->mensajeError.=$this->registrodecBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		$this->pagina->agregarDato("listado",$dt->data);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/registroDec_FormularioAgregar.html');

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


