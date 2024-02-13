<?php

// importar libreria de objetos
include_once("import.php");

class empleadosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["ciudad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion["comuna"]=array("Tipo"=>"character","Largo"=>"30","Key"=>"NO");
		$this->definicion["fechanacimiento"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion["estadocivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["idEstadoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion1["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		
		$this->definicion2["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion2["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["correo"]=array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");
		$this->definicion2["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["ciudad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["comuna"]=array("Tipo"=>"character","Largo"=>"30","Key"=>"NO");
		$this->definicion2["fechanacimiento"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion2["estadocivil"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["clave"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion2["estado"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion2["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["TipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["TipoUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["nombrex"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion4["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion4["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion4["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion4["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion4["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion4["correo"]=array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");
		$this->definicion4["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion4["ciudad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion4["comuna"]=array("Tipo"=>"character","Largo"=>"30","Key"=>"NO");
		$this->definicion4["fechanacimiento"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion4["estadocivil"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion4["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["clave"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion4["estado"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion4["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["TipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["TipoUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["CreacionUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["idTipoGeneracion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion5["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion5["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion5["idCentroCosto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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
	public function agregarConUsuario($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError, true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_agregarConUsuario ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	// agregar un registro con usuario con tipo de generacion
	public function agregarConUsuario2($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_agregarConUsuario2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	
	// agregar un registro con usuario con tipo de generacion
	public function agregarDatosEmpleado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_agregarDatosEmpleado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
		$sql = "sp_empleados_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		//
		
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

	// obtiene el listado paginado de los empleados
	public function listado($datos,&$resultado)
	{
		
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
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

	// obtiene un conteo del listado de los empleados
	public function total($datos,&$resultado)
	{
		
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
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