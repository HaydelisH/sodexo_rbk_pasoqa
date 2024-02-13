<?php
// importar libreria de objetos
include_once("import.php");

class respuesta_importarBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************

	function __construct()
	{
		$this->definicion1["usuarioingid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		$this->definicion1["IdArchivo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion2["usuarioingid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		$this->definicion2["IdArchivo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
    }

    //Obtener los datos de un registro 
	public function obtenerResultadoImportar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_respuesta_Importar_obtener".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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

	public function listado($datos,&$resultado)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_confimpResultado_listado".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	public function listadoTotal($datos,&$resultado)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_confimpResultado_listadoTotal".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	public function cuenta($datos,&$resultado)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_confimpResultado_count".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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