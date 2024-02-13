<?php

// importar libreria de objetos
include_once("import.php");

class tiposusuariosBD extends ObjetoBD //implements IOperacionesBD
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

		$this->definicion2["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion3["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion3["diasinactividad"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["rolprivado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["renotificar"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion4["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion4["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion5["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion6["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");
		$this->definicion6["opcionid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion6["consulta"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["modifica"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["crea"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["elimina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["ver"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion7["tipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion7["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion8["tipousuarioingid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion9["nombre"]=array("Tipo"=>"character varying","Largo"=>"50","Key"=>"NO");

		$this->definicion10['usuarioid'] = array("Tipo"=>"character varying","Largo"=>"10","Key"=>"NO");
		
	}


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_graba 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_graba 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
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

	public function obtenerXRut($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_obtenerXRut ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);
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

	// obtiene un conteo o tabla de registro
	public function Total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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

	public function Listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	public function agregar_opciones($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_graba_opciones 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion6);

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
	
	public function modificar_opciones($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_graba_opciones 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion6);

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

	public function obtener_opciones($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_obtener_opciones ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
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

	
	public function Todos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
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
	
	public function obtenerxnombre($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposusuarios_obtener_x_nombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
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