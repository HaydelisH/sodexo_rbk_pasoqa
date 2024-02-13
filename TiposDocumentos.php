<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/tiposdocumentosBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new TipoDocumentos();


class TipoDocumentos {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $tiposdocumentosBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el idCategoria a un nuevo registro 
	private $idCategoria="";
	
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

		$this->opcion = "Tipos de Documentos ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Tipos de Documentos</li>";
		
		// instanciamos del manejo de tablas
		$this->tiposdocumentosBD = new tiposdocumentosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->tiposdocumentosBD->usarConexion($conecc);
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
	
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
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
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
				
					$dt = new DataTable();
					$datos = $_REQUEST;
					
					// enviamos los datos del formulario a guardar
					if ($this->tiposdocumentosBD->agregar($datos,$dt))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
						
						$_REQUEST["idTipoDoc"] = $dt->data[0]["idTipoDoc"];

						$this->modificar();
						return;
					}
					//Pasamos el error si hubo
					$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					
					$_REQUEST['accion2'] = '';
					$this->agregar();
					return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		//Asignamos los datos que recibimos del formulario
		$this->tiposdocumentosBD->Todos($dt);
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
		$formulario[0]["cargos"]=$dt->data;	
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/tipodocumentos_FormularioAgregar.html');
	}

	//Accion de modificar un registro 
	private function modificar()
	{	
		$datos = $_REQUEST;
		$dt = new DataTable();
	
		// si es que nos enviaron una accion
		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":
					// si hicieron clic en el boton modificar obtenermos los datos desde el formulario
									
					if ($this->tiposdocumentosBD->modificar($datos,$dt))
					{
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
				
					}else{
						$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
						$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					}
							
					$_REQUEST["accion2"]="";
					//Nos vamos a modificar
					$this->modificar();
					return;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		
		// creamos un contenedor de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		
		$this->tiposdocumentosBD->obtener($datos,$dt);
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
		$campos = $dt->data;
		
		$this->pagina->agregarDato("formulario",$campos);
		
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/tipodocumentos_FormularioModificar.html');
	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{	
		$datos = $_REQUEST;
		$dt = new DataTable();
		
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->tiposdocumentosBD->eliminar($datos, $dt)){
			
			$this->mensajeOK="Registro Eliminado! Su registro se ha eliminado con exito";
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			
			//Pasamos al listado actualizado
			$this->listado();
			return;
		}
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
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
		$datos = $_REQUEST;
		
		$this->tiposdocumentosBD->Todos($dt);
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
		
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'TiposDocumentos.php';
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
		$this->pagina->imprimirTemplate('templates/tipodocumentos_Listado.html');
	}
	
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
