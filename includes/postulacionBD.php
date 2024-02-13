<?php

// importar libreria de objetos
include_once("import.php");

class postulacionBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["personaid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");

		$this->definicion2["personaid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion2["centrocostoid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion2["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion2["fechaPostulacionMIN"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		
		$this->definicion3["centrocostoid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["personaid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["nombre"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["email"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["telefono"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["Observacion"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["fechaPostulacion"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion3["discapacidad"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["disponibilidadid"]=array("Tipo"=>"int","Largo"=>"","Key"=>"NO");

		$this->definicion4["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["rutPostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion4["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");

		$this->definicion7["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion7["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion7["nombrePostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion7["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");

		$this->definicion5["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["rutPostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion5["nombrePostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion5["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion5["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion5["centrocostoid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion5["fechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
        $this->definicion5["fechaFin"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
        $this->definicion5["estadoPostulacionid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion5["discapacidadid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion5["disponibilidadid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion6["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["rutPostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion6["nombrePostulante"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion6["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion6["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion6["centrocostoid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
		$this->definicion6["fechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
        $this->definicion6["fechaFin"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
        $this->definicion6["estadoPostulacionid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion6["discapacidadid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion6["estadoPostulanteid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion6["disponibilidadid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion8["rut"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion8["to"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

        $this->definicion9["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"");
        $this->definicion9["dias"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");
        $this->definicion9["ahora"]=array("Tipo"=>"date","Largo"=>"","Key"=>"");
        $this->definicion9["proximidadCaducidadId"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");
        $this->definicion9["dias2"]=array("Tipo"=>"int","Largo"=>"","Key"=>"");

		$this->definicion10["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion10["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion10["link"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion10["fechaCaducidadLink"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");

		$this->definicion11["rut"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion11["estadoPostulacion"]=array("Tipo"=>"int","Largo"=>"","Key"=>"NO");
		$this->definicion11["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion11["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion11["estadoPostulante"]=array("Tipo"=>"int","Largo"=>"","Key"=>"NO");
		$this->definicion11["postulacionid"]=array("Tipo"=>"int","Largo"=>"","Key"=>"NO");
		$this->definicion11["postulanteid"]=array("Tipo"=>"int","Largo"=>"","Key"=>"NO");

		$this->definicion12["rut"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion12["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion12["idCargoEmpleado"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregarPostulacion($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_postulacion_agregar".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_postulante_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
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

	public function existePostulacion($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_postulanteExiste_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
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

	//Obtiene un conteo o tabla de registro
	public function listadoTotal($datos,&$resultado)
	{
	 	if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoTotal ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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

	//Obtener listado paginado de postulantes
	public function listadoPaginado($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoPaginado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	public function listadoTotal2($datos,&$resultado)
	{
	 	if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoTotal2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

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

	//Obtener listado paginado de postulantes
	public function listadoPaginado2($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoPaginado2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

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
	public function listadoReporteTotal($datos,&$resultado)
	{
	 	if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoReporteTotal ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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

	//Obtener listado paginado de postulantes
	public function listadoReportePaginado($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoReportePaginado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	public function listadoReporteTotal2($datos,&$resultado)
	{
	 	if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoReporteTotal2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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

	//Obtener listado paginado de postulantes
	public function listadoReportePaginado2($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_buscarPostulacion_listadoReportePaginado2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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

	// Actualizar lista negra
	public function listaNegraUpdate($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_blackList_update ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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

	public function getCargosEmpresa($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_cargosEmpresa_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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

	public function actualizarLink($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_actualizarLink ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);

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
	
	// Actualizar lista negra
	public function resultadoPostulacionUpdate($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_resultadoPostulacion_update ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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

	public function existePostulacion2($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_existePostulacion_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);
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
}

?>