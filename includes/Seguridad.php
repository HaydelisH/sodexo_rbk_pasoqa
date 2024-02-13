<?php

// incluir libreria de objectos
include_once("includes/usuariosBD.php");
include_once("includes/Paginas.php");

class Seguridad
{
	// PROPIEDADES ***********************************************************************************

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tablas
	private $usuariosBD;

	// para juntar los mensajes de error
	public $mensajeError="";

	private $creada=false;
	public $session;
	public $rut;
	public $ip = "";
	public $nombre = "";
	public $administrador = false;
	public $ejecutivo = false;
	public $logeado = false;
	public $tipousuarioid;
	public $nombreperfil;
	private $logear;

	private $datos;
	public $nuevasession = false;

	public $minutosinactividad = 20; //tiempo de inactividad representado en minutos
	public $ultimavez;
	public $fechaactual;
	public $difminutos;
	public $nuevoperfil = false;
	
	// OPERACIONES ***********************************************************************************
	// constructor de la clase, punto de entrada
	public function Seguridad(&$pagina, &$con)
	{
		$this->bd = $con;
		
		// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = $pagina;
		// instanciamos del manejo de tablas
		$this->usuariosBD = new usuariosBD();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas
		$this->usuariosBD->usarConexion($this->bd->conexion);

		if (!$this->creada)
		if (isset($_SERVER["HTTP_REFERER"])) {
			if ($_SERVER["HTTP_REFERER"]!="") {
				$host="www.pyme.cl";
				if (substr($_SERVER["HTTP_REFERER"],7,strlen($host))!=$host) {
					$this->datos["pagina"]=$_SERVER["HTTP_REFERER"];
					//$this->usuariosBD->referenciaPaginaWeb($this->datos,$dt);
					//$this->mensajeError .= $this->usuariosBD->accederError();
				}
			}
		}
		$this->creada=true;
	}

	public function sesionar($obligarlogear = true)
	{	
		$nuevasession = false;
		// Verificamos dsi hay alguna session
		if (!$this->obtenerSesion())
		{
			// si es un operador debemos hacer que se loggee denuevo
			if (!$this->obtenerRutContrasena())
			{
				// no es un rut hay que logearse como operador

				if (isset($_REQUEST["mensajeError"]))
					$this->mensajeError=$_REQUEST["mensajeError"];

				if ($_SERVER["PHP_SELF"]!="/pyme/index.php")
					if ($this->mensajeError=="") $this->mensajeError .= MensajeUsuario::DEBE_INICIARSESION;
				$this->datos["session"] = "";
				$this->logear = true;
				if ($obligarlogear) $this->imprimeLogin();
				return false;
			}
			// debemos logear al rut
			if (!$this->logear())
			{
				if ($this->mensajeError=="") $this->mensajeError .= MensajeUsuario::DEBE_NOSECREOSESION;
				$this->logear = true;
				$this->datos["session"] = "";
				if ($obligarlogear) $this->imprimeLogin();
				return false;
			}
		}
		// si quieren cerrar
		if (!$this->cerrarSesion()) {
			$this->mensajeError = "";
			$this->datos["session"] = "";
			$this->logear = true;
			if ($obligarlogear) $this->imprimeLogin();
			return false;
		}

		// si hay la verificamos en la base de datos
		if (!$this->verificarSesion())
		{
			// si no esta, esta session esta caducada
			// si es un operador debemos hacer que se loggee denuevo
			if (!$this->obtenerRutContrasena())
			{
				if ($this->mensajeError=="")
					$this->mensajeError = MensajeUsuario::DEBE_SESIONTERMINADA;
				$this->datos["session"] = "";
				$this->logear = true;
				if ($obligarlogear) $this->imprimeLogin();
				return false;
			}
			// debemos logear al rut
			if (!$this->logear())
			{
				//$this->mensajeError .= MensajeUsuario::DEBE_NOSECREOSESION;
				$this->logear = true;
				$this->datos["session"] = "";
				if ($obligarlogear) $this->imprimeLogin();
				return false;
			}
		}

		$this->session=$this->datos["session"];
		$this->rut=$this->datos["usuarioid"];
		$this->ip=$this->datos["ip"];
		return true;
	}

