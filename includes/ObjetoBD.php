<?php

//Notificaciones
error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);

//Librerias
require_once('./Config.php');  

class ObjetoBD {


	/* MEMBER VARIABLES ======================================================== */

	private $servidor = SERVIDOR;
	private $bd = BD;
	private $usuario = USUARIO;
	private $clave = CLAVE;
	public $conexion;

	/* MEMBER METHODS ========================================================== */

	public function ObjetoBD() {}

	// Administrador de propiedades

	public function accederError()
	{
		return $this->mensajeError;

	}

	public function accederCodigoError()
	{
		return $this->codigoError;

	}

	// Operaciones

	public function conectar()
	{
		// resetear error
        	$this->desconectar();
		
		$bd_info = array( "Database"=>$this->bd, "UID"=>$this->usuario, "PWD"=>$this->clave);
		
		$this->conexion = sqlsrv_connect($this->servidor, $bd_info);

		if(!$this->conexion)
		{
			$this->mensajeError = MensajeUsuario::CONEXION_BD_NO_DISPONIBLE;
			return false;
		}

		return true;
	}

	public function desconectar()
	{
		if(!is_null($this->conexion))
			sqlsrv_close($this->conexion);
	}

	public function usarConexion(&$con)
	{
		$this->conexion = $con;
	}

	public function obtenerConexion()
	{
		return $this->conexion;
	}

	public function actualizar($sql)
	{

		//csb 23-01-2017 graba log
		$this->graba_log ($sql);
		//fin


		// resetear error
		$this->error = "";

		// almacenar query en ejecucion
		$this->sqlEjecucion = $sql;

		if(is_null($this->conexion))
		{
			// error, almacenar y terminar ejecucion
			$this->mensajeError = MensajeUsuario::CONEXION_BD_NO_DISPONIBLE;
			return false;
		}

		// ejecutar sql
		if(!$resultado = sqlsrv_query($this->conexion,utf8_decode($sql)))
		{
			// si hubo un error, almacenar y terminar ejecucion
			$descripcionerror="";
			if( ($errors = sqlsrv_errors() ) != null) 
			{
				foreach( $errors as $error ) {
					$descripcionerror.= "SQLSTATE: ".$error[ 'SQLSTATE']." - ";
					$descripcionerror.= "code: ".$error[ 'code']." - ";
					$descripcionerror.="message: ".$error[ 'message']." ".$sql;
				}
			}			
			$this->mensajeError = $descripcionerror.' '. $sql;
			
			// graba error 
			$this->graba_log_error ($this->mensajeError);
			//fin
			
			return false;
		}

		if ($fila = sqlsrv_fetch_array($resultado)) 
		{
			//echo "res1:".$fila["mensaje"];
			if ($fila["error"]!=0)
			{
				// sino manda error
				$this->mensajeError = $fila["mensaje"];
				$this->codigoError = $fila["error"];
				return false;
			}
		}
		// todo OK
		sqlsrv_free_stmt($resultado);

		return true;
	}


	public function consultar($sql)
	{
		$this->graba_log ("INICIO".$sql);
	
		// resetear error
		$this->mensajeError = "";

		// almacenar query en ejecucion
		$this->sqlEjecucion = $sql;
        //echo $sql;

		if(is_null($this->conexion))
		{
			// error, almacenar y terminar ejecucion
			$this->error = MensajeUsuario::CONEXION_BD_NO_DISPONIBLE;
			return false;
		}

		// ejecutar sql
		if(!$resultado = sqlsrv_query($this->conexion,utf8_decode($sql)))
		{
			$descripcionerror="";
			if( ($errors = sqlsrv_errors() ) != null)
			{
				foreach( $errors as $error ) {
					$descripcionerror= "SQLSTATE: ".$error[ 'SQLSTATE']." - ";
					$descripcionerror.= "code: ".$error[ 'code']." - ";
					$descripcionerror.="message: ".$error[ 'message']." ".$sql;
				}
			} 
			
			$this->mensajeError = $descripcionerror.' '. $sql;
			// graba error 
			$this->graba_log_error ($this->mensajeError);
			//fin
		
			return false;
		}
		$this->graba_log("FIN".$sql);
		
		// create object datatable
		$dt = new DataTable();


		$indiceFila=0;
		// iterar el resultado y crear un array
		
		date_default_timezone_set('UTC'); 
		while ($fila = sqlsrv_fetch_array($resultado))
		{
			if ($indiceFila == 0)
			{	//evaluamos si hay un error cuando realizamos una consulta
				if (isset($fila["error"]) && ($fila["mensaje"])){ 
					if ($fila["error"]!=0)
					{   
						$this->mensajeError = $fila["mensaje"];
						$this->codigoError = $fila["error"];
						return false;
					}
				}			
			}
			
	        $dt->agregar($indiceFila, $fila);
			$indiceFila++;
		}
		
		sqlsrv_free_stmt($resultado);
		//print_r($dt);
	
		/* *INI* Parche anti ataque XSS*/
		$dt->data = ContenedorUtilidades::re_sanitizacion($dt->data);
		/* *FIN* Parche anti ataque XSS*/

		return $dt;
	}
	
//funcion para grabar cuando da un error el sp
	private function graba_log_error ($mensaje){
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logerror'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	@fputs($ar,@date("H:i:s")." ".$mensaje);
	   	@fputs($ar,"\n");
  		@fclose($ar);			
	}	
//fin

//graba log del sp
	private function graba_log ($mensaje){
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/log'.@date("Ymd").'.TXT';
		$ar= @fopen($nomarchivo,"a"); 
		//or die("Problemas en la creacion");
	   	@fputs($ar,@date("H:i:s")." ".$mensaje);
	   	@fputs($ar,"\n");
  		@fclose($ar);			
	}	
//fin	


}

?>