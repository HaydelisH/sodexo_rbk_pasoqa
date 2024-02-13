<?php
// importar libreria de objetos
include_once("import.php");

class estadocivilBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idEstadoCivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

    //listado
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadocivil_listado ";

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

	//listado
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_estadocivil_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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