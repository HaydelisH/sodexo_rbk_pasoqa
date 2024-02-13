<?php

include_once('includes/Seguridad.php');
include_once("includes/rl_proveedoresBD.php");
include_once('firma.php');

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new personas();

class personas {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $rl_proveedoresBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	private $nombrearchivo="";
	private $fechahoy="";
	// funcion contructora, al instanciar
	function __construct()
	{
		$inforequest = '';
		$infovariables 	= array_keys($_REQUEST); 	// obtiene los nombres de las variables
		$infovalores 	= array_values($_REQUEST);	// obtiene los valores de las variables
		$cantparametros = count($_REQUEST);
		for($i=0;$i<$cantparametros;$i++){
			$inforequest.= $infovariables[$i].'='.$infovalores[$i].'|';
		}	
	//	$this->Graba_Log ("datos REQUEST:".$inforequest);
	
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{		
			echo 'Mensaje | No hay conexión con la base de datos!';
			exit;
		}
		
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}
		$this->rl_proveedoresBD = new rl_proveedoresBD();

		$conecc = $this->bd->obtenerConexion();
		$this->rl_proveedoresBD->usarConexion($conecc);
		
		switch ($_REQUEST["accion"])
		{
			case "BUSCAR":
				$this->buscar();
				break;
				
			case "AGREGAR":
				$this->agregar();
				break;
				
			case "AGREGAFIRMANTE":
				$this->agregarfirmante();
				break;
				
			case "BUSCARFIRMANTE":
				$this->buscarfirmante();
				break;
				
			case "AGREGARROL":
				$this->agregarrol();
				break;
		}

		$this->bd->desconectar();
		exit;
	}
	
	private function agregarrol()
	{
		$datos = $_REQUEST;
		
		$rut = explode("-",$datos["rut"]);
		$datos["personalNumber"]=$rut[0].$rut[1];
		
		$firma = new firma();
		
		$respuesta = $firma->ObtenerRoles($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		
		if ($mensajeError != '')
		{	
			if ( trim($mensajeError) != 'Error api 404' )
			{	
				echo $mensajeError;
				return;
			}
		}
		
		$existe=0;
		if ($mensajeError == '')
		{
			$cantidad = count($dt["data"]["digitalIdentity"]["roles"]);
			for ($r = 0; $r < $cantidad; $r++) 
			{
				if( $datos["rol"] == $dt["data"]["digitalIdentity"]["roles"][$r]["code"] )
				{
					$existe=1;
					break;
				}
			}	
		}
		
		if ($existe == 1)
		{
			echo '';
			return;
		}
		
		$mensajeError = '';
		$firma->mensajeError = '';
		$respuesta = $firma->AgregarRol($datos,$dt);
		$mensajeError.= $firma->mensajeError;
		if ($mensajeError != '')
		{
			echo $mensajeError;
			return;
		}
		
		echo '';
	
	}
	
	private function agregar()
	{	
		$datos = $_REQUEST;
		
		if ( $this->rl_proveedoresBD->agregar($datos,$dt))
		{	
			echo '';
		}
		else
		{
			echo $this->rl_proveedoresBD->mensajeError; 
		}
		
	}
	
	private function agregarfirmante()
	{	
		$datos = $_REQUEST;
		
		$rut_arr 	= explode("-",$datos["RutUsuario"]);
		$rut_sindv 	= $rut_arr[0];		
					
		$datos["clave"] = hash('sha256', $rut_sindv);
		$datos['rolid']= ROL_PROVEEDOR;
		$datos['TipoCorreo'] = CODIGO_CORREO_USUARIO_NUEVO;
		$datos['TipoUsuario'] = PERFIL_PROVEEDOR;
		
		if ( $this->rl_proveedoresBD->agregarFirmanteProveedor($datos,$dt))
		{	
			echo '';
		}
		else
		{
			echo $this->rl_proveedoresBD->mensajeError; 
		}
		
	}
	
	private function buscar()
	{	
		$datos = $_REQUEST;
		
		if ( $this->rl_proveedoresBD->obtenerProveedor($datos,$dt))
		{	
			if( count($dt->data) > 0)
			{
				$array = $dt->data[0];
				$array = $this->utf8_converter($array);
				echo json_encode($array);
			}
			else
			{
				echo '';
			}
		}
		else
		{
			echo $this->rl_proveedoresBD->mensajeError; 
		}
		
	}

	private function buscarfirmante()
	{	
		$datos = $_REQUEST;
		
		if ( $this->rl_proveedoresBD->obtenerFirmanteUsuario($datos,$dt))
		{	
			if( count($dt->data) > 0)
			{
				$array = $dt->data[0];
				$array = $this->utf8_converter($array);
				echo json_encode($array);
			}
			else
			{
				echo '';
			}
		}
		else
		{
			echo $this->rl_proveedoresBD->mensajeError; 
		}
		
	}
	
	private function utf8_converter($array)
	{
	    array_walk_recursive($array, function(&$item, $key){
	        if(!mb_detect_encoding($item, 'utf-8', true)){
	                $item = utf8_encode($item);
	        }
	    });
	 
	    return $array;
	}
	
	private function Graba_Log($log)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logajax_'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		   die("Problemas en la creacion");
		if (trim($log) != "")
		{
		fputs($ar,@date("H:i:s")." ".$log);
		}
		else
		{
			fputs($ar," ");
		}
		fputs($ar,"\n");
		fclose($ar);		
	}
	
}

?>