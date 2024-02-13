<?php
// importar libreria de objetos
include_once("import.php");

class usuariosmantBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["nombre"]=array("Tipo"=>"character varying","Largo"=>"64","Key"=>"NO");
		$this->definicion1["diasinactividad"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion2["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		
		$this->definicion3["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");	
		$this->definicion3["nombre"]=array("Tipo"=>"character varying","Largo"=>"110","Key"=>"NO");
		$this->definicion3["appaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["apmaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["correo"]=array("Tipo"=>"character varying","Largo"=>"60","Key"=>"NO");
		$this->definicion3["nombreusuario"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["clave"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion3["idFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["idloginExterno"]=array("Tipo"=>"character varying","Largo"=>"1","Key"=>"NO");
		$this->definicion3["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion4["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion4["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion5["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion5["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion6["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["opcionid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["consulta"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["modifica"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["crea"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["elimina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion7["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion7["clave"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");

		$this->definicion8["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");	
		$this->definicion8["nombre"]=array("Tipo"=>"character varying","Largo"=>"110","Key"=>"NO");
		$this->definicion8["appaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion8["apmaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion8["correo"]=array("Tipo"=>"character varying","Largo"=>"60","Key"=>"NO");
		$this->definicion8["nombreusuario"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion8["clave"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion8["idFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["idloginExterno"]=array("Tipo"=>"character varying","Largo"=>"1","Key"=>"NO");
		$this->definicion8["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		$this->definicion8["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["centrocostoid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		$this->definicion8["idEstadoUsuario"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");
		$this->definicion8["forzarCambioContrasena"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");
		$this->definicion8["idUsuarioBloqueado"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");
		$this->definicion8["correoinstitucional"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_graba 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

		//echo "sql:".$sql;
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


	// modificar un registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_graba 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

		//echo "sql:".$sql;
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
	public function agregarPorEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_porempresa_graba 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);

		//echo "sql:".$sql;
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


	// modificar un registro
	public function modificarPorEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_porempresa_graba 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);

		//echo "sql:".$sql;
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


	// eliminar un registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
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

	// obtiene un conteo o tabla de registro
	public function Total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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

	public function Listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	
	// modificar un registro
	public function CambiarClave($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_cambiar_clave ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

		//echo "sql:".$sql;
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
	
	// modificar un registro
	public function AgregarClaveTemporal($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_usuariosmant_agregarClaveTemporal ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

		//echo "sql:".$sql;
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


}

?>