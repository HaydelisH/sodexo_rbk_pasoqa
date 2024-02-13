<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
include_once('includes/rl_imprespaldoBD.php');
include_once('includes/ChecklistDocumentosBD.php');
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/enviocorreosBD.php");
include_once("includes/tipogestorBD.php");
include_once("includes/PlantillasBD.php");
include_once("includes/tipoFirmasBD.php");
include_once("includes/opcionesxtipousuarioBD.php");
include_once("firma.php");

// creamos la instacia de esta clase
$page = new imprespaldo();

function acciones($idrespaldo,$iddocumento,$idtipogestor,$nombretipodoc,$idplantilla) 
{
	if ($idrespaldo == '')
	{
		$onclick = '';
		$onclick = 'onclick="'."muevedatosimp('".$iddocumento."','".$idtipogestor."','".$nombretipodoc."','".$idplantilla."')".'"';
		return '<button class="btn btn-primary" type="button" name="accion" data-toggle="modal" '.$onclick.' data-target="#modal_subir" value="SUBIR">Subir</button>';
	}
	else
	{
		$botones = '';
		$botones = '<button class="btn btn-primary"		type="submit" name="accion" value="VER_DOCUMENTO">Ver</button> ';
		$botones.= ' <button class="btn btn-primary"	type="submit" name="accion" onclick="return preguntarPorBorrar()" value="ELIMINAR_DOCUMENTO">Eliminar</button>';
		return $botones;
	}
}

function icono($obligatorio)
{
	if ($obligatorio == 1)
	{
		return 'SI';
	}
	
	return 'NO';
}

class imprespaldo {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $rl_imprespaldoBD;
	private $imprespaldoDatosImportacionBD;
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

		$this->opcion = "Pendientes de Respaldo";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Pendientes de Respaldo</li>";
		
		// instanciamos del manejo de tablas
		$this->rl_imprespaldoBD = new rl_imprespaldoBD();
		$this->ChecklistDocumentosBD = new ChecklistDocumentosBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->enviocorreosBD = new enviocorreosBD();
		$this->tipogestorBD = new tipogestorBD();
		$this->PlantillasBD = new PlantillasBD();
		$this->tipoFirmasBD = new tipoFirmasBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->rl_imprespaldoBD->usarConexion($conecc);
		$this->ChecklistDocumentosBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->enviocorreosBD->usarConexion($conecc);
		$this->tipogestorBD->usarConexion($conecc);
		$this->PlantillasBD->usarConexion($conecc);
		$this->tipoFirmasBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
		//print_r($_REQUEST);
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
				$this->rl_imprespaldoBD->agregarDocumento($datos,$dt);
				$this->mensajeError = $this->rl_imprespaldoBD->mensajeError;
				
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
		$datos = $_REQUEST; 
		$dt = new Datatable();
		//print_r($datos);
		//print_r($_FILES);
		if (!isset ($_FILES['archivo']))
		{
			$this->mensajeError = 'Error, no se adjunto archivo';
			$this->procesar;
			return;
		}
		
		if (strlen(($_FILES['archivo']['name']) > 100))
		{
			$this->mensajeError = 'Error, nombre de archivo no debe superar los 100 caracteres';
			$this->procesar;
			return;
		}
		
		$nombrearchivo = '';
		$nombrearchivo = $this->quitar_tildes($_FILES['archivo']['name']);
		$archivo = $_FILES['archivo']['tmp_name'];
		$archivoaux = file_get_contents($archivo);
		$datos["documento"] = base64_encode($archivoaux);		
		$datos["nombre"] = utf8_encode($nombrearchivo);
				
		$this->rl_imprespaldoBD->agregardocumento($datos);
		$this->mensajeError.= $this->rl_imprespaldoBD->mensajeError;	
		
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
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos=$_REQUEST;

