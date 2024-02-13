<?php

error_reporting(E_ERROR);
ini_set("display_errors", 1);

include_once('includes/Seguridad.php');
include_once('Variables.php');
include_once('Config.php');
include_once('generar.php');

//Opcion del AJAX para buscar el tipo de firma de una persona
$page = new vistaprevia();

class vistaprevia {

	// funcion contructora, al instanciar
	function __construct()
	{
		//Datos de la geeración
		$datos = $_REQUEST;
		$resultado = array();
		$resultado_empleados = array();
		$resultado_empresa = array();
		$html = '';

		if( $datos['idPlantilla'] == '' ){
			echo '0';
			return;
		}

		//Variables del empleado
		$var = new variables();
		$var->construirVariablesValoresSinDocumento($datos,VAR_EMPLEADOS,$resultado_empleados);
		$var->construirVariablesValoresSinDocumento($datos,VAR_EMPRESAS,$resultado_empresa);
		$var->construirVariablesValoresSinDocumento($datos,VAR_ARCHIVO,$resultado_archivo);
		$var->buscarVariablesValoresSubClausulasSinDocumento($datos,$resultado_subclausulas);

		if( $datos['Firmantes_Emp'][0] != '' ) 
			$var->construirVariablesValoresSinDocumento($datos,VAR_REPRESENTANTE,$resultado_representante);

		if( $datos['Firmantes_Emp'][1] != '' ) 
			$var->construirVariablesValoresSinDocumento($datos,VAR_REPRESENTANTE_2,$resultado_representante_2);
		
		//Variables de empleados
		if( count($resultado_empleados) > 0 ){
			$resultado['variables'] = $resultado_empleados['variables'];
			$resultado['valores'] = $resultado_empleados['valores'];
		}

		//Variables de empresas
		if( count($resultado_empresa) > 0 ){
			$resultado['variables'] = array_merge($resultado['variables'],$resultado_empresa['variables']);
			$resultado['valores'] = array_merge($resultado['valores'],$resultado_empresa['valores']);
		}

		//Variables de documento
		if( count($resultado_archivo) > 0 ){
			$resultado['variables'] = array_merge($resultado['variables'],$resultado_archivo['variables']);
			$resultado['valores'] = array_merge($resultado['valores'],$resultado_archivo['valores']);
		}

		//Variables de representante 
		if( count($resultado_representante) > 0 ){
			$resultado['variables'] = array_merge($resultado['variables'],$resultado_representante['variables']);
			$resultado['valores'] = array_merge($resultado['valores'],$resultado_representante['valores']);
		}
		
		//Variables de representante 2
		if( count($resultado_representante_2) > 0 ){
			$resultado['variables'] = array_merge($resultado['variables'],$resultado_representante_2['variables']);
			$resultado['valores'] = array_merge($resultado['valores'],$resultado_representante_2['valores']);
		}
		
		//Variables de rsubclausulas
		if( count($resultado_subclausulas) > 0 ){
			$resultado['variables'] = array_merge($resultado['variables'],$resultado_subclausulas['variables']);
			$resultado['valores'] = array_merge($resultado['valores'],$resultado_subclausulas['valores']);
		}
	
		//Generar de HTML
		$gen = new generar();
		$gen->construirPlantillaSinDocumento($datos['idPlantilla'],$resultado,$html);

		echo utf8_encode($html);
	}
}


?>