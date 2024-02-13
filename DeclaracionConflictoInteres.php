<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/formularioPlantillaBD.php");
include_once("includes/documentosdetBD.php");

// creamos la instacia de esta clase
$page = new generar_fichapersonal();


class generar_fichapersonal {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	
	// para el manejo de las tablas
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para juntar los mensajes de alerta 
	private $mensajeAd="";
	// para asignar el idCategoria a un nuevo registro 
	private $idCategoria="";
	
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

	private $html = ""; //Variable que almacenara el texto completo del documento
	private $ruta = "";
	private $contrato_html = ""; //Contrato en HTML
	private $tabla_anexo = ""; //Tabla del Anexo 
	private $anexo_html = ""; //Anexo en HTML
	private $firmantes_tabla = ""; //Tabla de Firmantes en HTML
	private $firmantes_completos; //Arreglo de Firmantes de un Documento
	private $firmantes_empresa;
	private $firmantes_cliente;
	private $firmantes_notaria;
	private $empleado;
	private $ordinal = array(); //Ordonal de Tabla
	private $tipo_con = 0;
	private $band;

	//Datos para importar Excel
	private $datos_contrato_marco;
	private $datos_anexo;
	private $datos_renting;
	private $inputFileType;
	private $objReader;
	private $objPHPExcel;
	private $sheet;
	private $total ="";
	private $vacio = 0;
	private $strSqlIniInsert	="";
	private $strSqlIniUpdate	="";
	private $strSqlIniSelect	="";
	private $strSql		="";
	private $valorCelda;
	private $Llave;

	private $orientacion = 'P';

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

		$this->opcion = "Declaracion de conflictos de interes ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "FORMULARIO";
		$this->opcionnivel2 = "<li>Declaracion de conflictos de interes</li>";
		
		// instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->formularioPlantillaBD = new formularioPlantillaBD();
		$this->documentosdetBD = new documentosdetBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->formularioPlantillaBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");
	
		// si no hay accion entonces mostramos el listado
		if (!isset($_REQUEST["accion"]))
		{
			// mostramos el listado
			$this->acceder();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}

		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		/*switch ($_REQUEST["accion"])
		{
			case "ENVIARFORMULARIO":
				$this->validar();
				break;
			case "LISTADO":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "GENERAR":
				$this->generar();
				break;
		}*/
		// e imprimimos el pie
		$this->imprimirFin();

    }
    private function acceder()
    {
		$datos = $_REQUEST;

		$datos["empleadoid"]=$this->seguridad->usuarioid;
		$datos["idFormulario"] = 1;
		$datos["estadoFormularioid"] = 1;

		$this->formularioPlantillaBD->getEmpleadoFormulario($datos, $dt);
		$this->mensajeError.=$this->formularioPlantillaBD->mensajeError;

		$this->pagina->agregarDato("empleadoFormularioid",$dt->data[0]['empleadoFormularioid']);

		$dat["usuarioid"] = $this->seguridad->usuarioid;
		$dat["opcionid"] = 'DeclaracionConflictoInteres.php';
		$this->opcionesxtipousuarioBD->getFormulariosUsuario($dat,$dt10); 
		
		if (count($dt10->data) == 0)
		{
			$this->pagina->agregarDato("noacceso",'display');
			$this->pagina->agregarDato("acceso",'none');
			$this->pagina->agregarDato("mensajeError",'Usted no tiene esta opcion habilitada');
			$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		}
		else
		{ 
            if ($dt10->data[0]['idDocumento'] != NULL)
            {
                $dat['idDocumento'] = $dt10->data[0]['idDocumento'];
                $this->documentosdetBD->Obtener($dat, $dt1);
                $this->mensajeError.=$this->documentosdetBD->mensajeError;
			
                switch($dt1->data[0]['idEstado'])
                {
                    case 1: // Documento pendiente  por aprobacion
                        $this->pagina->agregarDato("noacceso",'none');
                        $this->pagina->agregarDato("acceso",'display');
                        $this->pagina->agregarDato("esteFormulario",'none');
                        $str = '$(\'#idDocumento\').val('.$dt10->data[0]['idDocumento'].');';
                        $str .= '$(\'#ENVIARFORMULARIO\').hide();';
                        $str .= '$(\'#vista_previa\').show();';
                        $str .= '$(\'#vista_previa\').click();';
                        $this->pagina->agregarDato("clickAutomatico", $str);
                        $this->pagina->agregarDato("mensajeError",$this->mensajeError);
                        $this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
                    break;
                    case 3: // Pendiente por firma Empleado
                        $this->pagina->agregarDato("noacceso",'none');
                        $this->pagina->agregarDato("esteFormulario",'none');
                        $str = '$(\'#idDocumento\').val('.$dt10->data[0]['idDocumento'].');';
                        $str .= '$(\'#formularioFirmador\').submit();';
                        $this->pagina->agregarDato("clickAutomatico", $str);
                        $this->pagina->agregarDato("mensajeOK",'Redireccionando a firma');
                    break;
					default: // Otros estados
                        $this->pagina->agregarDato("noacceso",'none');
						$this->pagina->agregarDato("acceso",'display');
						$this->pagina->agregarDato("esteFormulario",'display');
                        $this->pagina->agregarDato("mensajeOK",'Se ha generado un documento previamente de este formulario ');
					
					break;
                }
            }
            else
            {
				$this->pagina->agregarDato("noacceso",'none');
				$this->pagina->agregarDato("acceso",'display');
				$this->pagina->agregarDato("esteFormulario",'display');
				$this->pagina->agregarDato("mensajeError",$this->mensajeError);
				$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
			}
		}

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/DeclaracionConflictoInteres.html');
	}

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>


