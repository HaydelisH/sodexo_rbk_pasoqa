<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/usuariosmantBD.php");
include_once("includes/FirmasBD.php");
include_once("includes/firmantesBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/rolesBD.php");
include_once("includes/empresasBD.php");
include_once("includes/centroscostoBD.php");

// creamos la instacia de esta clase
$page = new usuariosmant();


class usuariosmant {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $usuariosmantBD;
	private $FirmasBD;
	private $firmantesBD;
	private $rolesBD;
	private $empresasBD;
	private $centroscostoBD;
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
	
	private $nroopcion=5; //n�mero de opci�n este debe estar en la tabla opcionessistema
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

		$this->opcion = "Usuarios ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Usuarios</li>";
		
		// instanciamos del manejo de tablas
		$this->usuariosmantBD = new usuariosmantBD();
		$this->FirmasBD = new FirmasBD();
		$this->firmantesBD = new firmantesBD();
		$this->rolesBD = new rolesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->empresasBD = new empresasBD();
		$this->centroscostoBD = new centroscostoBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->usuariosmantBD->usarConexion($conecc);
		$this->FirmasBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);
		$this->rolesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		
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
			case "CAMBIAR CLAVE":
				$this->cambiarclave();
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

					$datos = $_REQUEST;
				
					$datos["TipoCorreo"] = CODIGO_CORREO_USUARIO_NUEVO;
										
					//Asignar Rol
					$datos['rolprivado'] = $datos['rolid']; //Rol del usuario que se esta registrando 
					
					$rolprivado = 0;
					$estado		= 0;

					$datos2["usuarioid"] = $this->seguridad->usuarioid;
				
					//Buscar el rol que tiene asignado el usuario operador 
					$this->tiposusuariosBD->obtenerXRut($datos2,$dt2);	
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					
					if($dt2->leerFila())
					{
						$rolprivado = $dt2->obtenerItem("rolid");
					}

					if( $datos["rolprivado"] == 1 && $rolprivado != 1 )
					{
						$this->mensajeError.= "Su perfil no puede dar permiso para ver rol privado<br>";
					}
					
					$idlogin = 0;
					switch( $datos['idloginExterno'] ){
						case '1' : $idlogin = 0; break;
						case '2' : $idlogin = 1; break;
					}		

					$datos['idloginExterno'] = $idlogin;
		
					$rut_arr 	= explode("-",$datos["newusuarioid"]);
					$rut_sindv 	= $rut_arr[0];		
							
					$datos["clave"] = hash('sha256', $rut_sindv);	
					
					if( $this->mensajeError == '' ){
						
						if ($this->usuariosmantBD->agregarPorEmpresa($datos))
						{	
							$this->mensajeOK = "El usuario fue agregado exitosamente";	
							$this->modificar();
							return;
						}
						// sino guardamos el mensaje de error
						$this->mensajeError=$this->usuariosmantBD->mensajeError;
					}else{
						$datos['rolid'] = 0;
						$formulario[0] = $datos;
					}
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();

					return;
			}
		}
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$datos = $_REQUEST;

		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		
		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		//Tipo de datos 
		$this->tiposusuariosBD->Todos($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulario[0]["tiposusuarios"]=$dt->data;

		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}

		//Tipo de firmas
		$this->FirmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->FirmasBD->mensajeError;
		$formulario[0]["idFirma"] = $dt1->data;

		//Listado de Roles
		$this->rolesBD->listado($dt);
		$this->mensajeError .= $this->rolesBD->mensajeError;
		$formulario[0]["Roles"] = $dt->data;

		//Login Externo
		$formulario[0]["LoginExterno"][0]["idloginExterno"] = 1;
		$formulario[0]["LoginExterno"][0]["Descripcion"] = "No";
		$formulario[0]["LoginExterno"][1]["idloginExterno"] = 2;
		$formulario[0]["LoginExterno"][1]["Descripcion"] = "Si";
		
		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
		$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/usuariosmant_FormularioAgregar.html');

	}

	private function modificar()
	{	
	
		// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
				
					$datos = $_REQUEST; 

					$this->usuariosmantBD->obtener($datos,$dt);
					$this->mensajeError .= $this->usuariosmantBD->mensajeError;
					
					if( $dt->leerFila() ){
						$rol_usuario = $dt->obtenerItem('rolid');
					}
					
					$datos["TipoCorreo"] = CODIGO_CORREO_USUARIO_NUEVO;
					
					//Asignar Rol
					$datos['rolprivado'] = $datos['rolid']; //Rol del usuario que se esta modificando
					
					$rolprivado = 0;
					$estado		= 0;

					$datos2["usuarioid"] = $this->seguridad->usuarioid;
				
					//Buscar el rol que tiene asignado el usuario operador 
					$this->tiposusuariosBD->obtenerXRut($datos2,$dt2);	
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					
					if( $dt2->leerFila() )
					{
						$rolprivado = $dt2->obtenerItem("rolid");
					}
				
					if( $rol_usuario == $datos['rolprivado'] )//Se va a cambiar el rol
					{ 
						if( $this->mensajeError == '' ){
		
							if ($this->usuariosmantBD->modificarPorEmpresa($datos))
							{
								//me fue bien entonces muestro en el listado lo ultimo ingresado
								$this->mensajeOK = "Su usuario ha sido modificado exitosamente";
							}else{
								// si sale todo mal leemos el error
								$this->mensajeError=$this->usuariosmantBD->mensajeError;
							}
						}else{
							// y guardamos los datos enviamos en una variable
							$formulario[0]=$datos;
						}
					}else{
					
						if( $datos["rolprivado"] == 1 && $rolprivado != 1 )
						{
							if( $datos['newusuarioid'] == $this->seguridad->usuarioid ){
								$this->mensajeError.= "Su perfil no puede dar permiso para modificar su rol, favor contacte a un usuario con mayores provilegios";
							}else{
								$this->mensajeError.= "Su perfil no puede dar permiso para ver rol privado<br>";
							}
						}
						if( $this->mensajeError == '' ){
		
							if ($this->usuariosmantBD->modificarPorEmpresa($datos))
							{
								//me fue bien entonces muestro en el listado lo ultimo ingresado
								$this->mensajeOK = "Su usuario ha sido modificado exitosamente";
							}else{
								// si sale todo mal leemos el error
								$this->mensajeError=$this->usuariosmantBD->mensajeError;
							}
						}else{
							// y guardamos los datos enviamos en una variable
							$formulario[0]=$datos;
						}
					}
					
					break;

				case "ELIMINAR":
					// si nos dijeron eliminar eliminamos
					$this->eliminar();
					// y de ahi nos vamos
					return;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
					break;
			}
		}
		
		$datos = $_REQUEST;

		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
	
		// obtenemos los datos a modificar
		$this->usuariosmantBD->obtener($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulario = $dt->data; 
		$datos['empresaid'] = $dt->data[0]['RutEmpresa'];
		$datos['idCentroCosto'] = $dt->data[0]['centrocostoid'];

		$this->centroscostoBD->obtenerEmpresa($datos, $dt);
		$this->mensajeError.=$this->centroscostoBD->mensajeError;
		$formulario[0]['nombrecentrocosto'] = $dt->data[0]['Descripcion'];

		if( $formulario[0]["idFirma"] == 0 ){
			switch (GESTOR_FIRMA) {
				case 'DEC5':
					$formulario[0]["idFirma"] = TIPO_FIRMA_PORDEFECTO_RBK;
					break;
				
				default:
					$formulario[0]["idFirma"] = TIPO_FIRMA_PORDEFECTO_DEC5;
					break;
			}
		}
		
		$idlogin = 0;
		switch( $formulario[0]['idloginExterno'] ){
			case '0' : $idlogin = 1; break;
			case '1' : $idlogin = 2; break;
		}						
		$formulario[0]['idloginExterno']= $idlogin;


		//Tipo de datos 
		$this->tiposusuariosBD->Todos($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulario[0]["tiposusuarios"]=$dt->data;

		//Tipo de firmas
		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}
		
		//Tipo de firmas
		$this->FirmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->FirmasBD->mensajeError;
		$formulario[0]["Firmas"] = $dt1->data;

		//Listado de Roles
		$this->rolesBD->listado($dt);
		$this->mensajeError .= $this->rolesBD->mensajeError;
		$formulario[0]["Roles"] = $dt->data;

		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		/*$this->centroscostoBD->listadoComboEmpresa($datos, $dt3);
		$this->mensajeError.= $this->centroscostoBD->mensajeError;
		$formulario[0]["CentrosCostos"] = $dt3->data;*/

		//Login Externo
		$formulario[0]["LoginExterno"][0]["idloginExterno"] = 1;
		$formulario[0]["LoginExterno"][0]["Descripcion"] = "No";
		$formulario[0]["LoginExterno"][1]["idloginExterno"] = 2;
		$formulario[0]["LoginExterno"][1]["Descripcion"] = "Si";

		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/usuariosmant_FormularioModificar.html');

	}

	private function cambiarclave()
	{	
		if (!isset($_REQUEST["accion2"]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// obtenemos los datos a modificar
			$this->usuariosmantBD->obtener($_REQUEST,$dt);

			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por siaca hubo
			$this->mensajeError=$this->usuariosmantBD->mensajeError;

		}

		// si es que nos enviaron una accion
		if (isset($_REQUEST["accion2"]))
		{
			switch ($_REQUEST["accion2"])
			{
				case "MODIFICAR":
					// si apretaron el boton modificar obtenermos los datos desde el formulario
					$_REQUEST["clave"] = hash('sha256', trim($_REQUEST["clave"]));
					if ($this->usuariosmantBD->CambiarClave($_REQUEST))
					{
						$this->mensajeOK = "Modificaci&oacute;n realizada OK";
						// si sale todo bien mostramos el listado
						$this->listado();
						return;
					}
					// si sale todo mal leemos el error
					$this->mensajeError=$this->usuariosmantBD->mensajeError;
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
		// agregamos pagina actual del listado
		$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
		$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
		$this->pagina->agregarDato("formulario",$campos);
		// agregamos los posibles errores a la pagina
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/usuariosmant_FormularioCambiarClave.html');

	}
	
	private function eliminar()
	{	
		$datos = $_REQUEST;

		// se envia a eliminar a la tabla con los datos del formulario
		if ($this->usuariosmantBD->eliminar($datos)){
			// si elimino entonces mostrar listado sin ning�n filtro, o sea mostrar todo
			$_REQUEST["pagina"]  = 1;
			$_REQUEST["nombrex"] = "";
			$this->mensajeOK = 'El usuario se elimino correctamente.';
		}
		
		$this->mensajeError=$this->usuariosmantBD->mensajeError;
		$this->listado();

	}

	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos=$_REQUEST;

		//Preparamos los datos necesarios para la consulta 
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		//busco el total de paginas
		$this->usuariosmantBD->Total($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"] = round($dt->data[0]["totalreg"]);
		
		$this->usuariosmantBD->Listado($datos,$dt);
		$this->mensajeError.=$this->usuariosmantBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		if ($datos["pagina_ultimo"]==0)
		{
			$this->mensajeOK="No hay informaci�n para la consulta realizada.";
		}else{
			$mensajeNoDatos="";
			$this->pagina->agregarDato("pagina",$datos["pagina"]);
			$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
			$formulario[0]=$datos;
			$formulario[0]["listado"]=$formulariox[0]["listado"];

			//Buscar opciones del usuario 
			$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
			$datos["opcionid"] = 'usuariosmant.php';
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
		}
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/usuariosmant_Listado.html');
	}


	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
