<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
//include_once('includes/fichasBD.php');
include_once('includes/opcionesxtipousuarioBD.php');
include_once('includes/empresasBD.php');
include_once('includes/tipoMovimientoBD.php');
// creamos la instacia de esta clase
$page = new setDocumentos();

class setDocumentos {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
    private $opcionesxtipousuarioBD;
	private $empresasBD;
	private $tipoMovimientoBD;
    // para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
	// para juntar los mensajes de alerta 
	private $mensajeAd="";
	// para asignar el idCategoria a un nuevo registro 
	//private $idCategoria="";
	
	/*private $opcion="";
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
	private $verde 		= '<div style="text-align: center;" title="Aprobado">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Aprobado" 		alt="En el plazo"></i></div>';
	private $amarillo	= '<div style="text-align: center;" title="Pendiente por aprobaci&oacute;n">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="Pendiente por aprobacion" 	alt="Pendiente por aprobaci&oacute;n"></i></div>';
	private $verde1 		= '<div style="text-align: center;" title="Datos completos">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="Datos completos" 		alt="En el plazo"></i></div>';
	
	private $orientacion = 'P';*/

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

		$this->opcion = "Set Documentos";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "PROCESO";
		$this->opcionnivel2 = "<li>Set Documentos</li>";
		
		// instanciamos del manejo de tablas
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->empresasBD = new empresasBD();
		$this->tipoMovimientoBD = new tipoMovimientoBD();
		/*$this->fichasBD = new fichasBD();
		$this->firmasBD = new firmasBD();
		$this->estadocivilBD = new estadocivilBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();*/
		/*$this->cargosBD = new cargosBD();
		$this->enviocorreosBD = new enviocorreosBD();*/
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->tipoMovimientoBD->usarConexion($conecc);
		/*$this->fichasBD->usarConexion($conecc);
		$this->firmasBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);*/
		/*$this->cargosBD->usarConexion($conecc);
		$this->enviocorreosBD->usarConexion($conecc);*/
		
	
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
		/*switch ($_REQUEST["accion"])
		{
			case "AGREGAR":
				$this->agregar();
				break;
			case "AGREGAR_DOC":
				$this->agregar_doc();
				break;
			case "BUSCAR":
				$this->listado();
				break;
			case "ATRAS":
				$this->atras();
				break;
			case "GENERAR":
				$this->generar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "ELIMINA":
				$this->elimina();
				break;
			case "VER_DOCUMENTO":
				$this->verdocumento();
				break;
			case "ELIMINAR_DOCUMENTO":
				$this->elimina_documento();
				break;
			case "CONFIRMAR":
				$this->confirmar();
				break;
			case "SETFIRMANTES":
				$this->setFirmantes();
				break;
		}*/

		// e imprimimos el pie
		$this->imprimirFin();

	}

