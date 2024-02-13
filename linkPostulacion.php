<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/usuariosBD.php");
include_once("includes/empresasBD.php");
//include_once("includes/centroscostoBD.php");

// creamos la instacia de esta clase
$page = new Postulacion();
class Postulacion {
    // Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas

    // para juntar los mensajes de error
	private $mensajeError="";

	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	/*private $nroopcion=5; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
    private $ver=0;*/

    function __construct()
	{
		/*// revisamos si la accion es volver desde el listado principal
		if (isset($_POST["accion"]))
		{
			// si lo es
			if ($_POST["accion"]=="Volver")
			{
				// nos devolvemos al lugar especificado
				header('Location: index.php');
				return;
			}
		}*/

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

		$this->opcion = "Actualizaci&oacute;n link";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Actualizaci&oacute;n link</li>";

        // instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->usuariosBD = new usuariosBD();
		$this->empresasBD = new empresasBD();
		//$this->centroscostoBD = new centroscostoBD();

		$conecc = $this->bd->obtenerConexion();
        // si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->usuariosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		//$this->centroscostoBD->usarConexion($conecc);

		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->agregar();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		/*switch ($_POST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "LISTADO":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "GENERARPOSTULACION":
				$this->generarPostulacion();
				break;
		}*/
		// e imprimimos el pie
		$this->imprimirFin();
    }

	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		$datos = $_POST;

		$proximidadCaducidad = array(
			array(
				'proximidadCaducidadId'=>'1',
				'proximidadCaducidadNombre'=>'Sin Link'
			),
			array(
				'proximidadCaducidadId'=>'6',
				'proximidadCaducidadNombre'=>'Link caducado'
			),
			array(
				'proximidadCaducidadId'=>'4',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_ROJO . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'3',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_NARANJO . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'2',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_VERDE . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'5',
				'proximidadCaducidadNombre'=>'Sobre ' . DIAS_VERDE . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'7',
				'proximidadCaducidadNombre'=>'Entre ' . DIAS_ROJO . ' y ' . DIAS_NARANJO . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'8',
				'proximidadCaducidadNombre'=>'Entre ' . DIAS_NARANJO . ' y ' . DIAS_VERDE . ' dias'
			)
		);
        $formulario[0]["proximidadCaducidad"] = $proximidadCaducidad;

        $this->empresasBD->listado($dt);
        $this->mensajeError.= $this->empresasBD->mensajeError;
        $formulario[0]["Empresas"] = $dt->data;

        //Pasamos los datos a Formulario
        $this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/linkPostuacion.html');
	}

    //Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

}
?>