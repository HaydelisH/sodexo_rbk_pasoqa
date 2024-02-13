<?php

error_reporting(E_ERROR);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Excel_2.php");
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/postulacionBD.php");
include_once('includes/Seguridad.php');

// creamos la instacia de esta clase
$page = new consultageneral_excel();

class consultageneral_excel {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $postulacionBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";

	// funcion contructora, al instanciar
	function __construct()
	{
		
		// revisamos si la accion es volver desde el listado principal
		if (isset($_POST["accion"]))
		{
			// si lo es
			if ($_POST["accion"]=="Volver")
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
		$nombrearchivo = "Reporte_Postulaciones_".$fechahoy;
		$this->pagina->iniciar($nombrearchivo,utf8_decode("Resultado Postulacion"));
	
		// instanciamos del manejo de tablas
		$this->postulacionBD = new postulacionBD();
		
		$conecc=$this->bd->obtenerConexion();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->postulacionBD->usarConexion($conecc);
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

        $datos = $_POST;
        //$datos["usuarioingid"]=$this->seguridad->usuarioid;
        $datos['pagina'] = 1;
        $datos['decuantos'] = 10;
        $this->postulacionBD->listadoReporteTotal2($datos,$dt);
		$this->mensajeError .= $this->postulacionBD->mensajeError;
		//$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["decuantos"]=round($dt->data[0]["totalreg"]);
		$datos["pagina"] = 1;
        $this->postulacionBD->listadoReportePaginado2($datos,$dt);
		$this->mensajeError.=$this->postulacionBD->mensajeError;

		$this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
            "fechaPostulacion",
            "rut",
            "nombre",
            "telefono",
            "email",
            "disponibilidadNombre",
            "contratado",
            "observacion",
            "discapacidad",
            "nombreCargo",
            "nombrecentrocosto",
            "EstadoPostulacion",
            "ResultadoPostulacion"
        );

        $descripciones = array(
                utf8_decode("Fecha postulación"),
                utf8_decode("R.U.T."),
                utf8_decode("Nombre"),
                utf8_decode("Telefono"),
                utf8_decode("Email"),
                utf8_decode("Disponibilidad"),
                utf8_decode("Contratado"),
                utf8_decode("Detalles"),
                utf8_decode("Discapacidad"),
                utf8_decode("Cargo"),
                utf8_decode("División personal"),
                utf8_decode("Estado postulante"),
                utf8_decode("Resultado postulación")
            );
                        
        $tipos = array("normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal","normal");

        $ancho = array("17","10","30","13","15","12","10","30","25","30","30","17","20");
    
		$this->pagina->agregarDato($dt->data, $campos, $descripciones, $tipos, $ancho);
		
		$this->bd->desconectar();
		$this->pagina->cerrar();
		
	}
}
?>