	// Inicio de session de operador
	public function logear()
	{
		//print_r($_REQUEST);
		
		if (trim($_REQUEST["javascriptok"]) == "")
		{
			$this->mensajeError = "Javascript no está habilitado!, favor habilitar";
			return false;
		}
		
		if ($_REQUEST["cookiesok"] != "true")
		{
			$this->mensajeError = "Para Ingresar a este sitio debe habilitar el uso de cookies!";
			return false;
		}


		if (!$this->obtenerRutContrasena())
			// si la accion no es logear nos vamos
			return false;

		//Borramos para no confundirnos
		$this->datos["session"]="";

		// crear instancia de DataTable
		$dt = new DataTable();

		$this->datos["clave"] = hash('sha256', $_REQUEST["clave"]);
		// ejecutar la consulta y almacenar el resultado en el objecto DataTable
		$this->usuariosBD->obtenerContrasena($this->datos,$dt);

		// cheqear por errores
		if(!$dt)
		{
			$this->mensajeError = $this->usuariosBD->accederError();

			//el error lo devuelve la consulta en sp_usuarios
			if ($this->usuariosBD->accederCodigoError() == 2 && substr($_SERVER["PHP_SELF"],-15)!="cambioclave.php") {
				$this->crearSesion();
				//header('Location: cambioclave.php?mensajeError='.$this->mensajeError);
				exit;
			}
			
			return false;
		}

		// si el producto no existe, se va false
		if(!$dt->leerFila())
		{
			// cerrar conexion BD
			$this->mensajeError = MensajeUsuario::RUT_NO_EXISTE;
			return false;
		}

		// solo ejecutar si existen registros
		//$this->datos["usuarioid"]=$dt->obtenerItem("usuarioid");

		// verifica si la clave es la misma
		if ($dt->obtenerItem("error")!=0)
		{

			// sino manda error
			$this->mensajeError = $dt->obtenerItem("mensaje");

			if ($dt->obtenerItem("error")==2 && substr($_SERVER["PHP_SELF"],-16)!="cambio_clave.php") {
				$this->crearSesion();
				header('Location: cambio_clave.php?mensajeError='.$dt->obtenerItem("mensaje"));
				exit;
			}
			return false;
		}
		return $this->crearSesion();

	}

	// Obtiene los datos del operador (POST, GET)
	private function obtenerRutContrasena()
	{
		// buscamos si la accion es logear
		if (!isset($_REQUEST["accion"]))
			return false;

		// Si no es nos vamos con falso
		if ($_REQUEST["accion"]!="Ingresar")
			return false;

		// las limpiamos
		$this->datos["usuarioid"]="";
		$this->datos["clave"]="";
		$this->datos["ip"]="";
		$this->datos["session"]="";

		// Obtener cliente desde datos enviado
		if (isset($_REQUEST["usuarioid"])) {
			$this->datos["usuarioid"] =$_REQUEST["usuarioid"];
		}
		// Obtener rut desde datos enviado
		if (isset($_REQUEST["clave"]))
			$this->datos["clave"]=$_REQUEST["clave"];

		// verifica si el rut si viene vacio
		if ($this->datos["usuarioid"]=="")
		{
			// sino manda error
			$this->mensajeError = MensajeUsuario::RUT_VACIO;
			$this->logear=true;
			return false;
		}
		// 	verifica si la clave es vacio
		if ($this->datos["clave"]=="")
		{
			// sino manda error
			$this->mensajeError = MensajeUsuario::CONTRASENA_VACIA;
			$this->logear=true;
			return false;
		}
		return true;
	}

	// Obtiene los datos del operador (POST, GET)
	private function obtenerCerrarSesion()
	{
		// buscamos si la accion es logear
		if (!isset($_REQUEST["accion"]))
			return false;
		// Si no es nos vamos con falso
		if ($_REQUEST["accion"]!="cerrarSesion")
			return false;
		return true;
	}

	// Funcion para obtener los datos de session (POST, GET, COOKIE)
	private function obtenerSesion()
	{

	
		//if (isset($_REQUEST["index"])){ // para evitar la doble sesion
			$dt = new DataTable();
			$datos=$_REQUEST;

			if (isset($_REQUEST["usuarioid"]))
			{
				$this->datos["usuarioid"] = $_REQUEST["usuarioid"];
				//print_r($datos);
				if ($this->usuariosBD->obtener($datos,$dt))
				{
					if(!$dt->leerFila())
						return false;

					$_REQUEST["session"] = $dt->obtenerItem("session");
					if (trim($_REQUEST["session"]) != '')
					{
						$this->datos["session"]=$_REQUEST["session"];
						return $this->datos["session"]!="";
					}
				}
			}
		//}

		
		if (isset($_REQUEST["accion"]))
		if ($_REQUEST["accion"]=="Ingresar") {
			setcookie("session","");
			return false;
		}

		$this->datos["session"]="";
		// Obtener session desde datos enviado
		if (isset($_REQUEST["session"]))
			$this->datos["session"]=$_REQUEST["session"];
		// Obtener session desde la Cookie
		if (isset($_COOKIE["session"]))
			$this->datos["session"]=$_COOKIE["session"];

		// Obtener session desde la actualidad
		return $this->datos["session"]!="";
	}

	// Verificar estado de la session
	public function cerrarSesion()
	{

		// revisamos si hay que se cerrar session
		if (!$this->obtenerCerrarSesion())
			// si no, nos vamos pero sin error
			return true;
		// ejecutar la consulta y almacenar el resultado en el objecto DataTable
		if (!$this->usuariosBD->eliminarSesion($this->datos))
		{
			$this->mensajeError = $this->usuariosBD->accederError();

			if (isset($_COOKIE["usuarioid"]))
				$this->datos["usuarioid"]=$_COOKIE["usuarioid"];
			

			return false;
		}

		if (isset($_COOKIE["usuarioid"]))
			$this->datos["usuarioid"]=$_COOKIE["usuarioid"];
	
		setcookie("session","");
		setcookie("usuarioid","");
		return false;
	}

