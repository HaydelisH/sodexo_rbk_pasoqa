<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/plantillasBD.php");
include_once("includes/rl_tiposdocumentosBD.php");
include_once("includes/clausulasBD.php");
include_once("includes/documentosBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/tipogestorBD.php");
include_once("generarpdf.php");
include_once("Config.php");

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;


// creamos la instacia de esta clase
$page = new plantillas();

class plantillas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $plantillasBD;
	private $rl_tiposdocumentosBD;
	private $documentosBD;
	private $clausulasBD;
	private $tipogestorBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para juntar los mensajes de Advertencia
	private $mensajeAd="";
	// para asignar el RutEmpresa
	private $RutEmpresa="";
	private $RazonSocial="";
	private $Categoria="";
	private $idPlantilla = "";
	private $flag = 0;
	private $html = "";
	private $orientacion = 'portrait';
	
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
	private $date;
	
	//Iconos 
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';


	private $ordinal = array();

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

		$this->opcion = "Plantillas ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>plantillas</li>";
		
		// instanciamos del manejo de tablas
		$this->plantillasBD = new plantillasBD();
		$this->rl_tiposdocumentosBD = new rl_tiposdocumentosBD();
		$this->documentosBD = new documentosBD();
		$this->clausulasBD = new clausulasBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->tipogestorBD = new tipogestorBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->plantillasBD->usarConexion($conecc);
		$this->rl_tiposdocumentosBD->usarConexion($conecc);
		$this->documentosBD->usarConexion($conecc);	
		$this->clausulasBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->tipogestorBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
