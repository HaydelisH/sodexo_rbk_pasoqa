
	/**************************************/
	/**AJAX PROVEEDOR**/
	/**************************************/
    function ajax_buscar_proveedor(rutproveedor)
    {
       var accion 		= "BUSCAR";
       var parametros 	= "&RutProveedor=" + rutproveedor;
       resultado = llamado_ajax("rl_Proveedores_ajax.php?accion=" + accion + parametros);
       return resultado;
   }
   
       
   function ajax_buscar_proveedor_firmante(rutempresa,rutrepresentante)
   {
       var accion 		= "BUSCARFIRMANTE";
       var parametros 	= "&RutProveedor=" 				+ rutempresa;
       var parametros 	= parametros + "&RutUsuario=" 	+ rutrepresentante;
       
       resultado = llamado_ajax("rl_Proveedores_ajax.php?accion=" + accion + parametros);
       return resultado;
   }

   function ajax_graba_proveedor(rutempresa,razonsocial,direccion,comuna,ciudad)
   {
       var accion 		= "AGREGAR";
       var parametros 	= "&RutProveedor=" 					+ rutempresa;
       var parametros 	= parametros + "&NombreProveedor=" 	+ razonsocial;
       var parametros 	= parametros + "&Direccion=" 		+ direccion;
       var parametros 	= parametros + "&Comuna=" 			+ comuna;
       var parametros 	= parametros + "&Ciudad=" 			+ ciudad;
       resultado = llamado_ajax("rl_Proveedores_ajax.php?accion=" + accion + parametros);
       return resultado;
   }
   

   function ajax_graba_firmante_proveedor(nacionalidad,nombre,appaterno,apmaterno,email,rutempresa,rutrepresentante,idfirma,cargo)
   {
       var accion 		= "AGREGAFIRMANTE";
       var parametros 	= "&nacionalidad=" 				+ $("#nacionalidad_fp").val();
       var parametros 	= parametros + "&nombre=" 		+ $("#nombre_fp").val();
       var parametros 	= parametros + "&appaterno=" 	+ $("#paterno_fp").val();
       var parametros 	= parametros + "&apmaterno=" 	+ $("#materno_fp").val();
       var parametros 	= parametros + "&correo="		+ $("#email").val();
       var parametros 	= parametros + "&RutProveedor=" + $("#rutempresa_fp").val();
       var parametros 	= parametros + "&RutUsuario=" 	+ $("#rutrepresentate_fp").val();
       var parametros 	= parametros + "&idFirma=" 		+ $("#idFirma").val();
       var parametros 	= parametros + "&Cargo=" 		+ $("#cargo_fp").val();
       resultado = llamado_ajax("rl_Proveedores_ajax.php?accion=" + accion + parametros);
       return resultado
   }
   
   function ajax_asigna_rol_firma($rut,$rol)
   {
       var accion 		= "AGREGARROL";
       var parametros 	= "&rut=" 				+ $rut;
       var parametros 	= parametros + "&rol="	+ $rol;	
       resultado = llamado_ajax("rl_Proveedores_ajax.php?accion=" + accion + parametros);
       return resultado		
   }
   
   /**************************************/
   /**AJAX PROCESOS**/
   /**************************************/
    function ajax_agregar_proceso_certificado(idplantilla,rutproveedor)
    {
       var accion 		= "AGREGARCERTIFICADO";
       var parametros 	= "&idPlantilla=" 	+ idplantilla;
       var parametros 	= parametros  + "&RutProveedor=" 	+ rutproveedor;
       resultado = llamado_ajax("rl_Procesos_ajax.php?accion=" + accion + parametros);
       return resultado;
   }
   
   /**************************************/
   /**AJAX GENERA DOCUMENTOS**/
   /**************************************/
    function ajax_genera_certificado( proceso, idplantilla,rutempresa,rutproveedor,fechadocumento, idcentrocosto,firmantesemp,firmantesemporden,firmantescli,firmantescliorden,tabla,email,duenorut,duenonombre)
    {	
       var accion 		= "GENERARCERTIFICADO";
       var parametros 	= "&idPlantilla=" 					+ idplantilla;
       var parametros 	= parametros + "&idProceso=" 		+ proceso;
       var parametros 	= parametros + "&RutEmpresa=" 		+ rutempresa;
       var parametros 	= parametros + "&RutProveedor=" 	+ rutproveedor;
       var parametros 	= parametros + "&FechaDocumento=" 	+ fechadocumento;
       var parametros 	= parametros + "&idCentroCosto=" 	+ idcentrocosto;
       var parametros 	= parametros + "&firmantesemp=" 	+ firmantesemp;
       var parametros 	= parametros + "&firmantesemporden=" + firmantesemporden;
       var parametros 	= parametros + "&firmantescli=" 	 + firmantescli;
       var parametros 	= parametros + "&firmantescliorden=" + firmantescliorden;
       var parametros 	= parametros + "&tabla=" 			+ tabla;
       var parametros 	= parametros + "&email=" 			+ email;
       var parametros 	= parametros + "&duenorut=" 		+ duenorut;
       var parametros 	= parametros + "&duenonombre=" 		+ duenonombre;
   
       resultado = llamado_ajax2("rl_Generar_ajax.php?accion=" + accion + parametros);
       return resultado;
   }
   
   /**************************************/
   /**LLAMADO A AJAX Y OTRAS FUNCIONALIDADES, COMUN PARA TODOS**/
   /**************************************/	
   function crearXMLHttpRequest() 
   {
       var xmlHttp=null;
       if (window.ActiveXObject) 
           xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
       else 
       if (window.XMLHttpRequest) 
           xmlHttp = new XMLHttpRequest();
       return xmlHttp;
   }
   
   
   function llamado_ajax(urlyparametros)
   {
       conexion=crearXMLHttpRequest();
       // Preparamos la petici�n con parametros
       conexion.open('POST', urlyparametros, false);//false sincrono
       
               
       // Realizamos la petici�n
       conexion.send(null);
           
       if (conexion.status == 200) 
       {
           //OcultarCargando();
           // Devolvemos el resultado
           respuesta =  conexion.responseText;	
           
           try 
           {
               JSON.parse(respuesta);
           } 
           catch (e) 
           {
               if (respuesta != '')
               {
                   alert ("Error al realizar la accion, intente nuevamente " + respuesta);
                   return "ERROR";
               }
               else
               {
                   return "";
               }
           }
           
           var datos = JSON.parse(respuesta);

           return datos;
       }
       else
       {
           alert ("Error al realizar la accion, intente nuevamente");
           return "ERROR";
       }
                    
   }
   
   //llamado de ajax sin alert
   function llamado_ajax2(urlyparametros)
   {	
       conexion=crearXMLHttpRequest();
       // Preparamos la petici�n con parametros
       conexion.open('GET', urlyparametros, false);//false sincrono
                   
       // Realizamos la petici�n
       conexion.send(null);
           
       if (conexion.status == 200) 
       {
           //OcultarCargando();
           // Devolvemos el resultado
           respuesta =  conexion.responseText;	
           //alert ("res:" + respuesta);
           
           try 
           {
               JSON.parse(respuesta);
           } 
           catch (e) 
           {
               if (respuesta != '')
               {
                   return "ERROR";
               }
               else
               {
                   return "";
               }
           }

           var datos = JSON.parse(respuesta);

           return datos;
       }
       else
       {
           //ALERT ("ERROR " + conexion.status);
           return "ERROR";
       }
                    
   }

   function decode_utf8(s) {
     return decodeURIComponent(escape(s));
   }