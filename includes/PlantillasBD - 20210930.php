<?php
// importar libreria de objetos
include_once("import.php");

class plantillasBD extends ObjetoBD //implements IOperacionesBD
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
	private $definicion9;
	private $definicion10;
	private $definicion11;
	private $definicion12;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************

	function __construct()
	{
		$this->definicion1["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["Descripcion_Pl"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["Titulo_Pl"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion1["idWF"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["RutModificador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["RutAprobador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	
		$this->definicion2["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion4["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion4["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion5["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion6["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion7["idWF"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion8["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"10","Key"=>"NO");
		$this->definicion8["idClausula"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["Encabezado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion8["Titulo"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion13["Descripcion_Pl"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion13["Titulo_Pl"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion13["idWF"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["RutModificador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["RutAprobador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion13["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion13["idTipoGestor"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion9["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion9["idClausula"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion9["Orden"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion10["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion10["idClausula"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion11["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion12["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion12["TipoEmpresa"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion14["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion14["RutAprobador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion15["idClausula"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion16["Titulo_Cl"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion16["Descripcion_Cl"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion16["Texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		$this->definicion16["idCategoria"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion16["RutModificador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion16["RutAprobador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion16["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion17["idClausula"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion17["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion18["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion18["idTipoDoc"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion19["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion19["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion13,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_agregar 'agregar' ".ContenedorUtilidades::generarLlamado($datos,$this->definicion13);

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

	// Agregar clausula a plantilla , en la tabla PlantillasClausulas
	public function agregarClausula($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_agregarClausula ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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


	// Agregar clausula a plantilla , en la tabla PlantillasClausulas
	public function agregarAEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion19,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_agregarAEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion19);

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

	// Agregar clausula a plantilla , en la tabla PlantillasClausulas
	public function modificarOrdenClausula($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_modificarOrdenClausula ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);

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

	// Agregar clausula a plantilla , en la tabla PlantillasClausulas
	public function eliminarClausula($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion10,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_eliminarClausula ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion10);

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

	// Agregar clausula a plantilla , en la tabla PlantillasClausulas
	public function eliminarPlantillaEmpresa($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion19,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_eliminarPlantillaEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion19);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_modificar 'modificar' ".ContenedorUtilidades::generarLlamado($datos,$this->definicion1);

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
		$sql = "sp_plantillas_eliminar 'eliminar' ".ContenedorUtilidades::generarLlamado($datos,$this->definicion2);

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

	// Cloonar un registro
	public function clonar($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_clonar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		$sql = "sp_plantillas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function obtenerPlantillaPorEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerPlantillaPorEmpresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	public function obtenerEstadosFlujos($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerFlujo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		$sql = "sp_plantillas_total ";
	
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

    //Obtener listado de plantillas Disponibles
	public function listado(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_listado ";

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
		$sql = "sp_plantillas_idmax ";

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
	public function aprobarPlantilla($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion14,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_aprobar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion14);

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
	public function listadoDiferencia($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_listadoDiferencia ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
		// generar el SQL de obtencion registros
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

	//Obtener la empresa de una plantilla
	public function obtenerEmpresa($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerEmpresa ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion12,$this->mensajeError,true)) return false;
		// generar el SQL de obtencion registros
		$sql = "sp_empresas_listadoDiferente ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion12);

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

	//Obtener Plantillas por Empresas
	public function obtenerPlantillasEmpresas($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerPlantillasEmpresas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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

	//Obtener Plantillas por Empresas
	public function obtenerPlantillasEmpresasTipoContrato($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion18,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerPlantillasEmpresasTipoContrato ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion18);

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

	//Obtener Plantillas por Empresas
	public function obtenerDatosFirmantesPlantilla($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerDatosFirmantesPlantilla ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	//Obtener Categorias registradas por empresas
	public function obtenerCategoria(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_categorias_listado ";

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

	//Obtener Flujos registradas por empresas
	public function obtenerFlujos(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_flujos_listado ";

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

	//Obtener Tipo de Documento registradas por empresas
	public function obtenerTiposDocumentos(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_tiposdocumentos_listado ";

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

	//Obtener Flujos registradas por empresas
	public function obtenerNombreFlujo($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion7,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_flujos_obtenerNomnbreWF ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion7);
		
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

	//Obtener Flujos registradas por empresas
	public function obtenerNombreTipoDoc($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion6,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_tiposdocumentos_obtenerNombreTipoDoc ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion6);
		
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion5,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_categorias_obtenerTitulo ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion5);

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
	public function obtenerClausulasPlantillas($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerClausulasPlantillas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

		//////echo $sql;
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
	public function obtenerDatosClausulaPlantilla($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion17,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerDatosClausulasPlantilla ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion17);

		//////echo $sql;
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
	public function obtenerClausulasCategorias($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion4,$this->mensajeError,true)) return false;
	
		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_obtenerClausulasCategorias ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion4);

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
	//Cambiar Orden de Clausulas en la Plantilla 
	public function cambiarOrdenClausulas($datos,&$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion9,$this->mensajeError,true)) return false;
	
		// generar el SQL de obtencion registros
		$sql = "sp_plantillas_cambiarOrdenClausulas ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion9);

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
	public function obtenerClausula($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion15,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion15);
////echo "<br/>".$sql."<br/>";
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
	public function agregarClausulaClonada($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion16,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_agregar 'agregar'".ContenedorUtilidades::generarLlamado($datos,$this->definicion16);
	  
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
	public function agregarClausulaAsignada($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion8,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_clausulas_agregarClausulaAsignada ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion8);
	  
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