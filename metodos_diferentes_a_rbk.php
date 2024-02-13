private function InicioFirma_RBK()
	{
		// creamos una nueva instancia de la tabla
		$dt = new DataTable();
		$datos = $_POST;

		//Llenar Select de Empresas registradas
		$this->documentosdetBD->obtenerb64($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
				
		$nomarchtmp 	= "";
		$extension		= "";
		$nombrearchivo 	= "";
		$archivob64 	= "";

		if($dt->leerFila())
		{
			$nomarchtmp = $dt->obtenerItem("NombreArchivo");
			$extension 	= $dt->obtenerItem("Extension");
			$archivob64	= $dt->obtenerItem("documento");
		}
		
		$nombrearchivo =	$nomarchtmp.".".$extension;
			
		$subcarpeta = "./tmp/";
		if (!is_dir($subcarpeta)){
			if(!mkdir($subcarpeta, 0777, true)) {	
				$this->mensajeError.="Error al crear carpeta temporal";
			}
		}
			
		$rutayarch  = "";
		$ruta = "./tmp/";
		$rutayarch = fopen($ruta.$nombrearchivo, "wb" ); 
		fwrite($rutayarch, base64_decode($archivob64)); 
		fclose($rutayarch); 

		//Datos de persona
		$datos["personaid"] = $this->seguridad->usuarioid;

		$this->documentosdetBD->obtenerTipoFirma($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		if( count($dt->data) > 0){

			foreach ($dt->data[0] as $key => $value) {
				if( $key == 'RutUsuario')
					$datos['RutUsuario'] = $value;
				if( $key == 'Descripcion')
					$datos['TipoFirma'] = $value;
			}
		}

		switch( $datos['TipoFirma'] ){
			case 'Pin' : 
				$datos['TipoFirma'] = strtoupper($datos['TipoFirma']);
				break;
			case 'Huella' : 
				$datos['TipoFirma'] = 'FINGERPRINT';
				break;
			case 'Pin o Huella' : 
				$datos['TipoFirma'] = '';
				break;
			//Agregar los tipos de firma necesarios 
		}
		
		$formulario[0] = $datos;		
		$formulario[0]["ruta"] = $ruta.$nombrearchivo;

		$this->pagina->agregarDato("formulario",$formulario);
	
		// agregamos los datos de mensaje de error
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);
		$this->pagina->agregarDato("mensajeOK",$this->mensajeOK);

		// se imprime el template con la pagina
		$this->pagina->imprimirTemplate('templates/documentosdet_firma_rbk.html');	
	}
	

	private function TieneUnaFirma()
	{	
		//si ya tiene una firma devuelve true
		$dt = new DataTable(); //Numero de Documento
		$dt1 = new DataTable(); //Firmantes
		$datos = $_POST;

		//Obtener Datos del Documento
		$this->documentosdetBD->totalFirmantes($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Asignamos el RUT del usuario en sesion	
		$datos["personaid"] 		= $this->seguridad->usuarioid;
		$datos["RutFirmante"]	= $this->seguridad->usuarioid;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		$this->pagina->agregarDato("mensajeError",$this->mensajeError);

		//Cantidad de firmantes que no han firmado
	    $count = count($dt1->data);	

		//si el total de firmas y la cantidad de firmantes es igual, quiere decir que nadie ha firmado
		if( $count == $dt->data[0]["total"])
		{
			return false;
		}
		return true;
	}

	//Accion de completar los datos del Firmante 
	private function cargarFirmante(){

		//Recibir $datos["idContrato"], $datos["personaid"], $this->firma = tipo de firma del firmante

		$datos = $_POST;

		//Variables para subida del Documento
		$dt = new DataTable(); //Numero de Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento
	    $dt3= new DataTable(); //Orden de los firmantes
	    $dt4= new DataTable(); //Tipo de firma
		
		//Seleccionar Documento 
		$this->documentosdetBD->obtener($datos,$dt);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
		
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos,$dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;
	
		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		
		if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			$num = 0;
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {

				$estado = $dt1->data[$i]["Nombre"];
				$cargo = $dt1->data[$i]["Cargo"];
				$persona = $firmante;
				$firmado = $dt1->data[$i]["Firmado"];
				$firmante = $firmante;

				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario'){
					array_push($this->signers_roles, "Representantes");
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 ){
					array_push($this->signers_roles, $persona);
					if(strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = $firmante;// el rol del usuario que firma
						$this->datos["user_institution"] = $firmante;
						$num++;
					}
				}
				
				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario'){
					array_push($this->signers_roles, "Notarios");
					if( strtoupper($persona) == strtoupper($this->seguridad->usuarioid) && $num == 0 && $firmado == 0){
						$this->datos["user_role"] = "Notarios";// el rol del usuario que firma
						$this->datos["user_institution"] = "RUBRIKA";
						$num++;
					}
				}
				
				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
			}
		
			//Completar los datos del usuario
			$this->datos["user_rut"] = strtoupper($this->seguridad->usuarioid);//usuario de la persona que firma
			
			//Quitar el guión de los ruts 
			$rut_sin_guion = '';
			$this->quitarGuiondelRut(strtoupper($this->seguridad->usuarioid),$rut_sin_guion);
			$this->datos["user_rut_sin_guion"] = $rut_sin_guion;

			$usuarioid = strtoupper($this->seguridad->usuarioid);
			$array = array ( "personaid" => $usuarioid,"idDocumento"=>$datos["idDocumento"]);

			//Consultar el tipo de firma que tiene asociadael usuario
			$this->documentosdetBD->obtenerTipoFirma($array,$dt4);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;

			// Si tipofirma es vacio, se asume pin
			$tipofirma = "Pin"; 
			$orden = 0;
			if($dt4->leerFila())
			{
				$tipofirma = $dt4->obtenerItem("Descripcion");
				$orden = $dt4->obtenerItem("Orden");
				$this->orden = $orden;
			}

			if( $tipofirma =="Pin"){
				$this->datos["user_pin"] = $datos["pin"];//clave del usuario que firma
			}
			else{
				$this->datos["user_pin"] = "";
			}

			if( $this->band == 0 ){ //Si es la primera vez 
				$this->datos["code"] = ""; 			}
			else{
				$this->datos["code"] = $dt2->data[0]["DocCode"]; //codigo del documento base64
			}
		}else{
			$this->mensajeError.= "No se pudo obtener los datos del firmante";
		}
	}

	private function cargarDocumento(){

		//Recibir $datos["idContrato"]

		//Variables para subida del Documento
	    $dt1= new DataTable(); //Firmantes 
	    $dt2= new DataTable(); //Datos del Documento

	    $datos = $_POST;
	  
		//Buscar Firmantes
		$this->documentosdetBD->obtenerFirmantes($datos, $dt1);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Obtener Datos del Documento
		$this->documentosdetBD->obtenerDatosDocumento($datos,$dt2);
		$this->mensajeError.=$this->documentosdetBD->mensajeError;

		//Declarar arreglos necesarios
		$this->signers_ruts = array();
		$this->signers_ruts_sin_guion = array();
		$this->signers_order = array();
		$this->signers_roles = array();
		$this->signers_institutions = array();
		$this->signers_type = array();
		$this->signers_notify = array();
		$this->signers_emails = array();
		$this->signers_type_sign = array();
		
		$respuesta = "";

	    if($dt1->leerFila())
		{
			$persona = $dt1->obtenerItem("personaid");
		}

		if( $persona != "" ){
			//Asignar a los arreglos requeridos
			foreach ($dt1->data as $i => $value) {
				
				$firmante = strtoupper($dt1->data[$i]["personaid"]);

				//valida datos dec5 y crea roles
				$this->ProcesoRolFirmante($firmante);
				if ($this->mensajeRol != "")
				{
					return;
				}
				
				//Quitar el guión de los ruts 
				$rut_sin_guion = '';
				$this->quitarGuiondelRut($firmante,$rut_sin_guion);

				array_push($this->signers_ruts, $firmante);
				array_push($this->signers_ruts_sin_guion,$rut_sin_guion);
				array_push($this->signers_order, $dt1->data[$i]["Orden"]);
				array_push($this->signers_emails, "dec@rubrika.cl");
				array_push($this->signers_type, "0");
				array_push($this->signers_notify, "0");
				array_push($this->signers_type_sign, $dt1->data[$i]["TipoFirma"]);

				$estado = $dt1->data[$i]["Nombre"];
				$cargo = $dt1->data[$i]["Cargo"];
				
				//Si es Empresa
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo != 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
				//Si es Empleado
				if ( mb_substr_count($estado, "Empleado") >  0 )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}

				//Si es Notario
				if ( mb_substr_count($estado, "Representante") >  0 && $cargo == 'Notario' )
				{
					array_push($this->signers_roles, $firmante);
					array_push($this->signers_institutions, $firmante);
				}
			}

			//Completar los datos de la primera firma 
			$this->datos["file"] = $dt2->data[0]["documento"];//el archivo en base 64
			$this->datos["signers_roles"] = $this->signers_roles;
			$this->datos["signers_institutions"] = $this->signers_institutions;
			$this->datos["signers_emails"] = $this->signers_emails;
			$this->datos["signers_ruts"] = $this->signers_ruts;
			$this->datos["signers_ruts_sin_guion"] = $this->signers_ruts_sin_guion;
			$this->datos["signers_type"] = $this->signers_type;
			$this->datos["signers_order"] = $this->signers_order;
			$this->datos["signers_notify"] = $this->signers_notify;
			$this->datos["signers_type_sign"] = $this->signers_type_sign;
			$this->datos["type_doc"] = $dt2->data[$i]["NombreTipoDoc"];
		}
		else{
			$this->mensajeError.="Este documentos no tiene Firmantes";	
		}
	}

	private function quitarGuiondelRut($rut, &$rut_sin_guion){

		$resultado = explode('-',$rut);
		$rut_sin_guion = $resultado[0].$resultado[1];
		return $rut_sin_guion;
	}

	//Actualiza Documento en la BD
	private function actualizaDocumentoRBK($idDocumento, $DocCode){

		$datos = $_POST;
		$datos['idDocumento'] = $idDocumento;
		$datos['DocCode'] = $DocCode;

		if ( $datos['DocCode'] != '' ){
			//Actualizar el codigo del documento
			$this->documentosdetBD->modificar($datos);
			$this->mensajeError.=$this->documentosdetBD->mensajeError;
			
			if( $this->mensajeError != '' )
				return false;
		}

		return true;
	}

	private function firmar_rbk(){

		//Variables para subida del Documento
		$dt = new DataTable(); 

		$datos = $_POST;

		if(! $this->TieneUnaFirma() ){

			//Actualizamos
			$this->band = 0; 
				
			//Buscamos los datos necesarios
			$this->cargarDocumento();
			
			if ( $this->datos["file"] != "" ){

				$firma_rbk = new firma();
				$firma_rbk->prepararDatosDocumento($this->datos, $dt);

				if( $this->mensajeError != '' ){
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					return;
				}

				if ( $this->buscarDatosDocumentosRBK($dt) ){

					$this->cargarFirmante();
				
					//Completar los datos que faltan
					$this->datos['id'] = $datos['id'];
					$this->datos['type'] = $datos['type'];
					$this->datos['subtype'] = $datos['subtype'];
					$this->datos['authenticated'] = $datos['authenticated'];
					$this->datos['sign'] = $datos['sign'];
					$this->datos['sessionid'] = $this->session_rbk;
					
					$firma_rbk->prepararDatosparaFirmar($this->datos, $dt);

					if( $this->mensajeError != '' ){
						$this->pagina->agregarDato("mensajeError",$this->mensajeError);
						return;
					}

					//Si firmo correctamente


				}else{
					$this->pagina->agregarDato("mensajeError",$this->mensajeError);
					return;
				}
			}
		}
	}

	//Actualizar documento de RBK 
	private function buscarDatosDocumentosRBK($dt){

		$datos = $_POST;

		//Actualizar datos del documento
		$this->valor_arr = "";
		$this->obtener_dato_arr($dt,"sessionId");

		if ($this->valor_arr != "")
		{
			$session = '';
			$id = '';
			$session = $this->valor_arr;
			$id = $dt['data']['digitalSignature']['documents'][0]['id'];
			$doccode = $session.SEPARADOR_DOCCODE.$id;
			$this->session_rbk = $session;
			$this->id_rbk = $id;

			if ( $this->actualizaDocumentoRBK($datos['idDocumento'],$doccode) )
				return true;
		}
		return false;
	}

	//Buscar datos dentro de la matriz 
	private function obtener_dato_arr($matriz,$variable)
	{
	
		foreach($matriz as $key=>$value)
		{
			if (is_array($value))
			{
				$this->obtener_dato_arr($value,$variable);
			}
			else
			{  
				if ($key == $variable)
				{	
					$this->valor_arr = $value;
					break;
				}
			
			}
		}
				
	} 

	//Prepara datos para Cargar
	public function prepararDatosDocumento($datos, &$resultado){

		$documento = array();
	
		//Documento
		$documento["documentosdatos"][0]["documentobase64"]	= $datos['file'];
		$documento["documentosdatos"][0]["documentotipo"]  = $datos['type_doc'];

		//Firmantes
		foreach ($datos['signers_ruts_sin_guion'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmanteid"] = $value;
		}

		foreach ($datos['signers_roles'] as $key => $value) {//PENDIENTE ASIGNAR ROL
			$documento["firmantesdatos"][$key]["firmanterol"] = '';
		}

		foreach ($datos['signers_type_sign'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmantetipofirma"] = $value;
		}

		foreach ($datos['signers_order'] as $key => $value) {
			$documento["firmantesdatos"][$key]["firmanteorden"] = $value;
		}
		
		//Ir a Cargar Documento		
		$this->CargarDocumento($documento,$dt);
		$this->mensajeError.=$this->mensajeError;

		$resultado = $dt;
		return $resultado;
	}

		//Prepara datos para Cargar
	public function prepararDatosparaFirmar($datos, &$resultado){

		$firmante = array();
		
		$firmante["authenticated"]  = $datos["authenticated"];
		$firmante["id"] 			= $datos["id"];
		$firmante["sign"] 			= $datos["sign"];
		$firmante["subtype"] 		= $datos["subtype"];
		$firmante["type"] 			= $datos["type"];
		
		$firmante["operadorid"] 	= $datos["user_rut_sin_guion"];
		$firmante["firmanteid"] 	= $datos["user_rut_sin_guion"];
		$firmante["sessionId"] 	    = $datos["sessionid"];
		
		$this->FirmarDocumento($firmante,$dt);
		$this->mensajeError.=$this->mensajeError;	

		$resultado = $dt;
		return $resultado;
	}
	
	
	