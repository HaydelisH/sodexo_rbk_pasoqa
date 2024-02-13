<?php

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// incluimos la clase para las tablas que vamos a ocupar
include_once('includes/Seguridad.php');
include_once("includes/opcionesxtipousuarioBD.php");
include_once("includes/panelBD.php");
include_once("includes/firmasdocBD.php");
include_once("includes/documentosBD.php");
include_once("includes/docvigentesBD.php");

// creamos la instacia de esta clase
$page = new inicio();

class inicio {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $panelBD;
	private $firmasdocBD;
	private $documentosBD;
	private $docvigentesBD;
	// para juntar los mensajes de error
	private $mensajeError="";
	private $infoconsulta="";
	
	private $nroopcion=0; //número de opción este debe estar en la tabla opcionessistema
	private $consulta=0;
	private $elimina=0;
	private $crea=0;
	private $modifica=0;
	private $ver=0;

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
		if (isset($_REQUEST["mensajeError"])) $this->mensajeError.=$_REQUEST["mensajeError"];

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
		
		
		$this->opcionicono = "fa fa-tachometer";
		$this->opcionnivel1 = "Panel";

		$this->opcionesxtipousuarioBD = new opcionesxtipousuarioBD();
		$this->panelBD = new panelBD();
		$this->firmasdocBD = new firmasdocBD();
		$this->documentosBD = new documentosBD();
		$this->docvigentesBD = new docvigentesBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$conecc=$this->bd->obtenerConexion();
		$this->opcionesxtipousuarioBD->usarConexion($conecc);
		$this->panelBD->usarConexion($conecc);
		$this->firmasdocBD->usarConexion($conecc);
		$this->documentosBD->usarConexion($conecc);
		$this->docvigentesBD->usarConexion($conecc);
	
		//se construye el menu
		include("includes/opciones_menu.php");

		// mostramos el listado
		$this->listado();

		// e imprimimos el pie
		$this->imprimirFin();

	}

	private function listado()
	{	
		$dt = new DataTable();
		$dt1 = new DataTable();

		$datos["usuarioid"]=$this->seguridad->usuarioid;
		$datos["ptipousuarioid"]=$this->seguridad->tipousuarioid;

		$this->panelBD->Obtener($datos,$dt);
		$this->mensajeError.=$this->panelBD->mensajeError;
		$formulario=$dt->data;

		$this->docvigentesBD->listadoRecientes($datos, $dt1);
		$this->mensajeError .= $this->docvigentesBD->mensajeError;

		if ( $dt1->data ){
			foreach ($dt1->data as $key => $value) {
				switch ($dt1->data[$key]["Nombre"]) {
				 	case 'Firmado':
				 		$dt1->data[$key]["color"] = "label label-success";
				 		break;
				 	case 'Generado en espera de aprobacion':
				 		$dt1->data[$key]["color"] = "label label-warning";
				 		break;
				 	case 'Rechazado':
				 		$dt1->data[$key]["color"] = "label label-danger";
				 		break;
				 	default:
				 		$dt1->data[$key]["color"] = "label label-warning";
				 		break;
				 } 
			}
		}else{
			$dt1->data[0]["NombreTipoDoc"] = "No hay Documentos";
			$dt1->data[0]["idDocumento_Gama"] = " ";
		}
		$formulario[0]["doc_ultimos"] = $dt1->data;
	
		//Buscar opciones del usuario 
		$datos["tipousuarioid"] = $this->seguridad->tipousuarioid;
		$this->opcionesxtipousuarioBD->Listado($datos,$dt);
		$action = '';
		//print_r($dt->data);
		if( count($dt->data) > 0 ){
			foreach( $dt->data as $key => $value ){
				
				foreach( $value as $key_1 => $value_1 ) {
				
					if( ! is_numeric($key_1 ) ){
					
						//echo $key_1."  ".$value_1."<br>";
						if(  $value_1 == 'MisDocumentos.php' ) { //TRABAJADOR
							$action = 'MisDocumentos.php';
							break ;
						}
						if(  $value_1 == 'Documentos_Vigentes.php' ) { //REPRESETANTES
							$action = 'Documentos_Vigentes.php';
							break;
						}
						if( $action == '' ){
							$action='#';
						}
					}
				}
			}
		}

		// agregamos a la pagina el mensaje de error
		$this->pagina->agregarDato("action",$action);
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("formulario",$formulario);
		
		// agregamos a la pagina los datos del listado
		$this->pagina->imprimirTemplate('templates/panel.html');
	}

	private function imprimirFin()
	{
		include("includes/opciones_fin.php");
	}


	

}
?>