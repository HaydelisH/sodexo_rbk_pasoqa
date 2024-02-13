<?php
// importar libreria de objetos
include_once("import.php");

class clausulasBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	private $definicion4;
	private $definicion5;
	private $definicion6;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idClausula"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion1["Titulo_Cl"]=array("Tipo"=>"character","Largo"=>"2000","Key"=>"NO");
		$this->definicion1["Descripcion_Cl"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion1["Texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion1["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["RutModificador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["RutAprobador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		

		$this->definicion2["idClausula"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion4["idCategoria-1"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion5["TipoEmpresa"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion6["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion6["TipoEmpresa"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion7["idPlantilla"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");

		$this->definicion8["Titulo_Cl"]=array("Tipo"=>"character","Largo"=>"2000","Key"=>"NO");
		$this->definicion8["Descripcion_Cl"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion8["Texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion8["idCategoria"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["RutModificador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["RutAprobador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion9["idClausula"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion9["RutAprobador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);
		  
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
		$sql = "sp_clausulas_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);
			  
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
	public function modificarEstado($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_modificarEstado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
		
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

	// Clonar un registro
	public function clonar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_clonar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
	  
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
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function obtenerIdPlantillas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_obtenerIdPlantillas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function total(&$resultado)
	{
	
		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_total";
		  
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
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_listado";
	  
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

	//Obtener Clausulas registradas por empresas
	public function obtenerClausulasEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_obtenerClausulasEmpresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	//Obtener Clausulas registradas por empresas
	public function obtenerPlantillas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_obtenerPlantillas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function obtenerEmpresas(&$resultado)
	{

		$sql = "sp_empresas_listado ";

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

	//Obtener Empresas registradas menos la seleccionada
	public function obtenerEmpresasDiferente($datos, &$resultado)
	{

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listadoDiferente ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);

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
	public function obtenerCategoria(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_categorias_listado";
	  
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
	public function obtenerCategoriaEmpresa($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_categorias_listadoEmpresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
	  
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
	public function obtenerTituloCategoria($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_categorias_obtenerTitulo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);
	  
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
	public function obtenerRazonSocial($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_empresas_obtenerRazonSocial ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	  
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

	//Aprobar Clausula
	public function aprobarClausula($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_aprobar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);
			  
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