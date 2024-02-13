//Construir Plantilla
	private function construirPlantilla(&$resultado){
		
		$papel_h = "28cm 21.59cm;";
		$papel_v = "21.59cm 28cm;";
		$papel = '';
		
		if( $this->orientacion == 'portrait'){
			$papel = $papel_v;
		}else{
			$papel = $papel_h;
		}

		// Obtenemos los datos de las Clausulas relacionados
		$this->documentosBD->obtenerClausulasPlantillas($_POST,$dt);
		$this->mensajeError.=$this->documentosBD->mensajeError;

        //Agregamos Titulo de la Plantilla
		//size: 21.59cm 28cm;
        $html = '<style>@page {'.$papel.' margin-top:3cm; margin-left:2.5cm; margin-right:2.5cm; margin-bottom:1cm; } 
@media print {'.$papel.'margin-top:3cm; margin-left:2.5cm; margin-right:2.5cm; margin-bottom:1cm; } 
p { orphans: 3; widows: 1}</style>
        <div align="center" style="font-size: 16px;color: black;">'.$dt->data[0]["Titulo_Pl"].'</div><div align="justify">';

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
					
					if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
						$aux = substr($dt->data[$i]["Texto"],3);
					}
					$this->GrabaLog("EJEM_1:".$dt->data[$i]["Texto"]);
					$this->GrabaLog("EJEM_1:".substr($dt->data[$i]["Texto"], 0 ,3));
					$this->GrabaLog("EJEM_1:".substr($dt->data[$i]["Texto"], 0 ,30));
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
					if( substr($dt->data[$i]["Texto"], 0 ,3 ) == '<p>'){
						$aux = substr($dt->data[$i]["Texto"],3);
					}
					if ( $aux != '' ){
						$clausula .= $aux;	        		
					}
				}
				
				//Agregar clausulas
				$contenido .= $clausula;
			}
	        
			//Limpiar el HTML
			$aux = '';
	        $aux = strip_tags($contenido,'<ul><li><p><ol><strong><table><tr><td><th><tbody><tfoot><col><colgroup><h1><h2><h3>');
							
			$html .= $aux;
	        $html .= "</div>";
        }
        else{
        	return false;
        }

    	//Reasignar HTML a un atributo de la clase
	    $resultado = $html;
	    return $resultado;
	    //FIN
	}