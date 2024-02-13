<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/DeclaracionFirmaElectonicaBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/docvigentesBD.php");
include_once("includes/empresasBD.php");
				  


$page = new autoevaluacionriesgorev();

class autoevaluacionriesgorev{

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $DeclaracionFirmaElectonicaBD;
	private $contratofirmantesBD;

	private $estadoFormularioBD;

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
	private $orden;
	
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

		$this->opcion = "Declaracion Firma Electonica  ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Declaracion Firma Electonica </li>";
		
		// instanciamos del manejo de tablas
		$this->DeclaracionFirmaElectonicaBD = new DeclaracionFirmaElectonicaBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->empresasBD = new empresasBD();
		$this->docvigentesBD = new docvigentesBD();

		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->DeclaracionFirmaElectonicaBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);
		
		//se construye el menu
		include("includes/opciones_menu.php");

		// si no hay accion entonces mostramos el listado
		if (!isset($_POST["accion"]))
		{
			// mostramos el listado
			$this->listado();
			// el pie
			$this->imprimirFin();
			// y salimos
			return;
		}
        //var_dump($_POST);
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
        switch ($_POST["accion"])
		{
            case "BUSCAR":
                $this->listado();
                break;
 			case "DETALLE":
				$this->detalle();
				break;
			case "VERDOCUMENTO":
				$this->verdocumento();
				break;
   
        }
		// e imprimimos el pie
		$this->imprimirFin();

	}



	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;

		if ((!isset($datos["pagina"])) || $datos['pagina'] =='') $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		
		if (!isset($datos["RutEmpresa"])) $datos["RutEmpresa"]="0";
		
		//if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
	    $datos["idDocumento"] =  $datos["idDocumentof"];
        //busco el total de paginas
		
		$this->DeclaracionFirmaElectonicaBD->total($datos,$dt);
		$this->mensajeError.=$this->DeclaracionFirmaElectonicaBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		$formulario[0]=$datos;
				
		
		$this->DeclaracionFirmaElectonicaBD->listado($datos,$dt);
		$this->mensajeError.=$this->DeclaracionFirmaElectonicaBD->mensajeError;
		$formulario[0]["listado"] = $dt->data;
		$registros = count($formulario[0]["listado"]);
		
		//for ( $i = 0 ; $i < $$total_registros ; $i++ ){
		//	$datos2["idDocumento"] = $dt->data[$i]["idDocumento"];
		//	$datos2["empleadoid"] = $dt->data[$i]["empleadoid"];
		//	$datos2["RutEmpresa"] = $dt->data[$i]["RutEmpresa"];
		//	$this->DeclaracionFirmaElectonicaBD->agregar($datos2);
		//}
		
		$formulario[0]["Estados"][0]["idEstado"] = "0";	
		$formulario[0]["Estados"][0]["Descripcion"] = "(Seleccione)";	
		$formulario[0]["Estados"][1]["idEstado"] = "1";	
		$formulario[0]["Estados"][1]["Descripcion"] = "Espera de firma";	
		$formulario[0]["Estados"][2]["idEstado"] = "7";	
		$formulario[0]["Estados"][2]["Descripcion"] = "Firmado";	
	
		// Rescato nombre del cargo //
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["empresas"]  = $dt->data;
				
		//Estados de gestion	
		/*$this->estadoFormularioBD->listado($dt5);
		$this->mensajeError .= $this->estadoFormularioBD->mensajeError;
		$formulario[0]["EstadosGestion"] = $dt5->data;*/
		
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ( $registros==0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}


		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("idDocumentof",$datos["idDocumentof"]);
		$this->pagina->agregarDato("RutEmpleado",$datos["RutEmpleado"]);
		$this->pagina->agregarDato("NombreEmpleado",$datos["NombreEmpleado"]);
	//	$this->pagina->agregarDato("fechaInicio",$datos["fechaInicio"]);
	//	$this->pagina->agregarDato("fechaFin",$datos["fechaFin"]);
		$this->pagina->agregarDato("idEstado",$datos["idEstado"]);
		
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates\reporteDeclaracionFirmaElectronica.html');
	}

	
	
    public function verdocumento()
	{
        
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		$fecha = date('dmY_hms');

		$datos["idDocumento"] = $datos["idDocumentof"];
		// Buscamos el idCategoria que vamos a asignar
		$this->docvigentesBD->obtenerb64($datos,$dt); 
		$this->mensajeError=$this->docvigentesBD->mensajeError;
				
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
		
		$nombrearchivo =	$nomarchtmp."_".$fecha.".".$extension;
		
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
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/diagteletrabajorev_Documento.html');				
    }
	
	

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
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
