<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/tiposusuariosBD.php");
include_once("includes/accesoxusuarioBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/lugarespagoBD.php");
//include_once("includes/departamentosBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/usuariosmantBD.php");


// creamos la instacia de esta clase
$page = new accesoxusuario();


class accesoxusuario {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $usuariosmantBD;
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
	
	private $nroopcion=4; //n�mero de opci�n este debe estar en la tabla opcionessistema
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

		$this->opcion = "Acceso a Documentos por Perfil ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Accceso a Documentos por Perfil</li>";

		// instanciamos del manejo de tablas
		$this->accesoxusuarioBD 	= new accesoxusuarioBD();
		$this->tiposusuariosBD 		= new tiposusuariosBD();
		$this->tiposdocumentosBD 	= new tiposdocumentosBD();
		$this->empresasBD 	= new empresasBD();
		$this->centroscostoBD 	= new centroscostoBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->lugarespagoBD = new lugarespagoBD();
		//$this->departamentosBD = new departamentosBD();
		$this->usuariosmantBD = new usuariosmantBD();
		
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->accesoxusuarioBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->lugarespagoBD->usarConexion($conecc);
		//$this->departamentosBD->usarConexion($conecc);
		$this->usuariosmantBD->usarConexion($conecc);
		
		
		//print_r($_REQUEST);
		//se construye el menu
		include("includes/opciones_menu.php");
		
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			//$this->inicio();
			$this->listadoEmpresas();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
	