//if( $this->seguridad->usuarioid == '26131316-2') print_r($_REQUEST);
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
			case "AGREGAR_CLAUSULA":
				$this->agregar_clausula();
				break;
			case "ELIMINAR_CLAUSULA":
				$this->eliminar_clausula();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "CAMBIAR_ORDEN":
				$this->cambiar_orden();
				break;
			case "CLONAR":
				$this->clonar();
				break;
			case "APROBAR":
				$this->aprobar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "GENERAR":
				$this->generar();
				break;
			case "GENERAR_MOD":
				$this->generar();
				break;
			case "VOLVER":
				$this->volver();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();
	}

	//Accion de agregar
	private function agregar()
	{	
		$datos = $_REQUEST;

		// si hubo algun evento
		if (isset($datos["accion2"]))
		{
			// revisamos
			switch ($datos["accion2"])
			{
				case "AGREGAR":
					
			
					$dt = new DataTable();
					// enviamos los datos del formulario a guardar
					if ($this->plantillasBD->agregar($datos,$dt))
					{	
						$this->mensajeOK = "Registro Completado! Su registro se ha guardado con exito"; 
						$_REQUEST["idPlantilla"] = $dt->data[0]["idPlantilla"];
						$this->modificar();
						return;
					}else{					
						$this->mensajeError.=$this->plantillasBD->mensajeError;
					}
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		// creamos una nueva instancia de la tabla
		$dt = new DataTable(); //Empresas
		$dt2 = new DataTable(); //Categorias
		$dt3 = new DataTable(); //Plantillas
		$dt4 = new DataTable(); //Flujos
		$dt5 = new DataTable(); //Tipo de Documento

		//Asignar tipo de empresa
		
		$this->plantillasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

	    //Categorias
		$this->plantillasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;
	   
	    //Flujos
	    // Buscamos las Empresas que vamos a asignar
		$this->plantillasBD->obtenerFlujos(array('tipoWF'=>NULL), $dt4);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Flujos"] = $dt4->data;

	    //Tipo de Documento
		$this->plantillasBD->obtenerTiposDocumentos($dt5);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["TipoDocumentos"] = $dt5->data;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;
		
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioAgregar.html');

	}
	//Agregar Clausulas a una Plantilla
	private function agregar_clausula()
	{	
		$datos = $_REQUEST;

		//Declaramos e instanciamos una nueva variable
		$dt = new DataTable();

		//Consultar las Clasulas disponibles para esa Categoria
		$this->plantillasBD->obtenerClausulasCategorias($datos,$dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;

		//Quitar las etiquetas de HTML antes de llevar al listado
		foreach ($dt->data as $i => $value) {
			if($dt->data[$i]["Aprobado"] == 1){
				$dt->data[$i]["Aprobado"] = $this->verde;
				$dt->data[$i]["aprob"] = 1;
			}
			else{
				$dt->data[$i]["Aprobado"] = $this->amarillo;
				$dt->data[$i]["aprob"] = 0;
			}
			$dt->data[$i]["Texto"] = strip_tags($dt->data[$i]["Texto"]);	
		}

		//Asignar resultado a una variable 
		$formulario[0] = $datos;
		$formulario[0]["listado_clausulas"] = $dt->data;

	   	//Asignar la variable al campo en html
	    $this->pagina->agregarDato("formulario",$formulario);

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioAgregarClausulas.html');

	}

	//Accion de modificar un registro
	private function modificar()
	{	
		$datos = $_REQUEST;

		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":

					$dt = new DataTable();
					$datos["RutModificador"] = $this->seguridad->usuarioid;

					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->plantillasBD->modificar($datos))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
					}else{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->plantillasBD->mensajeError;
					}
					$_REQUEST["accion2"]=" ";
					//Me quedo en el modificar
					$this->modificar();
					return;
					break;
					
				case "APROBAR":
					
					$datos["RutAprobador"] = $this->seguridad->usuarioid;
					
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->plantillasBD->aprobarPlantilla($datos))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha aprobado con exito";
					}
					else
					{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->plantillasBD->mensajeError;
					}
					$_REQUEST["accion2"]=" ";
					$this->aprobar();
					return;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		// creamos una nueva instancia de la tabla
		$dt = new DataTable(); //Empresas
		$dt2 = new DataTable(); //Categorias
		$dt3 = new DataTable(); //Plantillas
		$dt4 = new DataTable(); //Flujos
		$dt5 = new DataTable(); //Tipo de Documento
		$dt6 = new DataTable(); //Clausulas de la Plantilla

		//Buscar datos de la plantilla 
		$this->plantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->plantillasBD->mensajeError;
		$formulario = $dt->data;
		$tipoWF = $dt->data[0]['tipoWF'];

		//Asignar tipo de empresa		
		$this->plantillasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

	    //Categorias
		$this->plantillasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;
	   
	    //Flujos
	    // Buscamos las Empresas que vamos a asignar
		$this->plantillasBD->obtenerFlujos(array('tipoWF'=>$tipoWF), $dt4);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Flujos"] = $dt4->data;

	    //Tipo de Documento
		if ($tipoWF == 1) {// RRLL
			$this->rl_tiposdocumentosBD->Todos($dt5);
			$this->mensajeError.=$this->rl_tiposdocumentosBD->mensajeError;
		}
		else {// RRHH
			$this->plantillasBD->obtenerTiposDocumentos($dt5);
			$this->mensajeError.=$this->plantillasBD->mensajeError;
		}
		$formulario[0]["TipoDocumentos"] = $dt5->data;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;
			
		$this->plantillasBD->obtenerClausulasPlantillas($datos,$dt6);
		$this->mensajeError .= $this->plantillasBD->mensajeError;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;

		$aux = count($dt6->data);

		$verde_t = '<div style="text-align: center;"><i class="fa fa-check-circle-o fa-lg" aria-hidden="true" style="color:green;" data-toggle="tooltip" title="Incluye el T&iacute;tulo de Plantilla"></i></div>';
		$amarillo_t	= '<div style="text-align: center;"><i class="fa fa-times-circle-o fa-lg" aria-hidden="true" style="color:red;" data-toggle="tooltip" title="No incluye el T&iacute;tulo de la Plantilla"></i></div>';

		$verde_e = '<div style="text-align: center;"><i class="fa fa-check-circle-o fa-lg" aria-hidden="true" style="color:green;" data-toggle="tooltip" title="Incluye Encabezado autom&aacute;tico" ></i></div>';
		$amarillo_e	= '<div style="text-align: center;"><i class="fa fa-times-circle-o fa-lg" aria-hidden="true" style="color:red;" 	data-toggle="tooltip" title="No incluye el Encabezado autom&aacute;tico"></i></div>';

		if( $aux == 0 ){
			$this->mensajeAd .= "Esta Plantilla no tiene Clausulas asociadas";
		}
		else{
			foreach ($dt6->data as $i => $value) {
				if($dt6->data[$i]["Aprobado"] == 1){
					$dt6->data[$i]["Aprobado"] = $this->verde;
				}
				else{
					$dt6->data[$i]["Aprobado"] = $this->amarillo;
				}

				if($dt6->data[$i]["Encabezado"] == 1){
					$dt6->data[$i]["Encabezado"] = $verde_e;
				}
				else{
					$dt6->data[$i]["Encabezado"] = $amarillo_e;
				}

				if($dt6->data[$i]["Titulo"] == 1){
					$dt6->data[$i]["Titulo"] = $verde_t;
				}
				else{
					$dt6->data[$i]["Titulo"] = $amarillo_t;
				}
			}

			$formulario[0]["listado_clausulas"] = $dt6->data;
		}
		
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioModificar.html');
		
	}
	//Accion de modificar un registro
	private function modificarClonar()
	{	
		$datos = $_REQUEST;

		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":

					$dt = new DataTable();
					$datos["RutModificador"] = $this->seguridad->usuarioid;

					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->plantillasBD->modificar($datos))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
					}else{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->plantillasBD->mensajeError;
					}
					$_REQUEST["accion2"]=" ";
					//Me quedo en el modificar
					$this->modificarClonar();
					return;
					break;
	
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		// creamos una nueva instancia de la tabla
		$dt = new DataTable(); //Empresas
		$dt2 = new DataTable(); //Categorias
		$dt3 = new DataTable(); //Plantillas
		$dt4 = new DataTable(); //Flujos
		$dt5 = new DataTable(); //Tipo de Documento
		$dt6 = new DataTable(); //Clausulas de la Plantilla

		//Buscar datos de la plantilla 
		$this->plantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->plantillasBD->mensajeError;
		$formulario = $dt->data;
		$tipoWF = $dt->data[0]['tipoWF'];

		//Asignar tipo de empresa		
		$this->plantillasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

	    //Categorias
		$this->plantillasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;
	   
	    //Flujos
	    // Buscamos las Empresas que vamos a asignar
		$this->plantillasBD->obtenerFlujos(array('tipoWF'=>$tipoWF), $dt4);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Flujos"] = $dt4->data;

	    //Tipo de Documento
		if ($tipoWF == 1) {// RRLL
			$this->rl_tiposdocumentosBD->Todos($dt5);
			$this->mensajeError.=$this->rl_tiposdocumentosBD->mensajeError;
		}
		else {// RRHH
			$this->plantillasBD->obtenerTiposDocumentos($dt5);
			$this->mensajeError.=$this->plantillasBD->mensajeError;
		}
		$formulario[0]["TipoDocumentos"] = $dt5->data;
			
		$this->plantillasBD->obtenerClausulasPlantillas($datos,$dt6);
		$this->mensajeError .= $this->plantillasBD->mensajeError;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;

		$aux = count($dt6->data);
		
		if( $aux == 0 ){
			$this->mensajeAd .= "Esta Plantilla no tiene Clausulas asociadas";
		}
		else{
			foreach ($dt6->data as $i => $value) {
				if($dt6->data[$i]["Aprobado"] == 1){
					$dt6->data[$i]["Aprobado"] = $this->verde;
				}
				else{
					$dt6->data[$i]["Aprobado"] = $this->amarillo;
				}
			}

			$formulario[0]["listado_clausulas"] = $dt6->data;
		}
		
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);	
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);

		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioModificar.html');
		
	}
	//Cambiar Orden de Clausulas 
	private function cambiar_orden()
	{
		//$_REQUEST: 
		//[table] => Array ( [0] => [1] => 1 [2] => 17 [3] => 2 [4] => 19 
		//[idPlantilla] => 10 
		//[accion] => CAMBIAR_ORDEN
		if (isset($_REQUEST["accion"]))
		{    
			//Declaramos las variables necesarias
			$dt = new DataTable();
			$array = $_REQUEST["table"];

			//Total de valores del array 
			$total = count($_REQUEST["table"]);
			//Recorro el array
			for ( $i = 1; $i < $total ; $i++ ){
				//Cargo los datos que corresponden al primer cambio
				$aux = array ( "idPlantilla" => $_REQUEST["idPlantilla"], "idClausula" => $array[$i], "Orden"=> $i );
				//LLamar al sp 
				$this->plantillasBD->cambiarOrdenClausulas($aux,$dt);
			}

			// guardamos el error por si hubo
			$this->mensajeError.=$this->plantillasBD->mensajeError;
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			//Buscamos el idCategoria que vamos a asignar
			$this->plantillasBD->obtenerEmpresa($_REQUEST,$dt);
			$this->mensajeError.=$this->plantillasBD->mensajeError;
			//Asignar a variable el resultado
			$this->RutEmpresa = $dt->data[0]["RutEmpresa"];	
			$this->pagina->agregarDato("RutEmpresa",$this->RutEmpresa);
			//Imprimir plantilla
			$this->pagina->imprimirTemplate('templates/plantillas_FormularioModificar.html');
			return;
		}	

	}
	//Accion de modificar un registro
	private function aprobar()
	{	
		$datos = $_REQUEST;

		if (isset($datos["accion2"]))
		{   
			switch ($datos["accion2"])
			{   
				case "APROBAR":
					
					$datos["RutAprobador"] = $this->seguridad->usuarioid;
					
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->plantillasBD->aprobarPlantilla($datos))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha aprobado con exito";
					}
					else
					{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->plantillasBD->mensajeError;
					}
					$_REQUEST["accion2"]=" ";
					$this->aprobar();
					return;
					break;

				case "ELIMINAR":
					// si nos dijeron eliminar eliminamos
					$this->eliminar();
					// y de ahi nos vamos
					return;

				case "GENERAR":
					// si nos dijeron eliminar eliminamos
					$this->generar();
					// y de ahi nos vamos
					return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
		// creamos una nueva instancia de la tabla
		$dt = new DataTable(); //Empresas
		$dt2 = new DataTable(); //Categorias
		$dt3 = new DataTable(); //Plantillas
		$dt4 = new DataTable(); //Flujos
		$dt5 = new DataTable(); //Tipo de Documento
		$dt6 = new DataTable(); //Clausulas de la Plantilla

		//Buscar datos de la plantilla 
		$this->plantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->plantillasBD->mensajeError;
		$formulario = $dt->data;
		$tipoWF = $dt->data[0]['tipoWF'];

		//Asignar tipo de empresa		
		$this->plantillasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

	    //Categorias
		$this->plantillasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;
	   
	    //Flujos
	    // Buscamos las Empresas que vamos a asignar
		$this->plantillasBD->obtenerFlujos(array('tipoWF'=>$tipoWF), $dt4);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		$formulario[0]["Flujos"] = $dt4->data;

	    //Tipo de Documento
		if ($tipoWF == 1) {// RRLL
			$this->rl_tiposdocumentosBD->Todos($dt5);
			$this->mensajeError.=$this->rl_tiposdocumentosBD->mensajeError;
		}
		else {// RRHH
			$this->plantillasBD->obtenerTiposDocumentos($dt5);
			$this->mensajeError.=$this->plantillasBD->mensajeError;
		}
		$formulario[0]["TipoDocumentos"] = $dt5->data;
			
		$this->plantillasBD->obtenerClausulasPlantillas($datos,$dt6);
		$this->mensajeError .= $this->plantillasBD->mensajeError;

		//Tipo de Documento segun el gestor 
		$this->tipogestorBD->listado($dt3);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]["TipoGestor"] = $dt3->data;

		$aux = count($dt6->data);
		
		if( $aux == 0 ){
			$this->mensajeAd .= "Esta Plantilla no tiene Clausulas asociadas";
		}
		else{
			foreach ($dt6->data as $i => $value) {
				if($dt6->data[$i]["Aprobado"] == 1){
					$dt6->data[$i]["Aprobado"] = $this->verde;
				}
				else{
					$dt6->data[$i]["Aprobado"] = $this->amarillo;
				}
			}

			$formulario[0]["listado_clausulas"] = $dt6->data;
		}
		
		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);	
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);	

		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioAprobar.html');
			
	}

	//Accion de clonar un registro
	private function clonar()
	{	
		$datos = $_REQUEST;

		$dt = new DataTable();
		$dt1 = new DataTable();
		$dt2 = new DataTable();

		//Se Clona la plantilla
		if( $this->plantillasBD->clonar($datos,$dt) ){
			$_REQUEST['idPlantilla'] = $dt->data[0]['idPlantilla'];
			$this->modificarClonar();
			return;
		}
		$this->mensajeError .= $this->plantillasBD->mensajeError;
		$this->listado();
		return;
	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{	
		//Instanciar el obtejo
		$dt = new DataTable();

		$datos = $_REQUEST;

		$this->plantillasBD->eliminar($datos);
		$this->mensajeError .= $this->plantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			$this->mensajeOK="Registro Eliminado! Su registro se ha eliminado con exito";
		}
		$this->listado();
		return;
	}
	//Elimnar Clausulas de una Plantilla
	private function eliminar_clausula()
	{	
		$datos = $_REQUEST;

		// si hubo algun evento
		if (isset($datos["accion3"]))
		{
			// revisamos
			switch ($datos["accion3"])
			{
				case "ELIMINAR_CLAUSULA":

					//Declaramos e instanciamos una nueva variable
					$dt = new DataTable();
		
					//Consultar todas las  Clausulas disponibles
					$this->plantillasBD->eliminarClausula($datos);
					$this->mensajeError.=$this->plantillasBD->mensajeError;

				    //Nos vamos al modificar
				    $this->modificar();
					return;
			}
		}	

		//Declaramos e instanciamos una nueva variable
		$dt = new DataTable();

		//Consultar todas las  
		$this->plantillasBD->obtenerClausulasCategorias($datos,$dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		
		//Asignar resultado a una variable 
		$lista = $dt->data;
		//Asignar idPlantilla para tenerla disponible al asignar las Clausulas
	    $this->pagina->agregarDato("idPlantilla",$_REQUEST["idPlantilla"]);
	    $this->pagina->agregarDato("idCategoria",$_REQUEST["idCategoria"]);
	    	//Asignar la variable al campo en html
	    $this->pagina->agregarDato("listado_clausulas",$lista);
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/plantillas_FormularioModificar.html');
	}

	//Mostrar listado de todas las disponibles
	private function listado()
	{  
		$datos = $_REQUEST;

	    // creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// Buscamos las Empresas que vamos a asignar
		$this->plantillasBD->listado($dt);
		$this->mensajeError.=$this->plantillasBD->mensajeError;
		

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				
				$dt->data[$key]['Descripcion_Pl'] = substr(strip_tags( $dt->data[$key]['Descripcion_Pl']),0,25);
				$dt->data[$key]['NombreWF'] = substr(strip_tags( $dt->data[$key]['NombreWF']),0,25);

				if($dt->data[$key]["Aprobado"] == 1){
					$dt->data[$key]["Aprobado"] = $this->verde;
					$dt->data[$key]["Aprob"] = 1;
				}
				else{
					$dt->data[$key]["Aprobado"] = $this->amarillo;
					$dt->data[$key]["Aprob"] = 0;
				}
			}
		}
		$formulario[0]['listado'] = $dt->data;

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Plantillas.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
			$ver = $dt->data[0]["ver"];
		}

		$num = count($formulario[0]["listado"]);

		if ( $crea     ) $formulario[0]["crear"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
			if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
			if ( $ver ) $formulario[0]["listado"][$i]["ver"][0]   = "";
		}

	    $this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/plantillas_Listado.html');
	}
	//Sustituir acentos
	/*private static function TildesHtml($cadena){ 
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);     
    }*/


	//Sustituir acentos &nbsp;
	/*private static function TildesHtml($cadena){ 

        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","°","º","> <","<span> "),
                                         array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                    "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","&deg;","&deg;",">&nbsp;<","<span>&nbsp;"), $cadena);     
    }*/	
	
	//Generar un PDF a partir un HTML
	private function generar()
	{
		$datos= $_REQUEST;
		$dt = new DataTable();

		$this->plantillasBD->obtener($datos,$dt);
		$this->mensajeError .= $this->plantillasBD->mensajeError;

		/*if( $dt->data[0]["idTipoDoc"] == 2 ){ //Si es Anexo 
			$this->orientacion = 'landscape';
		}else{
			$this->orientacion = 'portrait';
		} */
		$this->construirPlantilla($this->html);

		$resultado = "<html><body>".$this->html."</body></html>";

		$texto = utf8_encode($resultado);

		//Sustituir acentos 
		$texto_completo = ContenedorUtilidades::TildesHtml($texto);

		$this->GrabaLog($texto_completo);
		if( GENERADOR_PDF == 'SERVICIO' ){
			
			//Generar un archivo de html
					
			$this->date = date('dmY_hms');
			$nom = $datos["idPlantilla"].'_'.$this->date;			
			$ruta = RUTA_GENERACION_ARCHIVO.NOMBRE_PLA.$nom.".html";
			
			$archivo = fopen($ruta,"w");
			fwrite($archivo, $texto_completo);
			fclose($archivo);
					
			//Generar PDF
			$this->generarPDFconServicio($texto_completo,$nom);
			
		}else{

			$this->generarPDF($texto_completo);
		}
		
		$ruta = './'.CARPETA.'/'.NOMBRE_PLA.$datos["idPlantilla"].'_'.$this->date.'.pdf';
		$formulario[0]["ruta"] = $ruta."#toolbar=0";
		$formulario[0]["idPlantilla"] = $datos["idPlantilla"];
     
		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		if( $datos['accion'] == 'GENERAR'){
			$this->pagina->imprimirTemplate('templates/plantillas_Generar.html');	
		}else{
			$this->pagina->imprimirTemplate('templates/plantillas_Generar_Mod.html');	
		}
		
	}

	//Volver a modificar
	private function volver()
	{
		$this->modificar();
	}

	//Construir Plantilla
	private function construirPlantilla(&$resultado){
		
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';
		
		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		// Obtenemos los datos de las Clausulas relacionados
		$this->documentosBD->obtenerClausulasPlantillas($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

        //Agregamos Titulo de la Plantilla
		//size: 21.59cm 28cm;
        $html = '<style>@page {'.$papel.' margin-top:3cm; margin-left:3cm; margin-right:3cm; margin-bottom:1cm; } 
@media print {'.$papel.'margin-top:3cm; margin-left:3cm; margin-right:3cm; margin-bottom:1cm; } 
</style>
        <div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';

        //Variables
        $num = 1;
        $contenido = '';
     
     	//Construir Plantilla con las Clausulas
        if( count($dt->data) > 0){

        	foreach ($dt->data as $i => $value) {
        		
				$clausula = '';
				$aux = '';
				
				//Si estan el titulo y encabezado activos 
				if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){
					
					$this->ordinal[$num] = $dt->data [$i]["idClausula"];
	        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
					
	        		$clausula = "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong> ";
																  
					if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
		        		$aux = substr($dt->data[$i]["Texto"],30);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
		        		$aux = substr($dt->data[$i]["Texto"],32);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
						$aux = substr($dt->data[$i]["Texto"],3);
					}
					if ( $aux != '' ){
						$clausula .= $aux;	        		
						$num++;
					}
				}
				
				//Si estan el titulo y encabezado inactivos 
				if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
					$aux = $dt->data[$i]["Texto"];
					$clausula = $aux; 
				}
				
				//Si esta el encabezado activo y el titulo no 
				if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
					
					$this->ordinal[$num] = $dt->data [$i]["idClausula"];
	        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
					
	        		$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
					
					if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
		        		$aux = substr($dt->data[$i]["Texto"],30);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
		        		$aux = substr($dt->data[$i]["Texto"],32);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
						$aux = substr($dt->data[$i]["Texto"],3);
					}
					
					if ( $aux != '' ){
						$clausula .= $aux;	        		
						$num++;
					}
				}
				
				//Si el titulo esta activo y el encabezado no 
				if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){
									
	        		$clausula = "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";
					
					if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
		        		$aux = substr($dt->data[$i]["Texto"],30);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
		        		$aux = substr($dt->data[$i]["Texto"],32);
		        	}
					if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
						$aux = substr($dt->data[$i]["Texto"],3);
					}
					if ( $aux != '' ){
						$clausula .= $aux;	        		
					}
				}
				
				//Agregar clausulas
				$contenido .= $clausula;
			}
	        
			///Limpiar el HTML
			//$aux = '';
	        //$aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><h3>');
							
			$html .= $contenido;
	        $html .= "</div>";
        }
        else{
        	return false;
        }

    	//Reasignar HTML a un atributo de la clase
	    $resultado = $html;
	    return $resultado;
	    //FIN
	}

	//PDF Contrato 
	private function generarPDF($html){

		try { 
			
			$datos = $_REQUEST;	
	        // instancia clase dompdf
			$dompdf = new Dompdf();
			
			$dompdf->loadHtml($html);
			
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper(TAMANO_HOJA, $this->orientacion);
			
			// Render the HTML as PDF
			$dompdf->render();		
							
			$pdf = $dompdf->output();
			$this->date = date('dmY_hms');

			//Asignar ruta del documento a generar
			$this->ruta = dirname(__FILE__).'/'.CARPETA.'/'.NOMBRE_PLA.$datos["idPlantilla"].'_'.$this->date.'.pdf';	
				
			file_put_contents($this->ruta, $pdf);
			
			} catch (Exception $e) {
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
	}
	
	//PDF Contrato 
	private function generarPDFconServicio($html,$plantilla){
		
		$generarpdf  = new generarpdf();
		return $generarpdf->generarPlantillaPorConsola($plantilla);
	}
	
	//Imprimir fin de Plantilla
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

	//Grabar log del HTML
	private function GrabaLog($datos){
		//graba log del xml
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs/logHTML'.@date("Ymd").'.TXT';	
		$ar=fopen($nomarchivo,"a") or
		   die("Problemas en la creacion");
		fputs($ar,@date("H:i:s",$time)." ".$datos);
		fputs($ar,"\n");
		fclose($ar);
	}
}
?>
