<?php
// importar libreria de objetos
include_once("import.php");

class firmasdocBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion1["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion1["usuarioid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");

		$this->definicion2["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion3["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion3["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
		  
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
			  
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
		$sql = "sp_clausulas_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);
		
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	//Obtiene un conteo o tabla de registro
	public function total($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		  
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
	
	//Obtiene un conteo o tabla de registro
	public function total_sinrol($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_total_sinrol ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		  
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
	
    //Obtener listado de clausulas Disponibles
	public function listado($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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
	
	//Obtener listado de clausulas Disponibles
	public function listado_sinrol($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_sinrol ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	//Obtener listado de clausulas Disponibles
	public function listadoPorEstados($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorEstados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorTiposDocumentos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposDocumentos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorTipoFirmas($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposFirmas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorProcesos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorProcesos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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
	//Obtener listado de clausulas Disponibles
	public function listadoPorEstados_md($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorEstados_md ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorTiposDocumentos_md($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposDocumentos_md ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorTipoFirmas_md($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposFirmas_md ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	public function listadoPorProcesos_md($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorProcesos_md ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	//Obtener listado de clausulas Disponibles
	public function listadoSoloDocumentos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_soloidDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	//Obtener listado de clausulas Disponibles
	public function listadoPorEstados_filtros($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorEstados_filtros ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorTiposDocumentos_filtros($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposDocumentos_filtros ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorTipoFirmas_filtros($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorTiposFirmas_filtros ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorProcesos_filtros($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorProcesos_filtros ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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