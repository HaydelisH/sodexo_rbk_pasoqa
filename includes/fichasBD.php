<?php

// importar libreria de objetos
include_once("import.php");

class fichasBD extends ObjetoBD //implements IOperacionesBD
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
		$this->definicion["empresaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["centrocostoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion["lugarpagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["ciudad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["comuna"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["fechanacimiento"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion["estadocivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["fono"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["tipodocumentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion["documento"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion["nombrearchivo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["clave"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion["idFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion2["idFicha"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion2["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion2["codigo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion2["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		
		$this->definicionLlaves2["session"]=array("Tipo"=>"character","Largo"=>"32","","Key"=>"");
		$this->definicionWeb["pagina"]=array("Tipo"=>"character","Largo"=>"256","Key"=>"");

		$this->definicion1["idFicha"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["buscar"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["codigo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
							
		$this->definicion3["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["idFichaOrigen"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion4["tipodocumentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["documento"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion4["nombrearchivo"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion4["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion4["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["idFichaOrigen"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["Obligatorio"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion5["personaid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		
		$this->definicion6["empleadoid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion6["correo"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		
		$this->definicion7["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion7["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");

		$this->definicion8["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["empresaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["centrocostoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["lugarpagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion8["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["ciudad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["comuna"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["fechanacimiento"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion8["estadocivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["fono"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["idFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion9["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion9["documentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion9["idFichaOrigen"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion10["documentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion10["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["idFichaOrigen"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["Obligatorio"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion11["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion12["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion12["idestado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion13["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["documentoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["idfichaorigen"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	
	}

	// agregar un registro
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	// agregar un registro
	public function agregarDocumento($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_agregarDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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

	// agregar un registro
	public function agregarDocumentoFichasDocumentos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_agregarFichasDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion13);

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

	// modificar registro
	public function modificar($datos)
	{ 		
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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

	// modificar registro
	public function modificarEstado($datos)
	{ 		
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_modificar_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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

	// obtiene un conteo o tabla de registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function EliminarDoc($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_eliminardoc ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);

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
	public function respaldar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_respaldar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

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
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function obtenerDocumentosXFicha($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtenerDocumentosXFicha ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function obtenerDocumento($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtenerDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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
	public function obtenerDocumentosObligatorios($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtener_DocumentosObligatorios ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function obtenerCambioCorreo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtenerCambios ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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
	public function obtenerpendiente($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtener_pendiente".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	public function obtenerConfirmar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtener_confirmar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function obtenerMaxDoc($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_obtener_MaxDoc ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function obtenerEmpleado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_empleado_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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

	public function obtenerDocumentosGenerados($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_documentos_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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

	public function obtenerFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_firmantes_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function listadoestados($datos,&$resultado)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichas_listadoestados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		//
		
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