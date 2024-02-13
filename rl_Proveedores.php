<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/rl_proveedoresBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/firmasBD.php");
include_once("includes/cargosBD.php");
include_once("includes/empresasBD.php");
//include_once("Empresas.php");

// creamos la instacia de esta clase
$page = new rl_proveedores();


class rl_proveedores {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $rl_proveedoresBD;
	private $cargosBD;
	// para el manejo de las tablas
	private $holdingBD;
	private $empresasBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para juntar los mensajes de Advertencia
	private $mensajeAd="";
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
	private $collapse;
	
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

		$this->opcion = "Externo";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Externo</li>";
		
		// instanciamos del manejo de tablas
		$this->rl_proveedoresBD = new rl_proveedoresBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->firmasBD = new firmasBD();
		$this->cargosBD = new cargosBD();
		$this->empresasBD = new empresasBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->rl_proveedoresBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->firmasBD->usarConexion($conecc);
		$this->cargosBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		//print_r($_REQUEST);
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

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica siempre va
		switch ($_REQUEST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "AGREGAR_R":
				$this->agregarFirmante();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "ELIMINAR_R":
				$this->eliminarFirmante();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "MODIFICAR_R":
				$this->modificarFirmante();
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
		$datos = $_REQUEST;
	
		if (isset($datos["accion2"]))
		{
			// revisamos
			switch ($datos["accion2"])
			{
				case "AGREGAR":
				
					// enviamos los datos del formulario a guardar
					if ($this->rl_proveedoresBD->agregar($datos))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Completado! Su registro se ha guardado con exito';
 
						$this->modificar();
						return;
					}
					// sino guardamos el mensaje de error
					$this->mensajeError=$this->rl_proveedoresBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		
		$datos = $_REQUEST;
		$formulario[0]=$datos;	
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_proveedores_FormularioAgregar.html');

	}


	//Accion del boton agregar un nuevo registro 
	private function agregarFirmante()
	{
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR_R":
					// enviamos los datos del formulario a guardar

					$datos = $_REQUEST;
		 
					$rut_arr 	= explode("-",$datos["RutUsuario"]);
					$rut_sindv 	= $rut_arr[0];		
					
					$datos["clave"] = hash('sha256', $rut_sindv);
					$datos['rolid']= ROL_PROVEEDOR;
					$datos['TipoCorreo'] = CODIGO_CORREO_USUARIO_NUEVO;
					$datos['TipoUsuario'] = PERFIL_PROVEEDOR;
					
					if ($this->rl_proveedoresBD->agregarFirmante($datos))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Completado! Su registro se ha guardado con exito';
						$this->collapse = 'collapse';
						$this->modificar();
						return;
					}
					
					// sino guardamos el mensaje de error
					$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$datos = $_REQUEST;
		$dt = new DataTable();

		$formulario[0] = $datos;	

		//Tipo de firmas
		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}
		
		//Tipo de firmas
		$this->firmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->firmasBD->mensajeError;
		$formulario[0]["Firmas"] = $dt1->data;

