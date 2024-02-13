<?php
// importar libreria de objetos
include_once("import.php");

class setDocumentosBD extends ObjetoBD //implements IOperacionesBD
{

	// CAMPOS PRIVADOS ***************************************************************************************************
	private $definicion;
	private $definicion1;
	public $mensajeError = "";
	public $codigoError = 0;

	// OPERACIONES *******************************************************************************************************


	function __construct()
	{
		$this->definicion["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion["idCargoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
		$this->definicion["idPlantilla"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");
		$this->definicion["idTipoMovimiento"]=array("Tipo"=>"integer","Largo"=>"","Key"=>"NO");

        $this->definicion1["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
		$this->definicion1["idCargoEmpleado"]=array("Tipo"=>"character","Largo"=>"","Key"=>"NO");
	}

    //Obtener listado de procesos Disponibles
	public function agregar($datos, &$resultado)
	{	
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_setDocumentos_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
    public function listar($datos, &$resultado)
    {
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_setDocumentos_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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
    public function eliminar($datos, &$resultado)
    {
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_setDocumentos_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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