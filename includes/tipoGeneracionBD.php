<?php

// importar libreria de objetos
include_once("import.php");

class tipogeneracionBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
			$this->definicion1["idTipoGeneracion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	
	}

	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_tipogeneracion_listado ";

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