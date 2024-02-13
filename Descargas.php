<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/descargasBD.php");
include_once("includes/holdingBD.php");
include_once("includes/tiposusuariosBD.php");
include_once("includes/opcionesxtipousuarioBD.php");

// creamos la instacia de esta clase
$page = new descargas();


class descargas {

	// Para armar la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $descargasBD;
	// para el manejo de las tablas
	private $holdingBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	// para juntar los mensajes de Ok
	private $mensajeOK="";
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

	private $icono_pdf = '<div style="text-align: center;" title="PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></div>';
	private $icono_excel = '<div style="text-align: center;" title="XLS/XLSX"><i class="fa fa-file-excel-o" aria-hidden="true"></i></div>';
	private $icono_image = '<div style="text-align: center;" title="GIF/JPEG/JPG/PNG"><i class="fa fa-file-image-o" aria-hidden="true"></i></div>';
	private $icono_text = '<div style="text-align: center;" title="TXT"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>';
	
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

		$this->opcion = "Descargas ";
		$this->opciondetalle = "";
		$this->opcionicono = "fa fa-cog";
		$this->opcionnivel1 = "MANTENEDORES";
		$this->opcionnivel2 = "<li>Descargas</li>";
		
		// instanciamos del manejo de tablas
		$this->descargasBD = new descargasBD();
		$this->holdingBD = new holdingBD();
		$this->tiposusuariosBD = new tiposusuariosBD();
		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		
		$conecc = $this->bd->obtenerConexion();
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->descargasBD->usarConexion($conecc);
		$this->holdingBD->usarConexion($conecc);
		$this->tiposusuariosBD->usarConexion($conecc);
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		
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
			case "AGREGAR":
				$this->agregar();
				break;
			case "ELIMINAR":
				$this->eliminar();
				break;
			case "DESCARGAR":
				$this->descargar();
				break;
			case "MODIFICAR":
				$this->modificar();
				break;
			case "BUSCAR":
				$this->listado();
				break;
		}
		// e imprimimos el pie
		$this->imprimirFin();

	}
	//Accion del boton agregar un nuevo registro 
	private function agregar()
	{	
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "AGREGAR":

					$dt = new DataTable();
					$datos = $_POST;

					if( is_uploaded_file($_FILES['archivo']["tmp_name"]) ){
						 //Revision del archivo subido 
						$nombre = str_replace(" ", "_",($_FILES['archivo']["name"]));
						$ruta = getcwd();
						$ruta .=  "/tmp/"; 
						$ruta .= $nombre; 
						$tipo = $_FILES['archivo']["type"];
						
						if( $tipo == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
							$tipo = "application/vnd.ms-excel";
						}

						if( move_uploaded_file($_FILES['archivo']["tmp_name"], $ruta) ){

							$archivoaux = file_get_contents($ruta);

							//Construir datos
						    $doc_aux = array();
					    	$doc_aux["Nombre"] = $nombre;
							$doc_aux["Tipo"] = $tipo;
							$doc_aux["Ruta"] = $ruta;
							$doc_aux["B64"] = base64_encode($archivoaux);//el archivo en base 64
							$doc_aux["Descripcion"] = $datos["Descripcion"];//el archivo en base 64

							// enviamos los datos del formulario a guardar
							if ($this->descargasBD->agregar($doc_aux,$dt))
							{
								//Pasamos el mensaje de Ok
								$this->mensajeOK="Registro Completado! Su registro se ha guardado con &eacute;xito";
								$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
								$this->listado();
								return;
							}
							//Pasamos el error si hubo
							$this->mensajeError.=$this->descargasBD->mensajeError;
							$this->pagina->agregarDato("mensajeError",$this->mensajeError);
							$this->listado();
							return;
							
						}
						else{

							$this->mensajeError = "Ha ocurrido un error inesperado en la subida del archivo : ".$nombre. ". Int&eacute;ntelo nuevamente"; 
						}
					}
					$this->listado();
			        return;

				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		//Asignamos los datos que recibimos del formulario
		$this->descargasBD->listado($dt);
		$this->mensajeError.=$this->descargasBD->mensajeError;
		$formulario[0]["descargas"]=$dt->data;		
		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/descargas_FormularioAgregar.html');
	}

	//Accion del boton agregar un nuevo registro 
	private function modificar()
	{	
		// si hubo algun evento
		if (isset($_POST["accion2"]))
		{
			// revisamos
			switch ($_POST["accion2"])
			{
				case "MODIFICAR":

					$dt = new DataTable();
					$datos = $_POST;

					if( $_FILES['archivo']["name"] != '' ){

						if( is_uploaded_file($_FILES['archivo']["tmp_name"]) ){
							 //Revision del archivo subido 
							$nombre = str_replace(" ", "_",($_FILES['archivo']["name"]));
							$ruta = getcwd();
							$ruta .=  "/tmp/"; 
							$ruta .= $nombre; 
							$tipo = $_FILES['archivo']["type"];
							
							if( $tipo == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
								$tipo = "application/vnd.ms-excel";
							}

							if( move_uploaded_file($_FILES['archivo']["tmp_name"], $ruta) ){

								$archivoaux = file_get_contents($ruta);

								//Construir datos
							    $doc_aux = array();
							    $doc_aux["idDescarga"] = $datos["idDescarga"];
						    	$doc_aux["Nombre"] = $nombre;
								$doc_aux["Tipo"] = $tipo;
								$doc_aux["Ruta"] = $ruta;
								$doc_aux["B64"] = base64_encode($archivoaux);//el archivo en base 64
								$doc_aux["Descripcion"] = $datos["Descripcion"];//el archivo en base 64

								// enviamos los datos del formulario a guardar
								if ($this->descargasBD->modificar($doc_aux,$dt))
								{
									//Pasamos el mensaje de Ok
									$this->mensajeOK="Registro Completado! Su registro se ha guardado con &eacute;xito";
									$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
									$this->listado();
									return;
								}
								//Pasamos el error si hubo
								$this->mensajeError.=$this->descargasBD->mensajeError;
								$this->pagina->agregarDato("mensajeError",$this->mensajeError);
								$this->listado();
								return;
							}
							else{
								$this->mensajeError = "Ha ocurrido un error inesperado en la subida del archivo : ".$nombre. ". Int&eacute;ntelo nuevamente"; 
							}
						}
						$this->listado();
			        	return;
					}else{
						// enviamos los datos del formulario a guardar
						if ($this->descargasBD->modificarDescripcion($datos,$dt))
						{
							//Pasamos el mensaje de Ok
							$this->mensajeOK="Registro Completado! Su registro se ha guardado con &eacute;xito";
							$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
							$this->listado();
							return;
						}
						//Pasamos el error si hubo
						$this->mensajeError.=$this->descargasBD->mensajeError;
						$this->listado();
						return;
					}
					
				case "VOLVER":
					// mostramos el listado
					$this->listado();
					return;
			}
		}

		$datos = $_POST;

		//Asignamos los datos que recibimos del formulario
		$this->descargasBD->obtener($datos, $dt);
		$this->mensajeError.=$this->descargasBD->mensajeError;

		$formulario[0]["listado"] = $dt->data;		

		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el formulario
		$this->pagina->imprimirTemplate('templates/descargas_FormularioModificar.html');
	}
	
	//Accion de eliminar un registro
	private function eliminar()
	{		

		$datos = $_POST;
		
		// se envia a eliminar a la tabla con los datos del formulario
		if ( $this->descargasBD->eliminar($datos)) {

			$this->mensajeOK = "Su archivo ha sido eliminado con &eacute;xito";
			$this->listado();
			return;
		}

		$this->mensajeError.=$this->descargasBD->mensajeError;
		//Pasamos al listado actualizado
		$this->listado();
		return;
		
	}

	//Accion de descargar un registro
	private function descargar()
	{		
		$datos = $_POST;

		// se envia a eliminar a la tabla con los datos del formulario
		$this->descargasBD->obtener($datos,$dt);
		$this->mensajeError.= $this->descargasBD->mensajeError;

		if( count( $dt->data ) > 0 ){
			if ( $dt->data[0]["Tipo"] == 'application/pdf' ){
				$this->verdocumento($datos);
				return;
			}else{
				$this->verdocumentoOtros($datos);
				return;
			}
		} 
		$this->listado();
		return;
	}

	//Mostrar listado de los registro disponibles
	private function listado()
	{  
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();

		// pedimos el listado
		$datos=$_POST;
		$datos["idCategoria"]=$this->seguridad->idCategoria;
		$datos["tipousuarioingid"]=$this->seguridad->tipousuarioid;

		$this->descargasBD->listado($dt);
		$this->mensajeError.=$this->descargasBD->mensajeError;

		if( count( $dt->data ) > 0 ){

			foreach ($dt->data as $key => $value) {
				
				$tipo = $dt->data[$key]["Tipo"];

				switch ($tipo) {
					case 'application/vnd.ms-excel':
						$dt->data[$key]["Tipo"] = $this->icono_excel;
						break;
					
					case 'application/pdf':
						$dt->data[$key]["Tipo"] = $this->icono_pdf;
						break;

					case 'image/gif':
						$dt->data[$key]["Tipo"] = $this->icono_image;
						break;

					case 'image/png':
						$dt->data[$key]["Tipo"] = $this->icono_image;
						break;

					case 'image/jpeg':
						$dt->data[$key]["Tipo"] = $this->icono_image;
						break;

					case 'image/jpg':
						$dt->data[$key]["Tipo"] = $this->icono_image;
						break;

					case 'text/plain':
						$dt->data[$key]["Tipo"] = $this->icono_text;
						break;
				}

			}
		} 

		$formulariox[0]["listado"]=$dt->data;
		
		$formulario[0]["listado"]=$formulariox[0]["listado"];

		$this->pagina->agregarDato("formulario",$formulario);
		//Pasamos los mensajes a la pagina 
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/descargas_Listado.html');
	}

	//Ver Documento PDF
	private function verdocumento($data){

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $data;
	
		$this->descargasBD->obtener($datos,$dt);		
		$this->mensajeError=$this->descargasBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nombrearchivo = $dt->obtenerItem("Nombre");
			$tipo 	= $dt->obtenerItem("Tipo");
			$archivob64	= $dt->obtenerItem("B64");
			//$ruta	= $dt->obtenerItem("Ruta");
		}
		
			
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
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/descargas_Documento.html');					
	}

	//Ver Documento PDF
	private function verdocumentoOtros($data){

		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $data;
	
		$this->descargasBD->obtener($datos,$dt);		
		$this->mensajeError=$this->descargasBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nombrearchivo = $dt->obtenerItem("Nombre");
			$tipo 	= $dt->obtenerItem("Tipo");
			$archivob64	= $dt->obtenerItem("B64");
			//$ruta	= $dt->obtenerItem("Ruta");
		}
		
			
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
        //print_r($formulario);
		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/descargas_DocumentoOtros.html');					
	}
	

	//Mostrar fin
	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}
}
?>
