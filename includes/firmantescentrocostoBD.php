<?php
// importar libreria de objetos
include_once("import.php");

class firmantescentrocostoBD extends ObjetoBD //implements IOperacionesBD
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
        $this->definicion["centrocostoid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion["principal"]=array("Tipo"=>"bit","Largo"=>"10","Key"=>"NO");
        $this->definicion["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion["RutUsuario_principal"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

        $this->definicion1["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion1["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");

		$this->definicion2["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion2["centrocostoid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion2["principal"]=array("Tipo"=>"bit","Largo"=>"10","Key"=>"NO");
	
        $this->definicion3["RutEmpresa"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion3["centrocostoid"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
        $this->definicion3["RutUsuario"]=array("Tipo"=>"character","Largo"=>"10","Key"=>"NO");
    }

    //Obtener listado de procesos Disponibles
	/*public function agregar($datos, &$resultado)
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
    }*/
    public function listar($datos, &$resultado)
    {
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_firmantescentrocosto_listar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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
    public function listar2($datos, &$resultado)
    {
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion1,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_firmantescentrocosto_listar2 ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion1);
		
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
    public function validar($datos, &$resultado)
    {
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_firmantescentrocosto_agregar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion);
		
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
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion3,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_firmantescentrocosto_eliminar ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion3);
		
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
	public function obtenerUsuarioPrincipalExistente($datos, &$resultado)
	{
		// verificar los datos
		if (!ContenedorUtilidades::validarDatos($datos,$this->definicion2,$this->mensajeError,true)) return false;
		
		// generar el SQL de obtencion registros
		$sql = "sp_firmantescentrocosto_usuarioprincipal ".ContenedorUtilidades::generarLlamado2($datos,$this->definicion2);
		
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