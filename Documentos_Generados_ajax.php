<?php
//error_reporting(E_ERROR);

include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosBD.php");
include_once("includes/panelBD.php");

//Opcion del AJAX para el Vista Previa
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosBD;
	private $panelBD;
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
		/*$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}*/

		// instanciamos del manejo de tablas
    	$this->documentosBD = new documentosBD();
    	$this->panelBD = new panelBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->panelBD->usarConexion($conecc);
		
		/**/
		$dt = new DataTable();
		$dt1 = new DataTable();
		$anno = 0;

		$datos = $_REQUEST;
	
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$anno = $datos["anno"];

		$fechaInicio = $anno."-01-01";
		$fechaFin = $anno."-12-01";

		$datos["fechaInicio"] = date("d-m-Y", strtotime($fechaInicio));
		$datos["fechaFin"] = date("d-m-Y", strtotime($fechaFin));

		$this->panelBD->ObtenerAnual($datos,$dt);
		$this->mensajeError.=$this->panelBD->mensajeError;
		
		$doc_p = array();
		$doc_f = array();
		$doc_r = array();
		$doc_t = array();
		$nombre = "";

		for( $i = 0; $i < 12; $i++ ){

			$f = $i + 1;
			$firm = 0;
			$recha = 0;
			$num = 0;
			$totalxmes = 0;

			$aux = array ( "mes" => $f , "anno" => $anno , "RutEjecutivo" => $datos["RutEjecutivo"] , "RutEmpresa" => $datos["RutEmpresa"], "tipousuarioid" => $datos["ptipousuarioid"] );
			$this->documentosBD->obtenerCuantos_2($aux,$dt);
			$this->mensajeError = $this->documentosBD->mensajeError;

	
			if ( count($dt->data) == 0 ){
				array_push($doc_f, 0);
				array_push($doc_p, 0);
				array_push($doc_r, 0);
				array_push($doc_t, 0);
			}
			else{
				foreach ($dt->data as $key => $value) {

					if( $dt->data[$key]["Estado"] == "Firmados" ){
						$firm = $dt->data[$key]["Total"];
					}
	
					if( $dt->data[$key]["Estado"] == "Proceso" || $dt->data[$key]["Estado"] == "Pendiente"){
						$num += $dt->data[$key]["Total"]; 
					}

					if( $dt->data[$key]["Estado"] == "Rechazados" ) {
						$recha = $dt->data[$key]["Total"];
					}
				}

				$totalxmes = $firm + $num + $recha;

				if( $firm > 0 ){
					array_push($doc_f, $firm);
				}else{
					array_push($doc_f, 0);
				}

				if( $num > 0 ){
					array_push($doc_p, $num);
				}else{
					array_push($doc_p, 0);
				}

				if( $recha > 0 ){
					array_push($doc_r, $recha);
				}else{
					array_push($doc_r, 0);
				}

				if( $totalxmes > 0 ){
					array_push($doc_t, $totalxmes);
				}else{
					array_push($doc_t, 0);
				}
			}//Fin del else			
		}

		$grafico["Documentos_P"] = $doc_p;
		$grafico["Documentos_F"] = $doc_f;
		$grafico["Documentos_R"] = $doc_r;
		$grafico["Documentos_T"] = $doc_t;
		//$grafico["anno"] = $anno; 
		
		//print_r($grafico);
		echo json_encode($grafico); 

		$this->bd->desconectar();
		exit;
	}
}

?>