<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once('includes/Paginas.php');
// y la seguridad
include_once('includes/Seguridad.php');

include_once('dec5.php');
// creamos la instacia de esta clase
$page = new pruebadec();

class pruebadec {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para juntar los mensajes de error
	private $mensajeError='';

	public $seguridad;

	// funcion contructora, al instanciar
	function __construct()
	{

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

		$this->dec5 = new dec5();
		
		
		//esto son los parametros para la primera firma, se deben entregar todos los parametros relacionados con la subida del documento
		$signers_roles 		= 		array("12382466-0","Representantes","17259567-7","RRHH");
		$signers_institutions=		array("12382466-0","RUBRIKA","17259567-7","RUBRIKA");
		$signers_emails		=		array("dec@rubrika.cl","dec@rubrika.cl","dec@rubrika.cl","any");
		$signers_ruts		=		array("12382466-0","17028706-1","17259567-7","Any");
		$signers_type		=		array("0","0","0","5");
		$signers_order		=		array("1","1","2","4");
		$signers_notify		=		array("0","0","0","0");	
		
		$archivoaux = "";
		$archivoaux = file_get_contents("prueba.pdf");
		$datos["file"] 					= base64_encode($archivoaux);//el archivo en base 64
		$datos["signers_roles"]			= $signers_roles;
		$datos["signers_institutions"]	= $signers_institutions;
		$datos["signers_emails"]		= $signers_emails;
		$datos["signers_ruts"]			= $signers_ruts;
		$datos["signers_type"]			= $signers_type;
		$datos["signers_order"]			= $signers_order;
		$datos["signers_notify"]		= $signers_notify;
		//fin
		


	
		//estos son los parametros para las siguientes firmas si es que hay mas de un firmante
		$datos["user_institution"]		= "12382466-0";			//usuario de la persona que firma
		$datos["user_rut"]				= "12382466-0";			//usuario de la persona que firma
		$datos["user_pin"]				= "@Rubrika1@";			//clave del usuario que firma
		$datos["user_role"]				= "12382466-0";	// el rol del usuario que firma
		$datos["code"]					= ""; //esto corresponde al codigo del documento que viene en las respuesta al subir el documento
		//fin 
		
		$this->dec5->FirmaPin($datos,$dt);
		
		
		if ($this->mensajeError != "")
		{
			print ("ERROR: ".$this->mensajeError);
		}
		print_r($dt);
		exit;		
		

		
		$datos["user_rut"] 	= "12382466-0";
		$datos["extra"] 	= "roles,institutions";
		//$this->dec5->ValidaUsuario($datos,$dt);
		
		$datos["code"] = "CA80000000BF5EACO2";
		$this->dec5->ObtenerDocumento($datos,$dt);


		
		$datos["user_rut"]		= "12382466-0";
		$datos["user_name"]		= "CRISTIAN LING";
		$datos["user_lastname"]	= "SOTO BRAVO";
		$datos["user_birthday"] = "02-09-1973";
		$datos["user_gender"]	= "M";
		$datos["user_phone"]	= "+56995949091";
		$datos["user_email"]	= "cling0209@gmail.com";
		$datos["serial_number"] = "12345";	
		$this->dec5->RegistrarUsuario($datos,$dt);
		
		$this->mensajeError.=$this->dec5->mensajeError;
		if ($this->mensajeError != "")
		{
			print ("ERROR: ".$this->mensajeError);
		}
		print_r($dt);

	}

}
?>