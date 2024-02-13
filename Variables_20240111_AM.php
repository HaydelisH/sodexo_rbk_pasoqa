<?php
error_reporting(E_ERROR);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once("includes/Paginas.php");
// y la seguridad
include_once('includes/Seguridad.php');
// incluimos la clase para las tablas que vamos a ocupar
include_once("includes/documentosBD.php");
include_once("includes/empresasBD.php");
include_once("includes/ContratosDatosVariablesBD.php");
include_once("includes/empleadosBD.php");
include_once("includes/subclausulasBD.php");
include_once("includes/centroscostoBD.php");
include_once("includes/lugarespagoBD.php");
include_once("includes/estadocivilBD.php");
include_once("includes/rolesfirmaBD.php");
include_once("includes/cargoEmpleadoBD.php");
require_once('Config.php');  

class variables 
{
	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para el manejo de las tabla
	private $documentosBD;
	private $empresasBD;
	private $ContratosDatosVariablesBD;
	private $empleadosBD;
	private $subclausulasBD;
	private $centroscostoBD;
	private $lugarespagoBD;
	private $estadocivilBD;
	private $rolesfirmaBD;
	private $cargoEmpleadoBD;

	// para juntar los mensajes de error
	public $mensajeError="";
	private $cantidad_caracteres;

	// funcion contructora, al instanciar
	function __construct()
	{
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();
		$this->pagina = new Paginas();
		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{ 
			echo 'Mensaje | No hay conexión con la base de datos!';
			exit;
		}

		// creamos la seguridad
		//Se bloquea la seguridad, porque cuando se usa el curl, no considera la sesion del usuario y se cae 
		$this->seguridad = new Seguridad($this->pagina,$this->bd);
		// si no funciona hay que logearse
		/*if (!$this->seguridad->sesionar()) 
		{
			echo 'Mensaje | Debe Iniciar sesión!';
			exit;
		}*/

		// instanciamos del manejo de tablas
		$this->documentosBD = new documentosBD();
		$this->ContratosDatosVariablesBD = new ContratosDatosVariablesBD();
		$this->empresasBD = new empresasBD();
		$this->empleadosBD = new empleadosBD();
		$this->subclausulasBD = new subclausulasBD();
		$this->centroscostoBD = new centroscostoBD();
		$this->lugarespagoBD = new lugarespagoBD();
		$this->estadocivilBD = new estadocivilBD();
		$this->rolesfirmaBD = new rolesfirmaBD();
		$this->cargoEmpleadoBD = new cargoEmpleadoBD();

		// si se pudo abrir entonces usamos la conecion en nuestras tablas  
		$conecc = $this->bd->obtenerConexion();
		$this->documentosBD->usarConexion($conecc);
		$this->ContratosDatosVariablesBD->usarConexion($conecc);
		$this->empresasBD->usarConexion($conecc);
		$this->empleadosBD->usarConexion($conecc);
		$this->subclausulasBD->usarConexion($conecc);
		$this->centroscostoBD->usarConexion($conecc);
		$this->lugarespagoBD->usarConexion($conecc);
		$this->estadocivilBD->usarConexion($conecc);
		$this->rolesfirmaBD->usarConexion($conecc);
		$this->cargoEmpleadoBD->usarConexion($conecc);
	}

	//Construir variables y valores disponibles 
	public function construirVariablesValoresTodas($datos,&$resultado){

		$resultado = array();
		$valores = array();

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE, VAR_REPRESENTANTE_2, VAR_REPRESENTANTE_3);

		//VARIABLES DE LAS CLAUSULAS
		foreach ($tablas as $key => $value) {

			if( $this->mensajeError == '' && is_array($resultado)){
				$this->buscarVariablesValoresTodos($datos,$value,$array);
				
				array_push($resultado,$array['variables']);
				array_push($valores,$array['valores']);
				$array = array();
			}else{
				$resultado = false;
			}
		}
		
		$array = array();
			
		//VARIABLES DE LA SUBCLAUSULA 
		$this->buscarVariablesValoresSubClausulas($datos,$array);
	
		array_push($resultado,$array['variables']); 
		array_push($valores,$array['valores']);
						
		$arreglo = array();
		$arreglo['variables'] = $resultado;
		$arreglo['valores'] = $valores;
		
		$resultado = array();
		$resultado = $arreglo;

