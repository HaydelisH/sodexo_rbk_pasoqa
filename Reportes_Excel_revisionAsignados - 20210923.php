<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
//include_once("includes/docvigentesBD.php");
//include_once("includes/tiposusuariosBD.php");
include_once("includes/formularioPlantillaBD.php");
include_once('includes/Seguridad.php');

// creamos la instacia de esta clase
$page = new consultageneral_excel();

class consultageneral_excel {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $gestionesBD;
	//private $docvigentesBD;
    private $formularioPlantillaBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";

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

		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// hacemos una instacia del manejo de plantillas (templates)
			$this->pagina = new Paginas();

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
		if (!$this->seguridad->sesionar()) {$this->pagina = new Paginas(); return;}

		$this->pagina = new Excel();
		
		$fechahoy="";
		$fechahoy=@date("dmY Hms");
		$nombrearchivo = "Reportes_RRHH_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Reporte RRHH"));

		// instanciamos del manejo de tablas
		//$this->docvigentesBD = new docvigentesBD();
		//$this->tiposusuariosBD = new tiposusuariosBD();
        $this->formularioPlantillaBD = new formularioPlantillaBD();
		
		$conecc=$this->bd->obtenerConexion();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		//$this->docvigentesBD->usarConexion($conecc);
		//$this->tiposusuariosBD->usarConexion($conecc);
        $this->formularioPlantillaBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt1 = new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;
		
		foreach ($datos as $key => $value) {
			$this->graba_log($datos[$key]);
		}
		
		//$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["pagina"] = 1;
		$datos["decuantos"] = 99999999;//10;

		/*$this->formularioPlantillaBD->totalListadoActor1($datos,$dt);
		$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
		//$datos["pagina_ultimo"]=$dt->data[0]["total"];
		//$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		$formulario[0]=$datos;
				
		$this->formularioPlantillaBD->listadoActor1($datos,$dt);
		$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;
		$formulario[0]["listado"] = $dt->data;
		$registros = count($formulario[0]["listado"]);*/

		$this->formularioPlantillaBD->listadoAsignados($datos,$dt);
		$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;

		if( count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$datos2[$key]["nombreFormulario"] 		= $dt->data[$key]["nombreFormulario"] ;
				$datos2[$key]["RutEmpleado"]  		= $dt->data[$key]["RutEmpleado"];
				$datos2[$key]["NombreEmpleado"] 	= $dt->data[$key]["NombreEmpleado"];
				$datos2[$key]["EstadoGestion"]		= $dt->data[$key]["EstadoGestion"];
				$datos2[$key]["estadoFujoFirma"]		= $dt->data[$key]["estadoFujoFirma"];
			}
		}

        $this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
            utf8_decode("nombreFormulario"),
            utf8_decode("RutEmpleado"),
            utf8_decode("NombreEmpleado"),
            utf8_decode("EstadoGestion"),
            utf8_decode("estadoFujoFirma")
        );

		$descripciones = array(
            utf8_decode("Formulario"),
            utf8_decode("Rut Empleado"),
            utf8_decode("Nombre Empleado"),
            utf8_decode("Estado formulario"),
            utf8_decode("Estado flujo firma")
        );

        $tipos = array(
            "normal",
            "normal",
            "normal",
            "normal",
            "normal"
        );
        
        $ancho = array(
            "20",
            "20",
            "20",
            "20",
            "30"
        );
                    
		$this->pagina->agregarDato($datos2, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
		
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/Reportes_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

}
?>