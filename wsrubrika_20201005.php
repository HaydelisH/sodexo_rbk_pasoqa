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


	// Create the server instance
	//$server = new soap_server(null, array('encoding'=>'UTF-8'));
	$server = new soap_server();
	
	// Initialize WSDL support
	$server->configureWSDL('Carga de Documento', 'urn:wscargadocumento');
	$server->configureWSDL('Carga Trabajador', 'urn:wscargatrabajador');
	
	$server->wsdl->addComplexType(
            'cd_datostrabajador',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'rut' => array('name' => 'rut',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'apellidopat' => array('name' => 'apellidopat',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'apellidomat' => array('name' => 'apellidomat',  'maxOccurs' =>'1', 'type' =>'xsd:string'),
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
					'CodDivPersonal' => array('name' => 'CodDivPersonal', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'CodCargo' => array('name' => 'CodCargo', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'ClaseContrato' => array('name' => 'ClaseContrato', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'TipoMovimiento' => array('name' => 'TipoMovimiento', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'CodigoJornada' => array('name' => 'CodigoJornada', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'EstadoEmpleado' => array('name' => 'EstadoEmpleado', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'AsignacionMovilizacion' => array('name' => 'AsignacionMovilizacion', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'Posicion' => array('name' => 'Posicion', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'AsignacionPerdidaCajaValor' => array('name' => 'AsignacionPerdidaCajaValor', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'AsignacionPerdidaCajaPorcentaje' => array('name' => 'AsignacionPerdidaCajaPorcentaje', 'maxOccurs' =>'1', 'type' =>'xsd:string'),	
					'AsignacionCajaFija' => array('name' => 'AsignacionCajaFija', 'maxOccurs' =>'1', 'type' =>'xsd:string'),	
					'AsignacionColacion' => array('name' => 'AsignacionColacion', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'DescCargoRiohs' => array('name' => 'DescCargoRiohs', 'maxOccurs' =>'1', 'type' =>'xsd:string'),		
					'SueldoBase' => array('name' => 'SueldoBase', 'maxOccurs' =>'1', 'type' =>'xsd:string'),	
					'FechaInicioContrato' => array('name' => 'FechaInicioContrato', 'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'FechaTermino1' => array('name' => 'FechaTermino1', 'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'FechaTermino2' => array('name' => 'FechaTermino2', 'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'AreaTrabajo' => array('name' => 'AreaTrabajo', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
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
        array('usuario' => 'xsd:string','clave' => 'xsd:string','datostrabajador' =>'tns:cd_datostrabajador','datosdocumento' =>'tns:cd_datosdocumento'),     	// input parameters
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
					'rut' => array('name' => 'rut', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'apellidopat' => array('name' => 'apellidopat', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'apellidomat' => array('name' => 'apellidomat', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'nacionalidad' => array('name' => 'nacionalidad', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'fechanacimiento' => array('name' => 'fechanacimiento', 'maxOccurs' =>'1', 'type' =>'xsd:date'),
					'estadocivil' => array('name' => 'estadocivil', 'maxOccurs' =>'1', 'type' =>'xsd:integer'),
					'direccion' => array('name' => 'direccion', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'comuna' => array('name' => 'comuna', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'ciudad' => array('name' => 'ciudad', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'rolid' => array('name' => 'rolid', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'correo' => array('name' => 'correo', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
					'coddivpersonal' => array('name' => 'CodDivPersonal', 'maxOccurs' =>'1', 'type' =>'xsd:string'),
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
function  wscargadocumento($usuario,$clave,$datostrabajador,$datosdocumentos) {
	
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
	
	
	$respuestag = Validar_Datos ($bd,$datostrabajador,$datosdocumentos);
	
	if ($respuestag["codigo"] != "000")
	{
		$codigo    		= $respuestag["codigo"];
		$mensaje   		= $respuestag["mensaje"];

		$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje,"nrocontrato" => "");
		
		return $respuesta;
	}
	
	$respuestag = Cargar_Documento($bd,$datostrabajador,$datosdocumentos);	
	$codigo    		= $respuestag["codigo"];
	$mensaje   		= $respuestag["mensaje"];
	$nrocontrato   	= $respuestag["data"];
	
	$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje,"nrocontrato" => $nrocontrato);
	
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

function Cargar_Documento($bd,$datostrabajador,$datosdocumentos)
{
	$fichasDatosImportacionBD = new fichasDatosImportacionBD();
	$conecc = $bd->obtenerConexion();
	$fichasDatosImportacionBD->usarConexion($conecc);

	$datos["RutTrabajador"]		= $datostrabajador["rut"];
	$datos["NombreTrabajador"]	= utf8_encode($datostrabajador["nombre"]);
	$datos["apellidopat"]		= utf8_encode($datostrabajador["apellidopat"]);
	$datos["apellidomat"]		= utf8_encode($datostrabajador["apellidomat"]);	
	$datos["FechaNacimiento"]	= utf8_encode($datostrabajador["fechanacimiento"]);
	$datos["Nacionalidad"]		= utf8_encode($datostrabajador["nacionalidad"]);
	$datos["EstadoCivil"]		= utf8_encode($datostrabajador["estadocivil"]);
	$datos["Direccion"]			= utf8_encode($datostrabajador["direccion"]);
	$datos["Comuna"]			= utf8_encode($datostrabajador["comuna"]);
	$datos["CiudadTrabajador"]	= utf8_encode($datostrabajador["ciudad"]);
	$datos["CorreoElectronicoEmpleado"] = $datostrabajador["correo"];
	
	//esto si es que confirman que el rol es un código alfanúmerico
	$rolsmu = 0;
	$rolrbk = 0;
	$rolsmu = $datostrabajador["rolid"];
	$rolrbk = Traduce_Rol($rolsmu); 
	
	if ($rolrbk == 0)
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rol no registrado (".$datos["rolid"].")" );
		return $respuesta;
	}
	$datos["RolEmpleado"] = $rolrbk;
	
	/*if ( $datostrabajador["rolid"] == 0 )
	{
		$datos["RolEmpleado"] = 1;//rol privado
	}
	else
	{
		$datos["RolEmpleado"] = 2;//rol general
	}*/
			
	$datos["RutEmpresa"]= $datosdocumentos["RutEmpresa"];
	$datos["CodDivPersonal"]= $datosdocumentos["CodDivPersonal"];
	$datos["CodCargo"]= $datosdocumentos["CodCargo"];
	$datos["ClaseContrato"]= $datosdocumentos["ClaseContrato"];
	$datos["TipoMovimiento"]= $datosdocumentos["TipoMovimiento"];
	$datos["CodJornada"]= $datosdocumentos["CodigoJornada"];
	$datos["EstadoEmpleado"]= $datosdocumentos["EstadoEmpleado"];
	$datos["AsignacionMovilizacion"]= $datosdocumentos["AsignacionMovilizacion"];
	$datos["Posicion"]= $datosdocumentos["Posicion"];
	$datos["AsignacionPerdidaCajaValor"]		= $datosdocumentos["AsignacionPerdidaCajaValor"];
	$datos["AsignacionPerdidaCajaPorcentaje"]	= $datosdocumentos["AsignacionPerdidaCajaPorcentaje"];
	$datos["CiudadFirma"]= $datosdocumentos["CiudadFirma"];
	$datos["AsignacionColacion"]= $datosdocumentos["AsignacionColacion"];
	$datos["DescCargoRIOHS"]= $datosdocumentos["DescCargoRiohs"];
	$datos["SueldoBase"]= $datosdocumentos["SueldoBase"];
	$datos["FechaTermino1"]= $datosdocumentos["FechaTermino1"];
	$datos["FechaTermino2"]= $datosdocumentos["FechaTermino2"];
	$datos["AreaTrabajo"]=$datosdocumentos["AreaTrabajo"];
	$datos["FechaInicioContrato"]= $datosdocumentos["FechaInicioContrato"];
	$datos["AsignacionCajaFija"]= $datosdocumentos["AsignacionCajaFija"];
	
	
	$fichasDatosImportacionBD->agregar($datos);
	$mensajeError = $fichasDatosImportacionBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError,"nrocontrato" => "");
		return $respuesta;
	}
	
	$respuesta = array("codigo" => "000","mensaje" => "OK","nrocontrato" => "");
	
	return $respuesta;
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
	$datos["personaid"]		= $datostrabajador["rut"];	
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

	$rolsmu = $datostrabajador["rolid"];
	$rolrbk = Traduce_Rol($rolsmu);
	
	if ($rolrbk == 0)
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

function Validar_Datos($bd,$datostrabajador,$datosdocumentos)
{
	if (!ContenedorUtilidades::validarRut2($datostrabajador["rut"])) 
	{
		$respuesta = array("codigo" => "888","mensaje" => "Rut no valido (rut,".$datostrabajador["rut"].")");
		return $respuesta;
	}
	
	$datos["rut"] 				= $datostrabajador["rut"];
	$datos["nombre"] 			= $datostrabajador["nombre"];
	$datos["apellidopat"] 		= $datostrabajador["apellidopat"];
	$datos["nacionalidad"] 		= $datostrabajador["nacionalidad"];
	$datos["fechanacimiento"] 	= $datostrabajador["fechanacimiento"];
	$datos["estadocivil"] 		= $datostrabajador["estadocivil"];
	$datos["direccion"] 		= $datostrabajador["direccion"];
	$datos["comuna"] 			= $datostrabajador["comuna"];
	$datos["ciudad"] 			= $datostrabajador["ciudad"];
	$datos["rolid"] 			= $datostrabajador["rolid"];
	$datos["CodDivPersonal"] 	= $datosdocumentos["CodDivPersonal"];
	$datos["CodCargo"]			= $datosdocumentos["CodCargo"];
	$datos["ClaseContrato"]		= $datosdocumentos["ClaseContrato"];
	$datos["TipoMovimiento"]	= $datosdocumentos["TipoMovimiento"];
	$datos["CodigoJornada"]		= $datosdocumentos["CodigoJornada"];
	$datos["EstadoEmpleado"]	= $datosdocumentos["EstadoEmpleado"];
	$datos["AsignacionMovilizacion"]= $datosdocumentos["AsignacionMovilizacion"];
	$datos["Posicion"]			= $datosdocumentos["Posicion"];
	$datos["AsignacionPerdidaCajaValor"]= $datosdocumentos["AsignacionPerdidaCajaValor"];
	$datos["AsignacionPerdidaCajaPorcentaje"]= $datosdocumentos["AsignacionPerdidaCajaPorcentaje"];
	$datos["AsignacionCajaFija"]= $datosdocumentos["AsignacionCajaFija"];
	$datos["AsignacionColacion"]= $datosdocumentos["AsignacionColacion"];
	$datos["DescCargoRiohs"]	= $datosdocumentos["DescCargoRiohs"];
	$datos["SueldoBase"]		= $datosdocumentos["SueldoBase"];
	$datos["FechaInicioContrato"]= $datosdocumentos["FechaInicioContrato"];
	$datos["FechaTermino1"]		= $datosdocumentos["FechaTermino1"];
	$datos["FechaTermino2"]		= $datosdocumentos["FechaTermino2"];
	$datos["AreaTrabajo"]		= $datosdocumentos["AreaTrabajo"];
				
	$resultado = ValidaVacio ($datos);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	$datos2["fechanacimiento"] 		= $datostrabajador["fechanacimiento"];
	$datos2["FechaInicioContrato"] 	= $datosdocumentos["FechaInicioContrato"];
	$datos2["FechaTermino1"] 		= $datosdocumentos["FechaTermino1"];
	$datos2["FechaTermino2"] 		= $datosdocumentos["FechaTermino2"];
	
	$resultado = ValidaFecha ($datos2);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	$datos3["AsignacionMovilizacion"] 	= $datosdocumentos["AsignacionMovilizacion"];
	$datos3["Posicion"] 				= $datosdocumentos["Posicion"];
	$datos3["AsignacionPerdidaCajaValor"] = $datosdocumentos["AsignacionPerdidaCajaValor"];
	$datos3["AsignacionPerdidaCajaPorcentaje"]= $datosdocumentos["AsignacionPerdidaCajaPorcentaje"];
	$datos3["AsignacionCajaFija"]		= $datosdocumentos["AsignacionCajaFija"];
	$datos3["AsignacionColacion"]		= $datosdocumentos["AsignacionColacion"];
	$datos3["SueldoBase"]				= $datosdocumentos["SueldoBase"];
	
	$resultado = ValidaNumero ($datos3);
	if ($resultado != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $resultado);
		return $respuesta;
	}
	
	//valida centro costo
	$centroscostoBD = new centroscostoBD();
	$conecc = $bd->obtenerConexion();
	$centroscostoBD->usarConexion($conecc);
		
	$datos["idCentroCosto"] = $datosdocumentos["CodDivPersonal"];
	$centroscostoBD->obtener($datos,$dt);
	$datos["RutEmpresa"] = $dt->data[0]['RutEmpresa'];
	
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
	
	//valida cargo trabajador
	/*$subclausulasBD = new subclausulasBD();
	$conecc = $bd->obtenerConexion();
	$subclausulasBD->usarConexion($conecc);
		
	$datos["idTipoSubClausula"] = 3;
	$datos["idSubClausula"] 	= $datos["CodCargo"];
	
	$subclausulasBD->obtener($datos,$dt);
	$mensajeError = $subclausulasBD->mensajeError;*/
	
	$cargoEmpleadoBD = new cargoEmpleadoBD();
	$conecc = $bd->obtenerConexion();
	$cargoEmpleadoBD->usarConexion($conecc);
	
	$datos["idCargoEmpleado"] 	= $datos["CodCargo"];
	
	$cargoEmpleadoBD->obtener($datos,$dt);
	$mensajeError.=$cargoEmpleadoBD->mensajeError;
	
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Codigo cargo no existe (".$datos["idSubClausula"].")" );
		return $respuesta;
	}
	//fin
		
	//valida codigo jornada
	$subclausulasBD = new subclausulasBD();
	$conecc = $bd->obtenerConexion();
	$subclausulasBD->usarConexion($conecc);
		
	$datos["idTipoSubClausula"] = 2;
	$datos["idSubClausula"] 	= $datos["CodigoJornada"];
	$subclausulasBD->obtener($datos,$dt);
	$mensajeError = $subclausulasBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Codigo jornada no existe (".$datos["idSubClausula"].")" );
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
	
	//valida estado empleado
	$EstadosEmpleadosBD = new EstadosEmpleadosBD();
	$conecc = $bd->obtenerConexion();
	$EstadosEmpleadosBD->usarConexion($conecc);
	
	if(  $datostrabajador["EstadoEmpleado"] == '' ) $datos["idestadoempleado"] = 'A';
	else $datos["idestadoempleado"] 	= $datostrabajador["EstadoEmpleado"];
	
	$EstadosEmpleadosBD->obtener($datos,$dt);
	$mensajeError = $EstadosEmpleadosBD->mensajeError;
	if ($mensajeError != "")
	{
		$respuesta = array("codigo" => "888","mensaje" => $mensajeError);
		return $respuesta;
	}

	if(!$dt->leerFila())
	{
		$respuesta = array("codigo" => "888","mensaje" => "Estado trabajador no registrado (".$datos["idestadoempleado"].")" );
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