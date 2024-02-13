<?php
// importar libreria de objetos
include_once("import.php");

class excelemplBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************

	function __construct()
	{
	
    }

	 public function Grabar($datos)
	{
		$sql = $datos;
      // almacena el resultado del SQL en el parametro de salida
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

	
	public function ConsultarX($datos,&$resultado)
	{
		//$sql = $datos;

	  // almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($datos);

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