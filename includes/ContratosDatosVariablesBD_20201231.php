<?php
// importar libreria de objetos
include_once("import.php");

class ContratosDatosVariablesBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

		$this->definicion1["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["newusuarioid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["idCentroCosto"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["LugarPagoid"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["Cargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["Jornada"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		//$this->definicion1["Movilizacion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["SueldoBase"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["FechaTermino"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion1["FechaInicio"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		//$this->definicion1["FechaIngreso"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		//$this->definicion1["FechaDocumento"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");	
		
		//$this->definicion1["querySiNoObs1"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs2"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs3"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs4"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs5"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoDinamico1"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs1_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs2_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs3_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs4_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoObs5_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["querySiNoDinamico1_texto"]=array("Tipo"=>"base64","Largo"=>"","Key"=>"NO");
		//$this->definicion1["tituloCargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		//$this->definicion1["nombreEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");

		$this->definicion1["Ciudad"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["DescripcionCargo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Duracion"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Fecha"]=array("Tipo"=>"date","Largo"=>"","Key"=>"NO");
		$this->definicion1["ModTeletrabajo"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["Segmento"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["PorcentajeBonoTarget"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["MetaTargetS"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["MetaTargetU"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		
		$this->definicion1["ObjetivoFinanciero1"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["DetalleObjetivoFinanciero1"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["ObjetivoFinanciero2"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["DetalleObjetivoFinanciero2"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion1["MetaIndividual"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");		
	}

	//Obtener los datos de un registro 
	public function obtener($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_contratodatosvariables_obtener ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		 
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

	public function agregar($datos){

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_contratodatosvariables_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	public function obtenerEncabezados(&$resultado)
	{
		// generar el SQL de obtencion registros
		$sql = "sp_contratodatosvariables_obtenerEncabezados ";
		 
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