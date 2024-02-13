<?php
error_reporting(E_ERROR); 
$nu_soap_path = 'lib/nusoap.php';
require_once ($nu_soap_path);
	
	
	
	$infovariables 	= array_keys($_SERVER); 	// obtiene los nombres de las variables
	$infovalores 	= array_values($_SERVER);	// obtiene los valores de las variables
	$cantparametros = count($_SERVER);
	for($i=0;$i<$cantparametros;$i++){
		$infoserver.= $infovariables[$i].'='.$infovalores[$i].'|';
	}	
	Graba_Log ("datos server:".$infoserver);

	include_once ("/includes/ObjetoBD.php");
	include_once ("/includes/ContenedorUtilidades.php");
	include_once ("/includes/MensajeUsuario.php");
	include_once ("/includes/DataTable.php");
	include_once ("/includes/usuariosBD.php");
	include_once ("/includes/fichasDatosImportacionBD.php");
	include_once ("/includes/centroscostoBD.php");
	include_once ("/includes/subclausulasBD.php");
	include_once ("/includes/estadocivilBD.php");
	include_once ("/includes/parametrosBD.php");
	include_once ("/includes/EstadosEmpleadosBD.php");
	include_once ("/includes/empleadosBD.php");
	include_once ("/includes/cargoEmpleadoBD.php");
	include_once ("/includes/documentosBD.php");
	include_once ("/includes/lugarespagoBD.php");
	include_once ('generar.php');


	// Create the server instance
	//$server = new soap_server(null, array('encoding'=>'UTF-8'));
	$server = new soap_server();
	
	// Initialize WSDL support
	$server->configureWSDL('Carga de Documento', 'urn:wscargadocumento');
	$server->configureWSDL('Carga Trabajador', 'urn:wscargatrabajador');
	
	$server->wsdl->addComplexType(
            'cd_datosproceso',
            'complexType',
            'struct',
            'all',
            '',
                array(
					'RutEmpresa' => array('name' => 'RutEmpresa	', 'maxOccurs' => '1','type' =>'xsd:string'),
					'FechaCreacion' => array('name' => 'FechaCreacion', 'maxOccurs' => '1','type' =>'xsd:date'),
					'idProceso' => array('name' => 'idProceso', 'maxOccurs' => '1','type' =>'xsd:integer'),
					'idPlantilla' => array('name' => 'idPlantilla', 'maxOccurs' => '1','type' =>'xsd:integer'),
					'pdf64' => array('name' => 'pdf64', 'maxOccurs' => '1','type' =>'xsd:string'),
                )
            );
	
	$server->wsdl->addComplexType(
            'cd_datostrabajador',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'personaid' => array('name' => 'personaid',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nacionalidad' => array('name' => 'nacionalidad',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'fechanacimiento' => array('name' => 'fechanacimiento',  'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'estadocivil' => array('name' => 'estadocivil',  'maxOccurs' =>'1', 'type' =>'xsd:integer'),
					'direccion' => array('name' => 'direccion',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'comuna' => array('name' => 'comuna',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'ciudad' => array('name' => 'ciudad',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'rolid' => array('name' => 'rolid',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'correo' => array('name' => 'correo',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
				)
            );
			
			
	$server->wsdl->addComplexType(
            'cd_datosdocumento',
            'complexType',
            'struct',
            'all',
            '',
                 array(
					'fechadocumento' => array('name' => 'fechadocumento',  'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'codlugarpago' => array('name' => 'codlugarpago', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'codcentrocosto' => array('name' => 'codcentrocosto', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
				)    
			);
			
	$server->wsdl->addComplexType(
            'cd_datosfirmantes',
            'complexType',
            'struct',
            'all',
            '',
				array(
					'firmanteempresa' => array('firmanteempresa','type'=>'tns:firmanteempresa'),
					'firmantenotario' => array('firmantenotario','type'=>'tns:firmantenotario'),
				)


            );
			
			
	$server->wsdl->addComplexType(
            'firmanteempresa',
            'complexType',
            'struct',
            'all',
            '',
                array(
					'rut' => array('name' => 'rut', 'type' =>'xsd:string'),
				)
            );
			
			
	$server->wsdl->addComplexType(
            'firmantenotario',
            'complexType',
            'struct',
            'all',
            '',
                array(
					'rut' => array('name' => 'rut', 'type' =>'xsd:string'),
				)
            );		
			
	$server->wsdl->addComplexType(
            'cd_respuesta',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'codigo' => array('name' => 'codigo', 'type' =>'xsd:string'),
					'mensaje' => array('name' => 'mensaje', 'type' =>'xsd:string'),
					'nrocontrato' => array('name' => 'nrocontrato', 'type' =>'xsd:string')
                )
            );
			
			

		  
	
    $server->register('wscargadocumento',             	// method name
        array('usuario' => 'xsd:string','clave' => 'xsd:string','datosproceso' =>'tns:cd_datosproceso','datostrabajador' =>'tns:cd_datostrabajador','datosdocumento' =>'tns:cd_datosdocumento','datosfirmantes' =>'tns:cd_datosfirmantes'),     	// input parameters
        array('return' =>'tns:cd_respuesta'),    		// output parameters
 		'urn:wscargadocumento',                     // namespace
		'urnwsgettipodocwsdl#wscargadocumento',
        'rpc',                                    	// style
        'encoded',                                	// use
        'Carga de Documentos'						// documentation
      );
	
		  
	$server->wsdl->addComplexType(
            'ct_datostrabajador',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'personaid' => array('name' => 'personaid', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nacionalidad' => array('name' => 'nacionalidad', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'fechanacimiento' => array('name' => 'fechanacimiento', 'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'estadocivil' => array('name' => 'estadocivil', 'maxOccurs' =>'1', 'type' =>'xsd:integer'),
					'direccion' => array('name' => 'direccion', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'comuna' => array('name' => 'comuna', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'ciudad' => array('name' => 'ciudad', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'rolid' => array('name' => 'rolid', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'correo' => array('name' => 'correo', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'rutempresa' => array('name' => 'rutempresa', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'codlugarpago' => array('name' => 'codlugarpago', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'codcentrocosto' => array('name' => 'codlugarpago', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
				)
            );
					
	$server->wsdl->addComplexType(
            'ct_respuesta',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'codigo' => array('name' => 'codigo', 'type' =>'xsd:string'),
					'mensaje' => array('name' => 'mensaje', 'type' =>'xsd:string')
                )
            );
		  
		  
    $server->register('wscargatrabajador',             	// method name
        array('usuario' => 'xsd:string','clave' => 'xsd:string','datostrabajador' =>'tns:ct_datostrabajador'),     	// input parameters
        array('return' =>'tns:ct_respuesta'),    		// output parameters
 		'urn:wscargatrabajador',                     // namespace
		'urnwsgettipodocwsdl#wscargatrabajador',
        'rpc',                                    	// style
        'encoded',                                	// use
        'Carga Trabajador'						// documentation
      );


// Define the method as a PHP function
function  wscargadocumento($usuario,$clave,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes) 
{	
	/*
	$respuesta = array();
	$respuesta = array("codigo" => "000","mensaje" => "SERVICIO EN CONSTRUCCION...","nrocontrato" => "");
	return  $respuesta; 
	*/
	
	$respuesta = array();
	$bd = new ObjetoBD();
	if (!$bd->conectar()) 
	{
		$respuesta = array("codigo" => "999","mensaje" => "Error al conectar a la base de datos","nrocontrato" => "");
		return  $respuesta; 
	}
	
	$mensaje = Valida_Usuario($bd,$usuario,$clave);
	if ($mensaje != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensaje,"nrocontrato" => "");
		return  $respuesta; 
	}
	
	//solo para prueba
	//$respuesta = array("codigo" => "200","mensaje" => "OK","nrocontrato" => "111");
	//return $respuesta;
	//fin solo pruebs
	
	$respuestag = Validar_Datos ($bd,$datosproceso,$datostrabajador,$datosdocumentos);
	
	if ($respuestag["codigo"] != "000")
	{
		$codigo    		= $respuestag["codigo"];
		$mensaje   		= $respuestag["mensaje"];

		$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje,"nrocontrato" => "");
		
		return $respuesta;
	}
	
	
	$respuestag = Cargar_Documento($bd,$usuario,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes);	
	$codigo    = $respuestag["codigo"];
	$mensaje   = $respuestag["mensaje"];
	$nrocontrato   = $respuestag["data"];
	
	$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje,"nrocontrato" => $nrocontrato);
	
	return $respuesta;
}

function Cargar_Documento($bd,$usuario,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes)
{	
	$documentosBD = new documentosBD();
	$conecc = $bd->obtenerConexion();
	$documentosBD->usarConexion($conecc);
	$rutempresa = $datosproceso["RutEmpresa"];
	
	$datosdocumentos["newusuarioid"]	= $datostrabajador["personaid"];
	$datosdocumentos["idCentroCosto"]	= $datosdocumentos["codcentrocosto"];
	$datosdocumentos["LugarPagoid"]		= $datosdocumentos["codlugarpago"];
	
	$datostrabajador["idEstadoCivil"] 	= $datostrabajador["estadocivil"];
		
	
	$datosproceso["usuarioid"] 	= $usuario;
	$datos = $datosproceso;
	$datos = $datos + $datostrabajador;
	$datos = $datos + $datosdocumentos;
	
	$firmanteempresa = $datosfirmantes["firmanteempresa"]["rut"];
	$firmantenotario = $datosfirmantes["firmantenotario"]["rut"];
	
	if ($firmanteempresa != "")
	{
		$datos2["RutFirmante"]  = $firmanteempresa;
		$datos2["RutEmpresa"] 	= $rutempresa;
		$documentosBD->obtenerFirmante($datos2,$dt1);
		$mensajeError = $documentosBD->mensajeError;	
		if ($mensajeError != "")
		{
			$respuesta["codigo"] 	= "400";
			$respuesta["mensaje"] 	= "Error al consultar firmante ".$mensajeError;
			$respuesta["data"] 		= "";
			return $respuesta;
		}
		
		if (!$dt1->leerFila())
		{
			$respuesta["codigo"] 	= "400";
			$respuesta["mensaje"] 	= "Error, firmante de empresa no esta registrado ";
			$respuesta["data"] 		= "";
			return $respuesta;		
		}
		
	}
	
	if ($firmantenotario != "")
	{
		$datos2["RutFirmante"]  = $firmantenotario;
		$datos2["RutEmpresa"] 	= $rutempresa;
		$documentosBD->obtenerFirmante($datos2,$dt2);	
		$mensajeError = $documentosBD->mensajeError;
		if ($mensajeError != "")
		{
			$respuesta["codigo"] 	= "400";
			$respuesta["mensaje"] 	= "Error al consultar firmante ".$mensajeError;
			$respuesta["data"] 		= "";
			return $respuesta;
		}
		
		if (!$dt2->leerFila())
		{
			$respuesta["codigo"] 	= "400";
			$respuesta["mensaje"] 	= "Error, firmante notario no esta registrado ";
			$respuesta["data"] 		= "";
			return $respuesta;		
		}
	}
	
	$idf = 0;
	if ($firmanteempresa != "")
	{
		$datos["Firmantes_Emp"] =  array($idf => $firmanteempresa);
		$idf++;
	}
	
	if ($firmantenotario != "")
	{
		$datos["Firmantes_Emp"] =  array($idf => $firmantenotario);
		$idf++;
	}
	
	//Graba_Log ("usuarioid->".$datos["usuarioid"]);
	
	$generar = new generar();
	$respuesta = $generar->GenerarDocumento($datos);

	return $respuesta;
}

function Valida_Usuario($bd,$usuario,$clave)
{	
	Graba_Log("Usuario : ".$usuario." - Clave:".$clave);

	$parametrosBD = new parametrosBD();
	$conecc = $bd->obtenerConexion();
	
	// si se pudo abrir entonces usamos la conecion en nuestras tablas
	$parametrosBD->usarConexion($conecc);
		
	$datos["idparametro"] = "ws_usuario";
	$parametrosBD->obtener($datos,$dt);

	Graba_Log("Respuesta obtener: ".$dt->data[0]['parametro']);
	
	$mensajeError = $parametrosBD->mensajeError;
	if ($mensajeError != "")
	{
		return $mensajeError;
	}
	
	$salida = "";
	if(!$dt->leerFila())
	{
		return "Configuracion de usuario no existe";
	}
	
	if ($dt->obtenerItem("parametro")!= $usuario)
	{
		return "Usuario o clave no existe";
	}
	
	
	$datos["idparametro"] = "ws_clave";
	$parametrosBD->obtener($datos,$dt);

	$mensajeError = $parametrosBD->mensajeError;
	if ($mensajeError != "")
	{
		return $mensajeError;
	}
	
	$salida = "";
	if(!$dt->leerFila())
	{
		return "Configuracion clave de usuario no existe";
	}
	
	if ($dt->obtenerItem("parametro")!= $clave)
	{
		return "Usuario o clave no existe";
	}
		
}

/// Define the method as a PHP function
function  wscargatrabajador($usuario,$clave,$datostrabajador) {
	
	/*	
	$respuesta = array();
	$respuesta = array("codigo" => "000","mensaje" => "SERVICIO EN CONSTRUCCION...");
	return  $respuesta; 
	*/

	$respuesta = array();
	$bd = new ObjetoBD();
	if (!$bd->conectar()) 
	{
		$respuesta = array("codigo" => "999","mensaje" => "Error al conectar a la base de datos");
		
		Graba_log("Respuesta : ". implode(",",$respuesta));
		
		return  $respuesta; 
	}
	
	
	$mensaje = Valida_Usuario($bd,$usuario,$clave);
	
	if ($mensaje != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensaje);
		
		Graba_log("Respuesta : ". implode(",",$respuesta));
		
		return  $respuesta; 
	}
	
	//solo para prueba
	$respuesta = array("codigo" => "200","mensaje" => "OK");
	return $respuesta;
	//fin solo pruebs
	
	$respuestag = Validar_Datos_Trabajador ($bd,$datostrabajador);	
	
	if ($respuestag["codigo"] != "000")
	{
		$codigo    		= $respuestag["codigo"];
		$mensaje   		= $respuestag["mensaje"];

		$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje);
		
		Graba_log("Respuesta : ". implode(",",$respuesta));
		
		return $respuesta;
	}
	
	if( $datostrabajador["idestado"] == '' ){
		$datostrabajador["idestado"] = 'A'; //Estado Activo por defecto
	}	
	
	/*if ( $datostrabajador["rolid"] == 0 )
	{
		$datos["RolEmpleado"] = 1;//rol privado
	}
	else
	{
		$datos["RolEmpleado"] = 2;//rol general
	}*/
	
	$respuestag = Cargar_Trabajador($bd,$datostrabajador);	
	$codigo    		= $respuestag["codigo"];
	$mensaje   		= $respuestag["mensaje"];
	$nrocontrato   	= $respuestag["data"];
	
	$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje);
	
	Graba_log("Respuesta : ".implode(",",$respuesta));
	
	return $respuesta;
}

function Cargar_Trabajador($bd,$datostrabajador)
{
	$datos["personaid"]		= $datostrabajador["personaid"];	
	$datos["nacionalidad"]	= utf8_encode($datostrabajador["nacionalidad"]);
	$datos["nombre"]		= utf8_encode($datostrabajador["nombre"]);	
	$datos["appaterno"]		= utf8_encode($datostrabajador["apellidopat"]);	
	$datos["apmaterno"]		= utf8_encode($datostrabajador["apellidomat"]);
	$datos["correo"] 		= $datostrabajador["correo"];
	$datos["direccion"]		= utf8_encode($datostrabajador["direccion"]);
	$datos["ciudad"]		= utf8_encode($datostrabajador["ciudad"]);
	$datos["comuna"]		= utf8_encode($datostrabajador["comuna"]);
	$datos["fechanacimiento"]	= utf8_encode($datostrabajador["fechanacimiento"]);
	$datos["estadocivil"]	= utf8_encode($datostrabajador["estadocivil"]);
	

	
	//esto si es que confirman que el rol es un código alfanúmerico
	$rolsmu = 0;
	$rolrbk = 0;
	//$rolsmu = $datos["rolid"];
	$rolsmu = $datostrabajador["rolid"];
	$rolrbk = Traduce_Rol($rolsmu);
	if ($rolrbk == 0)
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rol no registrado (".$datos["rolid"].")" );
		return $respuesta;
	}
	$datos['rolid']         =  $rolrbk;
	
	if( $datostrabajador["idestado"] == '' ){
		$datos["idestado"] = 'A'; //Estado Activo por defecto
	}	
	
	/*if ( $datostrabajador["rolid"] == 0 )
	{
		$datos["rolid"] = 1;//rol privado
	}
	else
	{
		$datos["rolid"] = 2;//rol general
	}*/
		
	$empleadosBD = new empleadosBD();
	$conecc = $bd->obtenerConexion();
	$empleadosBD->usarConexion($conecc);
	
	$empleadosBD->agregar($datos,$dt);
	$mensajeError = $empleadosBD->mensajeError;
	
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}
	
	$respuesta = array("codigo" => "000","mensaje" => "OK");
	
	return $respuesta;
}

function Validar_Datos_Trabajador($bd,$datostrabajador)
{
	if (!ContenedorUtilidades::validarRut2($datostrabajador["rut"])) 
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rut no valido (rut,".$datostrabajador["rut"].")");
		return $respuesta;
	}	
		
	$datos["nombre"]			= $datostrabajador["nombre"];	
	$datos["apellidopat"]		= $datostrabajador["apellidopat"];	
	$datos["apellidomat"]		= $datostrabajador["apellidomat"];
	$datos["nacionalidad"]		= $datostrabajador["nacionalidad"];
	$datos["estadocivil"]		= $datostrabajador["estadocivil"];
	$datos["direccion"]			= $datostrabajador["direccion"];
	$datos["comuna"]			= $datostrabajador["comuna"];
	$datos["ciudad"]			= $datostrabajador["ciudad"];

	//esto si es que confirman que el rol es un código alfanúmerico
	
	$rolsmu = 0;
	$rolrbk = 0;

	//$rolsmu = $datostrabajador["rolid"];
	//$rolrbk = Traduce_Rol($rolsmu);
	$rolrbk = $datostrabajador["rolid"];
	
	if ($rolrbk != 1 && $rolrbk != 2)
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rol no registrado (".$datos["rolid"].")" );
		return $respuesta;
	}
	$datos["rolid"]				=  $rolrbk; 
	//$datos["correo"] 			= $datostrabajador["correo"];

	if( $datostrabajador["idestado"] == '' ){
		$datos["idestado"] = 'A'; //Estado Activo por defecto
	}else{
		$datos["idestado"] = $datostrabajador["idestado"];
	}
	
	$resultado = ValidaVacio ($datos);
	
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}	
	
	$datos2["fechanacimiento"] = $datostrabajador["fechanacimiento"];
	$resultado = ValidaFecha ($datos2);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	//valida lugar de pago
	$lugarespagoBD = new lugarespagoBD();
	$conecc = $bd->obtenerConexion();
	$lugarespagoBD->usarConexion($conecc);
		
	$datos["idCentroCosto"] = $datostrabajador["codlugarpago"];
	$lugarespagoBD->obtener($datos,$dt);
	$mensajeError = $lugarespagoBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Codigo lugar d epago no existe (".$datos["idCentroCosto"].")" );
		return $respuesta;
	}
	//fin 
	
	//valida centro costo
	$centroscostoBD = new centroscostoBD();
	$conecc = $bd->obtenerConexion();
	$centroscostoBD->usarConexion($conecc);
		
	$datos["idCentroCosto"] = $datostrabajador["coddivpersonal"];
	$centroscostoBD->obtener($datos,$dt);
	$mensajeError = $centroscostoBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Codigo division personal no existe (".$datos["idCentroCosto"].")" );
		return $respuesta;
	}
	//fin 
	
	//valida codigo estado civil
	$estadocivilBD = new estadocivilBD();
	$conecc = $bd->obtenerConexion();
	$estadocivilBD->usarConexion($conecc);
		
	$datos["idEstadoCivil"] 	= $datos["estadocivil"];;
	$estadocivilBD->obtener($datos,$dt);
	$mensajeError = $estadocivilBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Estado Civil no registrado (".$datos["idEstadoCivil"].")" );
		return $respuesta;
	}
	//fin
		
	$respuesta = array("codigo" => "000","mensaje" => "OK");
	
	return $respuesta;
}

