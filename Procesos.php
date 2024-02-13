<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/procesosBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new procesos();

class procesos {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $procesosBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el idProceso a un nuevo registro 
	private $idProceso="";
	
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

		$this->opcion = "Procesos ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Procesos</li>";
		
		// instanciamos del manejo de tablas
		$this->procesosBD = new procesosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->procesosBD->usarConexion($conecc);
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
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "AGREGAR":
					$dt = new DataTable();
					// enviamos los datos del formulario a guardar
					if ($this->procesosBD->agregar($_POST,$dt))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						$_POST["idProceso"] = $dt->data[0]["idProceso"];
						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->procesosBD->mensajeError;
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->modificar();
					return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		//Asignamos los datos que recibimos del formulario
		$this->procesosBD->listado($dt);
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$formulario[0]["categorias"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/procesos_FormularioAgregar.html');
	}

	//Accion de modificar un registro 
	private function modificar()
	{	
		if (!isset($_POST["accion2 "]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			$this->procesosBD->obtener($_POST,$dt);
			$campos=$dt->data;
			$this->mensajeError.=$this->procesosBD->mensajeError;
		}

		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->procesosBD->modificar($_POST))
					{
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						$_POST["accion2"]=" ";
						//Nos vamos a modificar
						$this->modificar();
						return;
					}
					$this->mensajeError.=$this->procesosBD->mensajeError;
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					//Nos vamos a modificar
					$this->modificar();
					$campos[0]=$_POST;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		$datos2=$_POST;

		//Asignamos los datos que recibimos del formulario
		$this->procesosBD->listado($dt);
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$formulario[0]["categorias"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("formulario",$campos);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/procesos_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{	
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->procesosBD->eliminar($_POST)){
			$this->mensajeOK="Registro Elimnado! Su registro se ha eliminado con exito";
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			//Pasamos al listado actualizado
			$this->listado();
			return;
		}
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		//Pasamos al listado actualizado
		$this->listado();
		return;
		
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		$conTipoMovimiento = 0;
		$TipoMovimiento = new DataTable();

		// pedimos el listado
		$datos = $_POST;
		$datos["idProceso"]=$this->seguridad->idProceso;
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;

		$this->procesosBD->listado($dt);
		$this->mensajeError.=$this->procesosBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		$TipoMovimiento = $dt->data;
		
		$mensajeNoDatos="";
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Procesos.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
		}

		$num = count($formulario[0]["listado"]);

		if ( $crea ) $formulario[0]["crear"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if( is_null($TipoMovimiento[$i]['idTipoMovimiento']) && GENERACION_AUTOMATICA_PROCESO == 1 ){
		
				if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
				if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
			}
		}

		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/procesos_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