	// Crea la session en la base de datos
	private function crearSesion()
	{
		
		//$this->mensajeError
		// inventa un numero de session aleatoriamente
		$this->datos["session"] = ContenedorUtilidades::inventarSesion();
		
		//para prueba para usar distintos usuarios en mismo pc
		//$this->datos["session"] = '123';
		
		// obtiene el nuemero de ip mesclando la interna con la externa
		$this->datos["ip"] = ContenedorUtilidades::realIP();

		// crear instancia de DataTable
		$dt = new DataTable();

		// agregar nueva session
		if (!$this->usuariosBD->agregarSesion($this->datos,$dt))
		{
			// Rescatamos el error, desconectamos y nos vamos en false
			$this->mensajeError = $this->usuariosBD->accederError();
			// borramos la session para no confundirnos
			$this->datos["session"]="";

			if (isset($_COOKIE["usuarioid"]))
	
			return false;
		}

		// marcamos nueva session con true para que mas adelante se envien los datos al cliente ajax
		$this->nuevasession=true;

		// Cookie
		setcookie("session",$this->datos["session"]);
		setcookie("usuarioid",$this->datos["usuarioid"]);
		return $this->verificarSesion();
	}

	// Verificar estado de la session
	private function verificarSesion()
	{
		// crear instancia de DataTable
		$dt = new DataTable();

		//$this->datos["usuarioid"]=0;
		if (!isset($this->datos["usuarioid"]))
		{
			$this->datos["usuarioid"] = $_COOKIE['usuarioid'];
		}
		$this->datos["ip"]="";

		// ejecutar la consulta y almacenar el resultado en el objecto DataTable
		$this->usuariosBD->verificarSesion($this->datos,$dt);
	
		// cheqear por errores
		if(!$dt)
		{
			$this->mensajeError = $this->usuariosBD->accederError();
			return false;
		}
		// si el producto no existe, se va false
		if(!$dt->leerFila())
			return false;


		if ($dt->obtenerItem("bloqueado")>0) {
			if (substr($_SERVER["PHP_SELF"],-9)!="index.php") {
				header("Location: index.php?mensajeError=Usuario Bloqueado");
				exit;
			}
			return false;
		}

		// solo ejecutar si existen registros
		//print ("marca");
		// asignar valores globales
		$this->ip = $dt->obtenerItem("ip");
		//$this->session = $dt->obtenerItem("session");
		$this->usuarioid = $dt->obtenerItem("usuarioid");
		$this->nombre = $dt->obtenerItem("nombre");
		$this->tipousuarioid =  $dt->obtenerItem("tipousuarioid");
		$this->nombreperfil =  $dt->obtenerItem("nombreperfil");

		$this->logeado=true;
		$this->datos["usuarioid"]=$dt->obtenerItem("usuarioid");
		$dt2 = new DataTable();

		// ejecutar la consulta y almacenar el resultado en el objecto DataTable
		$this->datos["usuarioid"]=$this->usuarioid;
		$this->datos["ip"]=$this->ip;
		
		//$this->datos["session"]=$this->session;

		//csb 05-04-2018 para este caso no verificamos ip, ya que para este cliente es dinamico.
		/*
		$ipnueva = trim(ContenedorUtilidades::realIP());
		if (trim($this->ip)!=trim(ContenedorUtilidades::realIP()))
		{	//print ("ip:".trim($this->ip)." ip2:".trim(ContenedorUtilidades::realIP()));
			$this->graba_log("ip antigua:".$this->ip." ip nueva:".$ipnueva." usuario:".$this->usuarioid." nombre:".$this->nombre);
			$this->mensajeError = MensajeUsuario::DISTINTA_IP;
			return false;

		}
		*/

		// Actualizamos la session
		if (!$this->usuariosBD->actualizaSesion($this->datos))
		{
			$this->mensajeError = $this->usuariosBD->accederError();
			return false;
		}

		if ($dt->obtenerItem("cambiarclave")>0 && substr($_SERVER["PHP_SELF"],-15)!="cambioclave.php") {

				header('Location: cambioclave.php?mensajeError=Debe cambiar su clave para continuar');
				exit;
		}


		return true;
	}

	private function imprimeLogin()
	{
		// mostramos el encabezado

		// agregamos el error a la pagina
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		// y los datos
		$datos[0]=$_REQUEST;
		$this->pagina->agregarDato("login",$datos);

		$this->pagina->agregarDato("RECAPTCHA", RECAPTCHA);
	    // imprimimos el template		
		$this->pagina->imprimirTemplate('templates/login.html');

		// desconectamos
		$this->bd->desconectar();
		exit;
	}

}
?>