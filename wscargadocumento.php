<?php
error_reporting(E_ERROR); 
$nu_soap_path = 'lib/nusoap.php';
require_once ($nu_soap_path);

	include_once ("/includes/ObjetoBD.php");
	include_once ("/includes/ContenedorUtilidades.php");
	include_once ("/includes/MensajeUsuario.php");
	include_once ("/includes/DataTable.php");
	include_once ("/includes/parametrosBD.php");
	include_once ("/includes/documentosBD.php");
	include_once ('generar.php');


	// Create the server instance
	$server = new soap_server();

	// Initialize WSDL support
	$server->configureWSDL('Carga de Documento', 'urn:wscargadocumento');
	

			
	$server->wsdl->addComplexType(
            'datosproceso',
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
            'datostrabajador',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'personaid' => array('name' => 'personaid', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'nacionalidad' => array('name' => 'nacionalidad', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'fechanacimiento' => array('name' => 'fechanacimiento', 'maxOccurs' => '1', 'type' =>'xsd:date'),
					'estadocivil' => array('name' => 'estadocivil', 'maxOccurs' => '1', 'type' =>'xsd:integer'),
					'direccion' => array('name' => 'direccion', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'comuna' => array('name' => 'comuna', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'ciudad' => array('name' => 'ciudad', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'rolid' => array('name' => 'rolid', 'maxOccurs' => '1', 'type' =>'xsd:integer'),
					'correo' => array('name' => 'correo', 'maxOccurs' => '1', 'type' =>'xsd:string'),
				)
            );
			
			
	$server->wsdl->addComplexType(
            'datosdocumento',
            'complexType',
            'struct',
            'all',
            '',
                array(
					'idCentroCosto' => array('name' => 'centrocosto', 'maxOccurs' => '1','type' =>'xsd:string'),
					'lugarpagoid' => array('name' => 'xxd', 'maxOccurs' => '1','type' =>'xsd:string'),
					'fechadocumento' => array('name' => 'fechadocumento', 'maxOccurs' => '1','type' =>'xsd:date'),
					
					'AnticipoIndemnizacion' => array('name' => 'AnticipoIndemnizacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoAnillo1' => array('name' => 'BonoAnillo1', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoAnillo2' => array('name' => 'BonoAnillo2', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoAsistencia' => array('name' => 'BonoAsistencia', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoDisponibilidad' => array('name' => 'BonoDisponibilidad', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoImagen' => array('name' => 'BonoImagen', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoMixer' => array('name' => 'BonoMixer', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoOperadorPlanta' => array('name' => 'BonoOperadorPlanta', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoProductividad' => array('name' => 'BonoProductividad', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoProgramadorPlanta' => array('name' => 'BonoProgramadorPlanta', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoProyectoEspecial' => array('name' => 'BonoProyectoEspecial', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoProyectoEspecial2' => array('name' => 'BonoProyectoEspecial2', 'maxOccurs' => '1','type' =>'xsd:string'),
					'BonoSucursal' => array('name' => 'BonoSucursal', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Cargo' => array('name' => 'Cargo', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Ciudad' => array('name' => 'Ciudad', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Colacion' => array('name' => 'Colacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Entidad' => array('name' => 'Entidad', 'maxOccurs' => '1','type' =>'xsd:string'),
					'FechaIngreso' => array('name' => 'FechaIngreso', 'maxOccurs' => '1','type' =>'xsd:date'),
					'FechaInicio' => array('name' => 'FechaInicio', 'maxOccurs' => '1','type' =>'xsd:date'),
					'Gratificacion' => array('name' => 'Gratificacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Indemnizacion' => array('name' => 'Indemnizacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Jornada' => array('name' => 'Jornada', 'maxOccurs' => '1','type' =>'xsd:string'),
					'MesBonoProgramadorPlanta' => array('name' => 'MesBonoProgramadorPlanta', 'maxOccurs' => '1','type' =>'xsd:string'),
					'MontoActual' => array('name' => 'MontoActual', 'maxOccurs' => '1','type' =>'xsd:string'),
					'MontoFinal' => array('name' => 'MontoFinal', 'maxOccurs' => '1','type' =>'xsd:string'),
					'MontoNuevo' => array('name' => 'MontoNuevo', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Movilizacion' => array('name' => 'Movilizacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'NombreCurso' => array('name' => 'NombreCurso', 'maxOccurs' => '1','type' =>'xsd:string'),
					'NombreProyecto' => array('name' => 'NombreProyecto', 'maxOccurs' => '1','type' =>'xsd:string'),
					'PagoIas' => array('name' => 'PagoIas', 'maxOccurs' => '1','type' =>'xsd:string'),
					'SaldoIas' => array('name' => 'SaldoIas', 'maxOccurs' => '1','type' =>'xsd:string'),
					'SaldoIndemnizacion' => array('name' => 'SaldoIndemnizacion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'SueldoBase' => array('name' => 'SueldoBase', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Texto1' => array('name' => 'Texto1', 'maxOccurs' => '1','type' =>'xsd:string'),
					'TipoEstudios' => array('name' => 'TipoEstudios', 'maxOccurs' => '1','type' =>'xsd:string'),
					'ValorBeca' => array('name' => 'ValorBeca', 'maxOccurs' => '1','type' =>'xsd:string'),
					'Bono' => array('name' => 'Bono', 'maxOccurs' => '1','type' =>'xsd:string'),
					'FechaPago' => array('name' => 'FechaPago', 'maxOccurs' => '1','type' =>'xsd:date'),
					'Lugar' => array('name' => 'Lugar', 'maxOccurs' => '1','type' =>'xsd:string'),
					'NumeroAnillos' => array('name' => 'NumeroAnillos', 'maxOccurs' => '1','type' =>'xsd:string'),
					'PlantaDireccion' => array('name' => 'PlantaDireccion', 'maxOccurs' => '1','type' =>'xsd:string'),
					'TotalRemuneracion' => array('name' => 'TotalRemuneracion', 'maxOccurs' => '1','type' =>'xsd:string'),
				)
            );
			
	/*	
	$server->wsdl->addComplexType(
            'datosfirmantes',
            'complexType',
            'struct',
            'all',
            '',
                array	(
					'rut' => array('name' => 'rut', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'nombre' => array('name' => 'nombre', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'email' => array('name' => 'email', 'maxOccurs' => '1', 'type' =>'xsd:string'),
					'rol' => array('name' => 'rol', 'maxOccurs' => '1', 'type' =>'xsd:integer'),
                )
            );
	*/		
		
	/*
	   $server->wsdl->addComplexType(
            'datosfirmantes',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(
              array('ref' => 'SOAP-ENC:arrayType',
                    'wsdl:arrayType' => 'tns:firmanteempresa[]'
                  )
            ),
            'tns:firmanteempresa'
          );
		*/

	
	$server->wsdl->addComplexType(
            'datosfirmantes',
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
            'respuesta',
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
        array('usuario' => 'xsd:string','clave' => 'xsd:string','datosproceso' =>'tns:datosproceso','datostrabajador' =>'tns:datostrabajador','datosdocumento' =>'tns:datosdocumento','datosfirmantes' =>'tns:datosfirmantes'),     	// input parameters
        array('return' =>'tns:respuesta'),    		// output parameters
 		'urn:wscargadocumento',                     // namespace
		'urnwsgettipodocwsdl#wscargadocumento',
        'rpc',                                    	// style
        'encoded',                                	// use
        'Carga de Documentos'						// documentation
      );
		  




// Define the method as a PHP function
function  wscargadocumento($usuario,$clave,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes) {
	/*
	$seguridad = json_encode($clave);
	$datosrepresentantes = json_encode($seguridad);

	date_default_timezone_set('America/Santiago');
	$nomarchivo = 'logs/logjson'.@date("Ymd").'.TXT';
	$ar=fopen($nomarchivo,"a") or
	   die("Problemas en la creacion");
	fputs($ar,@date("H:i:s")." ".$seguridad." ".$datosrepresentantes);
	fputs($ar,"\n");
	fclose($ar);	

	
	/*
	$res = arrayToXml($datosrepresentantes);
	
	$nomarchivo = 'logs/logxml'.@date("Ymd").'.TXT';
	$ar=fopen($nomarchivo,"a") or
	   die("Problemas en la creacion");
	fputs($ar,@date("H:i:s")." ".$res);
	fputs($ar,"\n");
	fclose($ar);	
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
	
	$respuestag = Cargar_Documento($bd,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes);	
	$codigo    = $respuestag["codigo"];
	$mensaje   = $respuestag["mensaje"];
	$nrocontrato   = $respuestag["data"];
	
	$respuesta = array("codigo" => $codigo,"mensaje" => $mensaje,"nrocontrato" => $nrocontrato);
	
	return $respuesta;
}

function Valida_Usuario($bd,$usuario,$clave)
{
	$parametrosBD = new parametrosBD();
	$conecc = $bd->obtenerConexion();
	// si se pudo abrir entonces usamos la conecion en nuestras tablas
	$parametrosBD->usarConexion($conecc);
		
	$datos["idparametro"] = 'ws_usuario';
	$parametrosBD->Obtener($datos,$dt);
	$mensajeError = $parametrosBD->mensajeError;
	if ($mensajeError != "")
	{
		return $mensajeError;
	}

	if(!$dt->leerFila())
	{
		return ("Error parametro no creado");
	}
	
	$usuariobase = $dt->obtenerItem("parametro");
	
	$datos["idparametro"] = 'ws_clave';
	$parametrosBD->Obtener($datos,$dt);
	$mensajeError = $parametrosBD->mensajeError;
	if ($mensajeError != "")
	{
		return $mensajeError;
	}
	
	if(!$dt->leerFila())
	{
		return ("Error parametro no creado");
	}
	
	$clavebase = $dt->obtenerItem("parametro");
		
	if ($usuario != $usuariobase && $clave != $usuarioclave)
	{
		return ("Error usuario o clave no corresponde");
	}
	
	//return "ok";
}

function Cargar_Documento($bd,$datosproceso,$datostrabajador,$datosdocumentos,$datosfirmantes)
{
	$documentosBD = new documentosBD();
	$conecc = $bd->obtenerConexion();
	$documentosBD->usarConexion($conecc);
	$rutempresa = $datosproceso["RutEmpresa"];

	$datosdocumentos["newusuarioid"]	= $datostrabajador["personaid"];
	$datostrabajador["idEstadoCivil"] 	= $datostrabajador["estadocivil"];
	
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
	$datos['idTipoGeneracion'] = 8;
	$generar = new generar();
	$respuesta = $generar->GenerarDocumento($datos);

	return $respuesta;
}



function Graba_Log($log)
{
	date_default_timezone_set('America/Santiago');
	$nomarchivo = 'logs/logws_'.@date("Ymd").'.TXT';
	$ar=fopen($nomarchivo,"a") or
	   die("Problemas en la creacion");
	fputs($ar,@date("H:i:s")." ".$log);
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
$server->service($HTTP_RAW_POST_DATA);
?>