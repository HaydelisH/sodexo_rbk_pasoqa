<?php

// importar libreria de objetos
include_once("import.php");

class enviocorreosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["documentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion1["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion2["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["RutUsuario"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_enviocorreos_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

		// almacenar el resultado del SQL en el parametro de salida
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
	// agregar un registro sin documento
	public function agregarSinDocumento($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_enviocorreos_agregar_sindocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

		// almacenar el resultado del SQL en el parametro de salida
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
	// renotificar
	public function renotificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_enviocorreos_renotificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

		// almacenar el resultado del SQL en el parametro de salida
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

    //Obtener los datos de un registro 
	public function puedeRenotificar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_envioCorreos_puedeRenotificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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