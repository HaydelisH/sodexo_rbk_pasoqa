<?php
// importar libreria de objetos
include_once("import.php");

class estadoFormularioBD extends ObjetoBD //implements IOperacionesBD [sp_estadosgestion_listado]
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
        $this->definicion1["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
        /*
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion2["idEstadoGestion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion3["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");*/
	}

	// agregar un registro a Contratos
	/*public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_estadosgestion_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
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
	}*/


	/*// modificar un registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_estadosgestion_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		 
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

	}*/


	// eliminar un registro
	/*public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_estadosgestion_eliminar ,".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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

	}*/
    //Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_estadoFormulario_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		 
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

    //Obtener listado de Categorias Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosFormulario_listado ";
		 
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

	//Obtener listado de Categorias Disponibles
	public function listado1(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosFormulario1_listado ";
		 
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
	
    //Obtener listado de Categorias Disponibles
	public function listado2(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosFormulario2_listado ";
		 
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
	
    //Obtener listado estados de flujo de firma para formularios asignados
	public function listadoWf(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_estadosFormulario_listadoWF ";
		 
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