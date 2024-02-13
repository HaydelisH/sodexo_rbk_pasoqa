<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

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
include_once("firma.php");

//Firma masiva con PIN :   Documentos_firmaMasiva3_ajax.php
//Firma masiva con Huella: Documentos_firmaMasiva6_ajax.php
//Firma masiva con Token : Documentos_firmaMasiva7_ajax.php

// creamos la instacia de esta clase
$page = new documentosdet();

class documentosdet {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $clausulasBD;
	// para el manejo de las tablas
	private $holdingBD;
	
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para asignar el RutEmpresa
	private $RutEmpresa="";
	private $RazonSocial="";
	private $Categoria="";
	private $idClausula = "";
	
	private $opcion="";
	private $opcionicono="";
	private $opcionnivel1="";
	private $opcionnivel2="";
	private $opciondetalle="";
	
	private $nroopcion=5; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;
	
	//Datos para la firma
	private $signers_roles;
	private $signers_ruts;
	private $signers_ruts_sin_guion;
    private $signers_order;	

    private $datos;
	private $signers_institutions;
	private $signers_emails;
	private $signers_type;
	private $signers_notify;	
	private $nombre_archivo;
	private $archivo_codificado;
	private $valor_arr;

	private $dec5;
	private $band = 0;
	private $firma;
	
	private $mensajeRol="";
	private $orden = 0;
	private $session_rbk;
	private $id_rbk;

