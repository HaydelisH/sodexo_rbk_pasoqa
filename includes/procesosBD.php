<?php
// importar libreria de objetos
include_once("import.php");

class procesosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion2["idProceso"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["Descripcion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion4["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");	
		$this->definicion4["usuarioid"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion5["TipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion5["NombreProceso"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");	
	}

	// agregar un registro a Contratos
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_procesos_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);
		
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
	public function modificar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_procesos_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
		 
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
		$sql = "sp_procesos_eliminar 'eliminar',".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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
		$sql = "sp_procesos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		 
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

	//Obtener los datos de un registro 
	public function obtenerProcesoFlujo($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_procesos_obtenerProcesoFlujo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
		 
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


    //Obtener listado de procesos Disponibles
	public function listado(&$resultado)
	{	
		
		// generar el SQL de obtencion registros
		$sql = "sp_procesos_listado ";
		
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

	//Obtener listado de procesos Disponibles
	public function todos($datos, &$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_procesos_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
		
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

	 //Obtener listado de procesos Disponibles
	public function total($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_procesos_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
		 
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