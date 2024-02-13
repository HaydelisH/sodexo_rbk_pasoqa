<?php	

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/accesoxusuarioBD.php");
// creamos la instacia de esta clase

/*	date_default_timezone_set('America/Santiago');
	$nomarchivo = 'logs/ajax'.@date("Ymd").'.TXT';
	$ar=fopen($nomarchivo,"a") or
	   die("Problemas en la creacion");
	fputs($ar,@date("H:i:s")." "."llego");*/
		
	// creamos una instacia de la base de datos
	$pagina = new Paginas();
	$bd = new ObjetoBD();

	// nos conectamos a la base de datos
	if (!$bd->conectar())
	{
		exit;
	}
	
	// creamos la seguridad
	$seguridad = new Seguridad($pagina,$bd);
	// si no funciona hay que logearse
	if (!$seguridad->sesionar()) return;
		
	// instanciamos del manejo de tablas
	$accesoxusuarioBD = new accesoxusuarioBD();
	// si se pudo abrir entonces usamos la conecion en nuestras tablas
	$conecc = $bd->obtenerConexion();
	$accesoxusuarioBD->usarConexion($conecc);
	
	$dt = new DataTable();
	// pedimos el listado
	
	$datos = $_POST;
	
	if ($datos["accion"] == "graba")
	{
		$accesoxusuarioBD->GrabaEmpresa($datos,$dt);
	}

	if ($datos["accion"] == "elimina")
	{
		$accesoxusuarioBD->EliminaEmpresa($datos,$dt);
	}
	$mensajeError=$accesoxusuarioBD->mensajeError;
	
	if ($mensajeError == ""){
		print "OK";
	}	
	

?>