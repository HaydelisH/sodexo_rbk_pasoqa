<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/usuariosBD.php");

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

		$this->opcion = "Ingreso de rechazo por politica ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Rechazo por politica</li>";

        // instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->usuariosBD = new usuariosBD();

		$conecc = $this->bd->obtenerConexion();
        // si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->usuariosBD->usarConexion($conecc);

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
            case "LOAD":
                $this->load();
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

		//Inicialiamos la variable de tipo Tabla 
		/*$dt = new DataTable();
		$dt2 = new DataTable();

        $datos['usuarioid'] = $this->seguridad->usuarioid;
		$this->usuariosBD->obtenerEmpresaCentroCosto($datos, $dt);
		$this->mensajeError.= $this->usuariosBD->mensajeError;
		$formulario[0]['RazonSocial'] = $dt->data[0]['RazonSocial'];
		$formulario[0]['RutEmpresa'] = $dt->data[0]['RutEmpresa'];
		$formulario[0]['nombrecentrocosto'] = $dt->data[0]['nombrecentrocosto'];
		$formulario[0]['centeocostoid2'] = $dt->data[0]['centeocostoid'];

        $datos['RutEmpresa'] = $dt->data[0]['RutEmpresa'];
        $this->cargoempleadoBD->listado($datos, $dt2);
		$this->mensajeError.=$this->cargoempleadoBD->mensajeError;
		$formulario[0]["Cargos"] = $dt2->data;

        $this->centroscostoBD->listadoComboEmpresa($datos, $dt3);
		$this->mensajeError.= $this->centroscostoBD->mensajeError;
		$formulario[0]["CentrosCostos"] = $dt3->data;
		*/

        // Revisamos si existe ya un proceso de generacion de documentos masivo
		include_once("blackListProcess.php");
        if ($importar->procesoActivo())
        {
            $importar->getDataGrilla();
            $formulario[0]['highestRow'] = $importar->highestRow;
		}
		else
		{
			$formulario[0]['highestRow'] = '';
		}
		//Pasamos los datos a Formulario
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/blackList.html');
	}

    //Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

}
?>