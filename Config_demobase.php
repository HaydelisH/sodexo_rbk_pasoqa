<?php
 	//====================================================//
	// Nombre de archivo: Config.php 				      //
	// Fecha de creación: 11-04-2019					  //
	// Descripcion: Configuracion generales para el       //
	// proyecto de Rubrika 3.0							  //
	//====================================================//
	
	//***BASE DE DATOS***//
	
		//Direccion del servidor 
		define ('SERVIDOR', 'SQL2');
		//Nombre de la Base de Datos
		define ('BD', 'Rbk_Essity');
		//Usuario de la Base de Datos
		define ('USUARIO', 'sa');
		//Clave de Acceso de la Base de Datos
		define ('CLAVE', 'Gsur_78+');
	
	//***VARIABLES***//

		//Prefijo de variables
		define ('PREFIJO_VAR', '[$');
		//Sufijo de variables 
		define ('SUFIJO_VAR', '$]');
		//Prefijo de variables de Empresa
		define ('VAR_EMPRESAS', 'EMPRESA');
		//Prefijo de variables de Empleado 
		define('VAR_EMPLEADOS', 'EMPLEADO');
		//Prefijo de variables del Representante
		define('VAR_REPRESENTANTE', 'REPRESENTANTE');
		define('VAR_REPRESENTANTE_2', 'REPRESENTANTE2');
		//Prefijo de variables del Documento 
		define('VAR_DOCUMENTO', '');
		//Prefijo de variables del archivo de carga
		define('VAR_ARCHIVO', 'DATOS');
		//Prefijo de variables de SubClausulas 
		define('VAR_SUBCLAUSULAS','DATOS');
		//Caracter separador de SubClausulas
		define('SEPARADOR','_');
		//Formato de fecha larga:  Hasta el 01 de Enero de 2019
		define('VAR_FECHA_LARGA', '/L');
		//Formato de fecha corta: Enero de 2019
		define('VAR_FECHA_CORTA', '/D');
		//Formato de fecha larga sin termino
		define('VAR_FECHA_LARGA_ST', '/S');
		//Formato de fecha corta sin termino
		define('VAR_FECHA_CORTA_ST', '/C');
		//Formato de fecha a sustituir Indefinido
		define('VAR_FECHA_INDEFINIDA', '/I');
		//Formato de fecha a sustituir Hasta el
		//define('VAR_FECHA_HASTA_EL', '/H');
		//define('VAR_FECHA_HASTA_EL_LARGA', '/HL');
		//define('VAR_FECHA_HASTA_EL_CORTA', '/HC');
							
		//Formato de Numeros a palabras 
		define('VAR_NUM_A_LETRAS_MAYUS','/N');
		define('VAR_NUM_A_LETRAS_MINUS','/M');
		define('VAR_NUM_A_LETRAS_MIXTO','/O');
		define('VAR_NUM_SEPARADOR_MILES','/SM');//Separador de miles		
		define('VAR_SIGNO_MILES','.');//Signo de separacion de miles
		define('VAR_SIGNO_DECIMAL',',');//Signo de separación de decimales
		//define('VAR_CLAUSULA_INDEFINIDA','/CI');//Clausula indefinido
		//define('VAR_CLAUSULA_PLAZO','/CP');//Clausulas de plazo fijo
		
		//Texto sustituto de fecha indefinida
		define('VAR_INDEFINIDO', ' es de duraci&oacute;n indefinida y podr&aacute; terminar por cualquiera de las causales establecidas en la legislaci&oacute;n vigente. ');
		//Descripcion de plazo de contrato
		define('VAR_HASTA_EL', ' tendr&aacute; una duraci&oacute;n desde fecha de celebraci&oacute;n del mismo, hasta el  ');
		//Sustituto de fecha indefinida
		define('VAR_INDEFINIDA_NUEVA', ' Indefinida ');
		//Sustitucion de plazo de contrato
		define('VAR_HASTA', ' Hasta el ');
		//Separador de fecha
		define('SEPARADOR_FECHA','-');
		//Variable de fecha indefinida
		define('VAR_FECHA_IND' , '01-01-3000');
		//Formato de fecha
		define('VAR_FORMATO_FECHA' , 'd-m-Y');
		//Variable vacia
		define('VAR_VACIA','@0');
		//Variable de logo, que sustituye el rut de la empresa 
		define('VAR_LOGO', '@@Logo@@');
		//Variable de ruta completa, para buscar una imagen 
		define('VAR_RUTA', '@@Ruta@@');
		//Ruta completa de ubicacion de imagenes 
		//define('VAR_RUTA_COMPLETA', dirname(__FILE__).'/images/');
		//define('VAR_RUTA_COMPLETA','./images/');
		define('VAR_RUTA_COMPLETA','./imagenes/');
		//Extension de archivo de imagen
		define('VAR_EXTENSION', 'png');
		//Separador de subclausulas
		define('SEPARADOR_SUBCLAUSULAS','_');
		
	//***GESTOR DE FIRMA***//
		//Gestor de firma
		define('GESTOR_FIRMA','RBK'); // DEC5, RBK
		//Tipo de firma por defecto de RBK
		define('TIPO_FIRMA_PORDEFECTO_RBK', 'Huella'); //RBK
		//Tipo de firma por defecto de DEC
		define('TIPO_FIRMA_PORDEFECTO_DEC5', 'Pin'); //DEC5 
		//Otros DEC5 
		define("DEC5_INSTITUCION" , 'Rubrika');
	
	//***GENERACION DE DOCUMENTOS***//
		//Carpeta de almacenamiento  de Documentos
		define('CARPETA' , 'tmp');
		//Separador para concatenar el codigo de documento de RBK
		define('SEPARADOR_DOCCODE','|');
		//Nombre de Documento generado
		define('NOMBRE_DOC', 'Documento');
		//Nombre del Plantilla a Previsualizar
		define('NOMBRE_PLA', 'Plantilla_');
		//Tamaño de hoja de PDF
		define('TAMANO_HOJA', 'Letter');
		//Tipo de generacion por defecto 
		define('TIPO_GENERACION', 4 );
		//Estilo para PDF 
		define('ESTILO_PDF',
		 '@page { margin-top:1cm; margin-left:3cm; margin-right:3cm; margin-bottom:1cm; }
		  @media print { margin-top:3cm; margin-left:3cm; margin-right:3cm; margin-bottom:1cm; } 
		  p { orphans: 3; widows: 1}');
		//Tipo de firma por defecto 
		define('TIPO_FIRMA_DOC',2); //1 Manual y 2 Electronica
	
	//***VARIABLES DEL ARCHIVO DE CARGA***//
		//Jornada 
		define('VAR_JORNADA', 17);
		//Centro de Costo
		define('VAR_CENTROCOSTO', 13);
		//Lugar de pago 
		define('VAR_LUGARPAGO', 0);
		//Cargo (Si va a ser usado como subclausula
		define('VAR_CARGO', 14);
		//Variable dinamica para Pacto de Horas Extras 
		//Las fecha de referencia estan en ContenedorUtilidades::calculoFechaDinamicaSistema
		define('VAR_DINAMICA','FechaDinamica');
	
	/*
	TIPO DE USUARIOS
	1	SUPER USUARIO	0	0	0
	2	ADMINISTRADOR	0	0	0
	3	SUPERVISOR		0	0	0
	4	REPRESENTANTE 	0	0	0
	5	TRABAJADOR		0	0	0
	7	FUNCIONARIO		0	0	0
	8	Validador		0	0	0

	*/
	
	//***EMPLEADOS***//
		//Rol por defecto de empleado
		define('ROL',1); // 1	Privado 2	General
		//Estado por defecto de empleado
		define('ESTADO_EMPLEADO','A'); //A	Activo,B	Activo, E	Finiquitado
		//Perfil de usuario
		define('PERFIL_USUARIO', 5 ); //TRABAJADOR
		//Tipo de firma
		define('TIPO_FIRMA_EMPLEADO', 3); // 3 Pin o Huella
		//Tipo de correo para usuario nuevo
		define('CODIGO_CORREO_USUARIO_NUEVO',99);
	
	/***REPRESENTANTE***/
		//Rol de representante 
		define('Rol_REPRESENTANTE',1); // 1	Privado 2	Publico
		//Perfil de usuario
		define('PERFIL_REPRESENTANTE', 4 ); //REPRESENTANTE

	//***FICHA DE CONTRATACION***//
		//GENERACION AUTOMATICA DE LOS PROCESOS
		define('GENERACION_AUTOMATICA_PROCESO', 1); // 1 = TRUE, 0 = FALSE
		//NOMBRE AUTOMATICO DE PROCESO
		define('NOMBRE_PROCESO_AUTOMATICO','Contratacion ');
		//Carpeta de subida de documentos de Fichas 
		define('CARPETA_ARCHIVOS_SUBIDAS','./tmp/');
		//Tipo de Documento de subida 
		define('TIPO_DOC_GESTOR', '10031');
		
		
	//Separador para concatenar el codigo de documento de RBK
	define('SEPARADOR_DOCCODE','|');
	
	//Roles de Firma RBK
	define("ROLES_RBK_REPRESENTANTE", 'REPR'); //Representante
	define("ROLES_RBK_REPRESENTANTE_2", 'REPR2'); //Representante 2
	define("ROLES_RBK_NOTARIO", 'NOTAR'); // Notaria

	//Roles de Firma DEC5
	define("ROLES_DEC5_REPRESENTANTE", 'Representantes'); //Representante
	define("ROLES_DEC5_REPRESENTANTE_2", 'Representantes_2'); //Representante 2
	define("ROLES_DEC5_NOTARIO", 'Notarios'); // Notaria

	//***limite de proceso automatico de documentos pos iteracion***//
	//define('LIMITE_PROCESA_EXCEL', 5); // Numero de filas a procesar antes de refrescar la llamada
//***Constantes de sistema***//
	define('FECHA_MINIMA_SISTEMA', '14-12-1901');// Parche fecha inferior como limite
	define('LIMITE_PROCESA_EXCEL', 5); // Tiempo en segundos que dura la ejecucion del modulo que genera documentos masivamente
	define('INTENTOS_CURL', 10); // Cantidad de veces que el proceso de generacion de documentos masivos intentara establecer la conexion para continuar cuando haya agotado su tiempo de procesado
		
	
	/***Agregar Empleado Manualmente***/
	define('AGREGAR_EMPLEADO', 0 ); //1 : Si o 0 : No 
							
	//*** Define el comportamiento del sistema respecto a la accion de visualizar o no en listado de aprobacion de documentos, documentos generados para firma manual  ***//
	define('APROBAR_MANUAL', false);
//***Ruta de generacion del PDF***//
	#define('RUTA_GENERACION_ARCHIVO','C:\\Aplicaciones\\Dimerc_RBK\\tmp\\');
	#define('CURL_TIMEOUT_GENERACION',20);
	#define('SSLVERSION',4);
//***Inline encabezado y ordinal, con texto de clausula***//
	#define('INLINE_ENCABEZADO_ORDINAL', 1); //1 : Si o 0 : No 
//***Selector de generador PDF***//
	#define('GENERADOR_PDF','SERVICIO');
	define('GENERADOR_PDF','DOMPDF');
//***Agregar Empleado Manualmente***//
	#define('AGREGAR_EMPLEADO', 1 ); //1 : Si o 0 : No 
//***Socket y Puerto para la generación de PDF al Spire***//
	#define('SOCKET','tcp://127.0.0.1:');
	#define('PUERTO','8333');

	//Variable para las postulacion inferiores a x meses
	define('VAR_X_MESES_TRAS', 6);

	// Postulaciones (semasforo link)
	define('DIAS_VERDE', 15);
	define('DIAS_NARANJO', 10);
	define('DIAS_ROJO', 5);								 
?>