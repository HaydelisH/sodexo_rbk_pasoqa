<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/centroscostoBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/firmasBD.php");
include_once("includes/cargosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/lugarespagoBD.php");
//include_once("includes/firmantescentrocostoBD.php");


// creamos la instacia de esta clase
$page = new centroscosto();


class centroscosto {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $centroscostoBD;
	private $cargosBD;
	private $empresasBD;
	private $lugarespagoBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	//private $firmantescentrocostoBD;
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el idCategoria a un nuevo registro 
	private $RutEmpresa="";
	
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
	private $band = 0;
	
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
        //print_r($_POST);
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "<p class='CentrosCosto'>Centros de Costo</p>";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li class='CentroCosto'>Centro de Costo</li>";
		
		// instanciamos del manejo de tablas
		$this->centroscostoBD = new centroscostoBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->firmasBD = new firmasBD();
		$this->cargosBD = new cargosBD();
		$this->empresasBD = new empresasBD();
		$this->lugarespagoBD = new lugarespagoBD();
		//$this->firmantescentrocostoBD = new firmantescentrocostoBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->centroscostoBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->firmasBD->usarConexion($conecc);
		$this->cargosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->lugarespagoBD->usarConexion($conecc);
		//$this->firmantescentrocostoBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r($_POST);
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

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica siempre va
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
		$datos['usuarioid'] = $this->seguridad->usuarioid;
		
		if (isset($datos["accion2"]))
		{
			// revisamos
			switch ($datos["accion2"])
			{
				case "AGREGAR":
				
					// enviamos los datos del formulario a guardar
					if ($this->centroscostoBD->agregar2($datos))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Completado! Su registro se ha guardado con exito';
						
					}
					// sino guardamos el mensaje de error
					$this->mensajeError=$this->centroscostoBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		
		$dt = new DataTable();
		$formulario[0] = $datos;
		
		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;	

		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/centroscosto_FormularioAgregar.html');

	}


	//Accion de modificar un registro 
	private function modificar()
	{	
		$dt = new DataTable();
		$datos = $_POST;
		$datos['usuarioid'] = $this->seguridad->usuarioid;
	
		// si es que nos enviaron una accion
		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":

					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->centroscostoBD->modificar($datos))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Actualizado! Su registro se ha actualizado con exito';
					}else{
						// si sale todo mal leemos el error
						$this->mensajeError=$this->centroscostoBD->mensajeError;
					}
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_POST;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
	
		$dt = new DataTable();
		
		$this->centroscostoBD->obtener2($datos,$dt);		
		$this->mensajeError.=$this->centroscostoBD->mensajeError;
		$formulario = $dt->data;

		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;	
		
		//Consultar el tipo de firma que tiene asociada el usuario
		$datos['empresaid'] = $datos['RutEmpresa'];
		$datos['RL_LUGARPAGO_DEFECTO'] = RL_LUGARPAGO_DEFECTO;
		$this->lugarespagoBD->listado($datos,$dt);
		$this->mensajeError = $this->lugarespagoBD->mensajeError;
		$formulario[0]["lugarespago"] = $dt->data;	
		
		$formulario[0]['pagina_actual'] = $datos['pagina'];

	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
	    $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/centroscosto_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$datos = $_POST;
		
 		if ($this->centroscostoBD->eliminar($datos)){
			// si es que hubiera error lo obtenemos
			$this->mensajeOK = CENTROCOSTO_LABEL." seleccionado fue eliminado con &eacute;xito";
			// si elimino entonces mostrar listado sin ningún filtro, o sea mostrar todo
			unset($_POST['idCentroCosto']);
			unset($_POST['lugarpagoid']);
		}
		
		$this->mensajeError=$this->centroscostoBD->mensajeError;
		$this->listado();

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{ 
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
				
		//busco el total de paginas
		$this->centroscostoBD->totalPaginado($datos,$dt);
		$this->mensajeError.=$this->centroscostoBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$formulario[0]=$datos;
				
		$this->centroscostoBD->listadoPaginado($datos,$dt);
		$this->mensajeError.=$this->centroscostoBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;
		
		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;	

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'CentrosCosto.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
		}

		$num = count($formulario[0]["listado"]);

		if ( $crea     ) $formulario[0]["crear"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $modifica ) {
				$formulario[0]["listado"][$i]["modifica"][0] = "";
				$formulario[0]["listado"][$i]["modifica"][0]['idCentroCosto'] = $formulario[0]["listado"][$i]['idCentroCosto'];
				$formulario[0]["listado"][$i]["modifica"][0]['lugarpagoid'] = $formulario[0]["listado"][$i]['lugarpagoid'];
				$formulario[0]["listado"][$i]["modifica"][0]['RutEmpresa'] = $formulario[0]["listado"][$i]['RutEmpresa'];
				$formulario[0]["listado"][$i]["modifica"][0]['pagina_actual'] = $datos['pagina_actual'];
			}
			if ( $elimina  ) {
				$formulario[0]["listado"][$i]["elimina"][0]   = "";
				$formulario[0]["listado"][$i]["elimina"][0]['idCentroCosto'] = $formulario[0]["listado"][$i]['idCentroCosto'];
				$formulario[0]["listado"][$i]["elimina"][0]['lugarpagoid'] = $formulario[0]["listado"][$i]['lugarpagoid'];
				$formulario[0]["listado"][$i]["elimina"][0]['RutEmpresa'] = $formulario[0]["listado"][$i]['RutEmpresa'];
			}
		}

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/centroscosto_Listado.html');
	}
	
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}//Fin de clase
?>
