<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/postulacionBD.php");
include_once("includes/usuariosBD.php");

$page = new BuscarPostulacion();

class BuscarPostulacion{

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
    private $postulacionBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	//private $idProyecto="";
	
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
	private $orden;
	
	// funcion contructora, al instanciar
	function __construct()
	{
		// revisamos si la accion es volver desde el listado principal
		/*if (isset($_REQUEST["accion"]))
		{
			// si lo es
			if ($_REQUEST["accion"]=="Volver")
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

		$this->opcion = 'Buscador de postulaciones';
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "POSTULACIONES";
		$this->opcionnivel2 = "<li>Buscador de postulaciones</li>";
		
		// instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
        $this->postulacionBD = new postulacionBD();
		$this->usuariosBD = new usuariosBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
        $this->postulacionBD->usarConexion($conecc);
		$this->usuariosBD->usarConexion($conecc);
		
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
            case "BUSCAR":
                $this->listado();
                break;
        }
		// e imprimimos el pie
		$this->imprimirFin();

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$this->usuariosBD->obtenerEmpresaCentroCosto($datos, $dt);
		$this->mensajeError.= $this->usuariosBD->mensajeError;
	
		$datos['RutEmpresa'] = $dt->data[0]['RutEmpresa'];
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		if (isset($datos['rutPostulante']) && $datos['rutPostulante'] != '')
		{
			
			//busco el total de paginas
			$this->postulacionBD->listadoTotal($datos,$dt);
			$this->mensajeError .= $this->postulacionBD->mensajeError;
			$datos["pagina_ultimo"]=$dt->data[0]["total"];
			$datos["total_registros"]=round($dt->data[0]["totalreg"]);
			
			$formulario[0]=$datos;
			$this->postulacionBD->listadoPaginado($datos,$dt);
			$this->mensajeError.=$this->postulacionBD->mensajeError;
			$formulario[0]["listado"] = $dt->data;
			
			$registros = count($formulario[0]["listado"]);
			
			if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
				$datos["pagina_siguente"]=$datos["pagina_ultimo"];
		
			if ( $registros==0 ) 
			{
				$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
				$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
			}

			$this->pagina->agregarDato("formulario",$formulario);
			$this->pagina->agregarDato("nombrePostulante",$formulario[0]["listado"][0]['nombrePostulante']);

			$this->pagina->agregarDato("display_rut",'');
			$this->pagina->agregarDato("display_nombre",'style="display:none;"');

		}
		else if(isset($datos['nombrePostulante']) && $datos['nombrePostulante'] != '')
		{
			//busco el total de paginas
			$this->postulacionBD->listadoTotal2($datos,$dt);
			$this->mensajeError .= $this->postulacionBD->mensajeError;
			$datos["pagina_ultimo"]=$dt->data[0]["total"];
			$datos["total_registros"]=round($dt->data[0]["totalreg"]);

			$formulario[0]=$datos;

			$this->postulacionBD->listadoPaginado2($datos,$dt);
			$this->mensajeError.=$this->postulacionBD->mensajeError;
			$formulario[0]["listado"] = $dt->data;

			$registros = count($formulario[0]["listado"]);

			if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
				$datos["pagina_siguente"]=$datos["pagina_ultimo"];
		
			if ( $registros==0 ) 
			{
				$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
				$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
			}

			$this->pagina->agregarDato("formulario",$formulario);

			$this->pagina->agregarDato("display_rut",'style="display:none;"');
			$this->pagina->agregarDato("display_nombre",'');
		}
		else
		{
			$this->pagina->agregarDato("display_rut",'style="display:none;"');
			$this->pagina->agregarDato("display_nombre",'style="display:none;"');
		}
		
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// Filtro de busqueda y navegacion
		$this->pagina->agregarDato("autoFiltroPaginado", ContenedorUtilidades::autoFiltro('buscarPostulacion', $datos)['datos2datos']);
		// Filtro de busqueda y navegacion

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/buscarPostulacion.html');
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
	
	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/calculo_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

}
?>