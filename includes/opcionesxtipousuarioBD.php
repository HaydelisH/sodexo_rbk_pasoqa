<?php
// importar libreria de objetos
include_once("import.php");

class opcionesxtipousuarioBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["opcionid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		
		$this->definicion2["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion3["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion3["opcionid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
	}

	public function Obtener($datos,&$resultado)
	{
		// verificar los datos
	
		// generar el SQL de obtencion registros
		$sql = "sp_opcionesxtipousuario_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		//print ($sql);

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
	
	public function Listado($datos,&$resultado)
	{
		// verificar los datos
	
		// generar el SQL de obtencion registros
		$sql = "sp_opcionesxtipousuario_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		//print ($sql);

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

	public function getFormulariosUsuario($datos,&$resultado)
	{
		// verificar los datos
	
		// generar el SQL de obtencion registros
		$sql = "sp_opcionesxtipousuario_formulariosUsuario ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		//print ($sql);

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
	public function getFormularios(&$resultado)
	{
		// verificar los datos
	
		// generar el SQL de obtencion registros
		$sql = "sp_formulariosPlantilla_obtener ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		//print ($sql);

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

	public function rlpendientefirma($datos,&$resultado)
	{
		// verificar los datos
	
		// generar el SQL de obtencion registros
		$sql = "sp_opcionesxtipousuario_rlpendientefirma ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		//print ($sql);

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