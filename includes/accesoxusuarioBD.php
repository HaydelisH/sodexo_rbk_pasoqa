<?php

// importar libreria de objetos
include_once("import.php");

class accesoxusuarioBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
				
		$this->definicion2["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion2["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		
		$this->definicion3["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion3["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion3["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		
		$this->definicion4["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion4["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion4["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		//$this->definicion4["departamentoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion4["centrocostoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		
		$this->definicion5["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion5["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion5["lugarpago"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion5["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion6["newusuarioid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion6["empresaid"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion6["lugarpagoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		//$this->definicion6["departamentoid"]=array("Tipo"=>"character varying","Largo"=>"14","Key"=>"YES");
		$this->definicion6["centrocosto"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"YES");
		$this->definicion6["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion6["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	}

	public function ListadoEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_listado_empresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function GrabaEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_graba_empresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function GrabaEmpresaAccTodo($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_acctodo_empresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	
	// elimina un registro
	public function EliminaEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_elimina_empresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	
	public function ListadoLugaresPago($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_listado_lugarespago ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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

	public function ListadoLugaresPagoTotal($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_listado_lugarespago_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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

	// elimina un registro
	public function EliminaLugarPago($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_elimina_lugarpago ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function GrabaLugarPago($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_graba_lugarpago ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function GrabaLugarPagoAccTodo($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_acctodo_lugarpago ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function ListadoCentrosCosto($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_listado_centroscosto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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
	
	public function ListadoCentrosCostoTotal($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_listado_centroscosto_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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
	public function GrabaCentroCosto($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_graba_centrocosto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	
	// elimina un registro
	public function EliminaCentroCosto($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_elimina_centrocosto ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	
	public function ListadoXperfil($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_accesoxusuario_x_perfil ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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