<html><body><style>@page {21.59cm 28cm; margin-top:3cm; margin-left:2.5cm; margin-right:2.5cm; margin-bottom:1cm; } 
@media print {21.59cm 28cm;margin-top:3cm; margin-left:2.5cm; margin-right:2.5cm; margin-bottom:1cm; } 
p { orphans: 3; widows: 1}</style>
        <div align="center" style="font-size: 16px;color: black;"><p style="text-align:center"><strong><u>CONTRATO DE TRABAJO</u></strong></p>
</div><div align="justify"><p><strong>Encabezado contrato</strong>: Lugar y Fecha del Contrato: <strong>[$DATOS.Ciudad$]</strong>, <strong>[$Fecha/S$]</strong>.</p>

<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:40%"><strong>EMPLEADOR</strong></td>
			<td style="width:60%"><strong>:[$EMPRESA.RazonSocial$]</strong></td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>RUT </strong>[$EMPRESA.Rut$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Domicilio </strong>[$EMPRESA.Domicilio$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Representanda por Don </strong>[$REPRESENTANTE.Nombre$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>RUT </strong>[$REPRESENTANTE.Rut$]</td>
		</tr>
		<tr>
			<td style="width:40%"><strong>TRABAJADOR</strong></td>
			<td style="width:60%"><strong>:[$EMPLEADO.Nombre$]</strong></td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>RUT</strong> [$EMPLEADO.Rut$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Fecha de Nacimiento</strong> [$EMPLEADO.FechaNacimiento/S$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Estado Civil</strong> [$EMPLEADO.EstadoCivil$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Nacionalidad</strong> [$EMPLEADO.Nacionalidad$]</td>
		</tr>
		<tr>
			<td style="width:40%">&nbsp;</td>
			<td style="width:60%"><strong>Domicilio</strong> [$EMPLEADO.Direccion$]</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
<p><strong>PRIMERO</strong>: El Trabajador se compromete a efectuar la labor de <strong>[$DATOS.Cargo$]</strong>, y toda otra funci&oacute;n inherente a su profesi&oacute;n u oficio a bordo de las naves en que el Empleador le encomiende prestar servicios, sean estas propias o de terceros, cualesquiera sean los puertos o zonas geogr&aacute;ficas de operaci&oacute;n, de conformidad a lo pactado entre ambos, de acuerdo a la respectiva descripci&oacute;n de cargo y lo referido en el Contrato de Embarco, seg&uacute;n los Arts. 96 a 132, ambos inclusive, contenidos en el P&aacute;rrafo 1&deg; referido al Contrato de Embarco, del Cap&iacute;tulo III del T&iacute;tulo II del Libro I del C&oacute;digo del Trabajo, textos&nbsp; de cuyo contenido en ning&uacute;n caso el trabajador podr&aacute; alegar desconocimiento.-</p>
<p><strong>SEGUNDO</strong>: El Trabajador se compromete a cumplir las disposiciones legales y acatar los Reglamentos y Normas de procedimiento que existan en la Empresa para el trabajo a bordo de las naves o artefactos navales, y con la&nbsp; Reglamentaci&oacute;n de la Direcci&oacute;n del Territorio Mar&iacute;timo y Marina Mercante, debiendo mantener vigente su libreta de embarco y matr&iacute;cula,&nbsp; as&iacute; como en especial cumplir con&nbsp; las &oacute;rdenes e instrucciones del Capit&aacute;n, sus Oficiales y Delegados del Armador.-</p>
<p><strong>TERCERO</strong>: Las partes hacen presente que el Trabajador se regir&aacute; seg&uacute;n el Art. 38 N&ordm; 5, del C&oacute;digo del Trabajo, y seg&uacute;n resoluci&oacute;n excepcional N&deg; 730.-</p>
<p><strong>CUARTO</strong>: Atendida&nbsp; la naturaleza del contrato y de las funciones que desempe&ntilde;ar&aacute; el Trabajador, este se encuentra excluido de la limitaci&oacute;n de la jornada horaria, conforme a lo prevenido en el Art. 22 y 108 del C&oacute;digo del Trabajo, lo que es reconocido y aceptado en forma expresa por el Trabajador.</p>

<p style="text-align:justify">A mayor abundamiento y como consecuencia de lo anterior, las partes dejan expresamente establecido dada las caracter&iacute;sticas de las funciones, que el Trabajador no tendr&aacute; derecho en caso alguno, a impetrar o percibir pagos por concepto de horas extraordinarias </p>

<p style="text-align:justify">El Trabajador velar&aacute; por que los servicios contratados a la Empresa sean prestados, con la debida diligencia y respeto hacia los clientes, asimismo se compromete a cooperar con los clientes en todo momento.</p>

<p style="text-align:justify">&nbsp;</p>

<p style="text-align: justify;">El Trabajador recibir&aacute; por sus servicios las siguientes rentas brutas las que se pagaran por mensualidades vencidas:</p>
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
		$this->documentosBD->obtenerClausulasPlantillas($_REQUEST,$dt);
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
					$clausula .= $dt->data[$i]["Texto"];
				}
				
				//Si esta el encabezado activo y el titulo no 
				if( $dt->data[$i]["Encabezado"] == 1 && $dt->data[$i]["Titulo"] == 0){
					
					$this->ordinal[$num] = $dt->data [$i]["idClausula"];
	        		ContenedorUtilidades::numerosOrdinales($num,$resultado);
					
	        		$clausula = "<p><strong><u>".$resultado."</u></strong>: ";
					$this->GrabaLog("EJ:".$dt->data[$i]["Texto"]);
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
<p style="text-align:justify">El Trabajador no podr&aacute; aceptar donaciones de ning&uacute;n tipo de parte de alg&uacute;n cliente o empleado de estos.</p>
<p><strong>QUINTO</strong>: <table align="center" border="0" style="width:100%">
	<tbody>
		<tr>
			<td style="width:50%"><strong>SUELDO BASE:</strong></td>
			<td style="width:50%"><strong>$ [$DATOS.SueldoBase$].-</strong></td>
		</tr>
	</tbody>
</table>
<table align="center" border="0" style="width:100%">
	<tbody>
		<tr>
			<td style="width:50%"><strong>GRATIFICACI&Oacute;N:</strong></td>
			<td style="width:50%"><strong>$ [$DATOS.Gratificacion$].-</strong></td>
		</tr>
	</tbody>
</table>
<table align="center" border="0" style="width:100%">
	<tbody>
		<tr>
			<td style="width:50%"><strong>MOVILIZACI&Oacute;N:</strong></td>
			<td style="width:50%"><strong>$ [$DATOS.Movilizacion$].-</strong></td>
		</tr>
	</tbody>
</table>
<p>As&iacute; mismo, se deja constancia que la Empresa podr&aacute; otorgar&aacute; anticipos mensuales a cuenta gratificaci&oacute;n, en base&nbsp; 25 % del sueldo convenido con un tope de 4, 75 ingresos m&iacute;nimos, y en el evento que la Empresa obtenga utilidades l&iacute;quidas, todos los pagos que haya realizado la Empresa por concepto de gratificaci&oacute;n se entender&aacute;n&nbsp; efectuados con cargo a gratificaciones legales, conviniendo las partes que el Empleador ha ejercido anticipadamente el derecho de opci&oacute;n que establece el Art. 50 del C&oacute;digo del Trabajo, quedando de esta forma cumplida la obligaci&oacute;n de gratificar.</p>
<p><strong>QUINTO</strong>: Asimismo se establece un Vi&aacute;tico para alimentaci&oacute;n de $ <strong>[$DATOS.Viatico$]</strong> por cada d&iacute;a embarcado, para el trabajador que se desempe&ntilde;e en el Canal de Chacao a bordo&nbsp; de los denominados transbordadores, el cual solo tendr&aacute; derecho mientras la Empresa no entregue la alimentaci&oacute;n a bordo de la embarcaci&oacute;n.</p>

<p style="text-align:justify">&nbsp;</p>

<p style="text-align:justify">&nbsp;Adicionalmente y para los Trabajadores que re&uacute;nan las condiciones se&ntilde;aladas en el p&aacute;rrafo anterior, se cancelar&aacute; una Asignaci&oacute;n de Movilizaci&oacute;n por cada vez que inicien y terminen su guardia de acuerdo al rol de guardias establecido por la Empresa, equivalente a $ <strong>[DATOS.Movilizacion$]</strong>para Canal de Chacao.-</p>

<p style="text-align:justify">&nbsp;</p>

<p style="text-align:justify">En caso que el Vi&aacute;tico de Alimentaci&oacute;n y la Asignaci&oacute;n de Movilizaci&oacute;n, no tengan modificaci&oacute;n al monto en un per&iacute;odo semestral o que este sea inferior al IPC registrado, se establece un reajuste de manera de igualar la variaci&oacute;n que haya experimentado el IPC, en el semestre respectivo.</p>
<p><strong>SEXTO</strong>: Cualquiera prestaci&oacute;n que la Empresa conceda a el Trabajador, fuera de las que correspondan de acuerdo con este Contrato, sus modificaciones o anexos expresamente estipulados por escrito o disposiciones legales en vigor, se entender&aacute; conferida a t&iacute;tulo de mera liberalidad y no dar&aacute; derecho alguno al &quot;Trabajador&quot;, por lo que la Empresa&nbsp; podr&aacute; suspenderla o modificarla a su entero arbitrio.</p>
<p><strong>SEPTIMO</strong>: El Trabajador, se obliga a cumplir con diligente desempe&ntilde;o y dedicaci&oacute;n exclusiva las funciones que le corresponden y no podr&aacute; realizar funci&oacute;n o negociaci&oacute;n alguna para s&iacute; o para&nbsp; terceros, relacionada con el giro de su Empleador, ni usar en beneficio propio o de terceros, las oportunidades comerciales de las que tuviere conocimiento en raz&oacute;n de su cargo o labores. Adem&aacute;s ser&aacute; obligaci&oacute;n del Trabajador el oportuno cumplimiento de la normativa de la Autoridad Mar&iacute;tima en relaci&oacute;n a la aprobaci&oacute;n de los cursos exigidos como requisitos esenciales para embarcarse en el cargo contratado.</p>
<p><strong>OCTAVO</strong>: El presente Contrato tendr&aacute; una duraci&oacute;n <strong>[$DATOS.FechaTermino/S$]</strong>, sin perjuicio que podr&aacute; pon&eacute;rsele t&eacute;rmino por cualquiera de las partes, cuando concurran causas justificadas de conformidad al C&oacute;digo del Trabajo.</p>
<p><strong>NOVENO</strong>: Se deja constancia que el Trabajador Don <strong>[$EMPLEADO.Nombre$]</strong>, ingres&oacute; al servicio del Empleador, con fecha <strong>[DATOS.FechaIngreso$]</strong>, siendo &eacute;sta la &uacute;nica relaci&oacute;n laboral vigente.</p>
<p><strong>DECIMO</strong>: El Empleador retendr&aacute; de las remuneraciones que pague al Trabajador los porcentajes correspondientes a cotizaciones previsionales, impuestos y dem&aacute;s descuentos legales y contractuales que procedieren. Sin perjuicio de la facultad del Empleador de efectuar el pago en moneda de curso legal, el Trabajador autoriza expresamente que su remuneraci&oacute;n pueda ser pagada mediante el sistema bancario.</p>
<p><strong>DECIMO PRIMERO</strong>: El Empleador se obliga a suministrar al Trabajador la ropa de trabajo y los elementos de seguridad necesarios y compatibles con las labores a desempe&ntilde;ar, que se determinen por el Empleador, sin cobro o recargo para el Trabajador. Este deber&aacute; mantenerlas y devolverlas al t&eacute;rmino de su Contrato sin perjuicio del uso o desgaste leg&iacute;timo.</p>

<p style="text-align:justify">&nbsp;</p>

<p style="text-align:justify">Asimismo, el Trabajador acepta el descuento de sus remuneraciones de los materiales o elementos de seguridad que le hubiesen sido entregados, cuando concurra para ello el desgaste de los elementos sin ocasi&oacute;n del trabajo, o ya sea por hurto o extrav&iacute;o.</p>
<p><strong>DECIMO SEGUNDO</strong>: El presente Contrato se extiende en dos ejemplares del mismo tenor, dejando expreso testimonio el Trabajador que recibi&oacute; un ejemplar del mismo, firmado por ambas partes y un ejemplar del Reglamento Interno de Orden, Higiene y Seguridad de la Empresa, el cual forma parte integrante de este Contrato. </p>

<p style="text-align:justify">&nbsp;</p>

<p style="text-align:justify">Para todos los efectos legales, del presente Contrato, las partes fijan domicilio especial convencional, en la ciudad de <strong>[$DATOS.Ciuadad$].</strong></p>
</div></body></html>