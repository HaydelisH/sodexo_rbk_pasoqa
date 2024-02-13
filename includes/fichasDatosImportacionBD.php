<?php

// importar libreria de objetos
include_once("import.php");

class fichasDatosImportacionBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	private $definicion2;
	private $definicion3;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["CodDivPersonal"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["CodCargo"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["ClaseContrato"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["RolEmpleado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["TipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["CodJornada"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["EstadoEmpleado"]=array("Tipo"=>"character","Largo"=>"1","Key"=>"NO");
		$this->definicion["AsignacionMovilizacion"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["Posicion"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["Nacionalidad"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["AsignacionPerdidaCajaValor"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["RutTrabajador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["Comuna"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["AsignacionPerdidaCajaPorcentaje"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["Direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["NombreTrabajador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion["CiudadFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["AsignacionColacion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["DescCargoRIOHS"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion["SueldoBase"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["FechaNacimiento"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"");
		$this->definicion["CorreoElectronicoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["FechaTermino1"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion["FechaTermino2"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion["EstadoCivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["AreaTrabajo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["FechaInicioContrato"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion["CiudadTrabajador"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["AsignacionCajaFija"]=array("Tipo"=>"character","Largo"=>"14","Key"=>"NO");
		$this->definicion["apellidopat"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion["apellidomat"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");

		$this->definicion1["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion2["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["usuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["CodDivPersonal"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["CodCargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["ClaseContrato"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["RolEmpleado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["TipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["CodJornada"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["EstadoEmpleado"]=array("Tipo"=>"character","Largo"=>"1","Key"=>"NO");
		$this->definicion2["AsignacionMovilizacion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Posicion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Nacionalidad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["RutTrabajador"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["Comuna"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["AsignacionPerdidaCajaPorcentaje"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["Direccion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["NombreTrabajador"]=array("Tipo"=>"character","Largo"=>"","Key"=>"");
		$this->definicion2["CiudadFirma"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["AsignacionColacion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["DescCargoRIOHS"]=array("Tipo"=>"character","Largo"=>"100","Key"=>"NO");
		$this->definicion2["SueldoBase"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["FechaNacimiento"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion2["CorreoElectronicoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["FechaTermino1"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion2["FechaTermino2"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion2["EstadoCivil"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion2["AreaTrabajo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion2["FechaInicioContrato"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");

		$this->definicion3["fichaid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion3["idestado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["buscar"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion3["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion3["usuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion3["empresaid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion3["centrocostoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}

	// agregar un registro
	public function agregar($datos)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);

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

	// modificar registro
	public function modificar($datos)
	{ 		
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_modificar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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

	// obtiene un conteo o tabla de registro
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function listado($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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
	public function listadoEstados($datos,&$resultado)
	{
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_fichasDatosImportacion_listadoEstados ";//.ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);

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