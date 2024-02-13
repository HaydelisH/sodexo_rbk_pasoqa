<?php

// importar libreria de objetos
include_once("import.php");

class usuariosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{

		$this->definicion["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion["clave"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion["ip"]=array("Tipo"=>"character varying","Largo"=>"32","Key"=>"NO");
		$this->definicion["session"]=array("Tipo"=>"character","Largo"=>"16","Key"=>"NO");
		$this->definicion["ultimavez"]=array("Tipo"=>"timestamp without time zone","Largo"=>"","Key"=>"NO");

		$this->definicionLlaves["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");

		$this->definicionLlaves2["session"]=array("Tipo"=>"character","Largo"=>"32","","Key"=>"");
		$this->definicionWeb["pagina"]=array("Tipo"=>"character","Largo"=>"256","Key"=>"");

		$this->definicion1["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// agregar un registro
	public function agregarSesion($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'agregarSesion'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// modificar registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// modificar registro
	public function actualizaSesion($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves2,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'actualizarSesion'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// obtiene un conteo o tabla de registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// obtiene un conteo o tabla de registro
	public function eliminarSesion($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves2,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'eliminarSesion'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	// obtiene un conteo o tabla de registro
	public function obtener($datos,&$resultado)
	{

		
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'obtener'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}
	// obtiene un conteo o tabla de registro
	public function obtenerContrasena($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		//$sql = "select sp_usuarios('pcursor','obtenerContrasena'".ContenedorUtilidades::generarLlamado($datos,$this->definicion).");fetch all in pcursor;";
		$sql = "sp_usuarios 'obtenerContrasena'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}


	// obtiene un conteo o tabla de registro
	public function verificarSesion($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionLlaves2,$this->mensajeError)) return false;
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'verificarSesion'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		// almacenar el resultado del SQL en el parametro de salida
		//print ($sql);
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			$this->codigoError=parent::accederCodigoError();
			return false;
		}
		// todo bien
		return true;

	}


	public function obtenerListado($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarios 'obtenerListado'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
		//print ($sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;
	}


	public function referenciaPaginaWeb($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicionWeb,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		//$sql = "select sp_referenciaPaginaWeb('pcursor','agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicionWeb).");fetch all in pcursor;";
		$sql = " sp_referenciaPaginaWeb 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicionWeb);
		//echo $sql;
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;
	}

	public function obtenerEmpresaCentroCosto($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuarioEmpresa_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		//print ("sql:".$sql);
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;
	}

}

?>