<?php
// importar libreria de objetos
include_once("import.php");

class estadosworkflowBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion1["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}


    //listado
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosworkflow_listado_filtro ";

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
	public function listado_flujo($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosworkflow_listado_flujo_filtro ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
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
	public function listado_sr($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosworkflow_listado_sin_rechazo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
	public function listado_limitado($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosworkflow_listado_limitado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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