<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/firmasdocBD.php");
include_once("includes/documentosdetBD.php");
include_once("includes/docvigentesBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

include_once("includes/PlantillasBD.php");
include_once("includes/empresasBD.php");
include_once("includes/estadocontratosBD.php");

include_once("includes/feriadosBD.php");	
include_once("includes/flujofirmaBD.php");	
include_once("includes/tipoFirmasBD.php");
include_once("includes/tipoGeneracionBD.php");
include_once("includes/procesosBD.php");
include_once("includes/contratofirmantesBD.php");
include_once("includes/procesosBD.php");
include_once("includes/firmantesBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("dec5.php");
include_once("firma.php");
include_once("Config.php");

$page = new firmasdoc();

class firmasdoc {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $firmasdocBD;
	private $tipoFirmasBD;
	private $tipogeneracionBD;
	private $procesosBD;
	private $documentosdetBD;
	private $contratofirmantesBD;
	private $docvigentesBD;
	private $firmantesBD;
	private $tiposdocumentosBD;
	private $dec5;
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

		$this->opcion = "Firma un Tercero";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Firma un Tercero</li>";
		
		// instanciamos del manejo de tablas
		$this->firmasdocBD = new firmasdocBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->empresasBD = new empresasBD();
		$this->estadocontratosBD = new estadocontratosBD();
		$this->feriadosBD 	= new feriadosBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		$this->tipogeneracionBD = new tipogeneracionBD();
		$this->procesosBD = new procesosBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		$this->docvigentesBD = new docvigentesBD();
		$this->firmantesBD = new firmantesBD();
		$this->tiposdocumentosBD = new tiposdocumentosBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->firmasdocBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->estadocontratosBD->usarConexion($conecc);
		$this->feriadosBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->tipogeneracionBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);
		$this->firmantesBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		
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
	
		// ahora revisamos que accion se quiere ejecutar y ejecutamos la funcion especifica
		switch ($_POST["accion"])
		{
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "DETALLE":
				$this->detalle();
				break;
			//case "VERDOCUMENTO":
			//	$this->verdocumento();
			//	break;
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
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{ 
		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();

		// pedimos el listado
		$datos=$_POST;
 
        $datos["usuarioid"]=$this->seguridad->usuarioid;
       
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["idTipoFirma"] = 2 ; //Electronico

		if( ! isset($datos['idEstado']) )
			$datos['idEstado'] = 0; //Estados en proceso
        
		//busco el total de paginas
		$this->docvigentesBD->total($datos,$dt);

		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listado($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		for ($l=0; $l < count($dt->data) ; $l++)
		{	
			$estado = $formulariox[0]["listado"][$l]["idEstado"];

			if ($formulariox[0]["listado"][$l]["idEstado"] != 6)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}else{
				$formulariox[0]["listado"][$l]["semaforodetalle"] = "-";
				$formulariox[0]["listado"][$l]["semaforototal"]   =	"-";
			}

			//Estado 8 = Rechazado y Estado 6 = Aprobado
			if ($estado > 1 && $estado != 6 )//si debe cumplir con mas condiciones agregar
			{
				$formulariox[0]["listado"][$l]["firmar"] = "";
			}		
			
			if( $formulariox[0]["listado"][$l]["apmaterno"] == 'NULL' )
				$formulariox[0]["listado"][$l]["apmaterno"] = '' ;

		}
		
		$datos_filtros = $datos;
		$datos_filtros['idTipoDoc'] = '';
		$datos_filtros['idProceso'] = '';
		$datos_filtros['idEstado'] = 0;
		$datos_filtros['idTipoFirma'] = '';

		//Listados de Tipos de documentos
		//$this->firmasdocBD->listadoPorTiposDocumentos($datos,$dt);
		//$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$this->tiposdocumentosBD->Todos($dt);
		$this->mensajeError .= $this->tiposdocumentosBD->mensajeError;
		$formulariox[0]["tiposdocumentos"]=$dt->data;
		
		//Listado de estados
		//$this->firmasdocBD->listadoPorEstados($datos,$dt3);
		//$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$this->estadocontratosBD->listado($dt3);
		$this->mensajeError .= $this->estadocontratosBD->mensajeError;
		$formulariox[0]["estadocontratos"] = $dt3->data;
		
		//Listado de Tipo de firmas 
		//$this->firmasdocBD->listadoPorTipoFirmas($datos,$dtfirmas);
		//$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$this->tipoFirmasBD->listado($dtfirmas);
		$this->mensajeError .= $this->tipoFirmasBD->mensajeError;
		$formulariox[0]["TipoFirmas"] = $dtfirmas->data;

		//Listado de Procesos
		//$this->firmasdocBD->listadoPorProcesos($datos,$dt4);
		//$this->mensajeError.=$this->firmasdocBD->mensajeError;
		$this->procesosBD->listado($dt4);
		$this->mensajeError .= $this->procesosBD->mensajeError;
		$formulariox[0]["Procesos"] = $dt4->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) 
			$datos["pagina_siguente"]=$datos["pagina_ultimo"];
	
		if ($datos["pagina_ultimo"]==0) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);

		$formulario[0]=$datos;
	
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];	
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		$formulario[0]["firmar"][0] = $formulariox[0]["firmar"][0];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];
		$this->pagina->agregarDato("formulario",$formulario);
		
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_Listado.html');
		
	}
	
	//Mostrar listado de los registro disponibles
	public function semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$pdtferiados)
	{  
		//$this->graba_log("pfechacreacion:".$pfechacreacion." pfechaultimafirma".$pfechaultimafirma." dias max:".$pdiasmax);		
		$dt = new DataTable();
	
		//si tiene fecha ultima firma debe ser fecha inicio desde y el día actual es fecha hasta
		if ($pfechaultimafirma != "")
		{
			$fechainicio_num = substr($pfechaultimafirma,6,4).substr($pfechaultimafirma,3,2).substr($pfechaultimafirma,0,2);
		}
		else
		{
			if ($pfechacreacion == "")
			{
				$fechainicio_num 	= date('Ymd');
			}
			else
			{
				$fechainicio_num 	= substr($pfechacreacion,6,4).substr($pfechacreacion,3,2).substr($pfechacreacion,0,2);
			}
		}
		
		$fechatermino_num 	= date('Ymd');
	
		if ($pdiasmax ==  "")
		{
			$pdiasmax = 0;
		}
		
		//$this->graba_log("fecha inicio:".$fechainicio_num." fecha termino".$fechatermino_num." dias max:".$pdiasmax);
		//se formatea fecha para ocupar mas adelante para sumar días
		$fechaaux = substr($fechainicio_num,6,2)."-".substr($fechainicio_num,4,2)."-".substr($fechainicio_num,0,4);
		$fecha = date('d-m-Y', strtotime($fechaaux));
					
		$dias=0;
		for ($f=$fechainicio_num; $f<$fechatermino_num + 1; $f++)
		{
			$numdia = date('w', strtotime($fecha));
			
			//$this->graba_log("fecha :".$fecha." numdia:".$numdia." fechanum:".$fechainicio_num);
			
			//0=domingo 6=sabado
			if ($numdia != 0 && $numdia != 6)
			{
				//$this->graba_log("numdia:".$numdia." count:".count($pdtferiados->data));
				$encontro="N";
				for ($fe = 0; $fe < count($pdtferiados->data); $fe++) 
				{
					$datos["Fecha"] =$fecha;
					//$this->graba_log("feriado:".$pdtferiados->data[$fe]["Feriado"]." fecha:".$datos["Fecha"]);
					
					if ($pdtferiados->data[$fe]["Feriado"] == $datos["Fecha"])
					{
						//$this->graba_log("encontro ".$datos["Fecha"]);
						$encontro="S";
						break;
					}
				}
				
				if ($encontro == "N")
				{
					$dias++;
				}
				
				if ($dias > $pdiasmax)
				{
					break;
				}
				
			}
			
			$fecha =  date("d-m-Y",strtotime($fecha."+ 1 days"));
		}
			
		if ($dias > $pdiasmax )
			return $this->rojo;
		
		if ($dias < $pdiasmax )
			return $this->verde;
		
		if ($dias == $pdiasmax )
			return $this->amarillo;
 	}
	
	public function semaforototal($pfechacreacion,$pidwf,$pdtferiados,$dtflujos)
	{  
		//$this->graba_log("parametros:".$pfechacreacion." ".$pidwf);
		$dt = new DataTable();
		
		if ($pfechacreacion == "")
		{
			$fechacreacion_num = date('Ymd');
		}
		else
		{
			$fechacreacion_num = substr($pfechacreacion,6,4).substr($pfechacreacion,3,2).substr($pfechacreacion,0,2);
		}
		
		$fechaultimafirma_num = date('Ymd');
			
		//$fechacreacion_mmddaaaa = substr($pfechacreacion,3,2)."/".substr($pfechacreacion,0,2)."/".substr($pfechacreacion,6,4);
		//$fecha = strtotime($fechacreacion_mmddaaaa);
		$fecha = date('d-m-Y', strtotime($pfechacreacion));
		
		$diasmax = 0;
		
		//$this->graba_log("cant flujos:".count($dtflujos->data));
		for ($fl = 0; $fl < count($dtflujos->data) ; $fl++) 
		{
			if ($pidwf == $dtflujos->data[$fl]["idWF"])
			{
				$diasmax = $diasmax + $dtflujos->data[$fl]["DiasMax"];
				//$this->graba_log("diasmax:".$diasmax);
			}
		}
		
				
		$dias=0;
				
		for ($f=$fechacreacion_num; $f<$fechaultimafirma_num + 1; $f++)
		{
			
			$numdia = date('w', strtotime($fecha));
			
			//$this->graba_log("fecha :".$fecha." numdia:".$numdia." fechanum:".$fechacreacion_num." count:".count($pdtferiados->data));
			
			//0=domingo 6=sabado
			if ($numdia != 0 && $numdia != 6)
			{
				//$this->graba_log("numdia:".$numdia." count:".count($pdtferiados->data));
				$encontro="N";
				for ($fe = 0; $fe < count($pdtferiados->data); $fe++) 
				{
					$datos["Fecha"] =$fecha;
					//$this->graba_log("feriado:".$pdtferiados->data[$fe]["Feriado"]." fecha:".$datos["Fecha"]);
					
					if ($pdtferiados->data[$fe]["Feriado"] == $datos["Fecha"])
					{
						//$this->graba_log("encontro");
						$encontro="S";
						break;
					}
				}
				
				if ($encontro == "N")	
				{
					$dias++;
				}
				
				if ($dias > $diasmax)
				{
					break;
				}
				
			}
			
			$fecha =  date("d-m-Y",strtotime($fecha."+ 1 days"));
			//$this->graba_log("fecha despues:".$fecha);
		
		}
		
		//return $fechacreacion_num." ".$fechaultimafirma_num." ".$dias;
		
		if ($dias > $diasmax )
			return $this->rojo;
		
		if ($dias < $diasmax )
			return $this->verde;
		
		if ($dias == $diasmax )
			return $this->amarillo;		
 	}	

 	//Detalle del Documento
 	private function detalle(){

 		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$datos = $_POST;
	
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
		if ($estado > 1 && $estado != 6 && $aux!="")//si debe cumplir con mas condiciones agregar
		{
			$formulario[0]["firmar"][0]	= "";
		}		

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Documentos_FirmaTercero.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
			$ver = $dt->data[0]["ver"];
		}
		
		if ( $modifica ) {

			if( $datos["idEstado"] != 8 && $datos['idEstado'] != 6 ) 
				$formulario[0]["rechazar"][0] = "";
		}
		if ( $elimina  ) $formulario[0]["elimina"][0]   = "";
		if ( $ver  ) $formulario[0]["ver"][0]   = "";

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_formulario.html');
 	}

	//Ver documento actualizado
	private function verdocumento($idDocumento)
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		$datos['idDocumento'] = $idDocumento;
		$fecha = date('dmY_hms');

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt); 
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
		
		$nombrearchivo =	$nomarchtmp."_".$fecha.".".$extension;
		
		//Actualizar nombre de archivo
		//$datos['NombreArchivo'] = $nomarchtmp."_".$fecha;
		//$this->documentosdetBD->modificarNombreArchivo($datos);
		
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
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_documento.html');				
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
		$datos = $_POST;	
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
		$datos = $_POST;	
		$datos["estado"] = 8; //cambiar a estado de rechazo

		$this->documentosdetBD->modificarEstadoDocumento($datos);
		$this->mensajeError=$this->documentosdetBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}

		$_POST['idDocumento'] = '';
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
	
	private function iniciofirma()
	{ 
		$dt = new DataTable();
		$dt1 = new DataTable();
		$datos = $_POST;	

		//consulta para deducir tipo de firma pin, token o huella
		$this->documentosdetBD->obtenerFirmantesPorFirma($datos,$dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if( $this->mensajeError == '' ){

			if( $dt->leerFila() ){
				$datos['personaid'] = $dt->obtenerItem("RutFirmante");
			}
		}
		
		//Consultar el tipo de firma que tiene asociadael usuario
		$this->documentosdetBD->obtenerTipoFirma($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if($dt1->leerFila())
		{
			$tipofirma = $dt1->obtenerItem("Descripcion");
		}

		if ( $tipofirma == "" )
		{
			switch (GESTOR_FIRMA) {
				case 'DEC5':
					$tipofirma = TIPO_FIRMA_PORDEFECTO_DEC5;
				default:
					$tipofirma = TIPO_FIRMA_PORDEFECTO_RBK;
			}
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
		$datos = $_POST;

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
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma.html');			
	}

	private function InicioFirma_RBK()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;

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

		$this->documentosdetBD->obtenerFirmantesPorFirma($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( $dt->leerFila() ){
			$datos['personaid'] = $dt->obtenerItem('RutFirmante');
		}

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
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma_rbk.html');	
	}
	
	private function InicioFirmaToken()
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		
		$formulario[0] = $datos;	
		
		$this->documentosdetBD->Obtener($datos,$dt);//print_r($dt);
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
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma_token.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	
	private function ProcesoFirmaToken()
	{
		
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$dt1 = new DataTable();
		$datos = $_POST;
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
				$_POST["DocCode"] 	= $dt["result"][0]["code"];
				$_POST["documento"]	= $dt["result"][0]["file"];
				
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
				$_POST["FechaFirma"] = $ejemplo; 
				$_POST["RutFirmante"] = $this->seguridad->usuarioid;
				
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
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma_token.html');
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
		//print_r ($_POST);
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
		
		$nombrearchivo = $this->ObtenerDocBase64();
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $nombrearchivo;
		
		$rut = $this->seguridad->usuarioid;
		$formulario[0]["rut"]=$rut;
		
		$formulario[0]["botonfirma"]="";

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma_huella.html');	
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior		
		
	}
	
	private function ProcesoFirmaHuella()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;
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
					$_POST["DocCode"] 	= $dt["result"][0]["code"];
					$_POST["documento"]	= $dt["result"][0]["file"];
			
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
					$_POST["FechaFirma"] = $ejemplo; 

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
						$_POST["DocCode"] = $dt["result"]["code"];
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
		$this->pagina->imprimirTemplate('templates/documentos_FirmaTercero_firma_huella.html');
		$this->imprimirFin2();//se crea pie de pagina dos, por incompatibilidad con los script para multibrowser
		exit;//para finalizar ya que se imprimió el pie de pagina en linea anterior
	}
	
	private function firmahuella()
	{
		$datos = $_POST;
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

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($_POST,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Asignamos el RUT del usuario en sesion	
		$_POST["personaid"] 		= $this->seguridad->usuarioid;
		$_POST["RutFirmante"]	= $this->seguridad->usuarioid;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($_POST, $dt1);
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
		$datos = $_POST;
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

		$firma = new firma();
		$datos = $_POST;
		$usuarioid = $this->seguridad->usuarioid;
		$respuesta = array();

		switch (GESTOR_FIRMA) {

			case 'DEC5':
				if( isset($datos['pin']) ){
					$respuesta = $firma->firmar_pin($datos['idDocumento'],$usuarioid);
				}
				break;
			
			default:
			
			//print_r($datos);
				$datos['firmaTercero'] = 1;
				$usuarioid = $datos['inVerifyDocPersonalNumber']; 
				$datos['operadorid'] = $this->seguridad->usuarioid;
				$respuesta = $firma->firmar_rbk($datos,$usuarioid);
				break;
		}
		
		if( $respuesta['codigo'] == 200 ) {
			$this->mensajeOK .= $respuesta['mensaje'];
		}
		else {
			if( $respuesta['mensaje'] == 'No se pudo completar el envio del documento al Gestor' ){
				$this->mensajeAd = $respuesta['mensaje'];
			}else{
				$this->mensajeError .= $respuesta['mensaje'];
			}
		}
		$this->verdocumento($datos['idDocumento']);
	}

	//Accion de completar los datos del Firmante 
	private function cargarFirmante(){

		//Recibir $_POST["idContrato"], $_POST["personaid"], $this->firma = tipo de firma del firmante

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($_POST, $dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($_POST, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($_POST,$dt2);
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
				$persona = strtoupper($dt1->data[$i]["personaid"]);
				$firmado = $dt1->data[$i]["Firmado"];

				//Si es Empresa
				//echo mb_substr_count($estado, "Empresa")."<br/>";
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario'){
					array_push($this->signers_roles, "Representantes");
					if( $num == 0 && $firmado == 0){
						//$this->datos["user_role"] = "Representantes";// el rol del usuario que firma
						//$this->datos["user_institution"] = "RUBRIKA";
						$this->datos["user_role"] = strtoupper($persona);// el rol del usuario que firma
						$this->datos["user_institution"] = strtoupper($persona);
						$num++;
					}
				}
				//Si es Empleado
				//echo mb_substr_count($estado, "Empresa")."<br/>";
				if ( mb_substr_count($estado, "Empleado") >  0 ){
					array_push($this->signers_roles, $persona);
					if( $num == 0 && $firmado == 0){
						$this->datos["user_role"] = strtoupper($persona);// el rol del usuario que firma
						$this->datos["user_institution"] = strtoupper($persona);
						$num++;
					}
				}
				
				//Si es Notario
				//echo mb_substr_count($estado, "Notario")."<br/>";
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario'){
					array_push($this->signers_roles, "Notarios");
					if( $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Notarios";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				
				array_push($this->signers_ruts, strtoupper($persona));
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = strtoupper($persona);//usuario de la persona que firma

			$usuarioid = strtoupper($persona);
			$array = array ( "personaid" => $usuarioid,"idDocumento"=>$_POST["idDocumento"]);

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
				$this->datos["user_pin"] = $_POST["pin"];//clave del usuario que firma
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

		//Recibir $_POST["idContrato"]

		//Variables para subida del Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($_POST, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($_POST,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

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
				$cargo = $dt1->data[$i]["Cargo"];
				
				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario' )
				{
					//array_push($this->signers_roles, "Representantes");
					//array_push($this->signers_institutions, "RUBRIKA");
					array_push($this->signers_roles, strtoupper($dt1->data[$i]["personaid"]));
					array_push($this->signers_institutions, strtoupper($dt1->data[$i]["personaid"]));
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, strtoupper($dt1->data[$i]["personaid"]));
					array_push($this->signers_institutions, strtoupper($dt1->data[$i]["personaid"]));
				}

				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario' )
				{
					//array_push($this->signers_roles, "Notarios");
					//array_push($this->signers_institutions, "RUBRIKA");
					array_push($this->signers_roles, strtoupper($dt1->data[$i]["personaid"]));
					array_push($this->signers_institutions, strtoupper($dt1->data[$i]["personaid"]));
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
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
		}
	}

	//Actualiza firma en BD
	public function actualizarFirma(){

		$datos = $_POST;
		$datos['RutFirmante'] = $datos['RutUsuario'];

		if( $datos["FechaFirma"] == ''){
			$datos["FechaFirma"] = date("d-m-Y H:i:s");
		}
		//Actualiza el estado de firma 
		$this->documentosdetBD->agregarFirma($datos);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	    $this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Actualiza el documento firmado
		if( $datos['documento'] != '' ){
			$this->documentosdetBD->modificarDocumento($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);	
		}
	}

	//Actualiza Documento en la BD
	public function actualizaDocumento(){

		$datos = $_POST;

		if ( $datos['DocCode'] != '' ){
			//Actualizar el codigo del documento
			$this->documentosdetBD->modificar($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		}
	}

	//Consultar fsi firmo el documento 
	private function buscarDatosDec($docCode){

		$datos = $_POST;

		//Consulto los datos del documento en Acepta 
		$datos["code"] = $docCode;
		$this->dec5 = new dec5();
		$this->dec5->ObtenerDocumento($datos,$dt);

		if ($dt["status"] == "200"){

			$_POST["DocCode"] 	= $dt["result"][0]["code"];
			$_POST["documento"]	= $dt["result"][0]["file"];

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
				$_POST["FechaFirma"] = $ejemplo; 
				$_POST["RutFirmante"] = $this->seguridad->usuarioid;
				
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

	private function envioGestor(){

		$datos = $_POST;

		$dt = new DataTable();

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;

		if ( $dt->leerFila()){
			$estado = $dt->ObtenerItem("idEstado");
		}

		if( $estado == 6 ){ //Si esta firmado 
			$this->documentosdetBD->agregarGestor($datos);
			$this->mensajeError .= $this->documentosdetBD->mensajeError;
		}

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
