<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/flujofirmaBD.php");
include_once("includes/estadosworkflowBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new flujofirma();

class flujofirma {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $flujofirmaBD;
	// para el manejo de las tablas
	private $holdingBD;
	
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el RutEmpresa
	private $RutEmpresa="";
	private $RazonSocial="";
	private $Categoria="";
	private $idClausula = "";
	
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

	//Mensajes
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

		$this->opcion = "Flujos de Firma ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Flujos de Firma</li>";
		
		// instanciamos del manejo de tablas
		$this->flujofirmaBD = new flujofirmaBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->estadosworkflowBD = new estadosworkflowBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->flujofirmaBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->estadosworkflowBD->usarConexion($conecc);
		
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
	
		//print_r ($_REQUEST);
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
			case "APROBAR":
				$this->aprobar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	//Accion de agregar
	private function agregar()
	{	
		$dt = new DataTable();
		$datos = $_REQUEST;
		// si hubo algun evento
		if (isset($_REQUEST["accion2"]))
		{
			// revisamos
			switch ($_REQUEST["accion2"])
			{
				case "AGREGAR":
					$datos=$_REQUEST;
					
					if ($this->flujofirmaBD->agregar($datos,$dt))
					{	
						if($dt->leerFila())
						{
							$datos["idworkflow"] = $dt->obtenerItem("idwf");
							$_REQUEST["idworkflow"] =  $dt->obtenerItem("idwf");
							if ($this->flujofirmaBD->agregar_estado($datos))
							{
								$this->mensajeOK = "Grabado Exitosamente";
								$this->modificar();
								return;
							}
							else
							{
								$this->mensajeError.=$this->flujofirmaBD->mensajeError;
							}
							
						}
						else
						{
							$this->mensajeError.="Error, al rescatar id workflow";
						}
						
						break;
					}
					// sino guardamos el mensaje de error
					$this->mensajeError.=$this->flujofirmaBD->mensajeError;
					break;
					
				case "GRABAR":
					$this->mensajeError.= "Error, al menos debe agregado un estado para Guardar ";
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();

					return;
			}
		}
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos=$_REQUEST;
		$formulario[0] = $datos;
		
		$this->estadosworkflowBD->listado($dt);
		$this->mensajeError.=$this->estadosworkflowBD->mensajeError;
		
		$formulario[0]["idsestado"]=$dt->data;		
		
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/flujofirma_FormularioAgregar.html');

	}

	//Accion de modificar un registro
	private function modificar()
	{	
		$dt = new DataTable();
		$datos = $_REQUEST;

		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
		
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					$datos["idworkflow"] = $_REQUEST["idwf"];
					if ($this->flujofirmaBD->agregar_estado($datos))
					{
						$this->mensajeOK = "Grabado Exitosamente";
					}
					// sino guardamos el mensaje de error
					$this->mensajeError=$this->flujofirmaBD->mensajeError;
					
					
					break;
					
				case "GRABAR":
					
					$datos["idworkflow"] = $_REQUEST["idwf"];
					
					$diasmax = 0;
					
					if ($datos["idestadowfmax"] == "")
					{
						$this->mensajeError.="PARA GRABAR AL MENOS DEBE TENER UN ESTADO INGRESADO";
					}
					
					for ($l = 0; $l < $datos["idestadowfmax"] + 1; $l++) 
					{
						if (isset($datos["diasmx_".$l]))
						{
							$diasmax = $diasmax + $datos["diasmx_".$l];
							$datos["idestado"] = $l;
							$datos["diasmax"]  = $datos["diasmx_".$l];
							$this->flujofirmaBD->modificar_estado($datos);
							$this->mensajeError.=$this->flujofirmaBD->mensajeError;
						}	
					}
					
					if ($this->mensajeError == "")	
					{
						$datos["diasmax"] = $diasmax;
						$this->flujofirmaBD->modificar($datos);
						$this->mensajeError.=$this->flujofirmaBD->mensajeError;
					}
					
					if ($this->mensajeError == "")
					{
						
						$this->mensajeOK = "Modificaci&oacute;n Realizada Exitosamente";
					}
						
					break;
					
				case "ELIMINAR":
		
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					$datos["idworkflow"] = $datos["idwf"];
					if ($this->flujofirmaBD->eliminar_estado($datos))
					{
						
						$this->mensajeOK = "Eliminado Exitosamente";
					}
					// sino guardamos el mensaje de error
					$this->mensajeError.=$this->flujofirmaBD->mensajeError;
					
					break;
					
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
					
		$this->flujofirmaBD->obtener($datos,$dt);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;
		$formulario=$dt->data;	
		
		$this->flujofirmaBD->obtener_estados($datos,$dt);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;
		$formulario[0]["estados"]=$dt->data;	
		
		//$this->estadosworkflowBD->listado($dt);
		$this->estadosworkflowBD->listado_flujo($datos,$dt);
		$this->mensajeError.=$this->estadosworkflowBD->mensajeError;
		$formulario[0]["idsestado"]=$dt->data;		
		
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los posibles errores a la pagina
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/flujofirma_FormularioModificar.html');
		// si es que nos enviaron una accion
	}
	
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$this->flujofirmaBD->eliminar($_REQUEST);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Flujo fue eliminado correctamente";
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK); 
		}
		$this->listado();
	}

	//Mostrar listado de todas las disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		
		$datos = $_REQUEST;
		
		$this->flujofirmaBD->listado($dt);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;
		
		$formulario[0]["listado"]=$dt->data;

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'flujofirma.php';
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
			if ( $ver  ) $formulario[0]["listado"][$i]["ver"][0]   = "";
		}


		$this->pagina->agregarDato("formulario",$formulario);	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/flujofirma_Listado.html');
		
	}


	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
