<?php

// importar libreria de objetos
include_once("import.php");

class rl_tipogestorccBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idplantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");

	}

	public function obtenerxplantilla($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_tipogestorcc_xplantilla ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		//print ("sql:".$sql);
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