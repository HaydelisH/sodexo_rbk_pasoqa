<?php
// importar libreria de objetos
include_once("import.php");

class generar_manualesBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	private $definicion2;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion1["idDocumento_Gama"]=array("Tipo"=>"character","Largo"=>"11","Key"=>"NO");
		$this->definicion1["idProyecto"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["idEstado"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion1["idWF"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion1["FechaCreacion"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion1["TipoFirmas"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion1["idPlantilla"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion1["RutAprobador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["DocCode"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idTipoGeneracion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion2["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion4["RutEmpresaC"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion5["RutEjecutivo"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion6["RutSupervisor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion7["modelo_contrato"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion8["FormasPago"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion9["idPlantilla"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion10["TipoFirmas"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion11["RutEmpresaN"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion12["idWF"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion13["idContrato"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion13["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["RutFirmante"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["idEstado"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion13["Orden"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion14["idWF"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion14["idEstado"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion15["idContrato"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion15["Dia"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion15["Mes"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["Anno"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion15["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion15["RazonSocial"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["RutEmpresaC"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion15["RazonSocialC"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["FormasPago"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["Equipamiento"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion15["Deducibles"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion15["Porcentaje"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion15["NombreDoc"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["RutRepresentante_1"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion15["Representante_1"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion15["RutRepresentante_2"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion15["Representante_2"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		
		$this->definicion16["idContrato"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion16["NombreArchivo"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion16["Extension"]=array("Tipo"=>"character", "Largo"=>"10","Key"=>"NO");
		$this->definicion16["documento"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");

		$this->definicion17["idProyecto"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion18["idMoneda_Tarifa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion19["idMoneda_KmsExceso"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion20["idProyecto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion20["FechaInicio"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion20["FechaFinal"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion20["CiudadEntrega"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion20["CiudadOperacion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion20["CiudadDevolucion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion20["idMoneda_Tarifa"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion20["Tarifa"]=array("Tipo"=>"float","Largo"=>"","Key"=>"NO");
		$this->definicion20["PeriodoArriendo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["KmsMensuales"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["KmsContratados"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["FrecuenciaMantencion"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["FrecuenciaCambio"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["idMoneda_KmsExceso"]=array("Tipo"=>"float","Largo"=>"","Key"=>"NO");
		$this->definicion20["KmsExceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion20["Marca"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion20["Modelo"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion20["Cantidad"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion21["idProyecto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion21["fechaInicio"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion21["fechaFin"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion21["Estado"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion21["RutModificador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion21["FechaModificar"]=array("Tipo"=>"datetime","Largo"=>"35","Key"=>"NO");
		$this->definicion21["RutEjecutivo"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion21["RutSupervisor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion21["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion22["RutFirmante"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion22["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion23["idWF"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion24["personaid"] = array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion24["nombre"] = array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion24["apellido"] = array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion24["correo"] = array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");

	}

	// agregar un registro a Contratos
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
//echo $sql."<br>";

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

	// agregar un registro a Documentos 
	public function agregarDocumento($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion16,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion16);
//echo $sql."<br>";
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

	// agregar un registro a Firmantes 
	public function agregarFirmantes($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion13);
//echo $sql."<br>";
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

	// Guardar las variables del Documento
	public function agregarVariables($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion15,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarVariables ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion15);
//echo $sql."<br>";
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

	// Guardar las variables del Documento
	public function agregarPersona($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion24,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_personas_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion24);
//echo $sql."<br>";
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
	
	//Obtener los datos de una Empresa
	public function obtenerEmpresa($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>"; 
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

	//Obtener los si el Flujo tiene o no Notario 
	public function obtenerNotario($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion23,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerNotario ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion23);
//echo $sql."<br>";		 
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

	//Obtener los si el Flujo tiene o no Aval
	public function obtenerAval($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion23,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerAval ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion23);
//echo $sql."<br>";
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

	//Obtener los si el Flujo tiene o no Aval
	public function obtenerFlujo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion23,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerFlujo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion23);
//echo $sql."<br>";
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

	//Obtener el Orden
	public function obtenerOrden($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion14,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_firmantes_obtenerOrden ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);
//echo $sql."<br>";
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

	//Obtener los Estados del WorkFlow
	public function obtenerEstados($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_firmantes_obtenerEstados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);
//echo $sql."<br>";
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

	//Obtener el detalle de un Proyecto , buscado por el id de proyecto
	public function obtenerDetalle($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion17,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectossubproyectos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion17);
//echo $sql."<br>";
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

	//Obtener el detalle de un Proyecto , buscado por el id de proyecto
	public function obtenerContratoMarco($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerContratoMarco ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";
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

	//Obtener el detalle de un Proyecto , buscado por el id de proyecto
	public function obtenerContratoRenting($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerContratoRenting ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";
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

	//Obtener la cantidad de proyectos de un cliente 
	public function obtenerListadoProyectos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_listadoProyectos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";
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

	//Obtener las formas de pago disponibles
	public function listadoFormasPago(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_formaspago_listado ";
//echo $sql."<br>";
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

	//Obtener las formas de pago disponibles
	public function obtenerFormasPago($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_formaspago_obtenerFP ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
//echo $sql."<br>";
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

	//Obtener Lista de Flujos disponibles
	public function listadoFlujos(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_listado";
//echo $sql."<br>";
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

	//Obtener Lista de Firmas disponibles
	public function listadoFirmas(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_firmas_listado";
//echo $sql."<br>";
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

	//Obtener Empresas registradas por empresas
	public function listadoSupervisor(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_supervisores_listado";
//echo $sql."<br>";
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

	//Obtener los Clientes de una Empresa
	public function listadoClientes(&$resultado)
	{
		
		// generar el SQL de obtencion registros
		$sql = "sp_clientes_listado ";
//echo $sql."<br>";
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

	//Obtener los Clientes de una Empresa
	public function listadoClientesDiferente($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clientes_listadoDiferente ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>";
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

	//Obtener nombre de Persona tipo Ejecutivo
	public function obtenerNombreEjecutivo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
//echo $sql."<br>";
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

	//Obtener nombre de Persona tipo Ejecutivo
	public function obtenerNombreSupervisor($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
//echo $sql."<br>";
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
	//Obtener archivo b64
	public function obtenerb64($datos,&$resultado)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerB64 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
//echo $sql."<br>";

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
	
	//Obtener los datos de un solo Proyecto
	public function obtenerSubproyectoCreado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion17,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectossubproyectos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion17);
//echo $sql."<br>";
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

    //Obtener listado de Categorias Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_modelosContratos_listado ";
//echo $sql."<br>";		 
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

	//Obtener listado de Empresas Disponibles
	public function listadoEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
//echo $sql."<br>";	
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

	//Obtener listado de Empresas Disponibles
	public function listadoPlantillas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerPlantillasEmpresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>";
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

	//Obtener los Proyectos que tiene una Empresa asociada 
	public function listadoProyectos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_listadoEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>";	
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

	//Obtener los Proyectos que tiene una Empresa asociada 
	public function listadoProyectosXCliente($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_listadoXCliente ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";	
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

	//Obtener los Proyectos que tiene una Empresa asociada 
	public function listadoProyectosLimitados(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_listadoLimitado ";
//echo $sql."<br>";
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

	//Obtener los Proyectos que tiene una Empresa asociada 
	public function listadoTipoFirmas(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_tipofirmas_listado ";
//echo $sql."<br>";	
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

	//Obtener las Formas de Pago disponibles
	public function listadoEquipamiento(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_equipamientos_listado ";
//echo $sql."<br>";	
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

	//Obtener las Formas de Pago disponibles
	public function listadoDeducibles(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_deducibles_listado ";
//echo $sql."<br>";	
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

	//Obtener Datos de una Plantilla
	public function obtenerPlantilla($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
//echo $sql."<br>";	
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

	//Obtener Datos de una Plantilla
	public function obtenerModeloContrato($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_modelosContratos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
//echo $sql."<br>";
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

	//Obtener Razon Social de una Empresa
	public function obtenerRazonSocial($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerRazonSocial ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>";
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

	//Obtener Razon Social de una Empresa
	public function obtenerRazonSocialN($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerRazonSocial ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);
//echo $sql."<br>";
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

	//Obtener Razon Social de una Empresa
	public function obtenerRazonSocialC($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerRazonSocial ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";
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

	//Obtener Datos de Persona
	public function obtenerPersona($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
//echo $sql."<br>";	
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


	//Obtener Razon Social de una Empresa
	public function obtenerFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
//echo $sql."<br>";
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

	//Obtener Razon Social de una Empresa
	public function obtenerFirmante($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion22,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_firmantes_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion22);
//echo $sql."<br>";
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

	//Obtener Firmantes de la Notaria
	public function obtenerFirmantesN($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);
//echo $sql."<br>";
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

	//Obtener Firmantes de un Cliente 
	public function obtenerFirmantesC($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
//echo $sql."<br>";
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

	//Buscar en Proyectos
	public function buscarProyectos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_buscar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
//echo $sql."<br>";	
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

	//Obtener los datos del Proyecto 
	public function obtenerProyecto($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
//echo $sql."<br>";	
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

	//Obtener los datos del Subproyectos de un proyecto 
	public function obtenerSubProyecto($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion17,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_subproyectos_obtenerProyecto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion17);
//echo $sql."<br>";
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

	//Obtener los datos de Firmas
	public function obtenerTipoFirma($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tipofirmas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);
//echo $sql."<br>";	
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

	//Obtener los datos del Flujo de Firma
	public function obteneFlujoFirma($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);
//echo $sql."<br>";	
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

	//Obtener Datos de Formas de Pago 
	public function obtenerFormaPago($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_formaspago_obtenerDatos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
//echo $sql."<br>";
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

	//Obtener Categorias registradas por empresas
	public function obtenerClausulasPlantillas($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerClausulasPlantillas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
//echo $sql."<br>";
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

	//Obtener Categorias registradas por empresas
	public function idMaxDocumentos(&$resultado)
	{	
		// generar el SQL de obtencion registros
		$sql = "sp_proyectos_idMax ";
//echo $sql."<br>";
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
	
	

	//Obtener Categorias registradas por empresas
	public function contarClausulas($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_contarClausulas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
//echo $sql."<br>";	
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