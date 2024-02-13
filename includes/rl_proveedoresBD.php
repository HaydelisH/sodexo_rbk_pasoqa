<?php
// importar libreria de objetos
include_once("import.php");

class rl_proveedoresBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion2;
	private $definicion3;
	private $definicion4;
	private $definicion5;
	private $definicion8;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************

	function __construct()
	{
		/*$this->definicion["nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["nombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["appaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["apmaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["RutProveedor"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["RutUsuario"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["idCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["Cargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");*/
		
		$this->definicion2["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["NombreProveedor"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Comuna"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Ciudad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion3["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		
		$this->definicion4["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion4["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion5["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion5["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion5["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		/*$this->definicion8["nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["nombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["appaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["apmaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["clave"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion8["idCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["TipoUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["Cargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		
		$this->definicion9["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion10["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion10["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion10["usuarioid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");*/
		
		$this->definicion11["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		
		$this->definicion12["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion12["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		
		$this->definicion13["nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["nombre"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["appaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["apmaterno"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["correo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["RutProveedor"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["idFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["clave"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion13["idCargo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["rolid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["TipoUsuario"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["TipoCorreo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["Cargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		
		$this->definicion14["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion14["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion14["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	
    }


	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);

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

		
/*	public function agregarFirmante($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_firmantes_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);
          
         
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
	}*/
	
	public function agregarFirmanteProveedor($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_firmantes_agregar_x_proveedor 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion13);
          
         
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);
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

	/*public function modificarFirmante($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_firmantes_modificar 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion);
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

		$sql = "sp_proveedores_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion3);

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
	}*/

	public function eliminarFirmante($datos)
	{
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros

		$sql = "sp_rl_proveedores_firmantes_eliminar 'eliminar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion5);

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

    /*//Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	}*/
	
    public function obtenerFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_firmantes_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	/*public function obtenerFirmante($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_firmante_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	}*/
	
	public function obtenerFirmanteUsuario($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_firmante_obtener_x_usuario ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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

	/*//Obtiene un conteo o tabla de registro
	public function total_porperfil($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_total_porperfil ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);

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

	}*/
    
    //Obtener listado de equipamientos Disponibles
	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);


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
	

	/*//Obtener listado de equipamientos Disponibles
	public function listado_porperfil($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_listado_porperfil ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);


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
	public function todos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_proveedores_todos ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);


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
	}*/
	
	public function obtenerProveedor($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion11,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_obtener_rutproveedor ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion11);

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
	
	public function listadofiltro($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion14,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_listado_filtro ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);

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
	
	public function totalfiltro($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion14,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_total_filtro ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);

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
	public function listadoCombo(&$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_proveedores_listadoCombo ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);


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