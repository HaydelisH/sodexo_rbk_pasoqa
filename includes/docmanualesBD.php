<?php
// importar libreria de objetos
include_once("import.php");

class docmanualesBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	private $definicion5;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idProyecto"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["ptipousuarioid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
        $this->definicion1["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	    //$this->definicion1["NombreTipoDoc"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion1["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	    //$this->definicion1["RazonSocialGama"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion1["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
	    $this->definicion1["RazonSocialCliente"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion1["usuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"");
		
		//$this->definicion1["Nombre"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion1["Descripcion_Pl"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"");
		
		$this->definicion2["idContrato"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion3["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");

		$this->definicion4["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["observacion"]=array("Tipo"=>"character","Largo"=>"200","Key"=>"NO");

		$this->definicion5["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion5["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
	}



	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
		  
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_manuales_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion5);
			  
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
		$sql = "sp_clausulas_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);
		
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
	public function Obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function total($datos,&$resultado)
	{
	 if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosvigentes_total".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		  
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
	public function listado($datos,&$resultado)
	{
		// generar el SQL de obtencion registros
        if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentosmanuales_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	  //echo $sql."<br/>";
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

	public function modificar_estado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modifica_estado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	    //echo"<br/>".$sql."<br/>";
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

	public function obtenerb64($datos,&$resultado)
	{
		
		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerB64 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		//echo"<br/>".$sql."<br/>";

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