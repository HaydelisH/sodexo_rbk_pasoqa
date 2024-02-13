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
//include_once("includes/generar_documentos_masivosBD.php");

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

		$this->opcion = "Carga individual/masiva para formulario";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Carga individual/masiva para formulario</li>";

        // instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->usuariosBD = new usuariosBD();
		$this->empresasBD = new empresasBD();
		//$this->generar_documentos_masivosBD = new generar_documentos_masivosBD();
		
		$conecc = $this->bd->obtenerConexion();
        // si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->usuariosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		//$this->generar_documentos_masivosBD->usarConexion($conecc);
		
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
        switch ($_POST["accion"])
		{
            case "VOLVER":
                $this->agregar();
                break;
            
			/*case "LISTADO":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "GENERARPOSTULACION":
				$this->generarPostulacion();
				break;*/
                
        }
		// e imprimimos el pie
		$this->imprimirFin();
    }

    //Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		$datos = $_POST;

		//Inicialiamos la variable de tipo Tabla 
        $dt = new DataTable();

		$formulario[0]=$datos;
		// Recupero los tipos de firma
		/*$this->generar_documentos_masivosBD->listadoTipoFirmas($dt);
		$this->mensajeError.=$this->generar_documentos_masivosBD->mensajeError;
		$formulario[0]["tipoFirma"]=$dt->data;*/

		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		// Revisamos si existe ya un proceso de generacion de documentos masivo
        include_once("loadUserFormularioProcess.php");
        if ($importar->procesoActivo())
        {
            $importar->getDataGrilla();
			$formulario[0]['highestRow'] = $importar->highestRow - 1;
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
		$this->pagina->imprimirTemplate('templates/loadUserFormulario.html');
	}

    //Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

}
?>