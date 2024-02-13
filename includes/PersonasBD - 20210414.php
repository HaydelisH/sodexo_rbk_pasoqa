<?php

// importar libreria de objetos
include_once("import.php");

class personasBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{

		$this->definicion["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion1["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion1["personaid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion1["nombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion1["envioinfo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["nombreContacto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion1["relacionContacto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");

		$this->definicion2["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["ciudad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["comuna"]=array("Tipo"=>"character","Largo"=>"30","Key"=>"NO");
		$this->definicion2["celularContacto"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["celularPersonal"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion2["envioinfo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["nombreContacto"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion2["relacionContacto"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
	}

	
	// obtiene un conteo o tabla de registro
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
	public function obtenerPIC($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_PersonasInfoContacto_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_PersonasInfoContacto_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_PersonasInfoContacto_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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

	// agregar un registro
	public function agregarInfoContacto($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleados_agregarInfoContacto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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