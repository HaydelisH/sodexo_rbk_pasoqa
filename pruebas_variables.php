<?php

//Construir Plantilla
	private function construirPlantilla($idDocumento, &$resultado){
		
		//Variables a utilizar 
	   	$dt = new DataTable();
	   	$html = '';
		
		$datos['idDocumento'] = $idDocumento;
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';

		//Buscar la Plantilla del documento
		$this->documentosBD->obtener($datos,$dt);
		$this->mensajeError .= $this->documentosBD->mensajeError;

		if( $dt->leerFila() ){
			$idPlantilla = $dt->obtenerItem('idPlantilla');
		}

		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		if( $idPlantilla > 0 ){

			$datos['idPlantilla'] = $idPlantilla;

			$this->documentosBD->obtenerClausulasPlantillas($datos,$dt);
			$this->mensajeError.=$this->documentosBD->mensajeError;

			//Agregamos etiquetas 
			$tags = '<html>';
			$html = $tags;

	        //Agregamos el Estilo a la Plantilla 
	        $style = '';
	        $style = '<style>@page {'.$papel.'} @media print {'.$papel.'}';
	        $style .= ESTILO_PDF.'</style>';
	        $html .= $style;

	        //Agregamos el encabezado 
	        $encabezado = '';
	        $encabezado = '<body><div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';
	        $html .= $encabezado;

	        //Variables
	        $num = 1;
	        $contenido = '';
	     
	        //Construir Plantilla con las Clausulas
	        if( count($dt->data) > 0){

	        	foreach ($dt->data as $i => $value) {
        		
					$clausula = '';
					$aux = '';
					
					//Si estan el titulo y encabezado activos 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 1){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
						ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
						$clausula = "<p><strong><u>".$resultado."</u></strong> :<strong>".$dt->data[$i]["Descripcion_Cl"].":</strong> ";
																	
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32 ) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
					}
				
					//Si estan el titulo y encabezado inactivos 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 0){
						$aux = $dt->data[$i]["Texto"];
						$clausula = $aux; 
					}
				
					//Si esta el encabezado activo y el titulo no 
					if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
						
						$this->ordinal[$num] = $dt->data [$i]["idClausula"];
						ContenedorUtilidades::numerosOrdinales($num,$resultado);
						
						$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						
						if ( $aux != '' ){
							$clausula .= $aux;	        		
							$num++;
						}
					}
				
					//Si el titulo esta activo y el encabezado no 
					if( $dt->data[$i]["Encabezado"] == 0 && $dt->data[$i]["Titulo"] == 1){
										
						$clausula = "<p><strong><u>".$dt->data[$i]["Descripcion_Cl"]."</u></strong>: ";
						
						if( substr($dt->data[$i]["Texto"], 0 ,30 ) == '<p style="text-align:justify">'){
							$aux = substr($dt->data[$i]["Texto"],30);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,32) == '<p style="text-align: justify;">'){
							$aux = substr($dt->data[$i]["Texto"],32);
						}
						if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
							$aux = substr($dt->data[$i]["Texto"],3);
						}
						if ( $aux != '' ){
							$clausula .= $aux;	        		
						}
					}

					//Si encuentra la variable vacia 
					if ( strstr($dt->data[$i]["Texto"],VAR_VACIA) ){
						$clausula = '';
					}
					
					//Agregar clausulas
					$contenido .= $clausula;
				}
	        
				//Limpiar el HTML
				$aux = '';
		        $aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><img>');
				$html .= $aux;
		        $html .= "</div>";

		        $resultado = $html;
		        return $resultado;
			}
			else{
				return false;
			}
        }
        else{
        	return false;
        }
	}
	
	//Validar si el documento tiene variables de un tipo 
	private function buscarVariables($idDocumento,$html,$busqueda,$datos2,&$resultado){

		$datos = $_POST;
		$datos['idDocumento'] = $idDocumento;

		$dt = new DataTable();
		$var_busqueda = '';
		$resultado = '';

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
			
				$datos["RutUsuario"] = $datos2['Firmantes_Emp'][0];
				if( $datos["RutUsuario"] != '' ){
					$datos["RutUsuario"] = $datos2['Firmantes_Emp'][0];
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
		$var_formato_ci = ''; //Clausula Indefinida
		$var_formato_cp = ''; //Clausula a Plazo fija 
		$var_formato_indefinida = ''; //Indefinida
		$var_formato_hasta_el = ''; //Hasta el
		$var_formato_hasta_el_l = ''; // Hasta el (fecha larga)
		$var_formato_hasta_el_c = ''; //Hasta el (fecha corta)

		if( count($dt->data) > 0){
			
			// FechaDinamica
			$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema();

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
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_ci = PREFIJO_VAR.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
						$var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_hasta_el	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL.SUFIJO_VAR;
						$var_formato_hasta_el_l	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL_LARGA.SUFIJO_VAR;
						$var_formato_hasta_el_c	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL_CORTA.SUFIJO_VAR;
						
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
						$var_formato_ci = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
						$var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_hasta_el	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL.SUFIJO_VAR;
						$var_formato_hasta_el_l	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL_LARGA.SUFIJO_VAR;
						$var_formato_hasta_el_c	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL_CORTA.SUFIJO_VAR;
					}

					if( strlen($value) > 0 ) {
					
						if ( $this->validateDate($value,'d-m-Y')){

							//Si la fecha es Indefinido
							if( $value == VAR_FECHA_IND ){
								
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_indefinida);
								
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDO);
								array_push($aux,VAR_INDEFINIDA);
								
							}else{
								array_push($variables,$var);
								array_push($variables,$var_formato_s);
								array_push($variables,$var_formato_c);
								array_push($variables,$var_formato_l);
								array_push($variables,$var_formato_d);
								array_push($variables,$var_formato_indefinida);
								
								$fecha_s = $this->convertirFechaLarga($value);		
								$fecha_c = $this->convertirFechaCorta($value);
								
								array_push($aux,$value);
								array_push($aux,VAR_HASTA_EL.$fecha_s);
								array_push($aux,VAR_HASTA_EL.$fecha_c);
								array_push($aux,$fecha_s);
								array_push($aux,$fecha_c);
								array_push($aux,VAR_HASTA.$fecha_s);
							}
	
						}else{
							array_push($variables, $var);
						    array_push($aux, $value);
						}	

						if ( is_numeric($value)){
							
							array_push($variables,$var_formato_n);
							array_push($variables,$var_formato_m);
							array_push($variables,$var_formato_o);
							array_push($variables,$var_formato_arroba);
							array_push($variables,$var_formato_sm);//Separador de miles
							
							$numeros = $this->numerosALetras($value);
							
							array_push($aux,$numeros);
							array_push($aux,strtolower($numeros));
							array_push($aux,ucwords(strtolower(($numeros))));
							array_push($aux,$value);
							array_push($aux,number_format($value,0,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES));//Separador de miles
						}	
					}
				}
			}
		

			$html_resultado = '';
			
			//$this->graba_log("/// VARIBLES  ///");
			
			//Buscamos si existe conincidencia
			foreach ($variables as $key => $value) {
				if ( strstr($html,$value) ){
					//Sustituir en el HTML
					$html = str_replace($variables,$aux,$html);
				}
				$this->graba_log($value." : ".$aux[$key]);
			}

			if( strstr($html,VAR_LOGO)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];

				$logo = $rut_empresa.'.'.VAR_EXTENSION;
				$html = str_replace(VAR_LOGO,$logo,$html);
				
				//$this->graba_log(VAR_LOGO." : ".$logo);
			}

			if( strstr($html,VAR_RUTA)){

				$this->documentosBD->obtenerVariablesEmpresa($datos,$dt);
				$this->mensajeError = $this->documentosBD->mensajeError;
				$rut_empresa = $dt->data[0]["RutEmpresa"];
				
				$ruta = VAR_RUTA_COMPLETA;
				$html = str_replace(VAR_RUTA,$ruta,$html);
				
				//$this->graba_log(VAR_RUTA." : ".$ruta);
			}	
		}
		
		$resultado = $html;
		return $resultado;
	}
	
	//Construir variables y valores disponibles, devuelve un array con dos arrelos, una de variables y otra de los valores 
	private function construirVariablesValores($datos,&$resultado){

		$resultado = array();
		$valores = array();

		$tablas = array (VAR_DOCUMENTO,VAR_EMPRESAS,VAR_EMPLEADOS,VAR_ARCHIVO,VAR_REPRESENTANTE, VAR_REPRESENTANTE_2);

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
	
	//Validar si el documento tiene variables de un tipo 
	private function buscarVariablesValores($datos,$busqueda,&$resultado){

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
		$var_formato_ci = ''; //Clausula Indefinida
		$var_formato_cp = ''; //Clausula a Plazo fija 
		$var_formato_indefinida = ''; //Indefinida
		$var_formato_hasta_el = ''; //Hasta el
		$var_formato_hasta_el_l = ''; // Hasta el (fecha larga)
		$var_formato_hasta_el_c = ''; //Hasta el (fecha corta)

		if( count($dt->data) > 0){
			
			// FechaDinamica
			$dt->data[0][VAR_DINAMICA] = ContenedorUtilidades::calculoFechaDinamicaSistema();

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
						$var_formato_sm = PREFIJO_VAR.$key.VAR_NUM_SEPARADOR_MILES.SUFIJO_VAR;//Separador de miles
						$var_formato_ci = PREFIJO_VAR.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
						$var_formato_indefinida = PREFIJO_VAR.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_hasta_el	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL.SUFIJO_VAR;
						$var_formato_hasta_el_l	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL_LARGA.SUFIJO_VAR;
						$var_formato_hasta_el_c	= PREFIJO_VAR.$key.VAR_FECHA_HASTA_EL_CORTA.SUFIJO_VAR;
						
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
						$var_formato_ci = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_cp = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_CLAUSULA_PLAZO.SUFIJO_VAR;
						$var_formato_indefinida = PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_INDEFINIDA.SUFIJO_VAR;
						$var_formato_hasta_el	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL.SUFIJO_VAR;
						$var_formato_hasta_el_l	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL_LARGA.SUFIJO_VAR;
						$var_formato_hasta_el_c	= PREFIJO_VAR.$var_busqueda.'.'.$key.VAR_FECHA_HASTA_EL_CORTA.SUFIJO_VAR;
					}

										
					if ( $this->validateDate($value,'d-m-Y')){

						//Si la fecha es Indefinido
						if( $value == VAR_FECHA_IND ){
							
							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_indefinida);
							
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_INDEFINIDO);
							array_push($aux,VAR_FECHA_INDEFINIDA);
							
						}else{
							array_push($variables,$var);
							array_push($variables,$var_formato_s);
							array_push($variables,$var_formato_c);
							array_push($variables,$var_formato_l);
							array_push($variables,$var_formato_d);
							array_push($variables,$var_formato_hasta_el);
							array_push($variables,$var_formato_hasta_el_l);
							array_push($variables,$var_formato_hasta_el_c);
							
							$fecha_s = $this->convertirFechaLarga($value);		
							$fecha_c = $this->convertirFechaCorta($value);
							
							array_push($aux,$value);
							array_push($aux,VAR_HASTA_EL.$fecha_s);
							array_push($aux,VAR_HASTA_EL.$fecha_c);
							array_push($aux,$fecha_s);
							array_push($aux,$fecha_c);
							array_push($aux,VAR_FECHA_HASTA_EL);
							array_push($aux,VAR_FECHA_HASTA_EL.$fecha_s);
							array_push($aux,VAR_FECHA_HASTA_EL.$fecha_c);
						}

					}else{
						array_push($variables, $var);
						array_push($aux, $value);
					}	

					if ( is_numeric($value)){
						
						array_push($variables,$var_formato_n);
						array_push($variables,$var_formato_m);
						array_push($variables,$var_formato_o);
						array_push($variables,$var_formato_arroba);
						array_push($variables,$var_formato_sm);//Separador de miles
						
						$numeros = $this->numerosALetras($value);
						
						array_push($aux,$numeros);
						array_push($aux,strtolower($numeros));
						array_push($aux,ucwords(strtolower(($numeros))));
						array_push($aux,$value);
						array_push($aux,number_format($value,0,VAR_SIGNO_DECIMAL,VAR_SIGNO_MILES));//Separador de miles
					}	
					
				}
			}
		}

		$resultado = array();
		$resultado['variables'] = $variables;
		$resultado['valores'] = $aux;

		return $resultado;
	}
	
	//Buscar las variables y valores de subclausulas
	private function buscarVariablesValoresSubClausulas($datos,&$resultado){
	
		$dt = new DataTable();
		$dt_doc = new DataTable();
		$var_busqueda = '';

		//Buscar subclausulas 
		$this->ContratosDatosVariablesBD->obtener($datos,$dt);
		$this->mensajeError .= $this->ContratosDatosVariablesBD->mensajeError;

		if( $dt->leerFila() ){
			$jornada = $dt->obtenerItem('Jornada');
			$cargo = $dt->obtenerItem('Cargo');
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
					
		foreach($array_subclausulas as $key => $value){
	
			$this->subclausulasBD->obtener($array_subclausulas[$key],$dt);
			$this->mensajeError = $this->subclausulaBD->mensajeError;
		
			$tipo = '';
			$tipo = $dt->data[0]['TipoSubClausula'];

			if( count($dt->data) > 0 ){

				$variables = array();
				$aux = array();
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
		
		$resultado = array();
		$resultado['variables'] = $variables; 
		$resultado['valores'] = $aux;
		return $resultado;
	}
	
	private function arregloMultiASimple($array, &$resultado){
		
		$resultado = array();
		
		foreach( $array as $key => $value ){
			foreach ( $value as $key_1 => $value_1 ){
				array_push($resultado,$value_1);
			}
		}
		return $resultado;
	}
		
		
	//Si encuentra la variable vacia 
	if ( strstr($dt->data[$i]["Texto"],VAR_VACIA) ){
		$clausula = '';
	}
	
	$var = new variables(); 
	$variables_resultado = array();
	$array_valores = array();
	$array_variables = array();
	$resultado_html = '';
	
	$var->construirVariablesValoresTodas($datos,$variables_resultado);
	$var->arregloMultiASimple($variables_resultado['variables'], $array_variables);
	$var->arregloMultiASimple($variables_resultado['valores'], $array_valores);
				
	if( $var->buscarVariablesVacias($clausula,$array_variables,$array_valores)){
		$clausula = '';
		$num--;
	}else{
		$var->sustituirVariables($clausula,$array_variables,$array_valores,$datos,$resultado_html);
		$clausula = $resultado_html;
	}
	
	/*INICIO DE @0*/
	
	$array_valores = array();
	$array_variables = array();
	$resultado_html = '';
	
	$this->construirVariablesValores($datos,$variables_resultado);
	$this->arregloMultiASimple($variables_resultado['variables'], $array_variables);
	$this->arregloMultiASimple($variables_resultado['valores'], $array_valores);
				
	if( $var->buscarVariablesVacias($clausula,$array_variables,$array_valores)){
		$clausula = '';
		$num--;
	}
	
	/*FIN DE @0*/
	
	
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
						
?>