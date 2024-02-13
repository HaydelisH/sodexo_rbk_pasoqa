<?php
// importar libreria de objetos
include_once("import.php");

class cargoEmpleadoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion1["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["idCargoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Titulo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Obligaciones"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");

		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["idCargoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}


    
	//Obtener listado de Empresas Disponibles
	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_cargoEmpleado_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	//Obtener listado de Empresas Disponibles
	public function listadoPostulacion($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_cargoEmpleadoPostulacion_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	public function CargosEmpleado_listar2($datos, &$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_CargosEmpleado_listar2 ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_CargosEmpleado_agregar2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		  
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
		$sql = "sp_CargosEmpleado_eliminar2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		
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

    //Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_CargosEmpleado_obtener2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_CargosEmpleado_modificar2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
			  
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