		//print_r ($_REQUEST);
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_REQUEST["accion"])
		{
			case "BUSCAR":
				$this->listadoEmpresas();
				break;
			case "EMPRESAS":
				$this->empresas();		//nivel 1
				break;
			case "LUGARESPAGO":
				$this->lugarespago(); 	//nivel 2
				break;
			/*case "DEPARTAMENTOS":
				$this->departamentos(); //nivel 3
				break;*/
			case "CENTROSCOSTO":
				$this->centroscosto(); 	//nivel 4
				break;
	
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	
	private function listadoCcosto() 
	{	
		//print_r ($_REQUEST);
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_REQUEST;
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="50";
		if( $datos['empresaid'] != '' ) $datos['RutEmpresa'] = $datos['empresaid'];
		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["usuario"]=$dt->data;
		
		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;	
				
		$this->lugarespagoBD->obtener($datos,$dt);
		$this->mensajeError.=$this->lugarespagoBD->mensajeError;
		$formulariox[0]["lugarespago"]=$dt->data;	
		
		/*$this->departamentosBD->obtener($datos,$dt);
		$this->mensajeError.=$this->departamentosBD->mensajeError;
		$formulariox[0]["departamentos"]=$dt->data;*/

		$this->accesoxusuarioBD->ListadoCentrosCosto($datos,$dt);

		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$formulariox[0]["centroscosto"]=$dt->data;	
		$cantidad = count($dt->data);
		
		$this->accesoxusuarioBD->ListadoCentrosCostoTotal($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}
		//print_r ($datos);
		$formulario[0]=$datos;
		$formulario[0]["usuario"]		=	$formulariox[0]["usuario"];
		$formulario[0]["empresas"]		=	$formulariox[0]["empresas"];
		$formulario[0]["lugarespago"]	=	$formulariox[0]["lugarespago"];	
		//$formulario[0]["departamentos"]	=	$formulariox[0]["departamentos"];	
		$formulario[0]["centroscosto"]	=	$formulariox[0]["centroscosto"];	
		
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("empresaid",$datos["empresaid"]);
		$this->pagina->agregarDato("newusuarioid",$datos["newusuarioid"]);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("nropaginaini",$datos["nropaginaini"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		
		$this->pagina->imprimirTemplate('templates/accesoxusuario_CentrosCosto.html');		
	}
	
	private function centroscosto()
	{	
		// si hubo algun evento
		if (isset($_REQUEST["accion3"]))
		{
			// revisamos
			switch ($_REQUEST["accion3"])
			{
			   	case "GRABAR":
					$datos=$_REQUEST;
					
					$this->mensajeError = "";
					$this->accesoxusuarioBD->GrabaEmpresa($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
					
					for ($l = 0; $l < $_REQUEST["cantidad"]; $l++) {
						if (isset($_REQUEST["ide_".$l])){
							
							if (isset($_REQUEST["sel_".$l])){
								$datos["centrocostoid"]= $_REQUEST["sel_".$l];
								$this->accesoxusuarioBD->GrabaCentroCosto($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
							}else{
								$datos["centrocostoid"]= $_REQUEST["ide_".$l];
								$this->accesoxusuarioBD->EliminaCentroCosto($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;								
							}
						}
					}
					
					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
										
					$this->listadoCcosto();
					break;
					
				case "BUSCAR":
					$this->listadoCcosto();
					return;
					
				case "VOLVER":
					// mostramos el listado
					//$this->listadoDepartamentos();
					$this->listadoLugaresPago();
					return;

			}
		}
	}
	
	private function empresas()
	{	
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			$datos=$_REQUEST;
			
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "GRABAR":
					$this->mensajeError = "";
					for ($l = 0; $l < $_REQUEST["cantidad"]; $l++) {
						if (isset($_REQUEST["ide_".$l])){
							
							if (isset($_REQUEST["sel_".$l])){
								$datos["empresaid"]= $_REQUEST["sel_".$l];
								$this->accesoxusuarioBD->GrabaEmpresa($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
							}else{
								$datos["empresaid"]= $_REQUEST["ide_".$l];
								$this->accesoxusuarioBD->EliminaEmpresa($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;		
							}
						}
					}
					
					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoEmpresas();
					return;
					
				case "SIGUIENTE NIVEL":

					$this->listadoLugaresPago();
					break;
					
				case "ACCESO A TODO":
					$this->mensajeError = "";
					$this->accesoxusuarioBD->GrabaEmpresaAccTodo($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoEmpresas();
					return;
					
				case "VOLVER":
					// mostramos el listado
					$this->inicio();

					return;
			}
		}
	}

	
	private function inicio()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioingid"]=$this->seguridad->newusuarioid;

		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["usuario"]=$dt->data;
		
		$formulario[0]=$datos;
		$formulario[0]["usuario"]=$formulariox[0]["usuario"];
				
		$this->pagina->agregarDato("formulario",$formulario);
				
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		
		$this->pagina->agregarDato("nropaginaini",$datos["nropaginaini"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/accesoxusuario_Inicio.html');	
	}
	
	
	private function listadoEmpresas()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;
		if ($datos["newusuarioid"] == ''){
			$this->mensajeError = "Debe Seleccionar un Tipo de Usuario";
			$this->inicio();
			return;
		}
		
		$datos["usuarioid"]=$this->seguridad->usuarioid;
	
		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["usuario"]=$dt->data;
				
		$this->accesoxusuarioBD->ListadoEmpresas($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;
		$cantidad = count($dt->data);

		for ($l = 0; $l < $cantidad; $l++) {
			$formulariox[0]["empresas"][$l]["cantfilas"] 	= $datos["cantfilas"];
			$formulariox[0]["empresas"][$l]["pagina"] 		= $datos["pagina"];
		}		
		
	
		$formulario[0]=$datos;
		$formulario[0]["usuario"]=$formulariox[0]["usuario"];
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];
		
		$this->pagina->agregarDato("formulario",$formulario);
				
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("newusuarioid",$datos["usuarioid"]);
		
		$this->pagina->agregarDato("nropaginaini",$datos["nropaginaini"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/accesoxusuario_Empresas.html');
	}

	private function lugaresPago()
	{	
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			$datos=$_REQUEST;
			// revisamos
			switch ($_REQUEST["accion2"])
			{
			   	case "GRABAR":
					$this->mensajeError = "";
					$this->accesoxusuarioBD->GrabaEmpresa($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
					
					for ($l = 0; $l < $_REQUEST["cantidad"]; $l++) {
						if (isset($_REQUEST["ide_".$l])){
							
							if (isset($_REQUEST["sel_".$l])){
								$datos["lugarpagoid"]= $_REQUEST["sel_".$l];
								$this->accesoxusuarioBD->GrabaLugarPago($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
							}else{
								$datos["lugarpagoid"]= $_REQUEST["ide_".$l];
								$this->accesoxusuarioBD->EliminaLugarPago($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;								
							}
						}
					}
					
					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
										
					$this->listadoLugaresPago();
					return;
					
				case "SIGUIENTE NIVEL":
					$this->listadoCcosto();
					//$this->listadoDepartamentos();
					break;
				case "ACCESO A TODO":
					$this->mensajeError = "";

					$this->accesoxusuarioBD->GrabaLugarPagoAccTodo($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoLugaresPago();
					return;

				case "BUSCAR":
					$this->listadoLugaresPago();
					return;
					
				case "VOLVER":
					// mostramos el listado
					$this->listadoEmpresas();
					return;

			}
		}
	}

	private function listadoLugaresPago() 
	{	

		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_REQUEST;
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="50";

		if( $datos['empresaid'] != '' ) $datos['RutEmpresa'] = $datos['empresaid'];
		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["usuario"]=$dt->data;
		
		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;	
		
		$this->accesoxusuarioBD->ListadoLugaresPago($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$formulariox[0]["lugarespago"]=$dt->data;	
		$cantidad = count($dt->data);
		
		$this->accesoxusuarioBD->ListadoLugaresPagoTotal($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}
		//print_r ($datos);
		$formulario[0]=$datos;
		$formulario[0]["usuario"]		=	$formulariox[0]["usuario"];
		$formulario[0]["empresas"]		=	$formulariox[0]["empresas"];
		$formulario[0]["lugarespago"]	=	$formulariox[0]["lugarespago"];	
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		$this->pagina->agregarDato("empresaid",$datos["empresaid"]);
		$this->pagina->agregarDato("newusuarioid",$datos["newusuarioid"]);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
	
		$this->pagina->agregarDato("nropaginaini",$datos["nropaginaini"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		//$this->pagina->agregarDato("lugarpago",$datos["lugarpago"]);	
		$this->pagina->imprimirTemplate('templates/accesoxusuario_LugaresPago.html');		
	}

	/*private function departamentos()
	{	
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			$datos=$_REQUEST;
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "GRABAR":
					
					$this->mensajeError = "";
					$this->accesoxusuarioBD->GrabaEmpresa($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
					
					for ($l = 0; $l < $_REQUEST["cantidad"]; $l++) {
						if (isset($_REQUEST["ide_".$l])){
							
							if (isset($_REQUEST["sel_".$l])){
								$datos["departamentoid"]= $_REQUEST["sel_".$l];
								$this->accesoxusuarioBD->GrabaDepartamento($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
							}else{
								$datos["departamentoid"]= $_REQUEST["ide_".$l];
								$this->accesoxusuarioBD->EliminaDepartamento($datos);
								$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;								
							}
						}
					}
					
					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
										
					$this->listadoDepartamentos();
					break;
					
				case "SIGUIENTE NIVEL":

					$this->listadoCcosto();
					break;
				case "ACCESO A TODO":
					$this->mensajeError = "";
					$this->accesoxusuarioBD->GrabaDepartamentoAccTodo($datos);
					$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoDepartamentos();
					return;
				case "BUSCAR":
					$this->listadoDepartamentos();
					return;
					
				case "VOLVER":
					// mostramos el listado
					$this->listadoLugaresPago();
					return;

			}
		}
	}*/

	/*private function listadoDepartamentos() 
	{	
		//print_r ($_REQUEST);
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_REQUEST;
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="50";
		if( $datos['empresaid'] != '' ) $datos['RutEmpresa'] = $datos['empresaid'];
		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["usuario"]=$dt->data;
		
		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;	
		
		$this->lugarespagoBD->obtener($datos,$dt);
		$this->mensajeError.=$this->lugarespagoBD->mensajeError;
		$formulariox[0]["lugarespago"]=$dt->data;	
		
		$this->accesoxusuarioBD->ListadoDepartamentos($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$formulariox[0]["departamentos"]=$dt->data;	
		$cantidad = count($dt->data);
		
		$this->accesoxusuarioBD->ListadoDepartamentosTotal($datos,$dt);
		$this->mensajeError.=$this->accesoxusuarioBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
		//print_r ($datos);
		$formulario[0]=$datos;
		$formulario[0]["usuario"]		=	$formulariox[0]["usuario"];
		$formulario[0]["empresas"]		=	$formulariox[0]["empresas"];
		$formulario[0]["lugarespago"]	=	$formulariox[0]["lugarespago"];	
		$formulario[0]["departamentos"]	=	$formulariox[0]["departamentos"];	
		
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("empresaid",$datos["empresaid"]);
		$this->pagina->agregarDato("newusuarioid",$datos["newusuarioid"]);
		$this->pagina->agregarDato("lugarpagoid",$datos["lugarpagoid"]);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		//$this->pagina->agregarDato("lugarpago",$datos["lugarpago"]);	
		$this->pagina->agregarDato("nropaginaini",$datos["nropaginaini"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		
		$this->pagina->imprimirTemplate('templates/accesoxusuario_Departamentos.html');		
	}*/
	

	
	
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