function Validar_Datos($bd,$datosproceso,$datostrabajador,$datosdocumentos)
{
	if (!ContenedorUtilidades::validarRut2($datostrabajador["personaid"])) 
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rut no valido (rut,".$datostrabajador["rut"].")");
		return $respuesta;
	}
	
	$datos["rut"] 				= $datostrabajador["personaid"];
	$datos["nombre"] 			= $datostrabajador["nombre"];
	$datos["nacionalidad"] 		= $datostrabajador["nacionalidad"];
	$datos["fechanacimiento"] 	= $datostrabajador["fechanacimiento"];
	$datos["estadocivil"] 		= $datostrabajador["estadocivil"];
	$datos["direccion"] 		= $datostrabajador["direccion"];
	$datos["comuna"] 			= $datostrabajador["comuna"];
	$datos["ciudad"] 			= $datostrabajador["ciudad"];
	$datos["rolid"] 			= $datostrabajador["rolid"];
	$datos["codlugarpago"] 		= $datosdocumentos["codlugarpago"];
	$datos["codcentrocosto"]	= $datosdocumentos["codcentrocosto"];
	$datos["fechadocumento"]	= $datosdocumentos["fechadocumento"];
	$datos["RutEmpresa"]		= $datosproceso["RutEmpresa"];
	$datos["FechaCreacion"]		= $datosproceso["FechaCreacion"];
	$datos["idProceso"]			= $datosproceso["idProceso"];
	$datos["idPlantilla"]		= $datosproceso["idPlantilla"];
	$datos["pdf64"]				= $datosproceso["pdf64"];
				
	$resultado = ValidaVacio ($datos);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	$datos2["fechanacimiento"] 		= $datostrabajador["fechanacimiento"];
	$datos2["fechadocumento"]		= $datosdocumentos["fechadocumento"];
	$datos2["FechaCreacion"]		= $datosdocumentos["FechaCreacion"];
	
	$resultado = ValidaFecha ($datos2);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	

	//$datos3["SueldoBase"]				= $datosdocumentos["SueldoBase"];
	
	$resultado = ValidaNumero ($datos3);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	//valida lugar de pago
	$lugarespagoBD = new lugarespagoBD();
	$conecc = $bd->obtenerConexion();
	$lugarespagoBD->usarConexion($conecc);
		
	$datos["empresaid"] 	= $datos["RutEmpresa"];
	$datos["lugarpagoid"] 	= $datos["codlugarpago"];
	$lugarespagoBD->obtener($datos,$dt);

	
	$mensajeError = $lugarespagoBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Empresa lugar de pago no existe (".$datos["empresaid"]." ".$datos["lugarpagoid"].")" );
		return $respuesta;
	}
	//fin 
	
	//valida centro costo
	$centroscostoBD = new centroscostoBD();
	$conecc = $bd->obtenerConexion();
	$centroscostoBD->usarConexion($conecc);
	
	$datos["lugarpagoid"] = $datos["codlugarpago"];
	$datos["idCentroCosto"] = $datos["codcentrocosto"];
	$centroscostoBD->obtenerPorLugarPago($datos,$dt);
	
	$mensajeError = $centroscostoBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Lugar de pago entro de costo no existe (".$datos["lugarpagoid"]." ".$datos["codcentrocosto"].")" );
		return $respuesta;
	}
	//fin 
	
			
	$respuesta = array("codigo" => "000","mensaje" => "OK","nrocontrato" => "");
	
	return $respuesta;
}

