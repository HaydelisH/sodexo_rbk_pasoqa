<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/rl_proveedoresBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/firmasBD.php");
include_once("includes/usuariosmantBD.php");
/*
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/cargosBD.php");*/
//include_once("includes/firmantescentrocostoBD.php");


// creamos la instacia de esta clase
$page = new institucion();


class institucion {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
    private $rl_proveedoresBD;
    /*
	private $cargosBD;
	// para el manejo de las tablas
	private $holdingBD;*/
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

		$this->opcion = "Instituciones ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Instituciones</li>";
		
		// instanciamos del manejo de tablas
        $this->rl_proveedoresBD = new rl_proveedoresBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->firmasBD = new firmasBD();
		$this->usuariosmantBD = new usuariosmantBD();
        /*
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->cargosBD = new cargosBD();*/
		//$this->firmantescentrocostoBD = new firmantescentrocostoBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
        $this->rl_proveedoresBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->firmasBD->usarConexion($conecc);
		$this->usuariosmantBD->usarConexion($conecc);
       /*
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->cargosBD->usarConexion($conecc);*/
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
			case "MODIFICAR":
				$this->modificar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
            case "AGREGAR_R":
                $this->agregarRepresentantePersoneria();
				break;
            case "ELIMINAR_R":
                $this->eliminarRepresentante();
                break;
            /*
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "MODIFICAR_R":
				$this->modificarRepresentantePersoneria();
				break;*/
			/*case "VER_MODIFICAR_CENTRO_COSTO":
				$this->verModificarCentroCosto();
				break;*/
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	/*private function verModificarCentroCosto()
	{
		$datos = $_POST;

        $formulario[0] = $datos;
		$this->pagina->agregarDato("formulario",$formulario);
		//$this->pagina->agregarDato("formulario",$formulario);
	   
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
	
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/empresas_FirmantesCentroCosto.html');

	}*/

	//Accion del boton agregar un nuevo registro 
    private function agregar()
	{	
        $datos = $_POST;
        
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
		
        /*
        //Asignamos los datos que recibimos del formulario
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;

		$formulario[0]["empresas"]=$dt->data;	

		$this->pagina->agregarDato("formulario",$formulario);
        */
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_institucion_FormularioAgregar.html');

    }
    

