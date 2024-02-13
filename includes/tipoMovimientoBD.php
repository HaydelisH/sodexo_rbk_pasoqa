<?php
// importar libreria de objetos
include_once("import.php");

class tipoMovimientoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	//private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		//$this->definicion1["RazonSocial"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
    }

    //Obtener los datos de un registro 
	public function obtener(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_tipomovimiento_obtener";

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