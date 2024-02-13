<?php
// importar libreria de objetos
include_once("import.php");

class contratofirmantesBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion2["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
	}

  
    //Obtener listado de flujofirma Disponibles
	public function ObtenerXcontrato($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_contratofirmantes_xcontrato ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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

	//Obtener listado de flujofirma Disponibles
	public function ObtenerFirmantes($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_contratofirmantes_firmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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