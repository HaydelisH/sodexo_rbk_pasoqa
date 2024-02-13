<?php
// importar libreria de objetos
include_once("import.php");

class ChecklistDocumentosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idTipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion1["idTipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Obligatorio"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion2["idTipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

	//Listado por tipo de movimiento
 	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_ChecklistDocumentos_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	public function eliminar($datos)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_checkListDocumentos_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);
		//$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			$this->codigoError=parent::accederCodigoError();
			return false;
		}
		// todo bien
		return true;
    }


	//Obtener listado de procesos Disponibles
	public function agregar($datos)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_checkListDocumentos_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::actualizar($sql);
		//$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			$this->codigoError=parent::accederCodigoError();
			return false;
		}
		// todo bien
		return true;
    }

	public function checkListDocumentos_listar($datos,&$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_checkListDocumentos_listar ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

		// almacenar el resultado del SQL en el parametro de salida
		$resultado = parent::consultar($sql);

		if (!$resultado)
		{
			// mensaje de error y salimos
			$this->mensajeError=parent::accederError();
			$this->codigoError=parent::accederCodigoError();
			return false;
		}
		// todo bien
		return true;
	}
	public function tipoMovimiento_listar($datos,&$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tipoMovimiento_listar ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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
	public function tipoGestor_listar($datos,&$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tipoGestor_listar ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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