<?php
// importar libreria de objetos
include_once("import.php");

class flujofirmaBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["nombrewf"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["diasmax"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion2["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["idestado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["diasmax"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion3["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion4["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["nombrewf"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion4["diasmax"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion5["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion5["idestadowf"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion6["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["idestadowf"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion6["orden"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		// Relacion Laboral
		$this->definicion7["nombrewf"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion7["diasmax"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion7["tipoWF"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		// Relacion Laboral
		$this->definicion8["idworkflow"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["idestado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["diasmax"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["ConOrden"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

   //agregar, espera resultado identity
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	
	// Relacion Laboral
	//agregar, espera resultado identity
	public function agregar_porEnte($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_flujofirma_agregar_PorEnte ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

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
	public function agregar_estado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_agregar_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		  
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
	
	// Relacion Laboral
	// agregar un registro
	public function agregar_estado_ConOrden($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_flujofirma_agregar_estado_ConOrden ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
		  
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
	
	public function modificar_estado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_modificar_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		  
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
	
	
	public function eliminar_estado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_eliminar_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
		  
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
	
	public function modifica_orden($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_modificar_estado_orden ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
		  
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
			  
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function obtener_estados($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_obtener_estados ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

    //Obtener listado de flujofirma Disponibles
	public function listado(&$resultado)
	{
	

		// generar el SQL de obtencion registros
		$sql = "sp_flujofirma_listado";

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

	//Obtener listado de flujofirma Disponibles
	public function listado_PorEnte(&$resultado)
	{
	

		// generar el SQL de obtencion registros
		$sql = "sp_rl_flujofirma_listado_PorEnte";

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