<?php
// importar libreria de objetos
include_once("import.php");

class tipogestorBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Nombre"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
	
		$this->definicion2["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["Nombre"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
	}

	// agregar un registro a Contratos
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tipogestor_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);
		
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
		$sql = "sp_tipogestor_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
		 
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
		$sql = "sp_tipogestor_eliminar 'eliminar',".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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
		$sql = "sp_tipogestor_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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

	
    //Obtener listado de Categorias Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_tipogestor_listado ";
		 
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