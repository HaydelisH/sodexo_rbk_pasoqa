<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/panelBD.php");
include_once("includes/empresasBD.php");
include_once("includes/ejecutivosBD.php");
include_once("includes/firmasdocBD.php");
include_once("includes/documentosBD.php");
include_once("includes/docvigentesBD.php");

// creamos la instacia de esta clase
$page = new panel_avanzado();

class panel_avanzado {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $panelBD;
	private $empresasBD;
	private $ejecutivosBD;
	private $firmasdocBD;
	private $documentosBD;
	private $docvigentesBD;
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
		if (isset($_REQUEST["mensajeError"])) $this->mensajeError.=$_REQUEST["mensajeError"];

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
		
		$this->opcion = "Panel Avanzado ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-tachometer";
		$this->opcionnivel1 = "Panel";
		$this->opcionnivel2 = "<li>Panel Avanzado</li>";

		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->panelBD = new panelBD();
		$this->empresasBD = new empresasBD();
		$this->ejecutivosBD = new ejecutivosBD();
		$this->firmasdocBD = new firmasdocBD();
		$this->documentosBD = new documentosBD();
		$this->docvigentesBD = new docvigentesBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->panelBD->usarConexion($conecc);
		$this->ejecutivosBD->usarConexion($conecc);
		$this->firmasdocBD->usarConexion($conecc);
		$this->documentosBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);
	
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
			case "DETALLE_EJECUTIVO":
				$this->detalle_ejecutivo();
				break;
			
		}
		// e imprimimos el pie
		$this->imprimirFin();
	}

	private function listado()
	{	
		$dt_eje = new DataTable();
		$dt_emp = new DataTable();

		$datos = $_REQUEST;
		$datos["TipoEmpresa"] = 1;

		//Ejecutivos 
		$this->ejecutivosBD->listado($dt_eje);
		$this->mensajeError.= $this->ejecutivosBD->mensajeError;

		$formulario[0]["ejecutivos"]            = $dt_eje->data;
		$formulario[0]["Documentos_Ejecutivos"] = $dt_eje->data;
		$formulario[0]["cantidad"]              = count($dt_eje->data);

		//Empresas 
		$this->empresasBD->listado($datos,$dt_emp);
		$this->mensajeError.= $this->empresasBD->mensajeError;

		$formulario[0]["Empresas"] = $dt_emp->data;
		$formulario[0]["Documentos_Empresas"] = $dt_emp->data;

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/panel_avanzado.html');
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

}
?>