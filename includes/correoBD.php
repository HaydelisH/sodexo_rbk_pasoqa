<?php
// importar libreria de objetos
include_once("import.php");

class correoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
        $this->definicion2["CodCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion1["CodCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["CC"]=array("Tipo"=>"character","Largo"=>"150","Key"=>"NO");
		$this->definicion1["CCo"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion1["Asunto"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["Cuerpo"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_correos_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_correos_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
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
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_correos_eliminar 'eliminar','".$datos."'";
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
		$sql = "sp_correos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	//Obtiene un conteo o tabla de registro
	public function total(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_correos_total ";

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
    //Obtener listado de equipamientos Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_correos_listado ";

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
	
	//Obtener el idCategoria a asignar a un nuevo registro 
	public function idMax(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_correos_idmax ";

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