<?php
// importar libreria de objetos
include_once("import.php");

class centroscostoBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["nombre"]=array("Tipo"=>"character varying","Largo"=>"64","Key"=>"NO");
		$this->definicion1["diasinactividad"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion2["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		
		$this->definicion3["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"NO");
		$this->definicion3["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["appaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["apmaterno"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["correo"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["fono"]=array("Tipo"=>"character varying","Largo"=>"20","Key"=>"NO");
		$this->definicion3["clave"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");
		$this->definicion3["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion4["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion5["nombrex"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion5["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion6["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["opcionid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["consulta"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["modifica"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["crea"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["elimina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion7["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion7["clave"]=array("Tipo"=>"character varying","Largo"=>"100","Key"=>"NO");

		$this->definicion8["idCentroCosto"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"NO");

		$this->definicion9["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion9["lugarpagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion9["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion10["empresaid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion10["idCentroCosto"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");

		$this->definicion11["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");

		$this->definicion12["idCentroCosto"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion12["lugarpagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion13["empresaid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion13["LugarPagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["idCentroCosto"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");

		$this->definicion14["RutEmpresa"]=array("Tipo"=>"character varying","Largo"=>"","Key"=>"YES");
		$this->definicion14["lugarpagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_graba 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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


	// modificar un registro
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_graba 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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


	// eliminar un registro
	public function eliminar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
		
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
	
	public function obtenerPorLugarPago($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_obtenerPorLugarPago ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);
		
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
	
	
	public function obtenerEmpresa($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_obtenerPorEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);
		
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

	public function listadoporEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_listadoPorEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
		
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
	public function Total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	
	// modificar un registro
	public function listado(&$resultado)
	{
	
		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_listado ";

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

	// modificar un registro
	public function listadoComboEmpresa($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_por_empresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);
		
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

	public function obtenerCCPorNivel3($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_obtenerPorNivel3 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion13);
		
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
	
	public function getCombo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion14,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_centroscosto_combo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);
		
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