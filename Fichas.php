<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once('includes/fichasBD.php');
include_once('includes/fichasDatosImportacionBD.php');
include_once('includes/ChecklistDocumentosBD.php');
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/enviocorreosBD.php");
include_once("includes/tipogestorBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("firma.php");

// creamos la instacia de esta clase
$page = new fichas();

class fichas {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $fichasBD;
	private $fichasDatosImportacionBD;
	private $ChecklistDocumentosBD;
	private $firmasBD;
	private $estadocivilBD;
	private $empresasBD;
	private $cargosBD;
	private $enviocorreosBD;
	private $tipogestorBD;
	private $PlantillasBD;
	private $tipoFirmasBD;
	
	// para el manejo de las tablas
	private $holdingBD;
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
	private $siguiente = 0;

	//Iconos 
	private $opcional = '<div style="text-align: center;" title="Documento subido correctamente"><i class="fa fa-check" aria-hidden="true" style="color:green;" data-toggle="tooltip" title="Documento subido correctamente" alt="Documento subido correctamente"></i></div>';
	private $obligatorio = '<div style="text-align: center;" title="Documento Obligatorio"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:orange;" data-toggle="tooltip" title="Documento Obligatorio" alt="Documento Obligatorio"></i></div>';
	private $adicional = '<div style="text-align: center; style" title="Documento adicional"><i class="fa fa-file-text" aria-hidden="true" data-toggle="tooltip" title="Documento adicional" alt="Documento adicional"></i></div>';
	
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

		$this->opcion = "Ficha de Contrataci&oacute;n";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Ficha de Contrataci&oacute;n</li>";
		
		// instanciamos del manejo de tablas
		$this->fichasBD = new fichasBD();
		$this->fichasDatosImportacionBD = new fichasDatosImportacionBD();
		$this->ChecklistDocumentosBD = new ChecklistDocumentosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->enviocorreosBD = new enviocorreosBD();
		$this->tipogestorBD = new tipogestorBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->fichasBD->usarConexion($conecc);
		$this->fichasDatosImportacionBD->usarConexion($conecc);
		$this->ChecklistDocumentosBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->enviocorreosBD->usarConexion($conecc);
		$this->tipogestorBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		
	
		//se construye el menu
		include("includes/opciones_menu.php");
		//if( $this->seguridad->usuarioid == '26131316-2')print_r($_REQUEST);
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
			case "AGREGAR_DOC":
				$this->agregar_doc();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "PROCESAR":
				$this->procesar();
				break;
			case "VER_DOCUMENTO":
				$this->verdocumento();
				break;
			case "VER_DOCUMENTO_GENERADO":
				$this->verdocumento_generado();
				break;
			case "ELIMINAR_DOCUMENTO":
				$this->eliminar_documento();
				break;
			case "CANCELAR":
				$this->cancelar();
				break;
			case "BTN-SIGUIENTE":
				$this->siguiente();
				break;
			case "SETFIRMANTES":
				$this->setFirmantes();
				break;
		}

		// e imprimimos el pie
		$this->imprimirFin();

	}
	
	//Agregar docuemntos 
	/*private function agregar_doc()
	{	
		if ( isset ($_FILES['archivo'])){
		
			$datos = $_REQUEST; 
			$dt = new Datatable();
			$datos["idFichaOrigen"] = 1;
			$this->validar();

			//Subida de documentos adicionales
			if( isset ($datos['idTipoGestor']) ){
				$datos['tipodocumentoid'] = $datos['idTipoGestor'];
				$datos['Obligatorio'] = 2;
			}

			if ( $this->mensajeError == ''){

				$archivo = $this->carpeta.$this->NomPdf; 
				$archivoaux = file_get_contents($archivo);
				$datos["documento"] = base64_encode($archivoaux);		
				$datos["nombrearchivo"] = $this->NomPdf;
				$datos["usuarioid"] = $this->seguridad->usuarioid; 
			
				//Agregar un documento 
				$this->fichasBD->agregarDocumento($datos,$dt);
				$this->mensajeError = $this->fichasBD->mensajeError;
				
				$documentoid = $dt->data[0]['documentoid'];

				if( $this->mensajeError == '' && $documentoid > 0 ){
					
					if( $this->actualizarEstadoFicha($datos) ){
						$this->mensajeOK = "El Documento se ha subido de forma correcta";
					}

				}else{
					if( $this->mensajeError == '' ){
						$this->mensajeError = "No se pudo subir el documento, intente nuevamente";
					} 
				}
			}
		}

		$this->procesar();
	}*/
	
	//Agregar docuemntos 
	private function agregar_doc()
	{	
		if ( isset ($_FILES['archivo'])){
		
			$datos = $_REQUEST; 
			$dt = new Datatable();
			$datos["idFichaOrigen"] = 1;
			$this->validar();

			//Subida de documentos adicionales
			if( isset ($datos['idTipoGestor']) ){
				$datos['tipodocumentoid'] = $datos['idTipoGestor'];
				//$datos['Obligatorio'] = 2;
			}

			if ( $this->mensajeError == ''){

				$archivo = $this->carpeta.$this->NomPdf; 
				$archivoaux = file_get_contents($archivo);
				$datos["documento"] = base64_encode($archivoaux);		
				$datos["nombrearchivo"] = $this->NomPdf;
				$datos["usuarioid"] = $this->seguridad->usuarioid; 
			
				//Agregar un documento 
				$this->fichasBD->agregarDocumento($datos,$dt);
				$this->mensajeError = $this->fichasBD->mensajeError;
				
				$documentoid = $dt->data[0]['documentoid'];

				if( $this->mensajeError == '' && $documentoid > 0 ){
					
					if( $this->actualizarEstadoFicha($datos) ){
						$this->mensajeOK = "El Documento se ha subido de forma correcta";
					}

				}else{
					if( $this->mensajeError == '' ){
						$this->mensajeError = "No se pudo subir el documento, intente nuevamente";
					} 
				}
			}
		}

		$this->procesar();
	}

	//Validar subida de archivo
	/*private function validar()
	{	
		$datos = $_REQUEST;
		$dt = new Datatable();
		
		if( isset ($datos['idTipoGestor']) ){
			
			//Buscar nombre de tipo de documento
			$this->tipogestorBD->obtener($datos,$dt);
			$this->mensajeError.= $this->tipogestorBD->mensajeError;

			if( count($dt->data) > 0 ){
				if($dt->leerFila())
					$datos['idTipoDoc'] = $dt->obtenerItem('Nombre');
			}
		}

		$datos['idTipoDoc'] = $this->quitar_tildes($datos['idTipoDoc']);

		$this->carpeta 	= CARPETA_ARCHIVOS_SUBIDAS;
		$this->crear_carpeta ($this->carpeta);
		$this->NomPdf  	= utf8_encode($datos['idTipoDoc'])."_".$this->seguridad->usuarioid."_".date("Ymd_His").".pdf"; 
		$archivo 		= $this->carpeta.$this->NomPdf;
		
		if ($this->mensajeError == "")
		{	
			if (!COPY ($_FILES['archivo']['tmp_name'], $archivo)){
				$this->mensajeError.= "problemas para copiar el documento";
				return false;
			}
		}
		return true;
	}*/
	
	//Validar subida de archivo
	private function validar()
	{	
		$datos = $_REQUEST;
		$dt = new Datatable();
		//if( isset ($datos['idTipoGestor']) ){
			
			//Buscar nombre de tipo de documento
			$this->tipogestorBD->obtener($datos,$dt);
			$this->mensajeError.= $this->tipogestorBD->mensajeError;

			if( count($dt->data) > 0 ){
				if($dt->leerFila())
					$datos['idTipoDoc'] = $dt->obtenerItem('Nombre');
			}
		//}
		/* La siguiente funcion no hace nada
		Se lee en navegador asi: Recibo Código ÉÚtica
		var_dump($datos['idTipoDoc']);// -> imprime: string(20) "Recibo C�digo ��tica"
		//$datos['idTipoDoc'] = $this->quitar_tildes($datos['idTipoDoc']);
		*/

		$this->carpeta 	= CARPETA_ARCHIVOS_SUBIDAS;
		$this->crear_carpeta ($this->carpeta);
		$this->NomPdf  	= utf8_encode($datos['idTipoDoc'])."_".$this->seguridad->usuarioid."_".date("Ymd_His").".pdf"; 
		$archivo 		= $this->carpeta.$this->NomPdf;
		
		if ($this->mensajeError == "")
		{	
			if (!COPY ($_FILES['archivo']['tmp_name'], $archivo)){
				$this->mensajeError.= "problemas para copiar el documento";
				return false;
			}
		}
		return true;
	}

	//Crear carpeta en directorio
	private function crear_carpeta($end_directory)
	{
		try
		{
			$end_directory = $end_directory ? $end_directory : './';
			$new_path = preg_replace('/[\/]+/', '/', $end_directory);
			
			if (!is_dir($new_path))
			{
				// crea directorio si no existe
				mkdir($new_path, 0777, true);
			}else{
		
				$newelim = substr($new_path,0,-1);
				$handle = opendir( $newelim ); 
				while ($file = readdir($handle))  {
					if (is_file($new_path.$file)) {
						unlink($new_path.$file); 
					}
				}
			}	
		} 
			catch (Exception $e) {
				$this->mensajeError = 'Error, descripcion de la excepción: '.$e->getMessage();
				return 'ERROR';
		}
	}
	
	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		$datos = $_REQUEST;

		$dt = new DataTable;
		$dt2 = new DataTable;

		/*if( $datos['accion'] == 'BUSCAR' && $datos['fichaid'] != '' ){
			$datos['fichaid'] = '';
		}*/
	
		if( isset($datos['personaid'] )) $datos['buscar'] = $datos['personaid'];
		if( !isset($datos["idEstado"])) $datos["idEstado"] = 0;
		else $datos['idestado'] = $datos['idEstado'];


		$datos["codigo"] = 0;
		$datos['idFicha'] = $datos['fichaid'];

		//Preparamos los datos necesarios para la consulta 
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		//busco el total de paginas
		$this->fichasDatosImportacionBD->total($datos,$dt);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"] = round($dt->data[0]["totalreg"]); 

		$this->fichasDatosImportacionBD->listado($datos,$dt);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;

		if( count($dt->data) > 0 ){
			foreach ($dt->data as $key => $value) {
				$dt->data[$key]['NombreTrabajador'] = $dt->data[$key]['nombre_trabajador'].' '.$dt->data[$key]['appaterno_trabajador'].' '.$dt->data[$key]['apmaterno_trabajador'];
			}
		}
		$formulariox[0]["listado"]=$dt->data;
		$registros = count($formulariox[0]["listado"]);
	
		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];
		
		if ( $registros==0 ) 
		{
			$this->mensajeError="No hay informaci&oacute;n para la consulta realizada.";
			$datos["pagina_siguente"]=$datos["pagina_ultimo"]=1;

		}
		//Busco el listado de filtros de estado 
		$datos_filtros = $datos;

		//Listados de Tipos de documentos
		$this->fichasDatosImportacionBD->listadoEstados($datos_filtros,$dt);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;
		$formulariox[0]["estadosfichas"]=$dt->data;
		
		$mensajeNoDatos="";
		$formulario[0] = $datos;
		$formulario[0]["listado"] = $formulariox[0]["listado"];
		$formulario[0]["estadosfichas"] = $formulariox[0]["estadosfichas"];

		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$datos["opcionid"] = 'Fichas.php';
		$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

		if( $dt->data ){
			$crea = $dt->data[0]["crea"];
			$modifica = $dt->data[0]["modifica"];
			$elimina = $dt->data[0]["elimina"];
		}
		$num = count($formulario[0]["listado"]);

		if ( $crea ) $formulario[0]["crea"][0]	   = "";
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
			if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
		}

		$this->pagina->agregarDato("formulario",$formulario);

		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeAd",$this->mensajeAd);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_Listado.html');
	}

	//Ver documento actualizado
	private function verdocumento()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$fecha = date("dmY_hms");
		$datos["idFichaOrigen"] = 1;

		//Llenar Select de Empresas registradas
		$this->fichasBD->obtenerDocumento($datos,$dt); 
		$this->mensajeError  .= $this->fichasBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$archivob64	= $dt->obtenerItem("documento");
		}
		$extension = '.pdf';
		
		$nombrearchivo = substr($nomarchtmp,0,-4).$fecha.$extension;
			
		$subcarpeta = "./".CARPETA."/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
		
		$rutayarch  = "";
		$ruta = "./".CARPETA."/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/fichas_FormularioVerDocumento.html');				
	}

	//Ver documento actualizado
	private function verdocumento_generado()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$fecha = date("dmY_hms");
		$datos["idFichaOrigen"] = 2;

		//Llenar Select de Empresas registradas
		$this->fichasBD->obtenerDocumento($datos,$dt); 
		$this->mensajeError  .= $this->fichasBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$archivob64	= $dt->obtenerItem("documento");
		}
		$extension = '.pdf';
		
		$nombrearchivo = substr($nomarchtmp,0,-4).$fecha.$extension;
			
		$subcarpeta = "./".CARPETA."/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
		
		$rutayarch  = "";
		$ruta = "./".CARPETA."/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 
		
		$formulario[0]= $datos;
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/fichas_FormularioVerDocumentoGenerado.html');				
	}

	//Ver detalle de ficha 
	private function procesar()
	{	
		$datos = $_REQUEST;
		$dt = new DataTable();
		$dt_fichas = new Datatable();
		$dt_tipos = new Datatable();
		$band = 0;
		$datos["idFichaOrigen"] = 1;

		//Buscar datos de la ficha 
		$this->fichasDatosImportacionBD->obtener($datos,$dt_fichas);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;
		$formulario = $dt_fichas->data;	

		//Buscar datos del empleado 
		$this->fichasBD->obtenerEmpleado($datos,$dt);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$formulario[0]['empleado'] = $dt->data;
		$formulario[0]['empleado'][0]['fichaid'] = $datos['fichaid'];
		$formulario[0]['empleado'][0]['EstadoFicha'] = $dt_fichas->data[0]['EstadoFicha'];
	
			
		//Buscar datos de los documentos a generar
		$this->fichasBD->obtenerDocumentosGenerados($datos,$dt2);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		
		//Buscar si los flujos de las Plantillas
		if( count($dt2->data) ){
			
			$datos['Representantes'] = 0;
			
			foreach( $dt2->data as $key => $value ){
				
				$idPlantilla = 0;
				$idPlantilla = $dt2->data[$key]['idPlantilla'];
				
				$datos['idPlantilla'] = $idPlantilla;
				$this->PlantillasBD->obtenerEstadosFlujos($datos,$dt4);
				$this->mensajeError .= $this->PlantillasBD->mensajeError;
			
				if( count($dt4->data) > 0 ){
					
					foreach($dt4->data as $key_1 => $value_1 ){
						if( $dt4->data[$key_1]['idEstado'] == 2 ){ 
							$datos['Representantes'] = 1;
						}
					}
				}
				
			}
		}	
		//Pasar los datos del representantetante
		$formulario[0]['empleado'][0]['Representantes'] = $datos['Representantes'];
		
		//Buscar tipos de documentos
		$this->tipogestorBD->listado($dt_tipos);
		$this->mensajeError .= $this->tipogestorBD->mensajeError;
		$formulario[0]['TiposDocumentos'] = $dt_tipos->data;

		//Buscar docuemntos asociados a la ficha 
		$this->fichasBD->obtenerDocumentosXFicha($datos, $dt_docs);
		$this->mensajeError .= $this->fichasBD->mensajeError;
		
		//Buscar los firmantes segun la division de personal (centro de costo)
		$this->fichasBD->obtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$formulario[0]['empleado'][0]['rut_firmante'] = $dt3->data[0]['personaid'];
		$formulario[0]['empleado'][0]['nombre_firmante'] = $dt3->data[0]['nombre'];
	
		if( count( $dt_docs->data) > 0 ){
			//Ya tiene documentos asociados
			$band = 1;
		}

		if( count($dt_fichas->data) > 0 ){
			if( $dt_fichas->leerFila()){
				
				$TipoMovimiento = 0;
				$TipoMovimiento = $dt_fichas->ObtenerItem("TipoMovimiento"); 
			}
			
			$datos["idTipoMovimiento"] = $TipoMovimiento;
			$this->ChecklistDocumentosBD->listado($datos,$dt);
			$this->mensajeError .= $this->ChecklistDocumentosBD->mensajeError;
            
            if( count($dt->data) > 0 ){
				$cuentaObligatorios = 0;
				foreach ($dt->data as $key => $value) {
                    
                    //Agregar iconos
					/*if( $dt->data[$key]['Obligatorio'] == 0 ){
						$dt->data[$key]['Obligatorio_icono'] = $this->opcional;
					}*/
					if( $dt->data[$key]['Obligatorio'] == 1 ){
                        $dt->data[$key]['Obligatorio_icono'] = $this->obligatorio;
                        $cuentaObligatorios++;
                    }
                    
					$dt->data[$key]['fichaid'] = $datos['fichaid'];

					if( $band == 1 ){

						foreach ($dt_docs->data as $key_docs => $value_docs) {
                            
                            //Si el tipo de documento coincide con el listado, muestralo en la lista
							if( $dt_docs->data[$key_docs]['tipodocumentoid'] == $dt->data[$key]['idTipoGestor'] && $dt_docs->data[$key_docs]['idTipoSubida'] != 2 ){
								$dt->data[$key]['documento'] = $dt_docs->data[$key_docs]['nombrearchivo'];
								$dt->data[$key]['documentoid'] = $dt_docs->data[$key_docs]['documentoid'];
								$dt->data[$key]['Obligatorio_icono'] = $this->opcional;
							}
							
						}
					}
				}
				if ($cuentaObligatorios == 0)
				{
					if ($formulario[0]['idEstado'] != 5 && $formulario[0]['idEstado'] != 6)
					{
						$datos['idestado'] = 3;
						$this->fichasBD->modificarEstado($datos);
						$formulario[0]['idEstado'] = $datos['idestado'];
					}
				}
				//Si hay documentos subidos adicionales
				$this->agregarDocumentosAdicionales($dt_docs, $dt);
			}
			else if ($this->mensajeError != ' ')
			{
				//var_dump($formulario[0]['idEstado']);
				if ($formulario[0]['idEstado'] != 5 && $formulario[0]['idEstado'] != 6)
				{
					//Si hay documentos subidos adicionales
					$this->agregarDocumentosAdicionales($dt_docs, $dt);
					$datos['idestado'] = 3;
					$this->fichasBD->modificarEstado($datos);
					$formulario[0]['idEstado'] = $datos['idestado'];
				}
			}

			$formulario[0]["Checklist"] = $dt->data;		
			$formulario[0]['usuarioid'] = $this->seguridad->usuarioid;

			$this->pagina->agregarDato("formulario",$formulario);	
			$this->pagina->agregarDato("fichaid",$datos['fichaid']);			
		}
		else{
			$this->mensajeError = "La ficha seleccionada no fue generada correctamente";
		}

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioModificar.html');
	}
	
	/*private function agregarDocumentosAdicionales($dt_docs, &$dt)
	{
		foreach ($dt_docs->data as $key_docs => $value_docs) {
			if(  $dt_docs->data[$key_docs]['idTipoSubida'] == 2 ){
				$value_docs['Obligatorio_icono'] = $this->adicional;
				$value_docs['Nombre'] = $value_docs['documento'];
				$value_docs['documento'] = $value_docs['nombrearchivo'];
				array_push($dt->data,$value_docs);
			}
		}
	}*/
	
	private function agregarDocumentosAdicionales($dt_docs, &$dt)
	{
		foreach ($dt_docs->data as $key_docs => $value_docs) {
			if(  $dt_docs->data[$key_docs]['idTipoSubida'] == 2 ){
				$value_docs['Obligatorio_icono'] = $this->adicional;
				$value_docs['Nombre'] = $value_docs['documento'];
				$value_docs['documento'] = $value_docs['nombrearchivo'];
				$value_docs['idTipoGestor'] = $value_docs['tipodocumentoid'];
				array_push($dt->data,$value_docs);
			}
		}
	}

	//Elimina un documento de una Ficha
	private function eliminar_documento()
	{			
		$datos = $_REQUEST;
		$dt = new DataTable;
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["idFichaOrigen"] = 1;
		
		$this->fichasBD->EliminarDoc($datos,$dt);
		$this->mensajeError .= $this->fichasBD->mensajeError;
	
		if( $this->mensajeError == "" ){
			
			if( $this->actualizarEstadoFicha($datos) ){
				$this->mensajeOK = "El Documento fue eliminado con &eacute;xito";
			}
		}
		
		$this->procesar();
	}
	
	//Confirmar ficha 
	private function cancelar(){
		
		$datos = $_REQUEST;
		$dt = new Datatable();
		if ($datos['idEstado'] == "5")
		{
			$this->actualizarEstadoFicha($datos);
		}
		else
		{
			$datos['idestado'] = 5; //Cancelar
			$this->fichasBD->modificarEstado($datos);
			$this->mensajeError = $this->fichasBD->mensajeError;
		}
					
		if( $this->mensajeError == '' ){
			if ($datos['idestado'] == 5)
			{
				$this->mensajeOK = 'La ficha de c&oacute;digo : <b>'.$datos['fichaid'].'</b> ha sido Cancelada';
			}
			else
			{
				$this->mensajeOK = 'La ficha de c&oacute;digo : <b>'.$datos['fichaid'].'</b> ha sido Habilitada';
			}
		}
		if ($datos['idestado'] == "5")
		{
			$_REQUEST = array();
			$this->listado();
		}
		else
		{
			$this->procesar();
		}
	}
	
	//Quitar tildes 
	private function quitar_tildes($cadena) {

		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}

	private function actualizarEstadoFicha($datos){

		$dt = new Datatable();
		$resultado = 0;
		//Consultar si ya ha completado los documentos obligatorios 
		$this->fichasBD->obtenerDocumentosObligatorios($datos,$dt);
		$this->mensajeError = $this->fichasBD->mensajeError;

		if( $this->mensajeError == '' ){
		
			if( count($dt->data) > 0 ){

				if( $dt->leerFila() ){
					$documentos_subidos = $dt->obtenerItem('Subidos');
					$documentos_obligatorios = $dt->obtenerItem('Obligatorios');
				}

				if( $documentos_subidos == 0 ){
					$datos['idestado'] = 1;
				}else if( $documentos_subidos == $documentos_obligatorios ){
					$datos['idestado'] = 3;
				}else if( $documentos_subidos < $documentos_obligatorios ){
					$datos['idestado'] = 2;
				}

			}
		}else{
			return false;
		}
		//Cambiar estado de ficha 
		$this->fichasBD->modificarEstado($datos);
		$this->mensajeError = $this->fichasBD->mensajeError;

		if( $this->mensajeError == '' ){
			return true;
		}else{
			return false;
		}

	}

	private function siguiente(){

		$datos = $_REQUEST;
		$dt = new DataTable();
		$resultado = array();
		$RutTrabajador = '';

		//Buscar datos de la ficha 
		$this->fichasDatosImportacionBD->obtener($datos,$dt);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;
		
		if( $dt->leerFila() ){
			$RutTrabajador = $dt->obtenerItem('RutTrabajador');
		}

		//Quitar el guion
		$RutTrabajador = str_replace("-","",$RutTrabajador);

		//Validar si usuario existe
		$firma = new firma();
		$datos["personalNumber"] = $RutTrabajador;
		$firma->ObtenerRoles($datos,$resultado);

		if( $resultado['status']['code'] == 404 && isset( $resultado['status']['message']) ){
			echo "<br>Trabajador no existe<br>";
		}else{
			echo '<br>SIIII<br>';
		}
	}

	private function setFirmantes()
	{
		$datos = $_REQUEST;

		$dt = new DataTable();
		$dt2 = new DataTable();
		$dt3 = new DataTable();
		$dt4 = new DataTable();

		//Buscar datos de la ficha 
		$this->fichasDatosImportacionBD->obtener($datos,$dt);
		$this->mensajeError.=$this->fichasDatosImportacionBD->mensajeError;
		$idEstado = $dt->data[0]['idEstado'];
		$Estado = $dt->data[0]['EstadoFicha'];

		//Buscar los datos del empleado 
		$this->fichasBD->obtenerEmpleado($datos,$dt);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$formulario = $dt->data;
		$formulario[0]['fichaid'] = $datos['fichaid'];
		$formulario[0]['idEstado'] = $idEstado;
		$formulario[0]['EstadoFicha'] = $Estado;
		
		//Buscar datos de los documentos a generar
		$this->fichasBD->obtenerDocumentosGenerados($datos,$dt2);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$formulario[0]['listadoGeneracionDocumentos'] = $dt2->data;
		
		//Buscar si los flujos de las Plantillas
		if( count($dt2->data) ){
			
			$datos['Representantes'] = 0;
			
			foreach( $dt2->data as $key => $value ){
				
				$idPlantilla = 0;
				$idPlantilla = $dt2->data[$key]['idPlantilla'];
				
				$datos['idPlantilla'] = $idPlantilla;
				$this->PlantillasBD->obtenerEstadosFlujos($datos,$dt4);
				$this->mensajeError .= $this->PlantillasBD->mensajeError;
			
				if( count($dt4->data) > 0 ){
					
					foreach($dt4->data as $key_1 => $value_1 ){
						if( $dt4->data[$key_1]['idEstado'] == 2 ){ 
							$datos['Representantes'] = 1;
						}
					}
				}
				
			}
		}	
		//Pasar los datos del representantetante
		$formulario[0]['Representantes'] = $datos['Representantes'];

		//Buscra los firmantes segun la division de personal (centro de costo)
		$this->fichasBD->obtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$formulario[0]['listadoFirmantes'] = $dt3->data;
		
		$formulario[0]['rut_firmante'] = $dt3->data[0]['personaid'];
		$formulario[0]['nombre_firmante'] = $dt3->data[0]['nombre'];

		$num = count($dt2->data);
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $formulario[0]['listadoGeneracionDocumentos'][$i]['estado2'] === 0 )
			{
				$formulario[0]["listadoGeneracionDocumentos"][$i]["boton"][0] = $dt2->data[$i];
			}else{
				if( $formulario[0]['listadoGeneracionDocumentos'][$i]['idEstado'] > 1 ){
					$formulario[0]["listadoGeneracionDocumentos"][$i]["boton_ver"][0] = $dt2->data[$i];
				}
			}
		}
		$formulario[0]['usuarioid'] = $this->seguridad->usuarioid;
		
		//Listado de Tipo de firmas 
		$this->tipoFirmasBD->listado($dt);
		$this->mensajeError .= $this->tipoFirmasBD->mensajeError;
		$formulario[0]["idFirma"] = $dt->data;

		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioFirmantes.html');	
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>


