<?php

// importar libreria de objetos
include_once("import.php");

class accesoxusuariocentroscostoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");	
		$this->definicion1["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");	
		$this->definicion1["centrocosto"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");	
	}


	public function Listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuariocentroscosto_x_usuario ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		//print $sql;
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