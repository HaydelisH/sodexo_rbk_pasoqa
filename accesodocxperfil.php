<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/tiposusuariosBD.php");
include_once("includes/accesodocxperfilBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/lugarespagoBD.php");

// creamos la instacia de esta clase
$page = new documentosxperfil();

class documentosxperfil {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $usuariosmantBD;
	private $lugarespagoBD;
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

		$this->opcion = "Acceso a Documentos por Perfil ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Accceso a Documentos por Perfil</li>";

		// instanciamos del manejo de tablas
		$this->accesodocxperfilBD 	= new accesodocxperfilBD();
		$this->tiposusuariosBD 		= new tiposusuariosBD();
		$this->tiposdocumentosBD 	= new tiposdocumentosBD();
		$this->empresasBD 	= new empresasBD();
		$this->centroscostoBD 	= new centroscostoBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->lugarespagoBD = new lugarespagoBD();
		
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->accesodocxperfilBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->lugarespagoBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->inicio();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
			
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "BUSCAR":
				$this->listadoEmpresas();
				break;
			case "EMPRESAS":
				$this->empresas(); //nivel 0
				break;
			case "LUGARESPAGO":
				$this->lugarespago(); //nivel 1
				break;
			case "CENTROSCOSTO":
				$this->centroscosto(); 	//nivel 2
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	
	private function empresas()
	{	
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			$datos=$_POST;
			
			// revisamos
			switch ($_POST["accion2"])
			{
				case "GRABAR":
					$this->mensajeError = "";
					for ($l = 0; $l < $_POST["cantidad"]; $l++) {
						if (isset($_POST["ide_".$l])){
							
							if (isset($_POST["sel_".$l])){
								$datos["empresaid"]= $_POST["sel_".$l];
								$this->accesodocxperfilBD->GrabaEmpresa($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
							}else{
								$datos["empresaid"]= $_POST["ide_".$l];
								$this->accesodocxperfilBD->EliminaEmpresa($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;		
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

				case "SIGUIENTE NIVEL CC":

					$this->listadoCcosto();
					break;
					
				case "ACCESO A TODO":

					$this->mensajeError = "";
					$this->accesodocxperfilBD->GrabaEmpresaAccTodo($datos);
					$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoEmpresas();
					return;

				case "ACCESO A TODO CC":

					$this->mensajeError = "";
					$this->accesodocxperfilBD->GrabaLugarPagoAccTodo($datos);
					$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoLugaresPago();
					return;
					
				case "VOLVER":
					// mostramos el listado
					$this->inicio();

					return;
			}
		}
	}

	private function listadoEmpresas()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_POST;

		if ($datos["tipousuarioid"] == 0){
			$this->mensajeError = "Debe Seleccionar un Tipo de Usuario";
			$this->inicio();
			return;
		}
		
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		//busco el total de paginas
		$this->tiposusuariosBD->obtener($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["tiposusuarios"]=$dt->data;
		
		$this->accesodocxperfilBD->ListadoEmpresas($datos,$dt);
		$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;
		$cantidad = count($dt->data);

		for ($l = 0; $l < $cantidad; $l++) {
			$formulariox[0]["empresas"][$l]["cantfilas"] 	= $datos["cantfilas"];
			$formulariox[0]["empresas"][$l]["pagina"] 		= $datos["pagina"];
		}		
		
	
		$formulario[0]=$datos;
		$formulario[0]["tiposusuarios"]=$formulariox[0]["tiposusuarios"];
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];
		
		$this->pagina->agregarDato("formulario",$formulario);
				
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("cantidad",$cantidad);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/accesodocxperfil_Empresas.html');
	}

	private function lugarespago()
	{
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			$datos=$_POST;
			
			// revisamos
			switch ($_POST["accion2"])
			{
				case "GRABAR":
					$this->mensajeError = "";
					for ($l = 0; $l < $_POST["cantidad"]; $l++) {
						if (isset($_POST["ide_".$l])){
							
							if (isset($_POST["sel_".$l])){
								$datos["empresaid"]= $_POST["sel_".$l];
								$this->accesodocxperfilBD->GrabaEmpresa($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
							}else{
								$datos["empresaid"]= $_POST["ide_".$l];
								$this->accesodocxperfilBD->EliminaEmpresa($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;		
							}
						}
					}
					
					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoEmpresas();
					return;
					
				case "SIGUIENTE NIVEL CC":

					$this->listadoCcosto();
					break;
					
				case "ACCESO A TODO CC":
					$this->mensajeError = "";
					$this->accesodocxperfilBD->GrabaLugarPagoAccTodo($datos);
					$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;

					if ($this->mensajeError == ""){
						$this->mensajeOK = "Informaci&oacute;n Grabada OK";
					}
					
					$this->listadoLugaresPago();
					return;
					
				case "VOLVER":
					// mostramos el listado
					$this->inicio();

					return;
			}
		}
	}


	private function listadoCcosto() 
	{	
		
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_POST;
		$datos['RutEmpresa'] = $datos['empresaid'];
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="50";
		
		$datos["tipousuarioidusu"]=$this->seguridad->tipousuarioid;
		
		$this->tiposusuariosBD->obtener($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["tiposusuarios"]=$dt->data;	
		
		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;	

		$this->lugarespagoBD->obtener($datos,$dt);
		$this->mensajeError.=$this->lugarespagoBD->mensajeError;
		$formulariox[0]["lugarespago"]=$dt->data;	
	
		$this->accesodocxperfilBD->ListadoCentrosCostoPerfil($datos,$dt);
		$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
		$formulariox[0]["centroscosto"]=$dt->data;	
		$cantidad = count($dt->data);

		$this->accesodocxperfilBD->ListadoCentrosCostoTotalPerfil($datos,$dt);
		$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
		
		$formulario[0]=$datos;
		$formulario[0]["tiposusuarios"]	=	$formulariox[0]["tiposusuarios"];
		$formulario[0]["empresas"]		=	$formulariox[0]["empresas"];
		$formulario[0]["centroscosto"]	=	$formulariox[0]["centroscosto"];
		$formulario[0]["lugarespago"]	=	$formulariox[0]["lugarespago"];	
		
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("empresaid",$datos["empresaid"]);
		$this->pagina->agregarDato("tipousuarioid",$datos["tipousuarioid"]);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->imprimirTemplate('templates/accesodocxperfil_CentrosCosto.html');		
	}

	private function listadoLugaresPago() 
	{	
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_POST;
		$datos['RutEmpresa'] = $datos['empresaid'];
		
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="50";
		
		$datos["tipousuarioidusu"]=$this->seguridad->tipousuarioid;
		
		$this->tiposusuariosBD->obtener($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["tiposusuarios"]=$dt->data;	
		
		$this->empresasBD->obtener($datos,$dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulariox[0]["empresas"]=$dt->data;	

		$this->accesodocxperfilBD->ListadoLugaresPagoTotal($datos,$dt);
		$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos['decuantos'] = round($dt->data[0]["totalreg"]);
	
		$this->accesodocxperfilBD->ListadoLugaresPago($datos,$dt);
		$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
		$formulariox[0]["lugarespago"]=$dt->data;	
		$cantidad = count($dt->data);

		if( $cantidad > 0 ){
			foreach ($dt->data as $key => $value) {
				$formulariox[0]["lugarespago"][$key]['empresaid'] = $datos['empresaid'];
				$formulariox[0]["lugarespago"][$key]['tipousuarioid'] = $datos['tipousuarioid'];
			}
		}
		
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		$formulario[0]=$datos;
		$formulario[0]["tiposusuarios"]	=	$formulariox[0]["tiposusuarios"];
		$formulario[0]["empresas"]		=	$formulariox[0]["empresas"];
		$formulario[0]["lugarespago"]	=	$formulariox[0]["lugarespago"];	
		
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("empresaid",$datos["empresaid"]);
		$this->pagina->agregarDato("tipousuarioid",$datos["tipousuarioid"]);
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->imprimirTemplate('templates/accesodocxperfil_LugaresPago.html');		
	}
	
	private function centroscosto()
	{	
		// si hubo algun evento
		if (isset($_POST["accion3"]))
		{
			// revisamos
			switch ($_POST["accion3"])
			{
			   case "GRABAR":
					$datos=$_POST;
					
					$this->mensajeError = "";
					$this->accesodocxperfilBD->GrabaEmpresa($datos);
					$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
					
					for ($l = 0; $l < $_POST["cantidad"]; $l++) {
						if (isset($_POST["ide_".$l])){
							
							if (isset($_POST["sel_".$l])){
								$datos["centrocostoid"]= $_POST["sel_".$l];
								$this->accesodocxperfilBD->GrabaCentroCosto($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;
							}else{
								$datos["centrocostoid"]= $_POST["ide_".$l];
								$this->accesodocxperfilBD->EliminaCentroCosto($datos);
								$this->mensajeError.=$this->accesodocxperfilBD->mensajeError;								
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
					$this->listadoEmpresas();
					return;

			}
		}
	}
	
	private function inicio()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_POST;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;

		//busco el total de paginas
		$this->tiposusuariosBD->Todos($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["tiposusuarios"]=$dt->data;
		
		$formulario[0]=$datos;
		$formulario[0]["tiposusuarios"]=$formulariox[0]["tiposusuarios"];
				
		$this->pagina->agregarDato("formulario",$formulario);
				
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/accesodocxperfil_Inicio.html');	
	}
	
		
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