	//Accion del boton agregar un nuevo registro 
    private function agregarRepresentantePersoneria()
	{
        // si hubo algun evento
		if (isset($_POST["accion2"]))
		{
            // revisamos
			switch ($_POST["accion2"])
			{
                case "AGREGAR_R":
                    // enviamos los datos del formulario a guardar

                    $datos = $_POST;
            
                    //csb 26-05-2021 la clave ya no es el rut sin dv
					/*$rut_arr 	= explode("-",$datos["RutUsuario"]);
                    $rut_sindv 	= $rut_arr[0];		
                    
                    $datos["clave"] = hash('sha256', $rut_sindv);*/

					//csb 26-05-2021 clave temporal compleja
					$largo = 10;
					$clave = '';
					$clave = ContenedorUtilidades::generar_password_complejo($largo);
								
					$cl = hash('sha256',$clave); 
					$datos['clave'] = $cl;
					//fin

					$datos['rolid']= ROL_PROVEEDOR;
                    $datos['TipoCorreo'] = CODIGO_CORREO_USUARIO_NUEVO;
                    $datos['TipoUsuario'] = PERFIL_PROVEEDOR;
                    
                    if ($this->rl_proveedoresBD->agregarFirmanteProveedor($datos))
                    {
						//Actualizar clave de usuario
						$datos['clave'] = $clave;
						$datos['newusuarioid'] = $datos["RutUsuario"];
						$this->usuariosmantBD->AgregarClaveTemporal($datos);
						$this->mensajeError .= $this->usuariosmantBD->mensajeError;		
						//fin	

                        // si resulta mostramos el listado
                        $this->mensajeOK = 'Registro Completado! Su registro se ha guardado con exito';
                        //$this->collapse = 'collapse';
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
        $datos = $_POST;
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
		
		//Selecciona por defecto la primera opcion
		if( count($dt1->data) == 1 ) $formulario[0]["idFirma"] = $dt1->data[0]["idFirma"];
        /*

		$this->cargosBD->listado($dt); 
		$this->mensajeError.=$this->cargosBD->mensajeError;
		$formulario[0]['Cargos'] = $dt->data;

        */
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
        
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_institucion_representantes_FormularioAgregar.html');

    }


	//Accion de modificar un registro 
    private function modificar()
	{	
        $dt = new DataTable();
		$datos = $_POST;
        
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
					// y guardamos los datos enviamos en una variable
					//$campos[0]=$_POST;
                break;
				case "VOLVER":
					// mostramos el listado
					$this->listado();
                return;
            }
        }
            
		$this->rl_proveedoresBD->obtenerProveedor($datos,$dt);		
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario[0]["Proveedores"]=$dt->data;	
        
        // aqui se obtienen los datos del representante
		$this->rl_proveedoresBD->obtenerFirmantes($datos,$dt); 
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario[0]["representantes"]=$dt->data;	
        
		if (count($dt->data) > 0){
            foreach ($dt->data as $key => $value) {
                $formulario[0]["representantes"][$key]["RutProveedor"] = $datos["RutProveedor"];
			}
		}
		        
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
	    $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
        // imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/rl_institucion_FormularioModificar.html');

    }

	/*private function modificarRepresentantePersoneria()
	{
		//var_dump($_POST);
		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR_R":

					$datos = $_POST;
										
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					if ($this->empresasBD->modificarRepresentantePersoneria($datos))
					{
						$this->mensajeOK = 'Registro Actualizado! Su registro se ha actualizado con exito';
					}else{
						$this->mensajeError=$this->empresasBD->mensajeError;
					}
					$campos[0]=$_POST;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}
         
      	$datos = $_POST;
		$dt = new DataTable();
	
        // aqui se obtienen los datos del representante
		$this->empresasBD->obtenerRepresentantePersoneria($datos,$dt); 
		$this->mensajeError.=$this->empresasBD->mensajeError;
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

		$this->cargosBD->listado($dt); 
		$this->mensajeError.=$this->cargosBD->mensajeError;
		$formulario[0]['Cargos'] = $dt->data;

		$this->pagina->agregarDato("formulario",$formulario);//agrega los datos al formulario
	    
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
	
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/empresas_representantes_FormularioModificar.html');

	}*/
	
	//Accion de eliminar un registro
	/*private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$datos = $_POST;
		$datos["TipoEmpresa"] = 1;

 		if ($this->empresasBD->eliminar($datos)){
			// si es que hubiera error lo obtenemos
			
			// si elimino entonces mostrar listado sin ningún filtro, o sea mostrar todo
			$_POST["pagina"]  = 1;
			$_POST["nombrex"] = "";
			$this->mensajeOK="Su registro se ha eliminado correctamente";
		}
		
		$this->mensajeError=$this->empresasBD->mensajeError;
		$this->listado();

	}*/


    private function eliminarRepresentante()
	{
        //var_dump($_POST);
		$this->rl_proveedoresBD->eliminarFirmante($_POST);
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
		$datos=$_POST;

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
		//$formulario[0]["listado"]=$dt->data;

		$this->rl_proveedoresBD->listadofiltro($datos,$dt);
		$this->mensajeError.=$this->rl_proveedoresBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;
		$registros = count($formulario[0]["listado"]);

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'rl_institucion.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			//$elimina = $dt->data[0]["elimina"];
		}
        
        $num = count($formulario[0]["listado"]);

		if ( $crea     ) $formulario[0]["crear"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
			//if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
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
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$this->pagina->agregarDato("buscar",$datos["buscar"]);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
        $this->pagina->imprimirTemplate('templates/rl_instituciones_Listado.html');
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
