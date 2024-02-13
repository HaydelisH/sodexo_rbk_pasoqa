<?php
// importar libreria de objetos
include_once("import.php");

class empresasBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	private $definicion4;
	private $definicion5;
	private $definicion6;
	private $definicion7;
	private $definicion8;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["RazonSocial"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["RazonSocial"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["Direccion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["Comuna"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion2["Ciudad"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

        $this->definicion4["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion4["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion4["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion4["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion4["correo"]=array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");
		$this->definicion4["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion4["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion4["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion5["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion5["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion6["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

        $this->definicion7["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion7["RazonSocial"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion7["Direccion"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion7["Comuna"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion7["Ciudad"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		
		$this->definicion8["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion8["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion8["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion8["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion8["correo"]=array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");
		$this->definicion8["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["clave"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion8["idCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["TipoUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion9["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion9["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion10["nacionalidad"]=array("Tipo"=>"character","Largo"=>"20","Key"=>"NO");
		$this->definicion10["nombre"]=array("Tipo"=>"character","Largo"=>"110","Key"=>"NO");
		$this->definicion10["appaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion10["apmaterno"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion10["correo"]=array("Tipo"=>"character","Largo"=>"60","Key"=>"NO");
		$this->definicion10["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion10["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion10["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion10["idCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion11["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion11["buscar"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion11["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion11["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
    }




	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);

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

		public function agregarRepresentante($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representantes_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion4);

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
	
	public function agregarRepresentantePersoneria($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representantesPersoneria_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);
          
         
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion7);
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

	public function modificarRepresentante($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representante_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion4);
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
	
	public function modificarRepresentantePersoneria($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representantePersoneria_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion10);
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros

		$sql = "sp_empresas_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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

	public function eliminarRepresentante($datos)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros

		$sql = "sp_empresas_representantes_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion5);

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
		$sql = "sp_empresas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	public function obtenerRepresentantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representantes".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	
	public function obtenerRepresentantes_SinNotarios($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representantes_SinNotarios".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

    public function obtenerRepresentante($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representante_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	public function obtenerRepresentantePersoneria($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_representante_Personeria_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
		$sql = "sp_empresas_total ";

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
    
    //Obtener listado de equipamientos Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listado";

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

	//Obtener listado de equipamientos Disponibles
	public function listadopag($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listado_paginado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11); 

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

	//Obtener listado de equipamientos Disponibles
	public function totalpag($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listado_paginado_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	
	//Obtener el idCategoria a asignar a un nuevo registro 
	public function idMax(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_empresas_idmax ";

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