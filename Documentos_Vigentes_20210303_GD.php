<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/docvigentesBD.php");
include_once("includes/documentosdetBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

include_once("includes/PlantillasBD.php");
include_once("includes/empresasBD.php");
include_once("includes/estadocontratosBD.php");

include_once("includes/feriadosBD.php");	
include_once("includes/flujofirmaBD.php");	
include_once("includes/contratofirmantesBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("includes/tipoGeneracionBD.php");

include_once("includes/estadosworkflowBD.php");
include_once("includes/procesosBD.php");
include_once("includes/tiposdocumentosBD.php");
include_once("firma.php");
include_once("Config.php");							  

//Firma DEC5
include_once('dec5.php');

$page = new docvigentes();

function semaforodetalle ($fechacreacion,$fechaultimafirma,$diasmax)
{

	$calculos = new calculos();
	
	$res = $calculos->semaforodetalle($fechacreacion,$fechaultimafirma,$diasmax);
	
	return $res;
}

function semaforototal ($fechacreacion,$idwf)
{

	$calculos = new calculos();
	
	$res = $calculos->semaforototal($fechacreacion,$idwf);
	
	return $res;
}


class docvigentes{

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $docvigentesBD;
	private $contratofirmantesBD;
	private $tipoFirmasBD;
	private $tipogeneracionBD;
	private $procesosBD;
	private $tiposdocumentosBD;
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

		$this->opcion = "Documentos Vigentes ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Documentos Vigentes</li>";
		
		// instanciamos del manejo de tablas
		$this->docvigentesBD = new docvigentesBD();
		$this->documentosdetBD = new documentosdetBD();
		$this->contratofirmantesBD = new contratofirmantesBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->empresasBD = new empresasBD();
		$this->estadocontratosBD = new estadocontratosBD();
		$this->feriadosBD 	= new feriadosBD();
		$this->flujofirmaBD = new flujofirmaBD();
		$this->estadosworkflowBD = new estadosworkflowBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		$this->tipogeneracionBD = new tipogeneracionBD();
		$this->procesosBD = new procesosBD();
		$this->tiposdocumentosBD = new tiposdocumentosBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->docvigentesBD->usarConexion($conecc);
		$this->documentosdetBD->usarConexion($conecc);
		$this->contratofirmantesBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->estadocontratosBD->usarConexion($conecc);
		$this->feriadosBD->usarConexion($conecc);
		$this->flujofirmaBD->usarConexion($conecc);
		$this->estadosworkflowBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->tipogeneracionBD->usarConexion($conecc);
		$this->procesosBD->usarConexion($conecc);
		$this->tiposdocumentosBD->usarConexion($conecc);
		
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
			case "ACTUALIZAR":
				$this->actualizar();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "DETALLE":
				$this->detalle();
				break;
			case "APROBAR":
				$this->aprobar();
				break;
			case "VERDOCUMENTO":
				$this->verdocumento();
				break;
			case "RECHAZAR":
				$this->rechazar();
				break;
			case "POR_FIRMA":
				$this->por_firma();
				break;
			case "FIRMADOS":
				$this->firmados();
				break;
			case "RECHAZADOS":
				$this->rechazados();
				break;
			case "TODOS":
				$this->todos();
				break;
			case "SUBIR":
				$this->subir();
				break;
			case "ENVIO":
				$this->envio();
				break;	
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}

	//Actualizar documento descargando de nuevo desde Acepta
	private function actualizar(){
	
		$dt = new DataTable();
		$datos = $_REQUEST;
		$id = '';

		$this->documentosdetBD->Obtener($datos, $dt);
		$this->mensajeError .= $this->documentosdetBD->mensajeError;
		
		if( $this->mensajeError == '' ){

			if ( $dt->leerFila()){
				$doccode = $dt->ObtenerItem("DocCode");
			}

			if( $doccode != '' ){
				$codigos = array();
				$codigos = explode(SEPARADOR_DOCCODE,$doccode);
				$datos['numero'] = $codigos[1];
			}else{
				$this->mensajeError = "El Documento no se ha subido al gestor de firmas";
			}

			//Firmantes en ese orden
			$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt);
			$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
			
	
			if( $this->mensajeError == '' ){

				if ( $dt->leerFila()){
					$datos['rut']= $dt->ObtenerItem("RutFirmante");
				}

				$firma = new firma();
				
				//Rut del firmante
				$datos["rut"] = str_replace (".","",$datos["rut"]);
				$arr_rut = explode("-",$datos["rut"]);
			
				//IdDelDocumento
				$datos["personalNumber"] = $arr_rut[0].$arr_rut[1];
				$datos["id"] 			 = $datos["numero"];

				$firma->ConsultaDocumento($datos,$dt);
				$this->mensajeError.=$firma->mensajeError;
				
				if( $this->mensajeError == '' ){

					$this->valor_arr = "";
					$this->obtener_dato_arr($dt["data"]["digitalSignature"]["documents"][0]["content"],"data");
					if ($this->valor_arr != "")
					{
						$datos["base64"] = $this->valor_arr;
					}
					
					//Buscar firmantes				
					$firmantes = array();
					$firmantes = $dt["data"]["digitalSignature"]["signers"];
					
					foreach( $firmantes as $key => $value ) 
					{
						$firmante = array(); //Datos de un firmante
						
						foreach( $value as $key_1 => $value_1 ) 
						{
							
							switch( $key_1 ){
								case 'identityDocument':						
									$firmante['RutFirmante'] = $value_1["personalNumber"];
									break;
									
								case 'signState':
									$firmante['Estado'] = $value_1;
									break;
									
								case 'signStateDate':
									if ( $firmante['RutFirmante'] != '' && $firmante['Estado'] == 'FIRMADO' ){
										$firmante['FechaFirma'] = date('d-m-Y H:i:s', strtotime($value_1));
									}else{
										$firmante['FechaFirma'] = '';
									}
									break;
							}
						}
								
						if( $firmante['RutFirmante'] != '' && $firmante['FechaFirma'] != '' && $firmante['Estado'] == 'FIRMADO' ) {
							
							//Colocarle de nuevo el guion 
							$firmante['RutFirmante'] = substr($firmante['RutFirmante'], 0, -1)."-".substr($firmante['RutFirmante'], -1);
							
							if( $this->actualizarFirma($datos['idDocumento'],$firmante["RutFirmante"],$firmante["FechaFirma"],$datos["base64"])){
								$this->mensajeOk = 'Se ha actualizado el Documento correctamente';
							}else{
								$this->mensajeError .= 'Ha ocurrido algún error en la actualización del Docuemnto, intente nuevamente';
							}
						}
						
					}
				}
			}
		}
		
		$this->detalle();
		return;
	
	}

	//Accion de modificar un registro 
	private function rechazar()
	{	
		$datos = $_REQUEST;

		$this->docvigentesBD->rechazar($datos);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->detalle();
		return;

	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{
		$datos = $_REQUEST;
		$datos['idDocumento'] = $datos['idDocumento_el'];

	 	$dt = new DataTable();

		// se envia a eliminar a la tabla con los datos del formulario
		$this->documentosdetBD->eliminar($datos,$dt);

		if( $this->mensajeError == '' ){
			$this->mensajeOK=" El Documento Nro: ".$datos["idDocumento"]." se ha eliminado con &eacute;xito";
		}else{
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
		}
		
		$_REQUEST['idDocumento'] = '';
		//Pasamos al listado actualizado
		$this->listado();
		return;		
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

        $datos["usuarioid"]=$this->seguridad->usuarioid;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		
		if( ! isset($datos["idEstado"]) )
			$datos['idEstado'] = -1;
	
		if( $datos['idEstado'] == 0 ) 
			$datos['idEstado'] = -1;
			
		/*if( $datos['idTipoFirma'] == '' ){
			$datos['idTipoFirma'] = 2;
		}*/
			
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->totalPorTiempo($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listadoPorTiempo($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"] = $dt->data;
		$registros = count($formulariox[0]["listado"]);

		for ($l=0; $l < count($dt->data) ; $l++)
		{
			if ($formulariox[0]["listado"][$l]["idEstado"] != 6 && $formulariox[0]["listado"][$l]["idEstado"] != 8)
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

			if( $formulariox[0]["listado"][$l]["apmaterno"] == 'NULL' )
				$formulariox[0]["listado"][$l]["apmaterno"] = '' ;

			if( $formulariox[0]["listado"][$l]["fichaid"] == '' )
				$formulariox[0]["listado"][$l]["fichaid"] = '-';

			if( isset($datos['fichaid'])) $formulariox[0]["listado"][$l]['fichaid'] = $datos['fichaid'];
			if( isset($datos['Firmante'])) $formulariox[0]["listado"][$l]['Firmante'] = $datos['Firmante'];
			if( isset($datos['fechaInicio'])) $formulariox[0]["listado"][$l]['fechaInicio'] = $datos['fechaInicio'];
			if( isset($datos['fechaFin'])) $formulariox[0]["listado"][$l]['fechaFin'] = $datos['fechaFin'];
		}
		
		$datos_filtros = $datos;
		$datos_filtros['idTipoDoc'] = '';
		$datos_filtros['idProceso'] = '';
		$datos_filtros['idEstado'] = -1;
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
	
		if ( $registros==0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);

		$formulario[0]=$datos;

		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Detalle del Documento
	private function detalle(){ 

		//Instanciar la clase

		$dt = new DataTable();
		$dt1 = new DataTable();

		$datos = $_REQUEST;
		
		$this->docvigentesBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulario=$dt->data;

		if($dt->leerFila())
		{
			$datos["idEstado"] = $dt->obtenerItem("idEstado");
		}

		//busco el total de paginas
		$datos["tipousuarioid"] = $datos["tipousuarioingid"] = $this->seguridad->tipousuarioid;
		$datos["personaid"] = $this->seguridad->usuarioid;
		
		//Firmantes en ese orden
		$this->contratofirmantesBD->ObtenerXcontrato($datos,$dt2);
		$this->mensajeError.=$this->contratofirmantesBD->mensajeError;
	    $formulario[0]["contratofirmantes"] = $dt2->data;

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Documentos_Vigentes.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
			$ver = $dt->data[0]["ver"];
		}
		
		if ( $modifica ) {
			$formulario[0]["modifica"][0] = "";

			if( $datos["idEstado"] != 8 && $datos['idEstado'] != 6 ) $formulario[0]["rechazo"][0] = "";
			//Envio manual de Gestor
			if( $datos['idEstado'] == 6 ) $formulario[0]["envioagestor"][0]   = "";			   
		}
		if ( $elimina  ) {
			$formulario[0]["elimina"][0]   = "";
			$formulario[0]["actualizar"][0]   = "";
		}
		
		if ( $ver  ) $formulario[0]["ver"][0]   = "";

		if( isset($datos['fichaid'])) $formulario[0]['fichaid'] = $datos['fichaid'];
		if( isset($datos['Firmante'])) $formulario[0]['Firmante'] = $datos['Firmante'];
		if( isset($datos['fechaInicio'])) $formulario[0]['fechaInicio'] = $datos['fechaInicio'];
		if( isset($datos['fechaFin'])) $formulario[0]['fechaFin'] = $datos['fechaFin'];

		//Pasamos los datos a la pagina
		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Detalle.html');
	}

	private function aprobar()
	{ 
		$datos = $_REQUEST;	
		$datos["RutAprobador"] = $this->seguridad->usuarioid;
		
		//$this->docvigentesBD->modificar_estado($datos);
		//$this->mensajeError=$this->docvigentesBD->mensajeError;

		$this->docvigentesBD->modificar_aprobador($datos);
		$this->mensajeError=$this->docvigentesBD->mensajeError;

		if ($this->mensajeError == "")
		{
			$this->mensajeOK = "Grabado exitosamente";
		}
		$this->listado();
		return;
	}
	
	public function verdocumento()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$fecha = date('dmY_hms');

		$datos["idDocumento"] = $datos["idDocumento_vd"];

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
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Documento.html');				
	}
	
	//Mostrar listado de los registro disponibles
	public function semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$pdtferiados)
	{  
				
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
		
		for ($fl = 0; $fl < count($dtflujos->data); $fl++) 
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

	//Listado de Documentos en proceso por firma
	public function por_firma(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;
  
  	 	$datos["usuarioid"] = $this->seguridad->usuarioid;
        $datos["Firmante"] = $datos["usuarioid"];
        $datos["idEstado"] = 0;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;

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
		$formulariox[0]["listado"] = $dt->data;
		
		for ($l=0; $l < count($dt->data) ; $l++)
		{
			//if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6)
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6 && $formulariox[0]["listado"][$l]["idEstadoDocumento"] != 8)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}
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
	
		if ( count ($dt->data) == 0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["TipoGeneracion"]=$formulariox[0]["TipoGeneracion"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Listado de Documentos Firmados
	public function firmados(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;
       
        $datos["usuarioid"] = $this->seguridad->usuarioid;
        $datos["Firmante"] = $datos["usuarioid"];
        $datos["idEstado"] = 6;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
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
		$formulariox[0]["listado"] = $dt->data;
		
		for ($l=0; $l < count($dt->data) ; $l++)
		{
			//if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6)
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6 && $formulariox[0]["listado"][$l]["idEstadoDocumento"] != 8)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}
		}
	
		$datos_filtros = $datos;
		$datos_filtros['idTipoDoc'] = '';
		$datos_filtros['idProceso'] = '';
		$datos_filtros['idEstado'] = 6;
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
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["TipoGeneracion"]=$formulariox[0]["TipoGeneracion"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Listado de Documentos Rechazados
	public function rechazados(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

        $datos["usuarioid"] = $this->seguridad->usuarioid;
        $datos["Firmante"] = $datos["usuarioid"];
        $datos["idEstado"] = 8;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
		//busco el total de paginas
		$this->docvigentesBD->total_todos($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"]=round($dt->data[0]["totalreg"]);
		
		$this->feriadosBD->listado($dtferiados);
		$this->mensajeError.=$this->feriadosBD->mensajeError;
		
		$this->flujofirmaBD->listado($dtflujos);
		$this->mensajeError.=$this->flujofirmaBD->mensajeError;

		$this->docvigentesBD->listado_todos($datos,$dt);
		$this->mensajeError.=$this->docvigentesBD->mensajeError;
		$formulariox[0]["listado"] = $dt->data;
		
		for ($l=0; $l < count($dt->data) ; $l++)
		{
			//if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6)
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6 && $formulariox[0]["listado"][$l]["idEstadoDocumento"] != 8)
			{
				$pfechacreacion 	= $formulariox[0]["listado"][$l]["FechaCreacion"];
				$pfechaultimafirma	= $formulariox[0]["listado"][$l]["FechaUltimaFirma"];
				$pdiasmax			= $formulariox[0]["listado"][$l]["DiasEstadoActual"];
				$pidwf				= $formulariox[0]["listado"][$l]["idWF"];
				$formulariox[0]["listado"][$l]["semaforodetalle"] 	= $this->semaforodetalle($pfechacreacion,$pfechaultimafirma,$pdiasmax,$dtferiados);
				$formulariox[0]["listado"][$l]["semaforototal"] 	= $this->semaforototal($pfechacreacion,$pidwf,$dtferiados,$dtflujos);
			}
		}
	
		$datos_filtros = $datos;
		$datos_filtros['idTipoDoc'] = '';
		$datos_filtros['idProceso'] = '';
		$datos_filtros['idEstado'] = 8;
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
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		//$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$formulario[0]=$datos;
		//print_r($datos);
		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["TipoGeneracion"]=$formulariox[0]["TipoGeneracion"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
			
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Listado de Documentos asociados
	public function todos(){

		// creamos una nueva instancia de la tabla
		$dt 	= new DataTable();
		$dt2 	= new DataTable();
		$dt3 	= new DataTable();
		$dt4 	= new DataTable();

		// pedimos el listado
		$datos=$_REQUEST;

        $datos["usuarioid"] = $this->seguridad->usuarioid;
        $datos["Firmante"] = $datos["usuarioid"];
		$datos["idEstado"] = -1;
  
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; 
		else $datos["pagina_anterior"]=$datos["pagina"]-1;

		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";

		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;
        
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
		$formulariox[0]["listado"] = $dt->data;
		$registros = count($formulariox[0]["listado"]);

		for ($l=0; $l < count($dt->data) ; $l++)
		{
			//if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6)
			if ($formulariox[0]["listado"][$l]["idEstadoDocumento"] != 6 && $formulariox[0]["listado"][$l]["idEstadoDocumento"] != 8)
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
		}
	
		$datos_filtros = $datos;
		$datos_filtros['idTipoDoc'] = '';
		$datos_filtros['idProceso'] = '';
		$datos_filtros['idEstado'] = -1;
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
	
		if ( $registros==0 ) 
		{
			$this->mensajeError.="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;
		}

		$this->pagina->agregarDato("pagina",$datos["pagina"]);

		$formulario[0]=$datos;

		$formulario[0]["tiposdocumentos"]=$formulariox[0]["tiposdocumentos"];	
		$formulario[0]["empresas"]=$formulariox[0]["empresas"];	
		$formulario[0]["estadocontratos"]=$formulariox[0]["estadocontratos"];
		$formulario[0]["TipoFirmas"]=$formulariox[0]["TipoFirmas"];
		$formulario[0]["Procesos"]=$formulariox[0]["Procesos"];
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentos_vigentes_Listado.html');
	}

	//Actualiza firma en BD
	public function actualizarDocumento(){

		$datos = $_REQUEST;
		
		//Actualiza el documento firmado
		if( $datos['documento'] != '' ){
			$this->documentosdetBD->modificarDocumento($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			$this->pagina->agregarDato("mensajeError",$this->mensajeError);	
		}
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
	
	//Actualiza firma en BD
	private function actualizarFirma($idDocumento, $firmante, $fechafirma, $documento){

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
	
	//Envio Manual al Gestor de firmas 
	private function envio(){
	
		$datos = $_REQUEST;

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
					$this->mensajeOK = " El documento ha sido enviado con éxito";
			}
		}
		$this->detalle();
		return;
	
	}
}
	
?>