function ValidaVacio($datos)
{
	foreach ($datos as $key => $valor)
	{
		if (trim($datos[$key]) == "")
		{
			return "Valor no debe venir vacio (".$key.")";
		}
	}
	
	return "";
}

function ValidaFecha($datos)
{
	foreach ($datos as $key => $valor)
	{
		if (trim($datos[$key]) != "")
		{
			if (!ContenedorUtilidades::validarFecha($datos[$key]))
			{
				return "Valor fecha no valida "."(".$key.",".$datos[$key].")";
			}
		}
	}
	
	return "";
}

function ValidaNumero($datos)
{
	foreach ($datos as $key => $valor)
	{
		$datos[$key] = str_replace(",",".",$datos[$key]);
		if (!is_numeric($datos[$key]))
		{
			return "Valor no es numerico "."(".$key.",".$datos[$key].")";
		}

	}
	
	return "";
}

function Traduce_Rol($rolsmu)
{   
	$rol = 0;
	if ($rolsmu == 'C0' || $rolsmu == 'C1')
	{
		$rol = 1;//privado
	}
	else
	//if ($rolsmu == 'CC' || $rolsmu == 'C2' || $rolsmu == 'C3'|| $rolsmu == 'C4' || $rolsmu == 'C5' || $rolsmu == 'C6')
	{
		$rol = 2;//publico
	}
	
	return $rol;
}

function Graba_Log($log)
{
	date_default_timezone_set('America/Santiago');
	$nomarchivo = 'logs/logws_'.@date("Ymd").'.TXT';
	$ar=fopen($nomarchivo,"a") or
	   die("Problemas en la creacion");
	if (trim($log) != "")
	{
	fputs($ar,@date("H:i:s")." ".$log);
	}
	else
	{
		fputs($ar," ");
	}
	fputs($ar,"\n");
	fclose($ar);		
}


function arrayToXml($array, $rootElement = null, $xml = null) { 
    $_xml = $xml; 
      
    // If there is no Root Element then insert root 
    if ($_xml === null) { 
        $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>'); 
    } 
      
    // Visit all key value pair 
    foreach ($array as $k => $v) { 
          
        // If there is nested array then 
        if (is_array($v)) {  
              
            // Call function for nested array 
            arrayToXml($v, $k, $_xml->addChild($k)); 
            } 
              
        else { 
              
            // Simply add child element.  
            $_xml->addChild($k, $v); 
        } 
    } 
      
    return $_xml->asXML(); 
} 

// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$request = $HTTP_RAW_POST_DATA;
Graba_Log($request);
Graba_Log("");
Graba_Log("");
$server->service($HTTP_RAW_POST_DATA);
?>