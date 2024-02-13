<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/empresasBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/firmasBD.php");
include_once("includes/cargosBD.php");


// creamos la instacia de esta clase
$page = new PlantillasPorEmpresas();


class PlantillasPorEmpresas {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $empresasBD;
	private $PlantillasBD;
	private $cargosBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
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
	
	//Iconos 
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';


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
        //print_r($_REQUEST);
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "Asociar Plantillas a Empresa ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Asociar Plantillas a Empresa</li>";
		
		// instanciamos del manejo de tablas
		$this->empresasBD = new empresasBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->firmasBD = new firmasBD();
		$this->cargosBD = new cargosBD();
		$this->PlantillasBD = new plantillasBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->empresasBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->firmasBD->usarConexion($conecc);
		$this->cargosBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		
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
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "PLANTILLAS":
				$this->plantillas();
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

		// si hubo algun evento
		if (isset($datos["accion2"]))
		{
			// revisamos
			switch ($datos["accion2"])
			{
				case "AGREGAR":
					
					$this->PlantillasBD->total($dt);
					$this->mensajeError .= $this->PlantillasBD->mensajeError;
					$datos['cantidad'] = $dt->data[0]['total'];

					for ($l = 0; $l < $datos["cantidad"]; $l++) {
						if (isset($datos["sel_".$l])){
	
							$datos["idPlantilla"]= $datos["sel_".$l];
							$this->PlantillasBD->agregarAEmpresa($datos);
							$this->mensajeError.=$this->PlantillasBD->mensajeError;
						}
					}
						
					// si todo esta ok
					if ($this->mensajeError=="")
					{
						$this->mensajeOK = "Plantilla agrega correctamente";
						$this->plantillas();
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

		$dt = new DataTable();

		$this->empresasBD->obtener($datos,$dt);		
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario = $dt->data;
		
		//Asignamos los datos que recibimos del formulario
		$this->PlantillasBD->listadoDiferencia($datos, $dt);
		$this->mensajeError.=$this->PlantillasBD->mensajeError;

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				
				$dt->data[$key]['Descripcion_Pl'] = substr(strip_tags( $dt->data[$key]['Descripcion_Pl']),0,30);

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

		$formulario[0]["listado"]=$dt->data;	

		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/plantillasPorEmpresas_FormularioAgregar.html');

	}


	//Accion de modificar un registro 
	private function plantillas()
	{	
		
		$datos = $_REQUEST;
		$dt = new DataTable();
		
		$this->empresasBD->obtener($datos,$dt);		
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario = $dt->data;
		
		$this->PlantillasBD->obtenerPlantillasEmpresas($datos,$dt);		
		$this->mensajeError.=$this->PlantillasBD->mensajeError;

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				
				$dt->data[$key]['Descripcion_Pl'] = substr(strip_tags( $dt->data[$key]['Descripcion_Pl']),0,30);

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

		$formulario[0]["listado"]=$dt->data;	

	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
	    $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/plantillasPorEmpresas_FormularioModificar.html');

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$datos = $_REQUEST;
		
		$this->PlantillasBD->eliminarPlantillaEmpresa($datos,$dt);
		$this->mensajeError .= $this->PlantillasBD->mensajeError;

		if( $this->mensajeError == '' ){
			$this->mensajeOK = "La Plantilla fue eliminada de la empresa seleccionada";
		}
		$this->plantillas();
		return;

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{ 
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Empresas.php';
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

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/plantillasPorEmpresas_Listado.html');
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}//Fin de clase
?>
