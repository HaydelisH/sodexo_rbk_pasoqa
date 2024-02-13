<?php
// importar libreria de objetos
include_once("import.php");

class descargasBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["Nombre"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["Tipo"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["Ruta"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion1["B64"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion2["idDescarga"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["idDescarga"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["Nombre"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion3["Tipo"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion3["Ruta"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion3["B64"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion3["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion4["idDescarga"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
	}

	// agregar un registro a Contratos
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_descargas_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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

	// agregar un registro a Contratos
	public function modificar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_descargas_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
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

	// agregar un registro a Contratos
	public function modificarDescripcion($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_descargas_modificarDescripcion ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
		
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
	
    //Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_descargas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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

	 //Eliminar los datos de un registro 
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_descargas_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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

	
    //Obtener listado de descargas Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_descargas_listado ";
		 
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