		return $resultado;
	}

	//Construir variables y valores disponibles solo las variables que tiene valores
	public function construirVariablesValores($datos,&$resultado){

		$resultado = array();
		$valores = array();

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE, VAR_REPRESENTANTE_2, VAR_REPRESENTANTE_3);
		//$subclausula = array (SUB_JORNADA, SUB_CARGO, SUB_CAUSALES, SUB_TURNOS);

		//VARIABLES DE LAS CLAUSULAS
		foreach ($tablas as $key => $value) {

			if( $this->mensajeError == '' && is_array($resultado)){
				$this->buscarVariablesValores($datos,$value,$array);
				
				array_push($resultado,$array['variables']);
				array_push($valores,$array['valores']);
				$array = array();
			}else{
				$resultado = false;
			}
		}
		
		$array = array();
			
		//VARIABLES DE LA SUBCLAUSULA 
		$this->buscarVariablesValoresSubClausulas($datos,$array);
	
		array_push($resultado,$array['variables']); 
		array_push($valores,$array['valores']);
						
		$arreglo = array();
		$arreglo['variables'] = $resultado;
		$arreglo['valores'] = $valores;
		
		$resultado = array();
		$resultado = $arreglo;

		return $resultado;
	}

	//Construir variables disponibles
	public function construirSoloVariables(&$resultado){
		$resultado = array();
		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE);
		$subclausula = array (SUB_JORNADA, SUB_CARGO, SUB_CAUSALES, SUB_TURNOS);
		//VARIABLES DE LAS CLAUSULAS
		foreach ($tablas as $key => $value) {
			if( $this->mensajeError == '' && is_array($resultado)){
				$this->buscarSoloVariables($value,$array);
				array_push($resultado,$array);
			}else{
				$resultado = false;
			}
		}
		//VARIABLES DE LA SUBCLAUSULA 
		foreach ($subclausula as $key => $value) {
			if( $value != '' ){
				if( $this->mensajeError == '' && is_array($resultado)){
					$this->buscarVariablesSubClausulas($value,$array);
					array_push($resultado,$array);
				}
				else{
					$resultado = false;
				}
			} 
		}
		//$this->graba_log("construirSoloVariables : ".implode(",",$resultado));
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	public function buscarVariablesValoresTodos($datos,$busqueda,&$resultado){

		$dt = new DataTable();
		$var_busqueda = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerVariablesDocumento($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerVariablesEmpleado($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->ContratosDatosVariablesBD->obtener($datos,$dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:

				$datos["RutUsuario"] = $datos['Firmantes_Emp'][0];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}

				break;
			
			case VAR_REPRESENTANTE_2:

				$datos["RutUsuario"] = $datos['Firmantes_Emp'][1];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
			
			case VAR_REPRESENTANTE_3:
				
				$datos["RutUsuario"] = $datos['Firmantes_Emp'][2];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_3;
				}
				break;
		}

		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula
		$var_formato_sm = ''; //Separador de miles
		$var_formato_indefinida = ''; //Indefinida
		$var_formato_combinado = ''; //Comoinado de /SM@0
		$var_formato_ci = ''; //Clausula Indefinida
		$var_formato_cp = ''; //Clausula a Plazo fija 
        $var_formato_indefinida = ''; //Indefinida

		if( count($dt->data) > 0 && $this->mensajeError == '' ){

			// FechaDinamica
			switch (ORIGEN_DATA_VAR_DINAMICA)
			{
				case 'SERVIDOR':
					$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema(date('d-m-Y'));
				break;
				default:
					if ($dt->data[0][ORIGEN_DATA_VAR_DINAMICA] != null)
					{
						//var_dump($dt->data[0][ORIGEN_DATA_VAR_DINAMICA]);
						//var_dump(VAR_DINAMICA, ORIGEN_DATA_VAR_DINAMICA, $dt->data[0][ORIGEN_DATA_VAR_DINAMICA]);
						// string(13) "FechaDinamica" string(11) "FechaInicio" string(10) "10-08-2019"
						$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema($dt->data[0][ORIGEN_DATA_VAR_DINAMICA]);
						//var_dump($dt->data[0][VAR_DINAMICA]);
						// NULL
						
					}
				break;
			}

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				$this->cantidad_caracteres = 0;
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
						$var_formato_ci = PREFIJO_VAR.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
                        $var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;

					}

					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_combinado = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
						$var_formato_ci = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
                        $var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
					}

					if ( $this->validateDate($value,'d-m-Y')){
						$this->graba_log("fecha *** ".$value);
						//Si la fecha es Indefinido
						if( $value == VAR_FECHA_IND ){

							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_ci);
							array_push($variables,$var_formato_indefinida);

							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDA_NUEVA);                                       

						}else{

							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_ci);
							array_push($variables,$var_formato_cp);
							array_push($variables,$var_formato_indefinida);
					
							$fecha_s = $this->convertirFechaLarga($value);		
							$fecha_c = $this->convertirFechaCorta($value);
							
							array_push($aux,$value);
							array_push($aux,VAR_HASTA_EL.$fecha_s);
							array_push($aux,VAR_HASTA_EL.$fecha_c);
							array_push($aux,$fecha_s);
							array_push($aux,$fecha_c);
							array_push($aux,$value);
							array_push($aux,$value);
							array_push($aux,VAR_HASTA.$fecha_s);
						}

					}else{

						#if ( is_numeric($value)){
						if ( is_numeric(preg_replace('/[,]/i', '.', $value)) || is_numeric($value)){
							$value = preg_replace('/[,]/i', '.', $value);

							//Validar si es un decimal o que por error le colocaron ','
							$con_comas = count(explode(',',$value));
							$con_puntos = count(explode('.',$value));
							$this->cantidad_caracteres = 0;	
							
							if ( $con_comas == 2 || $con_puntos == 2 ) {
								$this->validarNumeroEnteroDecimal($value,$value); 
							}
								
							array_push($variables,$var);
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							array_push($variables,$var_formato_arroba);
							array_push($variables,$var_formato_sm);//Separador de miles
							array_push($variables,$var_formato_combinado);

							/*$numeros = $this->numerosALetras($value);
							$con_separador_de_miles = '';
							$con_separador_de_miles = number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES);*/
								
							/*if ( count(explode(',',$con_separador_de_miles)) == 2 ){
									$res = explode (',',$con_separador_de_miles);*/
							if ( count(explode('.',$value)) == 2 ){
								$res = explode ('.',$value);
								$parte_entera = $this->numerosALetras($res[0]);
								$parte_decimal = $this->numerosALetras($res[1]);
								if( $parte_decimal != '' )
								{
									//$numeros = $parte_entera." COMA ".$parte_decimal;
									$cerosIzquierda = $this->ceros((string)$res[1]);
									$numeros = $parte_entera." COMA ";
									for ($i = 0; $i < $cerosIzquierda; $i++)
									{
										$numeros .= 'CERO' . ($i == $cerosIzquierda - 1 ? '' : ' ');
									}
									$numeros .= $parte_decimal != 'CERO' ? ' ' . $parte_decimal : '';
								}
								else	
								{
									$numeros = $this->numerosALetras($parte_entera);
								}
							}
							else
							{
								$numeros = $this->numerosALetras($value);
							}

							$this->cantidad_caracteres = strpos($value, '.') ? (strlen($value) - strpos($value, '.') - 1) : 0;
                            $con_separador_de_miles = number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES); 

							$value = preg_replace('/[\.]/i', ',', $value);
							array_push($aux,$value);
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							array_push($aux,$value);
                            array_push($aux,$con_separador_de_miles);//Separador de miles
                            array_push($aux,$con_separador_de_miles);//Separador de miles
						}else{

							array_push($variables, $var);
							array_push($aux, $value);
						}
					}	
				}
			}
		}

		$resultado = array();
		$resultado['variables'] = $variables;
		$resultado['valores'] = $aux;

		return $resultado;
	}

	private function ceros($cadena)
	{
		$cuenta = 0;
		for ($i = 0; $i < strlen($cadena); $i++)
		{
			if ($cadena[$i] === "0")
			{
				$cuenta++;
			}
			else
			{
				break;
			}
		}
		return $cuenta;
	}

	private function validarNumeroEnteroDecimal($numero,&$resultado){
		
		//$numero = '25469,02';
        $int = 0;
		$decimal = 0 ;
		$res = array();
		$resultado = '';
		$coma = strpos($numero, ',');
		$punto = strpos($numero,'.');
		
		if( $coma ){
			$res = explode(',',$numero);
		}
		
		if( $punto ){ 
			$res = explode('.',$numero);
		}
		
		if( count($res) == 2 ){
			
			$int = $res[0];
			$decimal = $res[1];
					
			if( $decimal > 0 && $coma ){
				$resultado = str_replace(',','.', $numero); 
				$this->cantidad_caracteres = strlen($decimal); 
			}
			else if( $decimal > 0 && $punto ){
				$resultado = $numero; 
				$this->cantidad_caracteres = strlen($decimal);  
			}else if ( $decimal <= 0 ){
				$resultado = $int;
				$this->cantidad_caracteres = 0;
			}
		}else{
			$resultado = $numero; 
			$this->cantidad_caracteres = 0;  
		}
	}			
	
	//Buscar las variables y valores de subclausulas
	private function buscarVariablesValoresSubClausulas($datos,&$resultado){
	
		$dt = new DataTable();
		$dt_doc = new DataTable();
		$var_busqueda = '';
		$variables = array();
		$aux = array();
		$var = '';

		//Buscar subclausulas 
		$this->ContratosDatosVariablesBD->obtener($datos,$dt);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $dt->leerFila() ){
			$jornada = $dt->obtenerItem('Jornada');
			$cargo = $dt->obtenerItem('Cargo');
			$numeroarticulo = $dt->obtenerItem('NroArticulo');
		}
		$this->documentosBD->obtener($datos,$dt_doc);
		$this->mensajeError .= $this->documentosBD->mensajeError;
		
		if($dt_doc->leerFila()){
			$RutEmpresa = $dt_doc->obtenerItem('RutEmpresa');
		}
		
		$array_subclausulas = array();
		
		$array_subclausulas[0]['idSubClausula'] = $cargo; 
		$array_subclausulas[0]['idTipoSubClausula'] = 3; 
		
		$array_subclausulas[1]['idSubClausula'] = $jornada; 
		$array_subclausulas[1]['idTipoSubClausula'] = 2;

		$array_subclausulas[2]['idSubClausula'] = $numeroarticulo; 
		$array_subclausulas[2]['idTipoSubClausula'] = 6;

		foreach($array_subclausulas as $key => $value){
	
			$this->subclausulasBD->obtener($array_subclausulas[$key],$dt);
			$this->mensajeError = $this->subclausulaBD->mensajeError;

			$tipo = '';
			$tipo = $dt->data[0]['TipoSubClausula'];

			if( count($dt->data) > 0 ){

				//Construimos el arreglo de variables 
				foreach ($dt->data[0] as $key => $value) {

					if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){

						if( VAR_SUBCLAUSULAS == '') 
							$var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
						else 
							$var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

						if( strlen($value) > 0 ) {
							array_push($variables, htmlentities($var));
							array_push($aux, $value);
						}
					}
				}
			} 
		}
		
		$resultado = array();
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;
		return $resultado;
	}

	//Sustituir variables
	//Recibe un string que es el html de la Plantilla, un arreglo de variables, un arreglo de valores a sustituir y los datos correspondientes al documento.
	//Devuelve el HTML con las variables sustituidas
	public function sustituirVariables($html,$variables,$valores,$datos,&$resultado){
		$resultado = '';
		//Buscamos si existe conincidencia
		foreach ($variables as $key => $value) {
			if ( strstr($html, $value)){
				if ( strlen($valores[$key]) > 0 ){
					//Sustituir en el HTML
					$html = str_replace($value, $valores[$key], $html);
				}
			}
		}

		if( strstr($html,VAR_LOGO)){
			$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
			$this->mensajeError = $this->documentosBD->mensajeError;
			$rut_empresa = $dt->data[0]["RutEmpresa"];
			$logo = VAR_RUTA_COMPLETA.$rut_empresa.'.'.VAR_EXTENSION;
			#$logo = $rut_empresa.'.'.VAR_EXTENSION;
			$html = str_replace(VAR_LOGO,$logo,$html);
			$this->graba_log(VAR_LOGO." : ".$logo);
		}

		if( strstr($html,VAR_RUTA)){
			$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
			$this->mensajeError = $this->documentosBD->mensajeError;
			$rut_empresa = $dt->data[0]["RutEmpresa"];
			$ruta = VAR_RUTA_COMPLETA;
			$html = str_replace(VAR_RUTA,$ruta,$html);
		}	

		foreach( $variables as $key => $value ){
			//$this->graba_log('('.$value.' = '.$value.')');
			$this->graba_log($value."_".$valores[$key]);
		}

		$resultado = $html;
		
		return $resultado;
	}
	

	//Devuelve el HTML con las variables sustituidas sin documento
	public function sustituirVariablesSinDocumento($html,$variables,$valores,&$resultado){
	
		$resultado = '';
		//Buscamos si existe conincidencia
		foreach ($variables as $key => $value) {
			if ( strstr($html, $value)){
				if ( strlen($valores[$key]) > 0 ){
					//Sustituir en el HTML
					$html = str_replace($variables,$valores,$html);
				}
			}
		}
		$resultado = $html;
		
		return $resultado;
	}
	
	//Buscar variables vacias 
	public function buscarVariablesVacias($html,$variables,$valores){
		
		$cant = count($variables);
		$j = 0;
		
		if ( $cant > 0 ){
			for( $i = 0; $i < $cant; $i++ ){ 

				$aux = '';
				$num_var_vacia = 0;
				$num_var_vacia = strlen(VAR_VACIA);
				if( strstr($html, $variables[$i] )){ 
					$aux = strstr($variables[$i], VAR_VACIA);
					$aux = substr($aux,0,$num_var_vacia);
					if( $aux == VAR_VACIA ){ 
						if (!is_numeric($valores[$i]))
						{
							$valores[$i] = str_replace(',', '.', $valores[$i]);
						}
						if( $valores[$i] == 0 ){ 
							$j++;
						}
					}
				}
			}	
		}
		
		if( $j > 0 ) return true;
		else return false;
	}

	//Buscar variables vacias 
	public function buscarVariablesVaciasSinDocumento($html,$variables,$valores){
		
		$cant = count($variables);
		$j = 0;
		
		if ( $cant > 0 ){
			for( $i = 0; $i < $cant; $i++ ){ 

				$aux = '';
				$num_var_vacia = 0;
				$num_var_vacia = strlen(VAR_VACIA);
				
				if( strstr($html, $variables[$i] )){ 
					
					$aux = strstr($variables[$i], VAR_VACIA);
					$aux = substr($aux,0,$num_var_vacia);
				
					if( $aux == VAR_VACIA ){ 
						if( $valores[$i] == 0 ){ 
							$j++;
						}
					}
				}
			}	
		}
		
		if( $j > 0 ) return true;
		else return false;
	}

	//Valida Fecha
	public function validateDate($date, $format = VAR_FORMATO_FECHA){	
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	//Convertir a formato de fecha larga 
	private function convertirFechaLarga($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%d de %B de %Y", strtotime($fecha));
		//return ucwords($resultado);
		return $resultado;
	}

	//Convertir a formato de fecha corta 
	private function convertirFechaCorta($fecha){

		setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
		$resultado = strftime("%B %Y", strtotime($fecha));
		return ucwords($resultado);
	}

	//Funcion para devolver numeros ordinales 
	public function numerosALetras($xcifra){

	    $xarray = array(0 => "Cero",
	        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
	        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
	        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
	        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
	    );
		//
	    $xcifra = trim($xcifra);
	    $xlength = strlen($xcifra);
	    $xpos_punto = strpos($xcifra, ".");
	    $xaux_int = $xcifra;
	    $xdecimales = "00";
	    if (!($xpos_punto === false)) {
	        if ($xpos_punto == 0) {
	            $xcifra = "0" . $xcifra;
	            $xpos_punto = strpos($xcifra, ".");
	        }
	        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
	    }

	    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
	    $xcadena = "";
	    for ($xz = 0; $xz < 3; $xz++) {
	        $xaux = substr($XAUX, $xz * 6, 6);
	        $xi = 0;
	        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
	        $xexit = true; // bandera para controlar el ciclo del While
	        while ($xexit) {
	            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
	                break; // termina el ciclo
	            }

	            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
	            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
	            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
	                switch ($xy) {
	                    case 1: // checa las centenas
	                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
	                            
	                        } else {
	                            $key = (int) substr($xaux, 0, 3);
	                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
	                                if (substr($xaux, 0, 3) == 100)
	                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
	                            }
	                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
	                                $key = (int) substr($xaux, 0, 1) * 100;
	                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
	                                $xcadena = " " . $xcadena . " " . $xseek;
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 0, 3) < 100)
	                        break;
	                    case 2: // checa las decenas (con la misma lógica que las centenas)
	                        if (substr($xaux, 1, 2) < 10) {
	                            
	                        } else {
	                            $key = (int) substr($xaux, 1, 2);
	                            if (TRUE === array_key_exists($key, $xarray)) {
	                                $xseek = $xarray[$key];
	                                $xsub = $this->subfijo($xaux);
	                                if (substr($xaux, 1, 2) == 20)
	                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                                $xy = 3;
	                            }
	                            else {
	                                $key = (int) substr($xaux, 1, 1) * 10;
	                                $xseek = $xarray[$key];
	                                if (20 == substr($xaux, 1, 1) * 10)
	                                    $xcadena = " " . $xcadena . " " . $xseek;
	                                else
	                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
	                            } // ENDIF ($xseek)
	                        } // ENDIF (substr($xaux, 1, 2) < 10)
	                        break;
	                    case 3: // checa las unidades
	                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
	                            
	                        } else {
	                            $key = (int) substr($xaux, 2, 1);
	                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
	                            $xsub = $this->subfijo($xaux);
	                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
	                        } // ENDIF (substr($xaux, 2, 1) < 1)
	                        break;
	                } // END SWITCH
	            } // END FOR
	            $xi = $xi + 3;
	        } // ENDDO

	        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
	            $xcadena.= " DE";

	        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
	        if (trim($xaux) != "") {
	            switch ($xz) {
	                case 0:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN BILLON ";
	                    else
	                        $xcadena.= " BILLONES ";
	                    break;
	                case 1:
	                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
	                        $xcadena.= "UN MILLON ";
	                    else
	                        $xcadena.= " MILLONES ";
	                    break;
	                case 2:
	                    if ($xcifra < 1) {
	                        $xcadena = "CERO ";
	                    }
	                    if ($xcifra >= 1 && $xcifra < 2) {
	                        $xcadena = "UN ";
	                    }
	                    if ($xcifra >= 2) {
	                        $xcadena.= " "; //
	                    }
	                    break;
	            } // endswitch ($xz)
	        } // ENDIF (trim($xaux) != "")
	        // ------------------      en este caso, para México se usa esta leyenda     ----------------
	        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
	        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
	        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
	        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	    } // ENDFOR ($xz)
	    return trim($xcadena);
	}
	
	public function arregloMultiASimple($array, &$resultado){
		
		$resultado = array();
		
		foreach( $array as $key => $value ){
			foreach ( $value as $key_1 => $value_1 ){
				array_push($resultado,$value_1);
			}
		}
		return $resultado;
	}

	public function subfijo($xx){  // esta función regresa un subfijo para la cifra
	    $xx = trim($xx);
	    $xstrlen = strlen($xx);
	    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
	        $xsub = "";
	    //
	    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
	        $xsub = "MIL";
	    
	    return $xsub;
	}

	//Graba log de entradas 
	private function graba_log ($mensaje){

		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'logs/logimp'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
		die("Problemas en la creacion");
		fputs($ar,@date("H:i:s")." ".$mensaje);
		fputs($ar,"\n");
		fclose($ar);      
	}

	//Validar si el documento tiene variables de un tipo 
	public function construirVariablesValoresSinDocumento($datos,$busqueda,&$resultado){

		$dt = new DataTable();
		$var_busqueda = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerEncabezadosDocumento($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 
				if ( $datos['RutEmpresa'] != 0 ){
					$this->documentosBD->obtenerVariablesEmpresaConRutEmpresa($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
				}
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerEncabezadosEmpleado($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;

				//Buscar valores de los campos 
				$respuesta = $this->camposEquivalenciasEmpleados($dt->data[0],$datos);
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->documentosBD->obtenerEncabezadosVariables($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;

				//Buscar valores de los campos 
				$respuesta = $this->camposEquivalenciasArchivos($dt->data[0],$datos);
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:
				
				$datos["rutusuario"] = $datos['Firmantes_Emp'][0];
				if( $datos["rutusuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRutSinDocumento($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}
				break;
			
			case VAR_REPRESENTANTE_2:

				$datos["rutusuario"] = $datos['Firmantes_Emp'][1];
				if( $datos["rutusuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRutSinDocumento($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
		}

		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula
		$var_formato_sm = ''; //Separador de miles
		$var_formato_combinado = ''; //Comoinado de /SM@0
		$var_formato_ci = ''; //Clausula Indefinida
		$var_formato_cp = ''; //Clausula a Plazo fija 
        $var_formato_indefinida = ''; //Indefinida

		if( count($dt->data) > 0 && $this->mensajeError == '' ){

			// FechaDinamica
			switch (ORIGEN_DATA_VAR_DINAMICA)
			{
				case 'SERVIDOR':
					$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema(date('d-m-Y'));
				break;
				default:
					if ($dt->data[0][ORIGEN_DATA_VAR_DINAMICA] != null)
					{
						$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema($dt->data[0][ORIGEN_DATA_VAR_DINAMICA]);
					}
				break;
			}

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {

				$this->cantidad_caracteres = 0;
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_combinado = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
						$var_formato_ci = PREFIJO_VAR.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
                        $var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;

					}

					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
						$var_formato_sm = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_combinado = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_SEPARADOR_MILES.VAR_VACIA.SUFIJO_VAR;
						$var_formato_ci = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
                        $var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
					}

					if ( $this->validateDate($value,'d-m-Y')){

						//Si la fecha es Indefinido
						if( $value == VAR_FECHA_IND ){

							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_ci);
							array_push($variables,$var_formato_indefinida);                                                                                                                                                                                  

							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDA_NUEVA);                                       

						}else{

							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_ci);
							array_push($variables,$var_formato_cp);
							array_push($variables,$var_formato_indefinida);                             

							$fecha_s = $this->convertirFechaLarga($value);		
							$fecha_c = $this->convertirFechaCorta($value);
							
							array_push($aux,$value);
							array_push($aux,VAR_HASTA_EL.$fecha_s);
							array_push($aux,VAR_HASTA_EL.$fecha_c);
							array_push($aux,$fecha_s);
							array_push($aux,$fecha_c);
							array_push($aux,$value);
							array_push($aux,$value);
							array_push($aux,VAR_HASTA.$fecha_s);

						}

					}else{

						if ( is_numeric(preg_replace('/[,]/i', '.', $value)) || is_numeric($value)){
							$value = preg_replace('/[,]/i', '.', $value);

							array_push($variables,$var);
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							array_push($variables,$var_formato_arroba);
							array_push($variables,$var_formato_sm);//Separador de miles
							array_push($variables,$var_formato_combinado);

							if ( count(explode('.',$value)) == 2 ){
								$res = explode ('.',$value);
								$parte_entera = $this->numerosALetras($res[0]);
								$parte_decimal = $this->numerosALetras($res[1]);
								if( $parte_decimal != '' )
								{
									$cerosIzquierda = $this->ceros((string)$res[1]);
									$numeros = $parte_entera." COMA ";
									for ($i = 0; $i < $cerosIzquierda; $i++)
									{
										$numeros .= 'CERO' . ($i == $cerosIzquierda - 1 ? '' : ' ');
									}
									$numeros .= $parte_decimal != 'CERO' ? ' ' . $parte_decimal : '';
								}
								else        
								{
									$numeros = $this->numerosALetras($parte_entera);
								}
							}
							else
							{
								$numeros = $this->numerosALetras($value);
							}

							$this->cantidad_caracteres = strpos($value, '.') ? (strlen($value) - strpos($value, '.') - 1) : 0;
                            $con_separador_de_miles = number_format($value,$this->cantidad_caracteres,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES); 

							$value = preg_replace('/[\.]/i', ',', $value);
							array_push($aux,$value);
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							array_push($aux,$value);
                            array_push($aux,$con_separador_de_miles);//Separador de miles
                            array_push($aux,$con_separador_de_miles);//Separador de miles            
												
						}else{

							array_push($variables, $var);
							array_push($aux, $value);
						}
					}	
				}
			}
		}

		$resultado = array();
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;

		return $resultado;
	}

	//Equivalencias de campos de empleados
	private function camposEquivalenciasEmpleados(&$consulta,$datos){

		$dt  = new DataTable();

		//Estado civil 
		$this->estadocivilBD->obtener($datos,$dt);
		$this->mensajeError .= $this->estadocivilBD->mensajeError;
		$ec = $dt->data[0]['Descripcion'];

		if($this->mensajeError != '' ) return false;

		//Roles
		$this->rolesfirmaBD->obtener($datos,$dt);
		$this->mensajeError .= $this->rolesfirmaBD->mensajeError;
		$rol = $dt->data[0]['Descripcion'];

		if($this->mensajeError != '' ) return false;

		foreach ($consulta as $key => $value) { 

			if( $key === 'Rut') $consulta['Rut'] = $datos['newusuarioid'];
			if( $key === 'RutTrabajador') $consulta['RutTrabajador'] = $datos['newusuarioid'];
			if( $key === 'NombreTrabajador') $consulta['NombreTrabajador'] = $datos['nombre'];
			if( $key === 'Nombre') $consulta['Nombre'] = $datos['nombre'].' '.$datos['appaterno'].' '.$datos['apmaterno'];
			if( $key === 'ApellidoPaternoTrabajador') $consulta['ApellidoPaternoTrabajador'] = $datos['appaterno'];
			if( $key === 'ApellidoMaternoTrabajador') $consulta['ApellidoMaternoTrabajador'] = $datos['apmaterno'];
			if( $key === 'Nacionalidad') $consulta['Nacionalidad'] = $datos['nacionalidad'];
			if( $key === 'FechaNacimiento') $consulta['FechaNacimiento'] = $datos['fechanacimiento'];
			if( $key === 'EstadoCivil') $consulta['EstadoCivil'] = $ec;
			if( $key === 'direccion') $consulta['direccion'] = $datos['direccion'];
			if( $key === 'Direccion') $consulta['Direccion'] = $datos['direccion'].' '.$datos['comuna'].' '.$datos['ciudad'];
			if( $key === 'Comuna') $consulta['Comuna'] = $datos['comuna'];
			if( $key === 'comuna') $consulta['comuna'] = $datos['comuna'];
			if( $key === 'ciudad') $consulta['ciudad'] = $datos['ciudad'];
			if( $key === 'Ciudad') $consulta['Ciudad'] = $datos['ciudad'];
			if( $key === 'CiudadTrabajador') $consulta['CiudadTrabajador'] = $datos['ciudad'];
			if( $key === 'Rol') $consulta['Rol'] = $rol;
			if( $key === 'RolEmpleado') $consulta['RolEmpleado'] = $rol;
			if( $key === 'CorreoElectronicoEmpleado') $consulta['CorreoElectronicoEmpleado'] = $datos['correo'];
			if( $key === 'Correo') $consulta['Correo'] = $datos['correo'];
			if( $key === 'EstadoEmpleado') $consulta['EstadoEmpleado'] = $datos['idEstadoEmpleado'];
			if( $key === 'region') $consulta['region'] = $datos['region'];
			if( $key === 'Region') $consulta['Region'] = $datos['region'];
		}

		return true;
	}

	//Equivalencias de campos de documento
	private function camposEquivalenciasArchivos(&$consulta,$datos){

		$dt  = new DataTable();
		$datos['idCargoEmpleado'] = $datos['Cargo'];

		//Cargos 
		if( $datos['idCargoEmpleado'] != '' ){
			$this->cargoEmpleadoBD->obtener($datos,$dt);
			$this->mensajeError .= $this->estadocivilBD->mensajeError;
			$descripcion_cargo = $dt->data[0]['DescripcionCargo'];
			$obligaciones_cargo = $dt->data[0]['ObligacionesCargo'];
			$titulo_cargo = $dt->data[0]['TituloCargo'];
		}

		if($this->mensajeError != '' ) return false;

		//Centros de costo 
		$this->centroscostoBD->obtener($datos,$dt);
		$this->mensajeError .= $this->centroscostoBD->mensajeError;
		$nombre_centrocosto = $dt->data[0]['Descripcion'];

		if($this->mensajeError != '' ) return false;

		foreach ($consulta as $key => $value) { 
			//Datos
			if( $key === 'FechaInicio') $consulta['FechaInicio'] = $datos['FechaInicio'];
			if( $key === 'Fecha') $consulta['Fecha'] = $datos['Fecha'];
			if( $key === 'FechaTermino') $consulta['FechaTermino'] = $datos['FechaTermino'];
			if( $key === 'FechaIngreso') $consulta['FechaIngreso'] = $datos['FechaIngreso'];
			if( $key === 'BonoAsistencia') $consulta['BonoAsistencia'] = $datos['bonoAsistencia'];
			if( $key === 'Colacion') $consulta['Colacion'] = $datos['colacion'];
			if( $key === 'ContratoComercial') $consulta['ContratoComercial'] = $datos['contratoComercial'];
			if( $key === 'Descuento') $consulta['Descuento'] = $datos['Descuento'];
			if( $key === 'DireccionCliente') $consulta['DireccionCliente'] = $datos['DireccionCliente'];
			if( $key === 'Jefatura') $consulta['Jefatura'] = $datos['Jefatura'];
			if( $key === 'NombreCliente') $consulta['NombreCliente'] = $datos['NombreCliente'];
			if( $key === 'Movilizacion') $consulta['Movilizacion'] = $datos['Movilizacion'];
			if( $key === 'SueldoBase') $consulta['SueldoBase'] = $datos['SueldoBase'];
			if( $key === 'Texto1') $consulta['Texto1'] = $datos['Texto1'];
			if( $key === 'Texto2') $consulta['Texto2'] = $datos['Texto2'];
			if( $key === 'BonoResponsabilidad') $consulta['BonoResponsabilidad'] = $datos['BonoResponsabilidad'];
			if( $key === 'TramoCliente') $consulta['TramoCliente'] = $datos['TramoCliente'];
			
			if( $key === 'Segmento') $consulta['Segmento'] = $datos['Segmento'];
			if( $key === 'PorcentajeBonoTarget') $consulta['PorcentajeBonoTarget'] = $datos['PorcentajeBonoTarget'];
			if( $key === 'MetaTargetS') $consulta['MetaTargetS'] = $datos['MetaTargetS'];
			if( $key === 'MetaTargetU') $consulta['MetaTargetU'] = $datos['MetaTargetU'];
			if( $key === 'ObjetivoFinanciero1') $consulta['ObjetivoFinanciero1'] = $datos['ObjetivoFinanciero1'];
			if( $key === 'DetalleObjetivoFinanciero1') $consulta['DetalleObjetivoFinanciero1'] = $datos['DetalleObjetivoFinanciero1'];
			if( $key === 'ObjetivoFinanciero2') $consulta['ObjetivoFinanciero2'] = $datos['ObjetivoFinanciero2'];
			
			if( $key === 'DetalleObjetivoFinanciero2') $consulta['DetalleObjetivoFinanciero2'] = $datos['DetalleObjetivoFinanciero2'];
			if( $key === 'MetaIndividual') $consulta['MetaIndividual'] = $datos['MetaIndividual'];
			
			if( $key === 'Bono') $consulta['Bono'] = $datos['Bono'];
			//Datos de cargo
			if( $key === 'Cargo') $consulta['Cargo'] = $datos['Cargo'];
			if( $key === 'DescripcionCargo') $consulta['DescripcionCargo'] = $datos['DescripcionCargo'];
			if( $key === 'ObligacionesCargo') $consulta['ObligacionesCargo'] = $obligaciones_cargo;
			if( $key === 'TituloCargo') $consulta['TituloCargo'] = $titulo_cargo;
			//Centros de costo
			if( $key === 'CentroCosto') $consulta['CentroCosto'] = $nombre_centrocosto;
			//Subclausulas
			if( $key === 'Jornada') $consulta['Jornada'] = $datos['Jornada'];
			if( $key === 'Ciudad') $consulta['Ciudad'] = $datos['Ciudad2'];

			if( $key === 'nombreJefeDirecto') $consulta['nombreJefeDirecto'] = $datos['nombreJefeDirecto'];
			if( $key === 'cargoJefeDirecto') $consulta['cargoJefeDirecto'] = $datos['cargoJefeDirecto'];
			if( $key === 'nombreDirectorSegmento') $consulta['nombreDirectorSegmento'] = $datos['nombreDirectorSegmento'];
			if( $key === 'directorDeSegmento') $consulta['directorDeSegmento'] = $datos['directorDeSegmento'];

			if( $key === 'Texto2') $consulta['Texto2'] = $datos['Texto2'];
			if( $key === 'Texto3') $consulta['Texto3'] = $datos['Texto3'];
			if( $key === 'Texto4') $consulta['Texto4'] = $datos['Texto4'];
			if( $key === 'Porcentaje1') $consulta['Porcentaje1'] = $datos['Porcentaje1'];
			if( $key === 'Porcentaje2') $consulta['Porcentaje2'] = $datos['Porcentaje2'];
			if( $key === 'Porcentaje3') $consulta['Porcentaje3'] = $datos['Porcentaje3'];
			if( $key === 'Porcentaje4') $consulta['Porcentaje4'] = $datos['Porcentaje4'];
			
			if( $key === 'Stretch1') $consulta['Stretch1'] = $datos['Stretch1'];
			if( $key === 'Stretch2') $consulta['Stretch2'] = $datos['Stretch2'];
			
			if( $key === 'Porcentaje5') $consulta['Porcentaje5'] = $datos['Porcentaje5'];
			if( $key === 'Porcentaje6') $consulta['Porcentaje6'] = $datos['Porcentaje6'];
			
			if( $key === 'Texto5') $consulta['Texto5'] = $datos['Texto5'];
			
			if( $key === 'Jornada2') $consulta['Jornada2'] = $datos['Jornada2'];
			
			if( $key === 'AplicaStrech1') $consulta['AplicaStrech1'] = $datos['AplicaStrech1'];
			if( $key === 'AplicaStrech2') $consulta['AplicaStrech2'] = $datos['AplicaStrech2'];
			if( $key === 'Stretch3') $consulta['Stretch3'] = $datos['Stretch3'];
			if( $key === 'Stretch4') $consulta['Stretch4'] = $datos['Stretch4'];
			if( $key === 'Booster') $consulta['Booster'] = $datos['Booster'];
			if( $key === 'Modificador') $consulta['Modificador'] = $datos['Modificador'];
			
			if( $key === 'Texto6') $consulta['Texto6'] = $datos['Texto6'];
			
			
	
		}
		return true;
	}

	//Equivalencias de campos de empleados
	private function camposEquivalenciasDocumento(&$consulta,$datos){

		$dt  = new DataTable();

		date_default_timezone_set('America/Santiago');
		foreach ($consulta as $key => $value) { 
					if( $key === 'Fecha') $consulta['Fecha'] =  date('d-m-Y');
		}
		return true;
	}

	//Separar arreglos de variables y valores 
	public function separarArregloVariablesValores($array, &$resultado){
		
		$variables = array();
		$aux = array();

		foreach( $array as $key => $value ){
			array_push($variables,$key);
			array_push($aux,$value); 
		}

		$resultado = array();
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;

		return $resultado;
	}
	
	//Buscar las variables y valores de subclausulas sin documento
	public function buscarVariablesValoresSubClausulasSinDocumento($datos,&$resultado){
	
		$dt = new DataTable();
		$array_subclausulas = array();
		
		//Codigod de subclausulas
		$jornada = $datos['Jornada'];
	
		//Jotnada
		$array_subclausulas[0]['idSubClausula'] = $jornada; 
		$array_subclausulas[0]['idTipoSubClausula'] = 2;

		$variables = array();
		$aux = array();

		$resultado = array();
		foreach($array_subclausulas as $key => $value){
	
			$this->subclausulasBD->obtener($array_subclausulas[$key],$dt);
			$this->mensajeError = $this->subclausulaBD->mensajeError;
		
			$tipo = '';
			$tipo = $dt->data[0]['TipoSubClausula'];

			if( count($dt->data) > 0 ){

				$var = '';

				//Construimos el arreglo de variables 
				foreach ($dt->data[0] as $key => $value) {

					if ( ! is_numeric($key) && ($key != 'TipoSubClausula')){

						if( VAR_SUBCLAUSULAS == '') 
							$var = PREFIJO_VAR.$tipo.SEPARADOR.$key.SUFIJO_VAR;
						else 
							$var = PREFIJO_VAR.VAR_SUBCLAUSULAS.'.'.$tipo.SEPARADOR.$key.SUFIJO_VAR;

						if( strlen($value) > 0 ) {
							array_push($variables, htmlentities($var));
							array_push($aux, $value);
						}
					}
				}
			} 
		}
		
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	public function buscarSoloVariables($busqueda,&$resultado){

		$dt = new DataTable();
		$var_busqueda = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerEncabezadosDocumento($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 

				$this->documentosBD->obtenerEncabezadosEmpresa($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerEncabezadosEmpleado($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->ContratosDatosVariablesBD->obtenerEncabezados($dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE: 

				$this->documentosBD->obtenerEncabezadosRepresentante($dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_REPRESENTANTE;
				break;

		}

		$variables = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula

		if( count($dt->data) > 0 && $this->mensajeError == '' ){

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
					}

					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
					}

					if( strlen($value) > 0 ) {
					
						if ( $this->validateDate($value,'d-m-Y')){
							$this->graba_log("fecha *** ".$value);
							//Si la fecha es Indefinido
							if( $value == VAR_FECHA_IND ){

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								
							}else{

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);

							}

						}else{
							array_push($variables, $var);
						}	

						if ( is_numeric($value)){
							
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
				
						}	
					}
				}
			}
		}
		$resultado = array();
		$resultado = $variables;
		//$this->graba_log("buscarSoloVariables : ".implode(",",$resultado));
		return $resultado;
	}

	//Buscar subclausulas
	public function buscarVariablesSubClausulas($subclausula,&$resultado){

		$dt = new DataTable();
	
		$this->subclausulasBD->obtenerEncabezados($dt);
		$this->mensajeError = $this->subclausulasBD->mensajeError;

		$tipo = $subclausulas;
		$var = '';
		$variables = array();

		if( count($dt->data) > 0 ){

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				
				if( ! is_numeric($key) ){

					if( VAR_SUBCLAUSULAS == '') $var = $tipo.SEPARADOR.$key;
					else $var = VAR_SUBCLAUSULAS.'.'.$tipo.htmlspecialchars_decode(SEPARADOR).$key;
			
					array_push($variables, htmlspecialchars_decode($var));
				}
					
			}
		}

		$resultado = array();
		$resultado = $variables;
		//$this->graba_log("buscarVariablesSubClausulas : ".implode(",",$resultado));
		return $resultado;
	}

	//Validar si el documento tiene variables de un tipo 
	public function buscarVariablesValores($datos,$busqueda,&$resultado){

		$dt = new DataTable();
		$var_busqueda = '';

		//Consultamos segun la tabla a consultar

		switch( $busqueda ){

			case VAR_DOCUMENTO:

				$this->documentosBD->obtenerVariablesDocumento($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_DOCUMENTO;
				break;

			case VAR_EMPRESAS: 

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPRESAS;
				break;

			case VAR_EMPLEADOS: 

				$this->documentosBD->obtenerVariablesEmpleado($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$var_busqueda = VAR_EMPLEADOS;
				break;

			case VAR_ARCHIVO: 

				$this->ContratosDatosVariablesBD->obtener($datos,$dt);
				$this->mensajeError = $this->ContratosDatosVariablesBD->mensajeError;
				$var_busqueda = VAR_ARCHIVO;
				break;
				
			case VAR_REPRESENTANTE:
			
				$datos["RutUsuario"] = $datos['Firmantes_Emp'][0];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE;
				}
				break;
			
			case VAR_REPRESENTANTE_2:
				
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][1];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_2;
				}
				break;
			
			case VAR_REPRESENTANTE_3:
				
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][2];
				if( $datos["RutUsuario"] != '' ){
					$this->documentosBD->obtenerVariablesRepresentante_conRut($datos,$dt);
					$this->mensajeError = $this->documentosBD->mensajeError;
					$var_busqueda = VAR_REPRESENTANTE_3;
				}
				break;
		}

		$variables = array();
		$aux = array();
		$var = '';
		$var_formato_s = '';
		$var_formato_c = '';
		$var_formato_d = '';
		$var_formato_n = ''; //Mayusculas
		$var_formato_m = ''; //Minsculas
		$var_formato_o = ''; //Primera mayuscula de cada palabra 
		$var_formato_arroba = ''; //Oculta la Clausula

		if( count($dt->data) > 0 && $this->mensajeError == '' ){

			//Construimos el arreglo de variables 
			foreach ($dt->data[0] as $key => $value) {
				if ( ! is_numeric($key) ){
					
					if( $var_busqueda == ''){

						$var           = PREFIJO_VAR.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$key.VAR_VACIA.SUFIJO_VAR;
					}

					else{

						$var           = PREFIJO_VAR.$var_busqueda.'.'.$key.SUFIJO_VAR;
						$var_formato_s = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA.SUFIJO_VAR;
						$var_formato_c = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA.SUFIJO_VAR;
						$var_formato_l = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_LARGA_ST.SUFIJO_VAR;
						$var_formato_d = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_CORTA_ST.SUFIJO_VAR;
						$var_formato_n = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MAYUS.SUFIJO_VAR;
						$var_formato_m = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MINUS.SUFIJO_VAR;
						$var_formato_o = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_NUM_A_LETRAS_MIXTO.SUFIJO_VAR;
						$var_formato_arroba = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_VACIA.SUFIJO_VAR;
					}

					
					if( strlen($value) > 0 ) {
					
						if ( $this->validateDate($value,'d-m-Y')){
							$this->graba_log("fecha *** ".$value);
							//Si la fecha es Indefinido
							if( $value == VAR_FECHA_IND ){

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_arroba);

								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								
							}else{

								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_arroba);

								$fecha_s = $this->convertirFechaLarga($value);		
								$fecha_c = $this->convertirFechaCorta($value);
								
								array_push($aux,$value);
								array_push($aux,VAR_HASTA_EL.$fecha_s);
								array_push($aux,VAR_HASTA_EL.$fecha_c);
								array_push($aux,$fecha_s);
								array_push($aux,$fecha_c);
								array_push($aux,$value);
							}

						}else{
							array_push($variables, $var);
							array_push($variables, $var_formato_arroba);
							array_push($aux, $value);
							array_push($aux, $value);
						}	

						if ( is_numeric($value)){
							
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							array_push($variables,$var_formato_arroba);

							$numeros = $this->numerosALetras($value);
							
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							array_push($aux,$value);
				
						}	
					}
				}
			}
		}

		$resultado = array();
		$resultado['variables'] = $variables;
		$resultado['valores'] = $aux;

		return $resultado;
	}
	
}
?>