		//Cargos
		$this->cargosBD->listado($dt); 
		$this->mensajeError.=$this->cargosBD->mensajeError;
		$formulario[0]['Cargos'] = $dt->data;

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
        
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_proveedores_Firmantes_FormularioAgregar.html');

	}


	//Accion de modificar un registro 
	private function modificar()
	{	
		$dt = new DataTable();
		$datos = $_REQUEST;
		$formulario=$datos;
		// si es que nos enviaron una accion
		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":

					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->rl_proveedoresBD->modificar($datos))
					{
						// si resulta mostramos el listado
						$this->mensajeOK = 'Registro Actualizado! Su registro se ha actualizado con exito';
					}else{
						// si sale todo mal leemos el error
						$this->mensajeError=$this->rl_proveedoresBD->mensajeError;
					}

					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		
		$datos = $_REQUEST;
		
		$this->rl_proveedoresBD->obtenerProveedor($datos,$dt);		
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario = $dt->data;	

        // aqui se obtienen los datos del Firmante
		$this->rl_proveedoresBD->obtenerFirmantes($datos,$dt); 
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario[0]["Firmantes"]=$dt->data;	

		if( count($dt->data) == 0 ){

			$this->mensajeAd = "El Proveedor no tiene Representantes asociados";
			$this->mensajeError = '';
		}

		if (count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$formulario[0]["Firmantes"][$key]["RutCliente"] = $datos["RutCliente"];
			}
		}
		
		//Collapse de Empresa
		if( $this->collapse == "collapse" || isset($datos['collapse'])){
			$this->pagina->agregarDato("collapse","collapsed");
			$this->collapse = '';
		}else{
			$this->pagina->agregarDato("collapse","");
		}
		
	
		//Tipo de firmas
		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}
		
		$this->firmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->firmasBD->mensajeError;
		if (count($dt1->data) > 1){
			$dta = array("idFirma"=>"0", "Descripcion" =>"(Seleccione)");
			array_unshift($dt1->data,$dta);
		}
		$formulario_firmante[0]["Firmas"] = $dt1->data;
		
		$formulario[0]["RutEmpresa"] = $datos["RutEmpresa"];
		        
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
	    $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
	    $this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
		$this->pagina->agregarDato("Firmantes",$formulario[0]["Firmantes"]);
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
		$this->pagina->agregarDato("formulario_firmante",$formulario_firmante);
	   
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("buscar",$datos["buscar"]);
		$this->pagina->agregarDato("RutEmpresadin",$datos["RutEmpresadin"]);//print("empresadin".$datos["RutEmpresadin"]);
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/rl_proveedores_FormularioModificar.html');
	}

	private function modificarFirmante()
	{
	// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR_R":

					$datos = $_REQUEST;
				
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->rl_proveedoresBD->modificarFirmante($datos))
					{
						$this->mensajeOK = 'Registro Actualizado! Su registro se ha actualizado con exito';
					}else{
						$this->mensajeError=$this->rl_proveedoresBD->mensajeError;
					}
					//$this->modificar();
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

      	$datos = $_REQUEST;
		$dt = new DataTable();
	
        // aqui se obtienen los datos del Firmante
		$this->rl_proveedoresBD->obtenerFirmante($datos,$dt); 
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario = $dt->data;

		//Tipo de firmas
		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}
		
		//Tipo de firmas
		$this->firmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->firmasBD->mensajeError;
		$formulario[0]["Firmas"] = $dt1->data;

		//Cargos
		$this->cargosBD->listado($dt); 
		$this->mensajeError.=$this->cargosBD->mensajeError;
		$formulario[0]['Cargos'] = $dt->data;
	
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
	
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/rl_proveedores_Firmantes_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$datos = $_REQUEST;
		$datos["TipoEmpresa"] = 1;
		
 		if ($this->rl_proveedoresBD->eliminar($datos)){
			// si es que hubiera error lo obtenemos
			$this->mensajeOK = "El Cliente seleccionado fue eliminado";
			// si elimino entonces mostrar listado sin ningún filtro, o sea mostrar todo
			$_REQUEST["pagina"]  = 1;
			$_REQUEST["nombrex"] = "";
		}
		
		$this->mensajeError=$this->rl_proveedoresBD->mensajeError;
		
		$this->empresasBD->obtener($datos,$dt);		
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["empresas"]=$dt->data;	
		
		// aqui se obtienen los datos del representante
		$this->empresasBD->obtenerRepresentantes($datos,$dt_empresa);		
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["representantes"]=$dt_empresa->data;	

		$this->rl_proveedoresBD->todos($datos,$dt); 
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError; 
		$formulario[0]["representantes_proveedores"]=$dt->data;	

		if (count($dt_empresa->data) > 0){
			foreach ($dt_empresa->data as $key => $value) {
				$formulario[0]["representantes"][$key]["RutEmpresa"] = $datos["RutEmpresa"];
			}
		}
		
		if (count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$formulario[0]["representantes_proveedores"][$key]["RutEmpresa"] = $datos["RutEmpresa"];
			}
		}
		
		$formulario[0]["RutEmpresa"] = $datos["RutEmpresa"];
		
		//Collapse de proveedores
		if( isset( $datos['collapse_proveedores']) ){
			$this->pagina->agregarDato("collapse","collapsed");
		}else{
			$this->pagina->agregarDato("collapse","");
		}

		//Collapse de Empresa
		if( $this->collapse == "collapse" || isset($datos['collapse'])){
			$this->pagina->agregarDato("collapse_empresa","collapsed");
			$this->collapse = '';
		}else{
			$this->pagina->agregarDato("collapse_empresa","");
		}
				        
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
	    $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/empresas_FormularioModificar.html');

	}


    private function eliminarFirmante()
	{
		$this->rl_proveedoresBD->eliminarFirmante($_REQUEST);
		$this->mensajeError=$this->rl_proveedoresBD->mensajeError;
		
		if($this->mensajeError == '' ){
			$this->mensajeOK ="El representante seleccionado fue eliminado con &eacute;xito";
			$this->collapse ="collapse" ;
		}
			
		$this->modificar();
	}


	//Mostrar listado de los registro disponibles
	private function listado()
	{ 	
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]=10;

		//busco el total de paginas
		$this->rl_proveedoresBD->totalfiltro($datos,$dt);
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		$formulario[0] = $datos;
		
		$this->rl_proveedoresBD->listadofiltro($datos,$dt);
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;
		$registros = count($formulario[0]["listado"]);

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'rl_Proveedores.php';
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

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ( $registros == 0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$formulario[0]["pagina_siguente"] = $datos["pagina_siguente"];
		$formulario[0]["pagina_ultimo"] = $datos["pagina_ultimo"];

		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("RutEmpresadin",$datos["RutEmpresadin"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$this->pagina->agregarDato("buscar",$datos["buscar"]);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/rl_proveedores_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}//Fin de clase
?>
