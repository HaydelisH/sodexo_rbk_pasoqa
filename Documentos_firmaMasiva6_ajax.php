<?php

error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosdetBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/contratofirmantesBD.php");
include_once("includes/firmantesBD.php");

//Firma DEC5
include_once('dec5.php');

//Opcion del AJAX para la firma con huella
$page = new documentos();

class documentos {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	//Mensaje de error si existe 
	private $mensajeError="";

	//Datos para la firma
	private $signers_roles;
	private $signers_ruts;
    private $signers_order;	

    private $datos;
	private $signers_institutions;
	private $signers_emails;
	private $signers_type;
	private $signers_notify;	
	private $nombre_archivo;
	private $archivo_codificado;

	private $dec5;
	private $band = 0;
	private $firma;
	
	private $mensajeRol="";
	private $orden = 0;

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
		$this->documentosdetBD = new documentosdetBD();
		$this->firmantesBD = new firmantesBD();

		$conecc = $this->bd->obtenerConexion();
		$this->documentosdetBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);

		$dt = new DataTable();
		$datos = $_REQUEST;

		$this->firmar();

		if( $this->mensajeError ){
			$this->graba_log('idDocumento : '.$datos["idDocumento"].'-Usuario: '.$datos['usuarioid'].'-Tipo de firma: Huella - Error : '.$this->mensajeError);
			echo $this->mensajeError;
		}

		if( $this->mensajeOK ){	
			$this->graba_log('idDocumento : '.$datos["idDocumento"].'-Usuario: '.$datos['usuarioid'].'-Tipo de firma: Huella - Ok : '.$this->mensajeOK);
			echo '0';
		}

		$this->bd->desconectar();
		exit;
	}

	//Accion de Firmar 
	private function firmar(){

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];
		$datos['RutFirmante'] = $datos['usuarioid'];
		
		if ($datos["auditoria"] != "")
		{
			$this->PreparaFirmaHuella();
			
			if ($this->mensajeError == "")
			{
				$this->datos["audit"] = $datos["auditoria"];
				$this->dec5 = new dec5();
				$this->dec5->FirmaHuella($this->datos,$dt);
				$this->mensajeError.=$this->dec5->mensajeError;

				if($dt["status"] == 200)
				{
					$datos["DocCode"] 	= $dt["result"][0]["code"];
					$datos["documento"]	= $dt["result"][0]["file"];

					//Fecha de firma de DEC
					$fecha_actual = '';

					$res = array();
					$res = $dt["result"][0]["signers"];

					foreach ($res as $key => $value) {
						foreach ($res[$key] as $key_1 => $value_1) {
						
							 if( $res[$key]['rut'] == $this->datos['user_rut'] && $res[$key]['order'] == $this->orden){
							 	$fecha_actual = $res[$key]['date'];
							 }
						}
					}
					
					$ejemplo = ''; 
					if( $fecha_actual != '' ){ //Si tiene fecha de firma
						$ejemplo = str_replace('/','-', $fecha_actual); 
					}else{
						$ejemplo = date("d-m-Y H:i:s");
					}
					$_REQUEST["FechaFirma"] = $ejemplo; 

				  	 //Actualizar en la BD
					$this->actualizaDocumento($datos["DocCode"]);
				  	$this->actualizarFirma($datos["FechaFirma"], $datos["documento"], $datos["RutFirmante"]);
				  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				  	$this->verdocumento();
				  	return;
				}
				else
				{
					$formulario[0]["botonfirma"] = "";
					
					if ($dt["result"]["code"] != "")
					{
						$datos["DocCode"] = $dt["result"]["code"];
						//Actualizar en la BD
						$this->actualizaDocumento($datos["DocCode"]);
					}
				}
			}
		}
	}

	//Accion de completar los datos del Firmante 
	private function cargarFirmante(){

		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];

		//Recibir $datos["idContrato"], $_REQUEST["personaid"], $this->firma = tipo de firma del firmante

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($datos, $dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
        if ( $this->mensajeError ) return;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		
		if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}
		if( $persona != "" ){
			$num = 0;
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$estado = $dt1->data[$i]["Nombre"];
				$persona = strtoupper($dt1->data[$i]["personaid"]);
				$firmado = $dt1->data[$i]["Firmado"];

				//Si es Cliente
				//echo mb_substr_count($estado, "Cliente")."<br/>";
				if ( mb_substr_count($estado, "Cliente") >  0 ){
					array_push($this->signers_roles, "Representantes_2");
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0 ){
						$this->datos["user_role"] = "Representantes_2";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				//Si es Empresa
				//echo mb_substr_count($estado, "Empresa")."<br/>";
				if ( mb_substr_count($estado, "Empresa") >  0 ){
					array_push($this->signers_roles, "Representantes");
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Representantes";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				//Si es Aval
				//echo mb_substr_count($estado, "Aval")."<br/>";
				if ( mb_substr_count($estado, "Aval") >  0 ){
					array_push($this->signers_roles, $persona);
					if( strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $persona;// el rol del usuario que firma
						$this->datos["user_institution"] = $persona;
						$num++;
					}
				}
				//Si es Notario
				//echo mb_substr_count($estado, "Notario")."<br/>";
				if ( mb_substr_count($estado, "Notario") >  0 ){
					array_push($this->signers_roles, "Notarios");
					if( strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Notarios";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				
				
				array_push($this->signers_ruts, strtoupper($dt1->data[$i]["personaid"]));
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = strtoupper($this->seguridad->usuarioid);//usuario de la persona que firma

			$usuarioid = strtoupper($this->seguridad->usuarioid);
			$array = array ( "personaid" => $usuarioid,"idDocumento"=>$datos["idDocumento"]);

			//Consultar el tipo de firma que tiene asociadael usuario
			$this->documentosdetBD->obtenerTipoFirma($array,$dt4);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;

			// Si tipofirma es vacio, se asume pin
			$tipofirma = "Pin"; 
			$orden = 0;
			if($dt4->leerFila())
			{
				$tipofirma = $dt4->obtenerItem("Descripcion");
				$orden = $dt4->obtenerItem("Orden");
				$this->orden = $orden;
			}

			if( $tipofirma =="Pin"){
				$this->datos["user_pin"] = $datos["pin"];//clave del usuario que firma
			}
			else{
				$this->datos["user_pin"] = "";
			}

			if( $this->band == 0 ){ //Si es la primera vez 
				$this->datos["code"] = ""; 			}
			else{
				$this->datos["code"] = $dt2->data[0]["DocCode"]; //codigo del documento base64
			}
		}else{
			$this->mensajeError.= "No se pudo obtener los datos del firmante";
		}
	}

	//Accion de completar todos los datos para subir el Documento 
	private function cargarDocumento(){

		//Recibir $_REQUEST["idContrato"]
		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];

		//Variables para subida del Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {
				
				//valida datos dec5 y crea roles
				$this->ProcesoRolFirmante($dt1->data[$i]["personaid"]);
				if ($this->mensajeRol != "")
				{
					return;
				}
				
				array_push($this->signers_ruts, strtoupper($dt1->data[$i]["personaid"]));
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$estado = $dt1->data[$i]["Nombre"];
				
				//Si es Cliente
				if ( mb_substr_count($estado, "Cliente") >  0 )
				{
					array_push($this->signers_roles, "Representantes_2");
					array_push($this->signers_institutions, "RUBRIKA");
				}
				//Si es Empresa
				if ( mb_substr_count($estado, "Empresa") >  0 )
				{
					array_push($this->signers_roles, "Representantes");
					array_push($this->signers_institutions, "RUBRIKA");
				}
				//Si es Notario
				if ( mb_substr_count($estado, "Notario") >  0 )
				{
					array_push($this->signers_roles, "Notarios");
					array_push($this->signers_institutions, "RUBRIKA");
				}

				//Si es Aval
				if ( mb_substr_count($estado, "Aval") >  0 ){
					array_push($this->signers_roles, strtoupper($dt1->data[$i]["personaid"]));
					array_push($this->signers_institutions, strtoupper($dt1->data[$i]["personaid"]) );
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			return;
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
			return;
		}
	}

	//Actualiza firma en BD
	public function actualizarFirma($FechaFirma,$documento,$RutFirmante){

		//Recibir $_REQUEST["idContrato"]
		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];
		if( $FechaFirma == '' ){
			$FechaFirma = date('d-m-Y H:i:s');
		}
		$datos["FechaFirma"] = $FechaFirma;
		$datos["documento"] = $documento;
		$datos["RutFirmante"] = $RutFirmante;
		
		//Actualiza el estado de firma 
		$this->documentosdetBD->agregarFirma($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	    if ( $this->mensajeError ) return;

		//Actualiza el documento firmado
		$this->documentosdetBD->modificarDocumento($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;	
	}

	//Actualiza Documento en la BD
	public function actualizaDocumento($code){

		//Recibir $_REQUEST["idContrato"]
		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];
		$datos["DocCode"] = $code;

		//Actualizar el codigo del documento
		$this->documentosdetBD->modificar($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if ( $this->mensajeError ) return;
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/FirmaMasiva_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	private function verdocumento()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos["idContrato"] = $datos["idDocumento"];

		// Buscamos el idCategoria que vamos a asignar
		$this->documentosdetBD->obtenerb64($datos,$dt); 
		$this->mensajeError=$this->documentosdetBD->mensajeError;
		if( $this->mensajeError ) return;

		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";
		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp.".".$extension;
			
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
				return;
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 		
	}

	private function TieneUnaFirma(){

		//si ya tiene una firma devuelve true
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($_REQUEST,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if( $this->mensajeError ) return;

		//Asignamos el RUT del usuario en sesion	
		$_REQUEST["personaid"] 		= $this->seguridad->usuarioid;
		$_REQUEST["RutFirmante"]	= $this->seguridad->usuarioid;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($_REQUEST, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		if( $this->mensajeError ) return;

		//Cantidad de firmantes que no han firmado
		$count = 0;
	    $count = count($dt1->data);	

		//si el total de firmas y la cantidad de firmantes es igual, quiere decir que nadie ha firmado
		if( $count == $dt->data[0]["total"])
		{
			return false;
		}
		return true;
	}

	private function PreparaFirmaHuella(){	
		//si no tiene firmas debe enviar todos los datos necesarios para subir el documento de lo contrario solo carga los firmantes
		if ((!$this->TieneUnaFirma()) && ($this->mensajeError == ""))
		{	
			$this->cargarDocumento();
			
			if ( $this->datos["file"] != "" && $this->mensajeError == "")
			{
				$this->cargarFirmante();
			}
		}	
		else
		{
			$this->band = 1;//agregamos esta marca para obtener el codigo del documento que identifica en dec5
			$this->cargarFirmante();
		}
	}

	private function ProcesoRolFirmante($firmante)
	{
		
		$datos["firmanteid"] 	= $firmante;
		$this->firmantesBD->obtenerXusuario($datos,$dt);
	
		for ($f = 0; $f < count($dt->data); $f++)
		{
			if ($dt->data[$f]["tienerol"] == 0)
			{
				
				if ($dt->data[$f]["TipoEmpresa"] == 1 ) // Gama 
				{
					$this->CreaRol($firmante,"Representantes");
					$this->CreaRol($firmante,"Representantes_2");
				}
			
				if ($dt->data[$f]["TipoEmpresa"] == 2 ) // Clientes
				{
					$this->CreaRol($firmante,"Representantes_2");
				}

				if ($dt->data[$f]["TipoEmpresa"] == 3 ) // Notario
				{
					$this->CreaRol($firmante,"Notarios");
				}	
				
				if ($this->mensajeRol == "")
				{
					$datos["rutempresa"] = $dt->data[$f]["RutEmpresa"];
					$this->firmantesBD->MarcaRol($datos);
				}
				else
				{
					return;
				}
			 
			}
		}
	}

	private function CreaRol($firmanteid,$rol)
	{  

		$datos["user_rut"] 	= $firmanteid;
		$datos["extra"]		= "roles,institutions,emails";
		$this->dec5 = new dec5();
		$this->dec5->ValidaUsuario($datos,$dt);	
		$this->mensajeRol.=$this->dec5BD->mensajeError;
		if ($dt["status"] != 200)
		{
			$this->mensajeRol.=$this->dec5->mensajeError;
			return;
		}
		
		$existerol	= "N";
		$email 		= "";	
		$email		= $dt["result"][0]["email"];
		
		$cantidad = count($dt["result"][0]["roles"]);
		for ($c = 0; $c < $cantidad + 1; $c++)
		{
			//print ("dec:".$dt["result"][0]["roles"][$c]["role"]." rol par:".$rol."<br>");
			if ($dt["result"][0]["roles"][$c]["role"] == $rol)
			{
				$existerol = "S";
			}
		}
		
		if ($existerol == "N")
		{
			$datos["role"] 	= $rol;
			$datos["email"]	= $email;
			if (!$this->dec5->AgregarRol($datos,$dt))
			{	
				$this->mensajeRol.=$this->dec5->mensajeError;
			}
		}
	
	}


}

?>