	// funcion contructora, al instanciar
	function __construct()
	{
		// revisamos si la accion es volver desde el listado principal
		if (isset($_REQUEST["accion"]))
		{
			// si lo es
			if ($_REQUEST["accion"]=="Volver")
			{
				// nos devolvemos al lugar especificado
				header('Location: index.php');
				return;
			}
		}
				// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = new Paginas();
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			// lo agregamos a la pagina
			$this->pagina->agregarDato('mensajeError',$this->mensajeError);

			// mostramos el encabezado
			$this->pagina->imprimirTemplate('templates/encabezado.html');
			$this->pagina->imprimirTemplate('templates/encabezadoFin.html');

			// imprimimos el template
			$this->pagina->imprimirTemplate('templates/puroError.html');
			// Imprimimos el pie
			$this->pagina->imprimirTemplate('templates/piedepagina.html');
			// y nos vamos
			return;
		}

		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) return;

		$this->opcion = "Documento ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Documento</li>";
		
		// instanciamos del manejo de tablas
		$this->documentosdetBD = new documentosdetBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		$this->firmantesBD = new firmantesBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->documentosdetBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			$this->listado();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
	
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_REQUEST["accion"])
		{
			case "VERDOCUMENTO":
				$this->verdocumento();
				break;
			case "APROBAR":
				$this->aprobar();
				break;
			case "RECHAZAR":
				$this->rechazar();
				break;
			case "INICIOFIRMA":
				$this->iniciofirma();
				break;
			case "FIRMAR":
				$this->firmar();
				break;
			case "FIRMATOKEN":
				$this->firmatoken();
				break;
			case "FIRMAHUELLA":
				$this->firmahuella();
				break;
			case "DETALLE":
				$this->listado();
				break;
			case "FIRMA_RBK":
				$this->firmar_rbk();
				break;
		}
	
		// e imprimimos el pie
		$this->imprimirFin();

	}
	
	//Mostrar listado de todas las disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$datos = $_REQUEST;
	
		$this->documentosdetBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$formulario=$dt->data;
		
		$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt2);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
	    $formulario[0]["contratofirmantes"] = $dt2->data;

		$estado = 0;
		$datos["personaid"] = $this->seguridad->usuarioid;

		//para visualizar botones segun estado
		if($dt->leerFila())
		{
			$estado = $dt->obtenerItem("idEstado");
			$datos["idEstado"] = $estado;
		}

		//Firmantes en ese orden
		$this->contratofirmantesBD->ObtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
		
		if($dt3->leerFila())
		{
			$aux = $dt3->obtenerItem("RutFirmante");
		}

		//Estado 8 = Rechazado y Estado 6 = Aprobado
		if ($estado > 1 && $estado < 6 && $aux!="")//si debe cumplir con mas condiciones agregar
		{
			$formulario[0]["firmar"][0]	= "";
		}		

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_formulario.html');
	
	}
		
	private function verdocumento()
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;

		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->documentosdetBD->obtenerb64($datos,$dt); //print_r($datos);
		$this->mensajeError  .= $this->documentosdetBD->mensajeError;
				
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
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_documento.html');				
	}

	private function verdocumento_firmado($idDocumento)
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError=$this->documentosdetBD->mensajeError;
				
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
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_rbk.html');	
		
	}
		
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
	
	private function imprimirFin2()
	{
		include("includes/opciones_fin2.php");
	}
	
	private function aprobar()
	{ 
		$datos = $_REQUEST;	
		$datos["estado"] = 2;
		$this->documentosdetBD->modificar_estado($datos);
		$this->mensajeError=$this->documentosdetBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->listado();
		return;
	}
	
	private function rechazar()
	{ 
		$datos = $_REQUEST;	
		$datos["estado"] = 8; //cambiar a estado de rechazo
		$this->documentosdetBD->modificar_estado($datos);
		$this->mensajeError=$this->documentosdetBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->listado();
		return;
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
	
	private function iniciofirma()
	{  
		
		$dt = new DataTable();
		$dt1 = new DataTable();
		$datos = $_REQUEST;	

		//consulta para deducir tipo de firma pin, token o huella
		$usuarioid = $this->seguridad->usuarioid;
		$datos["personaid"] = $usuarioid;
		
		//Consultar el tipo de firma que tiene asociadael usuario
		$this->documentosdetBD->obtenerTipoFirma($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if($dt1->leerFila())
		{
			$tipofirma = $dt1->obtenerItem("Descripcion");
		}

		if (trim($tipofirma) == "")
		{
			$tipofirma = TIPO_FIRMA_PORDEFECTO;
		}

		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if ($tipofirma == "Pin")
				{
					$this->InicioFirmaPin();
				}
				
				if ($tipofirma == "Huella")
				{
					$this->InicioFirmaHuella();
				}
				
				if ($tipofirma == "Token")
				{
					$this->InicioFirmaToken();
				}

				if($tipofirma == 'Pin o Huella')
				{
					$this->mensajeError = "Debe configurarle al firmante un solo tipo de firma, Pin o Huella ";
					$this->listado();
				}
				break;
			
			default:
				if ($tipofirma == "Pin" || $tipofirma == "Huella" || $tipofirma == 'Pin o Huella')
				{
					$this->InicioFirma_RBK();
				}
				break;
		}

		if($tipofirma == "Manual")
		{	
			$this->mensajeError = "No podra continuar con el proceso de firma electr&oacute;nica, su tipo de firma es Manual ";
			$this->listado();
		}

		if($tipofirma == "No Firma")
		{	
			$this->mensajeError = "Ud. no tiene permiso para realizar ninguna firma";
			$this->listado();
		}
	
	}	
	
	private function InicioFirmaPin()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;

		//Llenar Select de Empresas registradas
		// Buscamos el idCategoria que vamos a asignar
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
				
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
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma.html');			
	}

	private function InicioFirma_RBK()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
				
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
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 

		//Datos de persona
		$datos["personaid"] = $this->seguridad->usuarioid;

		$this->documentosdetBD->obtenerTipoFirma($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( count($dt->data) > 0){

			foreach ($dt->data[0] as $key => $value) {
				if( $key == 'RutUsuario')
					$datos['RutUsuario'] = $value;
				if( $key == 'Descripcion')
					$datos['TipoFirma'] = $value;
			}
		}

		switch( $datos['TipoFirma'] ){
			case 'Pin' : 
				$datos['TipoFirma'] = strtoupper($datos['TipoFirma']);
				break;
			case 'Huella' : 
				$datos['TipoFirma'] = 'FINGERPRINT';
				break;
			case 'Pin o Huella' : 
				$datos['TipoFirma'] = '';
				break;
			//Agregar los tipos de firma necesarios 
		}
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_rbk.html');	
	}
	
	
	private function InicioFirmaToken()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		
		$formulario[0] = $datos;	
		
		$this->documentosdetBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		if($dt->leerFila())
		{
			$formulario[0]["documentos"] = $dt->obtenerItem("DocCode");
		}
		
		//obtener rut firmante 
		$rut = $this->seguridad->usuarioid;
		$formulario[0]["rut"]=$rut;
		
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0]["ruta"] 	= $nombrearchivo;
		
		$this->dec5 = new dec5();
		$this->dec5->ObtenerUrlToken($dt);
		$this->mensajeError.=$this->dec5->mensajeError;
		$formulario[0]["uri"] 			= $dt["url_token"];
		$formulario[0]["institucion"]	= $dt["institucion"];
	
		$formulario[0]["botonfirma"]="";
		
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_token.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	
	private function ProcesoFirmaToken()
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt1 = new DataTable();
		$datos = $_REQUEST;
		$this->orden = 0;
		
		//Buscar el orden de firmante 	
		$usuarioid = strtoupper($this->seguridad->usuarioid);
		$array = array ( "personaid" => $usuarioid,"idDocumento"=>$datos["idDocumento"]);

		$this->documentosdetBD->obtenerTipoFirma($array,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		// Si tipofirma es vacio, se asume pin
		if($dt1->leerFila())
		{
			$this->orden = $dt1->obtenerItem("Orden");
		}

		$formulario[0] = $datos;	
	
		//evalua mensaje según respuesta en proceso de firma con token
		if ($datos["status"] != "200")
		{
			$this->mensajeError.= $datos["message"];
			$formulario[0]["botonfirma"]="";
		}
		else
		{
			$this->mensajeOK = $datos["message"];
			
			//vamos a buscar el documento para actualizar con la firma con token
			$datos["code"] = $datos["documentos"];
			$this->dec5 = new dec5();
			$this->dec5->ObtenerDocumento($datos,$dt);

			if ($dt["status"] == "200")
			{
				$_REQUEST["DocCode"] 	= $dt["result"][0]["code"];
				$_REQUEST["documento"]	= $dt["result"][0]["file"];
				
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
				$_REQUEST["RutFirmante"] = $this->seguridad->usuarioid;
				
				$this->actualizaDocumento();
				$this->actualizarFirma();
				$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			}else{
				$this->mensajeError .= $dt["message"];
				$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			}
		}
			
		$nombrearchivo = $this->ObtenerDocBase64();
		$formulario[0]["ruta"] 	= $nombrearchivo;
		
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_token.html');
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	private function firmatoken()
	{
		//si todo ok en la firma, mostramos el inicio para que nos muestre el  documento con el ladrillo
		if ($datos["accion"] == "FIRMATOKEN")
		{
			$this->InicioFirmaToken();
		}
		else
		{
			$this->ProcesoFirmaToken();
		}
	}

	private function InicioFirmaHuella()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $nombrearchivo;
		
		$rut = $this->seguridad->usuarioid;
		$formulario[0]["rut"]=$rut;
		
		$formulario[0]["botonfirma"]="";

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_huella.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior		
		
	}
	
	private function ProcesoFirmaHuella()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$this->band = 0;
		$formulario[0] = $datos;	
		
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
					$_REQUEST["DocCode"] 	= $dt["result"][0]["code"];
					$_REQUEST["documento"]	= $dt["result"][0]["file"];
			
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

					$this->actualizaDocumento();
				  	$this->actualizarFirma();
				  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				  	$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
				}
				else
				{
					$formulario[0]["botonfirma"] = "";
					
					if ($dt["result"]["code"] != "")
					{
						$_REQUEST["DocCode"] = $dt["result"]["code"];
						$this->actualizaDocumento();

						$this->buscarDatosDec($dt["result"]["code"]);
					}
				}
			}
		}
		else
		{
			$formulario[0]["botonfirma"]="";
		}
				
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0]["ruta"] 	= $nombrearchivo;
	
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_huella.html');
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	private function firmahuella()
	{
		$datos = $_REQUEST;
		//si todo ok en la firma, mostramos el inicio para que nos muestre el documento con el ladrillo
		if ($datos["accion"] == "FIRMAHUELLA")
		{
			$this->ProcesoFirmaHuella();
		}
		else
		{
			$this->InicioFirmaHuella();
		}
	}
	
	private function PreparaFirmaHuella()
	{	
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

	private function TieneUnaFirma()
	{	
		//si ya tiene una firma devuelve true
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes
		$datos = $_REQUEST;

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Asignamos el RUT del usuario en sesion	
		$datos["personaid"] 		= $this->seguridad->usuarioid;
		$datos["RutFirmante"]	= $this->seguridad->usuarioid;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Cantidad de firmantes que no han firmado
	    $count = count($dt1->data);	

		//si el total de firmas y la cantidad de firmantes es igual, quiere decir que nadie ha firmado
		if( $count == $dt->data[0]["total"])
		{
			return false;
		}
		return true;
	}
	
	private function ObtenerDocBase64()
	{
		$dt = new DataTable();
		$datos = $_REQUEST;

		//vamos a buscar el documento que debe contener la firma adicional del token
		$this->documentosdetBD->obtenerb64($datos,$dt);//print_r($dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		$nombrearchivo 	= "";
		$nomarchtmp		= "";
		$extension		= "";
		$archivob64		= "";
		
		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo = $nomarchtmp.".".$extension;
				
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 

		return $ruta.$nombrearchivo;
	}
	
	//Accion de Firmar 
	private function firmar(){

		//Recibir $_REQUEST["idContrato"]

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes
		$datos = $_REQUEST;

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Asignamos el RUT del usuario en sesion	
		$datos["personaid"] = $this->seguridad->usuarioid;
		$datos["RutFirmante"] = $this->seguridad->usuarioid;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Cantidad de firmantes que no han firmado
	    $count = count($dt1->data);
		
		//Si es la primera vez firma
		if( $count == $dt->data[0]["total"]){

			//Actualizamos
			$this->band = 0; 
				
			//Buscamos los datos necesarios
			$this->cargarDocumento();
			
			//quiere decir que hubo algún tipo de error al crear rol al cargar el documento
			if ($this->mensajeRol != "")
			{
				$this->mensajeError.= $this->mensajeRol;
				$this->iniciofirma();
				return;
			}

			if ( $this->datos["file"] != "" ){

				//Voy a buscar firmantes
				$this->cargarFirmante();

				//Me voy a firmar
			 	$this->dec5 = new dec5();
		
			 	//Firma PIN
				$this->dec5->FirmaPin($this->datos,$dt);
				$this->mensajeError.=$this->dec5->mensajeError;
				if ($this->mensajeError != "")
				{
					//$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					$this->iniciofirma();
				}

				//Asignamos variables
				if( $dt["result"][0]["code"] ){
					$_REQUEST["DocCode"] = $dt["result"][0]["code"];
					$_REQUEST["documento"] = $dt["result"][0]["file"];

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
				}
				//Si la firma fue todo Ok
				if( ($dt["status"] == 200) && ($dt["message"] == "Success") ) {
				    //Actualizar en la BD
					$this->actualizaDocumento();
				  	$this->actualizarFirma();
				  	$this->envioGestor($datos['idDocumento']);
				  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				  	$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
				  	$this->verdocumento();
				}else{
					if( $dt["status"] == 400 ){
						//Si trae el codigo del documento, se subio correctamente
						if($dt["result"]["code"] != "" ){ 

							//Actualizar en la BD
							$_REQUEST["DocCode"] = $dt["result"]["code"];
							$this->actualizaDocumento();
			
							$this->buscarDatosDec($dt["result"]["code"]);
						}else{
							//Posiblemente no se subio el documento
							$this->mensajeError .= "No se pudo completar la carga del Documento";
							$this->pagina->agregarDato("mensajeError",$this->mensajeError);
						}
					}
				}
			}
		}else{
			//Actualizamos 
			$this->band = 1;
			
			//Buscamos los datos necesarios
		 	$this->cargarFirmante();

		 	//Me voy a firmar
		 	$this->dec5 = new dec5();
	
		 	//Firma PIN
			$this->dec5->FirmaPin($this->datos,$dt);
			$this->mensajeError.=$this->dec5->mensajeError;

			if ($this->mensajeError != "")
			{
				//print ("ERROR: ".$this->mensajeError);
				//$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				$this->iniciofirma();
			}

			//Asignamos variables
			$_REQUEST["DocCode"] = $dt["result"][0]["code"];
			$_REQUEST["documento"] = $dt["result"][0]["file"];
			
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
				//$this->mensajeError = 'Error en la fecha de firma del firmante '.$this->datos['user_rut'];
				//$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				$ejemplo = date("d-m-Y H:i:s");
			}
			$_REQUEST["FechaFirma"] = $ejemplo; 

			//Si todo fue bien
			if( ($dt["status"] == 200) && ($dt["message"] == "Success") ) {
			    //Actualizar en la BD
				$this->actualizaDocumento();
			  	$this->actualizarFirma();
			  	$this->envioGestor($datos['idDocumento']);
			  	$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
			  	$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			  	$this->verdocumento();
			}
			else{
				if( $dt["status"] == 400 ){
					if($dt["result"]["code"] != "" ){ 
						//Actualizar en la BD
						$_REQUEST["DocCode"] = $dt["result"]["code"];
						$this->actualizaDocumento();

						$this->buscarDatosDec($dt["result"]["code"]);
					}
					$this->mensajeError .= $dt["message"];
				    $this->pagina->agregarDato("mensajeError",$this->mensajeError);
				}
			}
			
		}//Fin del Else Grande
	}

	//Accion de completar los datos del Firmante 
	private function cargarFirmante(){

		//Recibir $datos["idContrato"], $datos["personaid"], $this->firma = tipo de firma del firmante

		$datos = $_REQUEST;

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

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
				$cargo = $dt1->data[$i]["Cargo"];
				$persona = $firmante;
				$firmado = $dt1->data[$i]["Firmado"];
				$firmante = $firmante;

				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario'){
					array_push($this->signers_roles, "Representantes");
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 ){
					array_push($this->signers_roles, $persona);
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				
				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario'){
					array_push($this->signers_roles, "Notarios");
					if( strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Notarios";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				
				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = strtoupper($this->seguridad->usuarioid);//usuario de la persona que firma
			
			//Quitar el guión de los ruts 
			$rut_sin_guion = '';
			$this->quitarGuiondelRut(strtoupper($this->seguridad->usuarioid),$rut_sin_guion);
			$this->datos["user_rut_sin_guion"] = $rut_sin_guion;

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

	private function cargarDocumento(){

		//Recibir $datos["idContrato"]

		//Variables para subida del Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento

	    $datos = $_REQUEST;
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_ruts_sin_guion = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		$this->signers_type_sign = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {
				
				$firmante = strtoupper($dt1->data[$i]["personaid"]);

				//valida datos dec5 y crea roles
				$this->ProcesoRolFirmante($firmante);
				if ($this->mensajeRol != "")
				{
					return;
				}
				
				//Quitar el guión de los ruts 
				$rut_sin_guion = '';
				$this->quitarGuiondelRut($firmante,$rut_sin_guion);

				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_ruts_sin_guion,$rut_sin_guion);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");

				$tipofirma = $dt1->data[$i]["TipoFirma"];

				switch( $tipofirma ){
					case 'Pin' : 
						$tipofirma = strtoupper($tipofirma);
						break;
					case 'Huella' : 
						$tipofirma = 'FINGERPRINT';
						break;
					case 'Pin o Huella' : 
						$tipofirma = '';
						break;
					//Agregar los tipos de firma necesarios 
				}

				array_push($this->signers_type_sign, $tipofirma);

				$estado = $dt1->data[$i]["Nombre"];
				$cargo = $dt1->data[$i]["Cargo"];
				
				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}

				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_ruts_sin_guion"] = $this->signers_ruts_sin_guion;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			$this->datos["signers_type_sign"] = $this->signers_type_sign;
			$this->datos["type_doc"] = $dt2->data[0]["NombreTipoDoc"];
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
		}
	}

	private function quitarGuiondelRut($rut, &$rut_sin_guion){

		$resultado = explode('-',$rut);
		$rut_sin_guion = $resultado[0].$resultado[1];
		return $rut_sin_guion;
	}

	//Actualiza firma en BD
	public function actualizarFirma(){

		$datos = $_REQUEST;
		
		if( $datos["FechaFirma"] == ''){
			$datos["FechaFirma"] = date("d-m-Y H:i:s");
		}

		//Actualiza el estado de firma 
		$this->documentosdetBD->agregarFirma($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Actualiza el documento firmado
		if( $datos['documento'] != '' ){
			$this->documentosdetBD->modificarDocumento($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
		}
	}

	//Actualiza firma en BD
	private function actualizarFirmaRBK($idDocumento, $firmante, $fechafirma, $documento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;
		$datos['RutFirmante'] = $firmante;
		$datos['FechaFirma'] = $fechafirma;
		$datos['documento'] = $documento;

		if( $datos["FechaFirma"] == ''){
			$datos["FechaFirma"] = date("d-m-Y H:i:s");
		}
		//Actualiza el estado de firma 
		$this->documentosdetBD->agregarFirma($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( $this->mensajeError != '' ){
			return false;
		}

		//Actualiza el documento firmado
		if( $datos['documento'] != '' ){

			$this->documentosdetBD->modificarDocumento($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			if( $this->mensajeError != '' ){
				return false;
			}
		}
	    return true;
	}

	//Actualiza Documento en la BD
	public function actualizaDocumento(){

		$datos = $_REQUEST;

		if ( $datos['DocCode'] != '' ){
			//Actualizar el codigo del documento
			$this->documentosdetBD->modificar($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		}
	}

	//Actualiza Documento en la BD
	private function actualizaDocumentoRBK($idDocumento, $DocCode){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;
		$datos['DocCode'] = $DocCode;

		if ( $datos['DocCode'] != '' ){
			//Actualizar el codigo del documento
			$this->documentosdetBD->modificar($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			if( $this->mensajeError != '' )
				return false;
		}

		return true;
	}

	//Consultar fsi firmo el documento 
	private function buscarDatosDec($docCode){

		$datos = $_REQUEST;

		//Consulto los datos del documento en Acepta 
		$datos["code"] = $docCode;
		$this->dec5 = new dec5();
		$this->dec5->ObtenerDocumento($datos,$dt);

		if ($dt["status"] == "200"){

			$_REQUEST["DocCode"] 	= $dt["result"][0]["code"];
			$_REQUEST["documento"]	= $dt["result"][0]["file"];

			$res = array();
			$res = $dt["result"][0]["signers"];

			foreach ($res as $key => $value) {
				foreach ($res[$key] as $key_1 => $value_1) {
				
					 if( $res[$key]['rut'] == $this->datos['user_rut'] && $res[$key]['order'] == $this->orden){
					 	$fecha_actual = $res[$key]['date'];
					 }
				}
				$datos['RutFirmante'] = $res[$key]['rut'];
				$datos['orden'] = $res[$key]['order'];
				$datos['FechaFirma'] = $res[$key]['date'];

				//Agrega datos de firma, segun dec
				$this->documentosdetBD->agregarFirmaDec($datos);
				$this->mensajeError .= $this->documentosdetBD->mensajeError;
			}
		
			$ejemplo = ''; 
			if( $fecha_actual != '' ){ //Si tiene fecha de firma

				$ejemplo = str_replace('/','-', $fecha_actual); 
				$_REQUEST["FechaFirma"] = $ejemplo; 
				$_REQUEST["RutFirmante"] = $this->seguridad->usuarioid;
				
				$this->actualizaDocumento();
				$this->actualizarFirma();

				$this->mensajeError .= "Este Documento ya fue firmado por Ud.";
				$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			  	$this->verdocumento();
			}
			
		}else{//Si geenro error en la consulta del documento
			$this->mensajeError .= $dt["message"];
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		}
	}

	private function envioGestor($idDocumento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$estado = $dt->ObtenerItem("idEstado");
			}

			if( $estado == 6 ){ //Si esta firmado 
				$this->documentosdetBD->agregarGestor($datos);
				$this->mensajeError .= $this->documentosdetBD->mensajeError;

				if( $this->mensajeError == '' )
					return true;
			}
		}
		return false;

	}

	private function firmar_rbk(){

		//Variables para subida del Documento
		$dt = new DataTable(); 

		$datos = $_REQUEST;

		if( !$this->TieneUnaFirma() ){

			//Actualizamos
			$this->band = 0; 

			//Buscamos los datos necesarios
			$this->cargarDocumento();

			if ( $this->datos["file"] != "" ){

				$this->datos['sessionid'] = $datos['sessionid'];

				$firma_rbk = new firma();
				$firma_rbk->prepararDatosDocumento($this->datos, $dt);

				if( $this->mensajeError != '' ){
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				}

				if ( $this->buscarDatosDocumentosRBK($dt) ){

					if( $this->ejecutarFirma() ){

						if ( $this->envioGestor($datos['idDocumento'])) {
					  		//OK
						}else{
							$this->mensajeError = 'Ha ocurrido un error en el envio del documento al Gestor';
							$this->pagina->agregarDato("mensajeError",$this->mensajeError);
						}

						$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
						$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
					}
					else{
						$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					}
				}
			}
		}else{
			
			//Actualizamos
			$this->band = 1; 

			if( $this->ejecutarFirma() ){
				if ( $this->envioGestor($datos['idDocumento'])) {
			  		//OK
				}else{
					$this->mensajeError = 'Ha ocurrido un error en el envio del documento al Gestor';
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				}

				$this->mensajeOK = "Su firma ha sido registrada con &eacute;xito";
				$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

			}else{
				$this->pagina->agregarDato("mensajeError",$this->mensajeError);
			}

		}
		$this->verdocumento();
	}
				
	private function ejecutarFirma(){	

		//Variables para subida del Documento
		$dt = new DataTable(); 

		$datos = $_REQUEST;
					
		$this->cargarFirmante();

		if( $this->band == 1 ){

			$this->buscarDatosDeSession($datos['idDocumento']);

			if( $this->mensajeError != '' ){
				return false;
			}
		}

		$datos['user_rut'] = $this->datos['user_rut_sin_guion'];
		$datos['session_doccode'] = $this->session_rbk;

		$firma_rbk = new firma();
		$firma_rbk->prepararDatosparaFirmar($datos, $dt);

		if( $this->mensajeError != '' ){
			return false;
		}

		$this->valor_arr = "";
		$this->obtener_dato_arr($dt,"timestamp");
		
		if ($this->valor_arr != "")
		{
			$fechafirma = $this->valor_arr;	
		}
		$code = $dt['status']['status']['code'];

		if ( $fechafirma != '' && $code == 200 ){

			//Si firmo correctamente 
			$datos["id"] = $this->id_rbk;
			$datos['sessionId'] = $this->session_rbk;

			$firma_rbk->DescargarDocumento($datos,$dt);
			$this->mensajeError.=$this->firma->mensajeError;
			
			if ($this->mensajeError == "")
			{
				$this->valor_arr = "";
				$this->obtener_dato_arr($dt,"data");
				
				if ($this->valor_arr != "")
				{
					$documento = $this->valor_arr;	
				}

				$firmante = $this->datos['user_rut'];
				$id = $datos['idDocumento'];
				$this->convertirFecha($fechafirma, $ff);
	
				if ( $this->actualizarFirmaRBK($id,$firmante,$ff, $documento ) ){
					return true;
				}
			}
		}
		return false;
	}

	//Actualizar documento de RBK 
	private function buscarDatosDocumentosRBK($dt){

		$datos = $_REQUEST;

		//Actualizar datos del documento
		$this->valor_arr = "";
		$this->obtener_dato_arr($dt,"sessionId");

		if ($this->valor_arr != "")
		{
			$session = '';
			$id = '';
			$session = $this->valor_arr;
			$id = $dt['data']['digitalSignature']['documents'][0]['id'];
			$doccode = $session.SEPARADOR_DOCCODE.$id;
		
			$this->session_rbk = $session;
			$this->id_rbk = $id;

			if ( $this->actualizaDocumentoRBK($datos['idDocumento'],$doccode) )
				return true;
		}
		return false;
	}

	//Buscar datos dentro de la matriz 
	private function obtener_dato_arr($matriz,$variable)
	{
		if( count($matriz) > 0 ){
			foreach($matriz as $key=>$value)
			{
				if (is_array($value))
				{
					$this->obtener_dato_arr($value,$variable);
				}
				else
				{  
					if ($key == $variable)
					{	
						$this->valor_arr = $value;
						break;
					}
				
				}
			}
		}
				
	} 

	//Convertir formato de fecha de timestamp
	private function convertirFecha($fecha_entrada, &$fecha_salida){

		//fecha de entrada : "2019-04-26T21:32:17.355Z"
		$res = array();
		$res = explode('T', $fecha_entrada );

		$fecha = $res[0].' '.substr($res[1],0,8);
		$fecha_salida = new DateTime($fecha);
		$fecha_salida = $fecha_salida->format('Y-m-d H:i:s');

		return $fecha_salida;
	}

	//Buscar datos del documentos RBK guadado
	private function buscarDatosDeSession($idDocumento){

		$datos = $_REQUEST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$doccode = $dt->ObtenerItem("DocCode");
			}

			if( $doccode != '' ){
				$codigos = explode(SEPARADOR_DOCCODE,$doccode);
				
				if( count($codigos) < 2 ){
					$this->mensajeError = "El Documento no se ha subido a este gestor de firma";
					return false;
				}

				$this->session_rbk = $codigos[0];
				$this->id_rbk = $codigos[1];

				return true;
			}else{
				$this->mensajeError = "El Documento no se ha subido correctamente";
				return false;
			}
		}
	}
}
?>