/*
    private function setFirmantes()
	{
		//var_dump($_REQUEST);
		$datos = $_REQUEST;

		$datos['fichaid'] = 11;

		$dt = new DataTable;
		$dt2 = new DataTable;
		$dt3 = new DataTable;
		$this->fichasBD->obtenerEmpleado($datos,$dt);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$this->fichasBD->obtenerDocumentosGenerados($datos,$dt2);
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$this->fichasBD->obtenerFirmantes($datos,$dt3);
		$this->mensajeError.=$this->fichasBD->mensajeError;

		$formulario = $dt->data;
		$formulario[0]['listadoGeneracionDocumentos'] = $dt2->data;
		$formulario[0]['listadoFirmantes'] = $dt3->data;
		$num = count($dt2->data);
		for ( $i = 0 ; $i < $num ; $i++ ){
			if ( $formulario[0]['listadoGeneracionDocumentos'][$i]['estado2'] === 0 )
			{
				$formulario[0]["listadoGeneracionDocumentos"][$i]["boton"][0] = $dt2->data[$i];
			}
		}// fin
		$this->pagina->agregarDato("formulario",$formulario);
		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioFirmantes.html');	
	}

	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		switch ($_REQUEST["accion2"])
		{
			case "AGREGAR":

				$datos = $_REQUEST;
				$datos["personaid"] = $datos["empleadoid"] = $datos["newusuarioid"];
				$datos["tipousuarioid"] = 5;
				$datos["empresaid"] = $datos["RutEmpresa"];
				$datos["estadocivil"] = $datos["idEstadoCivil"];
				$datos["nacionalidad"] = trim($datos["nacionalidad"]);
				$datos["nacionalidad"] = trim($datos["nacionalidad"]);
				$datos["centrocostoid"] = trim($datos["lugarpagoid"]);


				if( ! isset( $datos["rolid"] )) $datos["rolid"] = ROL;
				
				$this->validar();

				if ( !$this->mensajeError )
				{
				
					$this->fichasBD->obtenerpendiente($datos,$dt);
					$this->mensajeError .= $this->fichasBD->mensajeError;
					$cantidad = count($dt->data);
					
					if ($cantidad == 0)
					{
						$archivo = $this->carpeta.$this->NomPdf;
						$archivoaux = file_get_contents($archivo);
						$datos["documento"] = base64_encode($archivoaux);		
						$datos["nombrearchivo"] = $this->NomPdf;
						$datos["tipodocumentoid"] = 10024;
														
						$rut_arr 	= explode("-",$datos["empleadoid"]);
						$rut_sindv 	= $rut_arr[0];
						
						$datos["clave"] = hash('sha256', $rut_sindv);
						
						//Agregar a fichas
						$this->fichasBD->agregar($datos,$dt); 
						$this->mensajeError .= $this->fichasBD->mensajeError;
							
						if ( $dt->data[0]["error"] == 0 ){
							if ( $dt->data[0]["fichaid"] != '' && $dt->data[0]["documentoid"] > 0 ){
					
								$this->mensajeOK = "Su ficha se ha generado con &eacutexito";				
							}
							else
							{
								$this->mensajeError .= $this->fichasBD->mensajeError;
							}
						}
						else
						{
							$this->mensajeError .= $dt->data[0]["mensaje"];
						}
					}
					else
					{
						$this->mensajeAd .= "Ya existe un proceso de contrataci&oacute;n para el trabajadador que esta ingresando (Rut Trabajador:".$datos["empleadoid"].")";
					}
				}
				
				$this->listado();
				return;
				
				break;
		}

		$datos = $_REQUEST;
		$dt = new DataTable();

		$formulario[0] = $datos;	

		//Tipo de firmas
		switch( GESTOR_FIRMA ){
			case 'DEC5' : $datos['gestor'] = 1; break;
			default: $datos['gestor'] = 2; break;
		}
		
		if(!isset($datos['rolid'])) $datos['rolid'] = ROL;

		//Tipo de firmas
		$this->firmasBD->listadoPorGestor($datos,$dt1); 
		$this->mensajeError.=$this->firmasBD->mensajeError;
		$formulario[0]["Firmas"] = $dt1->data;

		//Estados civil
		$this->estadocivilBD->listado($dt);
		$this->mensajeError.=$this->estadocivilBD->mensajeError;
		$formulario[0]["EstadoCivil"] = $dt->data;

		//Empresas
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$formulario[0]["Empresas"] = $dt->data;

		$this->pagina->agregarDato("formulario",$formulario);

		$this->pagina->agregarDato("class_active",'class="active"');
		$this->pagina->agregarDato("active_datos","active");
		$this->pagina->agregarDato("active_docs","");

		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioAgregar.html');
	}

	//Agregar docuemntos 
	private function agregar_doc()
	{	
		if ( isset ($_REQUEST['accion2'])){
		
			$datos = $_REQUEST; 

			$this->validar();

			if ( $this->mensajeError == ''){

				$archivo = $this->carpeta.$this->NomPdf;
				$archivoaux = file_get_contents($archivo);
				$datos["documento"] = base64_encode($archivoaux);		
				$datos["nombrearchivo"] = $this->NomPdf;
				$datos["tipodocumentoid"] = TIPO_DOC_GESTOR;
				$datos["usuarioid"] = $this->seguridad->usuarioid; 
				$datos["personaid"] = $datos["empleadoid"] = $datos["newusuarioid"];
				
				//Agregar un documento 
				$this->fichasBD->agregarDocumento($datos,$dt);
				
				if( $this->mensajeError == '' ){
					$this->mensajeOK = "El Documento se ha subido de forma correcta";
				}
			}
		}

		$datos=$_REQUEST;

		$dt = new DataTable();
		$dt1 = new DataTable();

		$usuarioid = $this->seguridad->usuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="10";

		$this->fichasBD->obtener($datos,$dt_fichas);
		$this->mensajeError.=$this->fichasBD->mensajeError;

		if( count($dt_fichas->data) > 0 ){
			
			$formulario = $dt_fichas->data;	

			//Consultar si el estado es confirmado

			if( $dt_fichas->data[0]["estadoid"] == 3 ){ //[3] = Confirmado
				$formulario[0]["band"] = 1 ;
			}

			//Listado de documentos del empleado 
			$this->fichasBD->obtenerDocumentosXFicha($datos,$dt2);
			$this->mensajeError.=$this->fichasBD->mensajeError;
			$formulario[0]["documentos_subidos"] = $dt2->data;

			//Estados civil
			$this->estadocivilBD->listado($dt);
			$this->mensajeError.=$this->estadocivilBD->mensajeError;
			$formulario[0]["EstadoCivil"] = $dt->data;

			//Tipo de firmas
			switch( GESTOR_FIRMA ){
				case 'DEC5' : $datos['gestor'] = 1; break;
				default: $datos['gestor'] = 2; break;
			}
			
			//Tipo de firmas
			$this->firmasBD->listadoPorGestor($datos,$dt1); 
			$this->mensajeError.=$this->firmasBD->mensajeError;
			$formulario[0]["Firmas"] = $dt1->data;

			//Empresas
			$this->empresasBD->listado($dt);
			$this->mensajeError.=$this->empresasBD->mensajeError;
			$formulario[0]["Empresas"] = $dt->data;
		
			if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

			if ($datos["pagina_ultimo"]==0)
			{
				$mensajeNoDatos="No hay información para la consulta realizada.";
			}else{
				$mensajeNoDatos="";
			}

			$formulario[0]["pagina_siguente"] = $datos["pagina_siguente"];
			$formulario[0]["pagina_primera" ] = $datos["pagina_primera" ];
			$formulario[0]["pagina_ultimo"  ] = $datos["pagina_ultimo"  ];
			$formulario[0]["pagina_actual"  ] = $datos["pagina_actual"  ];

			$this->pagina->agregarDato("formulario",$formulario);
			
			$this->pagina->agregarDato("fichaid",$_REQUEST["fichaid"]);
			$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
			$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
			
		}
		else{
			$this->mensajeError = "La ficha seleccionada no fue generada correctamente";
		}

		$this->pagina->agregarDato("formulario",$formulario);

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		$this->pagina->agregarDato("class_active_df",'');
		$this->pagina->agregarDato("class_active_sd",'class="active"');
		$this->pagina->agregarDato("active_datos","");
		$this->pagina->agregarDato("active_docs","active");

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioModificar.html');
		
	}

	//Validar subida de archivo
	private function validar()
	{	
		$datos=$_REQUEST;
		
		$this->carpeta 	= CARPETA_ARCHIVOS_SUBIDAS;
		$this->crear_carpeta ($this->carpeta);
		$this->NomPdf  	= $this->seguridad->usuarioid."_".date("Y-m-d-H-i-s").".pdf";
		$archivo 		= $this->carpeta.$this->NomPdf;
		
		if ($this->mensajeError == "")
		{	
			if (!COPY ($_FILES['archivo']['tmp_name'], $archivo)){
				$this->mensajeError.= "problemas para copiar el documento";
			}
		}
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
	*/
	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		$datos = $_REQUEST;

		$dt = new DataTable;
		$dt2 = new DataTable;
		$this->empresasBD->listado($dt);
		$this->mensajeError.=$this->empresasBD->mensajeError;
		$this->tipoMovimientoBD->obtener($dt2);
		$this->mensajeError.=$this->tipoMovimientoBD->mensajeError;
		$formulario = $dt->data;
		$formulario2 = $dt2->data;

		$this->pagina->agregarDato("listadoEmpresas",$formulario);
		$this->pagina->agregarDato("tipoMovimiento",$formulario2);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/setDocumentos_Agregar.html');
	}

