<?php

include_once('includes/Seguridad.php');
include_once("includes/firmasdocBD.php");
include_once("includes/docvigentesBD.php");

//Opcion del AJAX para buscar todos los documentos pendientes por firma 
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $firmasdocBD;
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
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}

		$this->firmasdocBD	 = new firmasdocBD();
		$this->docvigentesBD	 = new docvigentesBD();

		$conecc = $this->bd->obtenerConexion();
		$this->firmasdocBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_POST;
		$resultado = array();
	
		$datos["pagina"]="1";
		$datos["decuantos"]="100";
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["Firmante"] = $datos["usuarioid"];
		$datos["idTipoFirma"] = 2;
		
		$this->docvigentesBD->totalMisDocumentos($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["decuantos"]=round($dt->data[0]["totalreg"]);
		// print_r($datos);
		
		//Array ( [usuarioid] => 13559051-7 [ptipousuarioid] => 3 [formulario] => idDocumento= [fichaid] => [idTipoDoc] => 0 [idProceso] => 0 [idEstado] => 2 [accion] => BUSCAR [session] => [pagina] => 1 [decuantos] => 4 [Firmante] => 13559051-7 [idTipoFirma] => 2 ) [20,21,23,24]
		
	/*	$this->definicion11["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion11["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");*/
				
		if($this->docvigentesBD->listadoMisDocumentosSoloidDocumento($datos,$dt)){
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

