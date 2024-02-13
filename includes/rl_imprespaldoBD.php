<?php

// importar libreria de objetos
include_once("import.php");

class rl_imprespaldoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	private $definicion2;
	private $definicion3;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		/*$this->definicion1["iddocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idestado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion2["iddocumentox"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["proveedor"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");*/
		
		$this->definicion3["iddocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["idplantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		/*$this->definicion4["iddocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["idtipogestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["nombre"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion4["documento"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		
		$this->definicion5["iddocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion5["idrespaldo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");*/
	}

	// agregar un registro
	/*public function agregar($datos)
	{
		// verificar los datos
	    if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		// print $sql;
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
	
	/*public function Listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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
	
	/*public function Total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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
	
	public function checklist($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_checklist ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		 
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
	
	/*public function agregardocumento($datos)
	{
		// verificar los datos
	    if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_agregar_documento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
		// print $sql;
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
	
	/*public function eliminardocumento($datos)
	{
		// verificar los datos
	    if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_eliminar_documento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
		// print $sql;
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
	
	/*public function obtenerdocumento($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_imprespaldo_obtener_documento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
		 
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


}

?>