<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/feriadosBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new feriados();


class feriados {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $feriadosBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el idCategoria a un nuevo registro 
	private $idFeriado="";
	
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

		$this->opcion = "D&iacute;as Inhabiles";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Días Inhabiles</li>";
		
		// instanciamos del manejo de tablas
		$this->feriadosBD = new feriadosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->feriadosBD->usarConexion($conecc);
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
		if (!isset($_REQUEST["accion2"]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
		}

		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
					// enviamos los datos del formulario a guardar
					if ($this->feriadosBD->agregar($_REQUEST))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Completado! Su registro se ha guardado con exito';
						
						break;
					}
					// sino guardamos el mensaje de error
					$this->mensajeError=$this->feriadosBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos2=$_REQUEST;

		//Asignamos los datos que recibimos del formulario
		$this->feriadosBD->listado($dt);
		$this->mensajeError.=$this->feriadsosBD->mensajeError;
		$formulario[0]["feriados"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		

		$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
		$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/feriados_FormularioAgregar.html');

	}

	//Accion de modificar un registro 
	private function modificar()
	{	
	     /*if (!isset($_REQUEST["accion2 "]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// obtenemos los datos a modificar
			$this->feriadosBD->obtener($_REQUEST,$dt);
			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por si aca hubo
			$this->mensajeError=$this->feriadosBD->mensajeError;

		}*/

		// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->feriadosBD->modificar($_REQUEST))
					{
						// si resulta mostramos el listado
						//Mensaje del CallOut
						$this->mensajeOK = 'Registro Actualizado! Su registro se ha actualizado con exito';
						
						$this->listado();
						return;
					}
					// si sale todo mal leemos el error
					$this->mensajeError=$this->feriadosBD->mensajeError;
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_REQUEST;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$datos2=$_REQUEST;

		//Asignamos los datos que recibimos del formulario

		$this->feriadosBD->obtener($datos2,$dt);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		$formulario =$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);

		
		// agregamos los posibles errores a la pagina
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/feriados_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->feriadosBD->eliminar($_REQUEST["idFeriado"])){
			// si es que hubiera error lo obtenemos
			
			// si elimino entonces mostrar listado sin ningún filtro, o sea mostrar todo
			$_REQUEST["pagina"]  = 1;
			$_REQUEST["nombrex"] = "";
			$this->mensajeOK="Su registro se ha eliminado correctamente";
		}
		
		$this->mensajeError=$this->feriadosBD->mensajeError;
		$this->listado();

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado

		$datos=$_REQUEST;
		//$datos["idCategoria"]=$this->seguridad->idCategoria;
		//$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;

		$this->feriadosBD->listado($dt);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		
		$mensajeNoDatos="";
		
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Feriados.php';
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

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/feriados_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}//Fin de clase
?>
