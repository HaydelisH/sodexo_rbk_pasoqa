<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once('includes/Seguridad.php');
include_once('includes/rolesfirmaBD.php');
include_once('firma.php');



// creamos la instacia de esta clase
$page = new asignacionroles();

class asignacionroles {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	// para juntar los mensajes de error
	private $mensajeError="";
	private $infoconsulta="";
	
	private $nroopcion=0; //número de opción este debe estar en la tabla opcionessistema
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
				
		$this->opcion = "Asignar Roles";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Asignar Roles</li>";
		
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->rolesfirmaBD = new rolesfirmaBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->rolesfirmaBD->usarConexion($conecc);
	
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
			case "BUSCAR":
				$this->buscar();
				break;
			case "AGREGAR":
				$this->agregar();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
		}
		
		// e imprimimos el pie
		$this->imprimirFin();

	}
	
	private function buscar()
	{
		$dt = new DataTable();
		$dt2 = new DataTable();
		
		$datos = $_POST;
		
		$rut = explode("-",$datos["rut"]);
		
		$datos["activarconsultar"] = "in active";
		$datos["classconsultar"]   = 'class="active"';
		
		$formulario[0]=$datos;
		$datos["personalNumber"]=$rut[0].$rut[1];
		
		$firma = new firma();
		
		$respuesta = $firma->ObtenerRoles($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		
		$cantidad = count($dt["data"]["digitalIdentity"]["roles"]);
		for ($r = 0; $r < $cantidad; $r++) 
		{
			$formulario[0]["listado"][$r]["rol"] = $dt["data"]["digitalIdentity"]["roles"][$r]["code"];
		}
				
		$this->rolesfirmaBD->listado($dt);
		$mensajeError.=$this->rolesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("rol"=>"0", "rol" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["rolesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionroles.html');
		
	}
	
	
	private function agregar()
	{
		$dt = new DataTable();
		$dt2 = new DataTable();
		
		$datos = $_POST;
		
		$rut = explode("-",$datos["rut_agregar"]);
			
		$datos["activaragregar"] = "in active";
		$datos["classagregar"]   = 'class="active"';
		
		$formulario[0]=$datos;
		$datos["personalNumber"] =	$rut[0].$rut[1];
		$datos["rol"]			 =	$datos["rolagregar"];
	
		$firma = new firma();
		
		$respuesta = $firma->AgregarRol($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		if ($mensajeError == "")
		{
			$mensajeOK = "Acci&oacute;n realizada correctamente";
		}
		
		$this->rolesfirmaBD->listado($dt);
		$mensajeError.=$this->rolesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("rol"=>"0", "rol" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["rolesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionroles.html');
		
	}
	
	
	private function eliminar()
	{
		$dt = new DataTable();
		$dt2 = new DataTable();
		
		$datos = $_POST;
		
		$rut = explode("-",$datos["rut_eliminar"]);
		
		$datos["activareliminar"] = "in active";
		$datos["classeliminar"]   = 'class="active"';
		
		$formulario[0]=$datos;
		$datos["personalNumber"]=$rut[0].$rut[1];
		$datos["rol"]			=$datos["rolaeliminar"];
		
		$firma = new firma();
		
		$respuesta = $firma-> EliminarRol($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		if ($mensajeError == "")
		{
			$mensajeOK = "Acci&oacute;n realizada correctamente";
		}
		
		$this->rolesfirmaBD->listado($dt);
		$mensajeError.=$this->rolesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("rol"=>"0", "rol" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["rolesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionroles.html');
		
	}

	private function listado()
	{	
		$dt = new DataTable();
	
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		
		$datos["activarconsultar"] = "in active";
		$datos["classconsultar"]   = 'class="active"';
		
		$formulario[0]	= $datos;
		
		$this->rolesfirmaBD->listado($dt);
		$mensajeError.=$this->rolesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("rol"=>"0", "rol" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["rolesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionroles.html');
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


	

}
?>