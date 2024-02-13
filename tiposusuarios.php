<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/tiposusuariosBD.php");
include_once("includes/holdingBD.php");
include_once("includes/opcionessistemaBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
// creamos la instacia de esta clase
$page = new usuarios();


class usuarios {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $tiposusuariosBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $mensajeError2="";
	
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
		
		//print_r($_POST);
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "Perfiles ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "Mantenedores";
		$this->opcionnivel2 = "<li>Perfiles</li>";


		// instanciamos del manejo de tablas
		$this->tiposusuariosBD   = new tiposusuariosBD();
		$this->opcionessistemaBD = new opcionessistemaBD();
		$this->holdingBD         = new holdingBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->opcionessistemaBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
		//print_r ($_POST);
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
			case "BUSCAR":
				$this->listado();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function agregar()
	{
		$dt = new DataTable();
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "AGREGAR":
					//print_r($_POST);
					// enviamos los datos del formulario a guardar
					$datos = $_POST;
					
					if (isset($datos["rolprivado"])){
						$datos["rolprivado"]=1; //  lo dejamos en 1, que corresponde si puede ver rol privado
					}else{
						$datos["rolprivado"]=0;
					}	
					
					if (isset($datos["estado"])){
						$datos["estado"]=1; // lo dejamos en 1, que corresponde si puede ver finiquitados
					}else{
						$datos["estado"]=0;
					}	
					
					if (isset($datos["renotificar"])){
						$datos["renotificar"]=1; //  lo dejamos en 1, que corresponde si puede renotificar documentos
					}else{
						$datos["renotificar"]=0;
					}	

					//csb 08-03-2018 rescatamos datos del perfil que esta logueado para ver si puede dar permisos
					$rolprivado = 0;
					$estado		= 0;
					$renotificar	= 0;
					$datos2["tipousuarioid"] = $this->seguridad->tipousuarioid;
					$datos["tipousuarioid"]  = $this->seguridad->tipousuarioid;
					$this->tiposusuariosBD->obtener($datos2,$dt2);	
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					if($dt2->leerFila())
					{
						$rolprivado = $dt2->obtenerItem("rolprivado");
						$estado 	= $dt2->obtenerItem("estado");
						$renotificar = $dt2->obtenerItem("renotificar");
					}
					
					if ($datos["rolprivado"] == 1 && $rolprivado == 0)
					{
						$datos["rolprivado"] = 0;
						$this->mensajeError2.= "Su perfil no puede dar permiso para ver rol privado<br>";
					}
					
					if ($datos["estado"] == 1 && $estado == 0)
					{
						$datos["estado"] = 0;
						$this->mensajeError2.= "Su perfil no puede dar permiso para ver finiquitados<br>";
					}

					if ($datos["renotificar"] == 1 && $renotificar == 0)
					{
						$datos["renotificar"] = 0;
						$this->mensajeError2.= "Su perfil no puede dar permiso para renotificar documentos<br>";
					}
					// fin
					
					if ($this->tiposusuariosBD->agregar($datos))
					{ 	
						$cantfilas = $_POST["cantfilas"];
						for ($filas=0;$filas<$cantfilas;$filas++)
						{	
							$consulta=0;$modifica=0;$crea=0;$elimina=0;$opcion=0;
							if (isset($_POST["consulta".$filas])){
								$consulta=1;
								$opcion=$_POST["consulta".$filas];
							}
							if (isset($_POST["modifica".$filas])){
								$modifica=1;
								$opcion=$_POST["modifica".$filas];
							}
							if (isset($_POST["crea".$filas])){
								$crea=1;
								$opcion=$_POST["crea".$filas];
							}
							if (isset($_POST["elimina".$filas])){
								$elimina=1;
								$opcion=$_POST["elimina".$filas];
							}
							if (isset($_POST["ver".$filas])){
								$ver=1;
								$opcion=$_POST["ver".$filas];
							}		
							
							//csb 08-03-2018 rescatamos opciones del perfil que esta logueado para ver si puede dar permisos
							$usulog_consulta 	= 0;
							$usulog_modifica 	= 0;
							$usulog_crea 		= 0;
							$usulog_elimina 	= 0;
							$usulog_ver  		= 0;
							
							$datos2["opcionid"] = $opcion;
							$this->opcionesxtipousuarioBD->Obtener($datos2,$dt3);	
							$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
							if($dt3->leerFila())
							{
								$usulog_opcion 		= $datos2["opcionid"];
								$usulog_consulta  	= $dt3->obtenerItem("consulta");
								$usulog_modifica  	= $dt3->obtenerItem("modifica");
								$usulog_crea  		= $dt3->obtenerItem("crea");
								$usulog_elimina  	= $dt3->obtenerItem("elimina");
								$usulog_ver  		= $dt3->obtenerItem("ver");
							}
							
							$opcionfila = $fila + 1; 
							
							if ($consulta == 1 && $usulog_consulta == 0)
							{
								$consulta = 0;
								$this->mensajeError2.= "Su perfil no puede dar permiso para consulta en opci&oacute;n ".$opcionfila."<br>"	;							
							}
							
							if ($modifica == 1 && $usulog_modifica == 0)
							{
								$modifica = 0;
								$this->mensajeError2.= "Su perfil no puede dar permiso para modificar en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($crea == 1 && $usulog_crea == 0)
							{
								$crea = 0;
								$this->mensajeError2.= "Su perfil no puede dar permiso para crear en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($elimina == 1 && $usulog_elimina == 0)
							{
								$elimina = 0;
								$this->mensajeError2.= "Su perfil no puede dar permiso para eliminar en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($ver == 1 && $usulog_ver == 0)
							{
								$ver = 0;
								$this->mensajeError2.= "Su perfil no puede dar permiso para ver documentos en opci&oacute;n ".$opcionfila."<br>";
							}
														
							if ($consulta == 0 && $modifica == 0 &&  $crea == 0 && $elimina == 0 && $ver == 0)
							{
								//$opcion = 0;
								$opcion = "";
							}
							// fin
							
							//if ($opcion > 0)
							if ($opcion != "")
							{	
								$datosopciones["holdingid"]=$_POST["holdingid"];
								$datosopciones["tipousuarioid"]=$datos2["tipousuarioid"];
								$datosopciones["nombre"]=$_POST["nombre"];
								$datosopciones["opcionid"]=$opcion;
								$datosopciones["consulta"]=$consulta;
								$datosopciones["modifica"]=$modifica;
								$datosopciones["crea"]    =$crea;
								$datosopciones["elimina"] =$elimina;
								$datosopciones["ver"] =$ver;
								$this->tiposusuariosBD->agregar_opciones($datosopciones);
								$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
							}
						}
						
						if (trim($this->mensajeError) == "")
						{
							// si resulta mostramos el listado
							$this->tiposusuariosBD->obtenerxnombre($_POST,$dt);
							if($dt->leerFila()){
								$_POST["tipousuarioid"] = $dt->obtenerItem("tipousuarioid");
								if ($this->mensajeError2 == "")
								{
									$this->mensajeOK = "Informaci&oacute;n Grabada OK. <br> Ahora puede conceder los permisos en bot&oacute;n Ir a documentos por perfil e Ir a permisos por perfil";
								}
								else
								{
									$this->mensajeError = $this->mensajeError2;
									$this->mensajeOK = "Informaci&oacute;n Grabada <br> Ahora puede conceder los permisos en bot&oacute;n Ir a documentos por perfil e Ir a permisos por perfil";
								}
							}
							$this->modificar();
							return;
						}
					}
					
					
					// sino guardamos el mensaje de error
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					break;

				case "VOLVER":
					// mostramos el listado
					$this->listado();

					return;
			}
		}
		// recuperamos lo que se escribio en el formulario que va llegando si es que hubo
		$campos[0]=$_POST;

		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;
		$this->opcionessistemaBD->ListadoXperfil($datos,$dt);
		$this->mensajeError.=$this->opcionessistemaBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		
		$cantfilas = count($dt->data);
		$this->pagina->agregarDato("cantfilas",$cantfilas);
		$this->pagina->agregarDato("pagina",$_POST["pagina"]);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/tiposusuarios_FormularioAgregar.html');

	}

	private function modificar()
	{	//print_r($_POST);
		if (!isset($_POST["accion2"]))
		{
			// creamos un contenedor de la tabla
			$dt = new DataTable();
			// obtenemos los datos a modificar
			$this->tiposusuariosBD->obtener($_POST,$dt);

			// guardamos los datos en un arreglo
			$campos=$dt->data;
			// guardamos el error por siaca hubo
			$this->mensajeError=$this->tiposusuariosBD->mensajeError;

		}

		// si es que nos enviaron una accion
		if (isset($_POST["accion2"]))
		{
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":
					$datos = $_POST;
					if (isset($datos["rolprivado"])){
						$datos["rolprivado"]=1; // lo dejamos en 1, que corresponde si puede ver rol privado
					}else{
						$datos["rolprivado"]=0;
					}
					
					if (isset($datos["estado"])){
						$datos["estado"]=1; // lo dejamos en 1, que corresponde si puede ver finiquitados
					}else{
						$datos["estado"]=0;
					}	
	
					if (isset($datos["renotificar"])){
						$datos["renotificar"]=1; // lo dejamos en 1, que corresponde si puede renotificar documentos
					}else{
						$datos["renotificar"]=0;
					}
					//csb 08-03-2018 rescatamos datos del perfil que esta logueado para ver si puede dar permisos
					$rolprivado = 0;
					$estado		= 0;
					$renotificar	= 0;
					$datos2["tipousuarioid"] = $this->seguridad->tipousuarioid;
					$this->tiposusuariosBD->obtener($datos2,$dt2);	
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					if($dt2->leerFila())
					{
						$rolprivado = $dt2->obtenerItem("rolprivado");
						$estado 	= $dt2->obtenerItem("estado");
						$renotificar = $dt2->obtenerItem("renotificar");
					}
					
					if ($datos["rolprivado"] == 1 && $rolprivado == 0)
					{
						$datos["rolprivado"] = 0;
						$this->mensajeError.= "Su perfil no puede dar permiso para ver rol privado<br>";
					}
					
					if ($datos["estado"] == 1 && $estado == 0)
					{
						$datos["estado"] = 0;
						$this->mensajeError.= "Su perfil no puede dar permiso para ver finiquitados<br>";
					}
					
					if ($datos["renotificar"] == 1 && $renotificar == 0)
					{
						$datos["renotificar"] = 0;
						$this->mensajeError.= "Su perfil no puede dar permiso para renotificar documentos<br>";
					}
					// fin
					
					if ($this->tiposusuariosBD->modificar($datos))
					{
						$cantfilas = $_POST["cantfilas"];
						for ($filas=0;$filas<$cantfilas;$filas++)
						{	
							$consulta=0;$modifica=0;$crea=0;$elimina=0;$opcion=0;$ver=0;
							if (isset($_POST["consulta".$filas])){
								$opcion=$_POST["consulta".$filas];
								$consulta=1;
							}
							if (isset($_POST["modifica".$filas])){
								$opcion=$_POST["modifica".$filas];
								$modifica=1;
							}
							if (isset($_POST["crea".$filas])){
								$opcion=$_POST["crea".$filas];
								$crea=1;
							}
							if (isset($_POST["elimina".$filas])){
								$opcion=$_POST["elimina".$filas];
								$elimina=1;
							}
							if (isset($_POST["ver".$filas])){
								$ver=1;
								$opcion=$_POST["ver".$filas];
							}
							
							//csb 08-03-2018 rescatamos opciones del perfil que esta logueado para ver si puede dar permisos
							$usulog_consulta 	= 0;
							$usulog_modifica 	= 0;
							$usulog_crea 		= 0;
							$usulog_elimina 	= 0;
							$usulog_ver  		= 0;
							
							$datos2["opcionid"] = $opcion;
							$this->opcionesxtipousuarioBD->Obtener($datos2,$dt3);	
							$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
							if($dt3->leerFila())
							{
								$usulog_opcion 		= $datos2["opcionid"];
								$usulog_consulta  	= $dt3->obtenerItem("consulta");
								$usulog_modifica  	= $dt3->obtenerItem("modifica");
								$usulog_crea  		= $dt3->obtenerItem("crea");
								$usulog_elimina  	= $dt3->obtenerItem("elimina");
								$usulog_ver  		= $dt3->obtenerItem("ver");
							}
							
							$opcionfila = $fila + 1;
							
							if ($consulta == 1 && $usulog_consulta == 0)
							{
								$consulta = 0;
								$this->mensajeError.= "Su perfil no puede dar permiso para consulta en opci&oacute;n ".$opcionfila."<br>"	;							
							}
							
							if ($modifica == 1 && $usulog_modifica == 0)
							{
								$modifica = 0;
								$this->mensajeError.= "Su perfil no puede dar permiso para modificar en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($crea == 1 && $usulog_crea == 0)
							{
								$crea = 0;
								$this->mensajeError.= "Su perfil no puede dar permiso para crear en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($elimina == 1 && $usulog_elimina == 0)
							{
								$elimina = 0;
								$this->mensajeError.= "Su perfil no puede dar permiso para eliminar en opci&oacute;n ".$opcionfila."<br>";
							}
							
							if ($ver == 1 && $usulog_ver == 0)
							{
								$ver = 0;
								$this->mensajeError.= "Su perfil no puede dar permiso para ver documentos en opci&oacute;n ".$opcionfila."<br>";
							}
														
							if ($consulta == 0 && $modifica == 0 &&  $crea == 0 && $elimina == 0 && $ver == 0)
							{
								$opcion = 0;
							}
							// fin
							
							$datosopciones["holdingid"]		=$_POST["holdingid"];
							$datosopciones["tipousuarioid"]	=$_POST["tipousuarioid"];
							$datosopciones["opcionid"]		=$_POST["opcion".$filas];
							$datosopciones["consulta"]		=$consulta;
							$datosopciones["modifica"]		=$modifica;
							$datosopciones["crea"]    		=$crea;
							$datosopciones["elimina"] 		=$elimina;
							$datosopciones["ver"] 			=$ver;
							$this->tiposusuariosBD->modificar_opciones($datosopciones);
							$this->mensajeError.=$this->tiposusuariosBD->mensajeError;

						}
						// si sale todo bien mostramos el listado
						
						if ($this->mensajeError == '')
						{
							$this->mensajeOK.="Grabado Exitosamente";
							$this->listado();
							return;
						}
					}
					// si sale todo mal leemos el error
					$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
					// y guardamos los datos enviamos en una variable
					$campos[0]=$_POST;
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
	
		$this->tiposusuariosBD->obtener($_POST,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulario[0]["listado"]=$dt->data;
		
		$_POST["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		$this->tiposusuariosBD->obtener_opciones($_POST,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulario[0]["listado2"]=$dt->data;		
		
		// agregamos pagina actual del listado
		$cantfilas = count($dt->data);
		$this->pagina->agregarDato("cantfilas",$cantfilas);
		$this->pagina->agregarDato("pagina",$_POST["pagina"]);
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos los posibles errores a la pagina
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				
		// imprimimos los datos en el template
		$this->pagina->imprimirTemplate('templates/tiposusuarios_FormularioModificar.html');

	}

	private function eliminar()
	{
		// se envia a eliminar a la tabla con los datos del formulario
		$this->tiposusuariosBD->eliminar($_POST);
		// si es que hubiera error lo obtenemos
		$this->mensajeError=$this->tiposusuariosBD->mensajeError;
		if ($this->mensajeError == '')
		{
			$this->mensajeOK = 'El perfil ha sido eliminado con exito';
		}
		// mostramos el listado
		$this->listado();

	}

	private function listado()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_POST;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		$datos["decuantos"]="10";

		//busco el total de paginas
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
	
		$this->tiposusuariosBD->Listado($datos,$dt);
		$this->mensajeError.=$this->tiposusuariosBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;
	
		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'tiposusuarios.php';
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

		// agregamos los datos de mensaje
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/tiposusuarios_Listado.html');
	}


	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


}
?>
