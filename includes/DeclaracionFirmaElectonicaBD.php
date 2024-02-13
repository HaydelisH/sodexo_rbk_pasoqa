<?php
// importar libreria de objetos
include_once("import.php");

class DeclaracionFirmaElectonicaBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion1;
	private $definicion2;
	private $definicion3;
	private $definicion4;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion1["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["RutEmpleado"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["NombreEmpleado"]=array("Tipo"=>"character","Largo"=>"50","Key"=>"NO");
		$this->definicion1["fechaInicio"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion1["fechaFin"]=array("Tipo"=>"date","Largo"=>"35","Key"=>"NO");
		$this->definicion1["idEstado"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion1["pagina"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion1["decuantos"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");	
		$this->definicion1["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");


		$this->definicion_param["tipodatoid"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
		$this->definicion2["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		$this->definicion2["empleadoid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		
		$this->definicion3["idDocumento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"");
		
	}
	
	public function agregar($datos){

		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_autoevaluacionriesgo_agrega_tmp ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);

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
	
	
	  //listado
	public function busca_parametro($datos,&$resultado)
	{//print ("fecha:".$datos["fechaInicio"]."<br>");
	
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion_param,$this->mensajeError,true)) return false;
		

		// generar el SQL de obtencion registros
		$sql = "sp_autoevaluacionriesgo_parametro ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion_param);
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
	


   //listado
	public function listado($datos,&$resultado)
	{//print ("fecha:".$datos["fechaInicio"]."<br>");
	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		

		// generar el SQL de obtencion registros
		$sql = "sp_reporteDeclaracionFirmaElectronica_listado ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	//	print($sql);
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
	
	   //listado
	public function listado_excel($datos,&$resultado)
	{//print ("fecha:".$datos["fechaInicio"]."<br>");
	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		

		// generar el SQL de obtencion registros
		$sql = "sp_autoevaluacionriesgo_Listado_excel ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
	//	print($sql);
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
	
	//listado
	public function listado_miper(&$resultado)
	{
	
		// generar el SQL de obtencion registros
		$sql = "sp_autoevaluacionriesgo_Listado_miper";
		
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
	
    //total
	public function total($datos,&$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;

		// generar el SQL de obtencion registros
		$sql = "sp_reporteDeclaracionFirmaElectronica_total ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);

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
	
  //todos los rsgistros de la consulta
	public function miper($datos,&$resultado)
	{	
	
		// verificar los datos
		//if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_autoevaluacionriesgo_miper ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
	
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