		//Preparamos los datos necesarios para la consulta 
		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];
		$datos["decuantos"]="10";
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;

		//busco el total de paginas
		$this->rl_imprespaldoBD->Total($datos,$dt);
		$this->mensajeError.=$this->rl_imprespaldoBD->mensajeError;
		$datos["pagina_ultimo"]=$dt->data[0]["total"];
		$datos["total_registros"] = round($dt->data[0]["totalreg"]);
		
		if( $datos["pagina_ultimo"] == 0 ) $datos["pagina_ultimo"] = 1;
		
		$this->rl_imprespaldoBD->Listado($datos,$dt);
		$this->mensajeError.=$this->rl_imprespaldoBD->mensajeError;
		$formulariox[0]["listado"]=$dt->data;

		if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

		$formulario[0]=$datos;
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		if ($datos["pagina_ultimo"]==0)
		{
			$this->mensajeOK="No hay información para la consulta realizada.";
		}else{
			$mensajeNoDatos="";
			$this->pagina->agregarDato("pagina",$datos["pagina"]);
			$this->pagina->agregarDato("nombrex",$datos["nombrex"]);
			$formulario[0]=$datos;
			$formulario[0]["listado"]=$formulariox[0]["listado"];

			//Buscar opciones del usuario 
			$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
			$datos["opcionid"] = 'rl_imprespaldo.php';
			$this->opcionesxtipousuarioBD->Obtener($datos,$dt);

			if( $dt->data ){
				$crea = $dt->data[0]["crea"];
				$modifica = $dt->data[0]["modifica"];
				$elimina = $dt->data[0]["elimina"];
			
			}

			$num = count($formulario[0]["listado"]);

			if ( $crea     ) $formulario[0]["crear"][0]	   = "";
			for ( $i = 0 ; $i < $num ; $i++ ){
				if ( $modifica ) $formulario[0]["listado"][$i]["modifica"][0] = "";
				if ( $elimina  ) $formulario[0]["listado"][$i]["elimina"][0]   = "";
		
			}

			$this->pagina->agregarDato("formulario",$formulario);
		}
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("proveedor",$datos["proveedor"]);
		$this->pagina->agregarDato("iddocumentox",$datos["iddocumentox"]);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/rl_imprespaldo_Listado.html');
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
		$this->rl_imprespaldoBD->obtenerdocumento($datos,$dt); 
		$this->mensajeError  .= $this->rl_imprespaldoBD->mensajeError;
				
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
		$this->pagina->agregarDato("iddocumentox",$datos["iddocumentox"]);
		
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/rl_imprespaldo_FormularioVerDocumento.html');				
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
		$this->rl_imprespaldoBD->obtenerDocumento($datos,$dt); 
		$this->mensajeError  .= $this->rl_imprespaldoBD->mensajeError;
				
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
		$this->pagina->imprimirTemplate('templates/rl_imprespaldo_FormularioVerDocumentoGenerado.html');				
	}

	//Ver detalle de ficha 
	private function procesar()
	{	
		$datos = $_REQUEST;
		$dt = new DataTable();
		$dt_imprespaldo = new Datatable();
		$dt_tipos = new Datatable();
		
		$acceso = 0;
		$datosop["opcionid"]="Documentos_Aprobar.php";
		$datosop["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$this->opcionesxtipousuarioBD->Obtener($datosop,$dto);
		$this->mensajeError.=$this->opcionesxtipousuarioBD->mensajeError;
		if($dto->leerFila())
		{
			$acceso = 1;
		}
		
		$this->rl_imprespaldoBD->checklist($datos,$dt); 
		$this->mensajeError.= $this->rl_imprespaldoBD->mensajeError;	
		$formulariox[0]["listado"]=$dt->data;
		
		$formulario[0] = $datos;
		$formulario[0]["listado"] = $formulariox[0]["listado"];
		
		// evaluar si debe subir documentos de respaldo, si no debe subir se muestra boton siguiente para que pueda aprobar
		$obligatorios=0;
		for ( $o = 0 ; $o < count($dt->data) ; $o++ )
		{
			if ($dt->data[$o]["Obligatorio"] == 1 && $dt->data[$o]["nombrearchivo"] == '' )
			{
				$obligatorios++;
				break;
			}
		}
		
		if ($obligatorios == 0 && $acceso == 1)
		{
			$formulario[0]["aprobar"] = "";
		}
		//
		
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("proveedor",$datos["proveedor"]);
		$this->pagina->agregarDato("iddocumentox",$datos["iddocumentox"]);
		
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_imprespaldo_FormularioModificar.html');
	}
		
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
				
		$this->rl_imprespaldoBD->eliminardocumento($datos);
		$this->mensajeError .= $this->rl_imprespaldoBD->mensajeError;
				
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
			$this->rl_imprespaldoBD->modificarEstado($datos);
			$this->mensajeError = $this->rl_imprespaldoBD->mensajeError;
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
		$this->rl_imprespaldoBD->obtenerDocumentosObligatorios($datos,$dt);
		$this->mensajeError = $this->rl_imprespaldoBD->mensajeError;

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
		$this->rl_imprespaldoBD->modificarEstado($datos);
		$this->mensajeError = $this->rl_imprespaldoBD->mensajeError;

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
		$this->imprespaldoDatosImportacionBD->obtener($datos,$dt);
		$this->mensajeError.=$this->imprespaldoDatosImportacionBD->mensajeError;
		
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
		$this->imprespaldoDatosImportacionBD->obtener($datos,$dt);
		$this->mensajeError.=$this->imprespaldoDatosImportacionBD->mensajeError;
		$idEstado = $dt->data[0]['idEstado'];
		$Estado = $dt->data[0]['EstadoFicha'];

		//Buscar los datos del empleado 
		$this->rl_imprespaldoBD->obtenerEmpleado($datos,$dt);
		$this->mensajeError.=$this->rl_imprespaldoBD->mensajeError;
		$formulario = $dt->data;
		$formulario[0]['fichaid'] = $datos['fichaid'];
		$formulario[0]['idEstado'] = $idEstado;
		$formulario[0]['EstadoFicha'] = $Estado;
		
		//Buscar datos de los documentos a generar
		$this->rl_imprespaldoBD->obtenerDocumentosGenerados($datos,$dt2);
		$this->mensajeError.=$this->rl_imprespaldoBD->mensajeError;
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
		$this->rl_imprespaldoBD->obtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->rl_imprespaldoBD->mensajeError;
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
		
		$this->pagina->agregarDato("pagina",$datos["pagina"]);
		$this->pagina->agregarDato("proveedor",$datos["proveedor"]);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/rl_imprespaldo_FormularioFirmantes.html');	
	}
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>


