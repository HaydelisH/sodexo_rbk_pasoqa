<?php

// importar libreria de objetos
include_once("import.php");

class holdingBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["holdingid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion["nombreholding"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion["direccion"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion["paisid"]=array("Tipo"=>"character varying","Largo"=>"3","Key"=>"NO");
		$this->definicion["gerenteid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion["appaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion["apmaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");

		$this->definicion2["holdingid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");

		$this->definicion4["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion5["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion5["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_graba 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql:".$sql;
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


	// agregar un registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_graba 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql:".$sql;
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


	// agregar un registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
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

	// obtiene un conteo o tabla de registro
	public function Total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	
	public function Todos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_holding_todos ";

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