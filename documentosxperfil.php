<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/tiposusuariosBD.php");
include_once("includes/documentosxperfilBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new documentosxperfil();


class documentosxperfil {

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

		$this->opcion = "Documentos por Perfil ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Documentos por Perfil</li>";
		
		// instanciamos del manejo de tablas
		$this->documentosxperfilBD 	= new documentosxperfilBD();
		$this->tiposusuariosBD 		= new tiposusuariosBD();
		$this->tiposdocumentosBD 	= new tiposdocumentosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->documentosxperfilBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);

		
		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r($_REQUEST);
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
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "FILTROS":
				$this->filtros();
				break;
			case "GRABAR":
				//$this->grabarfiltros();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function agregar()
	{	
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
					$datos=$_REQUEST;
					
					$this->mensajeError = "";
					for ($l = 0; $l < $_REQUEST["cantidad"]; $l++) {
						if (isset($_REQUEST["sel_".$l])){
	
							$datos["tipodocumentoid"]= $_REQUEST["sel_".$l];
							$this->documentosxperfilBD->agregar($datos);
							$this->mensajeError.=$this->documentosxperfilBD->mensajeError;
						}
					}
						
					// si todo esta ok
					if ($this->mensajeError=="")
					{
						$this->mensajeOK = "Informaci&oacute;n creada correctamente";
						// si resulta mostramos el listado
						$this->listado();
						return;
					}
					// sino guardamos el mensaje de error
					
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();

					return;
			}
		}
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos2=$_REQUEST;
		$formulario[0] = $datos2;

		$datos2["usuarioid"]=$this->seguridad->usuarioid;
		$datos2["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		
		$this->tiposusuariosBD->Todos($datos2,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulario[0]["tiposusuarios"]=$dt->data;	
		
		$cantidad = 0;
		$this->documentosxperfilBD->NoRegistrados($datos2,$dt);
		$this->mensajeError.=$this->documentosxperfilBD->mensajeError;
		$formulario[0]["tiposdocumentos"]=$dt->data;	
		$cantidad = count($dt->data);

		$this->pagina->agregarDato("formulario",$formulario);
		

		$this->pagina->agregarDato("cantidad",$cantidad);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/documentosxperfil_FormularioAgregar.html');

	}

	private function modificar()
	{	
		if (!isset($_REQUEST["accion2"]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// obtenemos los datos a modificar
			$this->documentosxperfilBD->obtener($_REQUEST,$dt);

			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por siaca hubo
			$this->mensajeError=$this->documentosxperfilBD->mensajeError;

		}

		// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->documentosxperfilBD->modificar($_REQUEST))
					{
						// si sale todo bien mostramos el listado
						$this->listado();
						return;
					}
					// si sale todo mal leemos el error
					$this->mensajeError=$this->documentosxperfilBD->mensajeError;
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_REQUEST;
					break;

				case "ELIMINAR":
					// si nos dijeron eliminar eliminamos
					$this->eliminar();
					// y de ahi nos vamos
					return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$this->documentosxperfilBD->Todos($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosxperfilBD->mensajeError;
		$campos[0]["documentosxperfil"]=$dt->data;
		
		// agregamos pagina actual del listado
		$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
		$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
		$this->pagina->agregarDato("formulario",$campos);
		// agregamos los posibles errores a la pagina
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/documentosxperfil_FormularioModificar.html');

	}

	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->documentosxperfilBD->eliminar($_REQUEST)){
			// si es que hubiera error lo obtenemos
			
			$this->mensajeOK = 'Informacion eliminada correctamente.';
		}
		
		$this->mensajeError=$this->documentosxperfilBD->mensajeError;
		$this->listado();

	}

	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;

		//busco el total de paginas
		$this->tiposusuariosBD->Todos($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["tiposusuarios"]=$dt->data;
		
		$this->documentosxperfilBD->Listado($datos,$dt);
		$this->mensajeError.=$this->documentosxperfilBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
	
		$mensajeNoDatos="";
		if (isset($_REQUEST["accion"])){
			if (count($dt->data) == 0){$this->mensajeOK="No hay informaci�n para la consulta realizada.";}
		}
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		$formulario[0]["tiposusuarios"]=$formulariox[0]["tiposusuarios"];
		
		$this->pagina->agregarDato("formulario",$formulario);
				
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosxperfil_Listado.html');
	}


	private function filtros()
	{	
		
		// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->documentosxperfilBD->modificar($_REQUEST))
					{
						// si sale todo bien mostramos el listado
						$this->listado();
						return;
					}
					// si sale todo mal leemos el error
					$this->mensajeError=$this->documentosxperfilBD->mensajeError;
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_REQUEST;
					break;

				case "ELIMINAR":
					// si nos dijeron eliminar eliminamos
					$this->eliminar();
					// y de ahi nos vamos
					return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
			
		$configid = 0;
		
		$datos=$_REQUEST;
		$this->filtrocamposconfigBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->filtrocamposconfigBD->mensajeError;
		
		$datos2["holdingid"] 		= $dt->data[0]["holdingid"];
		$datos2["tipousuarioid"] 	= $_REQUEST["tipousuarioid"];
		$datos2["tabla"] 			= $dt->data[0]["tabla"];
		$datos2["campoid"] 			= $dt->data[0]["campoid"];
		$datos2["camponombre"] 		= $dt->data[0]["camponombre"];
		$titulo 					= $dt->data[0]["descripcion"];
		$this->filtrocamposconfigBD->ConsultaDinamica($datos2,$dt);
		$this->mensajeError.=$this->filtrocamposconfigBD->mensajeError;		
		$formulariox[0]["listado"]=$dt->data;
		if (count($dt) == 0)
		{	
			$mensajeNoDatos="No hay informaci�n para la consulta realizada.";
		}else{ 
			$cantidad = count($dt->data);
			$mensajeNoDatos="";
			$formulario[0]=$_REQUEST;
			$formulario[0]["listado"]=$formulariox[0]["listado"];
			

			$datos=$_REQUEST;
			$configid = $datos["configid"];
			$configid = $configid + 1;
			$datos["configid"] = $configid;
			$this->filtrocamposconfigBD->Obtener($datos,$dt2);
			$this->mensajeError.=$this->filtrocamposconfigBD->mensajeError;
			if (count($dt2->data) > 0){
				$formulario[0]["configid"] = $configid;
				$formulario[0]["siguientefiltro"][0] = $configid;
			}					
		}		
		$this->pagina->agregarDato("formulario",$formulario);
		
		$this->pagina->agregarDato("cantidad",$cantidad);
		$this->pagina->agregarDato("titulo",$titulo);
		$this->pagina->agregarDato("tipousuarioid",$_REQUEST["tipousuarioid"]);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$mensajeNoDatos);
		$this->pagina->agregarDato("tabla",$datos2["tabla"]);
		$this->pagina->agregarDato("campoid",$datos2["campoid"]);
		$this->pagina->imprimirTemplate('templates/documentosxperfil_Filtro.html');
	}
	
	
	
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
