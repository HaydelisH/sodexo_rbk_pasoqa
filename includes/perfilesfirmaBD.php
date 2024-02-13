<?php
// importar libreria de objetos
include_once("import.php");

class perfilesfirmaBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
	
	}

    //Obtener listado de procesos Disponibles
	public function listado(&$resultado)
	{	
		
		// generar el SQL de obtencion registros
		$sql = "sp_perfilesfirma_listado ";
		
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