<?php

include_once('includes/Seguridad.php');
include_once("includes/docvigentesBD.php");

//Opcion del AJAX para buscar todos los documentos pendientes por firma 
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
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

		$this->docvigentesBD	 = new docvigentesBD();

		$conecc = $this->bd->obtenerConexion();
		$this->docvigentesBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_REQUEST;
		$resultado = array();
		
		$datos["pagina"]="1";
		$datos["decuantos"]=10;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["idEstado"] = 1; //En espera de aprobacion
		//$datos["idTipoFirma"] = 2; //Electronico

		$this->docvigentesBD->totalPorAprobar($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["decuantos"]=round($dt->data[0]["totalreg"]);
		
		if ( $this->docvigentesBD->listadoSoloDocumentosPorAprobar($datos,$dt)){
			if ( count($dt->data)> 0) {
				foreach ($dt->data as $key => $value) {
					foreach($dt->data[$key] as $key_1 =>$value_1 ){
						if( is_numeric($key_1) ){
							array_push($resultado, $value_1);
						}
					}
				}
			}else{
				echo 0;
			}
			echo json_encode($resultado);
		}else{
			echo $this->docvigentesBD->mensajeError;
		}

		$this->bd->desconectar();
		exit;
	}
}

?>