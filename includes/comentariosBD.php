<?php
// importar libreria de objetos
include_once("import.php");

class comentariosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
        $this->definicion["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion1["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Comentario"]=array("Tipo"=>"character","Largo"=>"1000","Key"=>"NO");

		$this->definicion2["idComentario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion3["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion3["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["Comentario"]=array("Tipo"=>"character","Largo"=>"1000","Key"=>"NO");
		$this->definicion3["idComentario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_comentarios_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);

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


	// modificar un registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_comentarios_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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


	// eliminar un registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_comentarios_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);

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
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_comentarios_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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