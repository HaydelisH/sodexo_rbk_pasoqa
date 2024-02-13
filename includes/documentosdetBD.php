<?php

// importar libreria de objetos
include_once("import.php");

class documentosdetBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion2["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		
		$this->definicion3["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["estado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["observacion"]=array("Tipo"=>"character","Largo"=>"200","Key"=>"NO");

		$this->definicion4["idDocumento"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion4["DocCode"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion5["idDocumento"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		//$this->definicion5["documento"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion5["documento"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");

		$this->definicion6["RutFirmante"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion6["idDocumento"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion6["FechaFirma"]=array("Tipo"=>"character","Largo"=>"35","Key"=>"NO");

		$this->definicion7["personaid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion7["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion8["RutFirmante"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion8["idDocumento"]=array("Tipo"=>"interger","Largo"=>"","Key"=>"NO");
		$this->definicion8["FechaFirma"]=array("Tipo"=>"character","Largo"=>"35","Key"=>"NO");
		$this->definicion8["orden"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		
		$this->definicion9["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion9["NombreArchivo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}


	public function Obtener($datos,&$resultado)
	{
		
		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
	
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
	
	public function obtenerb64($datos,&$resultado)
	{
		
		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerB64 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		

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
	
	public function modificarEstadoDocumento($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modifica_estadoDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	
	public function modificarNombreArchivo($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modificarNombre ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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

		//Actualiza Documento firmado 
	public function modificarDocumento($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modificarDocumento ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);
			
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


		//Obtiene la cantidad de firmantes de un documento
	public function totalFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_totalFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		 
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
	
	//Obtener los datos de la Tabla Documentos
	public function obtenerOrdenFirmante($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerOrdenFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function obtenerFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	// Relacion laboral
	//Obtener los datos de un registro 
	public function rl_obtenerFirmantes($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_rl_documentos_obtenerFirmantes ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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
	public function obtenerFirmantesPorFirma($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtenerFirmantesPorFirma ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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
	public function obtenerTipoFirma($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_personas_obtenerTipoFirma ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_modificarDocCode 'modificar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion4);

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

	// Agregar datos sde Firma
	public function agregarFirma($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarFirma 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion6);
		  
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

	// Agregar datos sde Firma
	public function agregarFirmaDec($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_agregarFirma_dec 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion8);
		  
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

	//Obtener los datos de la Tabla Documentos
	public function obtenerDatosDocumento($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	//Eliminar logicamente un documento 
	public function eliminar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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


	// Agregar datos de un Centro de Costo al Gestor 
	public function agregarGestor($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_documentos_envioGestor ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		  
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
}

?>