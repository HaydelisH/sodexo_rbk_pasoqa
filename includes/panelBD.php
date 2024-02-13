<?php

// importar libreria de objetos
include_once("import.php");

class panelBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["usuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion1["fechaInicio"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion1["fechaFin"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
	}


	public function Obtener($datos,&$resultado)
	{
	
		// generar el SQL de obtencion de datos
		$sql = "sp_panel ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
	
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

	public function ObtenerDatos(&$resultado)
	{
	
		// generar el SQL de obtencion de datos
		$sql = "sp_panel_avanzado ";
	
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

	public function ObtenerAnual($datos, &$resultado)
	{
	
		// generar el SQL de obtencion de datos
		$sql = "sp_panel_obtenerAnual ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	
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

	public function ObtenerEnProceso($datos,&$resultado)
	{
	
		// generar el SQL de obtencion de datos
		$sql = "sp_panel_obtenerEnProceso ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
	
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

	public function ObtenerFirmados($datos,&$resultado)
	{
	
		// generar el SQL de obtencion de datos
		$sql = "sp_panel_obtenerFirmados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
	
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