<?php
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/usuariosmantBD.php");
include_once("includes/enviocorreosBD.php");

//Opcion del AJAX para el Vista Previa
$page = new olvidoclave();

class olvidoclave {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $usuariosmantBD;
	private $enviocorreosBD;
	private $usuariosBD;
	// para juntar los mensajes de error
	private $mensajeError="";

	// funcion contructora, al instanciar
	function __construct()
	{
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{		
			echo 'Mensaje | No hay conexi�n con la base de datos!';
			exit;
		}
		
		// creamos la seguridad
		$this->seguridad = new Seguridad($this->pagina,$this->bd);

		// instanciamos del manejo de tablas
    	$this->usuariosmantBD = new usuariosmantBD();
		$this->enviocorreosBD = new enviocorreosBD();
    	$this->usuariosBD = new usuariosBD();
		
		// si se pudo abrir entonces usamos la conecion en nuestras tablas	
		$conecc = $this->bd->obtenerConexion();
		$this->usuariosmantBD->usarConexion($conecc);
		$this->enviocorreosBD->usarConexion($conecc);
		$this->usuariosBD->usarConexion($conecc);
		
		$dt = new DataTable();
		$datos = $_REQUEST;
		$largo = 10;
		$cl = '' ;
		
		$datos['clave'] = $this->generar_password_complejo($largo); 	
		$datos['newusuarioid'] = $datos['usuarioid'];
		
		//Actualizar clave de usuario
		$this->usuariosmantBD->AgregarClaveTemporal($datos);
		$this->mensajeError .= $this->usuariosmantBD->mensajeError;
		
		$cl = hash('sha256',$datos['clave']); 
		$datos['clave'] = $cl;
		
		//Actualizar clave de usuario
		$this->usuariosmantBD->CambiarClave($datos);
		$this->mensajeError .= $this->usuariosmantBD->mensajeError;
		
		//Enviar correo con clave temporal 
		$datos['estado'] = 7;
		$datos['TipoCorreo'] = 1;
		
		$this->usuariosBD->obtener($datos, $dt);
		$this->mensajeError .= $this->usuariosmantBD->mensajeError;
		if (count($dt->data) > 0)
		{
			if ( $this->enviocorreosBD->agregarSinDocumento($datos) )
				echo 'Su clave ha sido enviada a su correo';
			//else
				//echo $this->enviocorreosBD->mensajeError;
		}
		else
		{
			echo 'No es posible recuperar la contraseña, contacte con su administrador';
		}
				
		$this->bd->desconectar();
		exit;
	}
	
	private function generar_password_complejo($largo){
		$cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$cadena_base .= '0123456789' ;
		//$cadena_base .= '!@#%&*()_,.<>?;:[]{}|=+';

		$password = '';
		$limite = strlen($cadena_base) - 1;

		for ($i=0; $i < $largo; $i++)
			$password .= $cadena_base[rand(0, $limite)];

		return $password;
	}

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logexterno'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}
}
?>