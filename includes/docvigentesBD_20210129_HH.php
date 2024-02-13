<?php
// importar libreria de objetos
include_once("import.php");

class docvigentesBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES  ****************************************************************************************************


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
	
		$this->definicion2["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion3["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion4["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["observacion"]=array("Tipo"=>"character","Largo"=>"200","Key"=>"NO");
		
		$this->definicion5["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion6["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["observacion"]=array("Tipo"=>"character","Largo"=>"200","Key"=>"NO");

		$this->definicion7["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion7["RutAprobador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion8["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["NombreArchivo_f"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion8["Extension_f"]=array("Tipo"=>"character", "Largo"=>"10","Key"=>"NO");
		$this->definicion8["documento_f"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");

		$this->definicion9["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion9["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion9["usuarioid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion9["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion9["fechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicion9["fechaFin"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");

		$this->definicion10["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion10["usuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		
		$this->definicion11["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion11["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion11["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");

		$this->definicion12["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion12["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["Firmante"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion12["usuarioid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion12["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	
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

	// agregar un registro
	public function agregarDocumentoFirmado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarDocumentoFirmado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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
	public function Obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
		$sql = "sp_documentosvigentes_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function totalPorAprobar($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosporaprobar_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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
	public function total_pp($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_total_porprocesos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function totalMisDocumentos($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_total_firmaunitaria ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function total_misdocumentos($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_total_misdocumentos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function total_todos($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_total_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function totalPorTiempo($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_totalPorTiempo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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
		$sql = "sp_documentosvigentes_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function listadoPorAprobar($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosporaprobar_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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

	//Buscar solo el id de los documentos a aprobar
	public function listadoSoloDocumentosPorAprobar($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_soloidDocumentoPorAprobar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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
	public function listado_pp($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_porprocesos_2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_PorEstados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);
	  
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
	public function listadoMisDocumentos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_firmaunitaria ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function listadoMisDocumentosSoloidDocumento($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_firmaunitaria_soloiddocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function listado_misdocumentos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_listado_misdocumentos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function listado_todos($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function listadoPorTiempo($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listadoPorTiempo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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
	public function listadoRecientes($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listadoRecientes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);

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

	public function modificar_estado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modifica_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

		public function modificar_estadoDocumento($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modifica_estadoDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
	  
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

	public function modificar_aprobador($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modifica_aprobador ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function obtenerb64($datos,&$resultado)
	{
		
		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerB64 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		//echo"<br/>".$sql."<br/>";

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
	
	// modifica estado contrato manual
	public function modifica_estado_contrato($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_modifica_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	
	// rechaza contrato siempres y cuando no se encuentre firmado
	public function rechazar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_rechazo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
		 
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

	// rechaza contrato siempres y cuando no se encuentre firmado
	public function rechazarPendienteTerminado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_rechazarPendienteTerminado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
		 
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

	
	public function listadoPorEstados_dv($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_PorEstados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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
	
	public function listadoPorEstados_dv_pp($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_PorEstados_PorProcesos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorTiposDocumentos_dv($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_PorTiposDocumentos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorTipoFirmas_dv($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_PorTiposFirmas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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

	public function listadoPorProcesos_dv($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_listado_PorProcesos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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
		$sql = "sp_documentosvigentes_listado_soloidDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  
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