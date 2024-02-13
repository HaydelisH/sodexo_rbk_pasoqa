<?php

include_once('includes/Seguridad.php');
include_once("includes/postulacionBD.php");

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new linkPostulacion_ajax();

class linkPostulacion_ajax {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $postulacionBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";

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
		$this->postulacionBD = new postulacionBD();

		$conecc = $this->bd->obtenerConexion();
		$this->postulacionBD->usarConexion($conecc);

		$dt = new DataTable();
		$array = array ();
		$datos = $_POST;
		/*
			array(
				'proximidadCaducidadId'=>'1',
				'proximidadCaducidadNombre'=>'Sin Link'
			),
			array(
				'proximidadCaducidadId'=>'6',
				'proximidadCaducidadNombre'=>'Link caducado'
			),
			array(
				'proximidadCaducidadId'=>'4',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_ROJO . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'3',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_NARANJO . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'2',
				'proximidadCaducidadNombre'=>'Menos de ' . DIAS_VERDE . ' dias'
			),
			array(
				'proximidadCaducidadId'=>'5',
				'proximidadCaducidadNombre'=>'Sobre ' . DIAS_VERDE . ' dias'
			)
		*/
	    $ahora = date(VAR_FORMATO_FECHA);
		$datos['ahora'] = $ahora;
		switch($datos['proximidadCaducidadId'])
		{
			case 1://'Sin Link'
			{
				$datos['dias'] = 0;
				$datos['dias2'] = 0;
				break;
			}
			case 2://'Menos de ' . DIAS_VERDE . ' dias'
			{
				$datos['dias'] = DIAS_VERDE;
				$datos['dias2'] = 0;
				break;
			}
			case 3://'Menos de ' . DIAS_NARANJO . ' dias'
			{
				$datos['dias'] = DIAS_NARANJO;
				$datos['dias2'] = 0;
				break;
			}
			case 4://'Menos de ' . DIAS_ROJO . ' dias'
			{
				$datos['dias'] = DIAS_ROJO;
				$datos['dias2'] = 0;
				break;
			}
			case 5://'Sobre ' . DIAS_VERDE . ' dias'
			{
				$datos['dias'] = DIAS_VERDE;
				$datos['dias2'] = 0;
				break;
			}
			case 6://'Link caducado'
			{
				$datos['dias'] = 0;
				$datos['dias2'] = 0;
				break;
			}
			case 7://'Entre ' . DIAS_ROJO . ' y ' . DIAS_NARANJO . ' dias'
			{
				$datos['dias'] = DIAS_ROJO;
				$datos['dias2'] = DIAS_NARANJO;
				break;
			}
			case 8://'Entre ' . DIAS_NARANJO . ' y ' . DIAS_VERDE . ' dias'
			{
				$datos['dias'] = DIAS_NARANJO;
				$datos['dias2'] = DIAS_VERDE;
				break;
			}
		}// 2020-02-29
    	//Consultar el tipo de firma que tiene asociada el usuario
		if ( $this->postulacionBD->getCargosEmpresa($datos,$dt)){

			if( count($dt->data) > 0) {
                $listado = array();
                for ($i = 0; $i < count($dt->data); $i++)
                {
                    $listado[$i] = array();
                    foreach($dt->data[$i] AS $llave=>$valor)
                    {
                        if (!is_numeric($llave))
                        {
                            $listado[$i][$llave] = $valor;
                            if ($llave == 'fechaCaducidadLink')
                            {
                                if ($valor == null || $valor == '')
                                {
                                    $listado[$i]['estadoLink'] = 1;
                                    $listado[$i]['estadoLinkTitle'] = 'Link sin fecha de caducidad';
                                }
                                else if (strtotime($ahora) > strtotime($listado[$i][$llave]))
                                {
                                    $listado[$i]['estadoLink'] = 1;
                                    $listado[$i]['estadoLinkTitle'] = 'Link con su fecha caducada';
                                }
                                else if (0 < strtotime($ahora . "+" . DIAS_ROJO . " days") - strtotime($listado[$i][$llave]) && strtotime($ahora . "+" . DIAS_ROJO . " days") - strtotime($listado[$i][$llave]) <= (DIAS_ROJO * 86400))
                                {
                                    $listado[$i]['estadoLink'] = 4;
                                    $listado[$i]['estadoLinkTitle'] = 'Link a menos de ' . DIAS_ROJO . ' dias de su expiraci&oacute;n';
                                }
                                else if (0 < strtotime($ahora . "+" . DIAS_NARANJO . " days") - strtotime($listado[$i][$llave]) && strtotime($ahora . "+" . DIAS_NARANJO . " days") - strtotime($listado[$i][$llave]) <= (DIAS_NARANJO * 86400))
                                {
                                    $listado[$i]['estadoLink'] = 3;
                                    $listado[$i]['estadoLinkTitle'] = 'Link a menos de ' . DIAS_NARANJO . ' dias de su expiraci&oacute;n';
                                }
                                else if  (0 < strtotime($ahora . "+" . DIAS_VERDE . " days") - strtotime($listado[$i][$llave]) && strtotime($ahora . "+" . DIAS_VERDE . " days") - strtotime($listado[$i][$llave]) <= (DIAS_VERDE * 86400))
                                {
                                    $listado[$i]['estadoLink'] = 2;
                                    $listado[$i]['estadoLinkTitle'] = 'Link a menos de ' . DIAS_VERDE . ' dias de su expiraci&oacute;n';
                                }
                                else
                                {
                                    $listado[$i]['estadoLink'] = 2;
                                    $listado[$i]['estadoLinkTitle'] = 'Link por sobre ' . DIAS_VERDE . ' dias para su expiraci&oacute;n';
                                }
                            }
                        }
                    }
                }
				$array = $this->utf8_converter($listado);
				echo json_encode($array);
			}
			else
			{
				$array = $this->utf8_converter(array());
				echo json_encode($array);
			}
		}else{
			echo $this->postulacionBD->mensajeError; 
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