/*
	//Generar un documento individual 
	private function generar(){
				
		$datos = $_REQUEST;
	
		$generar = new generar();

		$respuesta = array();
		$respuesta = $generar->GenerarDocumento($datos);

		if( $respuesta['estado'] ){
			
			$idDocumento = '';
			$idDocumento = $respuesta['data'];
			$this->mensajeOK = $respuesta['mensaje'];
			$this->verdocumento($idDocumento);

		}else{

			$this->mensajeError = $respuesta['mensaje'];
			$this->agregar();
		}
	}

	//Ver documento actualizado
	private function verdocumento()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_REQUEST;
		$fecha = date("dmY_hms");

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

	//Ver detalle de ficha 
	private function modificar()
	{	
		switch ($_REQUEST["accion2"])
		{
			case "MODIFICAR":

				$datos = $_REQUEST;
				$datos["personaid"] = $datos["empleadoid"] = $datos["newusuarioid"];
				$datos["tipousuarioid"] = 5;
				$datos["estadocivil"] = $datos["idEstadoCivil"];
			
				if( ! isset( $datos["rolid"] )) $datos["rolid"] = ROL;		

				//Agregar a fichas
				$this->fichasBD->modificar($datos,$dt); 
				$this->mensajeError .= $this->fichasBD->mensajeError;
				
				if( $this->mensajeError == '' )
				{
					$this->mensajeOK = "Su ficha ha sido modificada con &eacute;xito";
				}								
				break;
		}

		$this->vista_modificar();
	}

	//Elimina Ficha
	private function elimina()
	{			
		$datos=$_REQUEST;
		$dt = new DataTable;

		$datos["usuarioid"]=$this->seguridad->usuarioid;
	
		if ( $this->respaldarFicha($datos) )
		{
			if(  $this->fichasBD->eliminar($datos) )
				$this->mensajeOK = "Su ficha de c&oacute;digo <b> ".$datos['fichaid']."</b> ha sido eliminada con &eacute;xito";
			{
				$this->listado();
				return;
			}
			$this->mensajeError.=$this->fichasBD->mensajeError;
			$this->listado();
			return;	
		}
		$this->mensajeError.=$this->fichasBD->mensajeError;
		$this->listado();
		return;	
	}
	
	//Elimina un documento de una Ficha
	private function elimina_documento()
	{			
		$datos=$_REQUEST;
		$dt = new DataTable;
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		
		$this->fichasBD->EliminarDoc($datos,$dt);
		$this->mensajeError .= $this->fichasBD->mensajeError;
	
		if( $this->mensajeError == "" )
			$this->mensajeOK = "El Documento fue eliminado con &eacute;xito";
		
		$this->agregar_doc();
	}
	
	private function respaldarFicha($datos)
	{	
		$this->fichasBD->respaldar($datos);
		$this->mensajeError = $this->fichasBD->mensajeError;
		
		if( $this->mensajeError == '' )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//Confirmar ficha 
	private function confirmar(){
		
		$datos = $_REQUEST;
		$dt = new Datatable();
		
		//Buscar el ultimo documento generado 
		$this->fichasBD->obtenerMaxDoc($datos,$dt);
		$this->mensajeError .= $this->fichasBD->mensajeError;
		$docMax = $dt->data[0]['documentoid'];
					
		if( $this->mensajeError == '' ){
		
			$this->fichasBD->modificarEstado($datos);
			$this->mensajeError = $this->fichasBD->mensajeError;
			
			if( $this->mensajeError == '' ){
		
				$datos["estado"] = 4;
				$datos["documentoid"] = $docMax;
				
				//Enviar documento
				$this->enviocorreosBD->agregar($datos);
				$this->mensajeError  = $this->enviocorreosBD->mensajeError;
				
				if( $this->mensajeError == '' ) {
					$this->mensajeOK = "La ficha de codigo <b>".$datos["fichaid"]."</b> ha sido confirmada";
				}
			}
		}
		
		$this->vista_modificar();
		
	}
	
	private function vista_modificar(){
	
		$datos = $_REQUEST;
		$dt = new DataTable();
		$dt1 = new DataTable();

		$usuarioid = $this->seguridad->usuarioid;
		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;

		if (!isset($datos["pagina"])) $datos["pagina"]="1";
		if ($datos["pagina"]==1) $datos["pagina_anterior"]="1"; else $datos["pagina_anterior"]=$datos["pagina"]-1;
		$datos["pagina_siguente"]=$datos["pagina"]+1;
		$datos["pagina_primera"]=1;
		$datos["pagina_ultimo"]=1;
		$datos["pagina_actual"]=$datos["pagina"];

		$datos["decuantos"]="10";

		$this->fichasBD->obtener($datos,$dt_fichas);
		$this->mensajeError.=$this->fichasBD->mensajeError;

		if( count($dt_fichas->data) > 0 ){
			
			$formulario = $dt_fichas->data;	

			//Consultar si el estado es confirmado

			if( $dt_fichas->data[0]["estadoid"] == 3 ){ //[3] = Confirmado
				$formulario[0]["band"] = 1 ;
			}

			//Listado de documentos del empleado 
			$this->fichasBD->obtenerDocumentosXFicha($datos,$dt2);
			$this->mensajeError.=$this->fichasBD->mensajeError;
			$formulario[0]["documentos_subidos"] = $dt2->data;

			//Estados civil
			$this->estadocivilBD->listado($dt);
			$this->mensajeError.=$this->estadocivilBD->mensajeError;
			$formulario[0]["EstadoCivil"] = $dt->data;

			//Tipo de firmas
			switch( GESTOR_FIRMA ){
				case 'DEC5' : $datos['gestor'] = 1; break;
				default: $datos['gestor'] = 2; break;
			}
			
			//Tipo de firmas
			$this->firmasBD->listadoPorGestor($datos,$dt1); 
			$this->mensajeError.=$this->firmasBD->mensajeError;
			$formulario[0]["Firmas"] = $dt1->data;

			//Empresas
			$this->empresasBD->listado($dt);
			$this->mensajeError.=$this->empresasBD->mensajeError;
			$formulario[0]["Empresas"] = $dt->data;
		
			if ($datos["pagina_siguente"]>$datos["pagina_ultimo"]) $datos["pagina_siguente"]=$datos["pagina_ultimo"];

			if ($datos["pagina_ultimo"]==0)
			{
				$mensajeNoDatos="No hay información para la consulta realizada.";
			}else{
				$mensajeNoDatos="";
			}

			$formulario[0]["pagina_siguente"] = $datos["pagina_siguente"];
			$formulario[0]["pagina_primera" ] = $datos["pagina_primera" ];
			$formulario[0]["pagina_ultimo"  ] = $datos["pagina_ultimo"  ];
			$formulario[0]["pagina_actual"  ] = $datos["pagina_actual"  ];

			$this->pagina->agregarDato("formulario",$formulario);
			
			$this->pagina->agregarDato("fichaid",$_REQUEST["fichaid"]);
			$this->pagina->agregarDato("pagina",$_REQUEST["pagina"]);
			$this->pagina->agregarDato("nombrex",$_REQUEST["nombrex"]);
			
		}
		else{
			$this->mensajeError = "La ficha seleccionada no fue generada correctamente";
		}

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		$this->pagina->agregarDato("class_active",'class="active"');
		$this->pagina->agregarDato("active_datos","active");
		$this->pagina->agregarDato("active_docs","");

		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/fichas_FormularioModificar.html');	
	}
*/
	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>


