<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once('includes/Seguridad.php');
include_once('includes/perfilesfirmaBD.php');
include_once('firma.php');



// creamos la instacia de esta clase
$page = new asignacionperfiles();

class asignacionperfiles {

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
				
		$this->opcion = "Asignacion Perfiles";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-user";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Asignar Perfil</li>";
		
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->perfilesfirmaBD = new perfilesfirmaBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->perfilesfirmaBD->usarConexion($conecc);
	
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
		
		$ruopt = explode("-",$this->seguridad->usuarioid);
		$datos["personalNumber_operador"]=$ruopt[0].$ruopt[1];
		
		$firma = new firma();
		
		$respuesta = $firma->Obtenerperfiles($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		
		$cantidad = count($dt["data"]["digitalIdentity"]["profiles"]);
			
		for ($r = 0; $r < $cantidad; $r++) 
		{
			$formulario[0]["listado"][$r]["perfil"] = $dt["data"]["digitalIdentity"]["profiles"][$r]["code"];
		}
		
		//Asignacion de mensaje
		
		$mensaje=''; 
		$firma->errores_firma($mensajeError, $mensaje);
		if( $mensajeError == "Error api 404 ") $mensajeError = '';
		//
		
		$this->perfilesfirmaBD->listado($dt);
		$mensajeError.=$this->perfilesfirmaBD->mensajeError;
		
		if (count($dt->data) > 1){
			$dta = array("perfil"=>"0", "perfil" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["perfilesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeAd",$mensaje);
		//
		//$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionperfiles.html');
		
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
		$datos["perfil"]			 =	$datos["perfilagregar"];
		
		$ruopt = explode("-",$this->seguridad->usuarioid);
		$datos["personalNumber_operador"]=$ruopt[0].$ruopt[1];
	
		$firma = new firma();
		
		$respuesta = $firma->AgregarPerfil($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		if ($mensajeError == "")
		{
			$mensajeOK = "Acci&oacute;n realizada correctamente";
		}
		
		$this->perfilesfirmaBD->listado($dt);
		$mensajeError.=$this->perfilesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("perfil"=>"0", "perfil" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["perfilesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionperfiles.html');
		
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
		$datos["perfil"]			=$datos["perfilaeliminar"];
		
		$ruopt = explode("-",$this->seguridad->usuarioid);
		$datos["personalNumber_operador"]=$ruopt[0].$ruopt[1];
		
		$firma = new firma();
		
		$respuesta = $firma-> EliminarPerfil($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		if ($mensajeError == "")
		{
			$mensajeOK = "Acci&oacute;n realizada correctamente";
		}
		
		$this->perfilesfirmaBD->listado($dt);
		$mensajeError.=$this->perfilesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("perfil"=>"0", "perfil" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["perfilesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeOK);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionperfiles.html');
		
	}

	private function listado()
	{	
		$dt = new DataTable();
	
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		
		$datos["activarconsultar"] = "in active";
		$datos["classconsultar"]   = 'class="active"';
		
		$formulario[0]	= $datos;
		
		$this->perfilesfirmaBD->listado($dt);
		$mensajeError.=$this->perfilesfirmaBD->mensajeError;
		if (count($dt->data) > 1){
			$dta = array("perfil"=>"0", "perfil" =>"(Seleccione)");
			array_unshift($dt->data,$dta);
		}
		$formulario[0]["perfilesagregar"] = $dt->data;	
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$mensajeError);
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/asignacionperfiles.html');
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


	

}
?>