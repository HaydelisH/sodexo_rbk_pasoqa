<?php

include_once('includes/Seguridad.php');
include_once("generar.php");

include_once("includes/documentosBD.php");
include_once("includes/parametrosBD.php");
include_once("includes/docvigentesBD.php");
include_once("includes/formularioPlantillaBD.php");
include_once("includes/personasBD.php");

//Opcion del AJAX para el Vista Previa
$page = new EnvioFormulario();

class EnvioFormulario {

	// Para armas la pagina
	//private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
	private $formularioPlantillaBD;
	private $personasBD;
	//private $plantillasBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $contIntentosCurl = 0;

	//private $nombrearchivo="";
	//private $fechahoy="";
	// funcion contructora, al instanciar
	function __construct()
	{
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{		
			echo 'Mensaje | No hay conexión con la base de datos!';
			exit;
		}
		
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}

		// instanciamos del manejo de tablas
    	$this->documentosBD = new documentosBD();
    	$this->parametrosBD = new parametrosBD();
		$this->docvigentesBD = new docvigentesBD();
		$this->formularioPlantillaBD = new formularioPlantillaBD();
		$this->personasBD = new personasBD();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->parametrosBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);
		$this->formularioPlantillaBD->usarConexion($conecc);
		$this->personasBD->usarConexion($conecc);

		$dt = new DataTable();
		// pedimos el listado

        $datos = $_REQUEST;
        $data['idPlantilla'] = $datos['idPlantilla'];
        $data['idProceso'] = $datos['idProceso'];

        $data['newusuarioid'] = $this->seguridad->usuarioid;
        $data['rutusuario'] = $this->seguridad->usuarioid;

        //var_dump(123);
        $this->formularioPlantillaBD->getByEmpleadoFormularioid($datos, $dt);
        $this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
        $idFormulario = $dt->data[0]['idFormulario'];
        $data['idFormulario'] = $idFormulario;
        $data['idTipoGeneracion'] = 7;
        $data['RutEmpresa'] = $dt->data[0]['empresaid'];
        $data['idCentroCosto'] = $dt->data[0]['centrocostoid'];
        $data['LugarPagoid'] = $dt->data[0]['lugarpagoid'];
        $data['CiudadFirma'] = $dt->data[0]['CiudadFirma'];
        $data['FechaDocumento'] = $dt->data[0]['FechaDocumento'];
        $data['FechaIngreso'] = $dt->data[0]['FechaIngreso'];
        $data['Cargo'] = $dt->data[0]['Cargo'];
        $data['Firmantes_Emp'] = json_decode($dt->data[0]['FirmantesJSON']);
        //var_dump($idFormulario);
        foreach($datos As $llave=>$valor)
        {
            $aux = explode('-', $llave);
            if (!is_array($valor))
            {
                if (stripos($llave, 'querySiNoDinamico') !== false)// && $valor == 'si')
                {
                    switch($idFormulario)
                    {
                        case 1000: // Declaracion de conflictos de interes
                        {
                            $data[$aux[0]] = $valor;
                            $data["{$llave}_texto"] = '<table border="1" width="100%" cellspacing="0" cellspading="0">';
                            $data["{$llave}_texto"] .= '  <thead>';
                            $data["{$llave}_texto"] .= '      <tr>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Nombre</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Parentesco</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Entidad / Autoridad politica / Cargo</th>';
                            $data["{$llave}_texto"] .= '      </tr>';
                            $data["{$llave}_texto"] .= '  </thead>';
                            $data["{$llave}_texto"] .= '  <tbody>';
                            for ($i = 0; $i < count($datos[$llave . '_columna1-R']); $i++)
                            {
                                $data["{$llave}_texto"] .= '      <tr>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna1-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna2-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna3-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '      </tr>';
                            }
                            $data["{$llave}_texto"] .= '  </tbody>';
                            $data["{$llave}_texto"] .= '</table>';
                            break;
                        }
                        case 4000: // medismart
                        {
                            //var_dump(123);
                            $data[$aux[0]] = $valor;
                            $data["{$llave}_texto"] = '<table border="1" width="100%" cellspacing="0" cellspading="0">';
                            $data["{$llave}_texto"] .= '  <thead>';
                            $data["{$llave}_texto"] .= '      <tr>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">RUT</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Nombre completo</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Correo</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Celular</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Genero</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Fecha nacimiento</th>';
                            $data["{$llave}_texto"] .= '          <th style="text-align: center">Parentesco</th>';
                            $data["{$llave}_texto"] .= '      </tr>';
                            $data["{$llave}_texto"] .= '  </thead>';
                            $data["{$llave}_texto"] .= '  <tbody>';
                            for ($i = 0; $i < count($datos[$llave . '_columna1-R']); $i++)
                            {
                                $data["{$llave}_texto"] .= '      <tr>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna1-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna2-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna3-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna4-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . ($datos[$llave . '_columna5-R'][$i] == 1 ? 'Masculino' : 'Femenino') . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna7-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '          <td>' . $datos[$llave . '_columna6-R'][$i] . '</td>';
                                $data["{$llave}_texto"] .= '      </tr>';
                            }
                            $data["{$llave}_texto"] .= '  </tbody>';
                            $data["{$llave}_texto"] .= '</table>';


                            for ($i = 0; $i < count($datos[$llave . '_columna1-R']); $i++)
                            {
                                //var_dump($llave . '_columna8-R', $datos[$llave . '_columna8-R']);
                                if (!isset($data[$datos[$llave . '_columna8-R'][$i]]))
                                {
                                    $data[$datos[$llave . '_columna8-R'][$i]] = array();
                                }
                                $aux = explode('-', $datos[$llave . '_columna7-R'][$i]);
                                $datos[$llave . '_columna7-R'][$i] = "{$aux[1]}-{$aux[0]}-{$aux[2]}";

                                array_push(
                                    $data[$datos[$llave . '_columna8-R'][$i]], 
                                    array(
                                        'parentescoRut'=>$datos[$llave . '_columna1-R'][$i],
                                        'parentescoNombre'=>$datos[$llave . '_columna2-R'][$i],
                                        'parentesco'=>$datos[$llave . '_columna8-R'][$i],
                                        'parentescoNacimiento'=>$datos[$llave . '_columna7-R'][$i],
                                        'parentescoEmail'=>$datos[$llave . '_columna3-R'][$i],
                                        'parentescoCelular'=>$datos[$llave . '_columna4-R'][$i],
                                        'parentescoGenero'=>($datos[$llave . '_columna5-R'][$i] == 1 ? 'Masculino' : 'Femenino'),
                                        'parentescoTipoCarga'=>$datos[$llave . '_columna6-R'][$i]
                                    )
                                );
                            }
                            break;
                        }
                    }
                    continue;
                }
                /*else if(stripos($llave, 'querySiNoDinamico') !== false)
                {
                    $data[$aux[0]] = $valor;
                }*/
                if (stripos($llave, 'querySiNoObsDes') !== false && stripos($llave, '_texto') === false)// && $valor == 'si')
                {
                    $data[$aux[0]] = $valor;
                    /*$data["{$llave}_texto"] = '<table border="1" width="100%" cellspacing="0" cellspading="0">';
                    $data["{$llave}_texto"] .= '  <tbody>';
                    $data["{$llave}_texto"] .= '      <tr>';
                    $data["{$llave}_texto"] .= '          <td>' . (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' ) . '</td>';
                    $data["{$llave}_texto"] .= '      </tr>';
                    $data["{$llave}_texto"] .= '  </tbody>';
                    $data["{$llave}_texto"] .= '</table>';*/
                    if (stripos($datos["{$llave}_texto-R"], 'rescatarEnBackEnd') !== false)
                    {
                        switch($idFormulario)
                        {
                            case 2000: // Covid-19
                                $data["{$llave}_texto"] = '
                                <p>Como trabajador en Grupo de riesgo seg&uacute;n clasificaci&oacute;n Minsal asumo el deber de cumplir con todas las medidas adoptadas por la empresa para proteger la vida y salud de los trabajadores para enfrentar la crisis sanitaria originada por el covid-19 que afecta al pa&iacute;s y declaro tomar conocimientos de las medidas excepcionales  que debo seguir: </p>
                                <ol type="1">
                                    <li>Para asistencia a reuniones s&oacute;lo hacerlo por medio de videoconferencia.</li>
                                    <li>Usar preferentemente respirador medio rostro con filtros P100.(en caso de trabajar en plantas)</li>
                                    <li>Uso preferente de guantes en todo momento al estar en contacto con equipo de trabajo.</li>
                                    <li>Facilidad para acordar horarios de ingreso diferidos.</li>
                                    <li>Nunca estar en contacto con m&aacute;s de una persona en recinto cerrados.</li>
                                    <li>Almuerzo en horario diferido, o en sectores apartados y/o en modo retira.</li>
                                    <li>Asistir a planta cuando sea estrictamente necesario, priorizando el teletrabajo</li>
                                </ol>';
                            break;
                        }
                    }
                    else
                    {
                        $data["{$llave}_texto"] = (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' );
                    }
                    continue;
                }
                /*else if (stripos($llave, 'querySiNoObsDes') !== false && stripos($llave, '_texto') === false)
                {
                    $data[$aux[0]] = $valor;
                }*/
                if (stripos($llave, 'querySiNoObs') !== false && stripos($llave, '_texto') === false)// && $valor == 'si')
                {
                    //var_dump($valor);
                    //var_dump($aux[0]);
                    $data[$aux[0]] = $valor;
                    switch ($idFormulario )
                    {
                        case 2:
                        case 3:
                        {
                            $ademas = '';
                            if ($valor == 'si')
                            {
                                $ademas = '<p>Reporto la existencia de las siguientes situaciones y/o relaciones que pueden dar lugar a un conflicto de inter&eacute;s en el marco de mis funciones en SODEXO</p>';
                            }
                            else
                            {
                                $ademas = '<p>No tengo ning&uacute;n conflicto de inter&eacute;s que reportar a la fecha</p>';
                            }
                            $ademas = $ademas.'<p style="text-align: justify;">Describa el conflicto de inter&eacute;s existente:</p>';
                        }
                        break;
                    }
                    $data["{$llave}_texto"] = $ademas.'<table border="1" width="100%" cellspacing="0" cellspading="0">';
                    $data["{$llave}_texto"] .= '  <tbody>';
                    $data["{$llave}_texto"] .= '      <tr>';
                    $data["{$llave}_texto"] .= '          <td>' . (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' ) . '</td>';
                    $data["{$llave}_texto"] .= '      </tr>';
                    $data["{$llave}_texto"] .= '  </tbody>';
                    $data["{$llave}_texto"] .= '</table>';
                    continue;
                }
                /*else if (stripos($llave, 'querySiNoObs') !== false && stripos($llave, '_texto') === false)
                {
                    $data[$aux[0]] = $valor;
                }*/
                if (stripos($llave, 'querySiNoSimple') !== false && stripos($llave, '_texto') === false)// && $valor == 'si')
                {
                    $data[$aux[0]] = $valor;
                    /*$data["{$llave}_texto"] = '<table border="1" width="100%" cellspacing="0" cellspading="0">';
                    $data["{$llave}_texto"] .= '  <tbody>';
                    $data["{$llave}_texto"] .= '      <tr>';
                    $data["{$llave}_texto"] .= '          <td>' . (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' ) . '</td>';
                    $data["{$llave}_texto"] .= '      </tr>';
                    $data["{$llave}_texto"] .= '  </tbody>';
                    $data["{$llave}_texto"] .= '</table>';*/
                    //$data["{$llave}_texto"] = (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' );
                    continue;
                }
                /*else if (stripos($llave, 'querySiNoSimple') !== false && stripos($llave, '_texto') === false)
                {
                    $data[$aux[0]] = $valor;
                }*/
            }
            if (stripos($llave, 'querySiNoOpciones') !== false && stripos($llave, 'Option') === false)// && $valor == 'si')
            {
                //var_dump($llave);
                $data[$aux[0]] = $valor;
                /*$data["{$llave}_texto"] = '<table border="1" width="100%" cellspacing="0" cellspading="0">';
                $data["{$llave}_texto"] .= '  <tbody>';
                $data["{$llave}_texto"] .= '      <tr>';
                $data["{$llave}_texto"] .= '          <td>' . (isset($datos[$llave . '_texto-R']) ? $datos[$llave . '_texto-R'] : '&nbsp;' ) . '</td>';
                $data["{$llave}_texto"] .= '      </tr>';
                $data["{$llave}_texto"] .= '  </tbody>';
                $data["{$llave}_texto"] .= '</table>';*/
                //var_dump($llave); //querySiNoOpciones1
				switch($idFormulario)
				{
					case 3000: // Bono Silla Teletrabajo
					{
						$data["{$llave}_option"] = (isset($datos[$llave . 'Option']) ? 'En el caso de aceptar dicho pr&eacute;stamo, el trabajador acepta pagarlo en ' . $datos[$llave . 'Option'] : '&nbsp;' );
						break;
					}
					default:
					{
						$data["{$llave}_option"] = (isset($datos[$llave . 'Option']) ? $datos[$llave . 'Option'] : '&nbsp;' );
						break;
					}
				}
                continue;
            }
            /*else if (stripos($llave, 'querySiNoSimple') !== false && stripos($llave, '_texto') === false)
            {
                $data[$aux[0]] = $valor;
            }*/
        }
        //var_dump($data['idCentroCosto']);
        //var_dump($data['lugarpagoid']);
        //var_dump($data['RutEmpresa']);
        //$this->documentosBD->perfilObtener($data, $dt);
        //$this->mensajeError.=$this->documentosBD->mensajeError;
        //$data['idCentroCosto'] = $dt->data[0]['idCentroCosto'];
        //$data['lugarpagoid'] = $dt->data[0]['lugarpagoid'];
        //$data['RutEmpresa'] = $dt->data[0]['RutEmpresa'];
        $this->documentosBD->obtenerPersonaPorRut($data, $dt);
        $this->mensajeError.=$this->documentosBD->mensajeError;
        $data['nombreEmpleado'] = $dt->data[0]['nombreCompleto'];
        /* Para que no se borren los datos de la persona al generar el documento */
        $data['personaid'] = $dt->data[0]['personaid'];
        $data['nacionalidad'] = $dt->data[0]['nacionalidad'];
        $data['nombre'] = $dt->data[0]['nombre'];
        $data['appaterno'] = $dt->data[0]['appaterno'];
        $data['apmaterno'] = $dt->data[0]['apmaterno'];
        $data['correo'] = $dt->data[0]['correo'];
        $data['direccion'] = $dt->data[0]['direccion'];
        $data['ciudad'] = $dt->data[0]['ciudad'];
        $data['comuna'] = $dt->data[0]['comuna'];
        $data['fechanacimiento'] = $dt->data[0]['fechanacimiento'];
        $data['estadocivil'] = $dt->data[0]['estadocivil'];
        $data['rolid'] = $dt->data[0]['rolid'];
        $data['fono'] = $dt->data[0]['fono'];
        /*
        [$DATOS.CiudadFirma$]
         [$DATOS.FechaDocumento/S$
          [$EMPLEADO.NombreTrabajador$],
          [$EMPLEADO.RutTrabajador$]

          [$EMPLEADO.Correo$]
        */
        //$data['clave'] = $dt->data[0]['nombre'];
        /* FIN */
        
        switch ($idFormulario)
        {
            case 1: // conflictos de interes (EMAIL)
                //$data['direccion'] = '';
                //$data['ciudad'] = '';
                //$data['comuna'] = '';
                $data['celularContacto'] = '';
                //$data['fono'] = '';
                $data['celularPersonal'] = '';
                $data['envioinfo'] = 0;
                $data['nombreContacto'] = '';
                $data['relacionContacto'] = '';
                $data['correoNotificacionPorConcentimiento'] = $datos['correoElectronico-R'];
                //var_dump($datos['correoElectronico-R']);
                $this->personasBD->agregarInfoContacto($data);
                //var_dump($this->personasBD->mensajeError);
            break;
            case 2000: // Covid-19
                $data['direccion'] = $datos['direccion-R'];
                $data['ciudad'] = $datos['ciudad-R'];
                $data['comuna'] = $datos['comuna-R'];
                $data['celularContacto'] = $datos['celularContacto-R'];
                $data['fono'] = $datos['celularPersonal-R'];
                $data['celularPersonal'] = $datos['celularPersonal-R'];
                $data['envioinfo'] = ($datos['querySiNoSimple1'] == 'si' ? 1 : 0);
                $data['nombreContacto'] = $datos['nombreContacto-R'];
                $data['relacionContacto'] = $datos['relacionContacto-R'];
                $data['correoNotificacionPorConcentimiento'] = '';
                $this->personasBD->agregarInfoContacto($data);
            break;
            case 3000: // Bono silla teletrabajo
                $data['tituloCargo'] = $datos['tituloCargo-R'];
                $data['FechaActual'] = date(VAR_FORMATO_FECHA);
            break;
            case 4000:
                $data['tituloCargo'] = $datos['tituloCargo-R'];
                $data['FechaActual'] = date(VAR_FORMATO_FECHA);
            break;
        }
        //var_dump($data);
        $generar = new generar();

        $respuesta = array();
        //echo GENERADOR_PDF;
        if( GENERADOR_PDF == 'SERVICIO' ){
            //->POR ACA SE VA
            $respuesta = $generar->GenerarDocumentoConServicio($data);
            //var_dump($espuesta);
        }else{
            $respuesta = $generar->GenerarDocumentoCompleto($data);
        }
        if( $respuesta['estado'] )
        {
            if (isset($_REQUEST['empleadoFormularioid']))
            {
                $datos['idDocumento'] = $respuesta['data'];
                $this->formularioPlantillaBD->setIdDocumento($datos, $dt);
                $this->mensajeError .= $this->formularioPlantillaBD->mensajeError;
            }
            switch ($idFormulario )
            {
                case 4000:
                    for ($i = 0; $i < count($data['directos']); $i++)
                    {

                        $data['directos'][$i]['idFormulario'] = $idFormulario;
                        $data['directos'][$i]['idDocumento'] = (int)$datos['idDocumento'];
                        $data['directos'][$i]['personaId'] = $data['personaid'];
                        $data['directos'][$i]['personaEmail'] = $datos['correo-R'];
                        $data['directos'][$i]['personaNombre'] = $data['nombre'];
                        $data['directos'][$i]['personaCelular'] = $datos['celular-R'];
                        $this->formularioPlantillaBD->setGenericaFormulario($data['directos'][$i]);
						$this->mensajeError .= $this->formularioPlantillaBD->mensajeError;
                    }
                    for ($i = 0; $i < count($data['extras']); $i++)
                    {
                        $data['extras'][$i]['idFormulario'] = $idFormulario;
                        $data['extras'][$i]['idDocumento'] = (int)$datos['idDocumento'];
                        $data['extras'][$i]['personaId'] = $data['personaid'];
                        $data['extras'][$i]['personaEmail'] = $datos['correo-R'];
                        $data['extras'][$i]['personaNombre'] = $data['nombre'];
                        $data['extras'][$i]['personaCelular'] = $datos['celular-R'];
                        $this->formularioPlantillaBD->setGenericaFormulario($data['extras'][$i]);
						$this->mensajeError .= $this->formularioPlantillaBD->mensajeError;
                    }
                    //var_dump($data['directos']);
                    //var_dump($data['extras']);
                break;
            }    
            $array = array(
                'estado'=>true,
                'idDocumento'=>$respuesta['data']
            );
            $array = $this->utf8_converter($array);
            echo json_encode($array);
        }
        else
        {
            $array = array(
                'estado'=>false,
                'mensaje'=>$respuesta['mensaje']
            );
            echo json_encode($array);
		}
		$this->bd->desconectar();
		exit;
	}
	
    private function utf8_converter($array)
	{
	    array_walk_recursive($array, function(&$item, $key){
	        if(!mb_detect_encoding($item, 'utf-8', true)){
	                $item = utf8_encode($item);
	        }
	    });
	 
	    return $array;
	}
}

?>