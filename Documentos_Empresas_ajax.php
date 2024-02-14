<?php
//error_reporting(E_ERROR);

include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosBD.php");
include_once("includes/ejecutivosBD.php");
include_once("includes/tiposdocumentosBD.php");

//Opcion del AJAX para el Vista Previa
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $documentosBD;
	private $ejecutivosBD;
	private $tiposdocumentosBD;
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
    	$this->ejecutivosBD = new ejecutivosBD();
    	$this->tiposdocumentosBD = new tiposdocumentosBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->ejecutivosBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		
		$dt = new DataTable();
		$dt1 = new DataTable();

		$datos = $_REQUEST;
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;

		$doc_1 = array();
		$doc_2 = array();
		$doc_3 = array();
		$doc_4 = array();
		$doc_5 = array();
		$doc   = array();
		$doc_firm = 0;
		$doc_firmado = array();

		$this->tiposdocumentosBD->Todos($dt2);
		$this->mensajeError.=$this->tiposdocumentosBD->mensajeError;

		if ( count($dt2->data) > 0 ){
			foreach ($dt2->data as $key => $value) {

				$datos["TipoDocumento"] = $dt2->data[$key]["idTipoDoc"];
				$this->documentosBD->obtenerDocumentosXTipo($datos,$dt);
				$this->mensajeError.=$this->documentosBD->mensajeError;

				if( count($dt->data) > 0 ){

					foreach ($dt->data as $key => $value) {
					
						if( $dt->data[$key]["Estado"] == "Rechazados" ) {
							$recha = $dt->data[$key]["Total"];
						}

						if( $dt->data[$key]["Estado"] == "Proceso" || $dt->data[$key]["Estado"] == "Pendiente"){
							$num += $dt->data[$key]["Total"]; 
						}

						if( $dt->data[$key]["Estado"] == "Firmados" ){
							$firm = $dt->data[$key]["Total"];
						}
					}

					if( $recha > 0 ){
						array_push($doc, $recha);
					}else{
						array_push($doc, 0);
					}

					if( $num > 0 ){
						array_push($doc, $num);
					}else{
						array_push($doc, 0);
					}

					if( $firm > 0 ){
						array_push($doc, $firm);
						$doc_firm += $firm;
					}else{
						array_push($doc, 0);
					}

					
				}else{
					$doc = array (0,0,0); // Firmados, Proceso, Rechazado
				}

				switch($datos["TipoDocumento"]){
					case 1:
						$doc_1 = $doc;
						array_push($doc_firmado, $doc_firm);
						break;
					case 2:
						$doc_2 = $doc;
						array_push($doc_firmado, $doc_firm);
						break;
					case 3:
						$doc_3 = $doc;
						array_push($doc_firmado, $doc_firm);
						break;
					case 4:
						$doc_4 = $doc;
						array_push($doc_firmado, $doc_firm);
						break;
					case 5:
						$doc_5 = $doc;
						array_push($doc_firmado, $doc_firm);
						break;
				}

				$doc = array ();
				$firm = 0;
				$num  = 0;
				$recha = 0;
				$doc_firm = 0;
			}
		}

		$grafico = array();
		$grafico["Contrato_Marco"]        = $doc_1;
		$grafico["Anexos"]                = $doc_2;
		$grafico["Contrato_Arriendo"]     = $doc_3;
		$grafico["Condiciones_Generales"] = $doc_4;
		$grafico["Contrato_Financiero"]   = $doc_5;
		$grafico["Firmados"]              = $doc_firmado;
		
		echo json_encode($grafico); 

		$this->bd->desconectar();
		exit;
	}
}

?>