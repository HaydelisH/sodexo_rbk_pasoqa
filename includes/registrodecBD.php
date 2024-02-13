<?php

// importar libreria de objetos
include_once("import.php");

class registrodecBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";


	// OPERACIONES *******************************************************************************************************


	function __construct()
	{

		$this->definicion["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion["claveant"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"YES");
		$this->definicion["clavenew"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"YES");
		$this->definicion["claverep"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"YES");

		$this->definicion2["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
	}

	// solictud de nueva contraseña
	public function cambioclave($datos)
	{
		// verificar los datos
	    if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_cambio_clave ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		// print $sql;
		// almacenar el resultado del SQL en el parametro de salida
		//$resultado = parent::actualizar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			return false;
		}
		// todo bien
		return true;

	}

	 //Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
