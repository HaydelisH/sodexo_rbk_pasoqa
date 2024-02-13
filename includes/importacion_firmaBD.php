<?php

// importar libreria de objetos
include_once("import.php");

class importacion_firmaBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ****************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["usuarioid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["paginainicio"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["paginafin"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["empleadoid"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		$this->definicion["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["observacion"]=array("Tipo"=>"character varying","Largo"=>"200","Key"=>"NO");
		$this->definicion["rutrepresentantes"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["FechaDocumento"]=array("Tipo"=>"date","Largo"=>"10","Key"=>"NO");
		$this->definicion["totalpaginas"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["reprocesado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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


	public function obtenerTodo($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'obtenerTodo'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	public function grabaEnvioOK($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'grabaEnvioOK'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function grabaEnvioError($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'grabaEnvioError'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function ObtenerUrl($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerUrl'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function infoParaXml($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'infoParaXml'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ListaRepresentantes($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ListaRepresentantes'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ListaTiposDocumentos($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ListaTiposDocumentos'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ListaProcesos($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ListaProcesos'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ListaEmpresas($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ListaEmpresas'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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

	public function ObtenerTipoDoc($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerTipoDoc'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function EliminarProceso($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'EliminarProceso'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function Validar($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'Validar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ObtenerConfiguracion($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerConfiguracion'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ObtenerProceso($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerProceso'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function Grabar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'Grabar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function Eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'Eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function Listado($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'Listado'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ObtenerEmpleado($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerEmpleado'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ObtenerPagina($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerPagina'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function GrabarEstado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'GrabarEstado'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function ObtenerUltimaPagina($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerUltimaPagina'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ListaTiposDocumentosMas($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ListaTiposDocumentosMas'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function DesmarcarReproceso($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'DesmarcarReproceso'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

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
	
	public function ObtenerNoEnviado($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerNoEnviado'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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
	
	public function ObtenerReprocesados($datos,&$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_importacion_firma 'ObtenerReprocesados'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);

		//echo "sql Empresas:".$sql;
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