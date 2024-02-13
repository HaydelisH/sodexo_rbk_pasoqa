<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/subclausulasBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new turnos();

class turnos {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $subclausulasBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	private $nroopcion=5; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;
	
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

		$this->opcion = "Turnos ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Turnos</li>";
		
		// instanciamos del manejo de tablas
		$this->subclausulasBD = new subclausulasBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->subclausulasBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->listado();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
	
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		$datos = $_POST;
		$datos["idTipoSubClausula"] = 1;
	
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "AGREGAR":

					$dt = new DataTable();

					// enviamos los datos del formulario a guardar
					if ($this->subclausulasBD->agregar($datos,$dt))
					{
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con &eacute;xito";
						$this->modificar();
						return;
					}else{
						$this->mensajeError.=$this->procesosBD->mensajeError;
						return;
					}	

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$dt = new DataTable();

		$this->subclausulasBD->listadoPorTipos($datos,$dt);
		$this->mensajeError.=$this->subclausulasBD->mensajeError;
		$formulario[0]["subclausulas"]=$dt->data;		

		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/turnos_FormularioAgregar.html');
	}

	//Accion de modificar un registro 
	private function modificar()
	{	
		$datos = $_POST;
		$datos["idTipoSubClausula"] = 1;

		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->procesosBD->modificar($datos))
					{
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
						return;
					}
					$this->mensajeError.=$this->procesosBD->mensajeError;
					return;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$dt = new DataTable();

		//Asignamos los datos que recibimos del formulario
		$this->subclausulasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->subclausulasBD->mensajeError;
		
		$formulario = $dt->data;		
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/turnos_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{	
		$datos = $_POST;
		$datos["idTipoSubClausula"] = 1;

		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->subclausulasBD->eliminar($datos)){
			$this->mensajeOK="Registro Elimnado! Su registro se ha eliminado con exito";
		}else{
			$this->mensajeError.=$this->subclausulasBD->mensajeError;
		}
		$this->listado();
		return;
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		$datos = $_POST;
		$datos["idTipoSubClausula"] = 1;
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		$this->subclausulasBD->listadoPorTipos($datos,$dt);
		$this->mensajeError.=$this->subclausulasBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Turnos.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
		}

		$num = count($formulario[0]["listado"]);

		if ( $crea     ) $formulario[0]["crear"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
			if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
		}

		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/turnos_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
