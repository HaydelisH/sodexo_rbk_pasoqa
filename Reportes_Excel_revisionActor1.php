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

		$this->formularioPlantillaBD->listadoActor1_Excel($datos,$dt);
		$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;

        //busco el total de paginas
		/*$this->docvigentesBD->total($datos,$dt1);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["decuantos"]=$dt1->data[0]["total"] * 10;
		
		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;

		foreach ($dt->data as $key => $value) {
			$dt->data[$key]["Empleado"] = $dt->data[$key]["nombre"];
			$dt->data[$key]["NombreFirmante"] = $dt->data[$key]["nombre_rep"];
		}*/

		if( count($dt->data) > 0){
			foreach ($dt->data as $key => $value) {
				$datos2[$key]["idDocumento"] 		= $dt->data[$key]["idDocumento"] ;
				$datos2[$key]["FechaUltimaFirma"] 	= $dt->data[$key]["FechaUltimaFirma"];
				$datos2[$key]["RutEmpleado"]  		= $dt->data[$key]["RutEmpleado"];
				$datos2[$key]["NombreEmpleado"] 	= $dt->data[$key]["NombreEmpleado"];
				$datos2[$key]["EstadoGestion"]		= $dt->data[$key]["EstadoGestion"];
                $datos2[$key]["respuesta1"]			= $dt->data[$key]["respuesta1"];
				$datos2[$key]["observacion1"]		= $dt->data[$key]["observacion1"];
				$datos2[$key]["respuesta2"]			= $dt->data[$key]["respuesta2"];
				$datos2[$key]["observacion2"]		= $dt->data[$key]["observacion2"];
				$datos2[$key]["respuesta3"]			= $dt->data[$key]["respuesta3"];
				$datos2[$key]["observacion3"]		= $dt->data[$key]["observacion3"];
				$datos2[$key]["respuesta4"]			= $dt->data[$key]["respuesta4"];
				$datos2[$key]["observacion4"]		= $dt->data[$key]["observacion4"];
				$datos2[$key]["respuesta5"]			= $dt->data[$key]["respuesta5"];
				$datos2[$key]["observacion5"]		= $dt->data[$key]["observacion5"];
				$datos2[$key]["respuesta6"]			= $dt->data[$key]["respuesta6"];
				$datos2[$key]["observacion6"]		= $dt->data[$key]["observacion6"];
				$datos2[$key]["respuesta7"]			= $dt->data[$key]["respuesta7"];
				$datos2[$key]["observacion7"]		= $dt->data[$key]["observacion7"];
				$datos2[$key]["respuesta8"]			= $dt->data[$key]["respuesta8"];
				$datos2[$key]["observacion8"]		= $dt->data[$key]["observacion8"];
                /*if ($dt->data[$key]["respuesta1"] == 'no')
                {
                    $datos2[$key]["observacion1"] = '';
                }
                else{
                    $aux = explode('</td>', explode('<td>', $dt->data[$key]["observacion1"])[1]);
                    $datos2[$key]["observacion1"] = $aux[0];//trim(str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion1"])));
                }*/
                $datos2[$key]["nombreFormulario"]			= $dt->data[$key]["nombreFormulario"];
                                                   // <p>Reporto la existencia de las siguientes situaciones y/o relaciones que pueden dar lugar a un conflicto de inter&eacute;s en el marco de mis funciones en SODEXO</p><p style="text-align: justify;">Describa el conflicto de inter&eacute;s existente:</p><table border="1" width="100%" cellspacing="0" cellspading="0">  <tbody>      <tr>          <td>lalala</td>      </tr>  </tbody></table>
//<p>No tengo ning&uacute;n conflicto de inter&eacute;s que reportar a la fecha</p><p style="text-align: justify;">Describa el conflicto de inter&eacute;s existente:</p><table border="1" width="100%" cellspacing="0" cellspading="0">  <tbody>      <tr>          <td>&nbsp;</td>      </tr>  </tbody></table>
                /*$datos2[$key]["respuesta2"]			= $dt->data[$key]["respuesta2"];
				$datos2[$key]["observacion2"]		= trim(str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion2"])));
				$datos2[$key]["respuesta3"]			= $dt->data[$key]["respuesta3"];
				$datos2[$key]["observacion3"]		= trim(str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion3"])));
				$datos2[$key]["respuesta4"]			= $dt->data[$key]["respuesta4"];
				$datos2[$key]["observacion4"]		= trim(str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion4"])));
				$datos2[$key]["respuesta5"]			= $dt->data[$key]["respuesta5"];
				$datos2[$key]["observacion5"]		= trim(str_replace(" Nombre          Parentesco          Entidad / Autoridad politica / Cargo","",str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion5"]))));
				$datos2[$key]["respuesta6"]			= $dt->data[$key]["respuesta6"];
				$datos2[$key]["observacion6"]		= trim(str_replace("&nbsp;","",strip_tags($dt->data[$key]["observacion6"])));
				
				$datos2[$key]["ObservacionGestion"] = "";
				if ($dt->data[$key]["IDEstadoGestion"] == 3) // Cerrado
				{
					$datos3['empleadoFormularioid'] = $dt->data[$key]["empleadoFormularioid"];
					$datos3['actorid'] = 1;
					$this->formularioPlantillaBD->getObservacionActor($datos3,$dt6);
					$this->mensajeError .= $this->formularioPlantillaBD->mensajeError;
					$datos2[$key]["ObservacionGestion"] = $dt6->data[0]['observacion'];
				}*/
			}
		}

        $this->pagina->agregarDato($encabezado, $campos, $descripciones, $tipos, $ancho, false);		
		 
		$campos = array(
            utf8_decode("idDocumento"),
            utf8_decode("nombreFormulario"),
            utf8_decode("FechaUltimaFirma"),
            utf8_decode("RutEmpleado"),
            utf8_decode("NombreEmpleado"),
            utf8_decode("EstadoGestion"),
            //utf8_decode("ObservacionGestion"),
            utf8_decode("respuesta1"),
            utf8_decode("observacion1"),
            utf8_decode("respuesta2"),
            utf8_decode("observacion2"),
            utf8_decode("respuesta3"),
            utf8_decode("observacion3"),
            utf8_decode("respuesta4"),
            utf8_decode("observacion4"),
            utf8_decode("respuesta5"),
            utf8_decode("observacion5"),
            utf8_decode("respuesta6"),
            utf8_decode("observacion6"),
			utf8_decode("respuesta7"),
            utf8_decode("observacion7"),
			utf8_decode("respuesta8"),
            utf8_decode("observacion8")
			 
        );

		$descripciones = array(
            utf8_decode("NRO DOCUMENTO"),
            utf8_decode("FORMULARIO"),
            utf8_decode("FECHA ULTIMA FIRMA"),
            utf8_decode("RUT EMPLEADO"),
            utf8_decode("NOMBRE EMPLEADO"),
            utf8_decode("ESTADO DE GESTION"),
            //utf8_decode("OBSERVACION DE GESTION"),
            utf8_decode("RESPUESTA 1"),
            utf8_decode("Descripcion Del conflicto de interes"),
			utf8_decode("RESPUESTA 2"),
            utf8_decode("Descripcion Del conflicto de interes 2"),
			utf8_decode("RESPUESTA 3"),
            utf8_decode("Descripcion Del conflicto de interes 3"),
			utf8_decode("RESPUESTA 4"),
            utf8_decode("Descripcion Del conflicto de interes 4"),
			utf8_decode("RESPUESTA 5"),
            utf8_decode("Descripcion Del conflicto de interes 5"),
			utf8_decode("RESPUESTA 6"),
            utf8_decode("Descripcion Del conflicto de interes 6"),
			utf8_decode("RESPUESTA 7"),
            utf8_decode("Descripcion Del conflicto de interes 7"),
			utf8_decode("RESPUESTA 8"),
            utf8_decode("Descripcion Del conflicto de interes 8")
            //utf8_decode("OBSERVACION RESPUESTA 1"),
            /*utf8_decode("RESPUESTA 2"),
            utf8_decode("¿Tiene usted o alguno de sus familiares directos alguna relación personal, comercial o económica con algún directivo, trabajador o subordinado en la empresa donde usted labora o en Melón y sus subsidiarias?"),
            //utf8_decode("OBSERVACION RESPUESTA 2"),
            utf8_decode("RESPUESTA 3"),
            utf8_decode("¿Tiene usted o alguno de sus familiares directos alguna participación económica o inversión en el Grupo Melón y sus subsidiarias?"),
            //utf8_decode("OBSERVACION RESPUESTA 3"),
            utf8_decode("RESPUESTA 4"),
            utf8_decode("¿Ha estado implicados usted o alguno de sus familiares directos en alguna disputa legal con Melón y sus subsidiarias o se encuentra actualmente involucrado en cualquier otra disputa legal que pudiera tener un efecto real o percibido sobre sus funciones en la empresa donde usted labora o en Melón y sus subsidiarias?"),
            //utf8_decode("OBSERVACION RESPUESTA 4"),
            utf8_decode("RESPUESTA 5"),
            utf8_decode("¿Tiene usted relación familiar con algún funcionario estatal o autoridad política?"),
            //utf8_decode("OBSERVACION RESPUESTA 5"),
            utf8_decode("RESPUESTA 6"),
            utf8_decode("¿Hay alguna otra situación no incluida en las preguntas precedentes que pudiera afectar su objetividad o independencia en el desempeño de sus funciones o la percepción de esa independencia y objetividad por parte de los demás?"),
            //utf8_decode("OBSERVACION RESPUESTA 6")*/
            );

            $tipos = array(
                "normal",
                "normal",
                "normal",
                "normal",
                "normal",
                "normal",
                //"normal",
                "normal",
                "normal",
                "normal",
                "normal", 
                "normal", 
                "normal", 
                "normal", 
                "normal", 
                "normal", 
                "normal", 
                "normal", 
                "normal",
				"normal",
				"normal",
				"normal",
				"normal"
                );
        
                $ancho = array(
                    "18",
                    "35",
                    "22",
                    "18",
                    "50",
                    "30",
                    //"120",
                    "15",
                    "40",
                    "15",
                    "40",
                    "15",
                    "40",
                    "15",
                    "40",
                    "15",
                    "40",
                    "15",
                    "40",
					"15",
                    "40",
					"15",
                    "40"
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