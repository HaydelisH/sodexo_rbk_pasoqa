<?php
// importar libreria de objetos
include_once("import.php");

class importarBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************

	function __construct()
	{
		$this->definicion1["IdArchivo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion2["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion2["fila"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["resultado"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion2["observaciones"]=array("Tipo"=>"character varying","Largo"=>"500","Key"=>"NO");
		$this->definicion2["tipotransaccion"]=array("Tipo"=>"character varying","Largo"=>"20","Key"=>"NO");
		$this->definicion2["IdArchivo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion3["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion3["IdArchivo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
    }

	 public function Grabar($datos)
	{
		$sql = $datos;
      // almacena el resultado del SQL en el parametro de salida
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

	/*public function Consultar($datos,&$resultado)
	{
		$sql = $datos;

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
	
	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_confImpResultado_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_confImpResultado_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function obtenerConfImpArchivo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_confImpArchivo_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function obtenerConfimpArchivoDet($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_confimpArchivoDet_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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

	public function contarColumnasTabla($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_confimpArchivoDet_Count ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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