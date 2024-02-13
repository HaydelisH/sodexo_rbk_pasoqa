<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/FirmasBD.php");

$page = new verificaidentidad();

class verificaidentidad {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $FirmasBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	private $idProyecto="";
	
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

	//Iconos 
	private $check = '<i class="fa fa-check DisBtn" aria-hidden="true" style="color:green;" title="Registro Aprobado" alt="Registro Aprobado"></i>';
	private $warning = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:orange;" title="Pendiente por aprobacion" alt="Pendiente por aprobacion"></i>';
	
	private $verde 		= '<div style="text-align: center;" title="En el plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="En el plazo" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Plazo por vencer">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Plazo por vencer" 	alt="Plazo por vencer"></i></div>';
	private $rojo		= '<div style="text-align: center;" title="Fuera de plazo">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="Fuera de plazo" 	alt="Fuera de plazo"></i></div>';
	
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

		$this->opcion = "Verificar Identidad";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Verificar Identidad</li>";
		
		// instanciamos del manejo de tablas
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->FirmasBD = new firmasBD();
				
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->FirmasBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		// mostramos el listado
		$this->listado();
		// el pie
		$this->imprimirFin();
		// y salimos
		return;
	}

	private function listado()
	{	
		$dt = new DataTable();

		$datos= $_REQUEST;

		switch( GESTOR_FIRMA ){
			case 'DEC5':
				$datos["gestor"] = 1;
				break;
			default: 
				$datos["gestor"] = 2;
				break;
		}

		//Tipo de firmas
		$this->FirmasBD->listadoPorGestor($datos,$dt); 
		$this->mensajeError.=$this->FirmasBD->mensajeError;

		if( $this->mensajeError == '' ){
			foreach ($dt->data as $key => $value) {
				switch ($value['Descripcion']) {
					case 'Pin':
						$dt->data[$key]['idFirma'] = 'PIN';
						break;
					case 'Huella':
						$dt->data[$key]['idFirma'] = 'FINGERPRINT';
						break;
					case 'Pin o Huella':
						$dt->data[$key]['idFirma'] = 'ANY';
						break;
				}
			}
		}

		$formulario[0]["TipoFirmas"] = $dt->data;
		
		//Selecciona por defecto la primera opcion
		//if( $this->seguridad->usuarioid == '26131316-2' ) {echo $dt->data[0]["idFirma"]}
		if( count($dt->data) == 1 ) $formulario[0]["idFirma"] = $dt->data[0]["idFirma"];


		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/verificaridentidad_Listado.html');	
	}
		
	
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}

	private function imprimirFin2()
	{
		include("includes/opciones_fin2.php");
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/calculo_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}
}

?>
