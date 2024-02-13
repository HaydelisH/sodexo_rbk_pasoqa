<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/clausulasBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new clausulas();

class clausulas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $clausulasBD;
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

	//Iconos 
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';



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

		$this->opcion = "Clausulas ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Clausulas</li>";
		
		// instanciamos del manejo de tablas
		$this->clausulasBD = new clausulasBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->clausulasBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

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
	
		
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
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
			case "CLONAR":
				$this->clonar();
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
		$datos = $_POST;

		// si hubo algun evento
		if (isset($datos["accion2"]))
		{
			// revisamos
			switch ($datos["accion2"])
			{
				case "AGREGAR":

					$dt = new DataTable();

					// enviamos los datos del formulario a guardar
					if ($this->clausulasBD->agregar($datos,$dt))
					{	
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha guardado con exito";
						
						$_POST["idClausula"] = $dt->data[0]["idClausula"];

						//Me quedo en el modificar
						$this->modificar();
						return;
					}

					//Pasamos el error si hubo
					$this->mensajeError.=$this->proyectosBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
 		
 		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt1 = new DataTable();

		//Empresas
		$this->clausulasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		//Categorias
		$this->clausulasBD->obtenerCategoria($dt1);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt1->data;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK", $this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/clausulas_FormularioAgregar.html');

	}

	//Accion de modificar un registro
	private function modificar()
	{	
		$datos = $_POST;

		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "MODIFICAR":

					$dt = new DataTable();

					$datos["RutModificador"] = $this->seguridad->usuarioid;
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->clausulasBD->modificar($datos))
					{
						//Consultamos las Plantillas a las que pertenece
						$this->clausulasBD->obtenerPlantillas($datos,$dt);
						$this->mensajeError.=$this->clausulasBD->mensajeError;

						if( $dt->data[0]["idPlantilla"] != "" ){

							//Recorremos el array resultado
							foreach ($dt->data as $key => $value) {
								$array = array( "idPlantilla" => $dt->data[$key]["idPlantilla"]);
								$this->clausulasBD->modificarEstado($array);
								$this->mensajeError.=$this->clausulasBD->mensajeError;
							}
						}	
						
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
					}else{
						$this->mensajeError .= $this->clausulasBD->mensajeError;
					}
	
					$_POST["accion2"]=" ";
					$this->modificar();
					return;
					break;
					
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		
		// obtenemos los datos a modificar
		$this->clausulasBD->obtener($datos,$dt3);
		$this->mensajeError .= $this->clausulasBD->mensajeError;
		$formulario = $dt3->data;

		//Empresas
		$this->clausulasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;
	    
	    //Categorias
		$this->clausulasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;

		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
				
		//Imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/clausulas_FormularioModificar.html');
	}

	//Accion de modificar un registro
	private function modificarClonar()
	{	
		$datos = $_POST;

		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					
					$dt = new DataTable();
					$datos["RutModificador"] = $this->seguridad->usuarioid;
					
					if ($this->clausulasBD->modificar($datos))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha modificado con exito";
					}else{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->clausulasBD->mensajeError;
					}
					$_POST["accion2"]=" ";
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
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		
		// obtenemos los datos a modificar
		$this->clausulasBD->obtener($datos,$dt3);
		$this->mensajeError .= $this->clausulasBD->mensajeError;
		$formulario = $dt3->data;

		//Empresas
		$this->clausulasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;
	    
	    //Categorias
		$this->clausulasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;

		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
				
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/clausulas_FormularioModificarClonar.html');
		// si es que nos enviaron una accion
	}
	//Accion de modificar un registro
	private function aprobar()
	{	
		$datos = $_POST;

		if (isset($datos["accion2"]))
		{
			switch ($datos["accion2"])
			{
				case "APROBAR":
					$dt = new DataTable();
					$datos["RutAprobador"] = $this->seguridad->usuarioid;

					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->clausulasBD->aprobarClausula($datos,$dt))
					{
						//Pasamos el mensaje de Ok
						$this->mensajeOK="Registro Completado! Su registro se ha aprobado con exito"; 
					}
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					else
					{
						//Pasamos el error si hubo
						$this->mensajeError.=$this->proyectosBD->mensajeError;
					}
					$_POST["accion2"]=" ";
					//Me quedo en el modificar
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
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		
		// obtenemos los datos a modificar
		$this->clausulasBD->obtener($datos,$dt3);
		$this->mensajeError .= $this->clausulasBD->mensajeError;
		$formulario = $dt3->data;

		//Empresas
		$this->clausulasBD->obtenerEmpresas($dt);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;
	    
	    //Categorias
		$this->clausulasBD->obtenerCategoria($dt2);
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$formulario[0]["Categorias"] = $dt2->data;
		
		//Verificar si ya la clausula estaba aprobada
		if( $formulario[0]["Aprobado"] == 1) {
			$this->pagina->agregarDato("formulario",$formulario);
			
			//Pasamos los mensajes a la pagina 
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);	
			
			// imprimimos los datos en el template
			$this->pagina->imprimirTemplate('templates/clausulas_FormularioAprobarBlo.html');
		}
		else{
			$this->pagina->agregarDato("formulario",$formulario);
			
			//Pasamos los mensajes a la pagina 
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			
			// imprimimos los datos en el template
			$this->pagina->imprimirTemplate('templates/clausulas_FormularioAprobar.html');
		}
		
	}
	//Accion de clonar un registro
	private function clonar()
	{	
		$datos = $_POST;

		$dt = new DataTable();
		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->clausulasBD->clonar($datos,$dt)){

			//Pasamos al consultar el nuevo registro
			$_POST["idClausula"] = $dt->data[0]["idClausula"];
			$this->modificarClonar();
			return;
		}	

		//Pasamos el error si hubo
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Pasamos al listado actualizado
		$this->listado();
		return;
	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{	
		$datos = $_POST;

		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->clausulasBD->eliminar($datos)){
			//Pasamos el mensaje de Ok
			$this->mensajeOK="Registro Elimnado! Su registro se ha eliminado con exito";
			$this->listado();
			return;
		}
		//Pasamos el error si hubo
		$this->mensajeError.=$this->clausulasBD->mensajeError;
		$this->listado();
		return;
	}

	//Mostrar listado de todas las disponibles
	private function listado()
	{	
		$datos = $_POST;

		
	    	// creamos una nueva instancia de la tabla
			$dt = new DataTable();
			
			$this->clausulasBD->listado($dt);
			$this->mensajeError.=$this->clausulasBD->mensajeError;
			
			//Cambios a iconos
			foreach ($dt->data as $key => $value) {
				
				$dt->data[$key]['Titulo_Cl'] = substr(strip_tags( $dt->data[$key]['Titulo_Cl']),0,50);

				if($dt->data[$key]["Aprobado"] == 1){
					$dt->data[$key]["Aprobado"] = $this->verde;
					$dt->data[$key]["Aprob"] = 1;
				}
				else{
					$dt->data[$key]["Aprobado"] = $this->amarillo;
					$dt->data[$key]["Aprob"] = 0;
				}
			}

			$formulariox[0]["listado"] = $dt->data;
		
			$this->pagina->agregarDato("formulario",$formulariox);
			
			// agregamos los datos de mensaje de error
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

			// se imprime el template con la pagina
			$this->pagina->imprimirTemplate('templates/clausulas_Listado.html');

			return;
	  

	  
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}

?>
