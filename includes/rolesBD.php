<?php
// importar libreria de objetos
include_once("import.php");

class rolesBD extends ObjetoBD //implements IOperacionesBD
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
		$this->definicion1["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion2["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

    //Obtener listado de procesos Disponibles
	public function listado(&$resultado)
	{	
		
		// generar el SQL de obtencion registros
		$sql = "sp_roles_listado ";
		
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