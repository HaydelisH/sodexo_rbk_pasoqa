<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/FirmasBD.php");
include_once("includes/firmantesBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/rolesBD.php");
include_once("includes/empresasBD.php");
include_once("includes/empleadosBD.php");
include_once("includes/PersonasBD.php");
include_once("includes/estadocivilBD.php");
include_once("includes/estadoempleadoBD.php");


// creamos la instacia de esta clase
$page = new usuariosmant();


class usuariosmant {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $FirmasBD;
	private $firmantesBD;
	private $rolesBD;
	private $empresasBD;
	private $empleadosBD;
	private $personasBD;
	private $estadocivilBD;
	private $estadoempleadoBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $mensajeOK="";
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	private $nroopcion=5; //n�mero de opci�n este debe estar en la tabla opcionessistema
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

		$this->opcion = "Personas Info Contacto ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Personas Info Contacto</li>";
		
		// instanciamos del manejo de tablas
		$this->FirmasBD = new FirmasBD();
		$this->firmantesBD = new firmantesBD();
		$this->rolesBD = new rolesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->empresasBD = new empresasBD();
		$this->empleadosBD = new empleadosBD();
		$this->personasBD = new personasBD();
		$this->estadocivilBD = new estadocivilBD();
		$this->estadoempleadoBD = new estadoempleadoBD();

		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->FirmasBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);
		$this->rolesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->empleadosBD->usarConexion($conecc);
		$this->personasBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);
		$this->estadoempleadoBD->usarConexion($conecc);
		
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
				$this->listado();
				break;
			case "DETALLE":
				$this->detalle();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos=$_POST;

		$datos['ptipousuarioid'] = $this->seguridad->tipousuarioid;

		if( isset($datos['volver'])){
			$datos['personaid'] = '';
		}else{

			if( isset($datos['nombre']) || $datos['nombre'] != '' ){
				$datos['nombre_filtro'] = $datos['nombre'];
			}

			if( isset($datos['envioinfo']) || $datos['envioinfo'] >= 0 ){
				$datos['envioinfo_filtro'] = $datos['envioinfo'];
			}
		}

		//Preparamos los datos necesarios para la consulta 
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		if( !isset($datos['envioinfo']) || $datos['envioinfo'] == '' ) $datos['envioinfo'] = -1;

		//busco el total de paginas
		$this->personasBD->total($datos,$dt);
		$this->mensajeError.=$this->personasBD->mensajeError;

		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"] = round($dt->data[0]["totalreg"]);
		
		if( $datos["pagina_ultimo"] == 0 ) $datos["pagina_ultimo"] = 1;
		
		$this->personasBD->listado($datos,$dt);
		$this->mensajeError.=$this->personasBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		if ($datos["pagina_ultimo"]==0)
		{
			$this->mensajeOK="No hay informaci�n para la consulta realizada.";
		}else{
			$mensajeNoDatos="";
			$this->pagina->agregarDato("pagina",$datos["pagina"]);
			$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
			$formulario[0]=$datos;
			$formulario[0]["listado"]=$formulariox[0]["listado"];

			foreach ($formulario[0]["listado"] as $key => $value) {
				
				$formulario[0]["listado"][$key]['nombre_filtro'] = $datos['nombre_filtro'];
				$formulario[0]["listado"][$key]['envioinfo_filtro'] = $datos['envioinfo_filtro'];
				
			}
			
			//Envio Info
			$formulario[0]["EnvioInfo_select"][0]["envioinfo"] = 0;
			$formulario[0]["EnvioInfo_select"][0]["Descripcion"] = "No";
			$formulario[0]["EnvioInfo_select"][1]["envioinfo"] = 1;
			$formulario[0]["EnvioInfo_select"][1]["Descripcion"] = "Si";

			$this->pagina->agregarDato("formulario",$formulario);
		}
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/personas_Listado.html');
	}

	//Detalle del Documento
	private function detalle(){ 

		//Instanciar la clase

		$dt = new DataTable();
		$dt1 = new DataTable();

		$datos = $_POST;
		
		$this->personasBD->obtenerPIC($datos,$dt);
		$this->mensajeError.=$this->personasBD->mensajeError;
		$formulario=$dt->data;

		if( $datos['nombre'] != '' ) $formulario[0]['nombre_filtro'] = $datos['nombre'];
		if( $datos['envioinfo'] != '' ) $formulario[0]['envioinfo_filtro'] = $datos['envioinfo'];


		$formulario[0]['pagina'] = $datos['pagina'];

		//Pasamos los datos a la pagina
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/personas_Detalle.html');
	}



	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
