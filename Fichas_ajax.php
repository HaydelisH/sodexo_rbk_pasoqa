<?php
   
error_reporting(E_ALL & ~E_NOTICE);

include_once('includes/Seguridad.php');
include_once('includes/fichasBD.php');
include_once('includes/fichasDatosImportacionBD.php');
include_once('includes/procesosBD.php');

//Generar docuemnto
include_once('generar.php');
include_once('Config.php');

//Opcion del AJAX para firma con PIN
$page = new fichas();

class fichas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	//Mensaje de error si existe 
	private $mensajeError="";
	//Manejo de base de datos
	private $fichasBD;
	private $fichasDatosImportacionBD;
	private $procesosBD;

	//funcion contructora, al instanciar
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

		$this->fichasBD = new fichasBD();
		$this->fichasDatosImportacionBD = new fichasDatosImportacionBD();
		$this->procesosBD = new procesosBD();

		$conecc = $this->bd->obtenerConexion();
		$this->fichasBD->usarConexion($conecc);
		$this->fichasDatosImportacionBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		
		$datos = $_REQUEST;
		$dt = new Datatable();
		$dt2 = new Datatable();
		$dt3 = new Datatable();
		$dt_empleado = new Datatable();
		$datos['idPlantilla'] = $datos['idplantilla'];
		$datos['RutFirmante'] = $datos['personaid'];
		$datos['idFichaOrigen'] = 2;

		$this->fichasBD->obtenerDocumentosXFicha($datos,$dt3);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		for ($i = 0; $i < count($dt3->data); $i++)
		{
			//var_dump($dt3->data[$i]);
			//var_dump($datos['idPlantilla']);
			if ($dt3->data[$i]['idplantilla'] == $datos['idPlantilla'])
			{
				$respuesta['estado'] = true;
				$respuesta['mensaje'] = 'Ya se ha generado un documento con esta plantilla';
				$respuesta['codigo'] = '200';
				$respuesta['data'] = $dt3->data[$i]['documentoid'];
				echo json_encode($respuesta);	
				$this->graba_log($respuesta['mensaje']);
				$this->bd->desconectar();
				exit;
			}
		}

		//print_r($datos);
		//Ordenar datos para generar el documento ( Datos de Contrato y Datos Variables)

		//Buscar el rut de empresa
		$this->fichasDatosImportacionBD->obtener($datos,$dt);
		$this->mensajeError = $this->fichasDatosImportacionBD->mensajeError;
		
		if( $dt->leerFila() ){
			
			$dt->data[0]['NombreProceso'] = NOMBRE_PROCESO_AUTOMATICO;
			$dt->data[0]['idPlantilla'] = $datos['idplantilla'];
			$dt->data[0]['idFirma'] = $datos['idFirma'];

			//Si esta configurado el automatico, va a buscar el procesos adecuado
			if( GENERACION_AUTOMATICA_PROCESO == 1 ){

				//Buscar el proceso que corresponde
				$this->procesosBD->obtenerProcesoFlujo($dt->data[0],$dt_proceso);
				$this->mensajeError = $this->procesosBD->mensajeError;

				if( $dt_proceso->leerFila() ){
					$dt->data[0]['idProceso'] = $dt_proceso->obtenerItem('idProceso');
				}
			}

			//Sino , tomara el que reciba del request
			//CONFIGURAR OPCION DE ENVIO DE PROCESO
		
			//Ordenar firmantes
			$dt->data[0]['Firmantes_Emp'] = array( $datos['RutFirmante']);

		}
				
		//Generar documento
		$generar = new generar();

		$respuesta = array();
		$respuesta = $generar->GenerarDocumento($dt->data[0]);

		if( $respuesta['estado'] ){
			
			$idDocumento = '';
			$idDocumento = $respuesta['data'];

			$datos["documentoid"] = $idDocumento;
			$datos["idfichaorigen"] = 2; //Generacion de Documentos

			$this->fichasBD->agregarDocumentoFichasDocumentos($datos,$dt);
			$this->mensajeError.=$this->fichasBD->mensajeError;

			//Si se genero correctamente el docuemento actualizar estado

			$datos['idFichaOrigen'] = 2; //Documentos generados 
			$this->fichasBD->obtenerDocumentosXFicha($datos,$dt);
			$this->mensajeError.=$this->fichasBD->mensajeError;

			$cantidad_generados = 0;
			$cantidad_generados = count($dt->data);

			//Buscar datos de los documentos a  generar
			$this->fichasBD->obtenerDocumentosGenerados($datos,$dt2);
			$this->mensajeError.=$this->fichasBD->mensajeError;
			$cantidad_obligatorio = 0;
			$cantidad_obligatorio = count($dt2->data);

			if( $cantidad_generados == 0 ){
				$datos['idestado'] = 3; //Pendiente de selección de Representante           
			}
			if( $cantidad_generados == $cantidad_obligatorio ){
				$datos['idestado'] = 6; //Finalizado
			}
			if( $cantidad_generados > 0 && $cantidad_generados < $cantidad_obligatorio ){
				$datos['idestado'] = 4; //Pendiente documento por generar                   
			}

			//Actualizar estado de generacion
			$this->fichasBD->modificarEstado($datos);
			$this->mensajeError .= $this->fichasBD->mensajeError;

			echo json_encode($respuesta);	
		}else{
			$datos['estado'] = $respuesta['estado'];
			$datos['mensaje'] = $respuesta['mensaje'];
			$datos['codigo'] = $respuesta['codigo'];
			echo json_encode($respuesta);	
			$this->graba_log($datos['mensaje']);
		}
		
		$this->bd->desconectar();
		exit;
		
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/FichaContratacion_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	
}
?>