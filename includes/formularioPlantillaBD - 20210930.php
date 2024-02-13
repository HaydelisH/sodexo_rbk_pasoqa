<?php
// importar libreria de objetos
include_once("import.php");

class formularioPlantillaBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicionCargaDatosVariables['empleadoFormularioid']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['rut']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['nombre']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['appaterno']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['apmaterno']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['nacionalidad']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['fechanacimiento']=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['idEstadoCivil']=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['direccion']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['comuna']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['ciudad']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['rolid']=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['correo']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['RutEmpresa']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['idCentroCosto']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['LugarPagoid']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['CiudadFirma']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['FechaDocumento']=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['FechaIngreso']=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['Cargo']=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicionCargaDatosVariables['LeFirmantes']=array("Tipo"=>"character","Largo"=>"","Key"=>"");

		$this->definicion1["rut"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion1["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion2["rut"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion2["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");

		$this->definicion3["empleadoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion3["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["estadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion4["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["empleadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion5["empleadoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion5["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion5["accion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion6["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion6["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["RutEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion6["NombreEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion6["fechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicion6["fechaFin"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicion6["idEstadoGestion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["RutCreador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["NombreCreador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["Descripcion_Pl"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["centrocostoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["nombrecentrocosto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["NombreCasino"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["CodCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["Cargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		//$this->definicion6["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		//$this->definicion6["Enviado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		//$this->definicion6["idTipoFirma"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

        $this->definicion7["empleadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion8["idEstadoGestion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["Observacion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion8["empleadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion8["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion8["actorid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
        $this->definicion9["empleadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion9["actorid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
        $this->definicion10["opcionid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		
        $this->definicion11["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
        $this->definicion11["empleadoFormularioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion11["CodCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion11["tipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion12["empleadoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion12["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion12["rut"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion13["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion13["personaId"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["personaEmail"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["personaNombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion13["personaCelular"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["parentescoRut"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["parentescoNombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion13["parentesco"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["parentescoNacimiento"]=array("Tipo"=>"datetime","Largo"=>"","Key"=>"NO");
		$this->definicion13["parentescoEmail"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion13["parentescoCelular"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion13["parentescoGenero"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion13["parentescoTipoCarga"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion14["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion15["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion16["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion16["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion16["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion16["RutEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion16["NombreEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion16["fechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicion16["fechaFin"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
		$this->definicion16["idEstadoGestion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion16["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion16["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion16["idFormulario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	}

    public function listado($datos, &$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);

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

    public function listado2(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_listado2 ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);

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

	public function getFormularioPlantilla($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_getformularioPlantilla ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion15);
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
	
	public function existe($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_existe ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
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
	
	public function carga($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_carga ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
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

	public function cargaDatosVariables($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_cargaDatosVariables ".ContenedorUtilidades::generarLlamado2($datos,$this->definicionCargaDatosVariables);
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

	public function getEmpleadoFormulario($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
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

	public function getDatosVariables($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_getDatosVariables ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
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

	public function getByEmpleadoFormularioid($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioPlantilla_getByEmpleadoFormularioid ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
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

	public function setIdDocumento($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_setIdDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
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

	public function deleteIdDocumento($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_deleteIdDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
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

	public function formularioCambioEstado($datos)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioCambioEstado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
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

	public function hayConflicto($datos)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_formularioEmpleado_hayConflicto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);
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

	//Obtiene un conteo o tabla de registro
	public function totalListadoActor1($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion16,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_revisionListadoActor1_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion16);

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
	public function totalListadoActor2($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_revisionListadoActor2_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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
	public function listadoActor1($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion16,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_revisionListadoActor1_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion16);

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
	public function listadoActor2($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_revisionListadoActor2_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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

	public function getTuplaEmpleadoFormulario($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_obtenerTupla ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
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

	public function modificar_estadogestion($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_estadoGestionUpdate ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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

	public function getObservacionActor($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_Observacion ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
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

	public function getActor($datos,&$resultado)
	{
		// verificar los datos
		
		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_getActor ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);
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

	public function sendEmail($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empleadoFormulario_sendEmail ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	public function setGenericaFormulario($datos)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_setGenericaFormulario_insert ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion13);

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
	
	//Obtener listado de RRHH para excel
	public function listadoActor1_Excel($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion16,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_revisionListadoActor1_obtener_excel ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion16);
	
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