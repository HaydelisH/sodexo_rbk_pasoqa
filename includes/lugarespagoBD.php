<?php
// importar libreria de objetos
include_once("import.php");

class lugarespagoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion1["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");

		$this->definicion2["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion2["RL_LUGARPAGO_DEFECTO"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"YES");
		
		$this->definicion3["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion3["nombrelugarpago"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"YES");
		
		$this->definicion4["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");		
		$this->definicion4["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion4["nombrelugarpago"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion4["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"YES");

		$this->definicion5["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion5["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
	}


	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
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

	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	///////////////////////
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_agregar" .ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_modificar" .ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
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
	public function listadoPaginado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_listadoPaginado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	
	public function totalPaginado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_lugarespago_totalPaginado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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