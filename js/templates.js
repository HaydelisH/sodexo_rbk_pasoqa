"use strict";

$(document).ready(function() {
    /*Buscva la accion del formulario*/
    let action = $("#formulario").attr("action");
console.log();
    /*Identificamos a cual funcionalidad ir*/
    switch (action) {

    }
});

/*
var cookie_dc = document.cookie;
var cookie_prefix = "muestramenu" + "=";
var cookie_begin = cookie_dc.indexOf("; " + cookie_prefix);//alert ("vv:" +  cookie_begin);
*/

var ajax;
var ajax_AGREGAR;
var ajax_cambioestado;
var ajax_checkListDocumentos_listar;
var ajax_ELIMINAR;
var ajax_FF;
var ajax_firma;
var ajax_GPorFichaA;
var ajax_IPDFA;
var ajax_LINKP;
var ajax_listado;
var ajax_LOF;
var ajax_LORP;
var ajax_PAC;
var ajax_PFA;
var ajax_PLA;
var ajax_PMod;
var ajax_RDLAprobar;
var ajax_RLIP;
var ajax_SDAgregar;
var ajax_tipoGestor_listar;
var ajax_tipoMovimiento_listar;
var ajax_token;
var ajax3;
var ajaxFirmantes_LOF;
var anno = 0;
//var Array = "";
var array = [];
var array_listado = [];
var array_RLDAprobar = [];
var arreglo_emp_GPorFichaA = [];
var arreglo_emp_IPDFA = [];
var arreglo_emp_RLIP = [];
var arreglo_IPDFA = [];
var arreglo_RLIP = [];
var aux_FF = [];
var aval = 0;
var band_token = 0;
var calculo = 0;
var cant_firma = 0;
var cant_token = 0;
var cantidad_firmantes_GPorFichaA;
var cantidad_firmantes_IPDFA;
var cantloop_IFListado = 10;
var conexion;
var conexion_FF;
var conexion_IFListado;
var consulta;
var consulta_IFListado;
var consulta_LOF;
var consulta_LORP;
var contenido ='';
var contenido_ded ='';
var dia = 0;
var doccode;
var docs;
var docs_RLDAprobar;
var documento = 0;
var documento_firma = 0;
var documento_token = 0;
var elementoError;
var elementoOK;
var error;
var fila = 0;
var fila_firma = 0;
var fila_listado = 0;
var fila_token = 0;
var i = 0;
var i;
var i_firma = 0;
var i_PLA;
var i_token = 0;
var id_doc_RDLAprobar = 0;
var iddoc = 0;
var idEstadoGestion_RA1 = '<php:texto id="estadoFormularioid" />';
var idEstadoGestion_RA2 = '<php:texto id="estadoFormularioid" />';
var indice = 0;
var indices;
var indices_firma = [];
var indices_token = [];
var iniciarx_IFListado;
var Inicio = "";
var institucion;
var letras="abcdefghijklmnñopqrstuvwxyz";
var listado;
var listado_LINKP = [];
var listado_POSAgregar = [];
var lpid = $("#lpid").val();
var matriz_FF = [];
var memory = '';
var memory_LOF = '';
var memory_LORP = '';
var memoryCount = 0;
var memoryCount_LOF = 0;
var memoryCount_LORP = 0;
var memoryTop = 10;
var memoryTop_LOF = 10;
var memoryTop_LORP = 10;
var mensajeDuplicados_POSAgregar = 'Tiene Postulaciones vigentes';//'Ya esta agregado el cargo a la postulacion';
var mes = 0;
var mesesArriendo = 0;
var message;
var mismovalor_IFListado;

var notario_GMAgregar = 0;
var nuevo ="";
var nuevo_emp_GPorFichaA = {};
var nuevo_emp_IPDFA = {};
var nuevo_emp_RLIP = {};
var nuevo_RLIP = {};
var num;
var num_a = 0;
var num_c = 0;
var num_e = 0;
var num_n = 0;
var num_RLIP = 0;
var numeros="0123456789";
var orden = [];
var orden_clientes = $("#orden_clientes").val();
var orden_emp_GPorFichaA = [];
var orden_emp_IPDFA = [];
var orden_emp_RLIP = [];
var orden_representantes = $("#orden_representantes").val();
var proceso;
var proceso_FF;
var proceso_firma;
var proceso_RDLAprobar;
var proceso_token;
var respuesta_FF;
var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
var salida;
var salida_cambioestado
var salida_firma;
var salida_GPorFichaA;
var salida_IPDFA;
var salida_listado;
var salida_PFA;
var salida_PLA;
var salida_PMod;
var salida_RDLAprobar;
var salida_RLIP;
var salida_SDAgregar;
var salida_token;
var salida3;
var separador = "-";
var session_id;
var status;
var sub = '';
var sub_ded = '';
var uri;
 

//////////////////////////////////////////////////////////////////////////////
/////////////////////     V A L I D A C I O N E S       //////////////////////
//////////////////////////////////////////////////////////////////////////////

//Asignar variables 
if (document.getElementById("noenviados")) { var noenviados = document.getElementById('noenviados').value; }

/*if (document.getElementById("mensajeError")) { elementoError = escapeHtmlChars(document.getElementById("mensajeError").value); }
if (document.getElementById("mensajeOK")) { elementoOK = escapeHtmlChars(document.getElementById("mensajeOK").value); }
*/

//////////////////////////////////////////////////////////////////////
/////////////////////     F U N C I O N E S     //////////////////////
//////////////////////////////////////////////////////////////////////


function OpcionMenu(p_opcion)
{
   MostrarCargando();
   //setCookie ("opcion",p_opcion,"365");
   setCookie("opcion",p_opcion, {secure: true, SameSite:'Lax'}, 365);
}

/*
//Cuando carga el sitio
if (document.getElementById('cargandoImagen')) { 
	var cargandoImagen = document.getElementById('cargandoImagen');
	cargandoImagen.src="images/cargando.gif";
}

if (cookie_begin != -1)
{
	var rescookie = getCookie("muestramenu");//alert ("jj:" + rescookie);
	var elemento = document.getElementById("bodyx");

	if (rescookie == "cerrado")
	{
		elemento.className = " skin-purple sidebar-mini sidebar-collapse";                                    
	}
	else
	{
		elemento.className = " skin-purple sidebar-mini";                                       
	}
}

if ( $("#mensajeAd").html().length > 0 ){
    $("#mensajeAd").addClass("callout callout-warning");
}else{
    $("#mensajeAd").removeClass("callout callout-warning");
    $("#mensajeAd").html("");
}

getUltimoCambioClave();

if ($('#progreso').attr('max') != '')
{
    estadoGeneracionMasiva();
}

if ($("#mensajeOK").html() != '') {
    $("#btn_msj_cambio_clave").click();
}

if(lpid>0){
    fnChangeEmpresa();
    $("#lugarpagoid").val(lpid);
    $("#idCentroCosto").attr("disabled", false);
    $("#nombreCentroCosto").attr("disabled", false);			
    $("#accion2").attr("disabled", false);
}

checkListDocumentos_listar();
tipoMovimiento_listar();
tipoGestor_listar();

num = $("#example2 tr").length;
i = 0;

for( i = 0; i < num ; i++ ){

   if($("#Aprob_" + i ).val() == 1 ){
      $("#APROBAR_" + i ).attr("disabled",true);
    }
}

<php:texto id="clickAutomatico" />

if( $("#cantidad").val() == 1 ){
    $("#Seleccion").prop('checked', true);
    $("#select").val(1);
}

//documentos de otras paginas
var docs = $("#docs").val();
if ( docs != '' ){
    var docs_res = docs.split(",");

    //Recorro el array que traje de php
    jQuery.each( docs_res, function( key, value ) {
        if( value != '' ){
              array.push(value);
          }
      //Recorro todos los checkbox del listado 
      $('.checkbox').each( function(){
           if ( value == $(this).val() ){
               $(this).prop('checked', true);
           }
      });
    });
}

if( $("#docs").val() != '' ){

}else{
    $("#SELECCION_MULTIPLE").attr("disabled",true);
    $("#SELECCION_MULTIPLE_R").attr("disabled",true);
}
if( $("#mensajeAd").html().length > 0 ) $("#mensajeAd").addClass("callout callout-warning");
else $("#mensajeAd").removeClass("callout callout-warning");


/*Documentos_FirmaMasiva_formulario.html*/
/*
$("#idDocumento_vd").val($("#idDocumento").val());
$("#idDocumento_ac").val($("#idDocumento").val());
$("#idDocumento_el").val($("#idDocumento").val());
$("#idDocumento_ac").val($("#idDocumento").val());
$("#idDocumento_eg").val($("#idDocumento").val());											
$("#idDocumento_rn").val($("#idDocumento").val());

if( $("#ficha").val().length > 0 ){
    $(".fichaid").show();
}
else{
    $(".fichaid").hide();
}

var cant_docs  = document.getElementById('cant_docs');
var cant_filas = document.getElementById('example').rows.length;

if ( cant_docs.innerHTML === '' ){
  cant_docs.innerHTML = cant_filas - 2;
}

if( $("#cantidad").val() == 1 ){
    $("#Seleccion").prop('checked', true);
    $("#select").val(1);
}

//documentos de otras paginas
var docs = $("#docs").val();
if ( docs != '' ){
    var docs_res = docs.split(",");

    $("#FIRMA_MASIVA").attr('disabled', false);

    //Recorro el array que traje de php
    jQuery.each( docs_res, function( key, value ) {
        if( value != '' ){
              array.push(value);
          }
      //Recorro todos los checkbox del listado 
      $('.checkbox').each( function(){
           if ( value == $(this).val() ){
               $(this).prop('checked', true);
           }
      });
    });
}else{
    $("#FIRMA_MASIVA").attr('disabled',true);
}

var cant_docs_token  = document.getElementById('cant_docs');
var cant_filas_token = document.getElementById('example').rows.length;

if ( cant_docs_token.innerHTML === '' ){
  cant_docs_token.innerHTML = cant_filas_token - 2;
}

if( $("#ficha").val().length > 0 ){
    $(".fichaid").show();
}
else{
    $(".fichaid").hide();
}

if( $("#idProyecto").val().length == 0 ){
    $("#idProyecto").val("(Seleccione)"); 
    $(".da").prop('readonly', true);
    $("#Tarifa").attr('disabled', 'disabled');
    $("#Exceso").attr('disabled', 'disabled');
    $("#FIRMANTES").attr('disabled',true);
  }else{
    $("#	Inicio").css({"background-color":"white"});
    $("#	Final").css({"background-color":"white"});
  }

  if($("#idDocumento_Gama").val().length == 0 ){
    $("#idDocumento_Gama").val("(Seleccione)");
  }

  if( $("#Tarifa").val().length == 0 ){
    $("#Tarifa option:selected").text("(Seleccione)");  
  }

  if( $("#Exceso").val().length == 0 ){
     $("#Exceso option:selected").text("(Seleccione)"); 
  }

  if( $("#proyectos_emp").val() == 1){
    $("#mensajeError").addClass("callout callout-warning");
    $("#mensajeError").html("La Empresa seleccionada no tiene Proyectos asociados");
    $("#btn_proyecto").attr("disabled",true);
  }

  if ( $("#KmsExceso").val().charAt(0) === '.' ){
     var val = '0' + String($("#KmsExceso").val());
     $("#KmsExceso").val( parseFloat(val));
  }

   if( $("#FormasPago").val().length == 0 ){
      $("#FormasPago option:selected").text("(Seleccione)");  
   }

   if($("#seleccion").val().length == 0 ){
      $("#seleccion").val("Seleccione...");
   }
   if($("#seleccion_ded").val().length == 0 ){
    $("#seleccion_ded").val("Seleccione...");
   }

if( $("#FormasPago").val().length == 0 ){
    $("#FormasPago option:selected").text("(Seleccione)");  
 }

 InicioFormularioF();

 documentosFormularioAgregar();

 $(".detalle").val($(".page-link").html());

 <php:texto id="CambiarContrasenaCheked" />

 InicioPlantillasPorEmpresasMod();
 InicioDocumentosVigentesListado();
 listar_firmantescentrocosto_ajax();
 InicioExcelEmp();
 InicioFichaModificar();
 InicioGenerarMasivo();
 InicioLoadResultado();
 InicioPlantillasAgregarClausulas();
 InicioPlantillasFormularioAprobar();
 InicioPlantillasAprobarBlo();
 InicioPlantilasModificar();
 InicioPlantillasModificarClausulas();
 InicioPlantillasListadoClasificado();
 InicioRevisionActor1();
 InicioRevisionActor2();
 inicio();
 InicioRevisorActor1();
 InicioEnrolar();
 InicioDocumentosFirmaMasiva();

 //revisa si hay algun cambio en el archivo
upload_input_PPE = document.querySelectorAll('#archivo')[0];

InicioPlantillasListado();*/

//////////////////////////////////////////////////////////////////////
/////////////////////     F U N C I O N E S     //////////////////////
//////////////////////////////////////////////////////////////////////

function getUltimoCambioClave()
{
	var parametros = '';
	var url = "ultimoCambioClave_ajax.php";
	// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
	if( window.XMLHttpRequest )
		ajax = new XMLHttpRequest(); // No Internet Explorer
	else
		ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
	// Almacenamos en el control al funcion que se invocara cuando la peticion
	// cambie de estado 
	ajax.onreadystatechange = funcionCallback_getUltimoCambioClave;
	// Enviamos la peticion
	ajax.open( "POST", url, true );
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(parametros);
}

function funcionCallback_getUltimoCambioClave()
{
	// Comprobamos si la peticion se ha completado (estado 4)
	//OcultarCargando();
	if( ajax.readyState == 4 )
	{
		// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
		if( ajax.status == 200 )
		{
			// Escribimos el resultado en la pagina HTML mediante DHTML
			//document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
			var salida = ajax.responseText;
			//console.log(salida);
			if( salida != '' )
			{
				var datos = JSON.parse(salida);
				console.log(datos);
				if (datos.exito)
				{
					/*if (datos.mensaje.estado == 'notificar')
					{
												
					}*/
				}
				else
				{
					//alert(123);
					$("#mensajeError").show();
					$("#mensajeError").html(datos.mensaje);
				}
			}
			else
			{
				$("#mensajeError").show();
				$("#mensajeError").html("Ha habido un error, intente mas tarde.");
			}
		}
	}
}

$(".index").click(function(){
	$(this).attr("href","index.php");
});

/*accesoxusuario_CentrosCosto.html*/

function IniMensajes ()
{
    mensajeError.innerHTML 	= "";
    $("#mensajeError").removeClass("callout callout-warning");
    mensajeOK.innerHTML 	= "";
    $("#mensajeOK").removeClass("callout callout-success");
}
	
function IniGraba()
{	
    IniMensajes ();
    error = 0;
    
    var cantidad = document.getElementById("cantidad").value;

    for (var b=0; b<cantidad; b++)
    {
        checknew 	= document.getElementById("sel_" + b).checked;
        checkorig 	= document.getElementById("selori_" + b).checked;
        
        if (checknew != checkorig)
        {
            centrocostoid 	= document.getElementById("ide_" + b).value;//alert ("centrocostoid:" + centrocostoid);
            newusuarioid 	= document.getElementById("newusuarioid").value;//alert ("newusuarioid:" + newusuarioid);
            empresaid 		= document.getElementById("empresaid").value;//alert ("empresaid:" + empresaid);
            lugarpagoid 	= document.getElementById("lugarpagoid").value;//alert ("lugarpagoid:" + lugarpagoid);
            
            if (checknew == false )
            {
                accion = "elimina";
            }
            else
            {
                accion = "graba";
            }
        
            phpyparametros = "accesoxusuarioccosto.php?accion=" + accion + "&" + "newusuarioid=" + newusuarioid + "&empresaid=" + empresaid + "&lugarpagoid=" + lugarpagoid + "&centrocostoid=" + centrocostoid;// + "&departamentoid=" + departamentoid;//alert ("phpyparametros" + phpyparametros);
            Grabar (phpyparametros);
            
        }
    }
    
    if (error > 0)
    {
        mensajeError.innerHTML 	= "Problemas al grabar, Intente Nuevamente.";
        $("#mensajeError").addClass("callout callout-warning");
        mensajeOK.innerHTML 	= '';
        $("#mensajeOK").removeClass("callout callout-success");
    }
    else
    {
        for (var b=0; b<cantidad; b++)
        {
            document.getElementById("selori_" + b).checked = document.getElementById("sel_" + b).checked;
        }
        
        mensajeOK.innerHTML = 'Acci&oacute;n realizada con &eacute;xito';
        $("#mensajeOK").addClass("callout callout-success");
    }
}

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

function Grabar(phpyparametros)
{  
    var urlencode = phpyparametros.split('?');
    var url = urlencode[0];
    var parametros = urlencode[1];
    conexion=crearXMLHttpRequest();
    conexion.onreadystatechange = procesarEventos;
    conexion.open('POST', url, false);
    conexion.send(parametros);
}

function procesarEventos()
{
    if(conexion.readyState == 4)
    {
        respuesta = conexion.responseText;
        if (respuesta != "OK")
        {
            error++;
        }
    } 
    else 
    {
        mensajeOK.innerHTML = 'Procesando...';
        $("#mensajeOK").addClass("callout callout-success");
    }
}

/*accesoxusuario_Empresas.html*/

function IniGrabaEmpresas()
{	
    IniMensajes ();
    error = 0;
    
    var cantidad = document.getElementById("cantidad").value;
    MostrarCargando();
    for (var b=0; b<cantidad; b++)
    {
        checknew 	= document.getElementById("sel_" + b).checked;
        checkorig 	= document.getElementById("selori_" + b).checked;
        
        if (checknew != checkorig)
        {
            empresaid 		= document.getElementById("ide_" + b).value;
            newusuarioid 	= document.getElementById("tipousuario").value;
            
            if (checknew == false )
            {
                accion = "elimina";
            }
            else
            {
                accion = "graba";
            }
        
            phpyparametros = "accesoxusuarioempresas.php?accion=" + accion + "&" + "newusuarioid=" + newusuarioid + "&empresaid=" + empresaid;
            Grabar (phpyparametros);
            
        }
        
    }

    if (error > 0)
    {
        mensajeError.innerHTML 	= "Problemas al grabar, Intente Nuevamente.";
        mensajeOK.innerHTML 	= '';
    }
    else
    {	

        for (var b=0; b<cantidad; b++)
        {
            document.getElementById("selori_" + b).checked = document.getElementById("sel_" + b).checked;
        }
        mensajeOK.innerHTML = 'Acci&oacute;n realizada con &eacute;xito';
    }
    
    refrescaMensaje();
    
    OcultarCargando();
}
	
function refrescaMensaje()
{
    
    var elementoOK 		= document.getElementById("mensajeOK");
    var elementoError 	= document.getElementById("mensajeError");

    if (elementoOK.innerHTML !="")
    {
            elementoOK.className += "callout callout-success";
            elementoError.innerHTML = "";
            elementoError.className = "";
    }

    if (elementoError.innerHTML !="")
    {
        elementoError.className += "callout callout-danger";
        elementoOK.innerHTML = "";
        elementoOK.className = "";
    }
}

/*accesoxusuario_LugaresPago.html*/

function IniGrabaLugaresPago()
{	
    //alert ("ini");
    IniMensajes ();
    error = 0;
    
    var cantidad = document.getElementById("cantidad").value; //alert( "CANTIDAD " + cantidad);
    MostrarCargando();

    for (var b=0; b<cantidad; b++)
    {  //alert(b + " < " + cantidad );
        checknew 	= document.getElementById("sel_" + b).checked; //alert("NUEVO " + checknew);
        checkorig 	= document.getElementById("selori_" + b).checked; //alert("ANTES :" + checkorig);
    
        if (checknew != checkorig)
        {
            lugarpagoid 	= document.getElementById("lugarpago_" + b).value;//alert ("lugarpagoid:" + lugarpagoid);
            newusuarioid 	= document.getElementById("newusuarioid").value;//alert ("newusuarioid:" + newusuarioid);
            empresaid 		= document.getElementById("empresaid").value;//alert ("empresaid:" + empresaid);
            
            if (checknew == false )
            {
                accion = "elimina";
            }
            else
            {
                accion = "graba";
            }
        
            phpyparametros = "accesoxusuariolugares.php?accion=" + accion + "&" + "newusuarioid=" + newusuarioid + "&empresaid=" + empresaid + "&lugarpagoid=" + lugarpagoid;//alert ("phpyparametros" + phpyparametros);

            Grabar (phpyparametros);
        }
        
    }
    //alert("Error :" + error );
    if (error > 0)
    {
        mensajeError.innerHTML 	= "Problemas al grabar, Intente Nuevamente.";
        mensajeOK.innerHTML 	= '';
    }
    else
    {	//alert("hola");
        for (var b=0; b<cantidad; b++)
        {	//alert(b);alert(cantidad);
            document.getElementById("selori_" + b).checked = document.getElementById("sel_" + b).checked;
        }
        
        mensajeOK.innerHTML = 'Acci&oacute;n realizada con &eacute;xito';
    }
    
    refrescaMensaje();
    
    OcultarCargando();
}

/*asignacionperfiles.html*/
function verificar_rut(id) {
    var rut_usuario = id.value;
    limpiar_ad();

    if (rut_usuario.length == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");

        return false;
    }

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");

    if (validaRut2(id)) {
        consulta_sesion();
    } else {
        return false;
    }
}

  
function verificar_rut_agregar(id) {
    var rut_usuario = id.value;
    var perfilagregar = $("#perfilagregar").val();
    limpiar_ad();

    if (rut_usuario.length === 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
        return false;
    }

    var respuesta = validaRut2(id);

    if (perfilagregar == '(Seleccione)') {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el perfil");
        return false;
    }

    if (respuesta) {
        consulta_sesion();
    } else {
        return false;
    }
}
  
function verificar_rut_eliminar(id) {
    var rut_usuario = id.value;
    var perfilaeliminar = $("#perfilaeliminar").val();
    limpiar_ad();

    if (rut_usuario.length == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
        return false;
    }

    var respuesta = validaRut2(id);

    if (perfilaeliminar == '(Seleccione)') {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el perfil a eliminar");
        return false;
    }

    if (respuesta) {
        consulta_sesion();
    } else {
        return false;
    }
}

function limpiar_ad() {
    $("#mensajeAd").removeClass("callout callout-warning");
    $("#mensajeAd").html("");
}
  
$(".clicktab").click(function(){
	let tabid = $(this).attr("id");
	let href = $(this).attr("href");
	$(".clicktab").removeClass("active").attr("aria-selected","false");
	$("#"+tabid).addClass("active").attr("aria-selected","true");
	$(".tab-pane").removeClass("active").removeClass("show");
	$(href).addClass("active").addClass("show");
});

/*asignacionroles.html*/

function verificar_rut_agregar_rol(id)
{ 
    var rut_usuario = id.value;
    var rolagregar = $("#rolagregar").val();
    
    if( rut_usuario.length === 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
        return false;
    }
    
    var respuesta = validaRut2(id);

    if( rolagregar == '(Seleccione)' ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el rol");
        return false;
    }
    
    if( respuesta ){
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        consulta_sesion();
    }else{
        return false;
    }
}

function verificar_rut_eliminar_rol(id)
{ 
    var rut_usuario = id.value;
    var rolaeliminar = $("#rolaeliminar").val();
    
    if( rut_usuario.length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
        return false;
    }
    
    var respuesta = validaRut2(id);
    
    if( rolaeliminar == '(Seleccione)' ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el rol a eliminar");
        return false;
    }
    
    if( respuesta ){
    
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        
        consulta_sesion();
    }else{
        return false;
    }
}
  
/*blackList.html*/

$("#newusuarioid").change(function () {
    $("#Documento").val('');
    $("#archivo").val('');
    var respuesta = validaRut2(document.formulario.newusuarioid);
    habilitaBoton();
});

function ver() {
    document.getElementById("resultado").submit();
}

function estadoGeneracionMasiva() {
    $('#estado').show();
    consultaEstado();
    consulta = setInterval(function () { consultaEstado() }, 10000);
}

function consultaEstado() {
    conexion = crearXMLHttpRequest();
    conexion.open('POST', 'blackListProcessEstado.php?accion=ESTADO&IdArchivo=2', false);
    conexion.send(null);
    try {
        respuesta = JSON.parse(conexion.responseText);
    }
    catch (e) {
        respuesta.actual = 0;
    }
    respuesta.actual = respuesta.actual == null ? 0 : respuesta.actual;
    $('#progreso').val(respuesta.actual);
    $('#estadodetalle').html(respuesta.actual + " filas procesadas de un total de " + ($('#progreso').attr('max')) + " filas.");
    if (controlMemory(respuesta.actual)) {
        if (respuesta.actual == $('#progreso').attr('max')) {
            clearInterval(consulta);
            $('#estado').hide();
            alert('El proceso de generacion masiva de documentos ha finalizado.');
        }
    }
    else {
        matarProceso();
        clearInterval(consulta);
        $('#estado').hide();
        alert('Ha ocurrio un error, revise detalle y ejecute nuevamene las filas no procesadas.');
    }
}

function matarProceso() {
    conexion = crearXMLHttpRequest();
    conexion.open('POST', 'blackListProcess.php?accion=KILL', false);
    conexion.send(null);
    memoryCount = 0;
}

function controlMemory(dato) {
    var respuesta = true;
    if (memory == dato) {
        memoryCount++;
    }
    else {
        memoryCount = 0;
    }
    if (memoryCount >= memoryTop) {
        respuesta = false;
    }
    memory = dato;
    console.log(dato, memory, memoryCount, memoryTop, respuesta);
    return respuesta;
}

function habilitaBoton() {
    if ($("#Documento").val() != '' || $('#newusuarioid').val() != '') {
        $("#GENERAR").attr('disabled', false);
    }
}

$("#archivo").change(function () {
    $("#Documento").val($("#archivo").val());
    $('#newusuarioid').val('');
    habilitaBoton();
});

function subirArchivo() {
    if ($("#Documento").val() != '') {
        //revisa si hay algun cambio en el archivo
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        MostrarCargando();
        if (validar() == true) {
            upload_input = document.querySelectorAll('#archivo')[0];
            uploadFile(upload_input.files[0]);
        }
    }
    else if ($('#newusuarioid').val() != '') {
        procesarUno();
    }
}

function validar() {
    return true;
}

function uploadFile(file) {
    $("#GENERAR").attr('disabled', true);
    importar(file, 2);
}

function importar(file, IdArchivo) {
    var xhr = crearXMLHttpRequest();
    xhr.upload.addEventListener('loadstart', function (e) {
        document.getElementById('mensaje').innerHTML = 'Cargando archivo...';
    }, false);
    xhr.upload.addEventListener('load', function (e) {
        document.getElementById('mensaje').innerHTML = '';
    }, false);
    xhr.upload.addEventListener('error', function (e) {
        alert('Ha habido un error :/');
    }, false);
    var datos = $('#formulario').serialize();
    var url = 'blackListProcess.php?accion=LOAD&datos=' + datos + '&IdArchivo=' + IdArchivo;
    //var url  = 'blackList.php?accion=LOAD&IdArchivo='+ IdArchivo + '&RutEmpresa=' + rutempresa + '&datos=' + datos;
    xhr.open('POST', url, false);//se le agrego false para que sea sincrono, para que espere antes de comenzar a cargar el otro archivo.
    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);
    xhr.addEventListener('readystatechange', function (e) {
        if (this.readyState == 4) {
            try {
                respuesta = JSON.parse(xhr.responseText);
                if (IdArchivo == 2) {
                    $('#progreso').attr('max', respuesta.highestRow);
                    procesar(IdArchivo);
                    estadoGeneracionMasiva();
                }
            }
            catch (e) {
                respuesta = xhr.responseText;
                var elementoError = document.getElementById("mensajeError");
                elementoError.innerHTML = respuesta;
                elementoError.className += "callout callout-danger";
            }
        }
        if (IdArchivo == 2) {
            document.getElementById("Documento").value = "";
            document.getElementById("archivo").value = "";
            OcultarCargando();
        }
    });
    xhr.send(file);
}


function procesarUno() {
    parametros = 'accion=UNO' + '&IdArchivo=2&to=' + ($("#soloUno").is(':checked') ? '2' : '1') + '&rut=' + $('#newusuarioid').val();
    var url = "blackListProcess.php";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_soloUNO;
    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}

function funcionCallback_soloUNO() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            var salida = ajax.responseText;
            respuesta = JSON.parse(salida);
            $('#mensajeOK').removeClass();
            if (respuesta.exito) {
                alert(respuesta.mensaje);
            }
        }
    }
}

function procesar(IdArchivo) {
    conexion = crearXMLHttpRequest();
    var datos = $('#formulario').serialize();
    parametros = '?accion=LOOP0' + '&IdArchivo=' + IdArchivo + '&to=1';
    conexion.open('POST', 'blackListProcess.php' + parametros);
    conexion.send(null);
    respuesta = conexion.responseText;
}

/*buscarPostulacion.html*/
function limpiaOtro(elementoID)
{
    $('#'+elementoID).val('');
    console.log(elementoID);
}

$(".ocultar").click(function(){
    if ( $("#collapseExample").hasClass('in')){
        $("#a").html('Mostrar filtros');
    }else{
        $("#a").html('Ocultar filtros');
    }
});


/*BuscarReportePostulacion2.html*/

$("#RutEmpresa").change(function(){
    $("#nombrecargoempleado").val('');
    $("#idCargoEmpleado").val('');
    $("#nombrecentrocosto").val('');
    $("#centrocostoid").val('');
});

//Modal de Cargos empleado
$("#btn_cargoEmpleado").click(function(){
    var RutEmpresa = $("#RutEmpresa").val();
    $(".fila_ce").remove();
    if( RutEmpresa == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "postulacion_ajax3.php";
    var parametros = "RutEmpresa=" + RutEmpresa;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_cargoEmpleado;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

function funcionCallback_cargoEmpleado()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                //[{"0":"cc1","centrocostoid":"cc1","1":"cc1","nombrecentrocosto":"cc1","2":"'cc1'","lp":"'cc1'"},{"0":"cc2","centrocostoid":"cc2","1":"c2","nombrecentrocosto":"c2","2":"'cc2'","lp":"'cc2'"}]
                $.each(listado, function( index, value ) {
                    $('#tabla_cargoEmpleado tr:last').after('<tr class="fila_ce"><td>' + listado[index].idCargoEmpleado + '</td><td><div id="' + listado[index].idCargoEmpleado + '">' + listado[index].nombrecargoempleado + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_ce" id="btn_agregar_ce" onclick="agregarCE(\'' + listado[index].idCargoEmpleado + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#tabla_cargoEmpleado tr:last').after('<tr class="fila_ce"><td colspan=3 >No existen Relaciones laborales para la Empresa y el cargo seleccionado</td></tr>');
            }
        }
    }
}

function agregarCE(i){
    $("#idCargoEmpleado").val(i);
    $("#nombrecargoempleado").val($("#"+i).html());
    $("#cerrar_cargoEmpleado").click();
}

//Modal de Centros de Costo
$("#btn_centro_costo").click(function(){
    var RutEmpresa = $("#RutEmpresa").val();
    $(".fila_cc").remove();
    if( RutEmpresa == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "Generar_Documento_PorFicha_ajax1.php";
    var parametros ="RutEmpresa=" + RutEmpresa;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_centrocosto;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

function funcionCallback_centrocosto()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                //[{"0":"cc1","centrocostoid":"cc1","1":"cc1","nombrecentrocosto":"cc1","2":"'cc1'","lp":"'cc1'"},{"0":"cc2","centrocostoid":"cc2","1":"c2","nombrecentrocosto":"c2","2":"'cc2'","lp":"'cc2'"}]
                $.each(listado, function( index, value ) {
                    $('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td>' + listado[index].centrocostoid + '</td><td><div id="' + listado[index].centrocostoid + '">' + listado[index].nombrecentrocosto + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cc" id="btn_agregar_cc" onclick="agregarCC(' + listado[index].lp + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td colspan=3 >No existen Relaciones laborales para la Empresa y el Centro de Costo seleccionado</td></tr>');
            }
        }
    }
}

function agregarCC(i){
    $("#centrocostoid").val(i);
    $("#nombrecentrocosto").val($("#"+i).html());
    $("#cerrar_centro_costo").click();
}

/*cambioclave.html*/

$('#clavenew, #clave').keyup(function (e) {
    var enoughRegex = new RegExp("(?=.{<php:texto id='largoClaveMin'/>,}).*", "g");
    if (tiene_numeros($('#clavenew').val(), $('#minNumber').val())) {
        $('#numeroPass').removeClass('callout callout-danger');
        $('#numeroPass').addClass('callout callout-success');
    } else {
        $('#numeroPass').removeClass('callout callout-success');
        $('#numeroPass').addClass('callout callout-danger');
    }
    if (tiene_minusculas($('#clavenew').val(), $('#minMinus').val())) {
        $('#minusculaPass').removeClass('callout callout-danger');
        $('#minusculaPass').addClass('callout callout-success');
    } else {
        $('#minusculaPass').removeClass('callout callout-success');
        $('#minusculaPass').addClass('callout callout-danger');
    }
    if (tiene_mayusculas($('#clavenew').val(), $('#minMayus').val())) {
        $('#mayusculaPass').removeClass('callout callout-danger');
        $('#mayusculaPass').addClass('callout callout-success');
    } else {
        $('#mayusculaPass').removeClass('callout callout-success');
        $('#mayusculaPass').addClass('callout callout-danger');
    }
    if (true == enoughRegex.test($(this).val())) {
        $('#caracterPass').removeClass('callout callout-danger');
        $('#caracterPass').addClass('callout callout-success');
    } else {
        $('#caracterPass').removeClass('callout callout-success');
        $('#caracterPass').addClass('callout callout-danger');
    }
    if (tiene_caracter_especial($('#clavenew').val(), $('#minCaracterEspecial').val())) {
        $('#especialPass').removeClass('callout callout-danger');
        $('#especialPass').addClass('callout callout-success');
    } else {
        $('#especialPass').removeClass('callout callout-success');
        $('#especialPass').addClass('callout callout-danger');
    }

});
function tiene_caracter_especial(valor, cantidadMinima) {

    var expresionRegular = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/g;

    // Utilizar el método match para encontrar todas las coincidencias
    var coincidencias = valor.match(expresionRegular);
    // Calcular la cantidad total de caracteres especiales
    var cantidadTotal = coincidencias ? coincidencias.length : 0;
    // alert(cantidadTotal);
    return expresionRegular.test(valor) && cantidadTotal >= cantidadMinima;
}
function tiene_mayusculas(texto, minMayusculas) {
    var contadorMayusculas = 0;

    for (var i = 0; i < texto.length; i++) {
        if ((letras.toUpperCase()).indexOf(texto.charAt(i), 0) !== -1) {
            contadorMayusculas++;
        }
    }

    return contadorMayusculas >= minMayusculas;
}

function tiene_minusculas(texto, minMinusculas) {
    var contadorMinusculas = 0;

    for (var i = 0; i < texto.length; i++) {
        if (letras.indexOf(texto.charAt(i), 0) !== -1) {
            contadorMinusculas++;
        }
    }

    return contadorMinusculas >= minMinusculas;
}

function tiene_numeros(texto, minNumeros) {
    var contadorNumeros = 0;

    for (var i = 0; i < texto.length; i++) {
        if (numeros.indexOf(texto.charAt(i), 0) !== -1) {
            contadorNumeros++;
        }
    }

    return contadorNumeros >= minNumeros;
}

function showPassword(button) {
    var idPass = "clave"
    var suffix = button.id.replace("show_password_", "");
    idPass = idPass + suffix;

    var cambio = document.getElementById(idPass);
    if (cambio.type == "password") {
        cambio.type = "text";
        $('.icon_' + suffix).removeClass('fa fa-eye-slash').addClass('fa fa-eye');
    } else {
        cambio.type = "password";
        $('.icon_' + suffix).removeClass('fa fa-eye').addClass('fa fa-eye-slash');
    }
}

/*cambiodatosdec_FormularioModificar.html*/

//Validar los tipo Number 
$(".MODIFICAR_CDEC").click(function(){
    //Campos vacios
  if($("#serial").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#serial').focus();
      return false;
  }
  if($("#nombre").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#nombre').focus();
      return false;
  }
  if($("#appaterno").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#appaterno').focus();
      return false;
  }
 
  if ($("#correo1").val().length == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#correo').focus();
      return false;
  }
  if ($("#correo2").val().length == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#correo').focus();
      return false;
  }

  if ($("#fono").val() == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Campo no debe estar vac&iacute;o");
      $('#fono').focus();
      return false;
  }
  //Si tiene menos numeros de lo que debe 
  if (($("#fono").val().length > 1 ) && ($("#fono").val().length < 12)){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Debe introducir el fono completo");
      $('#fono').focus();
      return false;
  }
  //Si no tiene el +
  if ($("#fono").val().charAt(0) != '+'){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Debe introducir el fono con codigo de area iniciando con + . Ejemplo: +569 ");
      $('#fono').focus();
      return false;
  }
  //Si la confirmacion de correo es valida
  if( $("#correo1").val() === $("#correo2").val() ){
      $("#correo").val($("#correo1").val());
  }
  else{
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Verifique correo electr&oacute;nico");
      $('#correo1').focus();
      return false;
  }
  
  
  MostrarCargando();
});

/*cambiopin_FormularioModificar.html*/

function ValidaPin()
 {
	var pin 	= document.getElementById("pin").value;
	var confpin = document.getElementById("confipin").value;
	
	if (pin != confpin)
	{
		alert ("el pin y la confirmacion del pin son distintos, favor ingresar nuevamente")
		document.getElementById("pin").value = "";
		document.getElementById("confipin").value = "";
		return false;
	}
	
	MostrarCargando();
 }


 /*cargosempleados_Agregar.html*/

 function agregarEmpresa(RutEmpresa, RazonSocial)
 {
     $("#RutEmpresa").val(RutEmpresa);
     $("#RazonSocial").val(RazonSocial);
     //$("#RutEmpresa").data('Rutempresa', RutEmpresa);
     $("#cerrar_empresa").click();
 }

 /*centroscosto_FormularioAgregar.html*/

$(".cambiarEmpresa").change(function () {
    fnChangeEmpresa();
});
function fnChangeEmpresa() {

    var RutEmpresa = $("#RutEmpresa").val();
    $(".fila_lp").remove();

    if (RutEmpresa == 0) {

        $("#mensajeError").addClass(" callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        $("#lugarpagoid").attr("disabled", true);
        $("#accion2").attr("disabled", true);
        return false;

    } else {

        //	$("#mensajeError").html("");
        //$("#mensajeError").removeClass(" callout callout-warning");
        var parametros = "RutEmpresa=" + RutEmpresa;
        var url = "CentroCosto_ajax.php";

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_rutempresa;

        // Enviamos la peticion
        ajax.open("POST", url, false);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }
}

function funcionCallback_rutempresa() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            let listado = JSON.parse(salida);
            let num = listado.length;

            if (num > 0) {
                $.each(listado, function (index, value) {
                    $('#lugarpagoid').append('<option class="fila_lp" value=' + listado[index].lugarpagoid + '>' + listado[index].nombrelugarpago + ' </option>');
                });
                $("#lugarpagoid").attr("disabled", false);

            }

        }
    }
}

$(".cambiarLugarPagoid").change(function () {

    var lugarpagoid = $("#lugarpagoid").val();

    if (lugarpagoid != 0) {
        $("#idCentroCosto").attr("disabled", false);
        $("#nombreCentroCosto").attr("disabled", false);
        $("#accion2").attr("disabled", false);
    } else {
        $("#idCentroCosto").attr("disabled", true);
        $("#nombreCentroCosto").attr("disabled", true);
        $("#accion2").attr("disabled", true);
    }
});

/*checkListDocumentos.html*/
function eliminar_cl(idTipoMovimiento, idTipoGestor) {
    var url = "checkListDocumentos_ajax.php";
    var parametros = "idTipoMovimiento=" + idTipoMovimiento + '&idTipoGestor=' + idTipoGestor + '&accion=ELIMINAR';
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_ELIMINAR = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_ELIMINAR = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_ELIMINAR.onreadystatechange = funcionCallback_eliminar;
    // Enviamos la peticion
    ajax_ELIMINAR.open("POST", url, true);
    ajax_ELIMINAR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_ELIMINAR.send(parametros);
};

function funcionCallback_eliminar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_ELIMINAR.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_ELIMINAR.status == 200) {
            $('#mensajeOK').html('Elemento eliminado.');
            $('#mensajeOK').addClass('callout callout-success');
            checkListDocumentos_listar();
        }
    }
}

$('.AGREGAR_CL').on('click', function () {
    var idTipoMovimiento = $("#TipoMovimiento").val();
    var idTipoGestor = $('#TipoGestor').val();
    var Obligatorio = $('#obligatorio').prop('checked') ? 1 : 0;
    var url = "checkListDocumentos_ajax.php";
    var parametros = "idTipoMovimiento=" + idTipoMovimiento + '&idTipoGestor=' + idTipoGestor + '&Obligatorio=' + Obligatorio + '&accion=AGREGAR';
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_AGREGAR = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_AGREGAR = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_AGREGAR.onreadystatechange = funcionCallback_agregar;
    // Enviamos la peticion
    ajax_AGREGAR.open("POST", url, true);
    ajax_AGREGAR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_AGREGAR.send(parametros);
});
function funcionCallback_agregar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_AGREGAR.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_AGREGAR.status == 200) {
            var salida = ajax_AGREGAR.responseText;
            listado = JSON.parse(salida);
            $('#mensajeOK').removeClass();
            switch (listado.codigo) {
                case 0:
                    $('#mensajeOK').html('Agregado exitosamente');
                    $('#mensajeOK').addClass('callout callout-success');
                    checkListDocumentos_listar();
                    break;
                case 1:
                    $('#mensajeOK').html(listado.mensaje);
                    $('#mensajeOK').addClass('callout callout-danger');
                    break;
            }
        }
    }
}


function tipoGestor_listar() {
    $(".opcion_TipoGestor").remove();
    var url = "checkListDocumentos_ajax.php";
    var parametros = "accion=tipoGestor_listar";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_tipoGestor_listar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_tipoGestor_listar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_tipoGestor_listar.onreadystatechange = funcionCallback_tipoGestor_listar;
    // Enviamos la peticion
    ajax_tipoGestor_listar.open("POST", url, true);
    ajax_tipoGestor_listar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_tipoGestor_listar.send(parametros);
};

function funcionCallback_tipoGestor_listar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_tipoGestor_listar.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_tipoGestor_listar.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            var salida = ajax_tipoGestor_listar.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            //console.log(listado);
            if (num > 0) {
                $('#TipoGestor').append('<option class="opcion_TipoGestor" value="">(Seleccione)</option>');
                $.each(listado, function (index, value) {
                    $('#TipoGestor').append('<option class="opcion_TipoGestor" value="' + listado[index].idTipoGestor + '">' + listado[index].Nombre + '</option>');
                });
            } else {
                //$('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td colspan=3 >No existen datos para la empresa y cargo seleccionado </td></tr>');
            }
        }
    }
};


function tipoMovimiento_listar() {
    $(".opcion_tipomovimiento").remove();
    var url = "checkListDocumentos_ajax.php";
    var parametros = "accion=tipoMovimiento_listar";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_tipoMovimiento_listar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_tipoMovimiento_listar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_tipoMovimiento_listar.onreadystatechange = funcionCallback_tipoMovimiento_listar;
    // Enviamos la peticion
    ajax_tipoMovimiento_listar.open("POST", url, true);
    ajax_tipoMovimiento_listar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_tipoMovimiento_listar.send(parametros);
};
function funcionCallback_tipoMovimiento_listar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_tipoMovimiento_listar.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_tipoMovimiento_listar.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            var salida = ajax_tipoMovimiento_listar.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            //console.log(listado);
            if (num > 0) {
                $('#TipoMovimiento').append('<option class="opcion_tipomovimiento" value="">(Seleccione)</option>');
                $.each(listado, function (index, value) {
                    $('#TipoMovimiento').append('<option class="opcion_tipomovimiento" value="' + listado[index].idTipoMovimiento + '">' + listado[index].Descripcion + '</option>');
                });
            } else {
                //$('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td colspan=3 >No existen datos para la empresa y cargo seleccionado </td></tr>');
            }
        }
    }
};


function checkListDocumentos_listar() {
    var url = "checkListDocumentos_ajax.php";
    var parametros  ="&accion=checkListDocumentos_listar";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_checkListDocumentos_listar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_checkListDocumentos_listar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_checkListDocumentos_listar.onreadystatechange = funcionCallback_checkListDocumentos_listar;
    // Enviamos la peticion
    ajax_checkListDocumentos_listar.open("POST", url, true);
    ajax_checkListDocumentos_listar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_checkListDocumentos_listar.send(parametros);
};
function funcionCallback_checkListDocumentos_listar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_checkListDocumentos_listar.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_checkListDocumentos_listar.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            var salida = ajax_checkListDocumentos_listar.responseText;
            listado = JSON.parse(salida);
            $('#tabla_checkListDocumentos').DataTable().destroy();
            $('#tabla_checkListDocumentos').DataTable({
                data: listado,
                columns: [
                    { data: 'Descripcion' },
                    { data: 'Nombre' }, //or { data: 'MONTH', title: 'Month' }`
                    {
                        data: 'Obligatorio',
                        render: function (data, type, row) {
                            return (data ? 'Si' : 'No');
                        }
                    },
                    {
                        data: 'idTipoMovimiento',
                        render: function (data, type, row) {
                            return '<button class="btn btn-default btn-sm" type="button" onclick="javascript:eliminar_cl(\'' + row.idTipoMovimiento + '\', \'' + row.idTipoGestor + '\');">Eliminar</button>';
                        }
                    }
                ],
                paging: true,
                lengthMenu: [10, 25, 50],
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                fixedColumns: true
            });
        }
    }
};

function refrescarFormulario() {
    setDocumentos_listar();
    $("#plantilla").val('');
    $('#plantilla').data('idplantilla', '');
    checkFormulario();
}
function checkFormulario() {
    if ($("#rutEmpresa").data('rutempresa') != '' && $('#cargoempleado').data('idCargoEmpleado') != '' && $('#plantilla').data('idplantilla') != '' && $('#tipomovimiento').val() != '') {
        $('#AGREGAR').removeAttr('disabled');
    }
    else {
        $('#AGREGAR').attr('disabled', 'disabled');
    }
}

function agregarCC(nombrecargoempleado, idCargoEmpleado)
{
    $("#cargoempleado").val(nombrecargoempleado);
    $('#cargoempleado').data('idCargoEmpleado', idCargoEmpleado);
    $("#cerrar_cargoempleado").click();
    setDocumentos_listar();
    checkFormulario();
}
function agregarPlantilla(idPlantilla, Descripcion_Pl)
{
    $("#plantilla").val(Descripcion_Pl);
    $('#plantilla').data('idplantilla', idPlantilla);
    $("#cerrar_plantilla").click();
    checkFormulario();
}

function setDocumentos_listar()
{
    var RutEmpresa = $("#rutEmpresa").data('rutempresa');
    var idCargoEmpleado = $("#cargoempleado").data('idCargoEmpleado');
    $(".fila_setDocumentos").remove();
    var url  = "setDocumentos_ajax.php";
    var parametros= "RutEmpresa=" + RutEmpresa + "&idCargoEmpleado=" +idCargoEmpleado + "&accion=LISTAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_setDocumentos_listar;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}
function funcionCallback_setDocumentos_listar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td>' + listado[index].nombreTipoMovimento + '</td><td>' + listado[index].nombrePlantilla + ' </td><td ><button type="button" style="background-color: transparent;" class="btn btn-default btn-sm" name="btn_eliminar_setDocumentos" onclick="javascript:eliminarSetDocumentos(\'' + listado[index].idTipoMovimiento + '\', \'' + listado[index].RutEmpresa + '\', \'' + listado[index].idCargoEmpleado + '\', \'' + listado[index].idPlantilla + '\');">Eliminar</button></td></tr>');
                });
            }else{
                $('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td colspan=3 >No existen datos para la empresa y cargo seleccionado </td></tr>');
            }
        }
    }
}

function eliminarSetDocumentos(idTipoMovimiento, RutEmpresa, idCargoEmpleado,idPlantilla)
{
    var url  = "setDocumentos_ajax.php";
    var parametros  ="RutEmpresa=" + RutEmpresa + "&idCargoEmpleado=" +idCargoEmpleado + "&idTipoMovimiento=" + idTipoMovimiento + "&idPlantilla=" + idPlantilla + "&accion=ELIMINAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_eliminarSetDocumentos;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}
function funcionCallback_eliminarSetDocumentos()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            refrescarFormulario();
        }
    }
}

/*clausulas_FormularioAprobar.html*/
$(".APROBAR_CLA").click(function(){

    //Consultar si esta Plantilla existe para mas de una empresa 
    var idClausula = $("#idClausula").val();
    var url = "Clausulas_ajax1.php";
    var parametros = "idClausula=" + idClausula;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
      ajax = new XMLHttpRequest(); // No Internet Explorer
    else
      ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_CLAUSULAS;

    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
  });

  var ajax;
  var salida;
   
  function funcionCallback_CLAUSULAS()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida = ajax.responseText;
       
        if( salida > 1 ){
          var respuesta = confirm("Esta Clausula esta asociada a " + salida + " Plantilla, desea continuar ?");
          
          if( respuesta )
            $("#APROBAR_1").click();
          else
            return respuesta;
        }else{
           $("#APROBAR_1").click();
        }

      }
    }
  }

/*clausulas_FormularioModificar.html*/
$(".MODIFICAR_CLA").click(function () {

    //Consultar si esta Plantilla existe para mas de una empresa 
    var idClausula = $("#idClausula").val();
    var url = "Clausulas_ajax1.php";
    var parametros="idClausula=" + idClausula;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_MODIFICAR_CLA;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

var ajax;
var salida;

function funcionCallback_MODIFICAR_CLA() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;

            if (salida > 1) {
                var respuesta = confirm("Esta Clausula esta asociada a " + salida + " Plantillas, desea continuar ?");

                if (respuesta)
                    $("#MODIFICAR_1").click();
                else
                    return respuesta;
            } else {
                $("#MODIFICAR_1").click();
            }

        }
    }
}

/*clausulas_Listado.html*/

function funcionCallback_listado_clasif() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            salida = ajax.responseText;
            respuesta = salida.split("|");
            document.getElementById("ejemplo-titulo").innerHTML = respuesta[0];
            document.getElementById("ejemplo-cuerpo").innerHTML = respuesta[1];
            $("#ejemplo").modal('show');
        }
    }
}

function vistaprevia(fila) {
    var vistaprevia = "idClausula_" + fila;
    var valor = document.getElementById(vistaprevia).value;
    var url = "Clausulas_ajax.php";
    var parametros = "idClausula=" + valor;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_listado_clasif;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}


function funcionCallback_clausulas_listado() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            salida = ajax.responseText;
            let respuesta = salida.split("|");
            document.getElementById("ejemplo-titulo").innerHTML = respuesta[0];
            document.getElementById("ejemplo-cuerpo").innerHTML = respuesta[1];
            $("#ejemplo").modal('show');
        }
    }
}

function vistaprevia(fila) {
    var vistaprevia = "idClausula_" + fila;
    var valor = document.getElementById(vistaprevia).value;
    var url = "Clausulas_ajax.php";
    var parametros = "idClausula=" + valor;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_clausulas_listado;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}

function consultar_clausulas_listado(fila) {

    i = fila;

    //Consultar si esta Plantilla existe para mas de una empresa 
    var idClausula = $("#idClausula_" + i).val();
    var url = "Clausulas_ajax1.php";
    var parametros = "idClausula=" + idClausula;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_consultar;
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.send(parametros);
}

function funcionCallback_consultar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;

            var respuesta;

            if (salida > 1) {

                respuesta = confirm("Esta Clausula esta asociada a " + salida + " Plantilla, desea continuar ?");

                if (respuesta)
                    $("#ELIMINAR_" + i).click();
                else
                    return respuesta;

            } else {

                respuesta = confirm("Desea eliminar esta Clausula ?");

                if (respuesta)
                    $("#ELIMINAR_" + i).click();
                else
                    return respuesta;
            }

        }
    }
}



function funcionCallback_correo_listado() {

    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            salida = ajax.responseText;
            respuesta = salida.split("|");
            document.getElementById("ejemplo-titulo").innerHTML = respuesta[0];
            document.getElementById("ejemplo-cuerpo").innerHTML = respuesta[1];
            $("#ejemplo").modal('show');

        }
    }
}


function vistaprevia_correos_listado(fila) {
    var vistaprevia = "CodCorreo_" + fila;
    var valor = document.getElementById(vistaprevia).value;

    var url = "Correo_ajax.php";
    var parametros = "CodCorreo=" + valor;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_correo_listado;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
}

$(".cerrar_modal").click(function(){

    if( $("#loginModal")) $("#loginModal").modal('hide');
    if( $("#alertModal")) $("#alertModal").modal('hide');
    if( $("#modal_cargoEmpleado")) $("#modal_cargoEmpleado").modal('hide');
    if( $("#modal_centro_costo")) $("#modal_centro_costo").modal('hide');
    if( $("#modal_msj_cambio_clave")) $("#modal_msj_cambio_clave").modal('hide');
    if( $("#modal_empresas")) $("#modal_empresas").modal('hide');
    if( $("#modal_lugares_pago")) $("#modal_lugares_pago").modal('hide');
    if( $("#ejemplo")) $("#ejemplo").modal('hide');
    if( $("#exampleModal-mod")) $("#exampleModal-mod").modal('hide');
    if( $("#modal_vp")) $("#modal_vp").modal('hide');
    if( $("#modal_firm")) $("#modal_firm").modal('hide');
    if( $("#modal_pendientes")) $("#modal_pendientes").modal('hide');
    if( $("#modal_pendientes_r")) $("#modal_pendientes_r").modal('hide');
    if( $("#myModal" )) $("#modal").modal('hide');
    if( $("#modal_centroscosto")) $("#modal_centroscosto").modal('hide');
    if( $("#modal_atencion")) $("#modal_atencion").modal('hide');
    if( $("#modal_notificacion")) $("#modal_notificacion").modal('hide');
    if( $("#detallePostulante")) $("#detallePostulante").modal('hide');
    if( $("#modal_subir")) $("#modal_subir").modal('hide');
    if( $("#modal_subir_adicional")) $("#modal_subir_adicional").modal('hide');
    if( $("#myModalx")) $("#myModalx").modal('hide');
    if( $("#modal_departamento")) $("#modal_departamento").modal('hide');
    if( $("#modal_vista_previa")) $("#modal_vista_previa").modal('hide');
    if( $("#modal_link")) $("#modal_link").modal('hide');
    if( $("#OC")) $("#OC").modal('hide');
    if( $("#divtabla")) $("#divtabla").modal('hide');
    if( $("#modal_creaproveedor")) $("#modal_creaproveedor").modal('hide');
    if( $("#modal_creafirmanteproveedor")) $("#modal_creafirmanteproveedor").modal('hide');
    if( $("#modal_plantilla")) $("#modal_plantilla").modal('hide');

});

/*DeclaracionConflictoInteres.html*/
$('.eliminar_dci').click(function () {
    var url = "EliminarFormulario_ajax.php";
    var parametros = "idDocumento=" + $("#idDocumento").val() + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_rechazar_dci;
    // Enviamos la peticion
    ajax.open("POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

function funcionCallback_rechazar_dci() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if (salida != '') {
                if (salida) {
                    $('.esteFormulario').show();
                    $('.ENVIARFORMULARIO_dci').show();
                    $('.vista_previa_dci').hide();
                }
                else {
                    return false;
                }
            }
            else {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$('.aprobar_dci').click(function () {
    MostrarCargando();
    var url = "Documentos_aprobar2_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val();// + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_aprobar_dci;
    // Enviamos la peticion
    ajax.open("POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_aprobar_dci() {
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if (salida != '') {
                if (salida) {
                    $('#formularioFirmador').submit();
                }
                else {
                    return false;
                }
            }
            else {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$(".vista_previa_dci").click(function () {
    //MostrarCargando();
    var url = "Documentos_aprobar4_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val(); //Descargar Documento
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_descargarDoc_dci;
    // Enviamos la peticion
    ajax.open("POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_descargarDoc_dci() {
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if (salida) {
                var ruta = salida;
                $("#viewer").attr("src", ruta);
                $("#num").html('Documento : ' + $('#idDocumento').val());
            }
            else {
                return false;
            }
        }
    }
}
$('.ENVIARFORMULARIO_dci').on('click', function () {
    if (validar_dci()) {
        MostrarCargando();
        var parametros2 = $('#formulario').serialize();
        //Produccion var url = "EnvioFormulario_ajax.php?idPlantilla=" + 70 + "&idProceso=" + 59 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //QA 
        var url = "EnvioFormulario_ajax.php";
        var parametros="idPlantilla=" + 1016 + "&idProceso=" + 2 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros2;
        //Desarrollo var url = "EnvioFormulario_ajax.php?idPlantilla=" + 36 + "&idProceso=" + 13 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_envioformulario_dci;
        // Enviamos la peticion
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }
});
function funcionCallback_envioformulario_dci() {
    // Comprobamos si la peticion se ha completado (estado 4)
    //OcultarCargando();
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            var salida = ajax.responseText;
            //console.log(salida);
            if (salida != '') {
                var datos = JSON.parse(salida);
                if (datos.estado) {
                    $('#idDocumento').val(datos.idDocumento);
                    //$('.vista_previa_dci').val(datos.idDocumento);
                    $('.ENVIARFORMULARIO_dci').hide();
                    $('.vista_previa_dci').show();
                    $('.vista_previa_dci').click();
                }
                else {
                    //alert(123);
                    $("#mensajeError").show();
                    $("#mensajeError").html(datos.mensaje);
                }
            }
            else {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
function validar_dci() {
    var respuesta = true;
    $('#formulario input[type=text]').each(function (index, element) {
        if (element.name.split('-')[1]) {
            if ($(element).val() == '') {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('#formulario textarea').each(function (index, element) {
        if (!$(element).prop('disabled')) {
            if ($(element).val() == '') {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('input:radio').each(function (index, element) {
        if ($('input:radio[name=' + element.name + ']:checked').val() == undefined) {
            aux = element.id.split('_');
            respuesta = false;
            $(element).focus();
            $('.' + aux[0]).css('background', 'darkred');
            $('.' + aux[0]).css('color', 'white');
        }
    });
    return respuesta;
}
function pinta_dci(element) {
    $(element).css('background', 'white');
    $(element).css('font-color', 'grey');
    $(element).css('color', 'black');
}
function controlCheck_dci(elemento) {
    var aux = elemento.id.split('_');
    pinta_dci($('.' + aux[0]));
    // Para tipo pregunta 1
    try {
        if ($(elemento).val() == 'si') {
            $('#' + aux[0] + '_texto').attr('disabled', false);
            if ($('#' + aux[0] + '_etiqueta').html().indexOf('(*)') == -1) {
                $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html() + ' (*)');
            }
            $('#' + aux[0] + '_show').show();
        }
        else {
            $('#' + aux[0] + '_texto').attr('disabled', true);
            $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html().replace(' (*)', ''));
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch (e) { }
    // Para tipo pregunta 2
    try {
        if ($(elemento).val() == 'si') {
            $('#' + aux[0] + '_MAS').attr('disabled', false);
            $('#' + aux[0] + '_MENOS').attr('disabled', false);
            $('#' + aux[0] + '_show').show();
        }
        else {
            $('#' + aux[0] + '_MAS').attr('disabled', true);
            $('#' + aux[0] + '_MENOS').attr('disabled', true);
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch (e) { }
}


function limpiar_dci(elemento, origen) {
    for (i = indice; i > 0; i--) {
        menos_dci(elemento, origen);
    }
}
function mas_dci(elemento, origen) {
    if ((origen == 'check' && indice == 0) || origen == 'button' && indice >= 0) {
        var aux = elemento.id.split('_');
        var html = '';
        html += '<div class="row" id="fila_' + indice + '">';
        html += '   <li>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Nombre (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna1-R[]" placeholder="Nombre" maxlength="110" value="<php:item id="' + aux[0] + '_nombre" />"  onkeyup="pinta_dci(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Parentesco (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna2-R[]" placeholder="Parentesco" maxlength="50" value="<php:item id="' + aux[0] + '_parentesco" />"  onkeyup="pinta_dci(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Entidad / Autoridad politica / Cargo (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna3-R[]" placeholder="Entidad / Autoridad politica / Cargo" maxlength="50" value="<php:item id="' + aux[0] + '_entidadAutoridadCargo" />"  onkeyup="pinta_dci(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '   </li>';
        html += '</div>';
        $('#' + aux[0] + '_fila').append(html);
        indice++;
    }
}
function menos_dci(elemento, origen) {
    if ((indice > 1 && origen == 'button') || (origen == 'check')) {
        var aux = elemento.id.split('_');
        indice--;
        $('#fila_' + indice).remove();
    }
}

/*DeclaracionConflictoInteresAboveSite.html*/
$('.eliminar_as').click(function(){
    var url = "EliminarFormulario_ajax.php";
    var parametros  = "idDocumento=" + $("#idDocumento").val() + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_rechazar_as;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
function funcionCallback_rechazar_as()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('.esteFormulario_as').show();
                    $('.ENVIARFORMULARIO_as').show();
                    $('.vista_previa_as').hide();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$('.aprobar_as').click(function(){
    MostrarCargando();
    var url = "Documentos_aprobar2_ajax.php";
    var parametros  ="idDocumento=" + $('#idDocumento').val();// + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_aprobar_as;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send( parametros);
});
//Callback de Vista previa
function funcionCallback_aprobar_as()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('#formularioFirmador').submit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$(".vista_previa_as").click(function()
{
    //MostrarCargando();
    var url = "Documentos_aprobar4_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val(); //Descargar Documento
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_descargarDoc_as;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_descargarDoc_as()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if(salida)
            {
                var ruta = salida;
                $("#viewer").attr("src",ruta);
                $("#num").html('Documento : ' + $('#idDocumento').val());
            }
            else
            {
                return false;
            }
        }
    }
}
$('.ENVIARFORMULARIO_as').on('click', function(){
    if (validar_as())
    {
        MostrarCargando();
        var parametros = $('#formulario').serialize();
        let idPlantilla = $("#idPlantilla").value();
        let idProceso = $("#idProceso").value();

        //Produccion var url = "EnvioFormulario_ajax.php?idPlantilla=" + ? + "&idProceso=" + ? + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //QA var url = "EnvioFormulario_ajax.php?idPlantilla=" + 2112 + "&idProceso=" + 9 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //Desarrollo 
        var url = "EnvioFormulario_ajax.php";
        var parametros  ="idPlantilla=" + idPlantilla + "&idProceso=" + idProceso + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_envioformulario_as;
        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }
});
function funcionCallback_envioformulario_as()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    //OcultarCargando();
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            var salida = ajax.responseText;
            //console.log(salida);
            if( salida != '' )
            {
                var datos = JSON.parse(salida);
                if (datos.estado)
                {
                    $('#idDocumento').val(datos.idDocumento);
                    //$('.vista_previa_as').val(datos.idDocumento);
                    $('.ENVIARFORMULARIO_as').hide();
                    $('.vista_previa_as').show();
                    $('.vista_previa_as').click();
                }
                else
                {
                    //alert(123);
                    $("#mensajeError").show();
                    $("#mensajeError").html(datos.mensaje);
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
function validar_as()
{
    var respuesta = true;
    $('#formulario input[type=text]').each(function(index, element)
    {
        if (element.name.split('-')[1])
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('#formulario textarea').each(function(index, element)
    {
        if (!$(element).prop('disabled'))
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('input:radio').each(function(index, element)
    {
        if ($('input:radio[name=' + element.name + ']:checked').val() == undefined)
        {
            aux = element.id.split('_');
            respuesta = false;
            $(element).focus();
            $('.' + aux[0]).css('background', 'darkred');
            $('.' + aux[0]).css('color', 'white');
       }
    });
    $('#formulario select').each(function(index, element)
    {
        //console.log(index, element);
        //if (element.name.split('-')[1])
        //{
            if ($(element).val() == '0')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background-color', 'darkred');
                $(element).css('color', 'white');
            }
        //}
    });

    $('#formulario .RUT-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val()));
        if(!validaRut($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('RUT invalido');
        }
    });

    $('#formulario .EMAIL-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarEmail($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Email invalido');
        }
    });

    $('#formulario .FECHA-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarFecha2($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Fecha invalida');
        }
    });
    return respuesta;
}
function pinta_as(element)
{
    $(element).css('background', 'white');
    $(element).css('font-color', 'grey');
    $(element).css('color', 'black');
}
function controlCheck_as(elemento)
{
    var aux = elemento.id.split('_');
    pinta_as($('.' + aux[0]));
    // Para tipo pregunta 1
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_texto').attr('disabled', false);
            if ($('#' + aux[0] + '_etiqueta').html().indexOf('(*)') == -1)
            {
                $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html() + ' (*)');
            }
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_texto').attr('disabled', true);
            $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html().replace(' (*)', ''));
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
    // Para tipo pregunta 2
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_MAS').attr('disabled', false);
            $('#' + aux[0] + '_MENOS').attr('disabled', false);
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_MAS').attr('disabled', true);
            $('#' + aux[0] + '_MENOS').attr('disabled', true);
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
}

function limpiar_as(elemento, origen)
{
    for (i = indice; i > 0; i--)
    {
        menos_as(elemento, origen);
    }
}
function mas_as(elemento, origen)
{
    if ((origen == 'check' && indice == 0) || origen == 'button' && indice >= 0)
    {
        var aux = elemento.id.split('_');
        var html = '';
        html += '<div class="row" id="fila_' + indice + '">';
        html += '   <li>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Nombre (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna1-R[]" placeholder="Nombre" maxlength="110" value="<php:item id="' + aux[0] + '_nombre" />"  onkeyup="pinta_as(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Parentesco (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna2-R[]" placeholder="Parentesco" maxlength="50" value="<php:item id="' + aux[0] + '_parentesco" />"  onkeyup="pinta_as(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Entidad / Autoridad politica / Cargo (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna3-R[]" placeholder="Entidad / Autoridad politica / Cargo" maxlength="50" value="<php:item id="' + aux[0] + '_entidadAutoridadCargo" />"  onkeyup="pinta_as(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '   </li>';
        html += '</div>';
        $('#' + aux[0] + '_fila').append(html);
        indice++;
    }
}
function menos_as(elemento, origen)
{
    if ((indice > 1 && origen == 'button') || (origen == 'check'))
    {
        var aux = elemento.id.split('_');
        indice--;
        $('#fila_' + indice).remove();
    }
}

/*DeclaracionConflictoInteresADC.html*/

$('.eliminar_adc').click(function(){
    var url = "EliminarFormulario_ajax.php";
    var parametros = "idDocumento=" + $("#idDocumento").val() + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_rechazar_adc;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
function funcionCallback_rechazar_adc()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('.esteFormulario').show();
                    $('.ENVIARFORMULARIO_adc').show();
                    $('vista_previa_adc').hide();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$('.aprobar_adc').click(function(){
    MostrarCargando();
    var url = "Documentos_aprobar2_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val();// + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_aprobar_adc;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

//Callback de Vista previa
function funcionCallback_aprobar_adc()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('#formularioFirmador').submit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$("vista_previa_adc").click(function()
{
    //MostrarCargando();
    var url = "Documentos_aprobar4_ajax.php";
    var parametros ="idDocumento=" + $('#idDocumento').val(); //Descargar Documento
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_descargarDoc_adc;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_descargarDoc_adc()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if(salida)
            {
                var ruta = salida;
                $("#viewer").attr("src",ruta);
                $("#num").html('Documento : ' + $('#idDocumento').val());
            }
            else
            {
                return false;
            }
        }
    }
}
$('.ENVIARFORMULARIO_adc').on('click', function(){
    if (validar_adc())
    {
        MostrarCargando();
        var parametros = $('#formulario').serialize();
        let idPlantilla = $("#idPlantilla").value();
        let idProceso = $("#idProceso").value();
        //Produccion var url = "EnvioFormulario_ajax.php?idPlantilla=" + ? + "&idProceso=" + ? + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //QA var url = "EnvioFormulario_ajax.php?idPlantilla=" + 2111 + "&idProceso=" + 9 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //Desarrollo 
        var url = "EnvioFormulario_ajax.php";
        var parametros = "idPlantilla=" + idPlantilla+ "&idProceso=" + idProceso + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_envioformulario_adc;
        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }
});
function funcionCallback_envioformulario_adc()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    //OcultarCargando();
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            var salida = ajax.responseText;
            //console.log(salida);
            if( salida != '' )
            {
                var datos = JSON.parse(salida);
                if (datos.estado)
                {
                    $('#idDocumento').val(datos.idDocumento);
                    //$'#vista_previa_adc').val(datos.idDocumento);
                    $('.ENVIARFORMULARIO_adc').hide();
                    $('vista_previa_adc').show();
                    $('vista_previa_adc').click();
                }
                else
                {
                    //alert(123);
                    $("#mensajeError").show();
                    $("#mensajeError").html(datos.mensaje);
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
function validar_adc()
{
    var respuesta = true;
    $('#formulario input[type=text]').each(function(index, element)
    {
        if (element.name.split('-')[1])
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('#formulario textarea').each(function(index, element)
    {
        if (!$(element).prop('disabled'))
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('input:radio').each(function(index, element)
    {
        if ($('input:radio[name=' + element.name + ']:checked').val() == undefined)
        {
            aux = element.id.split('_');
            respuesta = false;
            $(element).focus();
            $('.' + aux[0]).css('background', 'darkred');
            $('.' + aux[0]).css('color', 'white');
       }
    });
    $('#formulario select').each(function(index, element)
    {
        //console.log(index, element);
        //if (element.name.split('-')[1])
        //{
            if ($(element).val() == '0')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background-color', 'darkred');
                $(element).css('color', 'white');
            }
        //}
    });

    $('#formulario .RUT-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val()));
        if(!validaRut($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('RUT invalido');
        }
    });

    $('#formulario .EMAIL-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarEmail($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Email invalido');
        }
    });

    $('#formulario .FECHA-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarFecha2($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Fecha invalida');
        }
    });
    return respuesta;
}
function pinta_adc(element)
{
    $(element).css('background', 'white');
    $(element).css('font-color', 'grey');
    $(element).css('color', 'black');
}
function controlCheck_adc(elemento)
{
    var aux = elemento.id.split('_');
    pinta_adc($('.' + aux[0]));
    // Para tipo pregunta 1
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_texto').attr('disabled', false);
            if ($('#' + aux[0] + '_etiqueta').html().indexOf('(*)') == -1)
            {
                $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html() + ' (*)');
            }
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_texto').attr('disabled', true);
            $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html().replace(' (*)', ''));
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
    // Para tipo pregunta 2
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_MAS').attr('disabled', false);
            $('#' + aux[0] + '_MENOS').attr('disabled', false);
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_MAS').attr('disabled', true);
            $('#' + aux[0] + '_MENOS').attr('disabled', true);
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
}

function limpiar_adc(elemento, origen)
{
    for (i = indice; i > 0; i--)
    {
        menos_adc(elemento, origen);
    }
}
function mas_adc(elemento, origen)
{
    if ((origen == 'check' && indice == 0) || origen == 'button' && indice >= 0)
    {
        var aux = elemento.id.split('_');
        var html = '';
        html += '<div class="row" id="fila_' + indice + '">';
        html += '   <li>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Nombre (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna1-R[]" placeholder="Nombre" maxlength="110" value="<php:item id="' + aux[0] + '_nombre" />"  onkeyup="pinta_adc(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Parentesco (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna2-R[]" placeholder="Parentesco" maxlength="50" value="<php:item id="' + aux[0] + '_parentesco" />"  onkeyup="pinta_adc(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Entidad / Autoridad politica / Cargo (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna3-R[]" placeholder="Entidad / Autoridad politica / Cargo" maxlength="50" value="<php:item id="' + aux[0] + '_entidadAutoridadCargo" />"  onkeyup="pinta_adc(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '   </li>';
        html += '</div>';
        $('#' + aux[0] + '_fila').append(html);
        indice++;
    }
}
function menos_adc(elemento, origen)
{
    if ((indice > 1 && origen == 'button') || (origen == 'check'))
    {
        var aux = elemento.id.split('_');
        indice--;
        $('#fila_' + indice).remove();
    }
}

/*DeclaracionConflictoInteresEmail.html*/
$('.eliminar_email').click(function(){
    var url = "EliminarFormulario_ajax.php";
    var parametros = "idDocumento=" + $("#idDocumento").val() + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_rechazar_email;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
function funcionCallback_rechazar_email()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('.esteFormulario_email').show();
                    $('.ENVIARFORMULARIO_email').show();
                    $('.vista_previa_email').hide();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$('.aprobar_email').click(function(){
    MostrarCargando();
    var url = "Documentos_aprobar2_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val();// + "&empleadoFormularioid=" + $('#empleadoFormularioid').val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_aprobar_email;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_aprobar_email()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if( salida != '' )
            {
                if(salida)
                {
                    $('#formularioFirmador').submit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
$(".vista_previa_email").click(function()
{
    //MostrarCargando();
    var url = "Documentos_aprobar4_ajax.php";
    var parametros ="idDocumento=" + $('#idDocumento').val(); //Descargar Documento
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_descargarDoc_email;
    // Enviamos la peticion
    ajax.open( "POST", url, false);
    AJAX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});
//Callback de Vista previa
function funcionCallback_descargarDoc_email()
{
    OcultarCargando();
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            if(salida)
            {
                var ruta = salida;
                $("#viewer").attr("src",ruta);
                $("#num").html('Documento : ' + $('#idDocumento').val());
            }
            else
            {
                return false;
            }
        }
    }
}
$('.ENVIARFORMULARIO_email').on('click', function(){
    if (validar_email())
    {
        MostrarCargando();
        var parametros = $('#formulario').serialize();
        let idPlantilla = $("#idPlantilla").value();
        let idProceso = $("#idProceso").value();
        //Produccion var url = "EnvioFormulario_ajax.php?idPlantilla=" + ? + "&idProceso=" + ? + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //QA var url = "EnvioFormulario_ajax.php?idPlantilla=" + 2110 + "&idProceso=" + 9 + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        //Desarrollo 
        var url = "EnvioFormulario_ajax.php";
        var parametros = "idPlantilla=" + idPlantilla + "&idProceso=" + idProceso + "&empleadoFormularioid=" + $('#empleadoFormularioid').val() + "&" + parametros;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_envioformulario_email;
        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }
});
function funcionCallback_envioformulario_email()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    //OcultarCargando();
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
            var salida = ajax.responseText;
            //console.log(salida);
            if( salida != '' )
            {
                var datos = JSON.parse(salida);
                if (datos.estado)
                {
                    $('#idDocumento').val(datos.idDocumento);
                    //$('.vista_previa_email').val(datos.idDocumento);
                    $('.ENVIARFORMULARIO_email').hide();
                    $('.vista_previa_email').show();
                    $('.vista_previa_email').click();
                }
                else
                {
                    //alert(123);
                    $("#mensajeError").show();
                    $("#mensajeError").html(datos.mensaje);
                }
            }
            else
            {
                $("#mensajeError").show();
                $("#mensajeError").html("Ha habido un error, intente mas tarde.");
            }
        }
    }
}
function validar_email()
{
    var respuesta = true;
    $('#formulario input[type=text]').each(function(index, element)
    {
        if (element.name.split('-')[1])
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('#formulario textarea').each(function(index, element)
    {
        if (!$(element).prop('disabled'))
        {
            if ($(element).val() == '')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background', 'darkred');
                $(element).css('font-color', 'white');
            }
        }
    });
    $('input:radio').each(function(index, element)
    {
        if ($('input:radio[name=' + element.name + ']:checked').val() == undefined)
        {
            aux = element.id.split('_');
            respuesta = false;
            $(element).focus();
            $('.' + aux[0]).css('background', 'darkred');
            $('.' + aux[0]).css('color', 'white');
       }
    });
    $('#formulario select').each(function(index, element)
    {
        //console.log(index, element);
        //if (element.name.split('-')[1])
        //{
            if ($(element).val() == '0')
            {
                respuesta = false;
                $(element).focus();
                $(element).css('background-color', 'darkred');
                $(element).css('color', 'white');
            }
        //}
    });

    $('#formulario .RUT-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val()));
        if(!validaRut($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('RUT invalido');
        }
    });

    $('#formulario .EMAIL-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarEmail($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Email invalido');
        }
		
		if( $(element).val().includes('sodexo') ){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Email invalido, no debe ser institucional');
        }
    });

    $('#formulario .FECHA-VALIDAR').each(function(index, element)
    {
        //console.log(validaRut($(element).val())); validarEmail(email)
        if(!validarFecha2($(element).val())){
            respuesta = false;
            $(element).focus();
            $(element).css('background', 'darkred');
            $(element).css('font-color', 'white');
            alert('Fecha invalida');
        }
    });
    return respuesta;
}
function pinta_email(element)
{
    $(element).css('background', 'white');
    $(element).css('font-color', 'grey');
    $(element).css('color', 'black');
}
function controlCheck_email(elemento)
{
    var aux = elemento.id.split('_');
    pinta_email($('.' + aux[0]));
    // Para tipo pregunta 1
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_texto').attr('disabled', false);
            if ($('#' + aux[0] + '_etiqueta').html().indexOf('(*)') == -1)
            {
                $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html() + ' (*)');
            }
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_texto').attr('disabled', true);
            $('#' + aux[0] + '_etiqueta').html($('#' + aux[0] + '_etiqueta').html().replace(' (*)', ''));
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
    // Para tipo pregunta 2
    try
    {
        if ($(elemento).val() == 'si')
        {
            $('#' + aux[0] + '_MAS').attr('disabled', false);
            $('#' + aux[0] + '_MENOS').attr('disabled', false);
            $('#' + aux[0] + '_show').show();
        }
        else
        {
            $('#' + aux[0] + '_MAS').attr('disabled', true);
            $('#' + aux[0] + '_MENOS').attr('disabled', true);
            $('#' + aux[0] + '_show').hide();
        }
    }
    catch(e){}
}

function limpiar_email(elemento, origen)
{
    for (i = indice; i > 0; i--)
    {
        menos_email(elemento, origen);
    }
}
function mas_email(elemento, origen)
{
    if ((origen == 'check' && indice == 0) || origen == 'button' && indice >= 0)
    {
        var aux = elemento.id.split('_');
        var html = '';
        html += '<div class="row" id="fila_' + indice + '">';
        html += '   <li>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Nombre (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna1-R[]" placeholder="Nombre" maxlength="110" value="<php:item id="' + aux[0] + '_nombre" />"  onkeyup="pinta_email(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Parentesco (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna2-R[]" placeholder="Parentesco" maxlength="50" value="<php:item id="' + aux[0] + '_parentesco" />"  onkeyup="pinta_email(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '       <div class="col-md-4">';
        html += '           <label for="contenedorid" >Entidad / Autoridad politica / Cargo (*)</label>';
        html += '           <div class="input-group col-xs-12">';
        html += '               <input type="text" class="form-control" name="' + aux[0] + '_columna3-R[]" placeholder="Entidad / Autoridad politica / Cargo" maxlength="50" value="<php:item id="' + aux[0] + '_entidadAutoridadCargo" />"  onkeyup="pinta_email(this)">';
        html += '           </div>';
        html += '       </div>';
        html += '   </li>';
        html += '</div>';
        $('#' + aux[0] + '_fila').append(html);
        indice++;
    }
}
function menos_email(elemento, origen)
{
    if ((indice > 1 && origen == 'button') || (origen == 'check'))
    {
        var aux = elemento.id.split('_');
        indice--;
        $('#fila_' + indice).remove();
    }
}

/*documentos_aprobar_Listado.html*/
//Seleccionar todo
$("#Seleccion").change(function(){

    var status = this.checked; 
    var array_aux = [];

    if ( $("#Seleccion").is(':checked')){

        //Limpian todos los campos 
        array = [];
        $("#docs").val('');
        $("#cantidad").val(1);
        $("#select").val(1);
        MostrarCargando();		 

        //Vamos al ajax a buscar todos los documentos pendientes 
        proceso = setInterval(function(){ buscarTodosDocumentosPorFirma() }, 100);

    }else{
        //Limpian todos los campos 
        array = [];
        $("#docs").val('');
        $("#cantidad").val(0);
        $("#select").val(0);
        $(".checkbox").prop('checked',false);
    }
            
});

$('.checkbox').change(function(){
    //Agregar 
    if ( $(this).is(':checked') ){
        array.push($(this).val());

        $("#SELECCION_MULTIPLE").attr("disabled",false);
        $("#SELECCION_MULTIPLE_R").attr("disabled",false);
    }
    //Eliminar
    else{
        var k = $(this).val();
        jQuery.each( array, function( key, value ) {
          if( k == value ){
              array.splice(key, 1); 
          }
        });

        if (array.length == 0 )
        {
            $("#SELECCION_MULTIPLE").attr("disabled",true);
            $("#SELECCION_MULTIPLE_R").attr("disabled",true);
        }
    }
    //Pasar al formulario
    $("#docs").val(array);
});

var id_doc = 0;

//Vista previa del documento 
$(".vista").click(function(){ 
    
    var id = this.id;
    var res = id.split('_'); 
    var i = res[1];
    var idDocumento = $("#idDocumento_" + i).val();
    id_doc = idDocumento;
    var url = "Documentos_aprobar4_ajax.php";
    var parametros = "idDocumento=" + idDocumento; //Descargar Documento

     // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    
       // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_descargarDoc;

    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

//Callback de Vista previa
function funcionCallback_descargarDoc(){
  // Comprobamos si la peticion se ha completado (estado 4)
  if( ajax.readyState == 4 )
  {
    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
    if( ajax.status == 200 )
    {
      // Escribimos el resultado en la pagina HTML mediante DHTML
      //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
      salida = ajax.responseText;

      if(salida){
          
        var ruta = salida;
        $("#viewer").attr("src",ruta);
        $("#num").html('Documento : ' + id_doc);
        
      }else{
        
          return false;
      }
    }
  }
}

/*documentos_aprobar_Listado.html*/
	
	//Vista previa de firmantes 
	$(".firmantes").click(function(){
		
		var id = this.id;
		var res = id.split('_'); 
		var i = res[1];

		var idDocumento = $("#idDocumento_" + i).val();
		iddoc = idDocumento;
		var url = "Documentos_firmaMasiva_ajax.php";
        var parametros= "idDocumento=" + idDocumento;

		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
		ajax = new XMLHttpRequest(); // No Internet Explorer
		else
		ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax.onreadystatechange = funcionCallback_firmantes;

		// Enviamos la peticion
		ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(parametros);
	});

	//Callback de Vista previa
	function funcionCallback_firmantes(){
	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
	      salida = ajax.responseText;

	      if(salida){
	      	verFirmantes(salida);
	      }else{
			
	      	return false;
	      }
	    }
	  }
	}

	//Construir tabla de firmantes
	function verFirmantes(salida){

		  respuesta = salida.split("|");
	      count = 0;
	      count = respuesta.length;
	      j = 0; 
	      n = 0;
	      rep = Math.trunc(count/5);

	      //Eliminar filas ateriores
	      $(".fila").remove();

	      while ( j < rep ){

		      nombre='';
		      rut='';
		      fecha='';

		      if( j != 0 ){
		      	  m = 4;
			      for (var i = n; i < m+n ; i++) {
			      	if( i == (n  ) ) rut    = respuesta[i];
			      	if( i == (1+n) ) nombre = respuesta[i];
			      	if( i == (3+n) ) fecha  = respuesta[i];
			      }
			  }else{
			  	  for (var i = 0; i < 5 ; i++) {
			      	if( i == (n  ) ) rut    = respuesta[i];
			      	if( i == (1+n) ) nombre = respuesta[i];
			      	if( i == (3+n) ) fecha  = respuesta[i];
			      }
			  }

		      $('#tabla_firm tr:last').after('<tr class="fila"><td>' + rut + '</td><td>' + nombre + '</td><td>' + fecha + '	</td></tr>');

		      if( j == 0) n = 5;
		      if( j == 1) n = 10;
		      if( j == 2) n = 15;
		      if( j == 3) n = 20; 
		      
		      j++;
	      }

	      $("#num_f").html('Documento : ' + iddoc);
	}

	//Seleccionar todo
	function buscarTodosDocumentosPorFirma(){

 		clearInterval(proceso);// poner esto cuando llega respuesta

		var usuarioid = $("#usuarioid").val();
		var ptipousuarioid = $("#ptipousuarioid").val();
		var idDocumento = $("#idDocumento").val();
		var idTipoDoc = $("#idTipoDoc").val();
		var fichaid = $("#idProceso").val();
		var RutEmpleado = $("#RutEmpleado").val();
		var NombreEmpleado = $("#NombreEmpleado").val();
		var fichaid = $("#fichaid").val();
		var idProceso = $("#idProceso").val();
		var idTipoFirma = $("#idTipoFirma").val();
		var formulario = "usuarioid=" + usuarioid + "&ptipousuarioid=" + ptipousuarioid + '&idDocumento=' + idDocumento + '&idTipoDoc=' + idTipoDoc + '&idProceso=' + idProceso + '&fichaid=' + fichaid + '&idProceso=' + idProceso + "&idTipoFirma=" + idTipoFirma + "&RutEmpleado=" + RutEmpleado + "&NombreEmpleado=" + NombreEmpleado;
		var url = "Documentos_aprobar5_ajax.php";
		
			//console.log(url);
		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
			ajax = new XMLHttpRequest(); // No Internet Explorer
		else
			ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax.onreadystatechange = funcionCallback_buscarTodos;
		
		// Enviamos la peticion
		ajax.open( "POST", url, true);
		ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');		
		ajax.send(formulario);
	}

	//Callback de Vista previa
	function funcionCallback_buscarTodos(){

	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      salida = ajax.responseText;
	      if(salida){
	      	docs = JSON.parse(salida); 
	      
	      	$.each(docs, function( index, value ) {
			  array.push(value);
			});

	      	$("#docs").val(array);

	      	$(".checkbox").prop('checked','true');

	      	if( array.length > 0 ){
				$("#SELECCION_MULTIPLE").attr("disabled",false);
				$("#SELECCION_MULTIPLE_R").attr("disabled",false);
	      	}

	      }else{
			
	      	return false;
	      }
		  OcultarCargando();		  
	    }
	  }
	}

	//Pasar los datos de los documentos a firmar al formulario 
	$("#SELECCION_MULTIPLE").click(function(){
	
		var documentos = $("#docs").val();
		var resultado = documentos.split(',');
		var j = resultado.length;
		
		if( documentos != '' ){
			//Limpiamos la tabla del modal 
		$(".fila_pendiente").remove();
			MostrarCargando();
			buscarDocumentos(documentos);
		}else{
			$("#SELECCION_MULTIPLE").attr("disabled",true);
		}

	}); 

	function buscarDocumentos(documentos){

		var url = "Documentos_aprobar1_ajax.php";
		var parametros = "docs=" + documentos;

		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
			ajax = new XMLHttpRequest(); // No Internet Explorer
		else
			ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax.onreadystatechange = funcionCallback_buscar;

		// Enviamos la peticion
		ajax.open( "POST", url, true );
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send(parametros);
	}

	//Callback de Vista previa
	function funcionCallback_buscar(){

	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
	      salida = ajax.responseText;
	     
	      if(salida){
	      	docs = JSON.parse(salida); 
		
		 	$.each(docs, function( index, value ) {
		 		$('#tabla_pendientes tr:last').after('<tr class="fila_pendiente" id="fila_' + docs[index].idDocumento + '"><td style="text-align: center;">' + docs[index].idDocumento + '</td><td style="text-align: center;">' + docs[index].NombreTipoDoc + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="icon_btn_' + docs[index].idDocumento + '"  onclick="eliminarDeLista(' + docs[index].idDocumento + ');"><i id="icon_' + docs[index].idDocumento + '"  class="fa fa-minus" aria-hidden="true"  title="Quitar de esta lista"></i></button></td></tr>');
		 	});
		 	 $("#aprobar").attr("disabled",false);

	      }else{
					 
	      	return false;
	      }
		  
		  OcultarCargando();
	    }
	   }
  }

  //Eliminar de la lista 
  function eliminarDeLista(i){

  	var fila = "#fila_" + i; 
  	var cant = $('#tabla_pendientes tr').length - 1;
  	
	if( cant == 1 )
  		$('#modal_pendientes').modal('hide');
  	else
  		$(fila).remove();
  	
  }

    //Aprobar los documentos que esten en la lista 
 $(".aprobar_aprobar_").click(function(){
 	
    if( $("#docs").val() != '' ){

        //Cantidad de filas 
       cant = $('#tabla_pendientes tr').length - 1;
       docs = $("#docs").val();
       indices = docs.split(","); 
       documento = indices[i];

       //Validar si desean firmar todo
       //var respuesta = confirm('Esta seguro(a) que desea aprobar estos documentos?');

      // if( respuesta ){
          MostrarCargando();
          proceso = setInterval(function(){ aprobar_(documento,i) }, 1000);
          $(".aprobar_aprobar_").attr("disabled",true);
       //}
    }	
});

 //Firmar un documento
 function aprobar_(idDocumento,i){

   clearInterval(proceso);// poner esto cuando llega respuesta

   var url = "Documentos_aprobar2_ajax.php";
   var parametros = "idDocumento=" + idDocumento;

   fila = i;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
   if( window.XMLHttpRequest )
      ajax = new XMLHttpRequest(); // No Internet Explorer
   else
      ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
      // Almacenamos en el control al funcion que se invocara cuando la peticion
   ajax.onreadystatechange = funcionCallback_aprobar_;

   // Enviamos la peticion
   ajax.open( "POST", url, false);
   ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   ajax.send(parametros);
 }

 //Callback de Vista previa
   function funcionCallback_aprobar_(){

     // Comprobamos si la peticion se ha completado (estado 4)
     if( ajax.readyState == 4 )
     {
       // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
       if( ajax.status == 200 )
       {
         // Escribimos el resultado en la pagina HTML mediante DHTML
         //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
         salida = ajax.responseText;
        
             if(salida){
                 mostrarEstadoAprobacion(salida,fila);
             }else{
               
                 return false;
             }
       }
      }
   }

 //Mostrar estado de Firma en el modal
 function mostrarEstadoAprobacion(salida, fila){

   //Incrementar la fila de la tabla a recorrer
   documento = indices[i];
   i++;

   //Actualizamos iconos segun la respuesta 

      //Si es otro error 
   if( salida == 0 ){

     $("#icon_" + documento).removeClass();
     $("#icon_" + documento).addClass('fa fa-exclamation-triangle text-warning');
     $("#icon_" + documento).prop('title','Intente nuevamente'); 
     $("#icon_btn_" + documento).removeAttr('onclick');
     
      OcultarCargando();

   }else {
     $("#icon_" + documento).removeClass();
     $("#icon_" + documento).addClass('fa fa-check-circle text-success');
     $("#icon_" + documento).prop('title','Aprobado');
     $("#icon_btn_" + documento).removeAttr('onclick');

     //Si termino de recorrer las filas 
     if( !(i < cant) ){
           //Ocultar el gif de cargando
           OcultarCargando();
     }
   }

   //Si quedan filas que recorrer 
   if( i < cant ){
       //Pasar al siguiente 
       documento = indices[i];
       proceso = setInterval(function(){ aprobar_(documento,i) }, 1000);
   }

   $("#modal_pendientes").modal("show");
 }

//Cerrar el modal 
$("#cerrar_aprobar").click(function(){

   //Limpian todos los campos 
   array = [];
   $("#docs").val('');
   $("#cantidad").val(0);
   $("#select").val(0);
   $(".checkbox").prop('checked',false);

   $("#modal_pendientes").modal('hide');
   $("#formulario3").submit();
});

//////////////////
/// RECHAZAR   ///
//////////////////

//Pasar los datos de los documentos a firmar al formulario 
   $("#SELECCION_MULTIPLE_R").click(function(){
   
       var documentos = $("#docs").val();
       var resultado = documentos.split(',');
       var j = resultado.length;
       
       if( documentos != '' ){
           MostrarCargando();
           //Limpiamos la tabla del modal 
           $(".fila_pendiente_r").remove();
           buscarDocumentos_r(documentos);
       }else{
           $("#SELECCION_MULTIPLE_R").attr("disabled",true);
       }

   }); 

   function buscarDocumentos_r(documentos){

       var url = "Documentos_aprobar1_ajax.php";
       var parametros = "docs=" + documentos;

       // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
       if( window.XMLHttpRequest )
           ajax = new XMLHttpRequest(); // No Internet Explorer
       else
           ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

       // Almacenamos en el control al funcion que se invocara cuando la peticion
       // cambie de estado 
       ajax.onreadystatechange = funcionCallback_buscar_r;

       // Enviamos la peticion
       ajax.open( "POST", url, true );
       ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       ajax.send(parametros);
   }

   //Callback de Vista previa
   function funcionCallback_buscar_r(){

     // Comprobamos si la peticion se ha completado (estado 4)
     if( ajax.readyState == 4 )
     {
       // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
       if( ajax.status == 200 )
       {
         // Escribimos el resultado en la pagina HTML mediante DHTML
         //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
         salida = ajax.responseText;
        
         if(salida){
             docs = JSON.parse(salida); 
       
            $.each(docs, function( index, value ) {
                $('#tabla_pendientes_r tr:last').after('<tr class="fila_pendiente_r" id="fila_r_' + docs[index].idDocumento + '"><td style="text-align: center;">' + docs[index].idDocumento + '</td><td style="text-align: center;">' + docs[index].NombreTipoDoc + '</td><td style="text-align: center;"><button style="background-color: transparent;" id="icon_btn_' + docs[index].idDocumento + '" class="btn btn-md" type="button" onclick="eliminarDeLista_r(' + docs[index].idDocumento + ');"><i id="icon_r_' + docs[index].idDocumento + '"  class="fa fa-minus" aria-hidden="true"  title="Quitar de esta lista"></i></button></td></tr>');
            });
             $(".rechazar_").attr("disabled",false);

         }else{
                         
             return false;
         }
         
         OcultarCargando();
       }
   }
 }


 //Eliminar de la lista 
 function eliminarDeLista_r(i){

     var fila = "#fila_r_" + i; 
     var cant = $('#tabla_pendientes_r tr').length - 1;
     
   if( cant == 1 )
         $('#modal_pendientes_r').modal('hide');
     else
         $(fila).remove();
     
 }

 //Aprobar los documentos que esten en la lista 
$(".rechazar_").click(function(){
    
    if( $("#docs").val() != '' ){

        //Cantidad de filas 
       cant = $('#tabla_pendientes_r tr').length - 1;
       docs = $("#docs").val();
       indices = docs.split(","); 
       documento = indices[i];

       //Validar si desean firmar todo
       var respuesta = confirm('Esta seguro(a) que desea rechazar estos documentos?');

       if( respuesta ){
          MostrarCargando();
          proceso = setInterval(function(){ rechazar_(documento,i) }, 1000);
          $(".rechazar_").attr("disabled",true);
       }
    }	
});

 //Firmar un documento
 function rechazar_(idDocumento,i){

   clearInterval(proceso);// poner esto cuando llega respuesta
   var obs = $("#observacion").val();
   var url = "Documentos_aprobar3_ajax.php";
   var parametros = "idDocumento=" + idDocumento + "&observacion=" + obs; //Rechazar

   fila = i;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
   if( window.XMLHttpRequest )
      ajax = new XMLHttpRequest(); // No Internet Explorer
   else
      ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
      // Almacenamos en el control al funcion que se invocara cuando la peticion
   ajax.onreadystatechange = funcionCallback_rechazar_;

   // Enviamos la peticion
   ajax.open( "POST", url, false);
   ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   ajax.send( parametros );
 }

 //Callback de Vista previa
   function funcionCallback_rechazar_(){

     // Comprobamos si la peticion se ha completado (estado 4)
     if( ajax.readyState == 4 )
     {
       // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
       if( ajax.status == 200 )
       {
         // Escribimos el resultado en la pagina HTML mediante DHTML
         //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
         salida = ajax.responseText;
        
             if(salida){
                 mostrarEstadoAprobacion_r(salida,fila);
             }else{
       
                 return false;
             }
       }
      }
   }

 //Mostrar estado de Firma en el modal
 function mostrarEstadoAprobacion_r(salida, fila){

   //Incrementar la fila de la tabla a recorrer
   documento = indices[i];
   i++;

   //Actualizamos iconos segun la respuesta 

      //Si es otro error 
   if( salida == 0 ){

     $("#icon_r_" + documento).removeClass();
     $("#icon_r_" + documento).addClass('fa fa-exclamation-triangle text-warning');
     $("#icon_r_" + documento).prop('title','Intente nuevamente'); 
     $("#icon_btn_" + documento).removeAttr('onclick');
     
      OcultarCargando();

   }else {
     $("#icon_r_" + documento).removeClass();
     $("#icon_r_" + documento).addClass('fa fa-check-circle text-success');
     $("#icon_r_" + documento).prop('title','Rechazado');
     $("#icon_btn_" + documento).removeAttr('onclick');

     //Si termino de recorrer las filas 
     if( !(i < cant) ){
           //Ocultar el gif de cargando
           OcultarCargando();
     }
   }

   //Si quedan filas que recorrer 
   if( i < cant ){
       //Pasar al siguiente 
       documento = indices[i];
       proceso = setInterval(function(){ rechazar(documento,i) }, 1000);
   }

   $("#modal_pendientes_r").modal("show");
 }

//Cerrar el modal 
$("#cerrar_rechazar").click(function(){

   //Limpian todos los campos 
   array = [];
   $("#docs").val('');
   $("#cantidad").val(0);
   $("#select").val(0);
   $(".checkbox").prop('checked',false);

   $("#modal_pendientes_r").modal('hide');
   $("#formulario3").submit();
});
   
/*Documentos_FirmaMasiva_huella.html*/


  //Eliminar documentos de la lista 
  function eliminarDoc(i){

    var cant_filas = document.getElementById('example').rows.length;
    var form = document.getElementById('formulario');
    var cant_docs = document.getElementById('cant_docs');
    
    if( cant_filas == 3 ){
      form.submit();
    }else if( cant_filas > 3){
      document.getElementById('fila_' + i).remove();
      var fil = 0;
      fil = document.getElementById('example').rows.length;
      cant_docs.innerHTML = fil - 2;
    }        
  }

  function mostrarMensaje(mensaje){
    var error  = document.getElementById('mensajeError');
    error.classList.add("callout callout-warning");
    error.innerHTML = mensaje;
  }

  function limpiarMensajesError(){
    var error  = document.getElementById('mensajeError');
    var clase = 'callout callout-warning';
    var clase_error = document.getElementsByClassName('mensajeError');

    if( clase_error === clase ){
      error.classList.remove("callout");
      error.classList.remove("callout-warning");
      error.innerHTML = '';
    }   
  }

  function firmar(){

    MostrarCargando();

    var btn_firmar = document.getElementById('FIRMAR_HUELLA');
    var cant_filas = document.getElementById('example').rows.length;
    var tabla      = document.getElementById('example');

    cant_firma = cant_filas - 2; 

    limpiarMensajesError();
    btn_firmar.disabled = true;

    //LLenar el arreglo de los indices de los documentos
    var n;
    for( n = 0; n < cant_filas; n++ ){     
      var indice = tabla.rows[n].id;
      var res = indice.split("_");
      if ( isInteger(res[1]) ){
         indices_firma.push(res[1]);
      }
    }

    //Se verifica si desea firmar
    var respuesta = confirm('Esta seguro(a) que desea firmar estos documentos?');

    if ( respuesta ){
      documento = document.getElementById('doc_' + indices_firma[i]).value;
      //Buscar el numero de auditoria
      verificarh_fm(documento,indices_firma[i]);
    }else{
      btn_firmar.disabled = false;
      return false;
    }
  }

  //Firma huella, va al ajax
  function firma_huella(idDocumento,i){

    clearInterval(proceso_firma);// poner esto cuando llega respuesta

    var usuarioid = document.getElementById('rut').value;
    var auditoria = document.getElementById('auditoria').value;

    var url = "Documentos_firmaMasiva6_ajax.php";
    var parametros ="idDocumento=" + idDocumento + "&usuarioid=" + usuarioid + "&auditoria=" + auditoria;

    fila_firma = i_firma;

     // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax_firma = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax_firma = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax_firma.onreadystatechange = funcionCallback_firma_huella;
    ajax_firma.addEventListener("load", transferComplete);

    // Enviamos la peticion
    ajax_firma.open( "POST", url, false);
    ajax_firma.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_firma.send(parametros);

    function transferComplete(evt) {
      mostrarEstadoFirma(salida_firma,fila_firma);
    }
  }

  function funcionCallback_firma_huella(){

    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_firma.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_firma.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_firma = '';
        salida_firma = ajax_firma.responseText;
      }
    }
  }

  //Mostrar estado de Firma en el modal
  function mostrarEstadoFirma(salida_firma, fila_firma){

    var icono = 'icon_' + fila_firma; 
    var clase_minus = "fa fa-minus";
    var clase_error = 'fa fa-exclamation-triangle text-warning';
    var clase_ok    = 'fa fa-check-circle text-success';
    var resultado = document.getElementById(icono);
 
    //Error de login
    var error = salida_firma.substring(0, 33);

    //Incrementar la fila de la tabla a recorrer
    i_firma++;

    //Actualizamos iconos segun la respuesta 
    //Si es otro error 
    if( salida_firma != 0 ){
    
      resultado.classList.remove("fa-minus");
      resultado.classList.add("fa-exclamation-triangle");
      resultado.classList.add("text-warning");
      resultado.title = salida_firma;
      resultado.onclick = '';

      //Si termino de recorrer las filas 
      if( !(i_firma < cant_firma) ){

          //Ocultar el gif de cargando
          OcultarCargando();
          document.getElementById('auditoria').value = '';


      }//Si no, que continue con los siguientes documentos 

    }else if (salida_firma){

      resultado.classList.remove("fa-minus");
      resultado.classList.add("fa-check-circle"); 
      resultado.classList.add("text-success");
      resultado.title = 'Firmado correctamente';
      resultado.onclick = '';

      //Si termino de recorrer las filas 
      if( !(i_firma < cant_firma) ){
        //Ocultar el gif de cargando
        OcultarCargando();
        document.getElementById('auditoria').value = '';
      }
      //Continua con los siguientes documentos 
    }

    //Si quedan filas que recorrer 
    if( i_firma < cant_firma ){
      //Pasar al siguiente
      documento_firma = document.getElementById('doc_' + indices_firma[i]).value;
      proceso_firma = setInterval(function(){ firma_huella(documento_firma,indices_firma[i],auditoria) }, 1000);
    }
  }

/*Documentos_FirmaMasiva_Listado.html*/

//Seleccionar todo
	$("#Seleccion_Listado").change(function(){

		var status = this.checked; 
		var array_aux = [];

		if ( $("#Seleccion_Listado").is(':checked')){

			//Limpian todos los campos 
			array_listado = [];
			$("#docs").val('');
			$("#cantidad").val(1);
			$("#select").val(1);

			//Vamos al ajax a buscar todos los documentos pendientes 
			proceso_listado = setInterval(function(){ buscarTodosDocumentosPorFirma() }, 100);

			$(".FIRMA_MASIVA_Listado").attr('disabled', false);
		}else{
			//Limpian todos los campos 
			array_listado = [];
			$("#docs").val('');
			$("#cantidad").val(0);
			$("#select").val(0);
			$(".checkbox").prop('checked',false);

		}
				
	});

	$('.checkbox').change(function(){
		//Agregar 
		if ( $(this).is(':checked') ){
			array_listado.push($(this).val());
		}
		//Eliminar
		else{
			var k = $(this).val();
			jQuery.each( array_listado, function( key, value ) {
			  if( k == value ){
			  	array_listado.splice(key, 1);
			  }
			});
		}
		//Pasar al formulario
		$("#docs").val(array_listado);

		if( $("#docs").val() != '' )
			$(".FIRMA_MASIVA_Listado").attr('disabled', false);
		else
			$(".FIRMA_MASIVA_Listado").attr('disabled', true);
	});

	
	
	//Vista previa del documento 
	$(".vista_listado").click(function(){ 
		
		var id = this.id; //console.log(id);
		var res = id.split('_'); //console.log(res);
		var i = res[1];//console.log(i);
		var idDocumento = $("#idDocumento_" + i).val();//console.log(idDocumento);
		id_doc = idDocumento;
		var url = "Documentos_aprobar4_ajax.php";
        var parametros  ="idDocumento=" + idDocumento; //Descargar Documento

        //console.log(url);
		 // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
		   ajax_listado = new XMLHttpRequest(); // No Internet Explorer
		else
		   ajax_listado = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
		
		   // Almacenamos en el control al funcion que se invocara cuando la peticion
		ajax_listado.onreadystatechange = funcionCallback_descargarDoc_listado;

		// Enviamos la peticion
		ajax_listado.open( "POST", url, false);
        ajax_listado.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax_listado.send(parametros);
	});
	
	//Callback de Vista previa
	function funcionCallback_descargarDoc_listado(){
	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax_listado.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax_listado.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      //document.all.salida_listado.innerHTML = "<b>"+ajax.responseText+"</b>"; 
	      salida_listado = ajax.responseText;

	      if(salida_listado){
	      	
			var ruta = salida_listado;
			$("#viewer").attr("src",ruta);
			$("#num").html('Documento : ' + id_doc);
			
	      }else{
	      	return false;
	      }
	    }
	  }
	}

	//Vista previa de firmantes 
	$(".firmantes").click(function(){

		fila_listado = this.id; 
		var resultado = fila_listado.split("_"); 
		var i = resultado[1];
	
		var idDocumento = $("#idDocumento_" + fila_listado).val();
		var url = "Documentos_firmaMasiva_ajax.php";
        var parametros = "idDocumento=" + i;

		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
		ajax_listado = new XMLHttpRequest(); // No Internet Explorer
		else
		ajax_listado = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax_listado.onreadystatechange = funcionCallback;

		// Enviamos la peticion
		ajax_listado.open( "POST", url, true );
        ajax_listado.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax_listado.send(parametros);
	});

	//Callback de Vista previa
	function funcionCallback(){
	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax_listado.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax_listado.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      //document.all.salida_listado.innerHTML = "<b>"+ajax_listado.responseText+"</b>"; 
	      salida_listado = ajax_listado.responseText;

	      if(salida_listado){
	      	verFirmantes(salida_listado);
	      }else{
	      	return false;
	      }
	    }
	  }
	}

	//Construir tabla de firmantes
	function verFirmantes(salida_listado){

		  respuesta_listado = salida_listado.split("|");
	      count_listado = 0;
	      count_listado = respuesta_listado.length;
	      j = 0; 
	      n = 0;
	      rep = Math.trunc(count_listado/5);

	      //Eliminar filas ateriores
	      $(".fila").remove();

	      while ( j < rep ){

		      nombre='';
		      rut='';
		      fecha='';

		      if( j != 0 ){
		      	  m = 4;
			      for (var i = n; i < m+n ; i++) {
			      	if( i == (n  ) ) rut    = respuesta_listado[i];
			      	if( i == (1+n) ) nombre = respuesta_listado[i];
			      	if( i == (3+n) ) fecha  = respuesta_listado[i];
			      }
			  }else{
			  	  for (var i = 0; i < 5 ; i++) {
			      	if( i == (n  ) ) rut    = respuesta_listado[i];
			      	if( i == (1+n) ) nombre = respuesta_listado[i];
			      	if( i == (3+n) ) fecha  = respuesta_listado[i];
			      }
			  }

		      $('#tabla_firm tr:last').after('<tr class="fila"><td>' + rut + '</td><td>' + nombre + '</td><td>' + fecha + '	</td></tr>');

		      if( j == 0) n = 5;
		      if( j == 1) n = 10;
		      if( j == 2) n = 15;
		      if( j == 3) n = 20; 
		      
		      j++;
	      }
	}

	//Pasar los datos de los documentos a firmar al formulario 
	$(".FIRMA_MASIVA_Listado").click(function(){
		$("#docs_form").val($("#docs").val());
		$("#formulario3").submit();
	}); 

	//Buscar todos los idDocumnto, de todos los pendientes por firma
	function buscarTodosDocumentosPorFirma(){

 		clearInterval(proceso_listado);// poner esto cuando llega respuesta

		var usuarioid = $("#usuarioid").val();
		var ptipousuarioid = $("#ptipousuarioid").val();
		var form = $("#formulario_usuarios").serialize();
		var idDocumento = $("#idDocumento").val();  //console.log(idDocumento);
		
		var url = "Documentos_firmaMasiva8_ajax.php";
        var parametros = "usuarioid=" + usuarioid + "&ptipousuarioid=" + ptipousuarioid + "&formulario=" + form + "&idDocumento=" + idDocumento;
console.log(url);
		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
		ajax_listado = new XMLHttpRequest(); // No Internet Explorer
		else
		ajax_listado = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax_listado.onreadystatechange = funcionCallback_buscarTodos_listado;

		// Enviamos la peticion
		ajax_listado.open( "POST", url, true );
        ajax_listado.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax_listado.send( parametros ) ;
	}

	//Callback de Vista previa
	function funcionCallback_buscarTodos_listado(){
	  // Comprobamos si la peticion se ha completado (estado 4)
	  if( ajax_listado.readyState == 4 )
	  {
	    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
	    if( ajax_listado.status == 200 )
	    {
	      // Escribimos el resultado en la pagina HTML mediante DHTML
	      //document.all.salida_listado.innerHTML = "<b>"+ajax.responseText+"</b>"; 
	      salida_listado = ajax_listado.responseText;
	     
	      if(salida_listado){
	      	docs_listado = JSON.parse(salida_listado); 
	     // 	console.log(docs);
	      	$.each(docs_listado, function( index, value ) { console.log(value);
			  array_listado.push(value);
			});
	      	$("#docs").val(array_listado);

	      	$(".checkbox").prop('checked','true');

	      }else{
	      	return false;
	      }
	    }
	  }
	}

    /*Documentos_FirmaMasiva_token.html*/
    
  //Eliminar documentos de la lista 
  function eliminarDoc_token(i){

    var cant_filas_token = document.getElementById('example').rows.length;
    var form = document.getElementById('formulario');
    var cant_docs_token = document.getElementById('cant_docs');
    
    if( cant_filas_token == 3 ){
      form.submit();
    }else if( cant_filas_token > 3){
      document.getElementById('fila_' + i).remove();
      var fil = 0;
      fil = document.getElementById('example').rows.length;
      cant_docs_token.innerHTML = fil - 2;
    }        
  }

  function mostrarMensaje(mensaje){
    var error  = document.getElementById('mensajeError');
    error.classList.add("callout callout-warning");
    error.innerHTML = mensaje;
  }

  function limpiarMensajesError(){
    var error  = document.getElementById('mensajeError');
    var clase = 'callout callout-warning';
    var clase_error = document.getElementsByClassName('mensajeError');

    if( clase_error === clase ){
      error.classList.remove("callout");
      error.classList.remove("callout-warning");
      error.innerHTML = '';
    }   
  }

  function firmar_token(){

    var btn_firmar = document.getElementById('FIRMATOKEN');
    var cant_filas_token = document.getElementById('example').rows.length;
    var tabla      = document.getElementById('example');
    var result = '';

    cant = cant_filas_token - 2; 

    limpiarMensajesError();
    btn_firmar.disabled = true;

    //LLenar el arreglo de los indices de los documentos
    var n;
    for( n = 0; n < cant_filas_token; n++ ){     
      var indice = tabla.rows[n].id;
      var res = indice.split("_");
      if ( isInteger(res[1]) ){
         indices.push(res[1]);
      }
    }

    //Se verifica si desea firmar
    var respuesta = confirm('Esta seguro(a) que desea firmar estos documentos?');

    if ( respuesta ){
      //Parametros necesarios
      uri = document.getElementById('uri').value;
      institucion = document.getElementById('institucion').value;    
      doccode = document.getElementById('doccode_' + indices[i]).value;
      //Firmar con autentia
      firmarToken_fm(uri, institucion, doccode, indices[i]);

    }else{
      btn_firmar.disabled = false;
      return false;
    }
  }

   //Firma token, va al ajax
  function firmar_token(i){

    var usuarioid = document.getElementById('rut').value;
    var doccode = document.getElementById('doccode_' + i).value;
    var idDocumento = document.getElementById('doc_' + i).value;

    var url = "Documentos_firmaMasiva7_ajax.php";
    var parametros  ="idDocumento=" + idDocumento + "&usuarioid=" + usuarioid + "&doccode=" + doccode;

    fila = i;

     // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax.onreadystatechange = funcionCallback_firma_token;
    ajax.addEventListener("load", transferComplete_token);

    // Enviamos la peticion
    ajax.open( "POST", url, false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);

    function transferComplete_token(evt) {
      mostrarEstadoFirma_firma(salida,fila);
    }
  }

  function funcionCallback_firma_token(){

    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida = '';
        salida = ajax.responseText;
      }
    }
  }

  //Mostrar estado de Firma en el modal
  function mostrarEstadoFirma_firma(salida, fila){

    var icono = 'icon_' + fila; 
    var clase_minus = "fa fa-minus";
    var clase_error = 'fa fa-exclamation-triangle text-warning';
    var clase_ok    = 'fa fa-check-circle text-success';
    var resultado = document.getElementById(icono);
 
    //Error de login
    var error = salida.substring(0, 33);

    //Incrementar la fila de la tabla a recorrer
    i++;

    //Actualizamos iconos segun la respuesta 
    //Si es otro error 
    if( salida != 0 ){
    
      resultado.classList.remove("fa-minus");
      resultado.classList.add("fa-exclamation-triangle");
      resultado.classList.add("text-warning");
      resultado.title = salida;
      resultado.onclick = '';

      //Si termino de recorrer las filas 
      if( i >= cant ){

          //Ocultar el gif de cargando
          OcultarCargando();
          document.getElementById('pin').value = '';
      }//Si no, que continue con los siguientes documentos 

    }else if (salida){

      resultado.classList.remove("fa-minus");
      resultado.classList.add("fa-check-circle"); 
      resultado.classList.add("text-success");
      resultado.title = 'Firmado correctamente';
      resultado.onclick = '';

      //Si termino de recorrer las filas 
      if( i >= cant ){
        //Ocultar el gif de cargando
        OcultarCargando();
        document.getElementById('pin').value = '';
      }
      //Continua con los siguientes documentos 
    }

    //Si quedan filas que recorrer 
    if( i < cant ){

      //Pasar al siguiente
      //Datos necesarios
      uri = document.getElementById('uri').value;
      institucion = document.getElementById('institucion').value;  
      doccode = document.getElementById('doccode_' + indices[i]).value;
   
      //Firma con autentia
      firmarToken_fm(uri, institucion, doccode, indices[i]);
    }
  }

  /*documentos_FormularioAgregar_A.html*/
  

 $(".equipa").on( 'change', function() {
    if( $(this).is(':checked') ) {
        // Hacer algo si el checkbox ha sido seleccionado
        if( contenido == '') {
          contenido += $(this).val();
        }
        else{
          contenido += ', ' + $(this).val();
        }
        $("#seleccion").val(contenido);
    } else {
         contenido = contenido.replace($(this).val()," ");
         contenido = $.trim(contenido);

         if(contenido.charAt(0)== ',' ){
           contenido =  contenido.substring(contenido.length,1);
         } 
         if(contenido.charAt(contenido.length-1) == ","){   
           contenido = contenido.substring(contenido.length-1, 0);
         }
         
         if(contenido.search(",  , ") != -1){
           sub = contenido.substring(contenido.length, contenido.search(",  , ") + 3 );
           contenido = contenido.substring(contenido.search(",  , "), 0 );
           contenido = contenido + sub;
         }

        $("#seleccion").val($.trim(contenido));
    } 
});



$(".deduci").on( 'change', function() {
   
    if( $(this).is(':checked') ) {

        i = $(this).attr("id");

        // Hacer algo si el checkbox ha sido seleccionado
        if( contenido_ded == '') {

          valor = $("#valor_" + i).val();

          if( valor.length > 0 ){
            nuevo = $(this).val() + " : " + valor;
            contenido_ded += nuevo;
          }
          else{
             $("#mensaje_deducibles").addClass("callout callout-warning");
             $("#mensaje_deducibles").html("Introduzca el valor correspondiente");
             $("#valor_"+ i).focus();
             $(this).attr('checked', false);
          }
          
        }
        else{

          valor = $("#valor_" + i).val();
           if( valor.length > 0 ){
              nuevo = $(this).val() + " : " + valor;
              contenido_ded += ', ' + nuevo;
            }
            else{
              $("#mensaje_deducibles").addClass("callout callout-warning");
              $("#mensaje_deducibles").html("Introduzca el valor correspondiente");
              $("#valor_" + i).focus();
              $(this).attr('checked', false);
            }
        }
        $("#seleccion_ded").val(contenido_ded);

    } else {
         i = $(this).attr("id");

         valor = $("#valor_" + i).val();
         contenido_ded = contenido_ded.replace($(this).val() + " : " + valor," ");
         contenido_ded = $.trim(contenido_ded);

         if(contenido_ded.charAt(0)== ',' ){
           contenido_ded =  contenido_ded.substring(contenido_ded.length,1);
         } 
         if(contenido_ded.charAt(contenido_ded.length-1) == ","){   
           contenido_ded = contenido_ded.substring(contenido_ded.length-1, 0);
         }
         
         if(contenido_ded.search(",  , ") != -1){
           sub_ded = contenido_ded.substring(contenido_ded.length, contenido_ded.search(",  , ") + 3 );
           contenido_ded = contenido_ded.substring(contenido_ded.search(",  , "), 0 );
           contenido_ded = contenido_ded + sub_ded;
         }

        $("#seleccion_ded").val($.trim(contenido_ded));
    } 
});

//Validar los tipo Number 
  $(".FIRMANTES_A").click(function(){
   //Validar los datos del Proyecto
    if($("#idProyecto").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#idProyecto').focus();
        return false;
    }
    //Datos del Anexo 
    if($("#	Firma").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#	Firma').focus();
        return false;
    }
    if($("#Marca").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#Marca').focus();
        return false;
    }
    if($("#Modelo").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#Modelo').focus();
        return false;
    }
    if($("#CiudadEntrega").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#CiudadEntrega').focus();
        return false;
    }
    if($("#CiudadOperacion").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#CiudadOperacion').focus();
        return false;
    }
     if($("#CiudadDevolucion").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#CiudadDevolucion').focus();
        return false;
    }
    if ($("#PeriodoArriendo").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El Periodo de arriendo no puede estar vac&iacute;o");
        $('#PeriodoArriendo').focus();
        return false;
    }
   /* if ($("#	Inicio").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione 	 Inicio para continuar");
        $('#	Inicio').focus();
        return false;
    }
    if ($("#	Fin").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione 	 Inicio para continuar");
        $('#	Fin').focus();
        return false;
    }*/
    if ($("#Monedas").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
        $('#Monedas').focus();
        return false;
    }
    if ($("#Tarifa").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Introduzca el monto de Tarifa correspondiente");
        $('#Tarifa').focus();
        return false;
    }
    if ($("#Exceso").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
        $('#Exceso').focus();
        return false;
    }
    if ($("#KmsExceso").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Introduzca el monto de kms de exceso correspondiente");
        $('#KmsExceso').focus();
        return false;
    }
    if ($("#KmsContratados").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no puede ser 0");
        $('#KmsContratados').focus();
        return false;
    }
   if ( $("#FrecuenciaMantencion").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no puede ser 0");
        $("#FrecuenciaMantencion").focus();
        return false;
    }
    if ( $("#FrecuenciaCambio").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no puede ser 0");
        $("#FrecuenciaCambio").focus();
        return false;
    }
    if ( $("#Cantidad").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no puede ser 0");
        $("#Cantidad").focus();
        return false;
    }
    //Datos variables
    if ( $("#Porcentaje").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El Porcentaje no puede estar vac&iacute;o");
        $("#Porcentaje").focus();
        return false;
    }
    if ( $("#Propuesta").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El Propuesta no puede estar vac&iacute;o");
        $("#Propuesta").focus();
        return false;
    }
    if ( $("#	Propuesta").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Selecciona la 	 de Propuesta para continuar");
        $("#	Propuesta").focus();
        return false;
    }

    if ( $("#GPS").val() == 0 ){
        respuesta = confirm("No ha completado el valor del GPS, Desea continuar?");
        if ( respuesta == false ){
           $("#GPS").focus();
           return false;
        }
    }

    if ( $("#seleccion").val() == "Seleccione..." ){
        respuesta = confirm("No ha seleccionado ningun Equipamientos, Desea continuar?");
        if ( respuesta == false ){
           $("#btn_equipamientos").focus();
           return false;
        }
        else{
          $("#seleccion").val("");
        }
    }

    if ( $("#seleccion_ded").val() == "Seleccione..." ){
         respuesta = confirm("No ha seleccionado ningun Deducible, Desea continuar?");
        if ( respuesta == false ){
           $("#btn_deducibles").focus();
           return false;
        }
        else{
          $("#seleccion_ded").val("");
        }
    }

    if ( $("#rut_coordinador").val() == 0 && $("#nombre_coordinador").val() == 0){
        respuesta = confirm("El campo Rut y Nombre del Coordinador estan vacios, desea continuar?");
        if ( respuesta == false ){
           $("#rut_coordinador").focus();
           return false;
        }
    }  
});

//Validar Rut 
 $("#rut_coordinador").change(function(){

    if ( $("#rut_coordinador").val().length > 0 ){

      var respuesta = validaRut($("#rut_coordinador").val());

      if( respuesta == false ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El Rut del Coordinador no es un rut v&aacute;lido");
        $("#rut_coordinador").focus();
        return false;
      }
      else{
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
      }
  }
});


  $("#btn_equipamientos").click(function(){
     $(this).find('i').toggleClass('fa-minus');
  });

  $("#btn_deducibles").click(function(){
     $(this).find('i').toggleClass('fa-minus');
  });

  //Calcular 	 de Entrega

   $(".fechaInicio_a").change(function() {

      	Inicio = $("#	Inicio").val(); //22-06-2018
      	Array = 	Inicio.split(separador); //[0=>22, 1=>06, 2=>2018]
      dia = parseInt(	Array[0]) - 1;
      mes = parseInt(	Array[1]);
      anno = parseInt(	Array[2]);

     //Calculo 
     if( $("#PeriodoArriendo").val().length > 0 ){
        //Calculo 
        while( mesesArriendo > 12 ){
          //Sumo al año
          anno += 1;
          //Resto a la variable
          mesesArriendo -= 12;
        }
    
        if( ( mes + mesesArriendo ) > 12){
          anno++;
          mes = ( mes + mesesArriendo ) - 12;
        }
        else{
          mes += mesesArriendo;
        }
        //alert(dia);
        if( dia == 0 ){

          //Si el mes tiene 31 Dias 
          if( (mes == 1) || (mes == 3) || (mes== 5) || (mes == 7) || (mes == 8) || (mes == 10) || (mes == 12)){
            dia = 31;
            mes-=1;
            //alert(dia + " 31 dias");
            if( mes == 0 ){
              mes = 12;
            }
          
          }else{

              //Si el mes tiene 30 Dias
              if( (mes == 4) || (mes == 6) || (mes == 9) || (mes == 11 )){
                dia = 30;
                mes-=1;
                //alert(dia + " 30 dias");
                if( mes == 0 ){
                  mes = 12;
                }
              }
              else{
                //Si es Febrero
                if( mes == 2 ){
                  //Si es año bisiesto
                  if ( ( anno % 100 != 0) && ((anno % 4 == 0) || (anno % 400 == 0))) {
                    bis = 1;
                    dia = 29;
                    //mes-= 1;
                  }
                  else {
                    bis = 0;
                    dia = 28;
                    //mes-=1;
                  }
                }
              }
            }
          
        }

        if ( mes > 9 ){
          //Asigno la nueva 	 Final 
          if( dia < 10 ){
             $("#	Final").val($.trim("0" + dia + "-" + mes + "-" + anno)); 
          }
          else{
             $("#	Final").val($.trim(dia + "-" + mes + "-" + anno));
          }
         
        }
        else{
          //Asigno la nueva 	 Final
          if( dia < 10 ){
              $("#	Final").val($.trim("0"+ dia + "-0" + mes + "-" + anno));
          }
          else{
              $("#	Final").val($.trim(dia + "-0" + mes + "-" + anno));
          }
        }
     }
    else{
      $("#	Final").val(" ");
     }     
  }); 
  
  $("#PeriodoArriendo").change(function() { 
       //Limpia el campo
      //$("#	Final").reset();
      	Inicio = "";
      mesesArriendo = 0;
      dia = 0;
      mes = 0;
      anno = 0;

      mesesArriendo = parseInt($("#PeriodoArriendo").val());

      alert($("#KmsMensuales").val());

      calculo = parseInt($("#KmsMensuales").val()) * mesesArriendo;

      //Calcula los KmsContratado
      $("#KmsContratados").val(calculo);

      	Inicio = $("#	Inicio").val(); //22-06-2018
      	Array = 	Inicio.split('-'); //[0=>22, 1=>06, 2=>2018]
      dia = parseInt(	Array[0])-1;
      mes = parseInt(	Array[1]);
      anno = parseInt(	Array[2]);
      bis = 0;
          
    if( $("#	Inicio").val().length > 0 ){
        //Calculo 
        while( mesesArriendo > 12 ){
          //Sumo al año
          anno += 1;
          //Resto a la variable
          mesesArriendo -= 12;
        }
    
        if( ( mes + mesesArriendo ) > 12){
          anno++;
          mes = ( mes + mesesArriendo ) - 12;
        }
        else{
          mes += mesesArriendo;
        }
        //alert(dia);
        if( dia == 0 ){

          //Si el mes tiene 31 Dias 
          if( (mes == 1) || (mes == 3) || (mes== 5) || (mes == 7) || (mes == 8) || (mes == 10) || (mes == 12)){
            dia = 31;
            mes-=1;
            //alert(dia + " 31 dias");
            if( mes == 0 ){
              mes = 12;
            }
          
          }else{

              //Si el mes tiene 30 Dias
              if( (mes == 4) || (mes == 6) || (mes == 9) || (mes == 11 )){
                dia = 30;
                mes-=1;
                //alert(dia + " 30 dias");
                if( mes == 0 ){
                  mes = 12;
                }
              }
              else{
                //Si es Febrero
                if( mes == 2 ){
                  //Si es año bisiesto
                  if ( ( anno % 100 != 0) && ((anno % 4 == 0) || (anno % 400 == 0))) {
                    bis = 1;
                    dia = 29;
                    //mes-= 1;
                  }
                  else {
                    bis = 0;
                    dia = 28;
                    //mes-=1;
                  }
                }
              }
            }
          
        }

        if ( mes > 9 ){
          //Asigno la nueva 	 Final 
          if( dia < 10 ){
             $("#	Final").val($.trim("0" + dia + "-" + mes + "-" + anno)); 
          }
          else{
             $("#	Final").val($.trim(dia + "-" + mes + "-" + anno));
          }
         
        }
        else{
          //Asigno la nueva 	 Final
          if( dia < 10 ){
              $("#	Final").val($.trim("0"+ dia + "-0" + mes + "-" + anno));
          }
          else{
              $("#	Final").val($.trim(dia + "-0" + mes + "-" + anno));
          }
        }
     }
    else{
      $("#	Final").val(" ");
     }     
  });

  //Si cambian los Kms mensuales
   $("#KmsMensuales").change(function(){
       mesesArriendo = parseInt($("#PeriodoArriendo").val());
       if ( mesesArriendo > 0 ){
         calculo = parseInt($("#KmsMensuales").val()) * mesesArriendo;
          //Calcula los KmsContratado
          $("#KmsContratados").val(calculo);
        }
   });

   //Si click en borrar, limpiar los campos 
   $("#borrar_e").click(function(){
      $("#seleccion").val("");
      $(".equipa").prop("checked",false);
      contenido = "";
   });
   $("#borrar_d").click(function(){
      $("#seleccion_ded").val("");
      $(".deduci").prop("checked",false);
      $(".valor").val("");
      contenido_ded="";
   });

   /*documentos_FormularioAgregar_CM.html */

   $(".FIRMANTES_CM").click(function(){
    //	 de Inicio
    if( $('#fechaInicio').val().length == 0 ){
       $("#mensajeError").addClass("callout callout-warning");
       $("#mensajeError").html("Debe seleccionar la 	 de Inicio");
       return false;
    }
    //	 de Fin 
    if($('#	Fin').val().length == 0){
       $("#mensajeError").addClass("callout callout-warning");
       $("#mensajeError").html("Debe seleccionar la 	 de Fin");
       return false;
    }
    
    //Formas de Pago
    if($("#FormasPago").val().length == 0){
       $("#mensajeError").addClass("callout callout-warning");
       $("#mensajeError").html("Debe seleccionar la forma de pago");
      return false;
    }

    /*
      Consulta que tipo de empresa es, los proyectos se generaran, 
      solo si son empresas Clientes, es decir empresas tipo 2
    */
    if ( $("#TipoEmpresa").val() == 2 ){ 
       //Confirmar si quieren generara automaticamente un Proyecto
      //respuesta = confirm( " Desea generar un Negocio con este Cliente ?");
      
     // if( respuesta == true ){
     //   $("#pro").val("1");
     // }
     // else{
        $("#pro").val("0");
     // }
    }
   
  });

  /*documentos_FormularioAgregar_CR.html*/

  //Validar los tipo Number 
  $(".FIRMANTES_CR").click(function(){
    //Validar los datos del Proyecto
     if($("#FormasPago").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#FormasPago').focus();
         return false;
     }
     if($("#Marca").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#Marca').focus();
         return false;
     }
     if($("#Modelo").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#Modelo').focus();
         return false;
     } 
     if($("#Patente").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#Patente').focus();
         return false;
     }
     if($("#Color").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#Color').focus();
         return false;
     }
     if($("#VIN").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#VIN').focus();
         return false;
     }
      if($("#Anno").val().length == 0){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no debe estar vacio");
         $('#Anno').focus();
         return false;
     }
     if ($("#PeriodoArriendo").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("El Periodo de arriendo no puede estar vac&iacute;o");
         $('#PeriodoArriendo').focus();
         return false;
     }
     if ($("#KmsMensuales").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Campo no puede ser 0");
         $('#KmsMensuales').focus();
         return false;
     }
     if ($("#	Inicio").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione 	 Inicio para continuar");
         $('#	Inicio').focus();
         return false;
     }
     if ($("#	Fin").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione 	 Inicio para continuar");
         $('#	Fin').focus();
         return false;
     }
     if ($("#fechaPago").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione 	 Inicio para continuar");
         $('#fechaPago').focus();
         return false;
     }
     if ($("#fechaPie").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione 	 Inicio para continuar");
         $('#fechaPie').focus();
         return false;
     }
     if ($("#CuotaPie").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#CuotaPie').focus();
         return false;
     }
     if ($("#Monto_CuotaPie").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#Monto_CuotaPie').focus();
         return false;
     }
     if ($("#Exceso").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#Exceso').focus();
         return false;
     }
     if ($("#KmsExceso").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#KmsExceso').focus();
         return false;
     }
     if ($("#RentaMens").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#RentaMens').focus();
         return false;
     }
     if ($("#Monto_RentaMens").val() == 0 ){
         $("#mensajeError").addClass("callout callout-warning");
         $("#mensajeError").html("Seleccione Tipo de Moneda para continuar");
         $('#Monto_RentaMens').focus();
         return false;
     }
   });

   /*templates\documentos_FormularioAgregar_F.html*/
   function InicioFormularioF(){

    if( $("#Proveedor").val().length == 1 ){
      $("#Proveedor").val("(Seleccione)");  
    }

    if( $("#rut_p_0").val().length != 0 ){
      $('#tabla_proveedores tr:last').after('<tr id="fila_0" style="text-align:center;"><td>'+ $("#rut_p_0").val() + '</td><td>'+ $("#nombre_p_0").val() + '</td><td>' + $("#detalle_p_0").val() +'</td><td>' + $("#monto_p_0").val() + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="borrar_0" ><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>');
    }

    if( $("#rut_p_1").val().length != 0 ){
      $('#tabla_proveedores tr:last').after('<tr id="fila_1" style="text-align:center;"><td>'+ $("#rut_p_1").val() + '</td><td>'+ $("#nombre_p_1").val() + '</td><td>' + $("#detalle_p_1").val() +'</td><td>' + $("#monto_p_1" ).val() + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="borrar_1"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>');
    }

    if( $("#rut_p_2").val().length != 0 ){
      $('#tabla_proveedores tr:last').after('<tr id="fila_2" style="text-align:center;"><td>'+ $("#rut_p_2").val() + '</td><td>'+ $("#nombre_p_2").val() + '</td><td>' + $("#detalle_p_2").val() +'</td><td>' + $("#monto_p_2").val() + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="borrar_2"  ><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>');
    }

      //Borrar primera fila 
    $("#borrar_0").click(function(){

      //Remover la fila 
      $("#fila_0").remove();

      //Limpiar el campo
      $("#rut_p_0").val("");
      $("#nombre_p_0").val("");
      $("#detalle_p_0").val("");
      $("#monto_p_0").val("");

      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
     });
 
    $("#borrar_1").click(function(){

      //Remover la fila 
      $("#fila_1").remove();

      //Limpiar el campo
      $("#rut_p_1").val("");
      $("#nombre_p_1").val("");
      $("#detalle_p_1").val("");
      $("#monto_p_1").val("");

      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
    });

    $("#borrar_2").click(function(){

      //Remover la fila 
      $("#fila_2").remove();
    
      //Limpiar el campo
      $("#rut_p_2").val("");
      $("#nombre_p_2").val("");
      $("#detalle_p_2").val("");
      $("#monto_p_2").val("");
 
      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
    });

  };

  //Validar los tipo Number 
  $(".FIRMANTES_F").click(function(){

   //Validar los datos del Proyecto
   if($("#rut_p_0").val().length == 0 && $("#rut_p_1").val().length == 0 && $("#rut_p_2").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar al menos un Proveedor");
        $("#Proveedor").focus();
        return false;
    }
    if($("#fechaInicioPago").val().length == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#fechaInicioPago').focus();
        return false;
    } 
    if($("#Moneda").val() == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione para continuar");
        $('#Moneda').focus();
        return false;
    }
    if($("#CantTotal").val() == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#CantTotal').focus();
        return false;
    }
     if($("#CantRentas").val() == 0){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#CantRentas').focus();
        return false;
    }
    if ($("#ValorBallon").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#ValorBallon').focus();
        return false;
    }
    if ($("#ValorCompra").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#ValorCompra').focus();
        return false;
    }
   /* if ($("#DiaPagoMensual").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#DiaPagoMensual').focus();
        return false;
    }*/
    if ($("#DuracionContrato").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#DuracionContrato').focus();
        return false;
    }
    if ($("#fechaPrepago").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#fechaPrepago').focus();
        return false;
    }
    if ($("#ValorAsegurable").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#ValorAsegurable').focus();
        return false;
    }
    if ($("#ValorIguales").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Campo no debe estar vacio");
        $('#ValorIguales').focus();
        return false;
    }
  });

  $("#btn_proveedores").click(function(){

   if($("#rut_p_0").val().length > 0 ){

      if($("#rut_p_1").val().length > 0 ){
        fila = 2;
      }else{
        fila = 1;
      }
   }
   else{
      fila = 0;
   }

   if ( $("#tabla_proveedores tr").length < 4){

      if ( $("#Proveedor").val() != "(Seleccione)" && $("#DetalleBienes").val().length > 0 && $("#MontoAdquisicion").val().length > 0 ){
        $('#tabla_proveedores tr:last').after('<tr id="fila_' + fila + '" style="text-align:center;"><td>' + $("#RutProveedor").val() + '</td><td>' + $("#RazonSocial").val() + '</td><td>' + $("#DetalleBienes").val() +'</td><td>'+ $("#MontoAdquisicion").val() + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="borrar_' + fila + '" ><i class="fa fa-trash-o" aria-hidden="true"></i></button> </td></tr>');
        $("#rut_p_" + fila).val($("#RutProveedor").val());
        $("#nombre_p_" + fila).val($("#RazonSocial").val());
        $("#detalle_p_" + fila).val($("#DetalleBienes").val());
        $("#monto_p_" + fila).val($("#MontoAdquisicion").val());
        fila++;
      } 
    }
    else{
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("No puede agregar m&aacute;s Proveedores para este documento");
    }

    if ( $("#tabla_proveedores tr").length == 4 ){
      $("#btn_proveedores").prop("disabled", true);
      $("#btn_proveedores").prop("title", "Solo puede agregar hasta tres (3) Proveedores al Documento");
      $("#Proveedor").val("(Seleccione)");  
      $("#RutProveedor").val("");
      $("#RazonSocial").val("");
      $("#DetalleBienes").val("");
      $("#MontoAdquisicion").val("");
      $("#btn_proveedor").prop("disabled",true);

    }
    
    //Borrar primera fila 
    $("#borrar_0").click(function(){

      //Remover la fila 
      $("#fila_0").remove();

      //Limpiar el campo
      $("#rut_p_0").val("");
      $("#nombre_p_0").val("");
      $("#detalle_p_0").val("");
      $("#monto_p_0").val("");

      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
    });

    $("#borrar_1").click(function(){

      //Remover la fila 
      $("#fila_1").remove();

      //Limpiar el campo
      $("#rut_p_1").val("");
      $("#nombre_p_1").val("");
      $("#detalle_p_1").val("");
      $("#monto_p_1").val("");

      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
    });

    $("#borrar_2").click(function(){

      //Remover la fila 
      $("#fila_2").remove();
    
      //Limpiar el campo
      $("#rut_p_2").val("");
      $("#nombre_p_2").val("");
      $("#detalle_p_2").val("");
      $("#monto_p_2").val("");
 
      if ($("#tabla_proveedores tr").length < 4 ){
        $("#btn_proveedores").prop("disabled", false);
        $("#btn_proveedor").prop("disabled",false);
      }
    });

  });

  /*templates\documentos_FormularioAgregar.html*/

  
function documentosFormularioAgregar(){

    //Empresa
    if( $("#RutEmp").val().length < 3 ){
      $("#btn_cliente").prop("disabled",true);
      $("#SIGUIENTE").prop("disabled",true);
      $("#modelo_contrato").prop("disabled",true);
    }
    else{
      $("#btn_cliente").prop("disabled",false);
      $("#SIGUIENTE").prop("disabled",false);
      $("#modelo_contrato").prop("disabled",false);
    }

    //Cliente
    if( $("#RutCli").val().length < 3 ){
      $("#SIGUIENTE").prop("disabled",true);
      $("#modelo_contrato").prop("disabled",true);
    }
    else{
      $("#modelo_contrato").prop("disabled",false);
      $("#SIGUIENTE").prop("disabled",false);
    }

    //Modelo de Contrato
    if( $("#modelo_contrato").val() == 0 ){
       $("#SIGUIENTE").prop("disabled",true);
    }
    else{
      $("#SIGUIENTE").prop("disabled",false);
    }

    if( $("#modelo_contrato").val().length == 0 ){
      $("#modelo_contrato option:selected").text("(Seleccione)");  
    }

    if( $("#RutEmp").val().length == 2){
       $("#RutEmp").val("(Seleccione)");  
    }

    if( $("#RutCli").val().length ==  1 ){
       $("#RutCli").val("(Seleccione)");  
    }

    if( $("#FirEmpresa").val() == 1){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("La Empresa seleccionada no tiene Firmantes asociados");
      $("#btn_empresa").focus();
      $("#SIGUIENTE").prop("disabled",true);
    }

    if( $("#FirEmpresaC").val() == 1){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("La Empresa Cliente seleccionada no tiene Firmantes asociados");
      $("#btn_cliente").focus();
      $("#SIGUIENTE").prop("disabled",true);
    }

    if( $("#PlaEmpresa").val() == 1){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("La Empresa seleccionada no tiene Plantillas");
      $("#btn_empresa").focus();
      $("#SIGUIENTE").prop("disabled",true);
    }

    //Si hay algun error, dehabilitar el boton de siguiente
   if( $("#mensajeError").html().length > 0 ){
      $("#SIGUIENTE").prop("disabled",true);
   }

   if( $("#modelo_contrato").val() != 0 ){
       //Verificar si hay Contrato Marco generado

      if ( $("#modelo_contrato").val() == 2 && $("#RutEmpresaC").val().length > 0){

        var url = "Documentos_ajax.php";
        var parametros = "RutEmpresaC=" + $("#RutEmpresaC").val();

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback;

        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);

      }

      //Verificar si hay Contrato de Renting generado
      if ( $("#modelo_contrato").val() == 4 && $("#RutEmpresaC").val().length > 0){
        var url = "Documentos_ajax2.php";
        var parametros = "RutEmpresaC=" + $("#RutEmpresaC").val();

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback;

        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);

      } 
   }

  };

  //Tipo de Documento
  $("#modelo_contrato").change(function(){
      //Tipo de Documento
      if( $("#modelo_contrato").val() == 0 ){
         $("#SIGUIENTE").prop("disabled",true);
      }
      else{
        $("#SIGUIENTE").prop("disabled",false);
      }

      //Verificar si hay Contrato Marco generado
      if ( $("#modelo_contrato").val() == 2 && $("#RutEmpresaC").val().length > 0){
        var url = "Documentos_ajax.php";
        var parametros  ="RutEmpresaC=" + $("#RutEmpresaC").val();

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback;

        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);

      }

      //Verificar si hay Contrato de Renting generado
      if ( $("#modelo_contrato").val() == 4 && $("#RutEmpresaC").val().length > 0){
        var url = "Documentos_ajax2.php";
        var parametros = "RutEmpresaC=" + $("#RutEmpresaC").val();

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_documentosfa;

        // Enviamos la peticion
        ajax.open( "POST", url, true );
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);

      } 

  }); 

  //Variables Globales 
  var ajax;
  var i = "";

  function funcionCallback_documentosfa(){
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
        salida = ajax.responseText;

         if( salida.length > 0 ){
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html(salida);
            $("#SIGUIENTE").prop("disabled",true);
            $("#modelo_contrato").focus();
          }
         
      }
    } else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
          }
  }

 /*documentos_vigentes_Detalle.html*/
 function renotificar($RutUsuario){
    //alert($('#idDocumento').val());
    var url  = "renotificarDocumentos_ajax.php";
    var parametros = "idDocumento=" + $('#idDocumento').val() + ($RutUsuario != '' ? "&RutUsuario=" + $RutUsuario : '');

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajaxRenotificar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajaxRenotificar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajaxRenotificar.onreadystatechange = funcionCallback_renotificar;
    // Enviamos la peticion
    ajaxRenotificar.open( "POST", url, false );
    ajaxRenotificar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajaxRenotificar.send(parametros);
}

function funcionCallback_renotificar()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxRenotificar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajaxRenotificar.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajaxRenotificar.responseText;
            listado = JSON.parse(salida);
            console.log(listado);
            if (listado.estado){
                $("#mensajeOK").addClass("callout callout-success");
                $("#mensajeOK").html("Las notificaciones se realizaron exitosamente");
            }
            else{
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("Hubo un error intente mas tarde");
            }
            /*
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#lugarpagoid').append('<option class="fila_lp" value=' + listado[index].lugarpagoid + '>' + listado[index].nombrelugarpago + ' </option>');    
                });
                $("#lugarpagoid").attr("disabled", false);
            }*/
        }
    }
}

/*templates\documentosxperfil_Listado.html*/

function TipoUsuarioSel()
{
    document.formulario.tipousuarioid.value = document.form1.tipousuarioid.options[document.form1.tipousuarioid.selectedIndex].value;
}


function irtipousuarios()
{
    

    var tipousuarioid = document.getElementById('tipousuarioid').value;
    document.getElementById('tipousuarioidx').value = document.getElementById('tipousuarioid').value;
    document.getElementById('holdingidx').value 	= document.getElementById('holdingid').value;
    
    if (document.getElementById('tipousuarioid').value == '')
    {	
        document.getElementById('accion').value = 'BUSCAR';
    }else{
        document.getElementById('accionx').value = 'MODIFICAR';
    }
    
    document.formulario3.submit();				
}

/*templates\usuariosmant_Listado.html*/
$('#buscar').keypress(function (e) {
    var key = e.which;
    if (key == 13) {
        return false;
    }
});

$('#btn-buscar').click(function () {
    $("#nombrex").val($("#buscar").val());
    $("#pagina").val(1);
    $("#paginado").submit();
});

/*templates\usuariosmant_FormularioModificar.html*/

function iraccesoxusuario()
{
    formulario2.submit();
}
function validarRutEmpresa() {

    if ($("#RutEmpresa").val() == 0) {
        $("#mensajeError").html("Debe seleccionar");
        $("#mensajeError").addClass("callout callout-warning");
        $("#RutEmpresa").focus();
        return false;
    } else {
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        return true;
    }
}

/*templates\tiposusuarios_FormularioModificar.html*/

function irdocumentosxperfil()
{
	document.getElementById('perfildoc').value = document.getElementById('tipousuarioidx').value;
	formulario2.submit();
}

function iraccesoxperfil()
{
	document.getElementById('perfilacc').value = document.getElementById('tipousuarioidx').value;
	formulario3.submit();
}

/*templates\rl_proveedores_Listado.html*/


function volver()
{
   importacion.submit();
}

 function seleccion(rutproveedor,nombreproveedor)
{
    $("#RutProveedor").val(rutproveedor);
    $("#NombreProveedor").val(nombreproveedor);
   importacion.submit();
}

function graba_proveedor()
{
   resultado = ajax_graba_proveedor( $("#rutempresa_m").val(), $("#razonsocial_m").val(), $("#direccion_m").val(), $("#comuna_m").val(), $("#ciudad_m").val() )
   if (resultado != "ERROR")
   {
       var rut = $("#rutempresa_m").val();
       var nom = $("#razonsocial_m").val();
       $("#RutProveedor").val(rut);
       $("#NombreProveedor").val(nom);
       
       $('#modal_creaproveedor').modal('hide');
       
       $("#rutempresa_m").val(''); 
       $("#razonsocial_m").val(''); 
       $("#direccion_m").val(''); 
       $("#comuna_m").val(''); 
       $("#ciudad_m").val(''); 
       
       $("#RutProveedor").val(rut);
       $("#NombreProveedor").val(nom);
       importacion.submit();
   }		
}

$("#rutempresa_m").focusout(function(){
   resultado = ajax_buscar_proveedor( $("#rutempresa_m").val() );
   if (resultado != 'ERROR' && resultado != '')
   {
       $("#razonsocial_m").val(resultado.NombreProveedor); 
       $("#direccion_m").val(resultado.Direccion); 
       $("#comuna_m").val(resultado.Comuna); 
       $("#ciudad_m").val(resultado.Ciudad); 
   }
});

/*templates\rl_proveedores_FormularioAgregar.html*/
 //Buscar datos de la persona, si existe
 $("#RutProveedor").change(function(){
    var respuesta = validaRut2(document.formulario_proveedor.RutProveedor); 
    if( respuesta ){
     buscarDatosCliente($("#RutEmpresa").val(),$("#RutProveedor").val()); 
    }
  });

  /*templates\rl_proveedores_Firmantes_FormularioAgregar.html*/
  //Buscar datos de la persona, si existe
$(".RutUsuario_RLFFA").change(function(){
    var respuesta = validaRut2(document.formulario.RutUsuario);
    if( respuesta ){
        buscarDatosEmpleado($(".RutUsuario_RLFFA").val());
    }
});

/*templates\rl_flujoPorEnte_FormularioModificar.html*/
/*templates\flujofirma_FormularioModificar.html*/

function eliminaEstado(idestadowf)
{
    //alert (idestadowf);

    document.getElementById("idestadowf").value = idestadowf;
    
    if (!confirm("Esta seguro de eliminar?"))
    {
        return false;
    }
    
    return true;
}

/*templates\plantillasPorEmpresas_FormularioModificar.html*/

function TipoUsuarioSel()
{
    document.formulario.tipousuarioid.value = document.form1.tipousuarioid.options[document.form1.tipousuarioid.selectedIndex].value;
}


function iraPlantillasDisponibles()
{
    
    var tipousuarioid = document.getElementById('tipousuarioid').value;
    document.getElementById('tipousuarioidx').value = document.getElementById('tipousuarioid').value;
    document.getElementById('holdingidx').value 	= document.getElementById('holdingid').value;
    
    if (document.getElementById('tipousuarioid').value == '')
    {	
        document.getElementById('accion').value = 'BUSCAR';
    }else{
        document.getElementById('accionx').value = 'MODIFICAR';
    }
    
    document.formulario3.submit();				
}

function asignarRutEmpresa(i){
    var rut = document.getElementById("RutEmpresa").value;
    var id = "RutEmpresa_aux_" + i;
    document.getElementById(id).value = rut;
    return true;
}

/*plantillasPorEmpresas_FormularioModificar.html*/


function InicioPlantillasPorEmpresasMod(){
    var lugarpagoid = $(".lugarpagoid_PEMod").val();
    var RutEmpresa = $(".RutEmpresa_PEMod").val();
    var centrocostoid = $(".idCentroCosto_PEMod").val();
    //console.log(RutEmpresa);
    if( RutEmpresa != '' ) {
        $(".lugarpagoid_PEMod").attr("disabled",false);
    }
    if( RutEmpresa == 0 ){
        $(".lugarpagoid_PEMod").attr("disabled",true);
    }
    if ( lugarpagoid != 0 ){
        $(".idCentroCosto_PEMod").attr("disabled", false);
        //llamaCambioLugarPago_PEMod();
    }else{
        $(".idCentroCosto_PEMod").attr("disabled", true);
    }
};

$(".RutEmpresa_PEMod_PEMod").change(function(){ 
    $(".fila_lp").remove();
    $(".fila_cc").remove();
    ActualizarLugaresPago_PEMod(); 
});

function ActualizarLugaresPago_PEMod(){
    var RutEmpresa = $(".RutEmpresa_PEMod").val();
    if( RutEmpresa == 0 ){
        //$("#mensajeError").addClass("callout callout-warning");
        //$("#mensajeError").html("Debe seleccionar la Empresa");
        $(".lugarpagoid_PEMod").attr("disabled",true);
        return false;
    }else{
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "CentroCosto_ajax.php";
        var parametros  = "RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajaxLugarPago = new XMLHttpRequest(); // No Internet Explorer
        else
            ajaxLugarPago = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajaxLugarPago.onreadystatechange = funcionCallback_rutempresa_PEMod;
        // Enviamos la peticion
        ajaxLugarPago.open( "POST", url, false );
        ajaxLugarPago.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxLugarPago.send(parametros);
    }
}

function funcionCallback_rutempresa_PEMod()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxLugarPago.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajaxLugarPago.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajaxLugarPago.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('.lugarpagoid_PEMod').append('<option class="fila_lp" value=' + listado[index].lugarpagoid + '>' + listado[index].nombrelugarpago + ' </option>');    
                });
                $(".lugarpagoid_PEMod").attr("disabled", false);
            }
        }
    }
}

$(".lugarpagoid_PEMod").change(function(){
    $(".fila_cc").remove();
    llamaCambioLugarPago_PEMod();
});

function llamaCambioLugarPago_PEMod()
{
    var lugarpagoid = $(".lugarpagoid_PEMod").val();
    var RutEmpresa = $(".RutEmpresa_PEMod").val();
    if ( lugarpagoid != 0 ){
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "LugarPago_ajax.php";
        var parametros = "lugarpagoid=" + lugarpagoid + '&RutEmpresa=' + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajaxCentroCosto = new XMLHttpRequest(); // No Internet Explorer
        else
            ajaxCentroCosto = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajaxCentroCosto.onreadystatechange = funcionCallback_lugarpago_PEMod;
        // Enviamos la peticion
        ajaxCentroCosto.open( "POST", url, false );
        ajaxCentroCosto.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxCentroCosto.send(parametros);
    }else{
        $(".idCentroCosto_PEMod").attr("disabled", true);
        //$("#nombreCentroCosto").attr("disabled", true);
    }
}
function funcionCallback_lugarpago_PEMod()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxCentroCosto.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajaxCentroCosto.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajaxCentroCosto.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            //$('.idCentroCosto_PEMod').append('<option value="0">( Seleccione )</option>');    
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('.idCentroCosto_PEMod').append('<option class="fila_cc" value=' + listado[index].idCentroCosto + '>' + listado[index].nombrecentrocosto + ' </option>');    
                });
                $(".idCentroCosto_PEMod").attr("disabled", false);
            }
        }
    }
}

/*templates\documentos_vigentes_Listado.html*/

function InicioDocumentosVigentesListado(){
    var lugarpagoid = $(".lugarpagoid_DVListado").val();
    var RutEmpresa = $(".RutEmpresa_DVListado").val();
    var centrocostoid = $(".idCentroCosto_DVListado").val();
    //console.log(RutEmpresa);
    if( RutEmpresa != '' ) {
        $(".lugarpagoid_DVListado").attr("disabled",false);
    }
    if( RutEmpresa == 0 ){
        $(".lugarpagoid_DVListado").attr("disabled",true);
    }
    if ( lugarpagoid != 0 ){
        $(".idCentroCosto_DVListado").attr("disabled", false);
        //llamaCambioLugarPago_DVListado();
    }else{
        $(".idCentroCosto_DVListado").attr("disabled", true);
    }
};

$(".RutEmpresa_DVListado").change(function(){ 
    $(".fila_lp").remove();
    $(".fila_cc").remove();
    ActualizarLugaresPago_DVListado(); 
});

function ActualizarLugaresPago_DVListado(){
    var RutEmpresa = $(".RutEmpresa_DVListado").val();
    if( RutEmpresa == 0 ){
        //$("#mensajeError").addClass("callout callout-warning");
        //$("#mensajeError").html("Debe seleccionar la Empresa");
        $(".lugarpagoid_DVListado").attr("disabled",true);
        $(".idCentroCosto_DVListado").attr("disabled",true);
        return false;
    }else{
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "CentroCosto_ajax.php";
        var parametros  ="RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajaxLugarPago = new XMLHttpRequest(); // No Internet Explorer
        else
            ajaxLugarPago = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajaxLugarPago.onreadystatechange = funcionCallback_rutempresa_DVListado;
        // Enviamos la peticion
        ajaxLugarPago.open( "POST", url, false );
        ajaxLugarPago.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxLugarPago.send(parametros);
    }
}

function funcionCallback_rutempresa_DVListado()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxLugarPago.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajaxLugarPago.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajaxLugarPago.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('.lugarpagoid_DVListado').append('<option class="fila_lp" value=' + listado[index].lugarpagoid + '>' + listado[index].nombrelugarpago + ' </option>');    
                });
                $(".lugarpagoid_DVListado").attr("disabled", false);
            }
        }
    }
}

$(".lugarpagoid_DVListado").change(function(){
    $(".fila_cc").remove();
    llamaCambioLugarPago_DVListado();
});

function llamaCambioLugarPago_DVListado()
{
    var lugarpagoid = $(".lugarpagoid_DVListado").val();
    var RutEmpresa = $(".RutEmpresa_DVListado").val();
    if ( lugarpagoid != 0 ){
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "LugarPago_ajax.php";
        var parametros  = "lugarpagoid=" + lugarpagoid + '&RutEmpresa=' + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajaxCentroCosto = new XMLHttpRequest(); // No Internet Explorer
        else
            ajaxCentroCosto = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajaxCentroCosto.onreadystatechange = funcionCallback_lugarpago_DVListado;
        // Enviamos la peticion
        ajaxCentroCosto.open( "POST", url, false );
        ajaxCentroCosto.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxCentroCosto.send(parametros);
    }else{
        $(".idCentroCosto_DVListado").attr("disabled", true);
        //$("#nombreCentroCosto").attr("disabled", true);
    }
}
function funcionCallback_lugarpago_DVListado()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxCentroCosto.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajaxCentroCosto.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajaxCentroCosto.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            //$('.idCentroCosto_DVListado').append('<option value="0">( Seleccione )</option>');    
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('.idCentroCosto_DVListado').append('<option class="fila_cc" value=' + listado[index].idCentroCosto + '>' + listado[index].nombrecentrocosto + ' </option>');    
                });
                $(".idCentroCosto_DVListado").attr("disabled", false);
            }
        }
    }
}

/*templates\empleados_Listado.html*/

$('.buscar_EListado').keypress(function(e) {
    var key = e.which;
    if (key == 13) {
      return false;
    }
  });

  $('.btn-buscar_EListado').click(function(){
    $("#nombrex").val($(".buscar_EListado").val());
    $("#pagina").val(1);
    $("#paginado").submit();
  });

  /*templates\empresas_FirmantesCentroCosto.html*/

  $('#forzar_principal').on('click', function(){
    forzar_principal();
});

$('#reemplazar_principal').on('click', function(){
    reemplazar_principal();
});

$('#asignar_secundario').on('click', function(){
    asignar_secundario();
});


function forzar_principal()
{
    agregar_centrocosto1_ajax({opcionUsuario:'forzarPrincipal'});
};
function reemplazar_principal()
{
    agregar_centrocosto1_ajax({opcionUsuario:'cambiarPrincipal'});
};
function asignar_secundario()
{
    agregar_centrocosto1_ajax({opcionUsuario:'asignarSecundario'});
}
function listar_firmantescentrocosto_ajax()
{
    var RutEmpresa = $('#firmantes').data('rutempresa');
    var RutUsuario = $('#firmantes').data('rutusuario');
    $(".fila_firmanescentrocosto2").remove();
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "firmantescentrocosto_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&RutUsuario=" + RutUsuario + "&accion=LISTAR0";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_listar_firmantescentrocosto;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    AJAX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
};

function funcionCallback_listar_firmantescentrocosto()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                var fila = '';
                $.each(listado, function( index, value ) {
                    fila = '<tr class="fila_firmanescentrocosto2">';
                    fila += '<td>' + listado[index].centrocostoid + '</td>';
                    fila += '<td>' + (listado[index].pordefecto ? 'Si' : 'No') + '</td>';
                    fila += '<td>' + listado[index].nombrecentrocosto + '</td>';
                    fila += '<td><button class="btn btn-default btn-sm" type="button" onclick="javascript:eliminar_EFCC(\'' + listado[index].RutEmpresa + '\', \'' + listado[index].RutUsuario + '\', \'' + listado[index].centrocostoid + '\');">Eliminar</button></td>';
                    fila += '</tr>';
                    $('#firmanescentrocosto2 tr:last').after(fila);
                });
            }else{
                $('#firmanescentrocosto2 tr:last').after('<tr class="fila_firmanescentrocosto2"><td colspan=3 >No existen asignaciones de divisi&oacute personal</td></tr>');
            }
        }
    }
};

function eliminar_EFCC(RutEmpresa, RutUsuario, centrocostoid)
{
    if (confirm('Va a eliminar un registro, esta seguro'))
    {
        eliminar_firmantescentrocosto_ajax(RutEmpresa, RutUsuario, centrocostoid);
    }
    else
    {
        return false;
    }
};

function eliminar_firmantescentrocosto_ajax(RutEmpresa, RutUsuario, centrocostoid)
{
    var url  = "firmantescentrocosto_ajax.php";
    var parametros  = "RutEmpresa=" + RutEmpresa + "&RutUsuario=" + RutUsuario + "&centrocostoid=" + centrocostoid + "&accion=ELIMINAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_eliminar_firmantescentrocosto;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
};
function funcionCallback_eliminar_firmantescentrocosto()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            listar_firmantescentrocosto_ajax();
        }
    }
};

//Modal de plantillas
function obtener_firmantescentrocosto_ajax()
{
    var RutEmpresa = $('#firmantes').data('rutempresa');
    var RutUsuario = $('#firmantes').data('rutusuario');
    $(".fila_firmanescentrocosto").remove();

    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "firmantescentrocosto_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&RutUsuario=" + RutUsuario + "&accion=LISTAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_obtener_firmantescentrocosto;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
};
$("#btn_centroscosto").click(function()
{
    obtener_firmantescentrocosto_ajax();
});
function funcionCallback_obtener_firmantescentrocosto()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#firmanescentrocosto tr:last').after('<tr class="fila_firmanescentrocosto"><td>' + listado[index].centrocostoid + '</td><td>' + listado[index].nombrecentrocosto + '</td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_plantilla" id="btn_agregar_plantilla" onclick="javascript:agregar_centrocosto1(\'' + listado[index].centrocostoid + '\', \'' + listado[index].nombrecentrocosto + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#firmanescentrocosto tr:last').after('<tr class="fila_firmanescentrocosto"><td colspan=3 >No existen divisiones de personal para esta empresa</td></tr>');
            }
        }
    }
};

function agregar_centrocosto1_ajax(data)
{
    var principal = $('#firmante_principal').prop('checked') ? 1 : 0;
    var RutUsuario = $('#firmantes').data('rutusuario');
    var RutUsuario_principal = '';
    switch(data.opcionUsuario)
    {
        case 'forzarPrincipal':
            principal = 1;
        break;
        case 'asignarSecundario':
            principal = 0;
        break;
        case 'cambiarPrincipal':
            RutUsuario_principal = $('#RutUsuario_principal').val();
        break;
    }
    var RutEmpresa = $('#firmantes').data('rutempresa');
    var centrocostoid = $("#selector-modal-centrocosto").data('centrocostoid');

    var url  = "firmantescentrocosto_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&centrocostoid=" + centrocostoid + "&RutUsuario=" + RutUsuario + "&principal=" + principal + "&RutUsuario_principal=" + RutUsuario_principal + "&accion=VALIDAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_agregar_centrocosto1;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
};
function funcionCallback_agregar_centrocosto1()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            if( num > 0 )
            {
                switch (listado[0].accion)
                {
                    case 'ATENCION':
                        $('#modal_atencion').modal({show:'true'});
                    break;
                    case 'NOTIFICACION':
                        obtenerUsuarioPrincipalExistente_ajax();
                    break;
                    default:
                        $('#selector-modal-centrocosto').data('centrocostoid', '');
                        $('#selector-modal-centrocosto').val('');
                        $('#firmante_principal').prop('checked', false);
                        checkFormulario_EFCC();
                        listar_firmantescentrocosto_ajax();
                        $('#mensajeOK').html('Agregado exitosamente');
                        $('#mensajeOK').addClass('callout callout-success')
                }
            }
        }
    }
}
function obtenerUsuarioPrincipalExistente_ajax()
{
    var principal = 1;
    var RutEmpresa = $('#firmantes').data('rutempresa');
    var centrocostoid = $("#selector-modal-centrocosto").data('centrocostoid');

    var url  = "firmantescentrocosto_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&centrocostoid=" + centrocostoid + "&principal=" + principal + "&accion=PRINCIPAL";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_obtenerUsuarioPrincipalExistente;
    // Enviamos la peticion
    ajax.open( "POST", url, true );
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
};

function funcionCallback_obtenerUsuarioPrincipalExistente()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida);
            num = listado.length;
            if( num > 0 )
            {
                $('#RutUsuario_principal').val(listado[0].personaid);
                $('#nombre_principal').val(listado[0].nombre);
                $('#appaterno_principal').val(listado[0].appaterno);
                $('#apmaterno_principal').val(listado[0].apmaterno);
                $('#modal_notificacion').modal({show:'true'});
            }
        }
    }
};

function agregar_centrocosto1(centrocostoid, nombrecentrocosto)
{
    $('#selector-modal-centrocosto').val(nombrecentrocosto);
    $('#selector-modal-centrocosto').data('centrocostoid', centrocostoid);
    $("#cerrar_centroscosto").click();
    checkFormulario_EFCC();
}

function checkFormulario_EFCC()
{
    if ($("#selector-modal-centrocosto").data('centrocostoid') != '')
    {
        $('#AGREGAR').removeAttr('disabled');
    }
    else
    {
        $('#AGREGAR').attr('disabled','disabled');
    }
}

/*templates\empresas_Listado.html*/

/*
function uploadFile_EListdo(file) {

    var xhr = crearXMLHttpRequest();
    //xhr=new ActiveXObject("Microsoft.XMLHTTP");

    xhr.upload.addEventListener('loadstart', function (e) {
        document.getElementById('mensaje').innerHTML =
            'Cargando archivo...';
    }, false);

    xhr.upload.addEventListener('load', function (e) {
        document.getElementById('mensaje').innerHTML = '';
    }, false);

    xhr.upload.addEventListener('error', function (e) {
        alert('Ha habido un error :/');
    }, false);
    xhr.open('POST', 'importarExcel.php?IdArchivo=1');

    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);

    xhr.addEventListener('readystatechange', function (e) {
        if (this.readyState == 4) {

            respuesta = xhr.responseText;

            // document.getElementById("mensajeError").innerHTML = xhr.responseText;

            if (respuesta == "")//cuando sepa que fue bien sea vacio sea ok realiza el submit
            {

                document.getElementById("resultado").submit();
                //pasando en mensaje al formulario creado con nombre mensaje           
            }
            else {
                var elementoError = document.getElementById("mensajeError");
                elementoError.innerHTML = respuesta;
                elementoError.className += "callout callout-danger";

            }
        }

        document.getElementById("archivo").value = "";
    });
    xhr.send(file);
}
//revisa si hay algun cambio en el archivo
upload_input_EListado = document.querySelectorAll('#archivo')[0];

upload_input_EListado.onchange = function () {
    //alert('2');
    uploadFile_EListdo(this.files[0]);
};*/

/*templates\excelempl_inicio.html*/


function InicioExcelEmp(){

    if (archivoxls != "" )
    {	
        var mensaje = document.getElementById('mensajeError').innerHTML;
    
        if (mensaje == "")	
        {	
            document.getElementById('estado').style.display = "block";
            document.getElementById("IMPORTAR").disabled = true;
            
            document.getElementById("progreso").max = totalfilas;
            document.getElementById('progreso').value = filasprocesadas;
            document.getElementById('estadodetalle').innerHTML = "Procesando fila " + filasprocesadas + " de " +  totalfilas;
        
            Inicio_Consulta_Estado_EInicio();
        }
    } 
    }
    
    function Inicio_Consulta_Estado_EInicio()
    {	
        consulta = setInterval(function(){ Consulta_Estado_EInicio() }, 3000);
    }
    
    function Consulta_Estado_EInicio()
    {	
        conexion=crearXMLHttpRequest();
        parametros = "?accion=ESTADO&totalfilas=" + totalfilas;
        conexion.open('POST', 'excelempl.php' + parametros,false);
        conexion.send(null);
        respuesta =  conexion.responseText;	
        //alert ("respuesta" + respuesta);
        log_script (respuesta);
        if (respuesta == "FIN" )
        {
            //document.getElementById('estado').style.display = "none";
            document.getElementById("IMPORTAR").disabled = false;
                    
            document.getElementById('mensajeOK').innerHTML = "Proceso Finalizado"
            
            
            document.getElementById("progreso").max 	= totalfilas;
            document.getElementById('progreso').value	= totalfilas;
            document.getElementById('estadodetalle').innerHTML = "Fin Proceso, procesado " + totalfilas + " de " +  totalfilas;
            clearInterval(consulta);	
            finproceso = setInterval(function(){ mensaje_fin_proceso_EInicio() }, 300);
            return;
        }
                
        if (respuesta.substring(0, 2) == "OK")
        {
        
            document.getElementById('estado').style.display = "block";
            document.getElementById("IMPORTAR").disabled = true;
                    
            arr_respuesta = respuesta.split("|")
            est_filasprocesadas = arr_respuesta[1]
            est_totalfilas 		= arr_respuesta[2]
            
            
            if(est_filasprocesadas == aux_filasprocesadas)
            {
                repetidos++;
            }
            else
            {
                repetidos = 0
            }
            
            mensaje = " repetidos:" + repetidos + " fp:" + est_filasprocesadas + " tf:" + est_totalfilas;
            log_script_EInicio (mensaje);
                    
            if (repetidos > 13)
            {
                if (reactivaciones > 2)
                {
                    if (est_filasprocesadas != est_totalfilas)
                    {
                        log = document.getElementById('lbllog').innerHTML;
                        document.getElementById('lbllog').innerHTML = log + " mens";
                        
                        clearInterval(consulta);	
                        document.getElementById('estado').style.display = "none";
                        document.getElementById("IMPORTAR").disabled = false;
    
                        alert("Problemas con el proceso, Ejecute Nuevamente")
                    }
                }	
                else
                {
                    reactiva_EInicio();
                    repetidos = 0;
                }
            }
                
            document.getElementById("progreso").max = est_totalfilas;
            document.getElementById('filasprocesadas').value = est_filasprocesadas
            document.getElementById('progreso').value = est_filasprocesadas
            document.getElementById('estadodetalle').innerHTML = "Procesando fila " + est_filasprocesadas + " de " +  est_totalfilas
            aux_filasprocesadas = est_filasprocesadas;
        }
        else
        {
            clearInterval(consulta);	
            document.getElementById('estado').style.display = "none";
            document.getElementById("IMPORTAR").disabled = false;
            alert("Problemas con el proceso, Ejecute Nuevamente " + respuesta)
        }
        
    }
    
    function log_script_EInicio(mensaje)
    {
        //document.getElementById('log').style.display = "block";
        //log = document.getElementById('lbllog').innerHTML;
        //document.getElementById('lbllog').innerHTML = log + mensaje + "<br>"
    }
    
    function mensaje_fin_proceso_EInicio ()
    {
        clearInterval(finproceso);
        alert ("Proceso finalizado, para ver detalles dar click en boton DETALLE PROCESO");
        
    }
    
    function reactiva_EInicio()
    {
        reactivaciones++;
        conexion=crearXMLHttpRequest();
        parametros = "?accion=PROCESO";
        conexion.open('POST', 'excelempl.php' + parametros);
        conexion.send(null);
    }
    
    function subirArchivo_EInicio ()
    {
        if (document.getElementById('Documento').value == '')
        {
            alert ("Debe selecionar archivo");
            return false;
        }
        else
        {
            MostrarCargando();
        }
    }
    
    $(".archivo_EListado").change(function()
    {
        $("#Documento").val($(".archivo_EListado").val());
    });

    /*templates\fichas_FormularioAgregar.html*/

    
  $(".newusuarioid_FAgregar").change(function(){

    var RutUsuario = $(".newusuarioid_FAgregar").val();
    var url  = "Generar_Documento_PorFicha_ajax.php";
    var parametros = "personaid=" + RutUsuario;

  // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
  if( window.XMLHttpRequest )
    ajax = new XMLHttpRequest(); // No Internet Explorer
  else
    ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
 
  // Almacenamos en el control al funcion que se invocara cuando la peticion
  // cambie de estado 
  ajax.onreadystatechange = funcionCallback_rutusuario_FAgregar;

  // Enviamos la peticion
  ajax.open( "POST", url, true );
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send(parametros);
});

function funcionCallback_rutusuario_FAgregar(){
// Comprobamos si la peticion se ha completado (estado 4)
if( ajax.readyState == 4 )
{
// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
if( ajax.status == 200 )
{
  // Escribimos el resultado en la pagina HTML mediante DHTML
  salida = ajax.responseText;
  datos = JSON.parse(salida);

  if( salida != '' ){ 

      $("#nombre").val( datos.nombre + ' ' + datos.appaterno + ' ' + datos.apmaterno);
      $("#fechanacimiento").val( datos.fechanacimiento );
      $("#correo").val(datos.correo);
      $("#nacionalidad").val(datos.nacionalidad);
      $("#direccion").val(datos.direccion); 
      $("#comuna").val(datos.comuna);
      $("#ciudad").val(datos.ciudad);
      $("#idEstadoCivil").val(datos.estadocivil);
    $("#fono").val(datos.fono);
    $("#idFirma").val(datos.idFirma);

      var idestado = '#idEstadoCivil option[value="'+ datos.estadocivil + '"]';
      $(idestado).attr("selected",true);

      var rolid = '#rolid option[value="'+ datos.rolid +'"]'; 
      $(rolid).attr("selected", true);

  }else{
      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
  }
}
}
}

//Modal de Lugares de pago 
$(".btn_lugares_pago_FAgregar").click(function(){

var RutEmpresa = $("#RutEmpresa").val();
$(".fila_lp").remove();
if( RutEmpresa == 0 ){

$("#mensajeError").addClass("callout callout-warning");
$("#mensajeError").html("Debe seleccionar la Empresa");
return false;

}else{

$("#mensajeError").html("");
$("#mensajeError").removeClass("callout callout-warning");


var url  = "Generar_Documento_PorFicha_ajax1.php";
var parametros = "RutEmpresa=" + RutEmpresa;

// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
if( window.XMLHttpRequest )
  ajax = new XMLHttpRequest(); // No Internet Explorer
else
  ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

// Almacenamos en el control al funcion que se invocara cuando la peticion
// cambie de estado 
ajax.onreadystatechange = funcionCallback_lugarespago_FAgregar;

// Enviamos la peticion
ajax.open( "POST", url, true );
ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.send(parametros);
}

});

function funcionCallback_lugarespago_FAgregar(){
// Comprobamos si la peticion se ha completado (estado 4)
if( ajax.readyState == 4 )
{
// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
if( ajax.status == 200 )
{
  // Escribimos el resultado en la pagina HTML mediante DHTML
  salida = ajax.responseText;
  listado = JSON.parse(salida);
num = listado.length;

if( num > 0 ){
    $.each(listado, function( index, value ) {
  $('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td>' + listado[index].centrocostoid + '</td><td><div id="' + listado[index].centrocostoid + '">' + listado[index].nombrecentrocosto + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar" id="btn_agregar" onclick="agregarLp_FAgregar(' + listado[index].lp + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
});
}
else{
$('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td colspan = 3>No existen Centros de Costo de la Empresa seleccionada</td></tr>');
}
}
}
}

function agregarLp_FAgregar(i){
$("#lugarpagoid").val(i);
$("#nombrelugarpago").val($("#"+i).html());
$("#cerrar_lugares_pago").click();
}

$(".archivo_FAgregar").change(function(){
$("#Documento").val($(".archivo_FAgregar").val());
});

//Validar formulario
$(".AGREGAR_FAgregar").click(function(){

if ($(".newusuarioid_FAgregar").val().length < 9 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Rut no puede estar vac&iacute;o");
  $('.newusuarioid_FAgregar').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}
 if( ! validaRut2(document.formulario.newusuarioid)){
     return false;
}

if ($("#nombre").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Nombre no puede estar vac&iacute;o");
  $('#nombre').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#nacionalidad").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Nacionalidad no puede estar vac&iacute;o");
  $('#nacionalidad').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#direccion").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Direcci&oacute;n no puede estar vac&iacute;o");
  $('#direccion').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#comuna").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Comuna no puede estar vac&iacute;o");
  $('#comuna').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#ciudad").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Ciudad no puede estar vac&iacute;o");
  $('#ciudad').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#fono").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe completar el campo Fono");
  $('#fono').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#fono").val().length < 12 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El Fono introducido esta incompleto");
  $('#fono').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#fono").val().charAt(0) != '+' ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El Fono introducido no tiene el formato adecuado");
  $('#fono').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#correo").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo correo no puede estar vac&iacute;o");
  $('#correo').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}
var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

if ( !regex.test($("#correo").val().trim())){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe introducir un correo valido");
  $('#correo').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#idEstadoCivil").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe seleccionar el campo de Estado Civil");
  $('#idEstadoCivil').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#fechanacimiento").val().length == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Fecha de Nacimiento no puede estar vac&iacute;o");
  $('#fechanacimiento').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#idFirma").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo Tipo de firma no puede estar vac&iacute;o");
  $('#idFirma').focus();
  
  $("#ficha_datos_p").addClass("active");
  $("#datos_p").addClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

$("#ficha_datos_p").removeClass("active");
$("#datos_p").removeClass("active");

if ($("#RutEmpresa").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("El campo RutEmpresa no puede estar vac&iacute;o");
  $('#RutEmpresa').focus();
  
  $("#ficha_datos_e").addClass("active");
  $("#datos_e").addClass("active");
  $("#ficha_datos_p").removeClass("active");
  $("#datos_p").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#nombrelugarpago").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe seleccionar un Centro de Costo");
  $('#nombrelugarpago').focus();
  
  $("#ficha_datos_e").addClass("active");
  $("#datos_e").addClass("active");
  $("#ficha_datos_p").removeClass("active");
  $("#datos_p").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

if ($("#nombrecentrocosto").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe seleccionar Relaci�n Laboral");
  $('#nombrecentrocosto').focus();
  
  $("#ficha_datos_e").addClass("active");
  $("#datos_e").addClass("active");
  $("#ficha_datos_p").removeClass("active");
  $("#datos_p").removeClass("active");
  $("#ficha_datos_doc").removeClass("active");
  $("#datos_doc").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

$("#ficha_datos_e").removeClass("active");
$("#datos_e").removeClass("active");

if ($("#Documento").val() == 0 ){
  $("#mensajeError").addClass("callout callout-warning");
  $("#mensajeError").html("Debe seleccionar un archivo PDF");
  $('.archivo_FAgregar').focus();
  
  $("#ficha_datos_doc").addClass("active");
  $("#datos_doc").addClass("active");
  $("#ficha_datos_p").removeClass("active");
  $("#datos_p").removeClass("active");
  $("#ficha_datos_e").removeClass("active");
  $("#datos_e").removeClass("active");
  
  $(".archivo_FAgregar").val(""); $("#Documento").val("");
  
  return false;
}

$("#ficha_datos_doc").removeClass("active");
$("#datos_doc").removeClass("active");

});

/*templates\fichas_FormularioDocumento.html*/


$(".newusuarioid_FDocumento").change(function () {

    var RutUsuario = $(".newusuarioid_FDocumento").val();
    var url = "Generar_Documento_PorFicha_ajax.php";
    var parametros = "personaid=" + RutUsuario;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_rutusuario_FDocumento;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);
});

function funcionCallback_rutusuario_FDocumento() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            datos = JSON.parse(salida);

            if (salida != '') {

                $("#nombre").val(datos.nombre + ' ' + datos.appaterno + ' ' + datos.apmaterno);
                $("#fechanacimiento").val(datos.fechanacimiento);
                $("#correo").val(datos.correo);
                $("#nacionalidad").val(datos.nacionalidad);
                $("#direccion").val(datos.direccion);
                $("#comuna").val(datos.comuna);
                $("#ciudad").val(datos.ciudad);
                $("#idEstadoCivil").val(datos.estadocivil);
                $("#fono").val(datos.fono);
                $("#idFirma").val(datos.idFirma);

                var idestado = '#idEstadoCivil option[value="' + datos.estadocivil + '"]';
                $(idestado).attr("selected", true);

                var rolid = '#rolid option[value="' + datos.rolid + '"]';
                $(rolid).attr("selected", true);

            } else {
                $("#mensajeError").removeClass("callout callout-warning");
                $("#mensajeError").html("");
            }
        }
    }
}

//Modal de Lugares de pago 
$(".btn_lugares_pago_FAgregar").click(function () {

    var RutEmpresa = $(".RutEmpresa_FAgregar").val();
    $(".fila_lp").remove();
    $("#nombrecentrocosto").val('');
    $("#centrocostoid").val('');

    if (RutEmpresa == 0) {

        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;

    } else {

        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");


        var url = "Generar_Documento_PorFicha_ajax1.php";
        var parametros = "RutEmpresa=" + RutEmpresa;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax.onreadystatechange = funcionCallback_lugarespago_FAgregar;

        // Enviamos la peticion
        ajax.open("POST", url, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(parametros);
    }

});

function funcionCallback_lugarespago_FAgregar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida);
            num = listado.length;

            if (num > 0) {
                $.each(listado, function (index, value) {
                    $('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td>' + listado[index].lugarpagoid + '</td><td><div id="' + listado[index].lugarpagoid + '">' + listado[index].nombrelugarpago + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar" id="btn_agregar" onclick="agregarLp_FAgregar(' + listado[index].lp + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }
            else {
                $('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td colspan = 3>No existen Centros de Costo de la Empresa seleccionada</td></tr>');
            }
        }
    }
}

function agregarLp_FAgregar(i) {
    $("#lugarpagoid").val(i);
    $("#nombrelugarpago").val($("#" + i).html());
    $("#cerrar_lugares_pago").click();
}


//Modal de Centros de Costo
$(".btn_centro_costo_FAgregar").click(function () {

    var RutEmpresa = $(".RutEmpresa_FAgregar").val();
    var LugarPagoid = $("#lugarpagoid").val();
    $(".fila_cc").remove();

    if (RutEmpresa == 0) {

        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;

    }

    if (LugarPagoid == '') {

        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar Lugar de Pago");
        return false;

    }

    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");


    var url = "Generar_Documento_PorFicha_ajax2.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&lugarpagoid=" + LugarPagoid;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax.onreadystatechange = funcionCallback_centrocosto_FAgregar;

    // Enviamos la peticion
    ajax.open("POST", url, true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send(parametros);


});

function funcionCallback_centrocosto_FAgregar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax.responseText;
            listado = JSON.parse(salida);
            num = listado.length;

            if (num > 0) {
                $.each(listado, function (index, value) {
                    $('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td>' + listado[index].centrocostoid + '</td><td><div id="' + listado[index].centrocostoid + '">' + listado[index].nombre + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar" id="btn_agregar" onclick="agregarCC_FAgregar(' + listado[index].centrocostoid + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            } else {
                $('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td colspan=3 >No existen Relaciones laborales para la Empresa y el Centro de Costo seleccionado</td></tr>');
            }
        }
    }
}

function agregarCC_FAgregar(i) {
    $("#centrocostoid").val(i);
    $("#nombrecentrocosto").val($("#" + i).html());
    $("#cerrar_centro_costo").click();
}

$(".archivo_FAgregar").change(function () {
    $("#Documento").val($(".archivo_FAgregar").val());
});

$(".RutEmpresa_FAgregar").change(function () {
    $("#centrocostoid").val('');
    $("#nombrecentrocosto").val('');
    $("#lugarpagoid").val('');
    $("#nombrelugarpago").val('');
});

/*templates\fichas_FormularioFirmantes.html*/

function InicioFichasFirmantes() {

    var idEstado = $("#idEstado").val();

    if (idEstado == 6 || $(".custom-radio input:checked").length == 0) {
        $("#GENERAR").attr("disabled", true);
        $(".generar").attr("disabled", true);
    }

    if ($("#Representantes").val() == 1) {
        $(".firmantes").css("display", "block");
    } else {
        $(".firmantes").css("display", "none");
    }

};

function generacionDocumentos_FF(data) {
    MostrarCargando();

    clearInterval(proceso_FF);// poner esto cuando llega respuesta

    var fichaid = data['fichaid'];
    var idplantilla = data['idplantilla'];
    var personaid = data['personaid'];
    var idtipofirma = $("#idTipoFirma").val();

    if (idtipofirma > 0) {

        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");

        var url = "Fichas_ajax.php";
        var parametros = "fichaid=" + fichaid + "&idplantilla=" + idplantilla + "&personaid=" + personaid + "&idFirma=" + idtipofirma;
        //console.log(url);
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajax_FF = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_FF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        ajax_FF.onreadystatechange = funcionCallback_generaDocumentos_FF;
        ajax_FF.addEventListener("load", transferComplete);
        // Enviamos la peticion
        ajax_FF.open("POST", url, false);
        ajax_FF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_FF.send(parametros);
        function transferComplete(evt) {
            OcultarCargando();
        }
    } else {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el Tipo de Firma");
        $("#idTipoFirma").focus();
        OcultarCargando();
        return false;
    }
}

function cambiaEstado_FF() {
    return confirm('Desea Cancelar el Flujo de Contrataci\u00f3n?');
}
function funcionCallback_generaDocumentos_FF() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_FF.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_FF.status == 200) {
            salida = ajax_FF.responseText;
            var datos = JSON.parse(salida);
            //console.log(datos);

            if (datos.data == '') {

                $("#mensajeError").html(datos.mensaje);
                $("#mensajeError").addClass("callout callout-warning");
            } else {

                //Separar el id
                var aux_id = aux.id.split('_');
                var aux_num = aux_id[1];

                $("#estado_" + aux_num).val("Generado con el id: " + datos.data);
                $("#documentoid_ver_" + aux_num).val(datos.data);
                $("#generar_" + aux_num).hide();
                /*$("#ver_" +  aux_num).css("display","inline"); */

                $("#mensajeError").html('');
                $("#mensajeError").removeClass("callout callout-warning");


                procesaMatriz();
            }

        }
    }
}



function generar_FF(id) {
    var idtipofirma = $("#idTipoFirma").val();
    if (idtipofirma > 0) {
        $('#GENERAR').attr('disabled', true);
        $('.generar').attr('disabled', true);
        var aux = [];
        aux['id'] = id;
        aux['idplantilla'] = $(id).data("idplantilla");
        aux['fichaid'] = $(id).data("fichaid");
        aux['personaid'] = $('#listadoFirmantes .custom-radio input:checked').data("personaid");
        matriz_FF.push(aux);
        console.log(matriz_FF, id);
        this.procesaMatriz();
    } else {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el Tipo de Firma");
        $("#idTipoFirma").focus();
        OcultarCargando();
        return false;
    }
}

function generarPorLotes_FF() {
    var idtipofirma = $("#idTipoFirma").val();
    if (idtipofirma > 0) {
        $('#GENERAR').attr('disabled', true);
        $('.generar').attr('disabled', true);
        $('#listadoGeneracionDocumentos .datos').each(function (index) {
            if ($(this).data("estado2") == 0) {
                var aux = [];
                aux['id'] = this.id;
                aux['idplantilla'] = $(this).data("idplantilla");
                aux['fichaid'] = $(this).data("fichaid");
                aux['idTipoFirma'] = $("#idTipoFirma").val();
                aux['personaid'] = $('#listadoFirmantes .custom-radio input:checked').data("personaid");
                matriz_FF.push(aux);
            }
        });
        this.procesaMatriz();
    } else {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el Tipo de Firma");
        $("#idTipoFirma").focus();
        OcultarCargando();
        return false;
    }
}



function procesaMatriz() {
    if (matriz_FF.length > 0) {
        aux_FF = matriz_FF.shift(); //console.log(aux_FF);
        proceso_FF = setInterval(function () { generacionDocumentos_FF(aux_FF) }, 1000);
    } else {
        //console.log($("#mensajeError").html().length);
        if ($("#mensajeError").html().length == 0) {
            $('#GENERAR').attr('disabled', false);
            $('.generar').attr('disabled', false);
            //location.reload();
        }
    }
}

$(".ver_FF").click(function () {

    var id = this.id; //console.log(id);
    $("#VER_DOCUMENTO_" + id).click();
});

function ver_doc_FF(i) {
    $("#VER_DOCUMENTO_" + i).click();
}

let general_FF = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "CREATE",
    operator: {
        sessionId: null
    }
};


let popupPage_FF;
let openCheckId_FF = function (action) {
    MostrarCargando();
    general_FF.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_FF = window.open("checkid", "libPage", opciones);

    var timer = setInterval(function () {
        if (popupPage_FF.closed) {
            clearInterval(timer);
            document.getElementById('cargando').style.display = 'none';
        }
    }, 1000);

    // Puts focus on the popupPage_FF
    if (window.focus) {
        popupPage_FF.focus();
    }
};


let getParamsCreate_FF = function () {
    return {
        action: "CREATE",
        apiKey: general_FF.apiKey,
        sessionId: general_FF.session.id,
        companyId: general_FF.session.companyId,
        operationId: 1,
        baseUrl: general_FF.baseUrl,
        useFingerprint: true,//si pide huella al que esta enrolando
        usePin: true,//si pide pin al que esta enrolando
        useKBA: false,//si se utiliza la pregunta de seguridad
        operator: {
            sessionId: general_FF.operator.sessionId,
            identityDocument: {
                countryCode: general_FF.countryCode,
                type: general_FF.type,
                personalNumber: general_FF.rutoperador
            }
        },
        digitalIdentity: {
            personalData: {
                givenNames: "desmond",
                surnames: "miles",
                dob: 20000101,
                gender: "NOT_KNOWN"
            },
            emailAddresses: [
                {
                    type: 'BUSINESS',
                    address: 'algo@algo.cl',
                    primary: true
                },
                {
                    type: 'PERSONAL',
                    address: 'algo@algo2.cl',
                    primary: false
                }
            ],
            contactPhones: [
                {
                    number: '+56982365612',
                    primary: false,
                    type: 'HOME'
                },
                {
                    number: '+56911111111',
                    primary: true,
                    type: 'PERSONAL'
                }

            ],
            identityDocuments: [{
                countryCode: general_FF.countryCode,
                type: general_FF.type,
                personalNumber: general_FF.rutaenrolar
            }]
        }
    };
};

window.getParams = function () {
    return getParamsCreate_FF();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_FF.close();
    console.log("callback", result);
    document.getElementById('cargando').style.display = 'none';

    if (result.numError == 0) {
        //alert ("OK")
    }
    else {
        alert(result.numError + " " + result.msError);
    }

    return false;
}

function enrolar_FF() {
    consulta_sesion_FF();
}


function consulta_sesion_FF() {
    conexion_FF = crearXMLHttpRequest();

    conexion_FF.open('POST', './consulta_sesion.php', false);

    conexion_FF.send(null);
    // Devolvemos el resultado
    respuesta_FF = conexion_FF.responseText;
    arr_resp = respuesta_FF.split('|');
    if (arr_resp[0] == 'ok') {
        general_FF.baseUrl = arr_resp[1];
        general_FF.session.companyId = arr_resp[2];
        general_FF.username = arr_resp[4];
        general_FF.session.id = arr_resp[6];
        general_FF.countryCode = arr_resp[7];
        general_FF.type = arr_resp[8];
        general_FF.apiKey = arr_resp[9];

        var rut           = document.getElementById('usuarioid_FF').value;
        general_FF.rutoperador     = rut.replace ("-","");

        var rut = document.getElementById('rut').value;
        general_FF.rutaenrolar = rut.replace("-", "");


        openCheckId_FF("CREATE");
    }
    else {
        alert(respuesta_FF);
    }

}

/*templates\fichas_FormularioModificar.html*/

var idEstado_FMod = 0;
function InicioFichaModificar(){
   //$('[data-toggle="tooltip"]').tooltip(); 
   var filas = $("#tabla_documentos tr").length;
   var i = 0;
   idEstado_FMod = $("#idEstado").val();
   //console.log(idEstado);
   if( idEstado_FMod == 3 ){
     $("#BTN-SIGUIENTE").removeAttr('disabled');
     $("#BTN-SIGUIENTE").prop('title','');
     $("#CANCELAR").html('Cancelar flujo');
   }
   if( idEstado_FMod == 6 ){
     $(".subir").attr('disabled',true);
     $(".eliminar").attr('disabled',true);
     $(".subir_adicional").attr('disabled',true);
     $("#CANCELAR").attr('disabled',true);
     $("#CANCELAR").html('Cancelar flujo');
     //$("#BTN-SIGUIENTE").css('display','none');
   }
   if( idEstado_FMod == 5 ){
     $(".subir").attr('disabled',true);
     $(".eliminar").attr('disabled',true);
     $(".subir_adicional").attr('disabled',true);
     $("#CANCELAR").html('Habilitar flujo');
     $("#BTN-SIGUIENTE").css('display','none');
   }
     if( idEstado_FMod == 1 || idEstado_FMod == 2 ){
     $("#CANCELAR").html('Cancelar flujo');
     $("#BTN-SIGUIENTE").attr('disabled',true);
     $("#BTN-SIGUIENTE").prop('title','Faltan documentos obligatorios por subir');
   }
   if( idEstado_FMod == 4 ){
     $("#CANCELAR").html('Cancelar flujo');
     $("#BTN-SIGUIENTE").attr('disabled',false);
     $("#BTN-SIGUIENTE").prop('title','');
   }
   for( i = 0; i < filas - 2; i++ ){
     if( $("#documento_" + i).html().length > 0 ){
       $("#Subir_" + i ).css('display','none');
       $("#VER_DOCUMENTO_" + i ).css('display','inline');
       $("#ELIMINAR_DOCUMENTO_" + i ).css('display','inline');
     }else{
       $("#Subir_" + i ).css('display','inline');
       $("#VER_DOCUMENTO_" + i ).css('display','none');
       $("#ELIMINAR_DOCUMENTO_" + i ).css('display','none');
     }
   }
   if( $("#Representantes").val() == 1 ){
     $(".firmantes").css("display","block");
   }else{
     $(".firmantes").css("display","none");
   }
 };

 function cambiaEstado_FMod()
 {
   if (idEstado_FMod != 5) // Cancelado
   {
     return confirm('Desea Cancelar el Flujo de Contrataci\u00f3n?');
   }
   else{
     return confirm('Desea Habilitar el Flujo de Contrataci\u00f3n?');
   }
 }
 function asignarAlModal_Fmod(i){
   $("#idFicha_modal").val($("#fichaid_" + i).val());
   $("#idTipoGestor_modal").val($("#idTipoGestor_" + i).val());
   $("#Obligatorio_modal").val($("#Obligatorio_" + i).val());
   $("#idTipoDoc").val($("#Nombre_" + i).val());
   $("#idTipoGestor").val($("#idTipoGestor_" + i).val());
 };

 $(".archivo_FMod").change(function(){
   $("#Documento").val($(".archivo_FMod").val());
 });
 
 $("#archivo_adicional").change(function(){
   $("#Documento_adicional").val($("#archivo_adicional").val());
 });
 
 $("#AGREGAR_DOC").click(function(){
   if( $(".archivo_FMod").val() == '' ){
     $("#mensajeError_modal").html("Debe seleccionar un documento");
     $("#mensajeError_modal").addClass("callout callout-warning");
     return false;
   }else{
     $("#mensajeError_modal").html("");
     $("#mensajeError_modal").removeClass("callout callout-warning");
   }
 });
 
 $("#AGREGAR_DOC_ADICIONAL").click(function(){
   if( $("#archivo_adicional").val() == '' ){
     $("#mensajeError_adicional").html("Debe seleccionar un documento");
     $("#mensajeError_adicional").addClass("callout callout-warning");
     return false;
   }
   if( $("#idTipoGestor_adicional").val() == 0 ){
     $("#mensajeError_adicional").html("Debe seleccionar un tipo de documento");
     $("#mensajeError_adicional").addClass("callout callout-warning");
     return false;
   }
   $("#mensajeError_adicional").html("");
   $("#mensajeError_adicional").removeClass("callout callout-warning");
 });

 //Boton eliminar documento de ficha 
 $(".eliminar").click(function(){
   var respuesta_FMod = confirm("Desea eliminar este Documento?");
   if( respuesta_FMod ){
     MostrarCargando();
     var id = this.id;
     var res = id.split('_');
     var i = res[2]; 
     $("#fichaid_eliminar_" + i).val($("#fichaid_" + i).val());
     $("#documentoid_eliminar_" + i).val($("#documentoid_" + i).val());
     $("#Obligatorio_eliminar_" + i).val($("#Obligatorio_" + i).val());
     $("#formulario_eliminar_" + i).submit();
   }
   else{
     return respuesta_FMod;
   }
 });

 //Boton de ver documento
 $(".ver").click(function(){
   MostrarCargando();
   var id = this.id;
   var res = id.split('_');
   var i = res[2]; 
   $("#fichaid_ver_" + i).val($("#fichaid_" + i).val());
   $("#documentoid_ver_" + i).val($("#documentoid_" + i).val());
   $("#formulario_ver_" + i).submit();
 });

 let general_FMod = {
   baseUrl: "",
   apiKey: "",
   digitalSignature: 0,
   session: {
     id: "",
     companyId: "",
     username: ""
   },
   currentAction: "CREATE",
   operator: {
     sessionId: null
   }
 };

 let popupPage_FMod;
 let openCheckId_FMod = function(action) 
 {
   MostrarCargando();
   general_FMod.currentAction = action;
   // Create popup window
   var w = 900;
   var h = 620;
   // Fixes dual-screen position                         Most browsers      Firefox
   var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
   var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
   var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
   var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
   var left = ((width / 2) - (w / 2)) + dualScreenLeft;
   var top = ((height / 2) - (h / 2)) + dualScreenTop;
   var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
   popupPage_FMod = window.open("checkid", "libPage", opciones);
   var timer = setInterval(function() { 
     if(popupPage_FMod.closed) {
       clearInterval(timer);
       document.getElementById('cargando').style.display='none';
     }
   }, 1000);
   // Puts focus on the popupPage_FMod
   if (window.focus) {
     popupPage_FMod.focus();
   }
 };
 let getParamsCreate_FMod = function() {
   return {
     action: "CREATE",
     apiKey: general_FMod.apiKey,
     sessionId: general_FMod.session.id,
     companyId: general_FMod.session.companyId,
     operationId: 1,
     baseUrl: general_FMod.baseUrl,
     useFingerprint: true,//si pide huella al que esta enrolando
     usePin: true,//si pide pin al que esta enrolando
     useKBA: false,//si se utiliza la pregunta de seguridad
     operator: {
       sessionId: general_FMod.operator.sessionId,
       identityDocument: {
         countryCode: general_FMod.countryCode,
         type: general_FMod.type,
         personalNumber: general_FMod.rutoperador 
       }
     },
     digitalIdentity: {
       personalData: {
         givenNames: "desmond",
         surnames: "miles",
         dob: 20000101,
         gender: "NOT_KNOWN"
       },
       emailAddresses: [
         {
           type: 'BUSINESS',
           address: 'algo@algo.cl',
           primary: true
         },
         {
           type: 'PERSONAL',
           address: 'algo@algo2.cl',
           primary: false
         }
       ],
       contactPhones: [
         {
           number: '+56982365612',
           primary: false,
           type: 'HOME'
         },
         {
           number: '+56911111111',
           primary: true,
           type: 'PERSONAL'
         }
       ],
       identityDocuments: [{
         countryCode: general_FMod.countryCode,
         type: general_FMod.type,
         personalNumber: general_FMod.rutaenrolar 
       }]
     }
   };
 };
 window.getParams = function()
 {
   return getParamsCreate_FMod();
 }
 //respuesta_FMod del formulario checkid
 window.callback = function(result) 
 {
   popupPage_FMod.close();
   document.getElementById('cargando').style.display='none';
   if (result.numError == 0) 
   {
     //alert ("OK")
   }
   else
   {
     alert (result.numError +  " " + result.msError);
   }
   return false;
 }
 function enrolar_FMod()
 {
   consulta_sesion_FMod();
 }
 var conexion_FMod;

 function consulta_sesion_FMod()
 {
   conexion_FMod=crearXMLHttpRequest();
   conexion_FMod.open('POST', './consulta_sesion.php', false);
   conexion_FMod.send(null);
   // Devolvemos el resultado
   respuesta_FMod =  conexion_FMod.responseText;   
   arr_resp_FMod  = respuesta_FMod.split('|');
   if (arr_resp_FMod[0] == 'ok')
   {
     general_FMod.baseUrl       = arr_resp_FMod[1];
     general_FMod.session.companyId   = arr_resp_FMod[2];
     general_FMod.username      = arr_resp_FMod[4];
     general_FMod.session.id      = arr_resp_FMod[6];
     general_FMod.countryCode     = arr_resp_FMod[7];
     general_FMod.type        = arr_resp_FMod[8];
     general_FMod.apiKey        = arr_resp_FMod[9];

     var rut           = document.getElementById('usuarioid_FMod').value; 
     general_FMod.rutoperador     = rut.replace ("-","");

     var rut           = document.getElementById('rut').value; 
     general_FMod.rutaenrolar     = rut.replace ("-","");
     openCheckId_FMod("CREATE");
   }
   else
   {
     alert (respuesta_FMod);
   }
 }

 /*templates\generar_documentos_masivos_FormularioAgregar.html*/

 
 function InicioGenerarMasivo(){
    $("#GENERAR").attr('disabled', true);
    $(".idTipoDoc_GMasivo").attr("disabled",true);
    $(".idProceso_GMasivo").attr("disabled",true);
    $(".idPlantilla_GMasivo").attr("disabled",true);
    $(".idFirma_GMasivo").attr("disabled",true);
    $("#Documento").attr("disabled",true);
    $(".btn-file").attr("disabled",true);
    $(".archivo_GMasivo").attr("disabled",true);
    if ($('#progreso').attr('max') != '')
    {
      estadoGeneracionMasiva_GMasivos();
    }
  };

  var consulta_GMasivo;
  function estadoGeneracionMasiva_GMasivos()
  {
      $('#estado').show();
  	  consultaEstado_GMasivo();
      consulta_GMasivo = setInterval(function(){ consultaEstado_GMasivo() }, 10000);
  }

  function consultaEstado_GMasivo()
  {
    conexion=crearXMLHttpRequest();
    conexion.open('POST', 'importarExcel_MasivoEstado.php?accion=ESTADO&IdArchivo=1',false);
    conexion.send(null);
    try
    {
      respuesta = JSON.parse(conexion.responseText);
    }
    catch (e)
    {
      respuesta.actual = 0;
    }
    /*if (!('actual' in respuesta))
    {
      
    }*/
    //console.log(respuesta);
    respuesta.actual = respuesta.actual == null ? 0 : respuesta.actual;
    $('#progreso').val(respuesta.actual);
    $('#estadodetalle').html(respuesta.actual + " filas procesadas de un total de " +  ($('#progreso').attr('max')) + " filas.");
	
    //console.log(controlMemory_GMasivo(respuesta.actual));
    if (controlMemory_GMasivo(respuesta.actual))
    {
	
      if (respuesta.actual == $('#progreso').attr('max'))
      {	
        clearInterval(consulta_GMasivo);
		//sleep(3000);
        $('#estado').hide();
        alert('El proceso de generacion masiva de documentos ha finalizado.');
      }
    }
    else
    {
      matarProceso_GMasivo();
      clearInterval(consulta_GMasivo);
      $('#estado').hide();
      alert('Ha ocurrio un error, revise detalle y ejecute nuevamene las filas no procesadas.');
    }
  }

  function matarProceso_GMasivo()
  {
    conexion=crearXMLHttpRequest();
    conexion.open('POST', 'importarExcel_Masivo.php?accion=KILL',false);
    conexion.send(null);
    memoryCount = 0;
    //respuesta = JSON.parse(conexion.responseText);
    //respuesta.actual = respuesta.actual == null ? 0 : respuesta.actual;
  }
  var memory = '';
  var memoryTop = 10;
  var memoryCount = 0;
  function controlMemory_GMasivo(dato)
  {
    var respuesta = true;
    if (memory == dato)
    {
      memoryCount++;
    }
    else
    {
      memoryCount = 0;
    }
    if (memoryCount >= memoryTop)
    {
      respuesta = false;
    }
    memory = dato;
    console.log(dato, memory, memoryCount, memoryTop, respuesta);
    return respuesta;
  }

 $(".idProceso_GMasivo").change(function(){

    if( $(".idProceso_GMasivo").val() != 0 )
      $(".idPlantilla_GMasivo").attr("disabled",false);
    else{
        $(".idPlantilla_GMasivo").attr("disabled",true);
        $(".idFirma_GMasivo").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_GMasivo").attr("disabled",true);

        $(".idProceso_GMasivo").val(0);
        $(".idPlantilla_GMasivo").val(0);
        $(".idFirma_GMasivo").val(0);
		$(".orden").val('');
        $("#Documento").val('');
        $(".archivo_GMasivo").val();
    }
 });

  $(".idFirma_GMasivo").change(function(){

    if( $(".idFirma_GMasivo").val() != 0 ){
      $("#Documento").attr("disabled",false);
      $(".btn-file").attr("disabled",false);
      $(".archivo_GMasivo").attr("disabled",false);
    }else{
      $("#Documento").val('');
      $("#Documento").attr("disabled",true);
      $(".btn-file").attr("disabled",true);
      $(".archivo_GMasivo").attr("disabled",true);
    }
  });

  var cantidad_firmantes_GMasivo;

  $(".idPlantilla_GMasivo").change(function(){

      if( $(".idPlantilla_GMasivo").val() != 0 ){

        $(".idFirma_GMasivo").attr("disabled",false);
		$(".idFirma_GMasivo").val(0);
         	
		//Ocultar firmantes 
		$("#icono_representante").hide();
		$("#GENERAR").attr('disabled', true);
		$("#Documento").val('');
		$(".archivo_GMasivo").val('');
          
        $("#Representates").val("");
        $("#Empleado").val("");
        $("#Cantidad_Firmantes").val("");
        $("#idWF").val(0);
		$(".orden").val('');

        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");

        $('input[type=checkbox]').prop('checked', false);
        $("#step-1").collapse('hide');

        var idPlantilla = $(".idPlantilla_GMasivo").val();

        if( idPlantilla != 0 ){
          var url  = "Generar_Documentos_Masivos1_ajax.php";
          var parametros = "idPlantilla=" + idPlantilla;

          // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
          if( window.XMLHttpRequest )
            ajax_GMasivo = new XMLHttpRequest(); // No Internet Explorer
          else
            ajax_GMasivo = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
         
          // Almacenamos en el control al funcion que se invocara cuando la peticion
          // cambie de estado 
          ajax_GMasivo.onreadystatechange = funcionCallback_Plantilla_GMasivo;

          // Enviamos la peticion
          ajax_GMasivo.open( "POST", url, true );
          ajax_GMasivo.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          ajax_GMasivo.send( parametros );
        }
      }else{
      
        $(".idFirma_GMasivo").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_GMasivo").attr("disabled",true);

        $(".idPlantilla_GMasivo").val(0);
        $(".idFirma_GMasivo").val(0);
        $("#Documento").val('');
        $(".archivo_GMasivo").val();
        $("#idWF").val(0);
		$(".orden").val('');
      }
     
  });

  function ver_GMasivo()
  {
    document.getElementById("resultado").submit();
  }
  function funcionCallback_Plantilla_GMasivo()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_GMasivo.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_GMasivo.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_GMasivo.innerHTML = "<b>"+ajax_GMasivo.responseText+"</b>"; 
        salida_GMasivo = ajax_GMasivo.responseText;

        if( salida_GMasivo != '' ){ 
            var datos = JSON.parse(salida_GMasivo);
            var cont = 0; 
            var cant_firm = 0;
            var cant_firm = datos.length;

            $.each(datos,function(key, registro) {


			   if ( cant_firm == 1 ){
					 if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11){ //Reresentante - Representante 2 - Notario - Representante 3
						$("#Representantes").val("1");
						$("#Empleado").val("");
						cont++;
					 }
					 if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
						$("#Representantes").val("");
					 } 
				}  
				else 
				{ 	
					if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11 ){ //Reresentante - Representante 2 - Notario - Representante 3
						cont++; 
						$("#Representantes").val(cont);
					 }
					 if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
					 }
				}
				$("#Cantidad_Firmantes").val(cont); 
				$("#idWF").val(registro.idWF); 
			});
        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }

  $(".archivo_GMasivo").change(function(){
   
    $("#Documento").val($(".archivo_GMasivo").val());

    if( $("#Documento").val() != '' ){
      mostrarFirmantes_GMasivo();  

      if ( $("#Representantes").val() == '' ){
        $("#GENERAR").attr('disabled', false);
      }
    }

  });

	/*********************/
	/**FILTRAR PLANTILLA**/
	/********************/
  $(".idTipoDoc_GMasivo").change(function(){

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html(""); 

    if( $(".idTipoDoc_GMasivo").val() != 0 ){
    

      var empresa = $(".RutEmpresa_GMasivo").val();
      var idTipoDoc = $(".idTipoDoc_GMasivo").val();
      $(".plan").remove();

      if( idTipoDoc != 0 && empresa != 0 ){
        var url  = "Generar_Documentos_Masivos_ajax.php";
        var parametros = "RutEmpresa=" + empresa + "&idTipoDoc=" + idTipoDoc;
     
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_GMasivo = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_GMasivo = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_GMasivo.onreadystatechange = funcionCallback_GMasivo;

        // Enviamos la peticion
        ajax_GMasivo.open( "POST", url, true );
        ajax_GMasivo.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_GMasivo.send(parametros);
      }
    }else{
       
        $(".idProceso_GMasivo").attr("disabled",true);
        $(".idPlantilla_GMasivo").attr("disabled",true);
        $(".idFirma_GMasivo").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_GMasivo").attr("disabled",true);

        $(".idTipoDoc_GMasivo").val(0);
        $(".idProceso_GMasivo").val(0);
        $(".idPlantilla_GMasivo").val(0);
        $(".idFirma_GMasivo").val(0);
        $("#Documento").val('');
        $(".archivo_GMasivo").val();
        $("#idWF").val(0);
		$(".orden").val('');
    }

  });

  var ajax_GMasivo;
  var salida_GMasivo;
 
  function funcionCallback_GMasivo()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_GMasivo.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_GMasivo.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_GMasivo.innerHTML = "<b>"+ajax_GMasivo.responseText+"</b>"; 
        salida_GMasivo = ajax_GMasivo.responseText;

        if( salida_GMasivo != '' ){
            var datos = JSON.parse(salida_GMasivo);
           
            $.each(datos,function(key, registro) {
                $(".idPlantilla_GMasivo").append('<option class="plan" ' + registro.Aprobado + ' value='+ registro.idPlantilla +'>'+ registro.Descripcion_Pl+'</option>');                                      
            }); 

            $('.idProceso_GMasivo').attr("disabled",false);  

        }else{
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("La Empresa seleccionada no tiene Plantillas aprobadas o asociadas"); 
            
            $(".idProceso_GMasivo").attr("disabled",true);
            $(".idPlantilla_GMasivo").attr("disabled",true);
            $(".idFirma_GMasivo").attr("disabled",true);
            $("#Documento").attr("disabled",true);
            $(".btn-file").attr("disabled",true);
            $(".archivo_GMasivo").attr("disabled",true);

            $(".idTipoDoc_GMasivo").val(0);
            $(".idProceso_GMasivo").val(0);
            $(".idPlantilla_GMasivo").val(0);
            $(".idFirma_GMasivo").val(0);
            $("#Documento").val('');
            $(".archivo_GMasivo").val();
            $("#idWF").val(0);
			$(".orden").val('');
        }
      }
    }
  }
 
  /*****************/
  /**IMPORTAR EXCEL**/
  /******************/
   
  
	function uploadFile_GMasivo(file)
	{
		importar_GMasivo(file,1);
	}

  function importar_GMasivo(file,IdArchivo)
	{
    var xhr =  createXMLHttp();

		xhr.upload.addEventListener('loadstart',function(e){
		document.getElementById('mensaje').innerHTML = 
		'Cargando archivo...';
		}, false);

		xhr.upload.addEventListener('load',function(e){
		document.getElementById('mensaje').innerHTML = '';
		}, false);

		xhr.upload.addEventListener('error',function(e){
		alert('Ha habido un error :/');
		}, false);

		var datos = $('#formulario').serialize();
    //console.log(datos);
    var rutempresa = $(".RutEmpresa_GMasivo").val();
		var url  = 'importarExcel_Masivo.php?accion=LOAD&IdArchivo='+ IdArchivo + '&RutEmpresa=' + rutempresa + '&datos=' + datos;

		xhr.open('POST',url,false);//se le agrego false para que sea sincrono, para que espere antes de comenzar a cargar el otro archivo.
	
		xhr.setRequestHeader("Cache-Control", "no-cache");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader("X-File-Name", file.name);

		xhr.addEventListener('readystatechange', function(e) {
			
			if( this.readyState == 4 ) {
				try
				{
				  respuesta = JSON.parse(xhr.responseText);
				  if(IdArchivo == 1 )
				  {
					$('#progreso').attr('max', respuesta.highestRow);
					procesar_GMasivo(IdArchivo);
					estadoGeneracionMasiva_GMasivos();
					//document.getElementById("resultado").submit();
					//return;
				  }
				}
				catch (e)
				{
				  respuesta = xhr.responseText;
				  var elementoError   = document.getElementById("mensajeError");
				  elementoError.innerHTML = respuesta;
				  elementoError.className += "callout callout-danger";
				}

				/*respuesta = JSON.parse(xhr.responseText);
				if (respuesta.hasOwnProperty('highestRow'))//cuando sepa que fue bien sea vacio sea ok realiza el submit
				{ 
					if(IdArchivo == 1 )
				{
				$('#progreso').attr('max', respuesta.highestRow);
				procesar_GMasivo(IdArchivo);
				estadoGeneracionMasiva_GMasivos();
				//document.getElementById("resultado").submit();
				//return;
				}
				}
				else
				{
					var elementoError   = document.getElementById("mensajeError");
					elementoError.innerHTML = respuesta;
					elementoError.className += "callout callout-danger";
				}*/
			}
			if( IdArchivo == 1 )
			{
        document.getElementById("Documento").value = "";
				document.getElementById("archivo").value = "";
				OcultarCargando();
			} 
		});
		xhr.send(file);
	}
  function procesar_GMasivo(IdArchivo)
  {
    conexion=crearXMLHttpRequest();
    var datos = $('#formulario').serialize();
    //console.log(datos);
    //RutEmpresa=76012676-4&idTipoDoc=1&idProceso=4&idPlantilla=1&idFirma=1&Documento=C%3A%5Cfakepath%5CVariables_SMU_23082019_1.xlsx&orden_12382466-0=&Firmantes_Emp%5B%5D=13559051-7&orden_13559051-7=&orden_26131316-2=&idWF=4&Representantes=1&Empleado=1&Cantidad_Firmantes=1&input_empleado=&ACTIVAR=
    parametros = '?idPlantilla=' + $('.idPlantilla_GMasivo').val() + '&idProceso=' + $('.idProceso_GMasivo').val();
    parametros = parametros + '&RutEmpresa=' + $('.RutEmpresa_GMasivo').val() + '&IdArchivo='+ IdArchivo + '&datos='+ datos + '&accion=LOOP0&test[]=123&test[]=456';
    conexion.open('POST', 'importarExcel_Masivo.php' + parametros);
    conexion.send(null);
    respuesta =  conexion.responseText;	
    if(IdArchivo == 1 )
    {
      //document.getElementById("resultado").submit();
    }
  }
	function subirArchivo_GMasivo(){

		//revisa si hay algun cambio en el archivo
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
    MostrarCargando();
    if (validar_GMasivo() == true){
				upload_input = document.querySelectorAll('.archivo_GMasivo')[0];
				uploadFile_GMasivo( upload_input.files[0] );
		}
	}

  function mostrarFirmantes_GMasivo(){

      var Representantes = $("#Representantes").val();

      if( Representantes != '' ){
        $("#icono_representante").show();
        $("#label").show();
      }
  }

  /*******************************/
  /**BUSCAR FIRMANTES DE EMPRESA**/
  /*******************************/

   $(".RutEmpresa_GMasivo").change(function(){
            
      var RutEmpresa = $(".RutEmpresa_GMasivo").val();

      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
      $(".idTipoDoc_GMasivo").attr("disabled",true);
      $(".idProceso_GMasivo").attr("disabled",true);
      $(".idPlantilla_GMasivo").attr("disabled",true);
      $(".idFirma_GMasivo").attr("disabled",true);
      $("#Documento").attr("disabled",true);
      $(".btn-file").attr("disabled",true);
      $(".archivo_GMasivo").attr("disabled",true);

      $(".idTipoDoc_GMasivo").val(0);
      $(".idProceso_GMasivo").val(0);
      $(".idPlantilla_GMasivo").val(0);
      $(".plan").remove();
      $(".idFirma_GMasivo").val(0);
      $("#Documento").val('');
      $(".archivo_GMasivo").val();
      $("#idWF").val(0);
	  $(".orden").val('');

      //Limpiar tabla 
      $(".fila").remove();

      //Ocultar firmantes 
      $("#icono_representante").hide();
      $("#label").hide();
      $("#step-1").collapse('hide');

      if( RutEmpresa != 0 ){
        
        $(".idTipoDoc_GMasivo").attr("disabled",false);
        $(".idTipoDoc_GMasivo").val(0);

        var url  = "Generar_Documentos_Masivos2_ajax.php";
        var parametros  = "RutEmpresa=" + RutEmpresa;
    
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_GMasivo = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_GMasivo = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_GMasivo.onreadystatechange = funcionCallback_Empresa_GMasivo;

        // Enviamos la peticion
        ajax_GMasivo.open( "POST", url, true );
        ajax_GMasivo.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_GMasivo.send(parametros);
      }
  });

  function funcionCallback_Empresa_GMasivo()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_GMasivo.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_GMasivo.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_GMasivo.innerHTML = "<b>"+ajax_GMasivo.responseText+"</b>"; 
        salida_GMasivo = ajax_GMasivo.responseText;

        if( salida_GMasivo != '' ){ 
            var datos = JSON.parse(salida_GMasivo);

            $.each(datos,function(key, registro) {
              //  $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_<php:filamenos />'  onclick='seleccion_GMasivo(<php:filamenos />);'/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto  + "</td><td>" +  registro.descripcion + "</td></tr>");                                 
			  x = "onclick=\"seleccion_GMasivo(\'" + registro.personaid + "\');\"";
              $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_"+ registro.personaid +"' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");                                 
           
		   });          
        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }

  function seleccion_GMasivo(id){

      var num = $('input[type=checkbox]:checked').length;
      var cantidad_firmantes_GMasivo = $("#Cantidad_Firmantes").val(); 
      var representantes = $("#Representantes").val();
		
      if ( cantidad_firmantes_GMasivo == '' ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar la Plantilla" );
          $(".idPlantilla_GMasivo").focus();
          $("#GENERAR").attr('disabled', true);
          return false;
      }
	  
	  if ( num < cantidad_firmantes_GMasivo ){
		 $("#GENERAR").attr('disabled',true);
	  }
	  
      if ( num > cantidad_firmantes_GMasivo ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes_GMasivo + " Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
          $("#GENERAR").attr('disabled', true);
      }
	  
	  if( num == cantidad_firmantes_GMasivo ){
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
		  $("#GENERAR").attr('disabled', false);
      }
	
	  if( $("#orden_" + id ).val() == '' ){ //Seleccionado
		if ( cantidad_firmantes_GMasivo > 1 ){
			$("#orden_" + id).val(num);
		}
	  }else{//Deseleccionado
		if ( cantidad_firmantes_GMasivo > 1 ){
			
			if( confirm(' Desea reiniciar el orden de los firmantes ?')){
				$(".orden").val('');
				$(".f_emp").prop('checked',false);
			}else{	
			  $("#orden_" + id).val('');
				
				//Colocar orden 
				var rutfirmante = $("#emp_" + id).val();
				
				convertirArreglo_emp_GMasivo();
				nuevo_emp = convertirItem_GMasivo(arreglo_emp, orden_emp);
				var items = nuevo_emp;

				items.sort(function (a, b) {
				  if (a.orden > b.orden) {
					return 1;
				  }
				  if (a.orden < b.orden) {
					return -1;
				  }
				  // a must be equal to b
				  return 0;
				});
					
				reasignarOrden_GMasivo(items,'');
			

			}
		}
	  }
  }
  	
	/****************************/
	/**OPERACIONES DE SELECCION**/
	/****************************/
	
	function reasignarOrden_GMasivo(items,emp){
		
		for ( i = 0; i < items.length ; i++ ){
			var rut = $("#" + items[i].id).val(); 
			
			if( emp != '' )
				$("#orden_" + emp + rut).val(i+1);
			else
				$("#orden_" + rut).val(i+1);
		}
	}

	function convertirArreglo(){
		
		arreglo = [];
		var rut = '';
		var orden_id = '';

		$(".f_cli").each(function (index) {  
			
			if( $(this).is(':checked') ){
				rut = this.id;
				orden_id = $("#orden_"+ $("#" + this.id).val()).val();
				arreglo.push(this.id);
				orden.push(orden_id);
			}
		});
	}

	function convertirArreglo_emp_GMasivo(){
		
		arreglo_emp = [];
		orden_emp = [];
		var rut = '';
		var orden_id = '';

		$(".f_emp").each(function (index) {  
			
			if( $(this).is(':checked') ){
				rut = this.id;
				orden_id = $("#orden_" + $("#" + this.id).val()).val();
				arreglo_emp.push(this.id);
				orden_emp.push(orden_id);
			}
		});
	}

	function convertirItem_GMasivo(arreglo1,arreglo2){

    var data = new Object();
    var array = new Array();

    $.each(arreglo1, function (ind, elem){ 

      var data = new Object();

      data.id = elem;
      data.orden = arreglo2[ind];
      array[ind] = data;
    }); 	

    return array;
  }

  function validar_GMasivo(){

    if ( $(".RutEmpresa_GMasivo").val() == 0 ){
        $(".RutEmpresa_GMasivo").focus();
        return false;
    }
    if ( $(".idTipoDoc_GMasivo").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de documento");
        $(".idTipoDoc_GMasivo").focus();
        return false;
    }
  
    if( $(".idProceso_GMasivo").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione un Proceso");
        $(".idProceso_GMasivo").focus();
        return false;
    }

    if( $(".idPlantilla_GMasivo").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione la plantilla");
        $(".idPlantilla_GMasivo").focus();
        return false;
    }
    if( $(".idFirma_GMasivo").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de firma del documento");
        $(".idFirma_GMasivo").focus();
        return false;
    }
    if( $("#Documento").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el archivo de carga que necesita");
        $("#Documento").focus();
        return false;
    }
    return true;
  }

  /*templates\generar_documentos_PorFicha_Listado.html*/

  $('.buscar_GPorFicha').keypress(function(e) {
    var key = e.which;
    if (key == 13) {
      return false;
    }
  });

  $('.btn-buscar_GPorFicha').click(function(){
    $("#nombrex").val($("#buscar").val());
    $("#pagina").val(1);
    $("#paginado").submit();
  });

/*templates\generar_documentos_PorFicha_FormularioAgregar.html*/
$('#datosDocumento').on('click', function () {
    if ($('.idPlantilla_GPorFichaA').val() == 0) {
        alert('Debe seleccionar una plantilla para acceder a esta seccion');
    }
});
var idPlantillaajax;
idPlantillaajax = $("#var_idPlantilla").val();

var ajaxVariablesPlantilla;
var salida_GPorFichaAVariablesPlantilla;
var datosDocumento = [];
var datosDocumentoAux = [];
function getVariablesPlantilla() {
    var url = "Generar_Documentos_Masivos3_ajax.php";
    var parametros = "idPlantilla=" + $('.idPlantilla_GPorFichaA').val();
    if (window.XMLHttpRequest)
        ajaxVariablesPlantilla = new XMLHttpRequest(); // No Internet Explorer
    else
        ajaxVariablesPlantilla = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    ajaxVariablesPlantilla.onreadystatechange = funcionCallback_getVariablesPlantilla;
    ajaxVariablesPlantilla.open("POST", url, true);
    ajaxVariablesPlantilla.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajaxVariablesPlantilla.send(parametros);
}

function funcionCallback_getVariablesPlantilla() {
    if (ajaxVariablesPlantilla.readyState == 4) {
        if (ajaxVariablesPlantilla.status == 200) {
            salida_GPorFichaAVariablesPlantilla = ajaxVariablesPlantilla.responseText;
            if (salida_GPorFichaAVariablesPlantilla != '') {
                datosDocumento = JSON.parse(salida_GPorFichaAVariablesPlantilla);
                for (var i = 0; i < datosDocumento.length; i++) {
                    $('#' + datosDocumento[i].Variable).show();
                }
            }
            else {
                $("#mensajeError").removeClass("callout callout-warning");
                $("#mensajeError").html("");
            }
        }
    }
}

function ocultaDatosDocumento() {
    $('.datosDocumento').hide();
}

$('.GENERAR_GPorFichaA').on('click', function () {

    if (validacionGenerar_GPorFichaA()) {
        $('#nombre').val(encodeURIComponent($('#nombre').val()));
        $('#nacionalidad').val(encodeURIComponent($('#nacionalidad').val()));
        $('#direccion').val(encodeURIComponent($('#direccion').val()));
        $('#comuna').val(encodeURIComponent($('#comuna').val()));
        $('#ciudad').val(encodeURIComponent($('#ciudad').val()));

        $('#formulario').submit();

    }
});

function InicioGenerarDocsPorFichaA() {

    if (idPlantillaajax != 0) {
        $(".GENERAR_GPorFichaA").attr('disabled', false);
        $(".idTipoDoc_GPorFichaA").attr("disabled", false);
        $(".idProceso_GPorFichaA").attr("disabled", false);
        $(".idPlantilla_GPorFichaA").attr("disabled", false);
        $(".idTipoFirma_GPorFichaA").attr("disabled", false);
        $("#FechaDocumento").attr("disabled", false);
        change_RutEmpresa_GPorFichaA();
        filtrarPlantilla_GPorFichaA();
        change_Plantilla();
        change_TipoFirma();
    }
    else {
        $(".GENERAR_GPorFichaA").attr('disabled', true);
        $(".idTipoDoc_GPorFichaA").attr("disabled", true);
        $(".idProceso_GPorFichaA").attr("disabled", true);
        $(".idPlantilla_GPorFichaA").attr("disabled", true);
        $(".idTipoFirma_GPorFichaA").attr("disabled", true);
        $("#FechaDocumento").attr("disabled", true);
    }
};


$(".idProceso_GPorFichaA").change(function () {

    if ($(".idProceso_GPorFichaA").val() != 0)
        $(".idPlantilla_GPorFichaA").attr("disabled", false);
    else {
        $(".idPlantilla_GPorFichaA").attr("disabled", true);
        $(".idTipoFirma_GPorFichaA").attr("disabled", true);
        $(".idProceso_GPorFichaA").val(0);
        $(".idPlantilla_GPorFichaA").val(0);
        $(".idTipoFirma_GPorFichaA").val(0);
        $("#idWF").val(0);
        $(".orden").val('');
    }
});

function btnSiguiente_GPorFichaA() {

    if ($('#personaldata').hasClass('active')) {
        $("#generacion").click();
    }
    else if ($('#generaciondata').hasClass('active') && $('.idPlantilla_GPorFichaA').val() > 0) {
        $("#datosDocumento").click();
    }
    else if ($('.idPlantilla_GPorFichaA').val() == 0) {
        alert('Debe seleccionar una plantilla para acceder a esta seccion');
    }
    if ($('#documentdata').hasClass('active')) {
        $("#btnsiguiente").hide();
    }

}

$("#datosPersonales").click(function () {
    $("#btnsiguiente").show();
});

$("#generacion").click(function () {
    $("#btnsiguiente").show();
});

$("#datosDocumento").click(function () {
    $("#btnsiguiente").hide();
});

$(".idPlantilla_GPorFichaA").change(function () {
    idPlantillaajax = 0;
    $(".idTipoFirma_GPorFichaA").attr("disabled", false);
    $(".idTipoFirma_GPorFichaA").val(0);
    $(".orden").val('');

    //$("#Documento").val('');

    //Ocultar firmantes 
    $("#icono_representante").hide();
    $(".GENERAR_GPorFichaA").attr('disabled', true);

    $("#Representates").val("");
    $("#Empleado").val("");
    $("#Cantidad_Firmantes").val("");

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");

    $('input[type=checkbox]').prop('checked', false);
    $("#step-1").collapse('hide');

    change_Plantilla();
});

function change_Plantilla() {
    ocultaDatosDocumento();
    if ($(".idPlantilla_GPorFichaA").val() != 0) {
        var idPlantilla = $(".idPlantilla_GPorFichaA").val();

        if (idPlantilla != 0) {
            getVariablesPlantilla();
            var url = "Generar_Documentos_Masivos1_ajax.php";
            var parametros = "idPlantilla=" + idPlantilla;

            // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
            if (window.XMLHttpRequest)
                ajax = new XMLHttpRequest(); // No Internet Explorer
            else
                ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

            // Almacenamos en el control al funcion que se invocara cuando la peticion
            // cambie de estado 
            ajax_GPorFichaA.onreadystatechange = funcionCallback_Plantilla_GPorFichaA;

            // Enviamos la peticion
            ajax_GPorFichaA.open("POST", url, false);
            ajax_GPorFichaA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax_GPorFichaA.send(parametros);
        }
    }
    else {

        $(".idTipoFirma_GPorFichaA").attr("disabled", true);
        $("#Documento").attr("disabled", true);
        $(".btn-file").attr("disabled", true);
        $("#archivo").attr("disabled", true);

        $(".idPlantilla_GPorFichaA").val(0);
        $(".idTipoFirma_GPorFichaA").val(0);
        $("#Documento").val('');
        $("#archivo").val();
        $("#idWF").val(0);
        $(".orden").val('');
    }

}

function funcionCallback_Plantilla_GPorFichaA() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_GPorFichaA.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_GPorFichaA.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_GPorFichaA.innerHTML = "<b>"+ajax_GPorFichaA.responseText+"</b>"; 
            salida_GPorFichaA = ajax_GPorFichaA.responseText;

            if (salida_GPorFichaA != '') {
                var datos = JSON.parse(salida_GPorFichaA);
                var cont = 0;
                var cant_firm = 0;
                cant_firm = datos.length;

                $.each(datos, function (key, registro) {
                    if (cant_firm == 1) {
                        if (registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11) { //Reresentante - Representante 2 - Notario - Representante 3
                            $("#Representantes").val("1");
                            $("#Empleado").val("");
                            cont++;
                        }
                        if (registro.idEstado == 3) {
                            $("#Empleado").val("1");
                            $("#Representantes").val("");
                        }
                    }
                    else {
                        if (registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11) { //Reresentante - Representante 2 - Notario - Representante 3
                            cont++;
                            $("#Representantes").val(cont);
                        }
                        if (registro.idEstado == 3) {
                            $("#Empleado").val("1");
                        }
                    }

                    $("#Cantidad_Firmantes").val(cont);
                    $("#idWF").val(registro.idWF);

                });
                if (numFirmantesEmpresa_GPorFichaA < cont) {
                    $("#mensajeError").addClass("callout callout-warning");
                    $("#mensajeError").html("La Empresa solo posee " + numFirmantesEmpresa_GPorFichaA + " Representante/s.La Empresa debe tener al menos  " + cont + ". ");
                }

            } else {
                $("#mensajeError").removeClass("callout callout-warning");
                $("#mensajeError").html("");
            }
        }
    }
}

/*********************/
/**FILTRAR PLANTILLA**/
/********************/
$(".idTipoDoc_GPorFichaA").change(function () {
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
    filtrarPlantilla_GPorFichaA();
});
function filtrarPlantilla_GPorFichaA() {

    if ($(".idTipoDoc_GPorFichaA").val() != 0) {
        var empresa = $(".RutEmpresa_GPorFichaA").val();
        var idTipoDoc = $(".idTipoDoc_GPorFichaA").val();
        $(".plan").remove();

        if (idTipoDoc != 0 && empresa != 0) {
            var url = "Generar_Documentos_Masivos_ajax.php";
            var parametros = "RutEmpresa=" + empresa + "&idTipoDoc=" + idTipoDoc;

            // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
            if (window.XMLHttpRequest)
                ajax = new XMLHttpRequest(); // No Internet Explorer
            else
                ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

            // Almacenamos en el control al funcion que se invocara cuando la peticion
            // cambie de estado 
            ajax_GPorFichaA.onreadystatechange = funcionCallback_GPorFichaA;

            // Enviamos la peticion
            ajax_GPorFichaA.open("POST", url, false);
            ajax_GPorFichaA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax_GPorFichaA.send(parametros);
        }
    }
    else {
        $(".idProceso_GPorFichaA").attr("disabled", true);
        $(".idPlantilla_GPorFichaA").attr("disabled", true);
        $(".idTipoFirma_GPorFichaA").attr("disabled", true);
        $("#Documento").attr("disabled", true);
        $(".btn-file").attr("disabled", true);
        $("#archivo").attr("disabled", true);

        $(".idTipoDoc_GPorFichaA").val(0);
        $(".idProceso_GPorFichaA").val(0);
        $(".idPlantilla_GPorFichaA").val(0);
        $(".idTipoFirma_GPorFichaA").val(0);
        $("#Documento").val('');
        $("#archivo").val();
        $("#idWF").val(0);
        $(".orden").val('');
    }

}



function funcionCallback_GPorFichaA() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_GPorFichaA.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_GPorFichaA.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_GPorFichaA.innerHTML = "<b>"+ajax_GPorFichaA.responseText+"</b>"; 
            salida_GPorFichaA = ajax_GPorFichaA.responseText;

            if (salida_GPorFichaA != '') {
                var datos = JSON.parse(salida_GPorFichaA);

                $.each(datos, function (key, registro) {
                    if (registro.Aprobado == "disabled") {
                        $(".idPlantilla_GPorFichaA").append('<option class="plan" ' + registro.Aprobado + ' value=' + registro.idPlantilla + '>' + registro.Descripcion_Pl + ' (sin aprobar)' + '</option>');
                    }
                    else {
                        $(".idPlantilla_GPorFichaA").append('<option class="plan" ' + registro.Aprobado + ' value=' + registro.idPlantilla + '>' + registro.Descripcion_Pl + '</option>');
                    }
                });
                if (idPlantillaajax_GPorFichaA.length > 0) {
                    $(".idPlantilla_GPorFichaA").val(idPlantillaajax);
                }
                $('.idProceso_GPorFichaA').attr("disabled", false);

            } else {
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La Empresa seleccionada no tiene Plantillas aprobadas o asociadas");

                $(".idProceso_GPorFichaA").attr("disabled", true);
                $(".idPlantilla_GPorFichaA").attr("disabled", true);
                $(".idTipoFirma_GPorFichaA").attr("disabled", true);
                $(".idTipoDoc_GPorFichaA").val(0);
                $(".idProceso_GPorFichaA").val(0);
                $(".idPlantilla_GPorFichaA").val(0);
                $(".idTipoFirma_GPorFichaA").val(0);
                $("#idWF").val(0);
                $(".orden").val('');
            }
        }
    }
}


function mostrarFirmantes_GPorFichaA() {
    var Representantes = $("#Representantes").val();
    if (Representantes != '') {
        $("#icono_representante").show();
        $("#label").show();
    }
}

/*******************************/
/**BUSCAR FIRMANTES DE EMPRESA**/
/*******************************/

var numFirmantesEmpresa_GPorFichaA;
$(".RutEmpresa_GPorFichaA").change(function () {
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
    $(".idTipoDoc_GPorFichaA").attr("disabled", true);
    $(".idProceso_GPorFichaA").attr("disabled", true);
    $(".idPlantilla_GPorFichaA").attr("disabled", true);
    $(".idTipoFirma_GPorFichaA").attr("disabled", true);
    $("#Documento").attr("disabled", true);
    $(".btn-file").attr("disabled", true);
    $("#archivo").attr("disabled", true);
    $("#FechaDocumento").attr("disabled", true);

    $(".idTipoDoc_GPorFichaA").val(0);
    $(".idProceso_GPorFichaA").val(0);
    $(".idPlantilla_GPorFichaA").val(0);
    $(".idTipoFirma_GPorFichaA").val(0);
    $("#Documento").val('');
    $("#archivo").val();
    $("#idWF").val(0);
    $(".plan").remove();
    $("#rut_orden").val('');
    $("#ordenx").val('');
    $(".orden").val('');
    $("#nombrelugarpago").val("");
    $("#lugarpagoid").val("");
    $("#nombrecentrocosto").val('');
    $("#idCentroCosto").val('');
    change_RutEmpresa_GPorFichaA();
});
var ajaxFirmantes;
var salida_GPorFichaAFirmantes;

function change_RutEmpresa_GPorFichaA() {
    numFirmantesEmpresa_GPorFichaA = 0;
    var RutEmpresa = $(".RutEmpresa_GPorFichaA").val();



    //Limpiar tabla 
    $(".fila").remove();

    //Ocultar firmantes 
    $("#icono_representante").hide();
    $("#label").hide();
    $("#step-1").collapse('hide');

    if (RutEmpresa != 0) {

        /*$(".idTipoDoc_GPorFichaA").attr("disabled",false);
        $(".idTipoDoc_GPorFichaA").val(0);*/

        var url = "Generar_Documentos_Masivos2_ajax.php";
        var parametros = "RutEmpresa=" + RutEmpresa;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajaxFirmantes = new XMLHttpRequest(); // No Internet Explorer
        else
            ajaxFirmantes = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajaxFirmantes.onreadystatechange = funcionCallback_Empresa_GPorFichaA;

        // Enviamos la peticion
        ajaxFirmantes.open("POST", url, false);
        ajaxFirmantes.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxFirmantes.send(parametros);
    }
}

function funcionCallback_Empresa_GPorFichaA() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajaxFirmantes.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajaxFirmantes.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_GPorFichaA.innerHTML = "<b>"+ajax_GPorFichaA.responseText+"</b>"; 
            salida_GPorFichaAFirmantes = ajaxFirmantes.responseText;

            if (salida_GPorFichaAFirmantes != '') {
                var datos = JSON.parse(salida_GPorFichaAFirmantes);
                var x = "";
                var FirmantesCheck;
                var OrdenFirm;
                FirmantesCheck = $("#var_Firmantes_Emp").val();
                OrdenFirm = $("#var_ordenfirmantes").val();
                $.each(datos, function (key, registro) {
                    x = "onclick=\"seleccion_GPorFichaA(\'" + registro.personaid + "\');\"";

                    $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_" + registro.personaid + "' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto + "</td><td>" + registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");
                    numFirmantesEmpresa_GPorFichaA = numFirmantesEmpresa_GPorFichaA + 1;

                });
                var ArrayFirmantes = FirmantesCheck.split("|");
                var ArrayOrdenFirm = OrdenFirm.split("|");
                $.each(ArrayFirmantes, function (key) {
                    $("#emp_" + ArrayFirmantes[key]).prop("checked", true);
                    $("#orden_" + ArrayFirmantes[key]).val(ArrayOrdenFirm[key]);
           
        });
                $("#FechaDocumento").attr("disabled", false);
            } else {
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La empresa seleccionada no posee firmantes.");
            }
        }
    }
}

function seleccion_GPorFichaA(id) {

    var num = $('input[type=checkbox]:checked').length;
    var cantidad_firmantes_GPorFichaA = $("#Cantidad_Firmantes").val();

    var representantes = $("#Representantes").val();
    if (cantidad_firmantes_GPorFichaA == '') {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Plantilla");
        $(".idPlantilla_GPorFichaA").focus();
        $(".GENERAR_GPorFichaA").attr('disabled', true);
        return false;
    }
    if (num < cantidad_firmantes_GPorFichaA) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar un total de " + cantidad_firmantes_GPorFichaA + " Firmante/s, seg&uacute;n el flujo de firma de la Plantilla seleccionada");
        $(".GENERAR_GPorFichaA").attr('disabled', true);
    }
    if (num > cantidad_firmantes_GPorFichaA) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes_GPorFichaA + " Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada");
        $(".GENERAR_GPorFichaA").attr('disabled', true);
    }

    if (num == cantidad_firmantes_GPorFichaA) {
        if ($(".idPlantilla_GPorFichaA").length > 0) {
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
            $(".GENERAR_GPorFichaA").attr('disabled', false);
        }

    }

    if ($("#orden_" + id).val() == '') { //Seleccionado
        if (cantidad_firmantes_GPorFichaA > 1) {
            $("#orden_" + id).val(num);
        }
    } else {//Deseleccionado
        if (cantidad_firmantes_GPorFichaA > 1) {

            if (confirm(' Desea reiniciar el orden de los firmantes ?')) {
                $(".orden").val('');
                $(".f_emp").prop('checked', false);
            } else {
                $("#orden_" + id).val('');

                //Colocar orden 
                var rutfirmante = $("#emp_" + id).val();

                convertirArreglo_emp_GPorFichaA();
                nuevo_emp_GPorFichaA = convertirItem_GPorFichaM(arreglo_emp_GPorFichaA, orden_emp_GPorFichaA);
                var items = nuevo_emp_GPorFichaA;

                items.sort(function (a, b) {
                    if (a.orden > b.orden) {
                        return 1;
                    }
                    if (a.orden < b.orden) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                });

                reasignarOrden_GPorFichaA(items, '');


            }
        }
    }
}
$(".idTipoFirma_GPorFichaA").change(function () {
    change_TipoFirma();
});
function change_TipoFirma() {
    var num = $('input[type=checkbox]:checked').length;
    var cantidad_firmantes_GPorFichaA = $("#Cantidad_Firmantes").val();
    if ($(".idTipoFirma_GPorFichaA").val() != 0) {
        mostrarFirmantes_GPorFichaA();

        if (($("#Representantes").val() == '') || (num == cantidad_firmantes_GPorFichaA)) {
            $(".GENERAR_GPorFichaA").attr('disabled', false);
        }
    } else {
        $("#icono_representante").hide();
        $("#label").hide();
        $("#step-1").collapse('hide');
        $(".GENERAR_GPorFichaA").attr('disabled', true);
    }

}

function validacionGenerar_GPorFichaA() {
    /*respuesta = Checkfiles_GPorFichaA(); console.log(respuesta); return false;
    if (respuesta == false)
    {
        return false;
    }*/

    if ($(".newusuarioid_GPorFichaA").val().length < 9) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Rut no puede estar vac&iacute;o");
        $('.newusuarioid_GPorFichaA').focus();
        return false;
    }

    if (!validaRut2(document.formulario.newusuarioid)) {

        return false;
    }

    if ($("#nombre").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Nombre no puede estar vac&iacute;o");
        $('#nombre').focus();
        return false;
    }

    if ($("#nacionalidad").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Nacionalidad no puede estar vac&iacute;o");
        $('#nacionalidad').focus();
        return false;
    }
    if ($("#direccion").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Direcci&oacute;n no puede estar vac&iacute;o");
        $('#direccion').focus();
        return false;
    }
    if ($("#comuna").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Comuna no puede estar vac&iacute;o");
        $('#comuna').focus();
        return false;
    }
    if ($("#ciudad").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Ciudad no puede estar vac&iacute;o");
        $('#ciudad').focus();
        return false;
    }
    if ($("#correo").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo correo no puede estar vac&iacute;o");
        $('#correo').focus();
        return false;
    }
    if ($("#fechanacimiento").val().length == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo Fecha de Nacimiento no puede estar vac&iacute;o");
        $('#fechanacimiento').focus();
        return false;
    }
    if ($("#idEstadoCivil").val() == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el campo de Estado Civil");
        $('#idEstadoCivil').focus();
        return false;
    }
    if ($("#rolid").val() == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el campo de Rol");
        $('#rolid').focus();
        return false;
    }
    if ($("#nombrecentrocosto").val() == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar Relaci�n Laboral");
        $('#nombrecentrocosto').focus();
        return false;
    }
    if ($("#nombrelugarpago").val() == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar Relaci�n Laboral");
        $('#nombrelugarpago').focus();
        return false;
    }
    if ($("#idEstadoEmpleado").val() == 0) {
        $('#datosPersonales').click();
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar un Estado para el Empleado");
        $('#idEstadoEmpleado').focus();
        return false;
    }
    for (var i = 0; i < datosDocumento.length; i++) {

        if ($('#' + datosDocumento[i].Variable + ' select').length) {
            if ($('#' + datosDocumento[i].Variable + ' select').val() == 0) {
                $('#datosDocumento').click();
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("Debe seleccionar una opci&oacute;n");
                $('#' + datosDocumento[i].Variable + ' select').focus();
                return false;
            }
        }
        if ($('#' + datosDocumento[i].Variable + ' input').length) {
            if ($('#' + datosDocumento[i].Variable + ' input').val().length == 0) {
                $('#datosDocumento').click();
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("Debe agregar informaci&oacute;n");
                $('#' + datosDocumento[i].Variable + ' input').focus();
                return false;
            }
        }
    }
    MostrarCargando();
    return true;
}



$(".newusuarioid_GPorFichaA").change(function () {

    //Limpiar los campos 
    $("#nombre").val('');
    $("#appaterno").val('');
    $("#apmaterno").val('');
    $("#fechanacimiento").val('');
    $("#correo").val('');
    $("#nacionalidad").val('');
    $("#direccion").val('');
    $("#comuna").val('');
    $("#ciudad").val('');
    $("#idEstadoCivil").val(0);
    $("#rolid").val(0);
    $("#idEstadoEmpleado").val(0);

    var respuesta = validaRut2(document.formulario.newusuarioid);

    if (respuesta) {

        var RutUsuario = $(".newusuarioid_GPorFichaA").val();
        var url = "Generar_Documento_PorFicha_ajax.php";
        var parametros = "personaid=" + RutUsuario;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if (window.XMLHttpRequest)
            ajax = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_GPorFichaA.onreadystatechange = funcionCallback_rutusuario_GPorFichaA;

        // Enviamos la peticion
        ajax_GPorFichaA.open("POST", url, true);
        ajax_GPorFichaA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_GPorFichaA.send(parametros);
    }
});

function funcionCallback_rutusuario_GPorFichaA() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_GPorFichaA.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_GPorFichaA.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_GPorFichaA = ajax_GPorFichaA.responseText;
            datos = JSON.parse(salida_GPorFichaA);
            var cant = Object.keys(datos).length;
            if (cant > 0) {
                //console.log(datos);
                $("#nombre").val(datos.nombre);
                $("#appaterno").val(datos.appaterno);
                $("#apmaterno").val(datos.apmaterno);
                $("#fechanacimiento").val(datos.fechanacimiento);
                $("#correo").val(datos.correo);
                $("#nacionalidad").val(datos.nacionalidad);
                $("#direccion").val(datos.direccion);
                $("#comuna").val(datos.comuna);
                $("#ciudad").val(datos.ciudad);
                $("#idEstadoCivil").val(datos.estadocivil);

                var idestado = '#idEstadoCivil option[value="' + datos.estadocivil + '"]';
                $(idestado).attr("selected", true);

                var rolid = '#rolid option[value="' + datos.rolid + '"]';
                $(rolid).attr("selected", true);

                var idestadoEmpleado = '#idEstadoEmpleado option[value="' + datos.idEstadoEmpleado + '"]';
                $(idestadoEmpleado).attr("selected", true);

            }

            else {
                $("#mensajeError").removeClass("callout callout-warning");
                $("#mensajeError").html("");
            }
        }
    }
    else {
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
    }
}



//Modal de Centros de Costo
$(".btn_centro_costo_GPorFichaA").click(function () {
    RutEmpresa = $(".RutEmpresa_GPorFichaA").val();
    //departamento = $("#departamentoid").val();
    LugarPagoid = $("#lugarpagoid").val();
    $(".fila_cc").remove();
    if (RutEmpresa == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    if (LugarPagoid == '') {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar un Lugar Pago");
        return false;
    }

    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    mostrarCC_GPorFichaA();
});

var mostrarCC_GPorFichaA = function () {

    var url = "Generar_Documento_PorFicha_ajax2.php?RutEmpresa=" + RutEmpresa + '&lugarpagoid=' + LugarPagoid;

    var table = $('#tabla_centro_costo').dataTable({
        "ordering": false,
        "destroy": true,
        "method": "POST",
        "ajax": {
            "url": url,
            "dataType": "json",
            "cache": false,
            "dataSrc": ""
        },

        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": '<button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cc_GPorFichaA" id="btn_agregar_cc_GPorFichaA" ><i class="fa fa-plus" aria-hidden="true"></i></button>' //"<button class='btn'>Click!</button>
        }],
        'language': {
            'sLoadingRecords': 'Cargando...',
            'oPaginate': {
                'sFirst': '<<',
                'sLast': '>>',
                'sNext': '>',
                'sPrevious': '<'
            }
        }
    });

    btn_agregar_cc_GPorFichaA("#tabla_centro_costo tbody", table);
    $('#tabla_centro_costo').css("width", "100%");
};
var btn_agregar_cc_GPorFichaA = function (tbody, table) {
    $(tbody).on("click", "tr", function () {
        var codigo = $(this).find("td:first").html();
        var nombre = $(this).find('td:eq(1)').html();
        console.log(codigo, nombre);
        agregarCC_GPorFichaA(codigo, nombre);
    });
}

function agregarCC_GPorFichaA(i, j) {
    $("#centrocostoid").val(i);
    $("#nombrecentrocosto").val(j);
    $("#cerrar_centro_costo").click();

    $(".idTipoDoc_GPorFichaA").attr("disabled", false);
    $(".idTipoDoc_GPorFichaA").val(0);
}

function Checkfiles_GPorFichaA() {

    var fup = document.getElementById('pdf64');
    var fileName = fup.value;

    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    if (ext == "pdf" || ext == "PDF") {
        return true;
    }
    else {
        alert("Solo se pueden seleccionar archivos pdf !");
        fup.focus();
        return false;
    }
}

var RutEmpresa_GPorFichaA;
//Modal de Lugares de pago 
$("#btn_lugares_pago").click(function () {
    RutEmpresa_GPorFichaA = $(".RutEmpresa_GPorFichaA").val();
    $(".fila_lp").remove();
    if (RutEmpresa_GPorFichaA == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    mostrarLP_GPorFichaA();
});


var mostrarLP_GPorFichaA = function () {

    var url = "Generar_Documento_PorFicha_ajax1.php?RutEmpresa=" + RutEmpresa;

    var table = $('#tabla_lugares_pago').dataTable({
        "ordering": false,
        "destroy": true,
        "method": "POST",
        "ajax": {
            "url": url,
            "dataType": "json",
            "cache": false,
            "dataSrc": ""
        },

        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": '<button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_lp_GPorFichaA" id="btn_agregar_lp_GPorFichaA" ><i class="fa fa-plus" aria-hidden="true"></i></button>' //"<button class='btn'>Click!</button>
        }],
        'language': {
            'sLoadingRecords': 'Cargando...',
            'oPaginate': {
                'sFirst': '<<',
                'sLast': '>>',
                'sNext': '>',
                'sPrevious': '<'
            }
        }
    });

    btn_agregar_lp_GPorFichaA("#tabla_lugares_pago tbody", table);

    $('#tabla_lugares_pago').css("width", "100%");
};
var btn_agregar_lp_GPorFichaA = function (tbody, table) {
    $(tbody).on("click", "tr", function () {
        var codigo = $(this).find("td:first").html();
        var nombre = $(this).find('td:eq(1)').html();
        //console.log(codigo);

        agregarLp_GPorFichaA(codigo, nombre);
    });
}

function agregarLp_GPorFichaA(i, j) {
    $("#lugarpagoid").val(i);
    $("#nombrelugarpago").val(j);
    $("#cerrar_lugares_pago").click();
    $("#idCentroCosto").val("");
    $("#nombrecentrocosto").val("");
}


$(".VISTA_PREVIA_GPorFichaA").click(function () {

    var form = $("#formulario").serialize();
    var url = "Generar_Documento_PorFicha_ajax5.php";//?" + form;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_GPorFichaA.onreadystatechange = funcionCallback_inputToVar;
    // Enviamos la peticion
    ajax_GPorFichaA.open("POST", url, false);
    ajax_GPorFichaA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_GPorFichaA.send(form);
});

function funcionCallback_inputToVar() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_GPorFichaA.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_GPorFichaA.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_GPorFichaA = ajax_GPorFichaA.responseText;

            if (salida_GPorFichaA === '0') {
                $("#mensajeError").html("Debe seleccionar una Plantilla para poder visualizar el documento a generar");
                $("#mensajeError").addClass("callout callout-warning");
            } else {
                $("#mensajeError").html("");
                $("#mensajeError").removeClass("callout callout-warning");
                $("#contenido").html(salida_GPorFichaA);
                $("#btn_vista_previa").click();
            }

        }
    }
}

function limpiarCampoAccion2(input) {
    $("#accion2").val('');
    $("#accion2").removeClass(input);
}
/****************************/
/**OPERACIONES DE SELECCION**/
/****************************/

function reasignarOrden_GPorFichaA(items, emp) {

    for (i = 0; i < items.length; i++) {
        var rut = $("#" + items[i].id).val();

        if (emp != '')
            $("#orden_" + emp + rut).val(i + 1);
        else
            $("#orden_" + rut).val(i + 1);
    }
}

function convertirArreglo_emp_GPorFichaA() {

    arreglo_emp_GPorFichaA = [];
    orden_emp_GPorFichaA = [];
    var rut = '';
    var orden_id = '';

    $(".f_emp").each(function (index) {

        if ($(this).is(':checked')) {
            rut = this.id;
            orden_id = $("#orden_" + $("#" + this.id).val()).val();
            arreglo_emp_GPorFichaA.push(this.id);
            orden_emp_GPorFichaA.push(orden_id);
        }
    });
}

function convertirItem_GPorFichaM(arreglo1, arreglo2) {

    var data = new Object();
    var array = new Array();

    $.each(arreglo1, function (ind, elem) {

        var data = new Object();

        data.id = elem;
        data.orden = arreglo2[ind];
        array[ind] = data;
    });

    return array;
}

$(".clicktab_GPorFichaA").click(function () {
    let tabid = $(this).attr("id");
    let href = $(this).attr("href");
    $(".clicktab_GPorFichaA").removeClass("active").attr("aria-selected", "false");
    $("#" + tabid).addClass("active").attr("aria-selected", "true");
    $(".tab-pane").removeClass("active").removeClass("show");
    $(href).addClass("active").addClass("show");
});

/*C:\inetpub\wwwroot\sodexo_rbk\templates\importacion_firma_listado.html*/

function iniciar_IFListado()
{
	document.getElementById('estadodetalle').innerHTML =  '0 reprocesados de un total de ' + noenviados;
	MostrarCargando();
	document.getElementById('REPROCESAR').disabled = true;
	document.getElementById('estado').style.display = "block";
	iniciarx_IFListado = setInterval(function(){ reprocesar_IFListado() }, 1000);
}

function reprocesar_IFListado()
{
	clearInterval_IFListado(iniciarx_IFListado);
	conexion_IFListado=crearXMLHttpRequest();
    conexion_IFListado.open('POST', 'importacion_firma_split.php?accion=REPROCESO&pagina=0');
    conexion_IFListado.send(null);
	consulta_IFListado_estado();
	OcultarCargando();
	consulta_IFListado = setInterval(function(){ consulta_IFListado_estado() }, 7000);
}

function consulta_IFListado_estado()
{	
	conexion_IFListado=crearXMLHttpRequest();

	// Preparamos la petici�n con parametros
	var par = "?accion=ESTADOREPROCESO";
	
	conexion_IFListado.open('POST', 'importacion_firma_split.php' + par, false);
	// Realizamos la petici�n
	//alert ("antes de send");
	conexion_IFListado.send(null);
	// Devolvemos el resultado
	respuesta =  conexion_IFListado.responseText;	
	arr_resp  = respuesta.split('|');
	reprocesados = arr_resp[0];
	if (reprocesados != '')
	{	
		resp = validaloop(reprocesados);
	
		if (resp == true)
		{ 
			document.getElementById('progreso').value = reprocesados;
			document.getElementById('estadodetalle').innerHTML = reprocesados + ' reprocesados de un total de ' + noenviados;
			if ( noenviados == reprocesados || reprocesados > noenviados)
			{	
				document.getElementById('estado').style.display = "none";
				clearInterval_IFListado(consulta_IFListado);
				alert ("Proceso Finalizado");
				document.getElementById("procesoanterior").submit();
			}
		}
		else
		{	
			document.getElementById('estado').style.display = "none";
			clearInterval_IFListado(consulta_IFListado);	
			alert ("Problemas al reprocesar, intente nuevamente");
		}
	}
}

function validaloop(reprocesados)
{
	if (document.getElementById('progreso').value == reprocesados)
	{
		mismovalor_IFListado++;
	}
	else
	{
		mismovalor_IFListado = 0;
	}
	
	if (mismovalor_IFListado > cantloop_IFListado)
	{
		return false;
	}
	else
	{
		return true;
	}

}

function inicio_IFListado()
{
	document.getElementById("volver").submit();
}

/*templates\generar_manuales_FormularioAgregar.html*/


function InicioGenerarManualesAgregar(){

    //Empresa
    if ($("#RutEmp").val().length == 2 ){
      $("#RutEmp").val("(Seleccione)");
      $("#GENERAR").prop("disabled", true);
      //Deshabilitar firmantes 
      $(".btn-circle-2").addClass("disabled");
    }
  
    //Cliente
    if( $("#RutCli").val().length == 1 ){
      $("#RutCli").val("(Seleccione)");
      $("#GENERAR").prop("disabled", true);
      //Deshabilitar firmantes 
      $(".btn-circle-2").addClass("disabled");
    }
  
    //Flujo
    if($(".Flujo_GMAgregar").val().length == 0 ){
       $(".Flujo_GMAgregar option:selected").text("(Seleccione)");  
       //Deshabilitar firmantes 
       $(".btn-circle-2").addClass("disabled");
    }
  
    //Modelos de contratos
    if( $(".modelo_contrato_GMAgregar").val().length == 0 ){
      $(".modelo_contrato_GMAgregar option:selected").text("(Seleccione)"); 
      $("#GENERAR").prop("disabled", true); 
      $(".btn-circle-2").addClass("disabled");
  
    }
  
    //id de Documento 
    if( $("#idDocumento_Gama").val().length == 0 || $("#idDocumento_Gama").val().length < 6 ){
      $("#GENERAR").prop("disabled", true); 
      //Deshabilitar firmantes 
      $(".btn-circle-2").addClass("disabled");
    }
  
    //Tipo de Firmas 
    if($(".TipoFirmas_GMAgregar").val().length == 0 ){
       $(".TipoFirmas_GMAgregar option:selected").text("(Seleccione)");  
       //Deshabilitar firmantes 
       $(".btn-circle-2").addClass("disabled");
    }
  
    //Documento
    if( $("#Documento").val().length == 0 ){
      $("#Documento").val("(Seleccione)");
      $("#GENERAR").prop("disabled", true);
      //Deshabilitar firmantes 
      $(".btn-circle-2").addClass("disabled");
    }
  
    //Tipo de Firmas 
    if($(".TipoFirmas_GMAgregar").val().length == 0 ){
       $(".TipoFirmas_GMAgregar option:selected").text("(Seleccione)"); 
    }
    else{
      if( $(".TipoFirmas_GMAgregar").val() == 1 ){ //Si es Manual
         $(".btn-circle-2").addClass("disabled");
         $("#Notaria").val("");
         $("#Notaria_div").hide();
      }
      else{
        //Si tiene empresa
        if ( $("#input_emp").val() == 1 ){
          $("#boton_emp").removeClass("disabled");
        }
        else{
          $("#boton_emp").addClass("disabled");
        }
  
        //Si tiene Cliente 
        if ( $("#input_cli").val() == 1 ){
          $("#boton_cli").removeClass("disabled");
        }
        else{
          $("#boton_cli").addClass("disabled");
        }
  
        //Si tiene Notario
        if ( $("#input_not").val() == 1 ){
           $("#Notaria_div").show();
        
           if( $("#Notaria").val() != "(Seleccione)" ){
            $("#boton_not").removeClass("disabled");
           }else{
             $("#Notaria").val("(Seleccione)");
           }
        }
        else{
          $("#Notaria_div").hide();
          $("#boton_not").addClass("disabled");
        }
        
        //Si tiene aval 
        if( $("#input_aval").val() == 1 ){
          $("#avales").show();
        }else{
          $("#avales").hide();
        }
  
      }
    }
    
    //Estilo del mesae de advertencia 
    if ( $("#mensajeAd").html().length > 0 ){
        $("#mensajeAd").addClass("callout callout-warning");
    }
    else{
      $("#mensajeAd").removeClass("callout callout-warning");
    }
    
    //Firmantes Empresa
    var row_emp = $("#tabla_emp tr").length;
    var j = 0;
    var respuesta_2 = "";
  
    //Firmantes Clientes
    var row_cli = $("#tabla_cli tr").length;
    var i = 0;
    var respuesta = "";
  
    if( $(".TipoFirmas_GMAgregar").val() == 2 ){ //Si es firma electronica
  
        for( j = 0; j < row_emp ; j++ ){
          //Si esta pendiente por aprobacion
          respuesta_2 = validaRut($("#emp_" + i).val());
  
          if( respuesta_2 == false ){
            $("#fila_emp_" + i).css("background-color","#f4f4f4");
            $("#emp_" + i).prop("disabled", true);
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
          }
        }
  
        for( i = 0; i < row_cli ; i++ ){
          //Si esta pendiente por aprobacion
          respuesta = validaRut($("#cli_" + i).val());
  
          if( respuesta == false ){
            $("#fila_cli_" + i).css("background-color","#f4f4f4");
            $("#cli_" + i).prop("disabled", true);
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
          }
        }
    }
    else{
      for( j = 0; j < row_emp ; j++ ){
      
        $("#fila_emp_" + i).css("background-color","#fff");
        $("#emp_" + i).prop("disabled", false);
      }
  
      for( i = 0; i < row_cli ; i++ ){
  
        $("#fila_cli_" + i).css("background-color","#fff");
        $("#cli_" + i).prop("disabled", false);
      }
    } 
  };
  
  
  /*******************/
  /**FLUJO DE FIRMAS**/
  /*******************/
  $(".Flujo_GMAgregar").change(function(){
  
      $("#GENERAR").prop("disabled",true);
  
      //Cerrar los deplegables 
      $("#step-1").attr("aria-expanded","false");
      $("#step-1").removeClass("in"); 
      $("#step-2").attr("aria-expanded","false");
      $("#step-2").removeClass("in");  
      $("#step-3").attr("aria-expanded","false");
      $("#step-3").removeClass("in");
  
  
      //Enviar los datos para verificar el idProyecto
      var url = "Generar_Manuales_ajax.php";
      var parametros = "idWF=" + $(".Flujo_GMAgregar").val();
  
      //Destildar los firmantes
      $(".f_emp_GMAgregar").prop("checked", false);
      $(".f_cli_GMAgregar").prop("checked", false);
      $(".f_not_GMAgregar").prop("checked", false);
  
      // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
      if( window.XMLHttpRequest )
        ajax = new XMLHttpRequest(); // No Internet Explorer
      else
        ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
     
      // Almacenamos en el control al funcion que se invocara cuando la peticion
      // cambie de estado 
      ajax.onreadystatechange = funcionCallback_GMAgregar;
  
      // Enviamos la peticion
      ajax.open( "POST", url, true );
      ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      ajax.send(parametros);
  });
  
  /*****************/
  /***** AJAX ******/
  /*****************/
  function funcionCallback_GMAgregar(){
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida.innerHTML = "<b>"+ajax.responseText+"</b>"; 
        salida = ajax.responseText;
     
        if( salida.length > 0 ){
          var respuesta = salida.split("|");
          cliente = respuesta[0]; $("#input_cli").val(cliente);
          empresa = respuesta[1]; $("#input_emp").val(empresa);
          notario_GMAgregar = respuesta[2]; $("#input_not").val(notario_GMAgregar);
          aval    = respuesta[3]; $("#input_aval").val(aval);
  
          //Cliente
          if( $("#input_cli").val() == 1 ){
            $("#boton_cli").removeClass("disabled");
          }else{
            $("#boton_cli").addClass("disabled");
          }
  
          //Empresa
          if( $("#input_emp").val() == 1 ){
            $("#boton_emp").removeClass("disabled");
          }else{
            $("#boton_emp").addClass("disabled");
          }
  
          //Si tiene Notario
          if ( $("#input_not").val() == 1 ){
             $("#Notaria_div").show();
             $("#Notaria").val("(Seleccione)");
  
             if( $("#Notaria").val() != "(Seleccione)" ){
              $("#boton_not").removeClass("disabled");
             }
          }
          else{
            $("#Notaria_div").hide();
            $("#boton_not").addClass("disabled");
          }
  
          //Si tiene aval 
          if( $("#input_aval").val() == 1 ){
            $("#avales").show();
          }else{
            $("#avales").hide();
          }
        }
      }
    } else{
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
      }
  }
  
  /**********************/
  /**MODELO DE CONTRATO**/
  /**********************/
  $(".modelo_contrato_GMAgregar").change(function(){
    //Prefijo del idDocumento
      var modelo = $(".modelo_contrato_GMAgregar").val();
  
      switch (modelo) { 
        case '1': //Contrato Marco    
          $("#idDocumento_Gama").val("CtoA_");
          break;
        case '2': //Anexo     
          $("#idDocumento_Gama").val("PRY");
          break;
        case '3': //Renting     
          $("#idDocumento_Gama").val("CtoR_");
          break;    
        case '4': //Condiciones generales 
          $("#idDocumento_Gama").val("CGR_");
          break;
        case '5': //Financiero
          $("#idDocumento_Gama").val("OC_");
          break;
      }
  });
  
  /******************/
  /**TIPO DE FIRMAS**/
  /******************/
  $(".TipoFirmas_GMAgregar").change(function(){
  
    //Firmantes Empresa
    var row_emp = $("#tabla_emp tr").length;
    var j = 0;
    var respuesta_2 = "";
  
    //Firmantes Clientes
    var row_cli = $("#tabla_cli tr").length;
    var i = 0;
    var respuesta = "";
  
    //Si es de firma manual
    if( $(".TipoFirmas_GMAgregar").val() == 1 ){
       $(".btn-circle-2").addClass("disabled");
  
       for( j = 0; j < row_emp ; j++ ){
      
        $("#fila_emp_" + i).css("background-color","#fff");
        $("#emp_" + i).prop("disabled", false);
      }
  
      for( i = 0; i < row_cli ; i++ ){
  
        $("#fila_cli_" + i).css("background-color","#fff");
        $("#cli_" + i).prop("disabled", false);
      }
    }
  
    //Si es de firma electronica
    if( $(".TipoFirmas_GMAgregar").val() == 2 ){ 
  
        for( j = 0; j < row_emp ; j++ ){
          //Si esta pendiente por aprobacion
          respuesta_2 = validaRut($("#emp_" + i).val());
  
          if( respuesta_2 == false ){
            $("#fila_emp_" + i).css("background-color","#f4f4f4");
            $("#emp_" + i).prop("disabled", true);
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
          }
        }
  
        for( i = 0; i < row_cli ; i++ ){
          //Si esta pendiente por aprobacion
          respuesta = validaRut($("#cli_" + i).val());
  
          if( respuesta == false ){
            $("#fila_cli_" + i).css("background-color","#f4f4f4");
            $("#cli_" + i).prop("disabled", true);
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
          }
        }
  
        //Cliente
        if( $("#input_cli").val() == 1 ){
          $("#boton_cli").removeClass("disabled");
        }else{
          $("#boton_cli").addClass("disabled");
        }
  
        //Empresa
        if( $("#input_emp").val() == 1 ){
          $("#boton_emp").removeClass("disabled");
        }else{
          $("#boton_emp").addClass("disabled");
        }
  
        //Si tiene Notario
        if ( $("#input_not").val() == 1 ){
           $("#Notaria_div").show();
           $("#Notaria").val("(Seleccione)");
  
           if( $("#Notaria").val() != "(Seleccione)" ){
            $("#boton_not").removeClass("disabled");
           }
        }
        else{
          $("#Notaria_div").hide();
          $("#boton_not").addClass("disabled");
        }
  
        //Si tiene aval 
        if( $("#input_aval").val() == 1 ){
          $("#avales").show();
        }else{
          $("#avales").hide();
        }
    }
  });
  
  /*****************/
  /*****ARCHIVO*****/
  /*****************/
  
  //Asignar nombre del archivo seleccionado al campo
  $(".archivo_GMAgregar").change(function(){
  
      $("#Documento").val($(".archivo_GMAgregar").val());    
  
       if ( $("#input_not").val() == 1 ){
          //Habilitar firmantes 
  
         if( ($("#RutEmp").val().length > 2) && ($("#RutCli").val().length > 1) && ($(".Flujo_GMAgregar").val().length > 0) && ($(".TipoFirmas_GMAgregar").val() > 0) && ( $("#Notaria").val().length > 0) && ($("#Documento").val().length > 0 ) && ($(".modelo_contrato_GMAgregar").val() > 0)){
  
            if( $(".TipoFirmas_GMAgregar").val() == 2){
              //Cliente
              if( $("#input_cli").val() == 1 ){
                $("#boton_cli").removeClass("disabled");
              }else{
                $("#boton_cli").addClass("disabled");
              }
  
              //Empresa
              if( $("#input_emp").val() == 1 ){
                $("#boton_emp").removeClass("disabled");
              }else{
                $("#boton_emp").addClass("disabled");
              }
  
              //Si tiene aval 
              if( $("#input_aval").val() == 1 ){
                $("#avales").show();
              }else{
                $("#avales").hide();
              }
            }
            else{
              $(".btn-circle-2").addClass("disabled");
              $("#GENERAR").prop("disabled", false);
            }
  
         }
         else{
           $(".btn-circle-2").addClass("disabled");
         }
      }
      else{
          if( ($("#RutEmp").val().length > 2) && ($("#RutCli").val().length > 1) && ($(".Flujo_GMAgregar").val().length > 0) && ($(".TipoFirmas_GMAgregar").val() > 0 ) && ($("#Documento").val().length > 0 ) && ( $(".modelo_contrato_GMAgregar").val() > 0 )){
  
            if( $(".TipoFirmas_GMAgregar").val() == 2){
              //Cliente
              if( $("#input_cli").val() == 1 ){
                $("#boton_cli").removeClass("disabled");
              }else{
                $("#boton_cli").addClass("disabled");
              }
  
              //Empresa
              if( $("#input_emp").val() == 1 ){
                $("#boton_emp").removeClass("disabled");
              }else{
                $("#boton_emp").addClass("disabled");
              }
  
              //Si tiene Notario
              if ( $("#input_not").val() == 1 ){
                 $("#Notaria_div").show();
                 $("#Notaria").val("(Seleccione)");
  
                 if( $("#Notaria").val() != "(Seleccione)" ){
                  $("#boton_not").removeClass("disabled");
                 }
              }
              else{
                $("#Notaria_div").hide();
                $("#boton_not").addClass("disabled");
              }
  
              //Si tiene aval 
              if( $("#input_aval").val() == 1 ){
                $("#avales").show();
              }else{
                $("#avales").hide();
              }
            }
            else{
              $(".btn-circle-2").addClass("disabled");
              $("#GENERAR").prop("disabled", false);
            }
          }
          else{
            $(".btn-circle-2").addClass("disabled");
          }
      }
  });
  
  /*****************/
  /****FIRMANTES****/
  /*****************/
  
  
  /**************************************
  ** Recibe el ente que va a verificar,**
  ** consulta si tiene como minimo un  **
  ** firmante por ente y activa el     **
  ** boton de generar.                 **
  **************************************/
  function verificarFirmante_GMAgregar(datos){
    
     switch(datos) {
      case 'input_emp': //Empresa
            //Si tiene Notario
            if ( $("#input_not").val() == 1 ){
              //Si tiene aval
              if( $("#input_aval").val() == 1 ){
                //Si tiene Cliente 
                if( $("#input_cli").val() == 1){
                  
                  if( num_e > 0 && num_c > 0 && num_n > 0 && num_a > 0){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }else{//Si no tiene Cliente 
                  if( num_e > 0 && num_n > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                }
              }else{//Si no tiene aval 
                if( num_e > 0 && num_n > 0){
                  $("#GENERAR").attr('disabled', false);
                }
                else{
                  $("#GENERAR").prop('disabled', true); 
                }
              }
            }else{//Si no tiene Notario
             //Si tiene aval
              if( $("#aval").val() == 1 ){
                //Si tiene Cliente 
                if( $("#input_cli").val() == 1){
                  
                  if( num_e > 0 && num_c > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }else{//Si no tiene Cliente 
                  if( num_e > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }
               }else{//Si no tiene aval 
                   //Si tiene Cliente
                   if( $("#input_cli").val() == 1){
                      //Verificar los demas
                      if( num_e > 0 && num_c > 0 ){
                        $("#GENERAR").attr('disabled', false);
                      }
                      else{
                        $("#GENERAR").prop('disabled', true); 
                      }
                    }
                    else{//Si no tiene Cliente 
                      if( num_e > 0){
                        $("#GENERAR").attr('disabled', false);
                      }
                      else{
                        $("#GENERAR").prop('disabled', true); 
                      }
                    }
               }
             }
             break;
      case 'input_cli'://Cliente 
            //Si tiene Notario
            if ( $("#input_not").val() == 1 ){
              //Si tiene aval
              if( $("#input_aval").val() == 1 ){
                //Si tiene Empresa 
                if( $("#input_emp").val() == 1){
                  
                  if( num_e > 0 && num_c > 0 && num_n > 0 && num_a > 0){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }else{//Si no tiene Empresa 
                  if( num_c > 0 && num_n > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                }
              }else{//Si no tiene aval 
                if( num_c > 0 && num_n > 0){
                  $("#GENERAR").attr('disabled', false);
                }
                else{
                  $("#GENERAR").prop('disabled', true); 
                }
              }
            }else{//Si no tiene Notario
             //Si tiene aval
              if( $("#aval").val() == 1 ){
                //Si tiene Empresa 
                if( $("#input_emp").val() == 1){
                  
                  if( num_e > 0 && num_c > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }else{//Si no tiene Empresa 
                  if( num_c > 0 && num_a > 0 ){
                    $("#GENERAR").attr('disabled', false);
                  }
                  else{
                    $("#GENERAR").prop('disabled', true); 
                  }
                }
               }else{//Si no tiene aval 
                   //Si tiene Empresa
                   if( $("#input_emp").val() == 1){
                      //Verificar los demas
                      if( num_e > 0 && num_c > 0 ){
                        $("#GENERAR").attr('disabled', false);
                      }
                      else{
                        $("#GENERAR").prop('disabled', true); 
                      }
                    }
                    else{//Si no tiene Empresa 
                      if( num_c > 0){
                        $("#GENERAR").attr('disabled', false);
                      }
                      else{
                        $("#GENERAR").prop('disabled', true); 
                      }
                    }
               }
             }
             break;
      case 'input_not'://Notario
            //Si tiene aval 
            if ( $("#aval").val() == 1 ){
              //Si tiene Empresa
              if( $("#input_emp").val() == 1 ){
                //Si tiene Cliente 
                if( $("#input_cli").val() == 1 ){
                  if ( num_n > 0 && num_e > 0 && num_c > 0 && num_a > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
                }else{ // Si no tiene Cliente 
                  if ( num_n > 0 && num_e > 0 && num_a > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
                }
              }else{// Si no tiene empresa
                if ( num_n > 0 && num_a > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
              }
            }else{ //Si no tiene aval 
              //Si tiene empresa
              if( $("#input_emp").val() == 1 ){
                //Si tiene Cliente
                if( $("#input_cli").val() == 1 ){
  
                  if ( num_n > 0 && num_e > 0 && num_c > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
                }else{// Si no tiene Cliente 
                  if ( num_n > 0 && num_e > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
                }
              }else{ //Si no tiene empresa 
                if ( num_n > 0 ){
                     $("#GENERAR").prop('disabled', false); 
                  }
                  else{
                      $("#GENERAR").prop('disabled', true); 
                  }
              }
            }
            break;
    }
  }
  
  function verificarFirmanteAval_GMAgregar(){
  
    //Si al menos un esta en blanco
    if( num_a == 0 ){
      $("#GENERAR").prop('disabled', true);
    }
    else{
      //Si tiene aval 
      if ( $("#input_not").val() == 1 ){
        //Si tiene Empresa
        if( $("#input_emp").val() == 1 ){
          //Si tiene Cliente 
          if( $("#input_cli").val() == 1 ){
            if ( num_n > 0 && num_e > 0 && num_c > 0 && num_a > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
          }else{ // Si no tiene Cliente 
            if ( num_n > 0 && num_e > 0 && num_a > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
          }
        }else{// Si no tiene empresa
          if ( num_n > 0 && num_a > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
        }
      }else{ //Si no tiene Notaria
        //Si tiene empresa
        if( $("#input_emp").val() == 1 ){
          //Si tiene Cliente
          if( $("#input_cli").val() == 1 ){
  
            if ( num_a > 0 && num_e > 0 && num_c > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
          }else{// Si no tiene Cliente 
            if ( num_a > 0 && num_e > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
          }
        }else{ //Si no tiene empresa 
          if ( num_a > 0 ){
               $("#GENERAR").prop('disabled', false); 
            }
            else{
                $("#GENERAR").prop('disabled', true); 
            }
        }
      }
    }   
  }
  
  //Firmantes de Empresa
  $(".f_emp_GMAgregar").on( 'change', function() {
    if( $(this).is(':checked') == true ) {
  
      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
  
      num_e++;
      /*
      var resultado = confirm( "Desea incluir la personeria del firmante en el Documento?");
      var btn = "#btn_ver_" + i;
  
      if( resultado == true ){
        $("#"+ $(this).val() ).prop( "checked", true );
        $("#"+ $(this).val() ).attr( "value", 1 );
        $(btn).removeClass("btn-secondary").addClass("btn-success");
      }
      else{
        $("#" + $(this).val() ).prop( "checked", true );
        $("#"+ $(this).val() ).attr( "value", 0 );
      }*/
  
    }
    if( $(this).is(':checked') == false ){
      num_e--;
      $("#"+ $(this).val() ).attr( "value", 0 );
      $("#btn_ver_" + i).removeClass("btn-success").addClass("btn-secondary");
    }
    if(num_e == 0) {
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Seleccione Firmantes de Empresa");
      $("#GENERAR").prop('disabled', true); 
    }
  
    verificarFirmante_GMAgregar('input_emp');
  }); 
  
  //Firmantes de Clientes
  $(".f_cli_GMAgregar").on( 'change', function() {
    if( $(this).is(':checked') == true) {
  
      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
  
      num_c++;
      
      /*
      var resultado = confirm( "Desea incluir la personeria del firmante en el Documento?");
      var btn = "#btn_ver_cli_" + j;
  
      if( resultado == true ){
        $("#"+ $(this).val()+"_cli" ).prop( "checked", true );
        $("#"+ $(this).val()+"_cli" ).attr( "value", 1 );
        $(btn).removeClass("btn-secondary").addClass("btn-success");
      }
      else{
         $("#" + $(this).val()+"_cli" ).prop( "checked", true );
         $("#"+ $(this).val()+"_cli" ).attr( "value", 0 );
      }*/
    }
     if( $(this).is(':checked') == false ){
      num_c--;
      $("#"+ $(this).val()+"_cli" ).attr( "value", 0 );
      $("#btn_ver_cli_" + j).removeClass("btn-success").addClass("btn-secondary");
    }
    if(num_c == 0) {
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Seleccione Firmantes del Cliente");
      $("#GENERAR").prop('disabled', true); 
    }
  
    verificarFirmante_GMAgregar('input_cli');
    $(".rut_aval_GMAgregar").focus();
  });  
  
  //Firmantes de Notaria
  $(".f_not_GMAgregar").on('change', function() {
    if ( $("#input_not").val() == 1 ){
       if( $(this).is(':checked') ) {
          num_n++;
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
          
        }//Si seleccionaron algun firmante
         if( $(this).is(':checked') == false ){
          num_n--;
        }
        if(num_n == 0) {
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Seleccione Notario");
        $("#GENERAR").prop('disabled', true); 
        }
        verificarFirmante_GMAgregar('input_not');
    }
  });
  
  /*****************/
  /**DATOS DE AVAL**/
  /*****************/
  
    //Validar Rut de Aval
    $(".rut_aval_GMAgregar").change(function(){
  
      if ( $(".rut_aval_GMAgregar").val().length == 0 ){
        num_a--;
      }else{
        var respuesta = validaRut($(".rut_aval_GMAgregar").val());
  
        if( respuesta == false ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("El Rut del Aval no es un rut v&aacute;lido");
          $(".rut_aval_GMAgregar").focus();
          return false;
        }
        else{
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
        }
      }
  
     //Si todos los campos estan completos avanza
     if( $(".rut_aval_GMAgregar").val().length > 0 && $("#nombre_aval").val().length > 0 && $("#apellido_aval").val().length > 0 && $("#correo_aval").val().length > 0 && $("#personeria_aval").val().length > 0 ){
        num_a++;
      }  
      verificarFirmanteAval_GMAgregar();      
    });
  
     //Nombre
     $("#nombre_aval").change(function(){
  
       if ( $("#nombre_aval").val().length == 0 ){
          num_a--;
        }
  
        if( $(".rut_aval_GMAgregar").val().length > 0 && $("#nombre_aval").val().length > 0 && $("#apellido_aval").val().length > 0 && $("#correo_aval").val().length > 0 && $("#personeria_aval").val().length > 0 ){
            num_a++;
        }
        verificarFirmanteAval_GMAgregar();
     });
  
     //Apellido
     $("#apellido_aval").change(function(){
  
        if ( $("#apellido_aval").val().length == 0 ){
          num_a--;
        }
  
        if( $("#nombre_aval").val().length > 0 && $("#apellido_aval").val().length > 0 && $("#correo_aval").val().length > 0 && $("#personeria_aval").val().length > 0 ){
            num_a++;
          }
        verificarFirmanteAval_GMAgregar();
     });
  
     //Correo
     $("#correo_aval").change(function(){
  
       if ( $("#correo_aval").val().length == 0 ){
          num_a--;
        }
       if( $(".rut_aval_GMAgregar").val().length > 0 && $("#nombre_aval").val().length > 0 && $("#apellido_aval").val().length > 0 && $("#correo_aval").val().length > 0 && $("#personeria_aval").val().length > 0 ){
            num_a++;
          }
        verificarFirmanteAval_GMAgregar();
     });
  
    //Personerias
    $("#personeria_aval").change(function(){
  
        if ( $("#personeria_aval").val().length == 0 ){
          num_a--;
        }
  
        if( $(".rut_aval_GMAgregar").val().length > 0 && $("#nombre_aval").val().length > 0 && $("#apellido_aval").val().length > 0 && $("#correo_aval").val().length > 0 && $("#personeria_aval").val().length > 0 ){
            num_a++;
          }
        verificarFirmanteAval_GMAgregar();
     });

     /*templates\importacion_firma.html*/
     

 function InicioImportacionFirma(){
    $("#GENERAR").attr('disabled', true);
    $(".idTipoDoc_IF").attr("disabled",true);
    $(".idProceso_IF").attr("disabled",true);
    $(".idPlantilla_IF").attr("disabled",true);
 
    $("#Documento").attr("disabled",true);
    $(".btn-file").attr("disabled",true);
    $(".archivo_IF").attr("disabled",true);
	  $("#	Documento").attr("disabled",true);
    if ($('#progreso').attr('max') != '')
    {
		$(".RutEmpresa_IF").attr("disabled",true);
		estadoGeneracionMasiva();
    }
  };

  var consulta_IF;
  function estadoGeneracionMasiva()
  {
      $('#estado').show();
  	  consultaEstado_IF();
      consulta_IF = setInterval(function(){ consultaEstado_IF() }, 7000);
  }
 
 function consultaEstado_IF()
  {
    conexion_IF=crearXMLHttpRequest();
	  conexion_IF.open('POST', 'importacion_firma_split.php?accion=ESTADO',false);
    conexion_IF.send(null);
    try
    {
      respuesta_IF = JSON.parse(conexion_IF.responseText);
	 
    }
    catch (e)
    {
      respuesta_IF.actual = 0;
    }
  
    //console.log(respuesta_IF);
    respuesta_IF.actual = respuesta_IF.actual == null ? 0 : respuesta_IF.actual;
    $('#progreso').val(respuesta_IF.actual);
    $('#estadodetalle').html(respuesta_IF.actual + " filas procesadas de un total de " +  ($('#progreso').attr('max') - 1) + " filas.");
    //console.log(controlMemory_IF(respuesta_IF.actual));
    if (controlMemory_IF(respuesta_IF.actual))
    {
      if (respuesta_IF.actual == $('#progreso').attr('max') - 1)
      {
        clearInterval(consulta_IF);
        $('#estado').hide();
        alert('El proceso de generacion masiva de documentos ha finalizado.');
      }
    }
    else
    {
      //matarProceso_IF();
      clearInterval(consulta_IF);
      $('#estado').hide();
      alert('Ha ocurrio un error, revise detalle');
    }
  }

  function matarProceso_IF()
  {
	  conexion_IF=crearXMLHttpRequest();
    conexion_IF.open('POST', 'importacion_firma_split.php?accion=KILL',false);
    conexion_IF.send(null);   

  }
  
  var memory_IF = '';
  var memoryTop_IF = 20;
  var memoryCount_IF = 0;
  function controlMemory_IF(dato)
  {
    var respuesta_IF = true;
    if (memory_IF == dato)
    {
      memoryCount_IF++;
    }
    else
    {
      memoryCount_IF = 0;
    }
    if (memoryCount_IF >= memoryTop_IF)
    {
      respuesta_IF = false;
    }
    memory_IF = dato;
    //console.log(dato, memory_IF, memoryCount_IF, memoryTop_IF, respuesta_IF);
    return respuesta_IF;
  }

 $(".idProceso_IF").change(function(){

    if( $(".idProceso_IF").val() != 0 )
      $(".idPlantilla_IF").attr("disabled",false);
    else{
        $(".idPlantilla_IF").attr("disabled",true);
        //$("#idFirma").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_IF").attr("disabled",true);

        $(".idProceso_IF").val(0);
        $(".idPlantilla_IF").val(0);
        $("#idFirma").val(0);
		$(".orden").val('');
        $("#Documento").val('');
        $(".archivo_IF").val();
    }
 });

 /*
  $("#idFirma").change(function(){

    if( $("#idFirma").val() != 0 ){
      $("#Documento").attr("disabled",false);
      $(".btn-file").attr("disabled",false);
      $(".archivo_IF").attr("disabled",false);
    }else{
      $("#Documento").val('');
      $("#Documento").attr("disabled",true);
      $(".btn-file").attr("disabled",true);
      $(".archivo_IF").attr("disabled",true);
    }
  });
  */

  var cantidad_firmantes_IF;

  $(".idPlantilla_IF").change(function(){

      if( $(".idPlantilla_IF").val() != 0 ){
		
		$("#Documento").attr("disabled",false);
		$(".btn-file").attr("disabled",false);
		$(".archivo_IF").attr("disabled",false);
		$("#	Documento").attr("disabled",false);
		
        /*$("#idFirma").attr("disabled",false);
		$("#idFirma").val(0);*/
         	
		//Ocultar firmantes 
		$("#icono_representante").hide();
		$("#GENERAR").attr('disabled', true);
		$("#Documento").val('');
		$(".archivo_IF").val('');
          
        $("#Representates").val("");
        $("#Empleado").val("");
        $("#Cantidad_Firmantes").val("");
        $("#idWF").val(0);
		$(".orden").val('');

        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");

        $('input[type=checkbox]').prop('checked', false);
        $("#step-1").collapse('hide');

        var idPlantilla = $(".idPlantilla_IF").val();

        if( idPlantilla != 0 ){
          var url  = "Generar_Documentos_Masivos1_ajax.php";
          var parametros = "idPlantilla=" + idPlantilla;

          // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
          if( window.XMLHttpRequest )
            ajax_IF = new XMLHttpRequest(); // No Internet Explorer
          else
            ajax_IF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
         
          // Almacenamos en el control al funcion que se invocara cuando la peticion
          // cambie de estado 
          ajax_IF.onreadystatechange = funcionCallback_Plantilla;

          // Enviamos la peticion
          ajax_IF.open( "POST", url, true );
          ajax_IF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          ajax_IF.send(parametros);
        }
      }else{
      
     //   $("#idFirma").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_IF").attr("disabled",true);

        $(".idPlantilla_IF").val(0);
        $("#idFirma").val(0);
        $("#Documento").val('');
        $(".archivo_IF").val();
        $("#idWF").val(0);
		$(".orden").val('');
      }
     
  });

  function ver_IF()
  {
	  MostrarCargando();
    document.getElementById("resultado").submit();
  }
  
  function funcionCallback_Plantilla()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IF.readyState == 4 )
    {
      // Comprobamos si la respuesta_IF ha sido correcta (resultado HTTP 200)
      if( ajax_IF.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_IF.innerHTML = "<b>"+ajax_IF.responseText+"</b>"; 
        salida_IF = ajax_IF.responseText;

        if( salida_IF != '' ){ 
            var datos = JSON.parse(salida_IF);
            var cont = 0; 
            var cant_firm = 0;
            var cant_firm = datos.length;

            $.each(datos,function(key, registro) {


			   if ( cant_firm == 1 ){
					 if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9){ //Reresentante - Representante 2 - Notario 
						$("#Representantes").val("1");
						$("#Empleado").val("");
						cont++;
					 }
					 if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
						$("#Representantes").val("");
					 } 
				}  
				else 
				{ 	
					if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9){ //Reresentante - Representante 2 - Notario 
						cont++; 
						$("#Representantes").val(cont);
					 }
					 if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
					 }
				}
				$("#Cantidad_Firmantes").val(cont); 
				$("#idWF").val(registro.idWF); 
			});
        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }

  $(".archivo_IF").change(function(){
   
    $("#Documento").val($(".archivo_IF").val());

    if( $("#Documento").val() != '' ){
      mostrarFirmantes_IF();  

      if ( $("#Representantes").val() == '' ){
        $("#GENERAR").attr('disabled', false);
      }
    }

  });

	/*********************/
	/**FILTRAR PLANTILLA**/
	/********************/
  $(".idTipoDoc_IF").change(function(){

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html(""); 

    if( $(".idTipoDoc_IF").val() != 0 ){
    

      var empresa = $(".RutEmpresa_IF").val();
      var idTipoDoc = $(".idTipoDoc_IF").val();
      $(".plan").remove();

      if( idTipoDoc != 0 && empresa != 0 ){
        var url  = "Generar_Documentos_Masivos_ajax.php";
        var parametros = "RutEmpresa=" + empresa + "&idTipoDoc=" + idTipoDoc;
     
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_IF = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_IF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_IF.onreadystatechange = funcionCallback_IF2;

        // Enviamos la peticion
        ajax_IF.open( "POST", url, true );
        ajax_IF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_IF.send(parametros);
      }
    }else{
       
        $(".idProceso_IF").attr("disabled",true);
        $(".idPlantilla_IF").attr("disabled",true);
      //  $("#idFirma").attr("disabled",true);
        $("#Documento").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $(".archivo_IF").attr("disabled",true);

        $(".idTipoDoc_IF").val(0);
        $(".idProceso_IF").val(0);
        $(".idPlantilla_IF").val(0);
      //  $("#idFirma").val(0);
        $("#Documento").val('');
        $(".archivo_IF").val();
        $("#idWF").val(0);
		$(".orden").val('');
    }

  });

 
  function funcionCallback_IF2()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IF.readyState == 4 )
    {
      // Comprobamos si la respuesta_IF ha sido correcta (resultado HTTP 200)
      if( ajax_IF.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_IF.innerHTML = "<b>"+ajax_IF.responseText+"</b>"; 
        salida_IF = ajax_IF.responseText;

        if( salida_IF != '' ){
            var datos = JSON.parse(salida_IF);
           
            $.each(datos,function(key, registro) {
                $(".idPlantilla_IF").append('<option class="plan" ' + registro.Aprobado + ' value='+ registro.idPlantilla +'>'+ registro.Descripcion_Pl+'</option>');                                      
            }); 

            $('.idProceso_IF').attr("disabled",false);  

        }else{
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("La Empresa seleccionada no tiene Plantillas aprobadas o asociadas"); 
            
            $(".idProceso_IF").attr("disabled",true);
            $(".idPlantilla_IF").attr("disabled",true);
        //    $("#idFirma").attr("disabled",true);
            $("#Documento").attr("disabled",true);
            $(".btn-file").attr("disabled",true);
            $(".archivo_IF").attr("disabled",true);

            $(".idTipoDoc_IF").val(0);
            $(".idProceso_IF").val(0);
            $(".idPlantilla_IF").val(0);
        //    $("#idFirma").val(0);
            $("#Documento").val('');
            $(".archivo_IF").val();
            $("#idWF").val(0);
			$(".orden").val('');
        }
      }
    }
  }
 
  /*****************/
  /**IMPORTAR EXCEL**/
  /******************/
  var importax_IF;
  function uploadFile_IF(file)
  {	
		$("#GENERAR").attr('disabled', true);
		MostrarCargando();
		importax_IF = setInterval(function(){ importar_IF(file,1) }, 1000);
   }
	
  
  function importar_IF(file,IdArchivo)
	{
	    clearInterval(importax_IF);
		var xhr =  createXMLHttp();

		xhr.upload.addEventListener('loadstart',function(e){
		document.getElementById('mensaje').innerHTML = 
		'Cargando archivo...';
		}, false);

		xhr.upload.addEventListener('load',function(e){
		document.getElementById('mensaje').innerHTML = '';
		}, false);

		xhr.upload.addEventListener('error',function(e){
		alert('Ha habido un error :/');
		}, false);

		var datos = $('#formulario').serialize();
		//console.log(datos);
		var rutempresa = $(".RutEmpresa_IF").val();
		var url  = 'importacion_firma_split.php?accion=VALIDAR&RutEmpresa=' + rutempresa + '&datos=' + datos;

		xhr.open('POST',url,false);//se le agrego false para que sea sincrono, para que espere antes de comenzar a cargar el otro archivo.
	
		xhr.setRequestHeader("Cache-Control", "no-cache");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader("X-File-Name", file.name);

		xhr.addEventListener('readystatechange', function(e) 
		{
			
			if( this.readyState == 4 ) {

				respuesta_IF = xhr.responseText;
				
				var res = respuesta_IF.split("|");
				//alert ("resp " + res[0] + " " + res[1]);
				
				if (res[0] == "OK")//cuando sepa que fue bien.
				{ 
					$('#progreso').attr('max', res[1]);
					procesar_IF();
					estadoGeneracionMasiva();
				
				}
				else
				{
					$('#estado').hide();
					$("#GENERAR").attr('disabled', false);
					var elementoError   = document.getElementById("mensajeError");
					elementoError.innerHTML = respuesta_IF;
					elementoError.className += "callout callout-danger";
				}
			}
			
			OcultarCargando();

		});
		xhr.send(file);
	}
	
  function procesar_IF()
  {
    conexion_IF=crearXMLHttpRequest();
    var datos = $('#formulario').serialize();
    //console.log(datos);
    conexion_IF.open('POST', 'importacion_firma_split.php?' + datos);
    conexion_IF.send(null);
    respuesta_IF =  conexion_IF.responseText;	
   
  }
	
  function subirArchivo_IF()
  {

		//revisa si hay algun cambio en el archivo
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
	
    if (validar_IF() == true)
	{
		upload_input = document.querySelectorAll('.archivo_IF')[0];
		uploadFile_IF( upload_input.files[0] );
	}
  }
	
  function mostrarFirmantes_IF(){

      var Representantes = $("#Representantes").val();

      if( Representantes != '' ){
        $("#icono_representante").show();
        $("#label").show();
      }
  }

  /*******************************/
  /**BUSCAR FIRMANTES DE EMPRESA**/
  /*******************************/

   $(".RutEmpresa_IF").change(function(){
            
      var RutEmpresa = $(".RutEmpresa_IF").val();

      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
      $(".idTipoDoc_IF").attr("disabled",true);
      $(".idProceso_IF").attr("disabled",true);
      $(".idPlantilla_IF").attr("disabled",true);
      $("#idFirma").attr("disabled",true);
      $("#Documento").attr("disabled",true);
      $(".btn-file").attr("disabled",true);
      $(".archivo_IF").attr("disabled",true);

      $(".idTipoDoc_IF").val(0);
      $(".idProceso_IF").val(0);
      $(".idPlantilla_IF").val(0);
      $(".plan").remove();
      $("#idFirma").val(0);
      $("#Documento").val('');
      $(".archivo_IF").val();
      $("#idWF").val(0);
	  $(".orden").val('');

      //Limpiar tabla 
      $(".fila").remove();

      //Ocultar firmantes 
      $("#icono_representante").hide();
      $("#label").hide();
      $("#step-1").collapse('hide');

      if( RutEmpresa != 0 ){
        
        $(".idTipoDoc_IF").attr("disabled",false);
        $(".idTipoDoc_IF").val(0);

        var url  = "Generar_Documentos_Masivos2_ajax.php";
        var parametros = "RutEmpresa=" + RutEmpresa;
    
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_IF = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_IF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_IF.onreadystatechange = funcionCallback_Empresa_IF;

        // Enviamos la peticion
        ajax_IF.open( "POST", url, true );
        ajax_IF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_IF.send(parametros);
      }
  });

  function funcionCallback_Empresa_IF()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IF.readyState == 4 )
    {
      // Comprobamos si la respuesta_IF ha sido correcta (resultado HTTP 200)
      if( ajax_IF.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_IF.innerHTML = "<b>"+ajax_IF.responseText+"</b>"; 
        salida_IF = ajax_IF.responseText;

        if( salida_IF != '' ){ 
            var datos = JSON.parse(salida_IF);

            $.each(datos,function(key, registro) {
              //  $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_<php:filamenos />'  onclick='seleccion_IF(<php:filamenos />);'/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto  + "</td><td>" +  registro.descripcion + "</td></tr>");                                 
			  x = "onclick=\"seleccion_IF(\'" + registro.personaid + "\');\"";
              $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_"+ registro.personaid +"' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");                                 
           
		   });          
        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }

  function seleccion_IF(id){

      var num = $('input[type=checkbox]:checked').length;
      var cantidad_firmantes_IF = $("#Cantidad_Firmantes").val(); 
     var representantes = $("#Representantes").val();
		
      if ( cantidad_firmantes_IF == '' ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar la Plantilla" );
          $(".idPlantilla_IF").focus();
          $("#GENERAR").attr('disabled', true);
          return false;
      }
	  
	  if ( num < cantidad_firmantes_IF ){
		 $("#GENERAR").attr('disabled',true);
	  }
	  
      if ( num > cantidad_firmantes_IF ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes_IF + " Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
          $("#GENERAR").attr('disabled', true);
      }
	  
	  if( num == cantidad_firmantes_IF ){
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
		  $("#GENERAR").attr('disabled', false);
      }
	
	  if( $("#orden_" + id ).val() == '' ){ //Seleccionado
		if ( cantidad_firmantes_IF > 1 ){
			$("#orden_" + id).val(num);
		}
	  }else{//Deseleccionado
		if ( cantidad_firmantes_IF > 1 ){
			$("#orden_" + id).val('');
		}
	  }
  }

  function validar_IF(){

    if ( $(".RutEmpresa_IF").val() == 0 ){
        $(".RutEmpresa_IF").focus();
        return false;
    }
    if ( $(".idTipoDoc_IF").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de documento");
        $(".idTipoDoc_IF").focus();
        return false;
    }
  
    if( $(".idProceso_IF").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione un Proceso");
        $(".idProceso_IF").focus();
        return false;
    }

    if( $(".idPlantilla_IF").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione la plantilla");
        $(".idPlantilla_IF").focus();
        return false;
    }
	/*
    if( $("#idFirma").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de firma del documento");
        $("#idFirma").focus();
        return false;
    }
	*/
    if( $("#Documento").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el archivo de carga que necesita");
        $("#Documento").focus();
        return false;
    }
    return true;
  }

  /*templates\importacionpdf_FormularioAgregar.html*/

  

  function InicioIPDFA(){ 

    $("#GENERAR").attr('disabled', true);
    $(".idTipoDoc_IPDFA").attr("disabled",true);
    $(".idProceso_IPDFA").attr("disabled",true);
    $(".idPlantilla_IPDFA").attr("disabled",true);
 //   $("#idFirma").attr("disabled",true);
	
	//para deshabilitar la subida del pdf
	$("#Documento").attr("disabled",true);
	$(".btn-file").attr("disabled",true);
	$(".pdf64_IPDFA").attr("disabled",true);
	//fin
  };

 

	$(".idProceso_IPDFA").change(function(){

		if( $(".idProceso_IPDFA").val() != 0 )
		$(".idPlantilla_IPDFA").attr("disabled",false);
		else{
			$(".idPlantilla_IPDFA").attr("disabled",true);
			//$("#idFirma").attr("disabled",true);
			
			//deshabilita subida pdf
			$(".pdf64_IPDFA").attr("disabled",true);
			$(".btn-file").attr("disabled",true);
			$("#Documento").val('');
			//fin
			
			//Ocultar firmantes 
			$("#icono_representante").hide();
		
			$(".idProceso_IPDFA").val(0);
			$(".idPlantilla_IPDFA").val(0);
		// $("#idFirma").val(0);
			$("#idWF").val(0);
			$(".orden").val('');
		}
	});



	$(".idPlantilla_IPDFA").change(function(){
  
 	    $("#Documento").val('');
	
	  //Ocultar firmantes 
	   $("#icono_representante").hide();
	   
	   $(".pdf64_IPDFA").val('');

	   $("#GENERAR").attr('disabled', true);
		 
		if( $(".idPlantilla_IPDFA").val() != 0 ){
			//$("#idFirma").attr("disabled",false);
			//habilita la subida del pdf
			$(".btn-file").attr("disabled",false);
			$(".pdf64_IPDFA").attr("disabled",false);
			//fin

			//Ocultar firmantes 
			$("#icono_representante").hide();
			$("#GENERAR").attr('disabled', true);
			$("#Representates").val("");
			$("#Empleado").val("");
			$("#Cantidad_Firmantes").val("");
			$(".orden").val('');

			$("#mensajeError").removeClass("callout callout-warning");
			$("#mensajeError").html("");

			$('input[type=checkbox]').prop('checked', false);
			$("#step-1").collapse('hide');

        	var idPlantilla = $(".idPlantilla_IPDFA").val();

			if( idPlantilla != 0 ){
          		var url  = "Generar_Documentos_Masivos1_ajax.php";
                var parametros = "idPlantilla=" + idPlantilla;

			// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
			if( window.XMLHttpRequest )
				ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
			else
				ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
		
			// Almacenamos en el control al funcion que se invocara cuando la peticion
			// cambie de estado 
			ajax_IPDFA.onreadystatechange = funcionCallback_Plantilla_IPDFA;

			// Enviamos la peticion
			ajax_IPDFA.open( "POST", url, true );
            ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax_IPDFA.send(parametros);
		}
	}else{
     	//$("#idFirma").attr("disabled",true);
	 
		//deshabilita subida pdf
		$(".pdf64_IPDFA").attr("disabled",true);
		$(".btn-file").attr("disabled",true);
	 	//fin
		
        $(".idPlantilla_IPDFA").val(0);
      	//$("#idFirma").val(0);
        $("#Documento").val('');
        $(".pdf64_IPDFA").val();
        $("#idWF").val(0);
		$(".orden").val('');
      }
     
  });

	function funcionCallback_Plantilla_IPDFA()
	{
		// Comprobamos si la peticion se ha completado (estado 4)
		if( ajax_IPDFA.readyState == 4 )
		{
			// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
			if( ajax_IPDFA.status == 200 )
			{
				// Escribimos el resultado en la pagina HTML mediante DHTML
				//document.all.salida_IPDFA.innerHTML = "<b>"+ajax_IPDFA.responseText+"</b>"; 
				salida_IPDFA = ajax_IPDFA.responseText;
				if( salida_IPDFA != '' ){ 
				var datos = JSON.parse(salida_IPDFA);
				var cont = 0; 
				var cant_firm = 0;
				cant_firm = datos.length;
		
				$.each(datos,function(key, registro) {
				if ( cant_firm == 1 ){
					if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11){ //Reresentante - Representante 2 - Notario - Representante 3
						$("#Representantes").val("1");
						$("#Empleado").val("");
						cont++;
					}
					if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
						$("#Representantes").val("");
					} 
				}  
				else 
				{ 	
					if( registro.idEstado == 2 || registro.idEstado == 10 || registro.idEstado == 9 || registro.idEstado == 11 ){ //Reresentante - Representante 2 - Notario - Representante 3
						cont++; 
						$("#Representantes").val(cont);
					}
					if( registro.idEstado == 3 ){
						$("#Empleado").val("1");
					}
				}

				$("#Cantidad_Firmantes").val(cont); 
				$("#idWF").val(registro.idWF); 
			});
		}else{
			$("#mensajeError").removeClass("callout callout-warning");
			$("#mensajeError").html("");
		}
		}
		}
  	}


	/*********************/
	/**FILTRAR PLANTILLA**/
	/********************/
  $(".idTipoDoc_IPDFA").change(function(){

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html(""); 

    if( $(".idTipoDoc_IPDFA").val() != 0 ){
    

      var empresa = $(".RutEmpresa_IPDFA").val();
      var idTipoDoc = $(".idTipoDoc_IPDFA").val();
      $(".plan").remove();

      if( idTipoDoc != 0 && empresa != 0 ){
        var url  = "Generar_Documentos_Masivos_ajax.php";
        var parametros = "RutEmpresa=" + empresa + "&idTipoDoc=" + idTipoDoc;
     
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_IPDFA.onreadystatechange = funcionCallback;

        // Enviamos la peticion
        ajax_IPDFA.open( "POST", url, true );
        ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_IPDFA.send(parametros);
      }
    }else{
       
        $(".idProceso_IPDFA").attr("disabled",true);
        $(".idPlantilla_IPDFA").attr("disabled",true);
      //  $("#idFirma").attr("disabled",true);
	  
		//para deshabilitar la subida del pdf
		$(".pdf64_IPDFA").attr("disabled",true);
		$(".btn-file").attr("disabled",true);
		//fin
		
		//Ocultar firmantes 
		$("#icono_representante").hide();


        $(".idTipoDoc_IPDFA").val(0);
        $(".idProceso_IPDFA").val(0);
        $(".idPlantilla_IPDFA").val(0);
     //   $("#idFirma").val(0);
        $("#Documento").val('');
        $(".archivo_IPDFA").val();
        $("#idWF").val(0);
		$(".orden").val('');
    }

  });

  function funcionCallback()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IPDFA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_IPDFA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_IPDFA.innerHTML = "<b>"+ajax_IPDFA.responseText+"</b>"; 
        salida_IPDFA = ajax_IPDFA.responseText;

        if( salida_IPDFA != '' ){
            var datos = JSON.parse(salida_IPDFA);
           
            $.each(datos,function(key, registro) {
                $(".idPlantilla_IPDFA").append('<option class="plan" ' + registro.Aprobado + ' value='+ registro.idPlantilla +'>'+ registro.Descripcion_Pl+'</option>');                                      
            }); 

            $('.idProceso_IPDFA').attr("disabled",false);  

        }else{
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("La Empresa seleccionada no tiene Plantillas aprobadas o asociadas"); 
            
            $(".idProceso_IPDFA").attr("disabled",true);
            $(".idPlantilla_IPDFA").attr("disabled",true);
          //  $("#idFirma").attr("disabled",true);
            $(".idTipoDoc_IPDFA").val(0);
            $(".idProceso_IPDFA").val(0);
            $(".idPlantilla_IPDFA").val(0);
          //  $("#idFirma").val(0);
            $("#idWF").val(0);
			$(".orden").val('');
        }
      }
    }
  }
	$(".archivo_IPDFA").change(function(){

		$("#Documento").val($(".archivo_IPDFA").val());
	
		if( $("#Documento").val() != '' ){
			mostrarFirmantes_IPDFA();  

			if ( $("#Representantes").val() == '' ){
				$("#GENERAR").attr('disabled', false);
			}
		}

	});

	
  function mostrarFirmantes_IPDFA(){

      var Representantes = $("#Representantes").val();

      if( Representantes != '' ){
        $("#icono_representante").show();
        $("#label").show();
      }
	  
		if( $("#Clientes").val() != '' ){
			$("#icono_clientes").show();
		}

		//$("#Representantes").val('');
		//$("#Clientes").val('');
	}

	/*******************************/
	/**BUSCAR FIRMANTES DE EMPRESA**/
	/*******************************/

   $(".RutEmpresa_IPDFA").change(function(){
            
      var RutEmpresa = $(".RutEmpresa_IPDFA").val();

      $("#mensajeError").removeClass("callout callout-warning");
      $("#mensajeError").html("");
      $(".idTipoDoc_IPDFA").attr("disabled",true);
      $(".idProceso_IPDFA").attr("disabled",true);
      $(".idPlantilla_IPDFA").attr("disabled",true);
    //  $("#idFirma").attr("disabled",true);
	
	  //para deshabilitar la subida del pdf
  	  $(".pdf64_IPDFA").attr("disabled",true);
	  $(".btn-file").attr("disabled",true);
	 //fin
     
      $(".idTipoDoc_IPDFA").val(0);
      $(".idProceso_IPDFA").val(0);
      $(".idPlantilla_IPDFA").val(0);
   //   $("#idFirma").val(0);
      $("#Documento").val('');
      $(".archivo_IPDFA").val();
      $("#idWF").val(0);
      $(".plan").remove();
	  $(".orden").val('');
	  $("#nombrecentrocosto").val('');
	  $("#idCentroCosto").val('');

      //Limpiar tabla 
      $(".fila").remove();

      //Ocultar firmantes 
      $("#icono_representante").hide();
      $("#label").hide();
      $("#step-1").collapse('hide');

      if( RutEmpresa != 0 ){
        
        $(".idTipoDoc_IPDFA").attr("disabled",false);
        $(".idTipoDoc_IPDFA").val(0);

        var url  = "Generar_Documentos_Masivos2_ajax.php";
        var parametros = "RutEmpresa=" + RutEmpresa;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
          ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
        else
          ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
       
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_IPDFA.onreadystatechange = funcionCallback_Empresa_IPDFA;

        // Enviamos la peticion
        ajax_IPDFA.open( "POST", url, true );
        ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_IPDFA.send(parametros);
      }
  });

  function funcionCallback_Empresa_IPDFA()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IPDFA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_IPDFA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        //document.all.salida_IPDFA.innerHTML = "<b>"+ajax_IPDFA.responseText+"</b>"; 
        salida_IPDFA = ajax_IPDFA.responseText;

		if( salida_IPDFA != '' ){ 
			var datos = JSON.parse(salida_IPDFA);
			var x = "";
			$.each(datos,function(key, registro) {
				x = "onclick=\"seleccion_IPDFA(\'" + registro.personaid + "\');\"";
				$("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_"+ registro.personaid +"' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");                                 

		   });          
        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }
  
	/**************************************/
	/**SELECCION DE FIRMANTES POR EMPRESA**/
	/**************************************/
	
	function seleccion_IPDFA(id){

      var num = $('input[type=checkbox]:checked').length;
      var cantidad_firmantes = $("#Cantidad_Firmantes").val(); 

     /* if ( cantidad_firmantes == '' ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar la Plantilla" );
          $(".idPlantilla_IPDFA").focus();
          $("#GENERAR").attr('disabled', true);
          return false;

      }
      if ( num > cantidad_firmantes ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes + " Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
          $("#GENERAR").attr('disabled', true);
      }else{
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
          $("#GENERAR").attr('disabled', false);
      }*/
	  var representantes = $("#Representantes").val();
		
      if ( cantidad_firmantes == '' ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar la Plantilla" );
          $(".idPlantilla_IPDFA").focus();
          $("#GENERAR").attr('disabled', true);
          return false;

      }
	  if ( num < cantidad_firmantes ){
		 $("#GENERAR").attr('disabled',true);
	  }
	  
      if ( num > cantidad_firmantes ){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes + " Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
          $("#GENERAR").attr('disabled', true);

      }
	  
	  if( num == cantidad_firmantes ){
          $("#mensajeError").removeClass("callout callout-warning");
          $("#mensajeError").html("");
		  $("#GENERAR").attr('disabled', false);
      }
	  
	  if( $("#orden_" + id ).val() == '' ){ //Seleccionado
		if ( cantidad_firmantes > 1 ){
			$("#orden_" + id).val(num);
		}
	  }else{//Deseleccionado
		if ( cantidad_firmantes > 1 ){
			
			if( confirm(' Desea reiniciar el orden de los firmantes ?')){
				$(".orden").val('');
				$(".f_emp").prop('checked',false);
			}else{	
				$("#orden_" + id).val('');
				
				//Colocar orden 
				var rutfirmante = $("#emp_" + id).val();
				
				convertirArreglo_emp_IPDFA();
				nuevo_emp_IPDFA = convertirItem_IPDFA(arreglo_emp_IPDFA, orden_emp_IPDFA);
				var items = nuevo_emp_IPDFA;

				items.sort(function (a, b) {
				  if (a.orden > b.orden) {
					return 1;
				  }
				  if (a.orden < b.orden) {
					return -1;
				  }
				  // a must be equal to b
				  return 0;
				});
					
				reasignarOrden_IPDFA(items,'');
			

			}
		}
	  }
  }
	
	/****************************/
	/**OPERACIONES DE SELECCION**/
	/****************************/
	
	function reasignarOrden_IPDFA(items,emp){
		
		for ( i = 0; i < items.length ; i++ ){
			var rut = $("#" + items[i].id).val(); 
			
			if( emp != '' )
				$("#orden_" + emp + rut).val(i+1);
			else
				$("#orden_" + rut).val(i+1);
		}
	}

	function convertirArreglo_IPDFA(){
		
		arreglo_IPDFA = [];
		var rut = '';
		var orden_id = '';

		$(".f_cli").each(function (index) {  
			
			if( $(this).is(':checked') ){
				rut = this.id;
				orden_id = $("#orden_"+ $("#" + this.id).val()).val();
				arreglo_IPDFA.push(this.id);
				orden.push(orden_id);
			}
		});
	}

	function convertirArreglo_emp_IPDFA(){
		
		arreglo_emp_IPDFA = [];
		orden_emp_IPDFA = [];
		var rut = '';
		var orden_id = '';

		$(".f_emp").each(function (index) {  
			
			if( $(this).is(':checked') ){
				rut = this.id;
				orden_id = $("#orden_" + $("#" + this.id).val()).val();
				arreglo_emp_IPDFA.push(this.id);
				orden_emp_IPDFA.push(orden_id);
			}
		});
	}

	function convertirItem_IPDFA(arreglo1,arreglo2){

		var data = new Object();
		var array = new Array();
		
		$.each(arreglo1, function (ind, elem){ 

			var data = new Object();

			data.id = elem;
			data.orden = arreglo2[ind];
			array[ind] = data;
		}); 	

		return array;
	}
	/*
	$("#idFirma").change(function(){
	
		mostrarFirmantes_IPDFA();  

		if ( $("#Representantes").val() == '' ){
			$("#GENERAR").attr('disabled', false);
		}
	});
	*/


	/**********************/
	/**VALIDAR FORMULARIO**/
	/**********************/
	
	function validar_IPDFA(){

		respuesta = Checkfiles_IPDFA();
		if (respuesta == false)
		{
			return false;
		}
	
		if ($(".newusuarioid_IPDFA").val().length < 9 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo Rut no puede estar vac&iacute;o");
			$('.newusuarioid_IPDFA').focus();
			return false;
		}
		if( ! validaRut2(document.formulario.newusuarioid)){
			return false;
		}

		if ($("#nombre").val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo Nombre no puede estar vac&iacute;o");
			$('#nombre').focus();
			return false;
		}

		if ($("#nacionalidad").val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo Nacionalidad no puede estar vac&iacute;o");
			$('#nacionalidad').focus();
			return false;
		}

		if ($("#direccion").val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo Direcci&oacute;n no puede estar vac&iacute;o");
			$('#direccion').focus();
			return false;
		}

		if ($("#correo").val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo correo no puede estar vac&iacute;o");
			$('#correo').focus();
			return false;
		}

		if ($("#fechanacimiento").val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("El campo Fecha de Nacimiento no puede estar vac&iacute;o");
			$('#fechanacimiento').focus();
			return false;
		}

		if ($("#idEstadoCivil").val() == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar el campo de Estado Civil");
			$('#idEstadoCivil').focus();
			return false;
		}

		if ($("#rolid").val() == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar el campo de Rol");
			$('#rolid').focus();
			return false;
		}

		if ($("#nombrelugarpago").val() == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar un Centro de Costo");
			$('#nombrelugarpago').focus();
			return false;
		}

		if ($("#nombrecentrocosto").val() == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar Relaci�n Laboral");
			$('#nombrecentrocosto').focus();
			return false;
		}

		if ($("#idEstadoEmpleado").val() == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar el Estado del Empleado");
			$('#idEstadoEmpleado').focus();
			return false;
		}

		if ($('#FechaDocumento').val().length == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar la Fecha del Documento");
			$('#FechaDocumento').focus();
			return false;
		}
    
		MostrarCargando();
		return true;
	}

  $(".newusuarioid_IPDFA").change(function(){
	
		//Limpiar los campos 
		$("#nombre").val('');
		$("#appaterno").val('');
		$("#apmaterno").val('');
		$("#fechanacimiento").val('');
		$("#correo").val('');
		$("#nacionalidad").val('');
		$("#direccion").val(''); 
		$("#comuna").val('');
		$("#ciudad").val('');
		$("#idEstadoCivil").val(0);
		$("#rolid").val(0);
		$("#idEstadoEmpleado").val(0);
		
		var respuesta = validaRut2(document.formulario.newusuarioid);

		if( respuesta ){
			var RutUsuario = $(".newusuarioid_IPDFA").val();
			var url  = "Generar_Documento_PorFicha_ajax.php";
            var parametros  = "personaid=" + RutUsuario;

			// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
			if( window.XMLHttpRequest )
			  ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
			else
			  ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
		   
			// Almacenamos en el control al funcion que se invocara cuando la peticion
			// cambie de estado 
			ajax_IPDFA.onreadystatechange = funcionCallback_rutusuario_IPDFA;

			// Enviamos la peticion
			ajax_IPDFA.open( "POST", url, true );
            ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax_IPDFA.send(parametros);
		}
  });

  function funcionCallback_rutusuario_IPDFA()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IPDFA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_IPDFA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_IPDFA = ajax_IPDFA.responseText;
        datos = JSON.parse(salida_IPDFA);
        var cant = Object.keys(datos).length;

        if( cant > 0 ){ 
			console.log(datos);
        	$("#nombre").val( datos.nombre );
			$("#appaterno").val( datos.appaterno );
			$("#apmaterno").val( datos.apmaterno );
        	$("#fechanacimiento").val( datos.fechanacimiento );
        	$("#correo").val(datos.correo);
        	$("#nacionalidad").val(datos.nacionalidad);
        	$("#direccion").val(datos.direccion); 
        	$("#comuna").val(datos.comuna);
        	$("#ciudad").val(datos.ciudad);
        	$("#idEstadoCivil").val(datos.estadocivil);

        	var idestado = '#idEstadoCivil option[value="'+ datos.estadocivil + '"]';
        	$(idestado).attr("selected",true);

        	var rolid = '#rolid option[value="'+ datos.rolid +'"]'; 
        	$(rolid).attr("selected", true);
			
			var idestadoEmpleado = '#idEstadoEmpleado option[value="'+ datos.idEstadoEmpleado + '"]';
			$(idestadoEmpleado).attr("selected",true);

        }else{
            $("#mensajeError").removeClass("callout callout-warning");
            $("#mensajeError").html("");
        }
      }
    }
  }

  //Modal de Lugares de pago 
  /*$(".btn_lugares_pago_IPDFA").click(function(){

  	var RutEmpresa = $(".RutEmpresa_IPDFA").val();
  	$(".fila_lp").remove();
  	if( RutEmpresa == 0 ){

  		$("#mensajeError").addClass("callout callout-warning");
  		$("#mensajeError").html("Debe seleccionar la Empresa");
  		return false;

  	}else{

  		$("#mensajeError").html("");
  		$("#mensajeError").removeClass("callout callout-warning");
  		

		var url  = "Generar_Documento_PorFicha_ajax1.php";
        var parametros = "RutEmpresa=" + RutEmpresa;

	    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
	    if( window.XMLHttpRequest )
	      ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
	    else
	      ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
	   
	    // Almacenamos en el control al funcion que se invocara cuando la peticion
	    // cambie de estado 
	    ajax_IPDFA.onreadystatechange = funcionCallback_lugarespago;

	    // Enviamos la peticion
	    ajax_IPDFA.open( "POST", url, true );
        ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    ajax_IPDFA.send( parametros );
	}

  });

  function funcionCallback_lugarespago()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_IPDFA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_IPDFA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_IPDFA = ajax_IPDFA.responseText;
        listado = JSON.parse(salida_IPDFA);
     	num = listado.length;

     	if( num > 0 ){
	       	$.each(listado, function( index, value ) {
		 		$('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td>' + listado[index].lugarpagoid + '</td><td><div id="' + listado[index].lugarpagoid + '">' + listado[index].nombrelugarpago + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_lp_IPDFA" id="btn_agregar_lp_IPDFA" onclick="agregarLp_IPDFA(' + listado[index].lp + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
		 	});
		}
		else{
			$('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td colspan = 3>No existen Centros de Costo de la Empresa seleccionada</td></tr>');
		}
      }
    }
  }

  function agregarLp_IPDFA(i){
 
  	$("#lugarpagoid").val(i);
  	$("#idCentroCosto").val(i);
  	$("#nombrelugarpago").val($("#" + i ).html());
  	$("#cerrar_lugares_pago").click();
  }*/
  
   //Modal de Lugares de pago 
    $(".btn_lugares_pago_IPDFA").click(function() {
        RutEmpresa = $(".RutEmpresa_IPDFA").val();
        $(".fila_lp").remove();
        if( RutEmpresa == 0 ) {
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("Debe seleccionar la Empresa");
            return false;
        } 
		$("#mensajeError").html("");
		$("#mensajeError").removeClass("callout callout-warning");
		mostrarLP();
	});
			
			
	var mostrarLP = function(){
	
			var url  = "Generar_Documento_PorFicha_ajax1.php?RutEmpresa=" + RutEmpresa;
			
            var table = $('#tabla_lugares_pago').dataTable({
			"ordering": false,
			"destroy" : true,
			"method": "POST",
			"ajax_IPDFA": {
				"url": url,
				"dataType": "json",
				"cache": false,
				"dataSrc": ""
			},
			
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"defaultContent": '<button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_lp_IPDFA" id="btn_agregar_lp_IPDFA" ><i class="fa fa-plus" aria-hidden="true"></i></button>' //"<button class='btn'>Click!</button>
			} ],
			'language': {
                'sLoadingRecords': 'Cargando...',
                'oPaginate': {
                    'sFirst': '<<',
                    'sLast': '>>',
                    'sNext': '>',
                    'sPrevious': '<'
                }
			 }
		});
				
		btn_agregar_lp_IPDFA("#tabla_lugares_pago tbody",table);
		
		$('#tabla_lugares_pago').css("width","100%");
	};
	var btn_agregar_lp_IPDFA= function(tbody,table){
		$(tbody).on("click","tr",function(){
			var codigo = $(this).find("td:first").html();
			var nombre = $(this).find('td:eq(1)').html();
			//console.log(codigo);
			
			agregarLp_IPDFA(codigo,nombre);
		});
	}

    function agregarLp_IPDFA(i,j) {
        $("#lugarpagoid").val(i);
        $("#nombrelugarpago").val(j);
        $("#cerrar_lugares_pago").click();
		$("#idCentroCosto").val("");
		$("#nombrecentrocosto").val("");
    }
 
//Modal de Lugares de pago 
	/*$("#btn_departamento").click(function(){

		var RutEmpresa = $(".RutEmpresa_IPDFA").val();
		var LugarPagoid = $('#lugarpagoid').val();

		$(".fila_depa").remove();

		if( RutEmpresa == 0 ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar la Empresa");
			return false;
		}
		if( LugarPagoid == '' ){
			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar Lugar de pago");
			return false;
		}
		$("#mensajeError").html("");
		$("#mensajeError").removeClass("callout callout-warning");


		var url  = "Generar_Documento_PorFicha_ajax3.php";
        var parametros = "lugarpagoid=" + LugarPagoid+"&RutEmpresa="+RutEmpresa;

		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
			ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
		else
			ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax_IPDFA.onreadystatechange = funcionCallback_departamento;

		// Enviamos la peticion
		ajax_IPDFA.open( "POST", url, true );
        ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax_IPDFA.send( parametros);
	});

	function funcionCallback_departamento()
	{
		// Comprobamos si la peticion se ha completado (estado 4)
		if( ajax_IPDFA.readyState == 4 )
		{
			// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
			if( ajax_IPDFA.status == 200 )
			{
				// Escribimos el resultado en la pagina HTML mediante DHTML
				salida_IPDFA = ajax_IPDFA.responseText;
				listado = JSON.parse(salida_IPDFA);
				num = listado.length;

				if( num > 0 ){
						$.each(listado, function( index, value ) {
						$('#tabla_departamento tr:last').after('<tr class="fila_depa"><td>' + listado[index].departamentoid + '</td><td><div id="depa_' + listado[index].departamentoid + '">' + listado[index].nombre + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_depa" id="btn_agregar_depa" onclick="agregarDepa(' + listado[index].depa + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
					});
				}
				else{
					$('#tabla_departamento tr:last').after('<tr class="fila_depa"><td colspan = 3>No existen Divisiones de la Sede y la Empresa seleccionada</td></tr>');
				}
			}
		}
	}

	function agregarDepa(i){

		$("#departamentoid").val(i);
		$("#nombredepartamento").val($("#depa_" + i ).html());
		$("#cerrar_departamento").click();
	}*/

	//Modal de Centros de Costo
	/*$(".btn_centro_costo_IPDFA").click(function(){

		var RutEmpresa = $(".RutEmpresa_IPDFA").val();
		var LugarPagoid = $("#lugarpagoid").val();
		
		$(".fila_cc").remove();

		if( RutEmpresa == 0 ){

			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar la Empresa");
			return false;

		}

		if( LugarPagoid == '' ){

			$("#mensajeError").addClass("callout callout-warning");
			$("#mensajeError").html("Debe seleccionar Lugar de Pago");
			return false;

		}

		$("#mensajeError").html("");
		$("#mensajeError").removeClass("callout callout-warning");

		var url  = "Generar_Documento_PorFicha_ajax2.php2;
        var parametros = "RutEmpresa=" + RutEmpresa + "&lugarpagoid=" + LugarPagoid;

		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
		ajax_IPDFA = new XMLHttpRequest(); // No Internet Explorer
		else
		ajax_IPDFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax_IPDFA.onreadystatechange = funcionCallback_centrocosto;

		// Enviamos la peticion
		ajax_IPDFA.open( "POST", url, true );
        ajax_IPDFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax_IPDFA.send(parametros);


	});

function funcionCallback_centrocosto()
{
	// Comprobamos si la peticion se ha completado (estado 4)
	if( ajax_IPDFA.readyState == 4 )
	{
		// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
		if( ajax_IPDFA.status == 200 )
		{
			// Escribimos el resultado en la pagina HTML mediante DHTML
			salida_IPDFA = ajax_IPDFA.responseText;
			listado = JSON.parse(salida_IPDFA); 
			num = listado.length;

			if( num > 0 ){
					$.each(listado, function( index, value ) {
					$('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td>' + listado[index].centrocostoid + '</td><td><div id="' + listado[index].centrocostoid + '">' + listado[index].nombre + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cc" id="btn_agregar_cc" onclick="agregarCC_IPDFA(' + listado[index].cc + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
				});
			}else{
				$('#tabla_centro_costo tr:last').after('<tr class="fila_cc"><td colspan=3 >No existen Relaciones laborales para la Empresa y el Centro de Costo seleccionado</td></tr>');
			}
		}
	}
}

function agregarCC_IPDFA(i){
$("#centrocostoid").val(i);
$("#nombrecentrocosto").val($("#"+i).html());
$("#cerrar_centro_costo").click();
}*/

//Modal de Centros de Costo
    $(".btn_centro_costo_IPDFA").click(function() {
        RutEmpresa = $(".RutEmpresa_IPDFA").val();
        //departamento = $("#departamentoid").val();
        LugarPagoid = $("#lugarpagoid").val();
        $(".fila_cc").remove();
        if( RutEmpresa == 0 ) {
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("Debe seleccionar la Empresa");
            return false;
        }
        if( LugarPagoid == '' ) {
            $("#mensajeError").addClass("callout callout-warning");
            $("#mensajeError").html("Debe seleccionar un Lugar Pago");
            return false;
        }
        
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
		mostrarCC_IPDFA();
	});	
		
	var mostrarCC_IPDFA = function(){	
       // var url  = "gs_centroscosto_ajax.php?RutEmpresa=" + RutEmpresa + "&departamentoid=" + departamento + '&lugarpagoid=' + LugarPagoid;
		var url  = "Generar_Documento_PorFicha_ajax2.php?RutEmpresa=" + RutEmpresa + '&lugarpagoid=' + LugarPagoid;
		
        var table = $('#tabla_centro_costo').dataTable({
			"ordering": false,
			"destroy" : true,
			"method": "POST",
			"ajax_IPDFA": {
				"url": url,
				"dataType": "json",
				"cache": false,
				"dataSrc": ""
			},
			
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"defaultContent": '<button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cc" id="btn_agregar_cc" ><i class="fa fa-plus" aria-hidden="true"></i></button>' //"<button class='btn'>Click!</button>
			} ],
			'language': {
                'sLoadingRecords': 'Cargando...',
                'oPaginate': {
                    'sFirst': '<<',
                    'sLast': '>>',
                    'sNext': '>',
                    'sPrevious': '<'
                }
			 }
		});
				
		btn_agregar_cc_IPDFA("#tabla_centro_costo tbody",table);		
		$('#tabla_centro_costo').css("width","100%");
	};
	var btn_agregar_cc_IPDFA= function(tbody,table){
		$(tbody).on("click","tr",function(){
			var codigo = $(this).find("td:first").html();
			var nombre = $(this).find('td:eq(1)').html();
			console.log(codigo,nombre);
			agregarCC_IPDFA(codigo,nombre);
		});
	}

    function agregarCC_IPDFA(i,j) {
        $("#centrocostoid").val(i);
        $("#nombrecentrocosto").val(j);
        $("#cerrar_centro_costo").click();
		
		$(".idTipoDoc_IPDFA").attr("disabled",false);
		$(".idTipoDoc_IPDFA").val(0);  
    }

 function Checkfiles_IPDFA()
 {	
	
	var fup = document.getElementById('pdf64');
	var fileName = fup.value;
	
	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
	if(ext == "pdf" || ext == "PDF")
	{
		return true;
	} 
	else
	{
		alert("Solo se pueden seleccionar archivos pdf !");
		fup.focus();
		return false;	
	}
}

$(".pdf64_IPDFA").change(function(){

   $("#Documento").val($(".pdf64_IPDFA").val());
   $("#Documento").attr("disabled",true);//deshabilita texto de la subida del documento
	   
   mostrarFirmantes_IPDFA();  

   if ( $("#Representantes").val() == '' ){
	   $("#GENERAR").attr('disabled', false);
   }
});

$(".clicktab_IPDFA").click(function(){
	let tabid = $(this).attr("id");
	let href = $(this).attr("href");
	$(".clicktab_IPDFA").removeClass("active").attr("aria-selected","false");
	$("#"+tabid).addClass("active").attr("aria-selected","true");
	$(".tab-pane").removeClass("active").removeClass("show");
	$(href).addClass("active").addClass("show");
});

/*templates\linkPostuacion.html*/


$('.RutEmpresa_LINKP').on('change', function(){
    activaListado_LINKP();
});

$('.proximidadCaducidadId_LINKP').on('change', function(){
    activaListado_LINKP();
});

function activaListado_LINKP()
{
    if ($('.RutEmpresa_LINKP').val() != 0)
    {
        getListado_LINKP();
    }
    else
    {
        listado_LINKP = [];
        add2listado_LINKP(listado_LINKP);
        // Limpier listado_LINKP
    }
}

$('.link_LINKP').on('change', function(){
    etiquetaGuia_LINKP();
});
$('.fechaCaducidadLink_LINKP').on('change', function(){
    etiquetaGuia_LINKP();
});

function etiquetaGuia_LINKP()
{
    console.log($('.link_LINKP').val(), $('.fechaCaducidadLink_LINKP').val());
    if ($('.link_LINKP').val() != '' || $('.fechaCaducidadLink_LINKP').val() != '')
    {
        $('#obligatorio').show();
        $('#uno').html('Link (*)');
        $('#dos').html('Fechas caducidad link (*)');
    }
    else
    {
        $('#obligatorio').hide();
        $('#uno').html('Link');
        $('#dos').html('Fechas caducidad link');
    }
}


function getListado_LINKP()
{
    var RutEmpresa = $(".RutEmpresa_LINKP").val();
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "linkPostulacion_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + '&proximidadCaducidadId=' + $(".proximidadCaducidadId_LINKP").val();
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LINKP = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LINKP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LINKP.onreadystatechange = funcionCallback_cargaCargos_LINKP;
    // Enviamos la peticion
    ajax_LINKP.open( "POST", url, true );
    ajax_LINKP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LINKP.send(parametros);
}

function funcionCallback_cargaCargos_LINKP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LINKP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_LINKP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax_LINKP.responseText;
            listado_LINKP = JSON.parse(salida);
            add2listado_LINKP(listado_LINKP); 
        }
    }
}

function add2listado_LINKP(listado_LINKP)
{
    $('#tabla_actualizacionLink').DataTable().destroy();
    $('#tabla_actualizacionLink').DataTable({
        data: listado_LINKP,
        columns: [
            {
                data: 'Titulo'
            },
            {
                data: 'link'
            },
            {
                data: 'fechaCaducidadLink'
            },
            {
                data: 'estadoLink',
                render: function ( data, type, row ) {
                    switch(data)
                    {
                        case 1: // Negro
                        {
                            html = '<div style="text-align: center;" title="' + row.estadoLinkTitle + '">		<i class="fa fa-circle" aria-hidden="true" style="color:black;" 	data-toggle="tooltip" title="' + row.estadoLinkTitle + '" 		alt="' + row.estadoLinkTitle + '"></i></div>';
                            break;
                        }
                        case 2: // Verde
                        {
                            html = '<div style="text-align: center;" title="' + row.estadoLinkTitle + '">		<i class="fa fa-circle" aria-hidden="true" style="color:green;" 	data-toggle="tooltip" title="' + row.estadoLinkTitle + '" 		alt="' + row.estadoLinkTitle + '"></i></div>';;
                            break;
                        }
                        case 3: // Naranjo
                        {
                            html = '<div style="text-align: center;" title="' + row.estadoLinkTitle + '">	<i class="fa fa-circle" aria-hidden="true" style="color:yellow;" 	data-toggle="tooltip" title="' + row.estadoLinkTitle + '" 	alt="' + row.estadoLinkTitle + '"></i></div>';
                            break;
                        }
                        case 4: // Rojo
                        {
                            html = '<div style="text-align: center;" title="' + row.estadoLinkTitle + '">		<i class="fa fa-circle" aria-hidden="true" style="color:red;" 		data-toggle="tooltip" title="' + row.estadoLinkTitle + '" 	alt="' + row.estadoLinkTitle + '"></i></div>';
                            break;
                        }
                    }
                    return html;
                }
            },
            {
                data: 'Opciones',
                render: function ( data, type, row ) {
                    html = '<button class="btn btn-md btn-warning btn-block" onclick="javascrip: mostrarModal_LINKP(\'' + row.link + '\', \'' + row.fechaCaducidadLink + '\', \'' + row.RutEmpresa + '\', \'' + row.idCargoEmpleado + '\', \'' + row.RazonSocial + '\', \'' + row.Titulo + '\')" type="button" id="btn_modificarLink" data-toggle="modal" data-target="#modal_link" style="margin-top: 10px" >Modificar</button>';
                    return html;
                }
            }
        ],
        paging: false,
        //lengthMenu: [ 10, 25, 50 ],
        lengthChange: true,
        searching: false,
        ordering: false,
        info: true,
        autoWidth: false,
        fixedColumns: true
    });
}

function mostrarModal_LINKP(link, fechaCaducidadLink, RutEmpresa, idCargoEmpleado, RazonSocial, Titulo)
{
    $('.link_LINKP').val((link == 'null' ? '' : link));
    $('.fechaCaducidadLink_LINKP').val((fechaCaducidadLink == 'null' ? '' : fechaCaducidadLink));
    $('.RutEmpresa_LINKP').val(RutEmpresa);
    $('#idCargoEmpleado').val(idCargoEmpleado);
    $('#RazonSocial').val(RazonSocial);
    $('#Titulo').val(Titulo);
}

function modificar_LINKP(link, fechaCaducidadLink, RutEmpresa, idCargoEmpleado)
{
    if ((link == '' && fechaCaducidadLink == '' ? true : (link != '' && fechaCaducidadLink != '' ? true : alert('Complete ambos campos o limpie ambos campos'))))
    {
        // Cerrar modal
        $("#modal_link").modal('hide');//ocultamos el modal
        $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
        $('.modal-backdrop').remove();//eliminamos el backdrop del modal
        console.log(link, fechaCaducidadLink, RutEmpresa, idCargoEmpleado);
        actualizar_LINKP(link, fechaCaducidadLink, RutEmpresa, idCargoEmpleado);
    }
    else
    {}
}

function actualizar_LINKP(link, fechaCaducidadLink, RutEmpresa, idCargoEmpleado)
{
    //var RutEmpresa = $(".RutEmpresa_LINKP").val();
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "linkPostulacion_ajax2.php";
    var parametros = "RutEmpresa=" + RutEmpresa + '&idCargoEmpleado=' + idCargoEmpleado + '&fechaCaducidadLink=' + fechaCaducidadLink + '&link=' + encodeURIComponent(btoa(link));
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LINKP = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LINKP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LINKP.onreadystatechange = funcionCallback_actualizar_LINKP;
    // Enviamos la peticion
    ajax_LINKP.open( "POST", url, true );
    ajax_LINKP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LINKP.send(parametros);
}

function funcionCallback_actualizar_LINKP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LINKP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_LINKP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax_LINKP.responseText;
            listado_LINKP = JSON.parse(salida);
            //add2listado_LINKP(listado_LINKP);
            if (listado_LINKP.exito)
            {
                alert(listado_LINKP.mensaje);
                getListado_LINKP();
            }
            else
            {
                alert(listado_LINKP.mensaje);
            }
        }
    }
}

/*templates\loadResultadoPostulacion.html*/

    
$('.soloUno_LORP').click(function()
{
    $('#GENERAR').attr('disabled', true);
    if ($(".soloUno_LORP").is(':checked'))
    {
        $('#sin_planilla').hide();
        $('#con_planilla').show();
        $('.newusuarioid_LORP').val('');
        $(".estadoPostulacionid_LORP").prop("selectedIndex", 0).val(); 
    }
    else
    {
        $('#con_planilla').hide();
        $('#sin_planilla').show();
        $("#Documento").val('');
        $(".archivo_LORP").val('');
    }
});

$(".RutEmpresa_LORP").change(function(){
    $("#nombrecargoempleado").val('');
    $("#idCargoEmpleado").val('');
});

//Modal de Cargos empleado
$(".btn_cargoEmpleado_LORP").click(function()
{
    var RutEmpresa = $(".RutEmpresa_LORP").val();
    $(".fila_ce").remove();
    if( RutEmpresa == 0 )
    {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "postulacion_ajax3.php";
    var parametros = "RutEmpresa=" + RutEmpresa;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LORP = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LORP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LORP.onreadystatechange = funcionCallback_cargoEmpleado_LORP;
    // Enviamos la peticion
    ajax_LORP.open( "POST", url, true );
    ajax_LORP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LORP.send(parametros);
});

function funcionCallback_cargoEmpleado_LORP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LORP.readyState == 4 )
    {
        // Comprobamos si la respuesta_LORP ha sido correcta (resultado HTTP 200)
        if( ajax_LORP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida = ajax_LORP.responseText;
            listado = JSON.parse(salida); 
            num = listado.length;
            if( num > 0 ){
                //[{"0":"cc1","centrocostoid":"cc1","1":"cc1","nombrecentrocosto":"cc1","2":"'cc1'","lp":"'cc1'"},{"0":"cc2","centrocostoid":"cc2","1":"c2","nombrecentrocosto":"c2","2":"'cc2'","lp":"'cc2'"}]
                $.each(listado, function( index, value ) {
                    $('#tabla_cargoEmpleado tr:last').after('<tr class="fila_ce"><td>' + listado[index].idCargoEmpleado + '</td><td><div id="' + listado[index].idCargoEmpleado + '">' + listado[index].nombrecargoempleado + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_ce" id="btn_agregar_ce" onclick="agregarCE_LORP(\'' + listado[index].idCargoEmpleado + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#tabla_cargoEmpleado tr:last').after('<tr class="fila_ce"><td colspan=3 >No existen Relaciones laborales para la Empresa y el cargo seleccionado</td></tr>');
            }
        }
    }
}

function agregarCE_LORP(i){
    $("#idCargoEmpleado").val(i);
    $("#nombrecargoempleado").val($("#"+i).html());
    habilitaBoton_LORP();
    $("#cerrar_cargoEmpleado").click();
}

function InicioLoadResultado(){
    if ($('#progreso').attr('max') != '')
    {
        estadoGeneracionMasiva_LORP();
    }
};

function ver_LORP()
{
    document.getElementById("resultado").submit();
}


function estadoGeneracionMasiva_LORP()
{
    $('#estado').show();
    consultaEstado_LORP();
    consulta_LORP = setInterval(function(){ consultaEstado_LORP() }, 10000);
}

function consultaEstado_LORP()
{
    conexion_LORP=crearXMLHttpRequest();
    conexion_LORP.open('POST', 'loadResultadoPostulacionProcessEstado.php?accion=ESTADO&IdArchivo=3',false);
    conexion_LORP.send(null);
    try
    {
        respuesta_LORP = JSON.parse(conexion_LORP.responseText);
    }
    catch (e)
    {
        respuesta_LORP.actual = 0;
    }
    respuesta_LORP.actual = respuesta_LORP.actual == null ? 0 : respuesta_LORP.actual;
    $('#progreso').val(respuesta_LORP.actual);
    $('#estadodetalle').html(respuesta_LORP.actual + " filas procesadas de un total de " +  ($('#progreso').attr('max')) + " filas.");
    if (controlMemory_LORP(respuesta_LORP.actual))
    {
        if (respuesta_LORP.actual == $('#progreso').attr('max'))
        {
            clearInterval(consulta_LORP);
            $('#estado').hide();
            alert('El proceso de generacion masiva de documentos ha finalizado.');
        }
    }
    else
    {
        matarProceso_LORP();
        clearInterval(consulta_LORP);
        $('#estado').hide();
        alert('Ha ocurrio un error, revise detalle y ejecute nuevamene las filas no procesadas.');
    }
}

function matarProceso_LORP()
{
    conexion_LORP=crearXMLHttpRequest();
    conexion_LORP.open('POST', 'loadResultadoPostulacionProcess.php?accion=KILL',false);
    conexion_LORP.send(null);
    memoryCount_LORP = 0;
}



function controlMemory_LORP(dato)
{
    var respuesta_LORP = true;
    if (memory_LORP == dato)
    {
     memoryCount_LORP++;
    }
    else
    {
        memoryCount_LORP = 0;
    }
    if (memoryCount_LORP >= memoryTop_LORP)
    {
        respuesta_LORP = false;
    }
    memory_LORP = dato;
    console.log(dato, memory_LORP, memoryCount_LORP, memoryTop_LORP, respuesta_LORP);
    return respuesta_LORP;
}

$(".newusuarioid_LORP").change(function(){
    $("#Documento").val('');
    $(".archivo_LORP").val('');
    var respuesta_LORP = validaRut2(document.formulario.newusuarioid);
    habilitaBoton_LORP();
});

$(".estadoPostulacionid_LORP").change(function(){
    $("#Documento").val('');
    $(".archivo_LORP").val('');
    habilitaBoton_LORP();
});

function habilitaBoton_LORP()
{
    if( ($("#Documento").val() != '' || ($('.newusuarioid_LORP').val() != '' && $('.estadoPostulacionid_LORP').val() != 0 )) && ($(".RutEmpresa_LORP").val() != 0 && $("#idCargoEmpleado").val() != 0) )
    {
        $("#GENERAR").attr('disabled', false);
    }
    else
    {
        $("#GENERAR").attr('disabled', true);
    }
}

$(".RutEmpresa_LORP").change(function()
{
    habilitaBoton_LORP();
});

$(".archivo_LORP").change(function()
{
    $("#Documento").val($(".archivo_LORP").val());
    $('.newusuarioid_LORP').val('');
    $(".estadoPostulacionid_LORP").prop("selectedIndex", 0).val(); 
    habilitaBoton_LORP();
});

function subirArchivo_LORP()
{
    if( $("#Documento").val() != '')
    {
        //revisa si hay algun cambio en el archivo
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        MostrarCargando();
        if (validar_LORP() == true)
        {
            upload_input = document.querySelectorAll('.archivo_LORP')[0];
            uploadFile_LORP( upload_input.files[0] );
        }
    }
    else if ($('.newusuarioid_LORP').val() != '')
    {
        procesarUno_LORP();
    }
}

function validar_LORP()
{
    /*if ( $(".RutEmpresa_LORP").val() == 0 ){
        $(".RutEmpresa_LORP").focus();
        return false;
    }
    if ( $("#idTipoDoc").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de documento");
        $("#idTipoDoc").focus();
        return false;
    }
    if( $("#idProceso").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione un Proceso");
        $("#idProceso").focus();
        return false;
    }
    if( $("#idPlantilla").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione la plantilla");
        $("#idPlantilla").focus();
        return false;
    }
    if( $("#idFirma").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de firma del documento");
        $("#idFirma").focus();
        return false;
    }
    if( $("#Documento").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el archivo de carga que necesita");
        $("#Documento").focus();
        return false;
    }*/
    return true;
}

function uploadFile_LORP(file)
{
    $("#GENERAR").attr('disabled', true);
    importar_LORP(file,3);
}

function importar_LORP(file,IdArchivo)
{
    var xhr =  createXMLHttp();
    xhr.upload.addEventListener('loadstart',function(e)
    {
        document.getElementById('mensaje').innerHTML = 'Cargando archivo...';
    }, false);
    xhr.upload.addEventListener('load',function(e)
    {
        document.getElementById('mensaje').innerHTML = '';
    }, false);
    xhr.upload.addEventListener('error',function(e)
    {
        alert('Ha habido un error :/');
    }, false);
    var datos = $('#formulario').serialize();
    var url  = 'loadResultadoPostulacionProcess.php?accion=LOAD&datos=' + datos + '&IdArchivo='+ IdArchivo;
    //var url  = 'loadResultadoPostulacion.php?accion=LOAD&IdArchivo='+ IdArchivo + '&RutEmpresa=' + rutempresa + '&datos=' + datos;
    xhr.open('POST',url,false);//se le agrego false para que sea sincrono, para que espere antes de comenzar a cargar el otro archivo.
    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);
    xhr.addEventListener('readystatechange', function(e) 
    {
        if( this.readyState == 4 ) 
        {
            try
            {
                respuesta_LORP = JSON.parse(xhr.responseText);
                if(IdArchivo == 3 )
                {
                    $('#progreso').attr('max', respuesta_LORP.highestRow);
                    procesar_LORP(IdArchivo);
                    estadoGeneracionMasiva_LORP();
                }
            }
            catch (e)
            {
                respuesta_LORP = xhr.responseText;
                var elementoError   = document.getElementById("mensajeError");
                elementoError.innerHTML = respuesta_LORP;
                elementoError.className += "callout callout-danger";
            }
        }
        if( IdArchivo == 3 )
        {
            document.getElementById("Documento").value = "";
            document.getElementById("archivo").value = "";
            OcultarCargando();
        } 
    });
    xhr.send(file);
}




function procesarUno_LORP()
{
    var parametros = 'accion=UNO' + '&IdArchivo=3&rut=' + $('.newusuarioid_LORP').val() + '&estadoPostulacion=' + $('.estadoPostulacionid_LORP').val() + '&RutEmpresa=' + $('.RutEmpresa_LORP').val() + '&idCargoEmpleado=' + $('#idCargoEmpleado').val();
    var url  = "loadResultadoPostulacionProcess.php" + parametros;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LORP = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LORP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LORP.onreadystatechange = funcionCallback_soloUNO_LORP;
    // Enviamos la peticion
    ajax_LORP.open( "POST", url, true );
    ajax_LORP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LORP.send(parametros);
}

function funcionCallback_soloUNO_LORP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LORP.readyState == 4 )
    {
        // Comprobamos si la respuesta_LORP ha sido correcta (resultado HTTP 200)
        if( ajax_LORP.status == 200 )
        {
            var salida = ajax_LORP.responseText;
            respuesta_LORP = JSON.parse(salida);
            $('#mensajeOK').removeClass();
            if (respuesta_LORP.exito)
            {
                alert(respuesta_LORP.mensaje);
            }
        }
    }
}

function procesar_LORP(IdArchivo)
{
    conexion_LORP=crearXMLHttpRequest();
    var datos = $('#formulario').serialize();
    parametros = '?accion=LOOP0' + '&IdArchivo='+ IdArchivo + '&RutEmpresa=' + $('.RutEmpresa_LORP').val() + '&idCargoEmpleado=' + $('#idCargoEmpleado').val();
    conexion_LORP.open('POST', 'loadResultadoPostulacionProcess.php' + parametros);
    conexion_LORP.send(null);
    respuesta_LORP =  conexion_LORP.responseText;	
}

/*templates\setDocumentos_Agregar.html*/



$('.AGREGAR_SDAgregar').on('click', function(){
    var RutEmpresa = $("#rutEmpresa").data('rutempresa');
    var idCargoEmpleado = $('#cargoempleado').data('idCargoEmpleado');
    var idplantilla = $('#plantilla').data('idplantilla');
    var idtipomovimiento = $('.tipomovimiento_SDAgregar').val();
    var url  = "setDocumentos_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + '&idCargoEmpleado=' + idCargoEmpleado + '&idPlantilla=' + idplantilla + '&idTipoMovimiento=' + idtipomovimiento + '&accion=AGREGAR';
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_SDAgregar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_SDAgregar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_SDAgregar.onreadystatechange = funcionCallback_agregar_SDAgregar;
    // Enviamos la peticion
    ajax_SDAgregar.open( "POST", url, true );
    ajax_SDAgregar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_SDAgregar.send(parametros);
});
function refrescarFormulario_SDAgregar()
{
    setDocumentos_listar_SDAgregar();
    $("#plantilla").val('');
    $('#plantilla').data('idplantilla', '');
    checkFormulario_SDAgregar();
}
function funcionCallback_agregar_SDAgregar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_SDAgregar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_SDAgregar.status == 200 )
        {
            refrescarFormulario_SDAgregar();
        }
    }
}
function checkFormulario_SDAgregar()
{
    if ($("#rutEmpresa").data('rutempresa') != '' && $('#cargoempleado').data('idCargoEmpleado') != '' && $('#plantilla').data('idplantilla') != '' && $('.tipomovimiento_SDAgregar').val() != '')
    {
        $('.AGREGAR_SDAgregar').removeAttr('disabled');
    }
    else
    {
        $('.AGREGAR_SDAgregar').attr('disabled','disabled');
    }
}
$('.tipomovimiento_SDAgregar').on('change', function(){
    checkFormulario_SDAgregar();
});
function agregarEmpresa_SDAgregar(RutEmpresa, RazonSocial)
{
    $("#rutEmpresa").val(RazonSocial);
    $("#rutEmpresa").data('rutempresa', RutEmpresa);
    $("#cargoempleado").val('');
    $('#cargoempleado').data('idCargoEmpleado', '');
    $("#plantilla").val('');
    $('#plantilla').data('idplantilla', '');
    $("#cerrar_empresa").click();
    checkFormulario_SDAgregar();
}
function agregarCC_SDAgregar(nombrecargoempleado, idCargoEmpleado)
{
    $("#cargoempleado").val(nombrecargoempleado);
    $('#cargoempleado').data('idCargoEmpleado', idCargoEmpleado);
    $("#cerrar_cargoempleado").click();
    setDocumentos_listar_SDAgregar();
    checkFormulario_SDAgregar();
}
function agregarPlantilla_SDAgregar(idPlantilla, Descripcion_Pl)
{
    $("#plantilla").val(Descripcion_Pl);
    $('#plantilla').data('idplantilla', idPlantilla);
    $("#cerrar_plantilla").click();
    checkFormulario_SDAgregar();
}
function setDocumentos_listar_SDAgregar()
{
    var RutEmpresa = $("#rutEmpresa").data('rutempresa');
    var idCargoEmpleado = $("#cargoempleado").data('idCargoEmpleado');
    $(".fila_setDocumentos").remove();
    var url  = "setDocumentos_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa + "&idCargoEmpleado=" +idCargoEmpleado + "&accion=LISTAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_SDAgregar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_SDAgregar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_SDAgregar.onreadystatechange = funcionCallback_setDocumentos_listar_SDAgregar;
    // Enviamos la peticion
    ajax_SDAgregar.open( "POST", url, true );
    ajax_SDAgregar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_SDAgregar.send(parametros);
}
function funcionCallback_setDocumentos_listar_SDAgregar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_SDAgregar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_SDAgregar.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_SDAgregar = ajax_SDAgregar.responseText;
            listado = JSON.parse(salida_SDAgregar); 
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td>' + listado[index].nombreTipoMovimento + '</td><td>' + listado[index].nombrePlantilla + ' </td><td ><button type="button" style="background-color: transparent;" class="btn btn-default btn-sm" name="btn_eliminar_setDocumentos" onclick="javascript:eliminarSetDocumentos_SDAgregar(\'' + listado[index].idTipoMovimiento + '\', \'' + listado[index].RutEmpresa + '\', \'' + listado[index].idCargoEmpleado + '\', \'' + listado[index].idPlantilla + '\');">Eliminar</button></td></tr>');
                });
            }else{
                $('#tabla_setDocumentos tr:last').after('<tr class="fila_setDocumentos"><td colspan=3 >No existen datos para la empresa y cargo seleccionado </td></tr>');
            }
        }
    }
}
function eliminarSetDocumentos_SDAgregar(idTipoMovimiento, RutEmpresa, idCargoEmpleado,idPlantilla)
{
    var url  = "setDocumentos_ajax.php";
    var parametros  ="RutEmpresa=" + RutEmpresa + "&idCargoEmpleado=" +idCargoEmpleado + "&idTipoMovimiento=" + idTipoMovimiento + "&idPlantilla=" + idPlantilla + "&accion=ELIMINAR";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_SDAgregar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_SDAgregar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_SDAgregar.onreadystatechange = funcionCallback_eliminarSetDocumentos;
    // Enviamos la peticion
    ajax_SDAgregar.open( "POST", url, true );
    ajax_SDAgregar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_SDAgregar.send(parametros);
}
function funcionCallback_eliminarSetDocumentos_SDAgregar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_SDAgregar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_SDAgregar.status == 200 )
        {
            refrescarFormulario_SDAgregar();
        }
    }
}
//Modal de Cargos empreados
$(".btn_cargoempleado_SDAgregar").click(function(){
    var RutEmpresa = $("#rutEmpresa").data('rutempresa');
    $(".fila_cc").remove();
    if( RutEmpresa == "" ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "cargoEmpleadoListar_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_SDAgregar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_SDAgregar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_SDAgregar.onreadystatechange = funcionCallback_cargoempleado_SDAgregar;
    // Enviamos la peticion
    ajax_SDAgregar.open( "POST", url, true );
    ajax_SDAgregar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_SDAgregar.send(parametros);
});
function funcionCallback_cargoempleado_SDAgregar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_SDAgregar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_SDAgregar.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_SDAgregar = ajax_SDAgregar.responseText;
            listado = JSON.parse(salida_SDAgregar); 
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#tabla_cargoempleado tr:last').after('<tr class="fila_cc"><td>' + listado[index].idCargoEmpleado + '</td><td><div id="' + listado[index].idCargoEmpleado + '">' + listado[index].nombrecargoempleado + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cc" id="btn_agregar_cc" onclick="javascript:agregarCC_SDAgregar(\'' + listado[index].nombrecargoempleado + '\', \'' + listado[index].idCargoEmpleado + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#tabla_cargoempleado tr:last').after('<tr class="fila_cc"><td colspan=3 >No existen cargos para la Empresa seleccionada</td></tr>');
            }
        }
    }
}
//Modal de plantillas
$(".btn_plantilla_SDAgregar").click(function(){
    var cargoempleado = $('#cargoempleado').data('idCargoEmpleado');
    var idtipomovimiento = $('.tipomovimiento_SDAgregar').val();
    var RutEmpresa = $("#rutEmpresa").data('rutempresa');
    
    $(".fila_plantilla").remove();
    if (RutEmpresa == "" || RutEmpresa == undefined)
    {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar empresa");
        return false;
    }
    else if( cargoempleado == "" || cargoempleado == undefined){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar cargo");
        return false;
    }
    else if (idtipomovimiento == '')
    {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar tipo de movimiento");
        return false;
    }
    $("#mensajeError").html("");
    $("#mensajeError").removeClass("callout callout-warning");
    var url  = "plantillasListar_ajax.php";
    var parametros  ="RutEmpresa=" + RutEmpresa + "&idCargoEmpleado=" + cargoempleado + "&idtipomovimiento=" + idtipomovimiento;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_SDAgregar = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_SDAgregar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_SDAgregar.onreadystatechange = funcionCallback_plantilla_SDAgregar;
    // Enviamos la peticion
    ajax_SDAgregar.open( "POST", url, true );
    ajax_SDAgregar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_SDAgregar.send(parametros);
});

function funcionCallback_plantilla_SDAgregar()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_SDAgregar.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_SDAgregar.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_SDAgregar = ajax_SDAgregar.responseText;
            listado = JSON.parse(salida_SDAgregar); 
            num = listado.length;
            if( num > 0 ){
                $.each(listado, function( index, value ) {
                    $('#tabla_plantilla tr:last').after('<tr class="fila_plantilla"><td>' + listado[index].Descripcion_Pl + '</td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_plantilla" id="btn_agregar_plantilla" onclick="javascript:agregarPlantilla_SDAgregar(\'' + listado[index].idPlantilla + '\', \'' + listado[index].Descripcion_Pl + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }else{
                $('#tabla_plantilla tr:last').after('<tr class="fila_plantilla"><td colspan=3 >No existen plantillas para la Empresa, cargo y tipo de movimiento seleccionado</td></tr>');
            }
        }
    }
}

/*templates\rl_imprespaldo_FormularioModificar.html*/

function muevedatosimp_RLI($iddocumento,$idtipogestor,$nombretipogestor,$idplantilla)
{	
    $("#iddocumentoimp").val($iddocumento);
    $("#idtipogestorimp").val($idtipogestor);
    $("#nombretipogestor").val($nombretipogestor);
    $("#idplantillaimp").val($idplantilla);
}

var idEstado_RLI = 0;
$(document).ready(function(){
  //$('[data-toggle="tooltip"]').tooltip(); 
  var filas = $("#tabla_documentos tr").length;
  var i = 0;
  idEstado_RLI = $("#idEstado").val();
  //console.log(idEstado);
  if( idEstado_RLI == 3 ){
    $("#BTN-SIGUIENTE").removeAttr('disabled');
    $("#BTN-SIGUIENTE").prop('title','');
    $("#CANCELAR").html('Cancelar flujo');
  }
  if( idEstado_RLI == 6 ){
    $(".subir").attr('disabled',true);
    $(".eliminar_RLI").attr('disabled',true);
    $(".subir_adicional").attr('disabled',true);
    $("#CANCELAR").attr('disabled',true);
    $("#CANCELAR").html('Cancelar flujo');
    //$("#BTN-SIGUIENTE").css('display','none');
  }
  if( idEstado_RLI == 5 ){
    $(".subir").attr('disabled',true);
    $(".eliminar_RLI").attr('disabled',true);
    $(".subir_adicional").attr('disabled',true);
    $("#CANCELAR").html('Habilitar flujo');
    $("#BTN-SIGUIENTE").css('display','none');
  }
    if( idEstado_RLI == 1 || idEstado_RLI == 2 ){
    $("#CANCELAR").html('Cancelar flujo');
    $("#BTN-SIGUIENTE").attr('disabled',true);
    $("#BTN-SIGUIENTE").prop('title','Faltan documentos obligatorios por subir');
  }
  if( idEstado_RLI == 4 ){
    $("#CANCELAR").html('Cancelar flujo');
    $("#BTN-SIGUIENTE").attr('disabled',false);
    $("#BTN-SIGUIENTE").prop('title','');
  }
  for( i = 0; i < filas - 2; i++ ){
    if( $("#documento_" + i).html().length > 0 ){
      $("#Subir_" + i ).css('display','none');
      $("#VER_DOCUMENTO_" + i ).css('display','inline');
      $("#ELIMINAR_DOCUMENTO_" + i ).css('display','inline');
    }else{
      $("#Subir_" + i ).css('display','inline');
      $("#VER_DOCUMENTO_" + i ).css('display','none');
      $("#ELIMINAR_DOCUMENTO_" + i ).css('display','none');
    }
  }
  if( $("#Representantes").val() == 1 ){
    $(".firmantes").css("display","block");
  }else{
    $(".firmantes").css("display","none");
  }
});
function cambiaEstado_RLI()
{
  if (idEstado_RLI != 5) // Cancelado
  {
    return confirm('Desea Cancelar el Flujo de Contrataci\u00f3n?');
  }
  else{
    return confirm('Desea Habilitar el Flujo de Contrataci\u00f3n?');
  }
}
function asignarAlModal_RLI(i){
  $("#idFicha_modal").val($("#fichaid_" + i).val());
  $("#idTipoGestor_modal").val($("#idTipoGestor_" + i).val());
  $("#Obligatorio_modal").val($("#Obligatorio_" + i).val());
  $("#idTipoDoc").val($("#Nombre_" + i).val());
  $("#idTipoGestor").val($("#idTipoGestor_" + i).val());
};
$("#archivo").change(function(){
  $("#Documento").val($("#archivo").val());
});
$("#archivo_adicional").change(function(){
  $("#Documento_adicional").val($("#archivo_adicional").val());
});

$(".AGREGAR_DOC_RLI").click(function(){
    if( $("#archivo").val() == '' ){
        $("#mensajeError_modal").html("Debe seleccionar un documento");
        $("#mensajeError_modal").addClass("callout callout-warning");
        return false;
    }else{
        $("#mensajeError_modal").html("");
        $("#mensajeError_modal").removeClass("callout callout-warning");
    }
  
    var fup = document.getElementById('archivo');
    var fileName = fup.value;
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    if(ext == "pdf" || ext == "PDF")
    {
        if(fup.files[0].size > '20000000')
        {	document.getElementById('archivo').value = "";
            $("#mensajeError_modal").html("Archivo a importar supera el tama&ntilde;o permitido");
            $("#mensajeError_modal").addClass("callout callout-warning");	
           return false;	
        }	
    } 
    else
    {
        document.getElementById('archivo').value = "";
        $("#mensajeError_modal").html("Solo se pueden seleccionar archivos pdf");
        $("#mensajeError_modal").addClass("callout callout-warning");	
        return false;	
    }	  
  
});

$(".AGREGAR_DOC_ADICIONAL_RLI").click(function(){
  if( $("#archivo_adicional").val() == '' ){
    $("#mensajeError_adicional").html("Debe seleccionar un documento");
    $("#mensajeError_adicional").addClass("callout callout-warning");
    return false;
  }
  if( $("#idTipoGestor_adicional").val() == 0 ){
    $("#mensajeError_adicional").html("Debe seleccionar un tipo de documento");
    $("#mensajeError_adicional").addClass("callout callout-warning");
    return false;
  }
  $("#mensajeError_adicional").html("");
  $("#mensajeError_adicional").removeClass("callout callout-warning");
});
//Boton eliminar documento de ficha 
$(".eliminar_RLI").click(function(){
  var respuesta_RLI = confirm("Desea eliminar este Documento?");
  if( respuesta_RLI ){
    MostrarCargando();
    var id = this.id;
    var res = id.split('_');
    var i = res[2]; 
    $("#fichaid_eliminar_" + i).val($("#fichaid_" + i).val());
    $("#documentoid_eliminar_" + i).val($("#documentoid_" + i).val());
    $("#Obligatorio_eliminar_" + i).val($("#Obligatorio_" + i).val());
    $("#formulario_eliminar_" + i).submit();
  }
  else{
    return respuesta_RLI;
  }
});
//Boton de ver documento
$(".ver_RLI").click(function(){
  MostrarCargando();
  var id = this.id;
  var res = id.split('_');
  var i = res[2]; 
  $("#fichaid_ver_" + i).val($("#fichaid_" + i).val());
  $("#documentoid_ver_" + i).val($("#documentoid_" + i).val());
  $("#formulario_ver_" + i).submit();
});
let general_RLI = {
  baseUrl: "",
  apiKey: "",
  digitalSignature: 0,
  session: {
    id: "",
    companyId: "",
    username: ""
  },
  currentAction: "CREATE",
  operator: {
    sessionId: null
  }
};
let popupPage_RLI;
let openCheckId_RLI = function(action) 
{
  MostrarCargando();
  general_RLI.currentAction = action;
  // Create popup window
  var w = 900;
  var h = 620;
  // Fixes dual-screen position                         Most browsers      Firefox
  var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
  var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
  var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
  var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
  var left = ((width / 2) - (w / 2)) + dualScreenLeft;
  var top = ((height / 2) - (h / 2)) + dualScreenTop;
  var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
  popupPage_RLI = window.open("checkid", "libPage", opciones);
  var timer = setInterval(function() { 
    if(popupPage_RLI.closed) {
      clearInterval(timer);
      document.getElementById('cargando').style.display='none';
    }
  }, 1000);
  // Puts focus on the popupPage_RLI
  if (window.focus) {
    popupPage_RLI.focus();
  }
};
let getParamsCreate_RLI = function() {
  return {
    action: "CREATE",
    apiKey: general_RLI.apiKey,
    sessionId: general_RLI.session.id,
    companyId: general_RLI.session.companyId,
    operationId: 1,
    baseUrl: general_RLI.baseUrl,
    useFingerprint: true,//si pide huella al que esta enrolando
    usePin: true,//si pide pin al que esta enrolando
    useKBA: false,//si se utiliza la pregunta de seguridad
    operator: {
      sessionId: general_RLI.operator.sessionId,
      identityDocument: {
        countryCode: general_RLI.countryCode,
        type: general_RLI.type,
        personalNumber: general_RLI.rutoperador 
      }
    },
    digitalIdentity: {
      personalData: {
        givenNames: "desmond",
        surnames: "miles",
        dob: 20000101,
        gender: "NOT_KNOWN"
      },
      emailAddresses: [
        {
          type: 'BUSINESS',
          address: 'algo@algo.cl',
          primary: true
        },
        {
          type: 'PERSONAL',
          address: 'algo@algo2.cl',
          primary: false
        }
      ],
      contactPhones: [
        {
          number: '+56982365612',
          primary: false,
          type: 'HOME'
        },
        {
          number: '+56911111111',
          primary: true,
          type: 'PERSONAL'
        }
      ],
      identityDocuments: [{
        countryCode: general_RLI.countryCode,
        type: general_RLI.type,
        personalNumber: general_RLI.rutaenrolar 
      }]
    }
  };
};
window.getParams = function()
{
  return getParamsCreate_RLI();
}
//respuesta_RLI del formulario checkid
window.callback = function(result) 
{
  popupPage_RLI.close();
  document.getElementById('cargando').style.display='none';
  if (result.numError == 0) 
  {
    //alert ("OK")
  }
  else
  {
    alert (result.numError +  " " + result.msError);
  }
  return false;
}
function enrolar_RLI()
{
  consulta_sesion_RLI();
}
var conexion_RLI;

function consulta_sesion_RLI()
{
  conexion_RLI=crearXMLHttpRequest();
  conexion_RLI.open('POST', './consulta_sesion.php', false);
  conexion_RLI.send(null);
  // Devolvemos el resultado
  respuesta_RLI =  conexion_RLI.responseText;   
  arr_resp_RLI  = respuesta_RLI.split('|');
  if (arr_resp_RLI[0] == 'ok')
  {
    general_RLI.baseUrl       = arr_resp_RLI[1];
    general_RLI.session.companyId   = arr_resp_RLI[2];
    general_RLI.username      = arr_resp_RLI[4];
    general_RLI.session.id      = arr_resp_RLI[6];
    general_RLI.countryCode     = arr_resp_RLI[7];
    general_RLI.type        = arr_resp_RLI[8];
    general_RLI.apiKey        = arr_resp_RLI[9];

    var rut           = document.getElementById('usuarioid_RLI').value; 
    general_RLI.rutoperador     = rut.replace ("-","");

    var rut           = document.getElementById('rut').value; 
    general_RLI.rutaenrolar     = rut.replace ("-","");
    openCheckId_RLI("CREATE");
  }
  else
  {
    alert (respuesta_RLI);
  }
}

/*templates\loadUserFormulario.html*/



$('.soloUno_LOF').click(function()
{
    $('#GENERAR').attr('disabled', true);
    if ($(".soloUno_LOF").is(':checked'))
    {
        $('#sin_planilla').hide();
        $('#con_planilla').show();
        $('.newusuarioid_LOF').val('');
        //$(".idFormulario_LOF").prop("selectedIndex", 0).val(); 
    }
    else
    {
        $('#con_planilla').hide();
        $('#sin_planilla').show();
        $("#Documento").val('');
        $(".archivo_LOF").val('');
    }
});

$('.idFormulario_LOF').on('change', function(){
    ejecutaCambioDeFormulario_LOF();
});
function ejecutaCambioDeFormulario_LOF(){
    if ($('.idFormulario_LOF').val() != 0){
        $("#VER").attr('disabled', false);
    }
    else {
        $("#VER").attr('disabled', true);
    }
    $('.soloUno_LOF').prop('checked', true);
    $('#sin_planilla').hide();
    $('#con_planilla').show();
    $('.newusuarioid_LOF').val();
    //$('.soloUno_LOF').trigger('click');
    //$('.soloUno_LOF').click();
    var IdArchivo = $('.idFormulario_LOF').find(':selected').data('idarchivo');
    var idFormulario = $('.idFormulario_LOF').find(':selected').val();
    var RutEmpresa = $('.RutEmpresa_LOF').find(':selected').val();
    $('#IdArchivo').val(IdArchivo);
    $('.idFormulario_LOF_r').val(idFormulario);
    $('#RutEmpresa_r').val(RutEmpresa);
    switch(IdArchivo)
    {
        case 1: // Se permite la carga unitaria
        {
            $('.soloUno_LOFFull').show();
            break;
        }
        case 2: // Utiliza solo carga masiva
        case 3: // Utiliza solo carga masiva
        case 4: // Utiliza solo carga masiva
        {
            $('.soloUno_LOFFull').hide();
            break;
        }
    }
    resetMemoryCount_LOF();
    $("#Documento").val('');
    $(".archivo_LOF").val('');
    habilitaBoton_LOF();
    getEstadoSubida_LOF();
    $(".fila").remove();
    if ($('.idFormulario_LOF').find(':selected').data('numerorepresentantes') > 0)
    {
        $('.muestraFirmantes').show();
        getFirmantes_LOF($('.RutEmpresa_LOF').val());
    }
    else{
        $('.muestraFirmantes').hide();
    }
    //alert($(this).find(':selected').data('idarchivo'));
}
function getFirmantes_LOF(RutEmpresa)
{
    var url  = "Generar_Documentos_Masivos2_ajax.php";
    var parametros = "RutEmpresa=" + RutEmpresa;

    if( window.XMLHttpRequest )
        ajaxFirmantes_LOF = new XMLHttpRequest(); // No Internet Explorer
    else
        ajaxFirmantes_LOF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajaxFirmantes_LOF.onreadystatechange = funcionCallback_getFirmantes_LOF;
    // Enviamos la peticion
    ajaxFirmantes_LOF.open( "POST", url, true );
    ajaxFirmantes_LOF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajaxFirmantes_LOF.send(parametros);
}

function seleccion_LOF(id){
    //console.log(id);
    var num = $('.f_emp:checked').length;//$('input[type=checkbox]:checked').length;
    console.log(num);
    var cantidad_firmantes = $('.idFormulario_LOF').find(':selected').data('numerorepresentantes');//$("#Cantidad_Firmantes").val(); 
    //var representantes = $('.idFormulario_LOF').find(':selected').data('numerorepresentantes');
    //console.log(representantes);
    //console.log(cantidad_firmantes);
   if ( cantidad_firmantes == '' ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar un formulario" );
        $("#idPlantilla").focus();
        habilitaBoton_LOF();//$("#GENERAR").attr('disabled', true);
        return false;
    }
    if ( num < cantidad_firmantes ){
        habilitaBoton_LOF();//$("#GENERAR").attr('disabled',true);
    }
    if ( num > cantidad_firmantes ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar solo " + cantidad_firmantes + " Firmante, seg&uacute;n el flujo de firma del Formulario seleccionado" );
        habilitaBoton_LOF();//$("#GENERAR").attr('disabled', true);
    }
    if( num == cantidad_firmantes ){
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        habilitaBoton_LOF();//$("#GENERAR").attr('disabled', false);
    }
    if( $("#orden_" + id ).val() == '' ){ //Seleccionado
        if ( cantidad_firmantes > 1 ){
            $("#orden_" + id).val(num);
        }
    }else{//Deseleccionado
        if ( cantidad_firmantes > 1 ){
            if( confirm(' Desea reiniciar el orden de los firmantes ?')){
                $(".orden").val('');
                $(".f_emp").prop('checked',false);
            }else{	
                $("#orden_" + id).val('');
                //Colocar orden 
                var rutfirmante = $("#emp_" + id).val();
                convertirArreglo_emp_LOF();
                nuevo_emp = convertirItem_LOF(arreglo_emp, orden_emp);
                var items = nuevo_emp;
                items.sort(function (a, b) {
                    if (a.orden > b.orden) {
                        return 1;
                    }
                    if (a.orden < b.orden) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                });
                reasignarOrden_LOF(items,'');
            }
        }
    }
}

/****************************/
/**OPERACIONES DE SELECCION**/
/****************************/

function reasignarOrden_LOF(items,emp){
    for ( i = 0; i < items.length ; i++ ){
        var rut = $("#" + items[i].id).val(); 
        if( emp != '' )
            $("#orden_" + emp + rut).val(i+1);
        else
            $("#orden_" + rut).val(i+1);
    }
}



function convertirArreglo_emp_LOF(){
    arreglo_emp = [];
    orden_emp = [];
    var rut = '';
    var orden_id = '';
    $(".f_emp").each(function (index) {  
        if( $(this).is(':checked') ){
            rut = this.id;
            orden_id = $("#orden_" + $("#" + this.id).val()).val();
            arreglo_emp.push(this.id);
            orden_emp.push(orden_id);
        }
    });
}

function convertirItem_LOF(arreglo1,arreglo2){
    var data = new Object();
    var array = new Array();
    $.each(arreglo1, function (ind, elem){ 
        var data = new Object();
        data.id = elem;
        data.orden = arreglo2[ind];
        array[ind] = data;
    }); 	
    return array;
}

function funcionCallback_getFirmantes_LOF()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajaxFirmantes_LOF.readyState == 4 )
    {
        // Comprobamos si la respuesta_LOF ha sido correcta (resultado HTTP 200)
        if( ajaxFirmantes_LOF.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_LOF.innerHTML = "<b>"+ajaxFirmantes_LOF.responseText+"</b>"; 
            var salida_LOF = ajaxFirmantes_LOF.responseText;
            if( salida_LOF != '' ){ 
                var datos = JSON.parse(salida_LOF);
                var x = "";
                $.each(datos,function(key, registro) {
                    x = "onclick=\"seleccion_LOF(\'" + registro.personaid + "\');\"";
                    $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_"+ registro.personaid +"' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");                                 
                });
                /*$('.idFormulario_LOF').append('<option value="0">(Seleccione)</option>');
                $.each(datos,function(key, registro) {
                    //console.log(key, registro);
                    $('.idFormulario_LOF').append('<option data-numerorepresentantes="' + registro.NumeroRepresentantes + '" data-idarchivo="' + registro.IdArchivo + '" value="' + registro.idFormulario + '" >' + registro.nombreFormulario + '</option>');
                });
                $('.idFormulario_LOF').attr('disabled', false);*/
            }else{
                //$('.idFormulario_LOF').attr('disabled', true);
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La empresa seleccionada no posee Firmantes.");
            }
        }
    }
}


function resetMemoryCount_LOF()
{
    memoryCount_LOF = 0;
}




function InicioLoasUserFormulario(){
    if ($('#progreso').attr('max') != '')
    {
        estadoGeneracionMasiva_LOF();
    }
    if ($('.RutEmpresa_LOF').val() != 0) {
        //alert($('.RutEmpresa_LOF').val());
        cargaComboFormularios_LOF();
    }
};

function ver_LOF()
{
    document.getElementById("resultado").submit();
}


function estadoGeneracionMasiva_LOF()
{
    $('#estado').show();
    consultaEstado_LOF();
    consulta_LOF = setInterval(function(){ consultaEstado_LOF() }, 10000);
}

function consultaEstado_LOF()
{
    conexion_LOF=crearXMLHttpRequest();
    conexion_LOF.open('POST', 'loadUserFormularioProcessEstado.php?accion=ESTADO&IdArchivo='+$('#IdArchivo').val() ,false);
    conexion_LOF.send(null);
    try
    {
        respuesta_LOF = JSON.parse(conexion_LOF.responseText);
    }
    catch (e)
    {
        respuesta_LOF.actual = 0;
    }
    respuesta_LOF.actual = respuesta_LOF.actual == null ? 0 : respuesta_LOF.actual;
    $('#progreso').val(respuesta_LOF.actual);
    $('#estadodetalle').html(respuesta_LOF.actual + " filas procesadas de un total de " +  ($('#progreso').attr('max')) + " filas.");
    if (controlMemory_LOF(respuesta_LOF.actual))
    {
        if (respuesta_LOF.actual == $('#progreso').attr('max'))
        {
            clearInterval(consulta_LOF);
            $('#estado').hide();
            alert('El proceso de generacion masiva de documentos ha finalizado.');
        }
    }
    else
    {
        matarProceso_LOF();
        clearInterval(consulta_LOF);
        $('#estado').hide();
        alert('Ha ocurrio un error, revise detalle y ejecute nuevamene las filas no procesadas.');
    }
}

function matarProceso_LOF()
{
    conexion_LOF=crearXMLHttpRequest();
    conexion_LOF.open('POST', 'loadUserFormularioProcess.php?accion=KILL&IdArchivo='+$('#IdArchivo').val(),false);
    conexion_LOF.send(null);
    memoryCount_LOF = 0;
}

function controlMemory_LOF(dato)
{
    var respuesta_LOF = true;
    if (memory_LOF == dato)
    {
     memoryCount_LOF++;
    }
    else
    {
        memoryCount_LOF = 0;
    }
    if (memoryCount_LOF >= memoryTop_LOF)
    {
        respuesta_LOF = false;
    }
    memory_LOF = dato;
    console.log(dato, memory_LOF, memoryCount_LOF, memoryTop_LOF, respuesta_LOF);
    return respuesta_LOF;
}

$(".newusuarioid_LOF").change(function(){
    $("#Documento").val('');
    $(".archivo_LOF").val('');
    var respuesta_LOF = validaRut2(document.formulario.newusuarioid);
    habilitaBoton_LOF();
});



function getEstadoSubida_LOF()
{
    //parametros = '?accion=UNO' + '&IdArchivo=2&rut=' + $('.newusuarioid_LOF').val() + '&idFormulario=' + $('.idFormulario_LOF').val() + '&RutEmpresa=' + $('.RutEmpresa_LOF').val() + '&idCargoEmpleado=' + $('#idCargoEmpleado').val();
    parametros = '?accion=ESTADO' + '&IdArchivo='+$('#IdArchivo').val();
    var url  = "loadUserFormularioProcess.php";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LOF = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LOF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LOF.onreadystatechange = funcionCallback_getEstadoSubida;
    // Enviamos la peticion
    ajax_LOF.open( "POST", url, true );
    ajax_LOF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LOF.send(parametros);
}

function funcionCallback_getEstadoSubida_LOF()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LOF.readyState == 4 )
    {
        // Comprobamos si la respuesta_LOF ha sido correcta (resultado HTTP 200)
        if( ajax_LOF.status == 200 )
        {
            var salida_LOF = ajax_LOF.responseText;
            respuesta_LOF = JSON.parse(salida_LOF);
            $('#mensajeOK').removeClass();
            clearInterval(consulta_LOF);
            if (respuesta_LOF.highestRow)
            {
                $('#progreso').attr('max', respuesta_LOF.highestRow);
                estadoGeneracionMasiva_LOF();
            }
            else
            {
                $('#estado').hide();
            }
        }
    }
}

function habilitaBoton_LOF()
{
    //if( ($("#Documento").val() != '' || ($('.newusuarioid_LOF').val() != '' && $('.idFormulario_LOF').val() != 0 )) && ($(".RutEmpresa_LOF").val() != 0 && $("#idCargoEmpleado").val() != 0) )
    //if( ($("#Documento").val() != '' || ($('.newusuarioid_LOF').val() != '' && $('.idFormulario_LOF').val() != 0 )) )
    //if( ($("#Documento").val() != '' || $('.newusuarioid_LOF').val() != '') && ($('.idFormulario_LOF').val() != 0 && $('.RutEmpresa_LOF').val() != 0 && $('#lugarpagoid').val() != 0 && $('#centrocostoid').val() != 0 && $('.f_emp:checked').length == $('.idFormulario_LOF').find(':selected').data('numerorepresentantes') )  )//&& $('#idTipoFirma').val() != 0)  )
    if( ($("#Documento").val() != '' || $('.newusuarioid_LOF').val() != '') && ($('.idFormulario_LOF').val() != 0 && $('.RutEmpresa_LOF').val() != 0 && $('.f_emp:checked').length == $('.idFormulario_LOF').find(':selected').data('numerorepresentantes') )  )//&& $('#idTipoFirma').val() != 0)  )
    {
        $("#GENERAR").attr('disabled', false);
    }
    else
    {
        $("#GENERAR").attr('disabled', true);
    }
}

$(".archivo_LOF").change(function()
{
    $("#Documento").val($(".archivo_LOF").val());
    $('.newusuarioid_LOF').val('');
    //$(".idFormulario_LOF").prop("selectedIndex", 0).val(); 
    habilitaBoton_LOF();
});

function subirArchivo_LOF()
{
    if( $("#Documento").val() != '')
    {
        //revisa si hay algun cambio en el archivo
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        MostrarCargando();
        if (validar_LOF() == true)
        {
            upload_input = document.querySelectorAll('.archivo_LOF')[0];
            uploadFile_LOF( upload_input.files[0] );
        }
    }
    else if ($('.newusuarioid_LOF').val() != '')
    {
        procesarUno_LOF();
    }
}

function validar_LOF()
{
    /*if ( $(".RutEmpresa_LOF").val() == 0 ){
        $(".RutEmpresa_LOF").focus();
        return false;
    }
    if ( $("#idTipoDoc").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de documento");
        $("#idTipoDoc").focus();
        return false;
    }
    if( $("#idProceso").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione un Proceso");
        $("#idProceso").focus();
        return false;
    }
    if( $("#idPlantilla").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione la plantilla");
        $("#idPlantilla").focus();
        return false;
    }
    if( $("#idFirma").val() == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el tipo de firma del documento");
        $("#idFirma").focus();
        return false;
    }
    if( $("#Documento").val().length == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Seleccione el archivo de carga que necesita");
        $("#Documento").focus();
        return false;
    }*/
    return true;
}

function uploadFile_LOF(file)
{
    $("#GENERAR").attr('disabled', true);
    importar_LOF(file,$('#IdArchivo').val()); //IdArchivo
}

function importar_LOF(file,IdArchivo)
{
    var xhr =  createXMLHttp();
    xhr.upload.addEventListener('loadstart',function(e)
    {
        document.getElementById('mensaje').innerHTML = 'Cargando archivo...';
    }, false);
    xhr.upload.addEventListener('load',function(e)
    {
        document.getElementById('mensaje').innerHTML = '';
    }, false);
    xhr.upload.addEventListener('error',function(e)
    {
        alert('Ha habido un error :/');
    }, false);
    var datos = $('#formulario').serialize();
    var url  = 'loadUserFormularioProcess.php?accion=LOAD&datos=' + datos + '&IdArchivo='+ IdArchivo;
    //var url  = 'loadResultadoPostulacion.php?accion=LOAD&IdArchivo='+ IdArchivo + '&RutEmpresa=' + rutempresa + '&datos=' + datos;
    xhr.open('POST',url,false);//se le agrego false para que sea sincrono, para que espere antes de comenzar a cargar el otro archivo.
    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);
    xhr.addEventListener('readystatechange', function(e) 
    {
        if( this.readyState == 4 ) 
        {
            try
            {
                respuesta_LOF = JSON.parse(xhr.responseText);
                //if(IdArchivo == 2 )
                if(IdArchivo == $('#IdArchivo').val() )
                {
                    $('#progreso').attr('max', respuesta_LOF.highestRow);
                    procesar_LOF(IdArchivo);
                    estadoGeneracionMasiva_LOF();
                }
            }
            catch (e)
            {
                respuesta_LOF = xhr.responseText;
                var elementoError   = document.getElementById("mensajeError");
                elementoError.innerHTML = respuesta_LOF;
                elementoError.className += "callout callout-danger";
            }
        }
        //if( IdArchivo == 2 )
        if( IdArchivo == $('#IdArchivo').val() )
        {
            document.getElementById("Documento").value = "";
            document.getElementById("archivo").value = "";
            OcultarCargando();
        } 
    });
    xhr.send(file);
}


function procesarUno_LOF()
{
    //parametros = '?accion=UNO' + '&IdArchivo=2&rut=' + $('.newusuarioid_LOF').val() + '&idFormulario=' + $('.idFormulario_LOF').val() + '&RutEmpresa=' + $('.RutEmpresa_LOF').val() + '&idCargoEmpleado=' + $('#idCargoEmpleado').val();
    var parametros = 'accion=UNO' + '&IdArchivo='+$('#IdArchivo').val()+'&rut=' + $('.newusuarioid_LOF').val() + '&idFormulario=' + $('.idFormulario_LOF').val();
    var url  = "loadUserFormularioProcess.php";
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_LOF = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_LOF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_LOF.onreadystatechange = funcionCallback_soloUNO_LOF;
    // Enviamos la peticion
    ajax_LOF.open( "POST", url, true );
    ajax_LOF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_LOF.send(parametros);
}

function funcionCallback_soloUNO_LOF()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LOF.readyState == 4 )
    {
        // Comprobamos si la respuesta_LOF ha sido correcta (resultado HTTP 200)
        if( ajax_LOF.status == 200 )
        {
            var salida_LOF = ajax_LOF.responseText;
            respuesta_LOF = JSON.parse(salida_LOF);
            $('#mensajeOK').removeClass();
            if (respuesta_LOF.exito)
            {
                alert(respuesta_LOF.mensaje);
            }
        }
    }
}

function procesar_LOF(IdArchivo)
{
    conexion_LOF=crearXMLHttpRequest();
    var firmantes = $('.f_emp').serialize();
    var ordenFirmantes = $('.orden').serialize();
    //console.log(firmantes);
    //parametros = '?accion=LOOP0' + '&IdArchivo='+ IdArchivo + '&RutEmpresa=' + $('.RutEmpresa_LOF').val() + '&idCargoEmpleado=' + $('#idCargoEmpleado').val();
    //parametros = '?accion=LOOP0' + '&IdArchivo='+ IdArchivo + '&idFormulario=' + $('.idFormulario_LOF').val() + '&RutEmpresa=' + $('.RutEmpresa_LOF').val() + '&LugarPagoid=' + $('#lugarpagoid').val() + '&idCentroCosto=' + $('#centrocostoid').val() + '&' + firmantes + '&' + ordenFirmantes;
    parametros = '?accion=LOOP0' + '&IdArchivo='+ IdArchivo + '&idFormulario=' + $('.idFormulario_LOF').val() + '&RutEmpresa=' + $('.RutEmpresa_LOF').val() + '&' + firmantes + '&' + ordenFirmantes;
    conexion_LOF.open('POST', 'loadUserFormularioProcess.php' + parametros);
    conexion_LOF.send(null);
    respuesta_LOF =  conexion_LOF.responseText;	
}


$(".RutEmpresa_LOF").change(function(){
    cargaComboFormularios_LOF();
});
    
function cargaComboFormularios_LOF()
{
    $("#VER").attr('disabled', true);
    var RutEmpresa = $(".RutEmpresa_LOF").val();
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
    habilitaBoton_LOF();
    $('.idFormulario_LOF').empty();
   
    //Limpiar tabla 
    $(".fila").remove();
    //Ocultar firmantes 
    /*$("#icono_representante").hide();
    $("#label").hide();
    $("#step-1").collapse('hide');*/

    if( RutEmpresa != 0 ){
        /*$("#idTipoDoc").attr("disabled",false);
        $("#idTipoDoc").val(0);*/
        //var url  = "Generar_Documentos_Masivos2_ajax.php?RutEmpresa=" + RutEmpresa;
        var url  = "formularioPlantilla_ajax.php";
        var parametros = "RutEmpresa=" + RutEmpresa;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_LOF = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_LOF = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_LOF.onreadystatechange = funcionCallback_Empresa_LOF;
        // Enviamos la peticion
        ajax_LOF.open( "POST", url, true );
        ajax_LOF.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_LOF.send(parametros);
    }
}
function funcionCallback_Empresa_LOF()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_LOF.readyState == 4 )
    {
        // Comprobamos si la respuesta_LOF ha sido correcta (resultado HTTP 200)
        if( ajax_LOF.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_LOF.innerHTML = "<b>"+ajax_LOF.responseText+"</b>"; 
            salida_LOF = ajax_LOF.responseText;
            if( salida_LOF != '' ){ 
                var datos = JSON.parse(salida_LOF);
                var x = "";
                /*$.each(datos,function(key, registro) {
                    x = "onclick=\"seleccion_LOF(\'" + registro.personaid + "\');\"";
                    $("#tabla_emp tr:last ").after("<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_"+ registro.personaid +"' " + x + "/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td><input name='orden_" + registro.personaid + "' id='orden_" + registro.personaid + "' class='orden' style='text-align:center; border: none;' /></td></tr>");                                 
                }); */
                $('.idFormulario_LOF').append('<option value="0">(Seleccione)</option>');
                $.each(datos,function(key, registro) {
                    //console.log(key, registro);
                    $('.idFormulario_LOF').append('<option data-numerorepresentantes="' + registro.NumeroRepresentantes + '" data-idarchivo="' + registro.IdArchivo + '" value="' + registro.idFormulario + '" >' + registro.nombreFormulario + '</option>');
                });
                $('.idFormulario_LOF').attr('disabled', false);
                if ($('.idFormulario_LOF_r').val() != ''){
                    //alert($('.idFormulario_LOF_r').val());
                    $(".idFormulario_LOF option[value='" + $('.idFormulario_LOF_r').val() + "']").prop('selected',true);
                    ejecutaCambioDeFormulario_LOF();
                }
            }else{
                $('.idFormulario_LOF').attr('disabled', true);
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La empresa seleccionada no posee Formularios.");
            }
        }
    }
}


/*templates\plantillas_FormularioAgregarClausulas.html*/

function InicioPlantillasAgregarClausulas() {
    var row = $("#example2 tr").length;
    var i = 0;

    for (i = 0; i < row; i++) {
        //Si esta pendiente por aprobacion
        if ($("#aprob_" + i).val() == 0) {
            //Inhabilitar el boton de agregar
            $("#boton_" + i).prop('disabled', true);
            $("#fila_" + i).css("background-color", "#f4f4f4");
        }
    }

};

//Hace el llamado a funcion ajax_PAC externo
function funcionCallback_PAC() {
    // Comprobamos si la peticion se ha completado (estado 4)
    if (ajax_PAC.readyState == 4) {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if (ajax_PAC.status == 200) {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.getElementById("#divtabla").innerHTML = ajax_PAC.responseText; 
            if (ajax_PAC.responseText == 0) {
                //Correcto
            }
            else if (aja.responseText == 1) {
                //Error inesperado 
            }
        }
    }
}
//Envia Datos del Formulario para agregar
function enviar_PFAC() {

    var cat = $("#idCategoria").val();
    var pla = $("#idPlantilla").val();
    var cla = $("#idClausula_" + i).val();

    var url = "";
    var enc = "";
    var tit = "";

    if ($('#Encabezado_OC').is(':checked')) {
        enc = "1";
    }
    else {
        enc = "0";
    }
    if ($('#Titulo_OC').is(':checked')) {
        tit = "1";
    }
    else {
        tit = "0";
    }
    //Reasignar valores de los campos a la variable
    url = "Plantillas_ajax2.php";
    var parametros = "idCategoria=" + cat + "&idPlantilla=" + pla + "&idClausula=" + cla + "&accion=AGREGAR&accion3=AGREGAR_CLAUSULA &Encabezado=" + enc + "&Titulo=" + tit;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if (window.XMLHttpRequest)
        ajax_PAC = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_PAC = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

    ajax_PAC.onreadystatechange = funcionCallback_PAC;
    // Enviamos la peticion
    ajax_PAC.open("POST", url, true);
    ajax_PAC.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_PAC.send(parametros);

    //Ocultar el modal automaticamente 
    $('#OC').modal('hide');

    // Reiniciar campos de checkbox
    $('#Titulo_OC').prop("checked", false);
    $('#Encabezado_OC').prop("checked", false);

    //Reiniciamos la pagina
    var nuevo = "#fila_" + i;

    $(nuevo).remove();
}

//Almacena la fila en la que esta y muestra el Modal 
function mostrar_PFAC(fila) {
    i = fila;
    $("#OC").modal('show');
}

/*templates\plantillas_FormularioAprobar.html*/
function InicioPlantillasFormularioAprobar(){

    if( $("#mensajeAd").html().length > 0 ){
        $("#mensajeAd").addClass("callout callout-warning");
        $("#GENERAR").attr("disabled", true);
    }
    else{
        $("#mensajeAd").removeClass("callout callout-warning");
        $("#GENERAR").attr("disabled", false);
    }
    if( $("#mensajeOK").html().length > 1 ){
      $(".APROBAR_PFA").attr("disabled",true);
    }

  };

  $(".APROBAR_PFA").click(function(){

    //Consultar si esta Plantilla existe para mas de una empresa 
    var idPlantilla = $("#idPlantilla").val();
    var url = "Plantillas_ajax3.php";
    var parametros = "idPlantilla=" + idPlantilla;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
      ajax_PFA = new XMLHttpRequest(); // No Internet Explorer
    else
      ajax_PFA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_PFA.onreadystatechange = funcionCallback;

    // Enviamos la peticion
    ajax_PFA.open( "POST", url, true );
    ajax_PFA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_PFA.send(parametros);
  });

 
   
  function funcionCallback()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_PFA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_PFA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_PFA = ajax_PFA.responseText;

        if( salida_PFA == '1' ){
          var respuesta = confirm("Esta Plantilla esta asociada a m�s de una Empresa, desea continuar ?");

          if( respuesta ){
            $("#formulario").submit();
          }
        }else{
           $("#formulario").submit();
        }
      }
    }
  }

  /*templates\plantillas_FormularioAprobarBlo.html*/

  function InicioPlantillasAprobarBlo(){

    if( $("#mensajeAd").html().length > 0 ){
        $("#mensajeAd").addClass("callout callout-warning");
        $("#GENERAR").attr("disabled", true);
    }
    else{
        $("#mensajeAd").removeClass("callout callout-warning");
        $("#GENERAR").attr("disabled", false);
    }
    
  };

  /*templates\plantillas_FormularioModificar.html*/

  function InicioPlantilasModificar(){
    var filas = $("#table tr").length;
    if ( filas == 1 ){
      $("#GENERAR").prop('disabled', true);
      $("#GENERAR").attr('title', 'La Plantilla no tiene Clausulas asociadas');
    }

    if( $("#mensajeAd").html().length > 0 ){
        $("#mensajeAd").addClass("callout callout-warning");
    }
    else{
        $("#mensajeAd").removeClass("callout callout-warning");
    }
  };

 $(".MODIFICAR_PMod").click(function(){

    //Consultar si esta Plantilla existe para mas de una empresa 
    var idPlantilla = $("#idPlantilla").val();
    var url = "Plantillas_ajax3.php";
    var parametros = "idPlantilla=" + idPlantilla;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
      ajax_PMod = new XMLHttpRequest(); // No Internet Explorer
    else
      ajax_PMod = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_PMod.onreadystatechange = funcionCallback_PMod;

    // Enviamos la peticion
    ajax_PMod.open( "POST", url, true );
    ajax_PMod.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_PMod.send( parametros );
  });

 
   
  function funcionCallback_PMod()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_PMod.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_PMod.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_PMod = ajax_PMod.responseText;

        if( salida_PMod == '1'){
          var respuesta = confirm("Esta Plantilla esta asociada a m&aacute;s de una Empresa, desea continuar ?");

          if( respuesta ){
            $("#formulario").submit();
          }
        }else{
           $("#formulario").submit();
        }
      }
    }
  }

  /*templates\plantillas_FormularioModificarClausulas.html*/

  //Script para asignar iconos a un div y bloqueaar boton
 function InicioPlantillasModificarClausulas(){
    //Variables
    var aprobado = "";
    var icono = "";
    var fila = "";
    var total = example3.rows.length;

    //Valor del DIV en estado pendiente
    var pendiente  = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:orange;" title="Pendiente por aprobacion" alt="Pendiente por aprobacion"></i>';
    //Valor del DIV en estado Aprobado 
    var listo = '<i class="fa fa-check DisBtn" aria-hidden="true" style="color:green;" title="Registro Aprobado" alt="Registro Aprobado"></i>';

   for (var i=0; i < total; i++){

      aprobado = "Aprobado_" + i;
      icono = document.getElementById(aprobado);

      if( icono.innerHTML == 0 ){
        //Agregar icono
        document.getElementById("Aprobado_" + i).innerHTML = pendiente;
      }
      if( icono.innerHTML == 1 ){
        //Agregar icono
        document.getElementById("Aprobado_" + i).innerHTML = listo;
      }
    }
  }
  //Reasigna valores de campos 
  function envia_PModC(){ 
   document.getElementById('RutEmpresa').value=document.getElementById('Empresa').value;
  }
  //Reasignas valores de campos 
  function envia2_PModC(){ 
   document.getElementById('idCategoria').value=document.getElementById('Categoria').value;
  }
  //Reasignas valores de campos 
  function envia4_PModC(){ 
   document.getElementById('idWF').value=document.getElementById('Flujo').value;
  }
  //Reasignas valores de campos 
  function envia3_PModC(){ 
   document.getElementById('idTipoDoc').value=document.getElementById('TipoDeDocumento').value;
  }

  /*templates\registroDec_FormularioAgregar.html*/

   //Validar los tipo Number 
   $(".AGREGAR_RDEC").click(function(){
    //Campos vacios
  if($("#serial").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca serial del documento");
      $('#serial').focus();
      return false;
  }
  if($("#nombre").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca nombre completo");
      $('#nombre').focus();
      return false;
  }
  if($("#appaterno").val().length == 0){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca apellido paterno");
      $('#appaterno').focus();
      return false;
  }
  if ($("#fechaNac").val().length == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca 	 de nacimiento");
      $('#fechaNac').focus();
      return false;
  }
  if ($("#correo1").val().length == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca un correo de electr&oacute;nico v&aacute;lido");
      $('#correo1').focus();
      return false;
  }
  if ($("#correo2").val().length == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Confirme el correo de electr&oacute;nico introducido");
      $('#correo2').focus();
      return false;
  }
  //Si la confirmacion de correo es valida
  if( $("#correo1").val() === $("#correo2").val() ){
      $("#correo").val($("#correo1").val());
  }
  else{
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Verifique correo electr&oacute;nico introducido");
      $('#correo2').focus();
      return false;
  }
   if ($("#genero").val() == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Seleccione genero");
      $('#genero').focus();
      return false;
  }
  if ($("#fono").val() == 0 ){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Introduzca fono con el formato sugerido");
      $('#fono').focus();
      return false;
  }
  //Si tiene menos numeros de lo que debe 
  if (($("#fono").val().length > 1 ) && ($("#fono").val().length < 12)){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Debe introducir el fono completo con codigo de area iniciando con + . Ejemplo: +569 ");
      $('#fono').focus();
      return false;
  }
  //Si no tiene el +
  if ($("#fono").val().charAt(0) != '+'){
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("Debe introducir el fono con codigo de area iniciando con + . Ejemplo: +569 ");
      $('#fono').focus();
      return false;
  }
  
  
  //valida pin
  if ($("#pin").val() != $("#confipin").val() )
  {
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("El Pin ingresado es distinto a la confirmaci&oacute;n del Pin");
      document.getElementById("pin").value = "";
      document.getElementById("confipin").value = "";		
      $('#pin').focus();
      return false;
  }

  var clavepin = document.getElementById("pin").value;
  var largopin = clavepin.length;
  if (largopin < 6 || largopin > 12)
  {
      $("#mensajeError").addClass("callout callout-warning");
      $("#mensajeError").html("La clave para firmar sus documentos debe ser  de un m&iacute;nimo de 6 caracteres y m&aacute;ximo de 12 caracteres");
      document.getElementById("pin").value = "";
      document.getElementById("confipin").value = "";		
      $('#pin').focus();
      return false;
  }
  
  MostrarCargando();
});

/*templates\plantillas_Listado_Clasificado.html*/

 
function funcionCallback_PLC()
{
  // Comprobamos si la peticion se ha completado (estado 4)
  if( ajax_PLC.readyState == 4 )
  {
    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
    if( ajax_PLC.status == 200 )
    {
      // Escribimos el resultado en la pagina HTML mediante DHTML
      //document.all.salida_PLC.innerHTML = "<b>"+ajax_PLC.responseText+"</b>"; 
      salida_PLC = ajax_PLC.responseText;
      respuesta = salida_PLC.split("|");
      document.getElementById("ejemplo-titulo").innerHTML = respuesta[0];
      document.getElementById("ejemplo-cuerpo").innerHTML = respuesta[1];
      $("#ejemplo").modal({show:true});
    }
  }
}
 
function vistaprevia_PLC(fila)
{
  var vistaprevia = "idPlantilla_" + fila; 
  var valor = document.getElementById(vistaprevia).value; 
  var url = "Plantillas_ajax.php";
  var parametros = "idPlantilla=" + valor;

  // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
  if( window.XMLHttpRequest )
    ajax_PLC = new XMLHttpRequest(); // No Internet Explorer
  else
    ajax_PLC = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
 
  // Almacenamos en el control al funcion que se invocara cuando la peticion
  // cambie de estado 
  ajax_PLC.onreadystatechange = funcionCallback_PLC;

  // Enviamos la peticion
  ajax_PLC.open( "POST", url, true );
  ajax_PLC.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax_PLC.send(parametros);
}

//Bloquear boton de aprobar si ya esta aprobado
function InicioPlantillasListadoClasificado(){

  var num = $("#example2 tr").length;
  var i = 0;
  
  for( i = 0; i < num ; i++ ){

     if($("#Aprob_" + i ).val() == 1 ){
        $("#APROBAR_" + i ).attr("disabled",true);
      }
  }
  

};

/*templates\PlantillasPorEmpresas_Listado.html*/
/*
function uploadFile_PPE(file) {

    var xhr = createXMLHttp();
    //xhr=new ActiveXObject("Microsoft.XMLHTTP");

    xhr.upload.addEventListener('loadstart', function (e) {
        document.getElementById('mensaje').innerHTML =
            'Cargando archivo...';
    }, false);

    xhr.upload.addEventListener('load', function (e) {
        document.getElementById('mensaje').innerHTML = '';
    }, false);

    xhr.upload.addEventListener('error', function (e) {
        alert('Ha habido un error :/');
    }, false);
    xhr.open('POST', 'importarExcel.php?IdArchivo=1');

    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);

    xhr.addEventListener('readystatechange', function (e) {
        if (this.readyState == 4) {

            respuesta = xhr.responseText;

            // document.getElementById("mensajeError").innerHTML = xhr.responseText;

            if (respuesta == "")//cuando sepa que fue bien sea vacio sea ok realiza el submit
            {

                document.getElementById("resultado").submit();
                //pasando en mensaje al formulario creado con nombre mensaje           
            }
            else {
                var elementoError = document.getElementById("mensajeError");
                elementoError.innerHTML = respuesta;
                elementoError.className += "callout callout-danger";

            }
        }

        document.getElementById("archivo").value = "";
    });
    xhr.send(file);
}

upload_input_PPE.onchange = function () {
    //alert('2');
    uploadFile_PPE(this.files[0]);
};*/

/*templates\plantillas_Listado.html*/

function funcionCallback_PLA()
{
  // Comprobamos si la peticion se ha completado (estado 4)
  if( ajax_PLA.readyState == 4 )
  {
    // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
    if( ajax_PLA.status == 200 )
    {
      // Escribimos el resultado en la pagina HTML mediante DHTML
      //document.all.salida_PLA.innerHTML = "<b>"+ajax_PLA.responseText+"</b>"; 
      salida_PLA = ajax_PLA.responseText;
      let respuesta = salida_PLA.split("|");
      document.getElementById("ejemplo-titulo").innerHTML = respuesta[0];
      document.getElementById("ejemplo-cuerpo").innerHTML = respuesta[1];
      $("#ejemplo").modal('show');
    }
  }
}

function vistaprevia_PLA(fila)
{
  var vistaprevia = "idPlantilla_" + fila; 
  var valor = document.getElementById(vistaprevia).value; 
  var url = "Plantillas_ajax.php";
  var parametros = "idPlantilla=" + valor;

  // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
  if( window.XMLHttpRequest )
    ajax_PLA = new XMLHttpRequest(); // No Internet Explorer
  else
    ajax_PLA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
 
  // Almacenamos en el control al funcion que se invocara cuando la peticion
  // cambie de estado 
  ajax_PLA.onreadystatechange = funcionCallback_PLA;

  // Enviamos la peticion
  ajax_PLA.open( "POST", url, true );
  ajax_PLA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax_PLA.send(parametros);
}

//Bloquear boton de aprobar si ya esta aprobado
function InicioPlantillasListado(){

  var num = $("#example2 tr").length;
  var i_PLA = 0;
  
  for( i_PLA = 0; i_PLA < num ; i_PLA++ ){

     if($("#Aprob_" + i ).val() == 1 ){
        $("#APROBAR_" + i ).attr("disabled",true);
      }
  }
};

 function consultar_PLA(fila){

    i_PLA = fila;
 
    //Consultar si esta Plantilla existe para mas de una empresa 
    var idPlantilla = $("#idPlantilla_" + fila).val(); 

    var url = "Plantillas_ajax3.php";
    var parametros = "idPlantilla=" + idPlantilla;

    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
      ajax_PLA = new XMLHttpRequest(); // No Internet Explorer
    else
      ajax_PLA = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
   
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_PLA.onreadystatechange = funcionCallback_consultar;

    // Enviamos la peticion
    ajax_PLA.open( "POST", url, true );
    ajax_PLA.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_PLA.send(parametros);
  }
  
  function funcionCallback_consultar()
  {
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_PLA.readyState == 4 )
    {
      // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
      if( ajax_PLA.status == 200 )
      {
        // Escribimos el resultado en la pagina HTML mediante DHTML
        salida_PLA = ajax_PLA.responseText;

        var respuesta;

        if( salida_PLA == '1' ){
          respuesta = confirm("Esta Plantilla esta asociada a mas de una Empresa, desea continuar ?");          
        }else{
          respuesta = confirm("Esta seguro que desea eliminar esta Plantilla ?");
        }

        if( respuesta ) $("#ELIMINAR_" + i ).click();
        else return respuesta;
      }
    }
  }

  /*templates\postulacion_formularioAgregar.html */
  
	function controlCheck_POSAgregar()
	{
		if (getCheck_POSAgregar())
		{
			$('#DiscapacidadObs').text('');
		}
		$('#DiscapacidadObs').attr('disabled', getCheck_POSAgregar());
		$('#unid').html((getCheck_POSAgregar() ? '&iquest;Cual?' : '&iquest;Cual? (*)'));
	}

	function getCheck_POSAgregar()
	{
		return ($('input:radio[name=discapacidad]:checked').val() == 'no');
	}

	$('.idCargoEmpleado_POSAgregar').change(function()
	{
		var etiqueta = $('.idCargoEmpleado_POSAgregar option:selected').text();
		if (etiqueta.substring(etiqueta.length - 8, etiqueta.length - 1) == 'cerrada')
		{
			//$('option[value="0"]').attr('selected',true);
			alert('Esta postulacion se encuentra cerrada');
			$(".idCargoEmpleado_POSAgregar").prop("selectedIndex", 0).val(); 
		}
	});

	$(".newusuarioid_POSAgregar").change(function(){
		//Limpiar los campos 
		$("#nombre").val('');
		$("#correo").val('');
		$('#telefono').val('');
		$("#Observacion").val('');
		var respuesta = validaRut2(document.formulario.newusuarioid);
		if( respuesta ){
			var RutUsuario = $(".newusuarioid_POSAgregar").val();
			var url  = "postulacion_ajax.php";
            var parametros = "personaid=" + RutUsuario;
			// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
			if( window.XMLHttpRequest )
				ajax = new XMLHttpRequest(); // No Internet Explorer
			else
				ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
			// Almacenamos en el control al funcion que se invocara cuando la peticion
			// cambie de estado 
			ajax.onreadystatechange = funcionCallback_rutusuario_POSAgregar;
			// Enviamos la peticion
			ajax.open( "POST", url, true );
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send(parametros);
		}
	});
	
	function funcionCallback_rutusuario_POSAgregar()
	{
		// Comprobamos si la peticion se ha completado (estado 4)
		if( ajax.readyState == 4 )
		{
			// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
			if( ajax.status == 200 )
			{
				// Escribimos el resultado en la pagina HTML mediante DHTML
				salida = ajax.responseText;
				datos = JSON.parse(salida);
				var cant = Object.keys(datos).length;
				if( cant > 0 ){ 
					console.log(datos);
					$("#nombre").val( datos.nombre );
					$("#email").val(datos.email);
					$('#telefono').val(datos.telefono);
					$("#Observacion").val(datos.Observacion);
					$("#DiscapacidadObs").val(datos.discapacidad);
					if (datos.discapacidad != '' && datos.discapacidad != null)
					{
						$("#discapacidadSI").attr('checked', true);
						$("#discapacidadNO").attr('checked', false);
						controlCheck_POSAgregar();
					}
					else
					{
						$("#discapacidadSI").attr('checked', false);
						$("#discapacidadNO").attr('checked', true);
						controlCheck_POSAgregar();
					}
				}
				else
				{
					$("#mensajeError").removeClass("callout callout-warning");
					$("#mensajeError").html("");
				}
			}
		}
	}
	
	
	$('.AGREGAR_POSAgregar').on('click', function(){
		var centrocostoid = $('#centrocostoid').children("option:selected").val();
		var RutEmpresa = $('#RutEmpresa').val();
		var disponibilidadid = $('#disponibilidadid').children("option:selected").val();
		var idCargoEmpleado = $('.idCargoEmpleado_POSAgregar').children("option:selected").val();
		var personaid = $('.newusuarioid_POSAgregar').val();

		if (centrocostoid == 0 || idCargoEmpleado == 0 || personaid == '' || disponibilidadid == 0 || $('#nombre').val() == '' || $('#telefono').val() == '' || $('#email').val() == '' || (getCheck_POSAgregar() ? false : $('#DiscapacidadObs').val() == ''))
		{
			alert('Debe rellenar todos los campos obligatorios (*)');
			return false;
		}
		else
		{
			for (var i = 0; i < listado_POSAgregar.length; i++)
			{
				if (listado_POSAgregar[i]['RutEmpresa'] == RutEmpresa && listado_POSAgregar[i]['idCargoEmpleado'] == idCargoEmpleado)
				{
					alert(mensajeDuplicados_POSAgregar);
					return false;
				}
			}

			var url  = "postulacion_ajax1.php";
            var parametros = "centrocostoid=" + centrocostoid + '&idCargoEmpleado=' + idCargoEmpleado + '&personaid=' + personaid + '&RutEmpresa=' + $('#RutEmpresa').val();
			// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
			if( window.XMLHttpRequest )
				ajax_AGREGAR = new XMLHttpRequest(); // No Internet Explorer
			else
				ajax_AGREGAR = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
			// Almacenamos en el control al funcion que se invocara cuando la peticion
			// cambie de estado 
			ajax_AGREGAR.onreadystatechange = funcionCallback_agregar_POSAgregar;
			// Enviamos la peticion
			ajax_AGREGAR.open("POST", url, true );
            ajax_AGREGAR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax_AGREGAR.send(parametros);
		}
	});

	function funcionCallback_agregar_POSAgregar()
	{
		// Comprobamos si la peticion se ha completado (estado 4)
		if( ajax_AGREGAR.readyState == 4 )
		{
			// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
			if( ajax_AGREGAR.status == 200 )
			{
				var salida = ajax_AGREGAR.responseText;
				respuesta = JSON.parse(salida);
				$('#mensajeOK').removeClass();
				if (respuesta.existe)
				{
					alert(mensajeDuplicados_POSAgregar);
					return false;
				}
				else
				{
					fila = {
						'centrocostoid':$('#centrocostoid').children("option:selected").val(),
						'nombrecentrocosto':$('#centrocostoid').children("option:selected").text(),
						'idCargoEmpleado':$('.idCargoEmpleado_POSAgregar').children("option:selected").val(),
						'nombrecargo':$('.idCargoEmpleado_POSAgregar').children("option:selected").text(),
						'disponibilidadid':$('#disponibilidadid').children("option:selected").val(),
						'disponibilidadnombre':$('#disponibilidadid').children("option:selected").text(),
						'personaid':$(".newusuarioid_POSAgregar").val(),
						'nombre':$("#nombre").val(),
						'email':$("#email").val(),
						'telefono':$('#telefono').val(),
						'Observacion':$("#Observacion").val(),
						'RutEmpresa':$("#RutEmpresa").val(),
						'discapacidad':$('#DiscapacidadObs').val(),
						'estadoGeneracion':'Por generar'
					};
					listado_POSAgregar.push(fila);
					add2listado_POSAgregar(true);
				}
			
			}
		}
	}
	
	function habilitaGeneracion_POSAgregar()
	{
		$('#GENERARPOSTULACION').attr('disabled', !(listado_POSAgregar.length > 0));
	}

	function limpiarFormulario_POSAgregar()
	{
		$('#centrocostoid').val(0);
		$('.idCargoEmpleado_POSAgregar').val(0);
	}

	function quitarElemento_POSAGregar(centrocostoid, idCargoEmpleado)
	{
		var aux = [];
		for (var i = 0; i < listado_POSAgregar.length; i++)
		{
			if (!(listado_POSAgregar[i]['centrocostoid'] == centrocostoid && listado_POSAgregar[i]['idCargoEmpleado'] == idCargoEmpleado))
			{
				aux.push(listado_POSAgregar[i]);
			}
		}
		listado_POSAgregar = aux;
		add2listado_POSAgregar(true);
	}

	function add2listado_POSAgregar(condicion)
	{
		if (condicion)
		{
			habilitaGeneracion_POSAgregar();
		}
		limpiarFormulario_POSAgregar();
		$('#tabla_postulaciones').DataTable().destroy();
		$('#tabla_postulaciones').DataTable({
			data: listado_POSAgregar,
			columns: [
				{
					data: 'nombrecentrocosto'
				},
				{
					data: 'nombrecargo'
				},
				{
					data: 'estadoGeneracion'
				},
				{
					data: 'centrocostoid',
					render: function ( data, type, row ) {
						if (row.estadoGeneracion != 'Por generar')
						{
							html = '';
						}
						else
						{
							html = '<button class="btn btn-md btn-warning btn-block" onclick="javascrip: quitarElemento_POSAGregar(\'' + row.centrocostoid + '\', \'' + row.idCargoEmpleado + '\')" type="button" id="QUITAR" value="QUITAR" style="margin-top: 10px" >Quitar</button>';
						}
						return html;
					}
				}
			],
			paging: false,
			//lengthMenu: [ 10, 25, 50 ],
			lengthChange: true,
			searching: false,
			ordering: false,
			info: true,
			autoWidth: false,
			fixedColumns: true
		});
	}

	$('#GENERARPOSTULACION').on('click', function(){
		$('.AGREGAR_POSAgregar').attr('disabled', true);
		$('#GENERARPOSTULACION').attr('disabled', true);
		var url  = "postulacion_ajax2.php?matriz=" + JSON.stringify(listado_POSAgregar);
		// Creamos el control XMLHttpRequest segun el navegador en el que estemos 
		if( window.XMLHttpRequest )
			ajax_AGREGAR = new XMLHttpRequest(); // No Internet Explorer
		else
			ajax_AGREGAR = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
		// Almacenamos en el control al funcion que se invocara cuando la peticion
		// cambie de estado 
		ajax_AGREGAR.onreadystatechange = funcionCallback_generarpostulacion_POSAgregar;
		// Enviamos la peticion
		ajax_AGREGAR.open( "POST", url, true );
		ajax_AGREGAR.send( "" );
	});

	function funcionCallback_generarpostulacion_POSAgregar()
	{
		// Comprobamos si la peticion se ha completado (estado 4)
		if( ajax_AGREGAR.readyState == 4 )
		{
			// Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
			if( ajax_AGREGAR.status == 200 )
			{
				var salida = ajax_AGREGAR.responseText;
				respuesta = JSON.parse(salida);
				$('#mensajeOK').removeClass();
				listado_POSAgregar = respuesta;
				for (var i = 0, contError = 0; i < listado_POSAgregar.length; i++)
				{
					if (!listado_POSAgregar[i]['exito'])
					{
						contError++;
					}
				}
				if (contError == listado_POSAgregar.length)
				{
					alert('La operacion fallo, intente nuevamente');
				}
				else if (contError > 0)
				{
					alert('La operacion finalizo correctamente, con fallos en algunos registros');
				}
				else
				{
					alert('La operacion finalizo correctamente');
				}
				add2listado_POSAgregar(false);
			}
		}
	}

    /*templates\registroDec_RecuperarPIN.html*/
    $(".AGREGAR_RDECPIN").click(function(){
        //Campos vacios
      if($("#serial").val().length == 0){
          $("#mensajeError").addClass("callout callout-warning");
          $("#mensajeError").html("Campo no debe estar vac&iacute;o");
          $('#serial').focus();
          return false;
      }
     
  });

  /*templates\revisionActor1_Detalle.html*/
   //alert(idEstadoGestion);
   function guardarValida_RA1()
   {
       if ($('.idEstadoGestion_RA1').val() == 3 && $('#Observacion').val().trim() == '')//Cerrado por RRHH
       {
           alert('Debe agregar una observacion a la gestion de cierre');
           return false;
       }
       var datos = '&idEstadoGestion='+$('.idEstadoGestion_RA1').val()+'&Observacion='+$('#Observacion').val().trim()+'&empleadoFormularioid=<php:texto id="empleadoFormularioid" />'
       $("#formulario").attr("action", "revisionActor1.php?accion=ESTADO"+datos);
       $('#formulario').submit();
       MostrarCargando();
   }

  
   $('.idEstadoGestion_RA1').change(function(){
     hab_deshab_boton();
   });

   function hab_deshab_boton()
   {
     if ($('.idEstadoGestion_RA1').val() != 3)// Cerrado por RRHH
     {
         $('#Observacion').prop("disabled", true);
     }
     else
     {
         $('#Observacion').prop("disabled", false);
     }
     if ($('.idEstadoGestion_RA1').val() != 2)
     {
         $('#GUARDAR').prop("disabled", false);
     }
     else
     {
         $('#GUARDAR').prop("disabled", true);
     }
     if (idEstadoGestion_RA1 != 2)
       {
         $('#GUARDAR').hide();
       }
   }

   function InicioRevisionActor1(){

       $("#idDocumento_vd").val($("#idDocumento").val());
       $("#idDocumento_ac").val($("#idDocumento").val());
       $("#idDocumento_el").val($("#idDocumento").val());
       $("#idDocumento_ac").val($("#idDocumento").val());
       $("#idDocumento_eg").val($("#idDocumento").val());
       
       hab_deshab_boton();
   }

   /*templates\revisionActor2_Detalle.html*/

   function guardarValida_RA2()
   {
       if ($('#idEstadoGestion_RA2').val() == 5 && $('#Observacion').val().trim() == '')//Cerrado por RRHH
       {
           alert('Debe agregar una observacion a la gestion de cierre');
           return false;
       }
       var datos = '&idEstadoGestion='+$('#idEstadoGestion_RA2').val()+'&Observacion='+$('#Observacion').val().trim()+'&empleadoFormularioid=<php:texto id="empleadoFormularioid" />'
       $("#formulario").attr("action", "revisionActor2.php?accion=ESTADO"+datos);
       $('#formulario').submit();
       MostrarCargando();
   }

  
   $('#idEstadoGestion_RA2').change(function(){
     hab_deshab_boton_RA2();
   });

   function hab_deshab_boton_RA2()
   {
     if ($('#idEstadoGestion_RA2').val() != 5)// Cerrado por RRHH
     {
         $('#Observacion').prop("disabled", true);
     }
     else
     {
         $('#Observacion').prop("disabled", false);
     }
     if ($('#idEstadoGestion_RA2').val() != 4)
     {
         $('#GUARDAR').prop("disabled", false);
     }
     else
     {
         $('#GUARDAR').prop("disabled", true);
     }
     if (idEstadoGestion != 4)
       {
         $('#GUARDAR').hide();
       }
   }

   function InicioRevisionActor2(){

       $("#idDocumento_vd").val($("#idDocumento").val());
       $("#idDocumento_ac").val($("#idDocumento").val());
       $("#idDocumento_el").val($("#idDocumento").val());
       $("#idDocumento_ac").val($("#idDocumento").val());
       $("#idDocumento_eg").val($("#idDocumento").val());
       
       hab_deshab_boton_RA2();
   }

   /*templates\rl_documentos_aprobar_Listado.html*/



      //Actualizar la seleccion
      function InicioRLDocumentosAprobar(){
  
        if( $("#cantidad").val() == 1 ){
            $(".Seleccion_RLDAprobar").prop('checked', true);
            $("#select").val(1);
        }

        //documentos de otras paginas
        var docs = $("#docs").val();
        if ( docs != '' ){
            var docs_res = docs.split(",");

            //Recorro el array_RLDAprobar que traje de php
            jQuery.each( docs_res, function( key, value ) {
                if( value != '' ){
                      array_RLDAprobar.push(value);
                  }
              //Recorro todos los checkbox del listado 
              $('.checkbox_RDLAprobar').each( function(){
                   if ( value == $(this).val() ){
                       $(this).prop('checked', true);
                   }
              });
            });
        }

        if( $("#docs").val() != '' ){

        }else{
            $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",true);
            $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",true);
        }

    };

    //Seleccionar todo
    $(".Seleccion_RLDAprobar").change(function(){

        var status = this.checked; 
        var array_aux = [];
        $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",true);
        $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",true);

        if ( $(".Seleccion_RLDAprobar").is(':checked')){

            //Limpian todos los campos 
            array_RLDAprobar = [];
            $("#docs").val('');
            $("#cantidad").val(1);
            $("#select").val(1);
            MostrarCargando();

            //Vamos al ajax_RDLAprobar a buscar todos los documentos pendientes 
            proceso_RDLAprobar = setInterval(function(){ buscarTodosDocumentosPorFirma_RDLAprobar() }, 100);

        }else{
            //Limpian todos los campos 
            array_RLDAprobar = [];
            $("#docs").val('');
            $("#cantidad").val(0);
            $("#select").val(0);
            $(".checkbox_RDLAprobar").prop('checked',false);
        }
                
    });

    $('.checkbox_RDLAprobar').change(function(){
        //Agregar 
        if ( $(this).is(':checked') ){
            array_RLDAprobar.push($(this).val());
            $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",false);
            $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",false);
        }
        //Eliminar
        else{
            var k = $(this).val();
            jQuery.each( array_RLDAprobar, function( key, value ) {
              if( k == value ){
                  array_RLDAprobar.splice(key, 1); 
              }
            });

            if (array_RLDAprobar.length == 0 )
              $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",true);
              $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",true);
        }
        //Pasar al formulario
        $("#docs").val(array_RLDAprobar);
    });
    
    
    
    //Vista previa del documento 
    $(".vista_RDLAprobar").click(function(){ 
        
        var id = this.id;
        var res = id.split('_'); 
        var i = res[1];
        var idDocumento = $("#idDocumento_" + i).val();
        id_doc_RDLAprobar = idDocumento;
        var url = "Documentos_aprobar4_ajax.php";
        var parametros = "idDocumento=" + idDocumento; //Descargar Documento

         // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
           ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
        else
           ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        
           // Almacenamos en el control al funcion que se invocara cuando la peticion
        ajax_RDLAprobar.onreadystatechange = funcionCallback_descargarDoc_RDLAprobar;

        // Enviamos la peticion
        ajax_RDLAprobar.open( "POST", url, false);
        ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RDLAprobar.send(parametros);
    });
    
    //Callback de Vista previa
    function funcionCallback_descargarDoc_RDLAprobar(){
      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;

          if(salida_RDLAprobar){
              
            var ruta = salida_RDLAprobar;
            $("#viewer").attr("src",ruta);
            $("#num").html('Documento : ' + id_doc_RDLAprobar);
            
          }else{
              return false;
          }
        }
      }
    }

    
    
    //Vista previa de firmantes 
    $(".firmantes_RDLAprobar").click(function(){
        
        var id = this.id;
        var res = id.split('_'); 
        var i = res[1];

        var idDocumento = $("#idDocumento_" + i).val();
        iddoc = idDocumento;
        var url = "Documentos_firmaMasiva_ajax.php";
        var parametros = "idDocumento=" + idDocumento;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
        ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
        else
        ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RDLAprobar.onreadystatechange = funcionCallback_RDLAprobar;

        // Enviamos la peticion
        ajax_RDLAprobar.open( "POST", url, true );
        ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RDLAprobar.send(parametros);
    });

    //Callback de Vista previa
    function funcionCallback_RDLAprobar(){
      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;

          if(salida_RDLAprobar){
              verFirmantes_RDLAprobar(salida_RDLAprobar);
          }else{
              return false;
          }
        }
      }
    }

    //Construir tabla de firmantes
    function verFirmantes_RDLAprobar(salida_RDLAprobar){

          respuesta = salida_RDLAprobar.split("|");
          count = 0;
          count = respuesta.length;
          j = 0; 
          n = 0;
          rep = Math.trunc(count/5);
          //console.log(rep);
          //Eliminar filas ateriores
          $(".fila").remove();

          while ( j < rep ){

              nombre='';
              rut='';
              fecha='';

              if( j != 0 ){
                    m = 4;
                  for (var i = n; i < m+n ; i++) {
                      if( i == (n  ) ) rut    = respuesta[i];
                      if( i == (1+n) ) nombre = respuesta[i];
                      if( i == (3+n) ) fecha  = respuesta[i];
                  }
              }else{
                    for (var i = 0; i < 5 ; i++) {
                      if( i == (n  ) ) rut    = respuesta[i];
                      if( i == (1+n) ) nombre = respuesta[i];
                      if( i == (3+n) ) fecha  = respuesta[i];
                  }
              }

              $('#tabla_firm tr:last').after('<tr class="fila"><td>' + rut + '</td><td>' + nombre + '</td><td>' + fecha + '	</td></tr>');

              if( j == 0) n = 5;
              if( j == 1) n = 10;
              if( j == 2) n = 15;
              if( j == 3) n = 20; 
              
              j++;
          }

          $("#num_f").html('Documento : ' + iddoc);
    }

    //Seleccionar todo
    function buscarTodosDocumentosPorFirma_RDLAprobar(){

         clearInterval(proceso_RDLAprobar);// poner esto cuando llega respuesta

        var usuarioid = $("#usuarioid").val();
        var ptipousuarioid = $("#ptipousuarioid").val();
        var idDocumento = $("#idDocumento").val();
        var idTipoDoc = $("#idTipoDoc").val();
        var fichaid = $("#idProceso").val();
        var Firmante = $("#Firmante").val();
        var fichaid = $("#fichaid").val();
        var idProceso = $("#idProceso").val();
        var idTipoFirma = $("#idTipoFirma").val();
        var rlTipoDocumento = $("#rlTipoDocumento").val();

        var url = "rl_Documentos_aprobar5_ajax.php";
        var parametros = "usuarioid=" + usuarioid + "&ptipousuarioid=" + ptipousuarioid + '&idDocumento=' + idDocumento + '&idTipoDoc=' + idTipoDoc + '&idProceso=' + idProceso + '&Firmante=' + Firmante + '&fichaid=' + fichaid + '&idProceso=' + idProceso + "&idTipoFirma=" + idTipoFirma + "&rlTipoDocumento=" + rlTipoDocumento;
            //console.log(url);
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RDLAprobar.onreadystatechange = funcionCallback_buscarTodos_RDLAprobar;

        // Enviamos la peticion
        ajax_RDLAprobar.open( "POST", url, true );
        ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RDLAprobar.send( parametros );
    }

    //Callback de Vista previa
    function funcionCallback_buscarTodos_RDLAprobar(){

      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          salida_RDLAprobar = ajax_RDLAprobar.responseText;
          if(salida_RDLAprobar){
              docs = JSON.parse(salida_RDLAprobar); 
          
              $.each(docs, function( index, value ) {
              array_RLDAprobar.push(value);
            });

              $("#docs").val(array_RLDAprobar);

              $(".checkbox_RDLAprobar").prop('checked','true');

              if( array_RLDAprobar.length > 0 ){
                $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",false);
                $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",false);
              }

          }else{
              return false;
          }
          
          OcultarCargando();
        }
      }
    }

    //Pasar los datos de los documentos a firmar al formulario 
    $(".SELECCION_MULTIPLE_RDLAprobar").click(function(){
    
        var documentos = $("#docs").val();
        var resultado = documentos.split(',');
        var j = resultado.length;
        
        if( documentos != '' ){
            //Limpiamos la tabla del modal 
            $(".fila_pendiente").remove();
            MostrarCargando();
            buscarDocumentos_RDLAprobar(documentos);
        }else{
            $(".SELECCION_MULTIPLE_RDLAprobar").attr("disabled",true);
        }

    }); 

    function buscarDocumentos_RDLAprobar(documentos){

        var url = "Documentos_aprobar1_ajax.php";
        var parametros = "docs=" + documentos;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RDLAprobar.onreadystatechange = funcionCallback_buscar_RDLAprobar;

        // Enviamos la peticion
        ajax_RDLAprobar.open( "POST", url, true );
        ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RDLAprobar.send(parametros);
    }

    //Callback de Vista previa
    function funcionCallback_buscar_RDLAprobar(){

      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;
         
          if(salida_RDLAprobar){
              docs = JSON.parse(salida_RDLAprobar); 
        
             $.each(docs, function( index, value ) {
                 $('#tabla_pendientes tr:last').after('<tr class="fila_pendiente" id="fila_' + docs[index].idDocumento + '"><td style="text-align: center;">' + docs[index].idDocumento + '</td><td style="text-align: center;">' + docs[index].NombreTipoDoc + '</td><td style="text-align: center;"><button style="background-color: transparent;" class="btn btn-md" type="button" id="icon_btn_' + docs[index].idDocumento + '"  onclick="eliminarDeLista_RDLAprobar(' + docs[index].idDocumento + ');"><i id="icon_' + docs[index].idDocumento + '"  class="fa fa-minus" aria-hidden="true"  title="Quitar de esta lista"></i></button></td></tr>');
             });
              $(".aprobar_RDLAprobar2").attr("disabled",false);

          }else{
              return false;
          }
          
          OcultarCargando();
        }
       }
  }

  //Eliminar de la lista 
  function eliminarDeLista_RDLAprobar(i){

      var fila = "#fila_" + i; 
      var cant = $('#tabla_pendientes tr').length - 1;
      
    if( cant == 1 )
          $('#modal_pendientes').modal('hide');
      else
          $(fila).remove();
      
  }

  //Aprobar los documentos que esten en la lista 
 $(".aprobar_RDLAprobar2").click(function(){
     
     if( $("#docs").val() != '' ){

         //Cantidad de filas 
        cant = $('#tabla_pendientes tr').length - 1;
        docs = $("#docs").val();
        indices = docs.split(","); 
        documento = indices[i];

        //Validar si desean firmar todo
        //var respuesta = confirm('Esta seguro(a) que desea aprobar estos documentos?');

       // if( respuesta ){
           MostrarCargando();
           proceso_RDLAprobar = setInterval(function(){ aprobar_RDLAprobar(documento,i) }, 1000);
           $(".aprobar_RDLAprobar2").attr("disabled",true);
        //}
     }	
 });

  //Firmar un documento
  function aprobar_RDLAprobar(idDocumento,i){

    clearInterval(proceso_RDLAprobar);// poner esto cuando llega respuesta

    var url = "Documentos_aprobar2_ajax.php";
    var parametros = "idDocumento=" + idDocumento;

    fila = i;

     // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    
       // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax_RDLAprobar.onreadystatechange = funcionCallback_aprobar_RDLAprobar;

    // Enviamos la peticion
    ajax_RDLAprobar.open( "POST", url, false);
    ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_RDLAprobar.send( parametros );
  }

  //Callback de Vista previa
    function funcionCallback_aprobar_RDLAprobar(){

      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;
         
              if(salida_RDLAprobar){
                  mostrarEstadoAprobacion_RDLAprobar(salida_RDLAprobar,fila);
              }else{
                  return false;
              }
        }
       }
    }

  //Mostrar estado de Firma en el modal
  function mostrarEstadoAprobacion_RDLAprobar(salida_RDLAprobar, fila){

    //Incrementar la fila de la tabla a recorrer
    documento = indices[i];
    i++;

    //Actualizamos iconos segun la respuesta 

       //Si es otro error 
    if( salida_RDLAprobar == 0 ){

      $("#icon_" + documento).removeClass();
      $("#icon_" + documento).addClass('fa fa-exclamation-triangle text-warning');
      $("#icon_" + documento).prop('title','Intente nuevamente'); 
      $("#icon_btn_" + documento).removeAttr('onclick');
      
       OcultarCargando();

    }else {
      $("#icon_" + documento).removeClass();
      $("#icon_" + documento).addClass('fa fa-check-circle text-success');
      $("#icon_" + documento).prop('title','Aprobado');
      $("#icon_btn_" + documento).removeAttr('onclick');

      //Si termino de recorrer las filas 
      if( !(i < cant) ){
            //Ocultar el gif de cargando
            OcultarCargando();
      }
    }

    //Si quedan filas que recorrer 
    if( i < cant ){
        //Pasar al siguiente 
        documento = indices[i];
        proceso_RDLAprobar = setInterval(function(){ aprobar_RDLAprobar(documento,i) }, 1000);
    }

    $("#modal_pendientes").modal("show");
  }

 //Cerrar el modal 
 $(".cerrar_aprobar_RDLAprobar").click(function(){

    //Limpian todos los campos 
    array_RLDAprobar = [];
    $("#docs").val('');
    $("#cantidad").val(0);
    $("#select").val(0);
    $(".checkbox_RDLAprobar").prop('checked',false);

    $("#modal_pendientes").modal('hide');
    $("#formulario3").submit();
 });

 //////////////////
 /// RECHAZAR   ///
 //////////////////

 //Pasar los datos de los documentos a firmar al formulario 
    $(".SELECCION_MULTIPLE_R_RDLAprobar").click(function(){
    
        var documentos = $("#docs").val();
        var resultado = documentos.split(',');
        var j = resultado.length;
        
        if( documentos != '' ){
            MostrarCargando();
            //Limpiamos la tabla del modal 
            $(".fila_pendiente_r").remove();
            buscarDocumentos_r_RDLAprobar(documentos);
        }else{
            $(".SELECCION_MULTIPLE_R_RDLAprobar").attr("disabled",true);
        }

    }); 

    function buscarDocumentos_r_RDLAprobar(documentos){

        var url = "Documentos_aprobar1_ajax.php";
        var parametros = "docs=" + documentos;

        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer

        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RDLAprobar.onreadystatechange = funcionCallback_buscar_r_RDLAprobar;

        // Enviamos la peticion
        ajax_RDLAprobar.open( "POST", url, true );
        ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RDLAprobar.send(parametros);
    }

    //Callback de Vista previa
    function funcionCallback_buscar_r_RDLAprobar(){

      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;
         
          if(salida_RDLAprobar){
              docs = JSON.parse(salida_RDLAprobar); 
        
             $.each(docs, function( index, value ) {
                 $('#tabla_pendientes_r tr:last').after('<tr class="fila_pendiente_r" id="fila_r_' + docs[index].idDocumento + '"><td style="text-align: center;">' + docs[index].idDocumento + '</td><td style="text-align: center;">' + docs[index].NombreTipoDoc + '</td><td style="text-align: center;"><button style="background-color: transparent;" id="icon_btn_' + docs[index].idDocumento + '" class="btn btn-md" type="button" onclick="eliminarDeLista_r_RDLAprobar(' + docs[index].idDocumento + ');"><i id="icon_r_' + docs[index].idDocumento + '"  class="fa fa-minus" aria-hidden="true"  title="Quitar de esta lista"></i></button></td></tr>');
             });
              $(".rechazar_RDLAprobar").attr("disabled",false);

          }else{
              return false;
          }
          
          OcultarCargando();
        }
    }
  }

  //Eliminar de la lista 
  function eliminarDeLista_r_RDLAprobar(i){

      var fila = "#fila_r_" + i; 
      var cant = $('#tabla_pendientes_r tr').length - 1;
      
    if( cant == 1 )
          $('#modal_pendientes_r').modal('hide');
      else
          $(fila).remove();
      
  }

 

  //Aprobar los documentos que esten en la lista 
 $(".rechazar_RDLAprobar").click(function(){
     
     if( $("#docs").val() != '' ){

         //Cantidad de filas 
        cant = $('#tabla_pendientes_r tr').length - 1;
        docs = $("#docs").val();
        indices = docs.split(","); 
        documento = indices[i];

        //Validar si desean firmar todo
        var respuesta = confirm('Esta seguro(a) que desea rechazar estos documentos?');

        if( respuesta ){
           MostrarCargando();
           proceso_RDLAprobar = setInterval(function(){ rechazar_RDLAprobar(documento,i) }, 1000);
           $(".rechazar_RDLAprobar").attr("disabled",true);
        }
     }	
 });

  //Firmar un documento
  function rechazar_RDLAprobar(idDocumento,i){

    clearInterval(proceso_RDLAprobar);// poner esto cuando llega respuesta
    var obs = $("#observacion").val();
    var url = "Documentos_aprobar3_ajax.php";
    var parametros = "idDocumento=" + idDocumento + "&observacion=" + obs; //Rechazar

    fila = i;

     // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
       ajax_RDLAprobar = new XMLHttpRequest(); // No Internet Explorer
    else
       ajax_RDLAprobar = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    
       // Almacenamos en el control al funcion que se invocara cuando la peticion
    ajax_RDLAprobar.onreadystatechange = funcionCallback_rechazar_RDLAprobar;

    // Enviamos la peticion
    ajax_RDLAprobar.open( "POST", url, false);
    ajax_RDLAprobar.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_RDLAprobar.send( parametros );
  }

  //Callback de Vista previa
    function funcionCallback_rechazar_RDLAprobar(){

      // Comprobamos si la peticion se ha completado (estado 4)
      if( ajax_RDLAprobar.readyState == 4 )
      {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RDLAprobar.status == 200 )
        {
          // Escribimos el resultado en la pagina HTML mediante DHTML
          //document.all.salida_RDLAprobar.innerHTML = "<b>"+ajax_RDLAprobar.responseText+"</b>"; 
          salida_RDLAprobar = ajax_RDLAprobar.responseText;
         
              if(salida_RDLAprobar){
                  mostrarEstadoAprobacion_r_RDLAprobar(salida_RDLAprobar,fila);
              }else{
                  return false;
              }
        }
       }
    }

  //Mostrar estado de Firma en el modal
  function mostrarEstadoAprobacion_r_RDLAprobar(salida_RDLAprobar, fila){

    //Incrementar la fila de la tabla a recorrer
    documento = indices[i];
    i++;

    //Actualizamos iconos segun la respuesta 

       //Si es otro error 
    if( salida_RDLAprobar == 0 ){

      $("#icon_r_" + documento).removeClass();
      $("#icon_r_" + documento).addClass('fa fa-exclamation-triangle text-warning');
      $("#icon_r_" + documento).prop('title','Intente nuevamente'); 
      $("#icon_btn_" + documento).removeAttr('onclick');
      
       OcultarCargando();

    }else {
      $("#icon_r_" + documento).removeClass();
      $("#icon_r_" + documento).addClass('fa fa-check-circle text-success');
      $("#icon_r_" + documento).prop('title','Rechazado');
      $("#icon_btn_" + documento).removeAttr('onclick');

      //Si termino de recorrer las filas 
      if( !(i < cant) ){
            //Ocultar el gif de cargando
            OcultarCargando();
      }
    }

    //Si quedan filas que recorrer 
    if( i < cant ){
        //Pasar al siguiente 
        documento = indices[i];
        proceso_RDLAprobar = setInterval(function(){ rechazar_RDLAprobar(documento,i) }, 1000);
    }

    $("#modal_pendientes_r").modal("show");
  }

 //Cerrar el modal 
 $("#cerrar_rechazar_RDLAprobar").click(function(){

    //Limpian todos los campos 
    array_RLDAprobar = [];
    $("#docs").val('');
    $("#cantidad").val(0);
    $("#select").val(0);
    $(".checkbox_RDLAprobar").prop('checked',false);

    $("#modal_pendientes_r").modal('hide');
    $("#formulario3").submit();
 });
    
/*templates\rl_importacionpdf_proveedores_FormularioAgregar.html */


     
function inicio()
{
    $("#GENERAR").attr('disabled', true);
    $(".idTipoDoc_RLIP").attr("disabled",true);
    //$(".idProceso_RLIP").attr("disabled",true);
    $(".idPlantilla_RLIP").attr("disabled",true);
    $(".idCentroCosto_RLIP").attr("disabled",true);
    //para deshabilitar la subida del pdf
    $("#Documento").attr("disabled",true);
    $(".btn-file").attr("disabled",true);
    $(".pdf64_RLIP").attr("disabled",true);
    RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
    if ( RutEmpresa_RLIP == 0)
    {
        $(".RutProveedor_RLIP").attr("disabled",true);
        $(".btn_proveedores_RLIP").attr("disabled",true);
        $("#btn_empresa").attr("disabled",true);
    }
    else
    {
        seleccion_empresa_RLIP();
    }
    var rutproveedor = $(".RutProveedor_RLIP").val();
    if (rutproveedor.trim() == '')
    {
        $(".FechaDocumento_RLIP").attr("disabled",true);
    }
}
  
/*******************************/
/**BUSCAR FIRMANTES DE EMPRESA**/
/*******************************/
$(".RutEmpresa_RLIP").change(function(){
    $("#NombreProveedor").val('');
    $(".RutProveedor_RLIP").val('');
    $(".RutProveedor_RLIP").attr("disabled",false);
    $(".RutProveedor_RLIP").focus();
    $(".FechaDocumento_RLIP").attr("disabled",true);  
    seleccion_empresa_RLIP();
});

function seleccion_empresa_RLIP()
{	
    var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");
    $(".idTipoDoc_RLIP").attr("disabled",true);
    //  $(".idProceso_RLIP").attr("disabled",true);
    $(".idPlantilla_RLIP").attr("disabled",true);
    //  $(".FechaDocumento_RLIP").attr("disabled",true);  
    //para deshabilitar la subida del pdf
    $(".pdf64_RLIP").attr("disabled",true);
    $(".btn-file").attr("disabled",true);
    //fin
    $(".FechaDocumento_RLIP").val('');
    //$(".idTipoDoc_RLIP").val(0);
    //   $(".idProceso_RLIP").val(0);
    $(".idPlantilla_RLIP").val(0);
    $("#Documento").val('');
    $("#archivo").val();
    $("#idWF").val(0);
    $(".plan").remove();
    $(".orden").val('');
    $(".orden_emp").val('');
    // $("#nombrecentrocosto").val('');
    //  $(".idCentroCosto_RLIP").val('');
    /*$("#NombreProveedor").val('');
    $(".RutProveedor_RLIP").val('');
    $(".RutProveedor_RLIP").attr("disabled",false);
    $(".RutProveedor_RLIP").focus();*/
    //Ocultar firmantes 
    $("#icono_representante").hide();
    $("#icono_proveedores").hide();
    $("#label").hide();
    $("#step-1").collapse('hide');
    $("#step-2").collapse('hide');
    if( RutEmpresa_RLIP != 0 ){
        $(".idTipoDoc_RLIP").attr("disabled",true);
        //$(".idTipoDoc_RLIP").val(0);
        $(".btn_proveedores_RLIP").attr("disabled",false);
        $("#btn_empresa").attr("disabled",false);
        $(".RutProveedor_RLIP").attr("disabled",false);
        var url  = "Generar_Documentos_Masivos2_ajax.php";
        var parametros = "RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RLIP = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RLIP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RLIP.onreadystatechange = funcionCallback_Empresa_RLIP;
        // Enviamos la peticion
        ajax_RLIP.open( "POST", url, true );
        ajax_RLIP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RLIP.send(parametros);
    }
}

function funcionCallback_Empresa_RLIP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_RLIP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RLIP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_RLIP.innerHTML = "<b>"+ajax_RLIP.responseText+"</b>"; 
            salida_RLIP = ajax_RLIP.responseText;
            try {
                var datos = JSON.parse(salida_RLIP);
                $.each(datos,function(key, registro) {                            
                    var fila_orden = '';
                    fila_orden = "<tr align='center' class='fila'><td ><input type='checkbox' name='Firmantes_Emp[]' class='f_emp' value='" + registro.personaid + "'id='emp_" + registro.incremental + "'  onclick='seleccion_RLIP(" + registro.incremental + ");'/> </td><td>" + registro.personaid + "</td><td>" + registro.nombrecompleto +"</td><td>" +  registro.descripcion + "</td><td class='columna_emp'><input name='orden_emp_" + registro.personaid + "'' id='orden_emp_" + registro.personaid + "' class='orden_emp' style='text-align:center; border: none;' readonly /></td></tr>";
                    $("#tabla_emp tr:last ").after(fila_orden);
                });         
            }catch(e) {
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html(salida_RLIP);
                $(".idTipoDoc_RLIP").attr("disabled",true);
                //$(".idProceso_RLIP").attr("disabled",true);
                $(".idPlantilla_RLIP").attr("disabled",true);
                $(".FechaDocumento_RLIP").attr("disabled",true);
                //para deshabilitar la subida del pdf
                $(".pdf64_RLIP").attr("disabled",true);
                $(".btn-file").attr("disabled",true);
                //fin
                $(".btn_proveedores_RLIP").attr("disabled",true);
                $(".FechaDocumento_RLIP").val('');
                //$(".idTipoDoc_RLIP").val(0);
                //$(".idProceso_RLIP").val(0);
                $(".idPlantilla_RLIP").val(0);
                $("#Documento").val('');
                $("#archivo").val();
                $("#idWF").val(0);
                $(".plan").remove();
                $(".orden").val('');
                $(".orden_emp").val('');
                $("#nombrecentrocosto").val('');
                //$(".idCentroCosto_RLIP").val('');
                //$("#NombreProveedor").val('');
                //Limpiar tabla 
                $(".fila").remove();
                //Ocultar firmantes 
                $("#icono_representante").hide();
                $("#icono_proveedores").hide();
                $("#label").hide();
                $("#step-1").collapse('hide');
                $("#step-2").collapse('hide');
            }
        }
      }
}
  
/*******************************/
/**BUSCAR LISTADO DE proveedores **/
/*******************************/
$(".btn_proveedores_RLIP").click(function(){
    var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
    $(".fila_cl").remove();
    if( RutEmpresa_RLIP == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }else{
        $(".FechaDocumento_RLIP").val('');
        $(".FechaDocumento_RLIP").attr("disabled",false);
        //$(".idTipoDoc_RLIP").attr("disabled",true);
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "rl_Generar_Documento_PorFicha_ajax6.php";
        var parametros = "RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RLIP = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RLIP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RLIP.onreadystatechange = funcionCallback_cl;
        // Enviamos la peticion
        ajax_RLIP.open( "POST", url, true );
        ajax_RLIP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RLIP.send( parametros);
    }
});

function funcionCallback_cl()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_RLIP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RLIP.status == 200 )
        { 
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_RLIP = ajax_RLIP.responseText; 
            listado = JSON.parse(salida_RLIP);
            num_RLIP = listado.length;
            if( num_RLIP > 0 ){
                    $.each(listado, function( index, value ) {
                    $('#example2 tr:last').after('<tr class="fila_cl"><td>' + listado[index].RutProveedor + '</td><td><div id="' + listado[index].RutProveedor + '">' + listado[index].NombreProveedor + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_cl" id="btn_agregar_cl" onclick="agregarClX(\'' + listado[index].RutProveedor + '\');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }
            else{
                $('#example2 tr:last').after('<tr class="fila_cl"><td colspan=3>No existen Proveedores registrados para la Empresa seleccionada</td></tr>');
                $(".idTipoDoc_RLIP").attr("disabled",true);
                //$(".idProceso_RLIP").attr("disabled",true);
                $(".idPlantilla_RLIP").attr("disabled",true);
                $(".FechaDocumento_RLIP").val('');
                $(".FechaDocumento_RLIP").attr("disabled",true);
                //para deshabilitar la subida del pdf
                $(".pdf64_RLIP").attr("disabled",true);
                $(".btn-file").attr("disabled",true);
                //fin
                $(".FechaDocumento_RLIP").val('');
                //$(".idTipoDoc_RLIP").val(0);
                //$(".idProceso_RLIP").val(0);
                $(".idPlantilla_RLIP").val(0);
                $("#Documento").val('');
                $("#archivo").val();
                $("#idWF").val(0);
                $(".plan").remove();
                $(".orden").val('');
                $(".orden_emp").val('');
                //Limpiar tabla 
                $(".fila").remove();
                //Ocultar firmantes 
                $("#icono_representante").hide();
                $("#icono_proveedores").hide();
                $("#label").hide();
                $("#step-1").collapse('hide');
                $("#step-2").collapse('hide');
            }
        }
    }
}

function agregarClX(i){
    $(".RutProveedor_RLIP").val(i);
    $("#NombreProveedor").val($("#" + i ).html());
    $(".cerrar_proveedores_RLIP").click();
    //buscarFirmantesproveedores(); 
}

/********************************/
/**BUSCAR FIRMANTES DEL CLIENTE**/
/********************************/

$(".FechaDocumento_RLIP").change(function(){
    //$(".idTipoDoc_RLIP").attr("disabled",false);
    $(".idPlantilla_RLIP").attr("disabled",false);
    getPlantillas_RLIP();
});

//Modal de Lugares de pago 
$(".btn_lugares_pago_RLIP").click(function(){
    var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
    $(".fila_lp").remove();
    if( RutEmpresa_RLIP == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar la Empresa");
        return false;
    }else{
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
        var url  = "rl_Generar_Documento_PorFicha_ajax1.php";
        var parametros = "RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RLIP = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RLIP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RLIP.onreadystatechange = funcionCallback_lugarespago_RLIP;
        // Enviamos la peticion
        ajax_RLIP.open( "POST", url, true );
        ajax_RLIP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RLIP.send( parametros );
    }
});

function funcionCallback_lugarespago_RLIP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_RLIP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RLIP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            salida_RLIP = ajax_RLIP.responseText;
            listado = JSON.parse(salida_RLIP);
            var numlp = listado.length;
            if( numlp > 0 ){
                $.each(listado, function( index, value ) {
                    $('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td>' + listado[index].lugarpagoid + '</td><td><div id="' + listado[index].lugarpagoid + '">' + listado[index].nombrelugarpago + ' </div></td><td ><button type="button" style="background-color: transparent;" class="btn" name="btn_agregar_lp" id="btn_agregar_lp" onclick="agregarLp_RLIP(' + listado[index].lp + ');"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>');
                });
            }
            else{
                $('#tabla_lugares_pago tr:last').after('<tr class="fila_lp"><td colspan = 3>No existen Centros de Costo de la Empresa seleccionada</td></tr>');
            }
        }
    }
}

function agregarLp_RLIP(i){
    $("#lugarpagoid").val(i);
    $(".idCentroCosto_RLIP").val(i);
    $("#nombrelugarpago").val($("#" + i ).html());
    $("#cerrar_lugares_pago").click();
}

/*********************/
/**FILTRAR PLANTILLA**/
/********************/
$(".idTipoDoc_RLIP").change(function(){
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html(""); 
    if( $(".idTipoDoc_RLIP").val() != 0 ){
        $("#icono_representante").hide();
        $("#icono_proveedores").hide();
        $("#Representates").val("");
        $("#Empleado").val("");
        $("#Cantidad_Firmantes").val("");
        $("#proveedores").val("");
        $(".orden").val('');
        $(".orden_emp").val('');
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        $('input[type=checkbox]').prop('checked', false);
        $("#step-1").collapse('hide');
        $("#step-2").collapse('hide');
        var empresa = $(".RutEmpresa_RLIP").val();
        var idTipoDoc = $(".idTipoDoc_RLIP").val();
        var idTipoFlujo = 1; //No exista flujos de Cliente
        $(".plan").remove();
        if( idTipoDoc != 0 && empresa != 0 ){
            getPlantillas_RLIP();
        }
    }else{
        //$(".idProceso_RLIP").attr("disabled",true);
        $(".idPlantilla_RLIP").attr("disabled",true);
        //para deshabilitar la subida del pdf
        $(".pdf64_RLIP").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        //fin
        //Ocultar firmantes 
        $("#icono_representante").hide();
        $("#icono_proveedores").hide();
        //$(".idTipoDoc_RLIP").val(0);
        //$(".idProceso_RLIP").val(0);
        $(".idPlantilla_RLIP").val(0);
        $("#Documento").val('');
        $("#archivo").val();
        $("#idWF").val(0);
        $(".orden").val('');
        $(".orden_emp").val('');
      }
});

function getPlantillas_RLIP()
{
    var empresa = $(".RutEmpresa_RLIP").val();
    var idTipoDoc = $(".idTipoDoc_RLIP").val();
    var idTipoFlujo = 1; //No exista flujos de Cliente
    var url  = "rl_importacionpdf_buscarplantillas_porcliente_ajax.php";
    var parametros = "RutEmpresa=" + empresa + "&idTipoDoc=" + idTipoDoc + "&idTipoFlujo=" + idTipoFlujo;
    // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
    if( window.XMLHttpRequest )
        ajax_RLIP = new XMLHttpRequest(); // No Internet Explorer
    else
        ajax_RLIP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
    // Almacenamos en el control al funcion que se invocara cuando la peticion
    // cambie de estado 
    ajax_RLIP.onreadystatechange = funcionCallback_RLIP2;
    // Enviamos la peticion
    ajax_RLIP.open( "POST", url, true );
    ajax_RLIP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax_RLIP.send(parametros);
}

function funcionCallback_RLIP2()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_RLIP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RLIP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_RLIP.innerHTML = "<b>"+ajax_RLIP.responseText+"</b>"; 
            salida_RLIP = ajax_RLIP.responseText;
            if( salida_RLIP != '' ){
                var datos = JSON.parse(salida_RLIP);
                $(".idPlantilla_RLIP").empty().append('<option value="0">( Seleccione )</option>');
                $.each(datos,function(key, registro) {
                    $(".idPlantilla_RLIP").append('<option class="plan" ' + registro.Aprobado + ' value='+ registro.idPlantilla +'>'+registro.Descripcion_Pl+'</option>');                                      
                }); 
                // $('.idProceso_RLIP').attr("disabled",false);  
                $('.idPlantilla_RLIP').attr("disabled",false);  
            }else{
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La Empresa seleccionada no tiene flujos de importacion asociados."); 
                $(".idProceso_RLIP").attr("disabled",true);
                $(".idPlantilla_RLIP").attr("disabled",true);
                //$("#idFirma").attr("disabled",true);
                //$(".idTipoDoc_RLIP").val(0);
                $(".idProceso_RLIP").val(0);
                $(".idPlantilla_RLIP").val(0);
                //$("#idFirma").val(0);
                $("#idWF").val(0);
                $(".orden").val('');
                $(".orden_emp").val('');
            }
        }
    }
}


   $(".idProceso_RLIP").change(function(){
    if( $(".idProceso_RLIP").val() != 0 )
        $(".idPlantilla_RLIP").attr("disabled",false);
    else{
        $(".idPlantilla_RLIP").attr("disabled",true);
        //$("#idFirma").attr("disabled",true);
        //deshabilita subida pdf
        $(".pdf64_RLIP").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $("#Documento").val('');
        //fin
        //Ocultar firmantes 
        $("#icono_representante").hide();
        $("#icono_proveedores").hide();
        $(".idProceso_RLIP").val(0);
        $(".idPlantilla_RLIP").val(0);
        // $("#idFirma").val(0);
        $("#idWF").val(0);
        $(".orden").val('');
        $(".orden_emp").val('');
    }
});

  
$(".idPlantilla_RLIP").change(function(){
    $("#Documento").val('');
    //Ocultar firmantes 
    $("#icono_representante").hide();
    $("#icono_proveedores").hide();
    $(".pdf64_RLIP").val('');
    $("#GENERAR").attr('disabled', true);
    if( $(".idPlantilla_RLIP").val() != 0 ){
        getSubAreas_RLIP();
        $(".idCentroCosto_RLIP").attr("disabled",false);
        //habilita la subida del pdf
        $(".btn-file").attr("disabled",false);
        $(".pdf64_RLIP").attr("disabled",false);
        //fin
        $("#Representates").val("");
        $("#Empleado").val("");
        $("#Cantidad_Firmantes").val("");
        $("#proveedores").val("");
        $(".orden").val('');
        $(".orden_emp").val('');
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        $('input[type=checkbox]').prop('checked', false);
        $("#step-1").collapse('hide');
        $("#step-2").collapse('hide');
    }else{
        $(".idCentroCosto_RLIP").attr("disabled",true);
        //deshabilita subida pdf
        $(".pdf64_RLIP").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        //fin
        $(".idPlantilla_RLIP").val(0);
        $("#Documento").val('');
        $(".pdf64_RLIP").val();
        $("#idWF").val(0);
        $(".orden").val('');
    }
    //para eliminar filas tabla
    var nfilas = $("#tabla_cli tr").length;
    for (var i = 1; i < nfilas; i++) 
    {		
        document.getElementById("tabla_cli").deleteRow(1);
    }
});

/*********************/
/**FILTRAR SUB AREAS**/
/********************/
function getSubAreas_RLIP()
{
    //console.log(123);
    var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val();
    $(".tgcc").remove();
    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html(""); 
    var idplantilla = $(".idPlantilla_RLIP").val();
    if( $(".idPlantilla_RLIP").val() != 0 ){
        var url  = "rl_importacionpdf_tipogestorcc_ajax.php";
        var parametros =" idplantilla=" + idplantilla + "&RutEmpresa=" + RutEmpresa;

        if( window.XMLHttpRequest )
            ajax2 = new XMLHttpRequest(); 
        else
            ajax2 = new ActiveXObject("Microsoft.XMLHTTP"); 
        ajax2.onreadystatechange = funcionCallback_subarea;
        // Enviamos la peticion
        ajax2.open( "POST", url, true );
        ajax2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax2.send(parametros);
    }
}
var salida2;
function funcionCallback_subarea()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax2.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax2.status == 200 )
        {
            salida2 = ajax2.responseText;
            if( salida2 != '' ){
                var datos = JSON.parse(salida2);
                $.each(datos,function(key, registro) {
                    $(".idCentroCosto_RLIP").append('<option class="tgcc" value='+ registro.centrocostoid +'>'+registro.nombrecentrocosto+'</option>');                                      
                }); 
            }
            //console.log('HOLO');
            buscarFirmantesproveedores();
        }
    }
}


function buscarFirmantesproveedores(){
    //clearInterval(proceso);// poner esto cuando llega respuesta														
    var RutProveedor = $(".RutProveedor_RLIP").val(); 
    var RutEmpresa_RLIP = $(".RutEmpresa_RLIP").val(); 
    if( RutEmpresa_RLIP != 0 ){
        var url  = "rl_Generar_Documentos_Masivos4_ajax.php";
        var parametros = "RutProveedor=" + RutProveedor + "&RutEmpresa=" + RutEmpresa;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax3 = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax3 = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax3.onreadystatechange = funcionCallback_proveedores_seleccionado;
        // Enviamos la peticion
        ajax3.open( "POST", url, true );
        ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax3.send(parametros);
    }
}

function funcionCallback_proveedores_seleccionado()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax3.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax3.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML 
            salida3 = ajax3.responseText; 
            datos = JSON.parse(salida3);
            if( salida3 === '1' ) num_RLIP = 0;
            else num_RLIP = datos.length;
            try {
                $.each(datos,function(key, registro) {
                    var fila_orden = '';
                    fila_orden = "<tr align='center' class='fila_cl'><td ><input type='checkbox' name='Firmantes_Cli[]' class='f_cli' value='" + registro.RutUsuario + "'id='cli_" + registro.correlativo + "'  onclick='seleccion_cli_RLIP(" + registro.correlativo + ");'/> </td><td>" + registro.RutUsuario + "</td><td>" + registro.nombrecompleto +"</td><td class='columna_cli'><input name='orden_" + registro.RutUsuario + "'' id='orden_" + registro.RutUsuario + "' class='orden' style='text-align:center; border: none;' readonly /></td></tr>";
                    $("#tabla_cli tr:last ").after(fila_orden);                   
                });         
            //<td>" + registro.NombreProveedor + "</td>
            }catch(e) {
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html(salida3);
                $(".FechaDocumento_RLIP").attr("disabled",true);
            }
            if( num_RLIP === 0 ){
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("Los accionistas seleccionados no tiene firmantes asociados");
            }
            getDataFlujo_RLIP();
        }
    }
}

function getDataFlujo_RLIP()
{
    var idPlantilla = $(".idPlantilla_RLIP").val();
    if( idPlantilla != 0 ){
        var url  = "rl_Generar_Documentos_Masivos1_ajax.php";
        var parametros = "idPlantilla=" + idPlantilla;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_RLIP = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_RLIP = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_RLIP.onreadystatechange = funcionCallback_Plantilla_RLIP;
        // Enviamos la peticion
        ajax_RLIP.open( "POST", url, true );
        ajax_RLIP.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_RLIP.send(parametros);
    }
}

function funcionCallback_Plantilla_RLIP()
{
    // Comprobamos si la peticion se ha completado (estado 4)
    if( ajax_RLIP.readyState == 4 )
    {
        // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
        if( ajax_RLIP.status == 200 )
        {
            // Escribimos el resultado en la pagina HTML mediante DHTML
            //document.all.salida_RLIP.innerHTML = "<b>"+ajax_RLIP.responseText+"</b>"; 
            salida_RLIP = ajax_RLIP.responseText;
            if( salida_RLIP != '' ){ 
                var datos = JSON.parse(salida_RLIP);
                var max = $("#max").val();
                $.each(datos,function(key, registro) {
                    //2 : Representante 
                    //3 : Empleado
                    //11: Cliente
                    if( registro.idEstado == 2 ){
                        $("#Representantes").val("1");
                        if ( registro.ConOrden === 1 ){
                            $(".columna_emp").css("display","block");
                        }else{
                            $(".columna_emp").css("display","none");
                        }
                    }
                    if( registro.idEstado == 12 ){
                        $("#proveedores").val("1");
                        if ( registro.ConOrden === 1 ){
                            $(".columna_cli").css("display","block");
                        }else{

                            $(".columna_cli").css("display","none");
                        }
                    }        
                    $("#Cantidad_Firmantes").val('n'); 
                    $("#idWF").val(registro.idWF);                               
                }); 
            }else{
                $("#mensajeError").removeClass("callout callout-warning");
                $("#mensajeError").html("");
            }
        }
    }
}
  
function mostrarFirmantes_RLIP(){
    var Representantes = $("#Representantes").val();
    if( Representantes != '' ){
        $("#icono_representante").show();
        $("#label").show();
    }
    if( $("#proveedores").val() != '' ){
        $("#icono_proveedores").show();
    }
    //$("#Representantes").val('');
    //$("#proveedores").val('');
}

$(".pdf64_RLIP").change(function(){
    $("#Documento").val($(".pdf64_RLIP").val());
    $("#Documento").attr("disabled",true);//deshabilita texto de la subida del documento
    var cantidad_proveedores = $('.f_cli:checked').length; 
    var cantidad_firmantes = $('.f_emp:checked').length; 
    if ( $("#mensajeError").html() == '' || num_RLIP > 0 )	mostrarFirmantes_RLIP(); 
    if ( cantidad_proveedores > 0 && cantidad_firmantes > 0 ){
         $("#GENERAR").attr('disabled', false);
    }
});

function Checkfiles_RLIP()
   {	
    var fup = document.getElementById('pdf64');
    var fileName = fup.value;
    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
    if(ext == "pdf" || ext == "PDF")
    {
        return true;
    } 
    else
    {
        alert("Solo se pueden seleccionar archivos pdf !");
        fup.focus();
        return false;	
    }
}

/**************************************/
/**SELECCION DE FIRMANTES POR EMPRESA**/
/**************************************/
function seleccion_RLIP(id){
    var cantidad_proveedores = $('.f_cli:checked').length; 
    var cantidad_firmantes = $('.f_emp:checked').length; 
    var max_firmantes = $("#max").val();
    var cant = cantidad_firmantes + cantidad_proveedores; 
    if ( cantidad_firmantes == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar al menos un Firmante, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
        $("#GENERAR").attr('disabled', true);						   
        $('.f_emp').prop("checked",false);
        $(".orden_emp").val('');
        return false;
    }else{
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        if( $('.f_cli:checked').length > 0 ){
            if ( max_firmantes != 0 ){
                if ( cant > max_firmantes ){
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La cantidad de firmantes por Documento no debe ser mayor a " + max_firmantes );
                $("#GENERAR").attr('disabled', true);
                return false;
                }else{
                    $("#GENERAR").attr('disabled', false);
                }
            }else{
                $("#GENERAR").attr('disabled', false);
            }
        }else{
            if( cant > 0 && ($("#proveedores").val() != '1' || $("#proveedores").val() === 'n')){ //Si el flujo no tiene proveedores
                $("#GENERAR").attr('disabled',false);
            }
            else{
                $("#GENERAR").attr('disabled', true);
            }
            //return false;
        }
        //Colocar orden 
        var rutempresa_RLIP = $("#emp_" + id).val();
        if($('#emp_'+ id).is(':checked')){
            $("#orden_emp_" + rutempresa_RLIP ).val(cantidad_firmantes);	   
        }else{
            //if( $("#Representantes_ConOrden").val() == 1 ){
            //cantidad_firmantes = $('.f_emp:checked').length; 
            //Actualizar arreglo_RLIP
            convertirArreglo_emp_RLIP();
            $("#orden_emp_" + rutempresa_RLIP ).val('');
            cantidad_firmantes = $('.f_emp:checked').length; 
            if ( cantidad_firmantes == 1 ){
                if( confirm(' Desea reiniciar el orden de los firmantes ?')){
                    //Si 
                    $(".f_emp").prop("checked",false);
                    $(".orden_emp").val('');
                    $("#GENERAR").attr('disabled', true);
                }else{	
                    for( i = 1; i < ($("#tabla_emp tr").length + 1) ; i++ ){
                        rutempresa_RLIP = $("#emp_" + i).val();
                        var orden_rc = $("#orden_emp_" + rutempresa).val(); 
                        if ( orden_rc != '' ) {
                            $("#orden_emp_" + rutempresa_RLIP ).val('1');
                        }
                    }
                }
            }
            if ( cantidad_firmantes > 1 ){
                if( confirm(' Desea reiniciar el orden de los firmantes ?')){
                    //Si 
                    $(".f_emp").prop("checked",false);
                    $(".orden_emp").val('');
                    $("#GENERAR").attr('disabled', true);
                }else{
                    convertirArreglo_emp_RLIP();
                    nuevo_emp_RLIP = convertirItem_RLIP(arreglo_emp_RLIP, orden_emp_RLIP);
                    var items = nuevo_emp_RLIP;
                    items.sort(function (a, b) {
                    if (a.orden > b.orden) {
                        return 1;
                    }
                    if (a.orden < b.orden) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                    });
                    reasignarOrden_RLIP(items,'emp_');
                }
            }else{
                $("#orden_emp_" + rutempresa_RLIP ).val('');
            }
        }
    }
}
  
/****************************/
/**OPERACIONES DE SELECCION**/
/****************************/
function reasignarOrden_RLIP(items,emp){
    for ( i = 0; i < items.length ; i++ ){
        var rut = $("#" + items[i].id).val(); 
        if( emp != '' )
            $("#orden_" + emp + rut).val(i+1);
        else
            $("#orden_" + rut).val(i+1);
    }
}

function convertirArreglo_RLIP(){
    arreglo_RLIP = [];
    var rut = '';
    var orden_id = '';
    $(".f_cli").each(function (index) {  
        if( $(this).is(':checked') ){
            rut = this.id;
            orden_id = $("#orden_"+ $("#" + this.id).val()).val();
            arreglo_RLIP.push(this.id);
            orden.push(orden_id);
        }
    });
}

function convertirArreglo_emp_RLIP(){
    arreglo_emp_RLIP = [];
    orden_emp_RLIP = [];
    var rut = '';
    var orden_id = '';
    $(".f_emp").each(function (index) {  
        if( $(this).is(':checked') ){
            rut = this.id;
            orden_id = $("#orden_emp_" + $("#" + this.id).val()).val();
            arreglo_emp_RLIP.push(this.id);
            orden_emp_RLIP.push(orden_id);
        }
    });
}

function convertirItem_RLIP(arreglo1,arreglo2){
    var data = new Object();
    var array = new Array();
    $.each(arreglo1, function (ind, elem){ 
        var data = new Object();
        data.id = elem;
        data.orden = arreglo2[ind];
        array[ind] = data;
    }); 	
    return array;
}

/**************************************/
/**SELECCION DE FIRMANTES POR CLIENTE**/
/**************************************/
function seleccion_cli_RLIP(id){
    var cantidad_proveedores = $(".f_cli:checked").length;
    var cantidad_firmantes = $(".f_emp:checked").length;
    var max_firmantes = $("#max").val();
    var cant = cantidad_firmantes + cantidad_proveedores;
    //Si el flujo tiene Cliente la cantidad de firmantes sera n
    if ( cantidad_proveedores == 0 ){
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar al menos un Firmante por Proveedor, seg&uacute;n el flujo de firma de la Plantilla seleccionada" );
        $("#GENERAR").attr("disabled", true);
        $(".f_cli").prop("checked",false);
        $(".orden").val('');
        return false;
    }else{
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");
        //Activar o Desactivar el boton de Generar
        if( $('.f_emp:checked').length > 0 ){
            if( max_firmantes != 0 ){
                if ( cant > max_firmantes ){
                $("#mensajeError").addClass("callout callout-warning");
                $("#mensajeError").html("La cantidad de firmantes por Documento no debe ser mayor a " + max_firmantes);
                $("#GENERAR").attr('disabled', true);
                return false;
                }else{
                    $("#GENERAR").attr('disabled', false);
                }
            }else{
                $("#GENERAR").attr('disabled', false);
            }
        }else if( $("#Representantes").val() == ""  ){
            $("#GENERAR").attr('disabled', false);
        }else{
            $("#GENERAR").attr('disabled', true);
            //return false;
        }
        //Colocar orden 
        var RutProveedor = $("#cli_" + id).val();
        if($('#cli_'+ id).is(':checked')){
            $("#orden_" + RutProveedor ).val(cantidad_proveedores);
        }else{
            //Actualizar arreglo_RLIP
            convertirArreglo_RLIP();
            $("#orden_" + RutProveedor ).val('');
            cantidad_proveedores = $(".f_cli:checked").length;
            if ( cantidad_proveedores == 1 ){
                if( confirm(' Desea reiniciar el orden de los firmantes ?')){
                    //Si 
                    $(".f_cli").prop("checked",false);
                    $(".orden").val('');
                    $("#GENERAR").attr('disabled', true);
                }else{	
                    for( i = 1; i < ($("#tabla_cli tr").length + 1) ; i++ ){
                        RutProveedor = $("#cli_" + i).val();
                        var orden_rc = $("#orden_" + RutProveedor).val();
                        if ( orden_rc != '' ) {
                            $("#orden_" + RutProveedor ).val('1');
                        }
                    }
                }
            }
            if ( cantidad_proveedores > 1 ){
                if( confirm(' Desea reiniciar el orden de los firmantes ?')){
                    //Si 
                    $(".f_cli").prop("checked",false);
                    $(".orden").val('');
                    $("#GENERAR").attr('disabled', true);
                }else{
                    convertirArreglo_RLIP();
                    nuevo_RLIP = convertirItem_RLIP(arreglo_RLIP, orden);
                    var items = nuevo_RLIP;
                    items.sort(function (a, b) {
                    if (a.orden > b.orden) {
                        return 1;
                    }
                    if (a.orden < b.orden) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                    });
                    reasignarOrden_RLIP(items,'');
                }
            }else{
                $("#orden_" + RutProveedor ).val('');
            }
        }
    }
} 
  
$(".close").click(function(){
    if( $(".RutProveedor_RLIP").val() == '' ){
        $(".FechaDocumento_RLIP").attr("disabled",true);
        $("#mensajeError").html("Debe seleccionar un Cliente");
        $("#mensajeError").addClass("callout callout-warning");
    }
    else{
        $(".FechaDocumento_RLIP").attr("disabled",false);
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
    }
});
  
$(".cerrar_proveedores_RLIP").click(function(){
    if( $(".RutProveedor_RLIP").val() == '' ){
        $(".FechaDocumento_RLIP").attr("disabled",true);
        $("#mensajeError").html("Debe seleccionar un Cliente");
        $("#mensajeError").addClass("callout callout-warning");
    }
    else{
        $(".FechaDocumento_RLIP").attr("disabled",false);
        $(".FechaDocumento_RLIP").val('');
        $(".idTipoDoc_RLIP").attr("disabled",true);
        //$(".idTipoDoc_RLIP").val(0);
        $(".idProceso_RLIP").attr("disabled",true);
        $(".idProceso_RLIP").val(0);
        $(".idPlantilla_RLIP").attr("disabled",true);
        $(".idPlantilla_RLIP").val(0);
        //para deshabilitar la subida del pdf
        $(".pdf64_RLIP").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        //fin
        //Ocultar firmantes 
        $("#icono_representante").hide();
        $("#icono_proveedores").hide();
        $("#label").hide();
        $("#step-1").collapse('hide');
        $("#step-2").collapse('hide');
        $("#mensajeError").html("");
        $("#mensajeError").removeClass("callout callout-warning");
    }
});

$(".idCentroCosto_RLIP").change(function(){
    var cc = $(".idCentroCosto_RLIP").val();
    if( cc == 0 )
    {
        $(".pdf64_RLIP").attr("disabled",true);
        $(".btn-file").attr("disabled",true);
        $("#Documento").val('');
    }
    else
    {
        $(".pdf64_RLIP").attr("disabled",false);
        $(".btn-file").attr("disabled",false);
    }
});
  
//nuevo///////////////////////////////////////////////
function validargenerar_RLIP()
{
    var cc = $(".idCentroCosto_RLIP").val();
    if ( cc == 0)
    {
        alert ("Error, Debe Ingresar Sub Area!");
        return false;	
    }
}
  
$(".RutProveedor_RLIP").focusout(function(){
    resultado = ajax_buscar_proveedor( $(".RutProveedor_RLIP").val() );
    if (resultado != "ERROR")
    {
        $("#NombreProveedor").val(resultado.NombreProveedor); 
        $(".FechaDocumento_RLIP").attr("disabled",false);
    }
    var rut = $(".RutProveedor_RLIP").val();
    var nom = $("#NombreProveedor").val();
    if ( nom.trim() == '' && rut.trim() != '' )
    {	
        ventanax("Proveedor no registrado","error");
    }
});
  
$(".rutempresa_RLIP_m").focusout(function(){
    resultado = ajax_buscar_proveedor( $(".rutempresa_RLIP_m").val() );
    if (resultado != 'ERROR' && resultado != '')
    {
        $("#razonsocial_m").val(resultado.NombreProveedor); 
        $("#direccion_m").val(resultado.Direccion); 
        $("#comuna_m").val(resultado.Comuna); 
        $("#ciudad_m").val(resultado.Ciudad); 
    }
});
  
var existefirmante = '';
$("#rutrepresentate_fp").focusout(function(){
    resultado = ajax_buscar_proveedor_firmante( $(".rutempresa_RLIP_fp").val(), $("#rutrepresentate_fp").val() );
    if (resultado != 'ERROR' && resultado != '')
    {	
        $("#nacionalidad_fp").val(resultado.nacionalidad); 
        $("#nombre_fp").val(resultado.nombre); 
        $("#paterno_fp").val(resultado.appaterno); 
        $("#materno_fp").val(resultado.apmaterno); 
        $("#idFirma option[value='"+ resultado.idFirma +"']").attr("selected",true);
        $("#email").val(resultado.correo); 
        $("#cargo_fp").val(resultado.cargo); 
        existefirmante = 'S';
    }		
});

function graba_firmante_proveedor()
{
    resultado = ajax_graba_firmante_proveedor( $("#nacionalidad_fp").val(),$("#nombre_fp").val(),$("#paterno_fp").val(),$("#materno_fp").val(),$("#email").val(),$(".rutempresa_RLIP_fp").val(),$("#rutrepresentate_fp").val(),$("#idFirma").val(),$("#cargo_fp").val() )
    if (resultado != "ERROR")
    {	
        if (existefirmante == '')
        {
            var nfilas = $("#tabla_cli tr").length;
            nfilas = nfilas + 1;
            fila_orden = "<tr align='center' class='fila_cl'><td ><input type='checkbox' name='Firmantes_Cli[]' class='f_cli' value='" + $("#rutrepresentate_fp").val() + "'id='cli_" + nfilas + "'  onclick='seleccion_cli_RLIP(" + nfilas + ");'/> </td><td>" + $("#rutrepresentate_fp").val() + "</td><td>" +  $("#nombre_fp").val() + ' ' + $("#paterno_fp").val() + ' ' + $("#materno_fp").val() +"</td><td class='columna_cli'><input name='orden_" + $("#rutrepresentate_fp").val() + "'' id='orden_" + $("#rutrepresentate_fp").val() + "' class='orden' style='text-align:center; border: none;' readonly /></td></tr>";
            $("#tabla_cli tr:last ").after(fila_orden);  
        }
        resultado = ajax_asigna_rol_firma( $("#rutrepresentate_fp").val() , 'CLIENTE' )	
        if (resultado != "ERROR")
        {
            $('#modal_creafirmanteproveedor').modal('hide');
            $("#nacionalidad_fp").val('');
            $("#nombre_fp").val('');
            $("#paterno_fp").val('');
            $("#materno_fp").val('');
            $("#email").val('');
            $(".rutempresa_RLIP_fp").val('')
            $("#rutrepresentate_fp").val('');
            $("#cargo_fp").val('');
        }
    }
}	
  
function graba_proveedor_RLIP()
{
    resultado = ajax_graba_proveedor( $(".rutempresa_RLIP_m").val(), $("#razonsocial_m").val(), $("#direccion_m").val(), $("#comuna_m").val(), $("#ciudad_m").val() )
    if (resultado != "ERROR")
    {
        var rut = $(".rutempresa_RLIP_m").val();
        var nom = $("#razonsocial_m").val();
        $(".RutProveedor_RLIP").val(rut);
        $("#NombreProveedor").val(nom);
        $('#modal_creaproveedor').modal('hide');
        $(".rutempresa_RLIP_m").val(''); 
        $("#razonsocial_m").val(''); 
        $("#direccion_m").val(''); 
        $("#comuna_m").val(''); 
        $("#ciudad_m").val(''); 
        $(".FechaDocumento_RLIP").attr("disabled",false);		
    }		
    else
    {
        $("#NombreProveedor").val(resultado.NombreProveedor); 
        $(".FechaDocumento_RLIP").attr("disabled",false);	
    }
}
  
//para ir al mantenedor de proveedores
function mantenedor_RLIP()
{
    rut = $(".RutEmpresa_RLIP").val();
    $(".RutEmpresa_RLIPdin").val(rut);
    formproveedor.submit();
}
  
//para agregar el rut del  proveedor al popup de ingreso de proveeedor
function inicio_firmate_proveedor_RLIP()
{
    var rut = $(".RutProveedor_RLIP").val();
    $(".rutempresa_RLIP_fp").val(rut);
}
  
//Botón de empresa
$('#boton_emp').click(function(){
    if( $("#step-2").hasClass('in') ){
        $("#step-2").removeClass('in');
    }
});

//Botón de cliente/accionista
$('#boton_cli').click(function(){
    if( $("#step-1").hasClass('in') ){
        $("#step-1").removeClass('in');
    }
});

/*templates\empresas_FormularioModificar.html*/
function preguntarPorBorrar_1() {
    return confirm("Esta seguro que desea eliminar este Representante?, recuerde que lo eliminar también de los Centros de Costo a los que este asociado");
}


$(".btnFirmantes_e").click(function () {
    let respuesta = $("#collapseExample").hasClass("active");

    if (respuesta === false) {
        $("#collapseExample").addClass("active show");
    } else {
        $("#collapseExample").removeClass("active show");
    }
})

/*templates\revisionActor1_listado.html*/
function InicioRevisorActor1(){
      
    if( $("#idDocumento").val() != '' ) 	$(".idDocumento_filtro").val($("#idDocumento").val()); 
    //if( $("#RutCreador").val() != '' ) 		$(".RutCreador_filtro").val($("#RutCreador").val()); 
    //if( $("#NombreCreador").val() != '' ) 	$(".NombreCreador_filtro").val($("#NombreCreador").val()); 
    //if( $("#idPlantilla").val() != '' ) 	$(".idPlantilla_filtro").val($("#idPlantilla").val());
    //if( $("#Descripcion_Pl").val() != '' ) 	$(".Descripcion_Pl_filtro").val($("#Descripcion_Pl").val()); 
    //if( $("#RutEmpresa").val() != '' ) 		$(".RutEmpresa_filtro").val($("#RutEmpresa").val()); 
    //if( $("#centrocostoid").val() != '' ) 	$(".centrocostoid_filtro").val($("#centrocostoid").val()); 
    //if( $("#nombrecentrocosto").val() != '') $(".nombrecentrocosto_filtro").val($("#nombrecentrocosto").val()); 
    //if( $("#NombreCasino").val() != '' ) 	$(".NombreCasino_filtro").val($("#NombreCasino").val()); 
    //if( $("#CodCargo").val() != '' ) 		$(".CodCargo_filtro").val($("#CodCargo").val()); 
    //if( $("#Cargo").val() != '' ) 			$(".Cargo_filtro").val($("#Cargo").val()); 
    if( $("#RutEmpleado").val() != '' ) 	$(".RutEmpleado_filtro").val($("#RutEmpleado").val()); 
    if( $("#NombreEmpleado").val() != '' ) 	$(".NombreEmpleado_filtro").val($("#NombreEmpleado").val()); 
    //if( $("#idEstado").val() != 0 ) 	 	$(".idEstado_filtro").val($("#idEstado").val());
    //if( $("#idTipoFirma").val() != 0 ) 		$(".idTipoFirma_filtro").val($("#idTipoFirma").val()); 
    //if( $("#idProceso").val() != 0 ) 		$(".idProceso_filtro").val($("#idProceso").val()); 
    if( $("#	Inicio").val() != '' ) 	$(".	Inicio_filtro").val($("#	Inicio").val()); 
    if( $("#	Fin").val() != '' ) 		$(".	Fin_filtro").val($("#	Fin").val()); 
    //if( $("#Enviado").val() != '-1' ) 		$(".Enviado_filtro").val($("#Enviado").val()); 
    if( $("#idEstadoGestion").val() != 0 ) $(".idEstadoGestion_filtro").val($("#idEstadoGestion").val()); 
    
};

/*templates\revisionAsignados_listado.html*/
;
    function cambioEstado(empleadoFormularioid, estadoFormularioid){
        var url  = "revisionAsignados_ajax.php";
        var parametros = "empleadoFormularioid=" + empleadoFormularioid + '&estadoFormularioid=' + estadoFormularioid;
        // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
        if( window.XMLHttpRequest )
            ajax_cambioestado = new XMLHttpRequest(); // No Internet Explorer
        else
            ajax_cambioestado = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
        // Almacenamos en el control al funcion que se invocara cuando la peticion
        // cambie de estado 
        ajax_cambioestado.onreadystatechange = funcionCallback_cambioEstado;
        // Enviamos la peticion
        ajax_cambioestado.open( "POST", url, false );
        ajax_cambioestado.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax_cambioestado.send(parametros);
    }
	function funcionCallback_cambioEstado() {
		if( ajax_cambioestado.readyState == 4 ) {
			if( ajax_cambioestado.status == 200 ) {
				salida_cambioestado = ajax_cambioestado.responseText;
				if( salida_cambioestado != '' ) {
					/*datosDocumento = JSON.parse(salida_cambioestado);
					for(var i = 0; i < datosDocumento.length; i++) {
						$('#' + datosDocumento[i].Variable).show();
					}*/
                    $('#formulario_usuarios').submit();
				}
				else {
					$("#mensajeError").removeClass("callout callout-warning");
					$("#mensajeError").html("");
				}
			}
		}
	} 

  
  function InicioRevisionAsignados(){
      
      if( $("#RutEmpleado").val() != '' ) 	$(".RutEmpleado_filtro").val($("#RutEmpleado").val()); 
      if( $("#NombreEmpleado").val() != '' ) 	$(".NombreEmpleado_filtro").val($("#NombreEmpleado").val()); 
      if( $("#idEstadoGestion").val() != 0 ) $(".idEstadoGestion_filtro").val($("#idEstadoGestion").val()); 
  };

  /*templates\verificaridentidad_Listado.html*/
let general_VerificacionId = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};


let popupPage_VerificacionId;
let openCheckId_VerificacionId = function (action) {
    general_VerificacionId.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_VerificacionId = window.open("checkid/index.html?v12", "libPage", opciones);
    // Puts focus on the popupPage_VerificacionId
    if (window.focus) {
        popupPage_VerificacionId.focus();
    }
};


let getParamsVerify_VerificacionId = function () {
    return {
        action: "VERIFY",
        method: general_VerificacionId.method,
        apiKey: general_VerificacionId.apiKey,
        sessionId: general_VerificacionId.session.id,
        companyId: general_VerificacionId.session.companyId,
        operationId: 1,
        baseUrl: general_VerificacionId.baseUrl,
        useFingerprint: true,//si pide huella al que esta enrolando
        usePin: false,//si pide pin al que esta enrolando
        pinRestore: true,//cambio de pin
        pinRestoreMethod: 'ACEP',
        operator: {
            useFingerprint: true,
            usePin: false,
            pinRestore: true,//cambio de pin
            pinRestoreMethod: 'ACEP',
            sessionId: general_VerificacionId.operator.sessionId,
            identityDocument: {
                countryCode: general_VerificacionId.countryCode,
                type: general_VerificacionId.type,
                personalNumber: general_VerificacionId.rutoperador
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_VerificacionId.countryCode,
                type: general_VerificacionId.type,
                personalNumber: general_VerificacionId.rutaverificar
            }]
        }
    };
};


window.getParams = function () {
    return getParamsVerify_VerificacionId();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_VerificacionId.close();
    console.log("callback", result);

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_VerificacionId.lastVerify = result.data;

                //Limpiar campos
                document.getElementById('inVerifyDocPersonalNumber').value = '';
                document.getElementById('inVerifyType').value = 0;
            }
        }
    } else {
        alert(result.numError + " " + result.msError);
    }

    return false;
}

function verificar_VerificacionId() {
    var rut_usuario = $("#inVerifyDocPersonalNumber").val();
    var tipo = $("#inVerifyType").val();

    if (rut_usuario.length == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
        return false;
    }
    if (tipo == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Debe seleccionar el tipo de firma");
        return false;
    }

    $("#mensajeError").removeClass("callout callout-warning");
    $("#mensajeError").html("");

    if ((validaRut2(document.formulario.inVerifyDocPersonalNumber)) && (document.getElementById("inVerifyType").value != 0)) {
        consulta_sesion_VerificacionId();
    }
}

var conexion_VerificacionId;

function consulta_sesion_VerificacionId() {
    conexion_VerificacionId = crearXMLHttpRequest();

    conexion_VerificacionId.open('POST', 'consulta_sesion.php', false);

    conexion_VerificacionId.send(null);
    // Devolvemos el resultado
    respuesta = conexion_VerificacionId.responseText;

    arr_resp = respuesta.split('|');
    if (arr_resp[0] == 'ok') {
        general_VerificacionId.baseUrl = arr_resp[1];
        general_VerificacionId.session.companyId = arr_resp[2];
        general_VerificacionId.username = arr_resp[4];
        general_VerificacionId.session.id = arr_resp[6];
        general_VerificacionId.countryCode = arr_resp[7];
        general_VerificacionId.type = arr_resp[8];
        general_VerificacionId.apiKey = arr_resp[9];
        general_VerificacionId.rutoperador = arr_resp[10];

        document.getElementById('sessionid').value = general_VerificacionId.session.id; //token de la sesion

        var rut = document.getElementById('inVerifyDocPersonalNumber').value;
        general_VerificacionId.rutaverificar = rut.replace("-", "");

        var tipo = document.getElementById('inVerifyType').value;

        if (tipo == 'ANY')
            tipo = '';

        general_VerificacionId.method = tipo;

        openCheckId_VerificacionId("VERIFY");
    }
    else {
        alert(respuesta);
    }

}

/*templates\rl_documentos_Pendientes_firma_rbk.html*/


var swpopup_RlDocsP = 0;			 
let general_RlDocsP = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "VERIFY",
     operator: {
       sessionId: null
     }
   };

 
 let popupPage_RlDocsP;
   let openCheckId = function(action) 
 {
     MostrarCargando();
     general_RlDocsP.currentAction = action;
     // Create popup window
     var w = 850;
     var h = 580;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_RlDocsP = window.open("checkid/index.html?v12", "libPage", opciones);

     var timer = setInterval(function() { 
       if(popupPage_RlDocsP.closed) {
         clearInterval(timer);
         if( swpopup_RlDocsP == 0 )
         {
             document.getElementById('cargando').style.display='none';
         }
       }
     }, 1000);
     
     // Puts focus on the popupPage_RlDocsP
     if (window.focus) {
       popupPage_RlDocsP.focus();
     }
   };


   let getParamsVerify_RlDocsP = function() {
     return {
       action: "VERIFY",
       method: general_RlDocsP.method,
       apiKey: general_RlDocsP.apiKey,
       sessionId: general_RlDocsP.session.id,
       companyId: general_RlDocsP.session.companyId,
       operationId: 1,
       baseUrl: general_RlDocsP.baseUrl,
     pinRestore: true,//cambio de pin
     pinRestoreMethod:'ACEP',//pide nro documento
       operator: {
         sessionId: general_RlDocsP.operator.sessionId,
         identityDocument: {
             countryCode: general_RlDocsP.countryCode,
             type: general_RlDocsP.type,
             personalNumber: general_RlDocsP.rutaverificar
         }
       },
       digitalIdentity: {
         identityDocuments: [{
           countryCode: general_RlDocsP.countryCode,
           type: general_RlDocsP.type,
           personalNumber: general_RlDocsP.rutaverificar
         }]
       }
       };
   };
 
//respuesta del formulario checkid
window.callback = function(result) 
{	//console.log("callback", result);
 if( $("#accion_enrolar").val() === 'SI'){
     popupPage_enrolar_RlDocsP.close();
     document.getElementById("formulario3").submit();	
     console.log("callback", result);
     //document.getElementById('cargandof').style.display='none';
     OcultarCargando();
     if (result.numError == 0) 
     {
         //alert ("OK")
          //document.getElementById("formulario3").submit();	
     }
     else
     {
         
         alert (result.numError +  " " + result.msError);
         //document.getElementById('cargandof').style.display='none';
         OcultarCargando();
     }

     return false;
 }
 else
 {
     swpopup_RlDocsP = 1;	 
     popupPage_RlDocsP.close();
     console.log("callback", result);
     //document.getElementById('cargando').style.display='none';

     if (result.numError == 0) 
     {
       if (result.action == "VERIFY") 
       {	
         try{	
                 let resultMessage = "Validación Errónea";
                 if (result.data.authenticated)
                 {
                   resultMessage = "Validación Exitosa!";
               
                   general_RlDocsP.lastVerify = result.data;
           
                   document.getElementById('id').value       = general_RlDocsP.lastVerify.id;
                   document.getElementById('type').value       = general_RlDocsP.lastVerify.type;
                   document.getElementById('subtype').value      = general_RlDocsP.lastVerify.subtype;
                   document.getElementById('authenticated').value  = general_RlDocsP.lastVerify.authenticated;
                   document.getElementById('sign').value       = general_RlDocsP.lastVerify.sign;      
                   document.getElementById("formulario").submit();
                 
                 }
                 else
                 {	
                     document.getElementById('cargando').style.display='none';
                 }
             }catch(err){
                 //alert(err);
                 document.getElementById('cargando').style.display='none';
             }
     
         }
     }
     
     if (result.numError == 0) 
     {
         //alert ("OK")
     }
     else
     {
         alert (result.numError +  " " + result.msError);
         document.getElementById('cargando').style.display='none';
     }

     return false;
 }
}

function verificar_RlDocsP()
{ 
 //consulta_sesion_RlDocsP();
 consultar_usuario_RlDocsP();
}

var conexion_RlDocsP;
   
function consulta_sesion_enrolar()
{
 conexion_RlDocsP=crearXMLHttpRequest();

 conexion_RlDocsP.open('POST', 'consulta_sesion.php', false);

 conexion_RlDocsP.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_RlDocsP.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
     //Datos para enrolar
     general_enrolar_RlDocsP.baseUrl 			= arr_resp[1];
     general_enrolar_RlDocsP.session.companyId 	= arr_resp[2];
     general_enrolar_RlDocsP.username 			= arr_resp[4];
     general_enrolar_RlDocsP.session.id 			= arr_resp[6];
     general_enrolar_RlDocsP.countryCode 		= arr_resp[7];
     general_enrolar_RlDocsP.type 				= arr_resp[8];
     general_enrolar_RlDocsP.apiKey 				= arr_resp[9];
     
     
     var rut						= $("#RutUsuario_f").val();
     general_enrolar_RlDocsP.rutoperador			= rut.replace ("-",""); 
     
     var rut						= $("#RutUsuario_f").val();
     general_enrolar_RlDocsP.rutaenrolar			= rut.replace ("-",""); 
     
         
     openCheckId_enrolar_RlDocsP("CREATE");			
 }
 else
 {
   alert (respuesta);
 }
 
}

function consulta_sesion_RlDocsP()
{
 conexion_RlDocsP=crearXMLHttpRequest();

 conexion_RlDocsP.open('POST', 'consulta_sesion.php', false);

 conexion_RlDocsP.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_RlDocsP.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
   general_RlDocsP.baseUrl       = arr_resp[1];
   general_RlDocsP.session.companyId   = arr_resp[2];
   general_RlDocsP.username      = arr_resp[4];
   general_RlDocsP.session.id      = arr_resp[6];
   general_RlDocsP.countryCode     = arr_resp[7];
   general_RlDocsP.type        = arr_resp[8];
   general_RlDocsP.apiKey        = arr_resp[9];

   document.getElementById('sessionid').value =   general_RlDocsP.session.id; //token de la sesion

   var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
   general_RlDocsP.rutaverificar   = rut.replace ("-","");
   general_RlDocsP.method        = document.getElementById('inVerifyType').value;

   openCheckId("VERIFY");
 }
 else
 {
   alert (respuesta);
 }
 
}

function consultar_usuario_RlDocsP()
{
 conexion_RlDocsP=crearXMLHttpRequest();
 var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
 rut  = rut.replace ("-","");
 var url = 'consulta_usuario.php';
 var parametros  ='personalNumber=' + rut;

 conexion_RlDocsP.open('POST',url, false);

 conexion_RlDocsP.send(parametros);
 // Devolvemos el resultado
 respuesta =  conexion_RlDocsP.responseText;  

 var datos = JSON.parse(respuesta); 

 if( respuesta.length > 5 ){
 
     $("#mensajeError").removeClass("callout callout-wauning");
     $("#mensajeError").html("");
     consulta_sesion_RlDocsP();
 }
 else
 {
   $("#respuesta").css("display","none");
   $("#huella").html("");
   $("#pin").html("");
   
 /*  $("#mensajeError").addClass("callout callout-warning");
   $("#mensajeError").html("El usuario no esta enrolado, <a onclick='enrolarse_RlDocsP()'>haga clic aqu&iacute;</a> para enrolarse");*/
   
   var mensaje = "El usuario no esta enrolado, " + "<a onclick='enrolarse_RlDocsP()'>haga clic aqu&iacute;</a>" + " para enrolarse";
   ventanax(mensaje,'error')
   
 }
}

 let general_enrolar_RlDocsP = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "CREATE",
     operator: {
       sessionId: null
     }
   };

   let popupPage_enrolar_RlDocsP;
   let openCheckId_enrolar_RlDocsP = function(action) 
   {
     general_enrolar_RlDocsP.currentAction = action;
     // Create popup window
     var w = 900;
     var h = 620;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_enrolar_RlDocsP = window.open("checkid/index.html?v12", "libPage", opciones);
     
 
     var timer_enrolar = setInterval(function() { 
         if(popupPage_enrolar_RlDocsP.closed) {
             clearInterval(timer_enrolar);
             //document.getElementById('cargandof').style.display='none';
         }
     }, 1000);
 
     
     // Puts focus on the popupPage_enrolar_RlDocsP
     if (window.focus) {
       popupPage_enrolar_RlDocsP.focus();
     }
   };

   let getParamsCreate_RlDocsP = function() {
     return {
         action: "CREATE",
           apiKey: general_enrolar_RlDocsP.apiKey,
           sessionId: general_enrolar_RlDocsP.session.id,
           companyId: general_enrolar_RlDocsP.session.companyId,
           operationId: 1,
           baseUrl: general_enrolar_RlDocsP.baseUrl,
           useFingerprint: false,//si pide huella al que esta enrolando
           usePin: true,//si pide pin al que esta enrolando
           pinRestore: false,//cambio de pin
           pinRestoreMethod:'ACEP',
           operator: {
           useFingerprint:false,
           usePin:true,
           sessionId: general_enrolar_RlDocsP.operator.sessionId,
           identityDocument: {
               countryCode: general_enrolar_RlDocsP.countryCode,
               type: general_enrolar_RlDocsP.type,
               personalNumber: general_enrolar_RlDocsP.rutoperador	
           }
         },
     
           digitalIdentity: {
               personalData: {
                   givenNames: '',
                   surnames: '',
                   dob: 20000101,
                   gender: "NOT_KNOWN"
               },
               emailAddresses: [
                 {
                     type: 'BUSINESS',
                     address: '',
                     primary: true
                 },
                 {
                     type: 'PERSONAL',
                     address: '' ,
                     primary: false
                 }
             ],
             contactPhones: [
                 {
                     number: '',
                     primary: true,
                     type: 'HOME'
                 },
                 {
                     number: '',
                     primary: false,
                     type: 'PERSONAL'
                 }

               ],
               identityDocuments: [{
                 countryCode: general_enrolar_RlDocsP.countryCode,
                 type: general_enrolar_RlDocsP.type,
                 personalNumber: general_enrolar_RlDocsP.rutaenrolar
               }]
           }
       };
   };

window.getParams = function()
{
 //return getParamsVerify_RlDocsP();
     if( $("#accion_enrolar").val() === 'SI' ){
         //$("#accion_enrolar").val('');
         return getParamsCreate_RlDocsP();
     }else{
          return getParamsVerify_RlDocsP();
 }
}
 function enrolarse_RlDocsP()
 {
      $('#myModalx').modal('hide');
     $("#accion_enrolar").val('SI');	
     consulta_sesion_enrolar();
     
     
 }


/*templates\rl_aprobarfirmar_firma.html*/

var swpopup_RlAprobarF = 0;			 
let general_RlAprobarF = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "VERIFY",
     operator: {
       sessionId: null
     }
   };

 
 let popupPage_RlAprobarF;
 let openCheckId_RlAprobarF = function(action) 
 {
     MostrarCargando();
     general_RlAprobarF.currentAction = action;
     // Create popup window
     var w = 850;
     var h = 580;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_RlAprobarF = window.open("checkid/index.html?v12", "libPage", opciones);

     var timer = setInterval(function() { 
       if(popupPage_RlAprobarF.closed) {
         clearInterval(timer);
         if( swpopup_RlAprobarF == 0 )
         {
             document.getElementById('cargando').style.display='none';
         }
       }
     }, 1000);
     
     // Puts focus on the popupPage_RlAprobarF
     if (window.focus) {
       popupPage_RlAprobarF.focus();
     }
   };


   let getParamsVerify_RlAprobarF = function() {
     return {
       action: "VERIFY",
       method: general_RlAprobarF.method,
       apiKey: general_RlAprobarF.apiKey,
       sessionId: general_RlAprobarF.session.id,
       companyId: general_RlAprobarF.session.companyId,
       operationId: 1,
       baseUrl: general_RlAprobarF.baseUrl,
     pinRestore: true,//cambio de pin
     pinRestoreMethod:'ACEP',//pide nro documento
       operator: {
         sessionId: general_RlAprobarF.operator.sessionId,
         identityDocument: {
             countryCode: general_RlAprobarF.countryCode,
             type: general_RlAprobarF.type,
             personalNumber: general_RlAprobarF.rutaverificar
         }
       },
       digitalIdentity: {
         identityDocuments: [{
           countryCode: general_RlAprobarF.countryCode,
           type: general_RlAprobarF.type,
           personalNumber: general_RlAprobarF.rutaverificar
         }]
       }
       };
   };
 
//respuesta del formulario checkid
window.callback = function(result) 
{	//console.log("callback", result);
 if( $("#accion_enrolar").val() === 'SI'){
     popupPage_enrolar_RlAprobarF.close();
     document.getElementById("formulario3").submit();	
     console.log("callback", result);
     //document.getElementById('cargandof').style.display='none';
     OcultarCargando();
     if (result.numError == 0) 
     {
         //alert ("OK")
          //document.getElementById("formulario3").submit();	
     }
     else
     {
         
         alert (result.numError +  " " + result.msError);
         //document.getElementById('cargandof').style.display='none';
         OcultarCargando();
     }

     return false;
 }
 else
 {
     swpopup_RlAprobarF = 1;	 
     popupPage_RlAprobarF.close();
     console.log("callback", result);
     //document.getElementById('cargando').style.display='none';

     if (result.numError == 0) 
     {
       if (result.action == "VERIFY") 
       {	
         try{	
                 let resultMessage = "Validación Errónea";
                 if (result.data.authenticated)
                 {
                   resultMessage = "Validación Exitosa!";
               
                   general_RlAprobarF.lastVerify = result.data;
           
                   document.getElementById('id').value       = general_RlAprobarF.lastVerify.id;
                   document.getElementById('type').value       = general_RlAprobarF.lastVerify.type;
                   document.getElementById('subtype').value      = general_RlAprobarF.lastVerify.subtype;
                   document.getElementById('authenticated').value  = general_RlAprobarF.lastVerify.authenticated;
                   document.getElementById('sign').value       = general_RlAprobarF.lastVerify.sign;      
                   document.getElementById("formulario").submit();
                 
                 }
                 else
                 {	
                     document.getElementById('cargando').style.display='none';
                 }
             }catch(err){
                 //alert(err);
                 document.getElementById('cargando').style.display='none';
             }
     
         }
     }
     
     if (result.numError == 0) 
     {
         //alert ("OK")
     }
     else
     {
         alert (result.numError +  " " + result.msError);
         document.getElementById('cargando').style.display='none';
     }

     return false;
 }
}
 
function verificar_RlAprobarF()
{  
   consultar_usuario_RlAprobarF();
}
 
var conexion_RlAprobarF;
   

function consulta_sesion_enrolar()
{
 conexion_RlAprobarF=crearXMLHttpRequest();

 conexion_RlAprobarF.open('POST', 'consulta_sesion.php', false);

 conexion_RlAprobarF.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_RlAprobarF.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
     //Datos para enrolar
     general_enrolar_RlAprobarF.baseUrl 			= arr_resp[1];
     general_enrolar_RlAprobarF.session.companyId 	= arr_resp[2];
     general_enrolar_RlAprobarF.username 			= arr_resp[4];
     general_enrolar_RlAprobarF.session.id 			= arr_resp[6];
     general_enrolar_RlAprobarF.countryCode 		= arr_resp[7];
     general_enrolar_RlAprobarF.type 				= arr_resp[8];
     general_enrolar_RlAprobarF.apiKey 				= arr_resp[9];
     
     
     var rut						= document.getElementById('RutUsuario_RlAprobarF').value;
     general_enrolar_RlAprobarF.rutoperador			= rut.replace ("-",""); 
     
     var rut						= document.getElementById('RutUsuario_RlAprobarF').value;
     general_enrolar_RlAprobarF.rutaenrolar			= rut.replace ("-",""); 
     
         
     openCheckId_enrolar_RlAprobarF("CREATE");			
 }
 else
 {
   alert (respuesta);
 }
 
}

function consulta_sesion_RlAprobarF()
{
 conexion_RlAprobarF=crearXMLHttpRequest();

 conexion_RlAprobarF.open('POST', 'consulta_sesion.php', false);

 conexion_RlAprobarF.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_RlAprobarF.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {

   general_RlAprobarF.baseUrl       = arr_resp[1];
   general_RlAprobarF.session.companyId   = arr_resp[2];
   general_RlAprobarF.username      = arr_resp[4];
   general_RlAprobarF.session.id      = arr_resp[6];
   general_RlAprobarF.countryCode     = arr_resp[7];
   general_RlAprobarF.type        = arr_resp[8];
   general_RlAprobarF.apiKey        = arr_resp[9];

  document.getElementById('sessionid').value =   general_RlAprobarF.session.id; //token de la sesion
  
  var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
  general_RlAprobarF.rutaverificar   = rut.replace ("-","");
  general_RlAprobarF.method        = document.getElementById('inVerifyType').value;

  openCheckId_RlAprobarF("VERIFY");
     
 }
 else
 {
   alert (respuesta);
 }
 
}

function consultar_usuario_RlAprobarF()
{
 conexion_RlAprobarF=crearXMLHttpRequest();
 var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
 rut  = rut.replace ("-","");
 var url = 'consulta_usuario.php';
 var parametros = 'personalNumber=' + rut;

 conexion_RlAprobarF.open('POST',url, false);

 conexion_RlAprobarF.send(parametros);
 // Devolvemos el resultado
 respuesta =  conexion_RlAprobarF.responseText;  

 var datos = JSON.parse(respuesta); 

 if( respuesta.length > 5 ){
 
     $("#mensajeError").removeClass("callout callout-wauning");
     $("#mensajeError").html("");
     consulta_sesion_RlAprobarF();
 }
 else
 {
   $("#respuesta").css("display","none");
   $("#huella").html("");
   $("#pin").html("");
   
   $("#mensajeError").addClass("callout callout-warning");
   $("#mensajeError").html("El usuario no esta enrolado, <a onclick='enrolarse_RlAprobarF()'>haga clic aqu&iacute;</a> para enrolarse");
   
 }
}

let general_enrolar_RlAprobarF = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "CREATE",
     operator: {
       sessionId: null
     }
   };

   
   let popupPage_enrolar_RlAprobarF;
   let openCheckId_enrolar_RlAprobarF = function(action) 
   {
     general_enrolar_RlAprobarF.currentAction = action;
     // Create popup window
     var w = 900;
     var h = 620;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_enrolar_RlAprobarF = window.open("checkid/index.html?v12", "libPage", opciones);
     
 
     var timer_enrolar = setInterval(function() { 
         if(popupPage_enrolar_RlAprobarF.closed) {
             clearInterval(timer_enrolar);
             //document.getElementById('cargandof').style.display='none';
         }
     }, 1000);
 
     
     // Puts focus on the popupPage_enrolar_RlAprobarF
     if (window.focus) {
       popupPage_enrolar_RlAprobarF.focus();
     }
   };

  
 let getParamsCreate_RlAprobarF = function() {
     return {
         action: "CREATE",
           apiKey: general_enrolar_RlAprobarF.apiKey,
           sessionId: general_enrolar_RlAprobarF.session.id,
           companyId: general_enrolar_RlAprobarF.session.companyId,
           operationId: 1,
           baseUrl: general_enrolar_RlAprobarF.baseUrl,
           useFingerprint: false,//si pide huella al que esta enrolando
           usePin: true,//si pide pin al que esta enrolando
           pinRestore: false,//cambio de pin
           pinRestoreMethod:'ACEP',
           operator: {
           useFingerprint:false,
           usePin:true,
           sessionId: general_enrolar_RlAprobarF.operator.sessionId,
           identityDocument: {
               countryCode: general_enrolar_RlAprobarF.countryCode,
               type: general_enrolar_RlAprobarF.type,
               personalNumber: general_enrolar_RlAprobarF.rutoperador	
           }
         },
     
           digitalIdentity: {
               personalData: {
                   givenNames: '',
                   surnames: '',
                   dob: 20000101,
                   gender: "NOT_KNOWN"
               },
               emailAddresses: [
                 {
                     type: 'BUSINESS',
                     address: '',
                     primary: true
                 },
                 {
                     type: 'PERSONAL',
                     address: '' ,
                     primary: false
                 }
             ],
             contactPhones: [
                 {
                     number: '',
                     primary: true,
                     type: 'HOME'
                 },
                 {
                     number: '',
                     primary: false,
                     type: 'PERSONAL'
                 }

               ],
               identityDocuments: [{
                 countryCode: general_enrolar_RlAprobarF.countryCode,
                 type: general_enrolar_RlAprobarF.type,
                 personalNumber: general_enrolar_RlAprobarF.rutaenrolar
               }]
           }
       };
   };


 window.getParams = function()
 { 
     if( $("#accion_enrolar").val() === 'SI' ){
         //$("#accion_enrolar").val('');
         return getParamsCreate_RlAprobarF();
     }else{
          return getParamsVerify_RlAprobarF();
     }
 }
         
 function enrolarse_RlAprobarF()
 {
     $("#accion_enrolar").val('SI');	
     consulta_sesion_enrolar();
 }

 /*templates\obtenerenrolado_Listado.html*/
let general_ObtenerEnrolado = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};


let popupPage_ObtenerEnrolado;
let _ObtenerEnrolado = function (action) {
    general_ObtenerEnrolado.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_ObtenerEnrolado = window.open("checkid", "libPage", opciones);
    // Puts focus on the popupPage_ObtenerEnrolado
    if (window.focus) {
        popupPage_ObtenerEnrolado.focus();
    }
};

var bUseKBA = false;
let getParamsVerify_ObtenerEnrolado = function () {
    return {
        action: "VERIFY",
        method: general_ObtenerEnrolado.method,
        apiKey: general_ObtenerEnrolado.apiKey,
        sessionId: general_ObtenerEnrolado.session.id,
        companyId: general_ObtenerEnrolado.session.companyId,
        operationId: 1,
        baseUrl: general_ObtenerEnrolado.baseUrl,
        useKBA: bUseKBA,
        operator: {
            sessionId: general_ObtenerEnrolado.operator.sessionId,
            identityDocument: {
                countryCode: general_ObtenerEnrolado.countryCode,
                type: general_ObtenerEnrolado.type,
                personalNumber: general_ObtenerEnrolado.rutoperador
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_ObtenerEnrolado.countryCode,
                type: general_ObtenerEnrolado.type,
                personalNumber: general_ObtenerEnrolado.rutaverificar
            }]
        }
    };
};


window.getParams = function () {
    return getParamsVerify_ObtenerEnrolado();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_ObtenerEnrolado.close();
    console.log("callback", result);

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_ObtenerEnrolado.lastVerify = result.data;

                //Limpiar campos
                document.getElementById('inVerifyDocPersonalNumber').value = '';
                document.getElementById('inVerifyType').value = 0;
            }
        }
    } else {
        alert(result.numError + " " + result.msError);
    }

    return false;
}

function verificar_ObtenerEnrolado() {
    var rut_usuario = $("#inVerifyDocPersonalNumber").val();

    if (rut_usuario.length == 0) {
        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("El campo rut no puede ser vac&iacute;o");
    } else {
        $("#mensajeError").removeClass("callout callout-warning");
        $("#mensajeError").html("");

        if (validaRut2(document.formulario.inVerifyDocPersonalNumber)) {
            consulta_sesion_ObtenerEnrolado();
        }
    }
}


var conexion_ObtenerEnrolado;

function consulta_sesion_ObtenerEnrolado() {
    conexion_ObtenerEnrolado = crearXMLHttpRequest();
    var rut = document.getElementById('inVerifyDocPersonalNumber').value;
    rut = rut.replace("-", "");
    var url = 'consulta_usuario.php';
    var parametros = 'personalNumber=' + rut;

    conexion_ObtenerEnrolado.open('POST', url, false);

    conexion_ObtenerEnrolado.send(parametros);
    // Devolvemos el resultado
    respuesta = conexion_ObtenerEnrolado.responseText;

    var datos = JSON.parse(respuesta);

    if (respuesta.length > 5) {

        var huella = '';
        var pin = '';

        if (datos.hasFingerprints) {
            huella = 'Si';
        } else {
            huella = 'No';
        }

        if (datos.hasPin) {
            pin = 'Si';
        } else {
            pin = 'No';
        }

        $("#respuesta").css("display", "block");
        $("#huella").html(huella);
        $("#pin").html(pin);

        $("#mensajeError").removeClass("callout callout-wauning");
        $("#mensajeError").html("");
    }
    else {
        $("#respuesta").css("display", "none");
        $("#huella").html("");
        $("#pin").html("");

        $("#mensajeError").addClass("callout callout-warning");
        $("#mensajeError").html("Este usuario no esta enrolado");

        //12634720-0
    }

}

/*templates\misdocumentos_firma_rbk.html*/
let general_MisDocs = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};

let popupPage_MisDocs;
let openCheckId_MisDocs = function (action) {
    MostrarCargando();
    general_MisDocs.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_MisDocs = window.open("checkid/", "libPage", opciones);

    var timer = setInterval(function () {
        if (popupPage_MisDocs.closed) {
            clearInterval(timer);
            document.getElementById('cargando').style.display = 'none';
        }
    }, 1000);

    // Puts focus on the popupPage_MisDocs
    if (window.focus) {
        popupPage_MisDocs.focus();
    }
};

let getParamsVerify_MisDocs = function () {
    return {
        action: "VERIFY",
        method: general_MisDocs.method,
        apiKey: general_MisDocs.apiKey,
        sessionId: general_MisDocs.session.id,
        companyId: general_MisDocs.session.companyId,
        operationId: 1,
        baseUrl: general_MisDocs.baseUrl,
        useFingerprint: true,//si pide huella al que esta enrolando
        usePin: true,//si pide pin al que esta enrolando
        useKBA: false,//si se utiliza la pregunta de seguridad
        operator: {
            sessionId: general_MisDocs.operator.sessionId,
            identityDocument: {
                countryCode: general_MisDocs.countryCode,
                type: general_MisDocs.type,
                personalNumber: general_MisDocs.rutaverificar
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_MisDocs.countryCode,
                type: general_MisDocs.type,
                personalNumber: general_MisDocs.rutaverificar
            }]
        }
    };
};

window.getParams = function () {
    return getParamsVerify_MisDocs();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_MisDocs.close();
    console.log("callback", result);
    document.getElementById('cargando').style.display = 'none';

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_MisDocs.lastVerify = result.data;

                document.getElementById('id').value = general_MisDocs.lastVerify.id;
                document.getElementById('type').value = general_MisDocs.lastVerify.type;
                document.getElementById('subtype').value = general_MisDocs.lastVerify.subtype;
                document.getElementById('authenticated').value = general_MisDocs.lastVerify.authenticated;
                document.getElementById('sign').value = general_MisDocs.lastVerify.sign;
                document.getElementById("formulario").submit();

            }
        }
    }

    if (result.numError == 0) {
        //alert ("OK")
    }
    else {
        alert(result.numError + " " + result.msError);
    }

    return false;
}

function verificar_MisDocs() {
    consulta_sesion_MisDocs();
}
var conexion_MisDocs;

function consulta_sesion_MisDocs() {
    conexion_MisDocs = crearXMLHttpRequest();

    conexion_MisDocs.open('POST', 'consulta_sesion.php', false);

    conexion_MisDocs.send(null);
    // Devolvemos el resultado
    respuesta = conexion_MisDocs.responseText;
    arr_resp = respuesta.split('|');
    if (arr_resp[0] == 'ok') {
        general_MisDocs.baseUrl = arr_resp[1];
        general_MisDocs.session.companyId = arr_resp[2];
        general_MisDocs.username = arr_resp[4];
        general_MisDocs.session.id = arr_resp[6];
        general_MisDocs.countryCode = arr_resp[7];
        general_MisDocs.type = arr_resp[8];
        general_MisDocs.apiKey = arr_resp[9];

        document.getElementById('sessionid').value = general_MisDocs.session.id; //token de la sesion

        var rut = document.getElementById('inVerifyDocPersonalNumber').value;
        general_MisDocs.rutaverificar = rut.replace("-", "");

        general_MisDocs.method = document.getElementById('inVerifyType').value;

        openCheckId_MisDocs("VERIFY");
    }
    else {
        alert(respuesta);
    }

}

/*templates\firma_rbk.html*/

let general_FirmaRBK = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};


let popupPage_FirmaRBK;
let openCheckId_FirmaRBK = function (action) {
    general_FirmaRBK.currentAction = action;
    // Create popup window
    var w = 850;
    var h = 580;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_FirmaRBK = window.open("checkid/checkid/", "libPage", opciones);
    // Puts focus on the popupPage_FirmaRBK
    if (window.focus) {
        popupPage_FirmaRBK.focus();
    }
};


let getParamsVerify_FirmaRBK = function () {
    return {
        action: "VERIFY",
        method: general_FirmaRBK.method,
        apiKey: general_FirmaRBK.apiKey,
        sessionId: general_FirmaRBK.session.id,
        companyId: general_FirmaRBK.session.companyId,
        operationId: 1,
        baseUrl: general_FirmaRBK.baseUrl,
        useFingerprint: true,//si pide huella al que esta enrolando
        usePin: true,//si pide pin al que esta enrolando
        useKBA: false,//si se utiliza la pregunta de seguridad
        operator: {
            sessionId: general_FirmaRBK.operator.sessionId,
            identityDocument: {
                countryCode: general_FirmaRBK.countryCode,
                type: general_FirmaRBK.type,
                personalNumber: general_FirmaRBK.rutaverificar
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_FirmaRBK.countryCode,
                type: general_FirmaRBK.type,
                personalNumber: general_FirmaRBK.rutaverificar
            }]
        }
    };
};


window.getParams = function () {
    return getParamsVerify_FirmaRBK();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_FirmaRBK.close();
    console.log("callback", result);

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_FirmaRBK.lastVerify = result.data;

                document.getElementById('id').value = general_FirmaRBK.lastVerify.id;
                document.getElementById('type').value = general_FirmaRBK.lastVerify.type;
                document.getElementById('subtype').value = general_FirmaRBK.lastVerify.subtype;
                document.getElementById('authenticated').value = general_FirmaRBK.lastVerify.authenticated;
                document.getElementById('sign').value = general_FirmaRBK.lastVerify.sign;

                document.getElementById("formulario").submit();
                //alert (verif_id + " " + verif_type + " " + verif_subtype + " " + verif_authenticated + " " + verif_sign);
            }
        }
    }

    return false;
}

function verificar_FirmaRBK() {
    consulta_sesion_FirmaRBK();
}


var conexion_FirmaRBK;


function consulta_sesion_FirmaRBK() {
    conexion_FirmaRBK = crearXMLHttpRequest();

    conexion_FirmaRBK.open('POST', 'consulta_sesion.php', false);

    conexion_FirmaRBK.send(null);
    // Devolvemos el resultado
    respuesta = conexion_FirmaRBK.responseText;
    arr_resp = respuesta.split('|');
    if (arr_resp[0] == 'ok') {
        general_FirmaRBK.baseUrl = arr_resp[1];
        general_FirmaRBK.session.companyId = arr_resp[2];
        general_FirmaRBK.username = arr_resp[4];
        general_FirmaRBK.session.id = arr_resp[6];
        general_FirmaRBK.countryCode = arr_resp[7];
        general_FirmaRBK.type = arr_resp[8];
        general_FirmaRBK.apiKey = arr_resp[9];

        var rut = document.getElementById('inVerifyDocPersonalNumber').value;
        general_FirmaRBK.rutaverificar = rut.replace("-", "");

        general_FirmaRBK.method = document.getElementById('inVerifyType').value;

        openCheckId_FirmaRBK("VERIFY");
    }
    else {
        alert(respuesta);
    }

}

/*templates\enrolar.html*/
function InicioEnrolar(){
    $('#rut').keyup(function() {
      this.value = this.value.toUpperCase();
    });
  }
  
   let general_Enrolar = {
      baseUrl: "",
      apiKey: "",
      digitalSignature: 0,
      session: {
        id: "",
        companyId: "",
        username: ""
      },
      currentAction: "CREATE",
      operator: {
        sessionId: null
      }
    };
 
    
    let popupPage_Erolar;
    let openCheckId_Enrolar = function(action) 
    {
      MostrarCargando();
      general_Enrolar.currentAction = action;
      // Create popup window
      var w = 900;
      var h = 620;

      // Fixes dual-screen position                         Most browsers      Firefox
      var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
      var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

      var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
      var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

      var left = ((width / 2) - (w / 2)) + dualScreenLeft;
      var top = ((height / 2) - (h / 2)) + dualScreenTop;

      var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
      popupPage_Erolar = window.open("checkid/index.html?v13", "libPage", opciones);
      
      var timer = setInterval(function() { 
          if(popupPage_Erolar.closed) {
              clearInterval(timer);
              document.getElementById('cargando').style.display='none';
          }
      }, 1000);
      
      // Puts focus on the popupPage_Erolar
      if (window.focus) {
        popupPage_Erolar.focus();
      }
    };

   
 let getParamsCreate_Enrolar = function() {
      return {
          action: "CREATE",
            apiKey: general_Enrolar.apiKey,
            sessionId: general_Enrolar.session.id,
            companyId: general_Enrolar.session.companyId,
            operationId: 1,
            baseUrl: general_Enrolar.baseUrl,
            useFingerprint: true,//si pide huella al que esta enrolando
            usePin: true,//si pide pin al que esta enrolando
            pinRestore: false,//cambio de pin
            pinRestoreMethod:'ACEP',
            operator: {
            useFingerprint:true,
            usePin:false,
            pinRestore: false,//cambio de pin
            sessionId: general_Enrolar.operator.sessionId,
            identityDocument: {
                countryCode: general_Enrolar.countryCode,
                type: general_Enrolar.type,
                personalNumber: general_Enrolar.rutoperador	
            }
          },
            digitalIdentity: {
                personalData: {
                    givenNames: "desmond",
                    surnames: "miles",
                    dob: 20000101,
                    gender: "NOT_KNOWN"
                },
                emailAddresses: [
                  {
                      type: 'BUSINESS',
                      address: '',
                      primary: true
                  },
                  {
                      type: 'PERSONAL',
                      address: '',
                      primary: false
                  }
              ],
              contactPhones: [
                  {
                      number: '',
                      primary: false,
                      type: 'HOME'
                  },
                  {
                      number: '',
                      primary: true,
                      type: 'PERSONAL'
                  }

                ],
                identityDocuments: [{
                  countryCode: general_Enrolar.countryCode,
                  type: general_Enrolar.type,
                  personalNumber: general_Enrolar.rutaenrolar	
                }]
            }
        };
    };

    
    
  window.getParams = function()
  {
    return getParamsCreate_Enrolar();
  }
    
  //respuesta del formulario checkid
  window.callback = function(result) 
  {
      popupPage_Erolar.close();
      console.log("callback", result);
      document.getElementById('cargando').style.display='none';
      
      if (result.numError == 0) 
      {
          //alert ("OK")
      }
      else
      {
          alert (result.numError +  " " + result.msError);
      }

      return false;
  }
    
  function enrolar_Enrolar()
  {		
      consulta_sesion_Enrolar();
  }
    
    
  var conexion_Enrolar;
          
  function consulta_sesion_Enrolar()
  {
      conexion_Enrolar=crearXMLHttpRequest();
  
      conexion_Enrolar.open('POST', './consulta_sesion.php', false);
  
      conexion_Enrolar.send(null);
      // Devolvemos el resultado
      respuesta =  conexion_Enrolar.responseText;		
      arr_resp  = respuesta.split('|');
      if (arr_resp[0] == 'ok')
      {
          general_Enrolar.baseUrl 			= arr_resp[1];
          general_Enrolar.session.companyId 	= arr_resp[2];
          general_Enrolar.username 			= arr_resp[4];
          general_Enrolar.session.id 			= arr_resp[6];
          general_Enrolar.countryCode 		= arr_resp[7];
          general_Enrolar.type 				= arr_resp[8];
          general_Enrolar.apiKey 				= arr_resp[9];
          
   
          var rut						= document.getElementById("usuarioid_Enrolar").value;
          general_Enrolar.rutoperador			= rut.replace ("-","");
         
          
          var rut						= document.getElementById('rut').value;
          general_Enrolar.rutaenrolar			= rut.replace ("-","");
      
          
          openCheckId_Enrolar("CREATE");
      }
      else
      {
          alert (respuesta);
      }
      
  }

  /*templates\documentosdet_firma_rbk.html*/

let general_DDFirma = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};


let popupPage_DDF;
let openCheckId_DDF = function (action) {
    general_DDFirma.currentAction = action;
    // Create popup window
    var w = 850;
    var h = 580;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_DDF = window.open("checkid/", "libPage", opciones);

    var timer = setInterval(function () {
        if (popupPage_DDF.closed) {
            clearInterval(timer);
            document.getElementById('cargando').style.display = 'none';
        }
    }, 1000);

    // Puts focus on the popupPage_DDF
    if (window.focus) {
        popupPage_DDF.focus();
    }
};


let getParamsVerify_DDF = function () {
    return {
        action: "VERIFY",
        method: general_DDFirma.method,
        apiKey: general_DDFirma.apiKey,
        sessionId: general_DDFirma.session.id,
        companyId: general_DDFirma.session.companyId,
        operationId: 1,
        baseUrl: general_DDFirma.baseUrl,
        useFingerprint: true,//si pide huella al que esta enrolando
        usePin: true,//si pide pin al que esta enrolando
        useKBA: false,//si se utiliza la pregunta de seguridad
        operator: {
            sessionId: general_DDFirma.operator.sessionId,
            identityDocument: {
                countryCode: general_DDFirma.countryCode,
                type: general_DDFirma.type,
                personalNumber: general_DDFirma.rutaverificar
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_DDFirma.countryCode,
                type: general_DDFirma.type,
                personalNumber: general_DDFirma.rutaverificar
            }]
        }
    };
};


window.getParams = function () {
    return getParamsVerify_DDF();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_DDF.close();
    console.log("callback", result);
    //document.getElementById('cargando').style.display='none';

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_DDFirma.lastVerify = result.data;

                document.getElementById('id').value = general_DDFirma.lastVerify.id;
                document.getElementById('type').value = general_DDFirma.lastVerify.type;
                document.getElementById('subtype').value = general_DDFirma.lastVerify.subtype;
                document.getElementById('authenticated').value = general_DDFirma.lastVerify.authenticated;
                document.getElementById('sign').value = general_DDFirma.lastVerify.sign;
                document.getElementById("formulario").submit();
                document.getElementById('cargando').style.display = 'none';

            }
        }
    }
    else {
        document.getElementById('cargando').style.display = 'none';
        alert(result.numError + " " + result.msError);
    }

    return false;
}

function verificar_DDF() {
    consulta_sesion_DDF();
}


var conexion_DDF;

function consulta_sesion_DDF() {
    conexion_DDF = crearXMLHttpRequest();

    conexion_DDF.open('POST', 'consulta_sesion.php', false);

    conexion_DDF.send(null);
    // Devolvemos el resultado
    respuesta = conexion_DDF.responseText;
    arr_resp = respuesta.split('|');
    if (arr_resp[0] == 'ok') {
        general_DDFirma.baseUrl = arr_resp[1];
        general_DDFirma.session.companyId = arr_resp[2];
        general_DDFirma.username = arr_resp[4];
        general_DDFirma.session.id = arr_resp[6];
        general_DDFirma.countryCode = arr_resp[7];
        general_DDFirma.type = arr_resp[8];
        general_DDFirma.apiKey = arr_resp[9];

        document.getElementById('sessionid').value = general_DDFirma.session.id; //token de la sesion

        var rut = document.getElementById('inVerifyDocPersonalNumber').value;
        general_DDFirma.rutaverificar = rut.replace("-", "");

        general_DDFirma.method = document.getElementById('inVerifyType').value;

        openCheckId_DDF("VERIFY");
    }
    else {
        alert(respuesta);
    }

}

/*templates\documentos_Pendientes_firma_rbk.html*/


var swpopup_DocsP = 0;		
let general_DocsP = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "VERIFY",
     operator: {
       sessionId: null
     }
   };

 
 let popupPage_DocsP;
   let openCheckId_DocsP = function(action) 
 {
     MostrarCargando();
     general_DocsP.currentAction = action;
     // Create popup window
     var w = 850;
     var h = 580;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_DocsP = window.open("checkid/index.html?v12", "libPage", opciones);

     var timer = setInterval(function() { 
       if(popupPage_DocsP.closed) {
         clearInterval(timer);
         if( swpopup_DocsP == 0 )
         {
             document.getElementById('cargando').style.display='none';
         }
       }
     }, 1000);
     
     // Puts focus on the popupPage_DocsP
     if (window.focus) {
       popupPage_DocsP.focus();
     }
   };


   let getParamsVerify_DocsP = function() {
     return {
       action: "VERIFY",
       method: general_DocsP.method,
       apiKey: general_DocsP.apiKey,
       sessionId: general_DocsP.session.id,
       companyId: general_DocsP.session.companyId,
       operationId: 1,
       baseUrl: general_DocsP.baseUrl,
     pinRestore: true,//cambio de pin
     pinRestoreMethod:'ACEP',//pide nro documento
       operator: {
         sessionId: general_DocsP.operator.sessionId,
         identityDocument: {
             countryCode: general_DocsP.countryCode,
             type: general_DocsP.type,
             personalNumber: general_DocsP.rutaverificar
         }
       },
       digitalIdentity: {
         identityDocuments: [{
           countryCode: general_DocsP.countryCode,
           type: general_DocsP.type,
           personalNumber: general_DocsP.rutaverificar
         }]
       }
       };
   };
 
//respuesta del formulario checkid
window.callback = function(result) 
{	//console.log("callback", result);
 if( $("#accion_enrolar").val() === 'SI'){
     popupPage_enrolar_DocsP.close();
     document.getElementById("formulario3").submit();	
     console.log("callback", result);
     //document.getElementById('cargandof').style.display='none';
     OcultarCargando();
     if (result.numError == 0) 
     {
         //alert ("OK")
          //document.getElementById("formulario3").submit();	
     }
     else
     {
         
         alert (result.numError +  " " + result.msError);
         //document.getElementById('cargandof').style.display='none';
         OcultarCargando();
     }

     return false;
 }
 else
 {
     swpopup_DocsP = 1;	 
     popupPage_DocsP.close();
     console.log("callback", result);
     //document.getElementById('cargando').style.display='none';

     if (result.numError == 0) 
     {
       if (result.action == "VERIFY") 
       {	
         try{	
                 let resultMessage = "Validación Errónea";
                 if (result.data.authenticated)
                 {
                   resultMessage = "Validación Exitosa!";
               
                   general_DocsP.lastVerify = result.data;
           
                   document.getElementById('id').value       = general_DocsP.lastVerify.id;
                   document.getElementById('type').value       = general_DocsP.lastVerify.type;
                   document.getElementById('subtype').value      = general_DocsP.lastVerify.subtype;
                   document.getElementById('authenticated').value  = general_DocsP.lastVerify.authenticated;
                   document.getElementById('sign').value       = general_DocsP.lastVerify.sign;      
                   document.getElementById("formulario").submit();
                 
                 }
                 else
                 {	
                     document.getElementById('cargando').style.display='none';
                 }
             }catch(err){
                 //alert(err);
                 document.getElementById('cargando').style.display='none';
             }
     
         }
     }
     
     if (result.numError == 0) 
     {
         //alert ("OK")
     }
     else
     {
         alert (result.numError +  " " + result.msError);
         document.getElementById('cargando').style.display='none';
     }

     return false;
 }
}
 
function verificar_DocsP()
{  
 consultar_usuario_DocsP();
}

var conexion_DocsP;
   

function consulta_sesion_enrolar_DocsP()
{
 conexion_DocsP=crearXMLHttpRequest();

 conexion_DocsP.open('POST', 'consulta_sesion.php', false);

 conexion_DocsP.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_DocsP.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
     //Datos para enrolar
     general_enrolar_DocsP.baseUrl 			= arr_resp[1];
     general_enrolar_DocsP.session.companyId 	= arr_resp[2];
     general_enrolar_DocsP.username 			= arr_resp[4];
     general_enrolar_DocsP.session.id 			= arr_resp[6];
     general_enrolar_DocsP.countryCode 		= arr_resp[7];
     general_enrolar_DocsP.type 				= arr_resp[8];
     general_enrolar_DocsP.apiKey 				= arr_resp[9];
     
     
     var rut						= document.getElementById('RutUsuario_DocsP').value;
     general_enrolar_DocsP.rutoperador			= rut.replace ("-",""); 
     
     var rut						= document.getElementById('RutUsuario_DocsP').value;
     general_enrolar_DocsP.rutaenrolar			= rut.replace ("-",""); 
     
         
     openCheckId_enrolar_DocsP("CREATE");			
 }
 else
 {
   alert (respuesta);
 }
 
}

function consulta_sesion_DocsP()
{
 conexion_DocsP=crearXMLHttpRequest();

 conexion_DocsP.open('POST', 'consulta_sesion.php', false);

 conexion_DocsP.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_DocsP.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
   general_DocsP.baseUrl       = arr_resp[1];
   general_DocsP.session.companyId   = arr_resp[2];
   general_DocsP.username      = arr_resp[4];
   general_DocsP.session.id      = arr_resp[6];
   general_DocsP.countryCode     = arr_resp[7];
   general_DocsP.type        = arr_resp[8];
   general_DocsP.apiKey        = arr_resp[9];

   document.getElementById('sessionid').value =   general_DocsP.session.id; //token de la sesion

   var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
   general_DocsP.rutaverificar   = rut.replace ("-","");
   general_DocsP.method        = document.getElementById('inVerifyType').value;

   openCheckId_DocsP("VERIFY");
 }
 else
 {
   alert (respuesta);
 }
 
}

function consultar_usuario_DocsP()
{
 conexion_DocsP=crearXMLHttpRequest();
 var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
 rut  = rut.replace ("-","");
 var url = 'consulta_usuario.php';
 var parametros  ='personalNumber=' + rut;

 conexion_DocsP.open('POST',url, false);

 conexion_DocsP.send(parametros);
 // Devolvemos el resultado
 respuesta =  conexion_DocsP.responseText;  

 var datos = JSON.parse(respuesta); 

 if( respuesta.length > 5 ){
 
     $("#mensajeError").removeClass("callout callout-wauning");
     $("#mensajeError").html("");
     consulta_sesion_DocsP();
 }
 else
 {
   $("#respuesta").css("display","none");
   $("#huella").html("");
   $("#pin").html("");
   
 /*  $("#mensajeError").addClass("callout callout-warning");
   $("#mensajeError").html("El usuario no esta enrolado, <a onclick='enrolarse_DocsP()'>haga clic aqu&iacute;</a> para enrolarse");*/
   
   var mensaje = "El usuario no esta enrolado, " + "<a onclick='enrolarse_DocsP()'>haga clic aqu&iacute;</a>" + " para enrolarse";
   ventanax(mensaje,'error')
   
 }
}

let general_enrolar_DocsP = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "CREATE",
     operator: {
       sessionId: null
     }
   };

   
 let popupPage_enrolar_DocsP;
   let openCheckId_enrolar_DocsP = function(action) 
   {
     general_enrolar_DocsP.currentAction = action;
     // Create popup window
     var w = 900;
     var h = 620;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_enrolar_DocsP = window.open("checkid/index.html?v12", "libPage", opciones);
     
 
     var timer_enrolar = setInterval(function() { 
         if(popupPage_enrolar_DocsP.closed) {
             clearInterval(timer_enrolar);
             //document.getElementById('cargandof').style.display='none';
         }
     }, 1000);
 
     
     // Puts focus on the popupPage_enrolar_DocsP
     if (window.focus) {
       popupPage_enrolar_DocsP.focus();
     }
   };

  
 let getParamsCreate_DocsP = function() {
     return {
         action: "CREATE",
           apiKey: general_enrolar_DocsP.apiKey,
           sessionId: general_enrolar_DocsP.session.id,
           companyId: general_enrolar_DocsP.session.companyId,
           operationId: 1,
           baseUrl: general_enrolar_DocsP.baseUrl,
           useFingerprint: false,//si pide huella al que esta enrolando
           usePin: true,//si pide pin al que esta enrolando
           pinRestore: false,//cambio de pin
           pinRestoreMethod:'ACEP',
           operator: {
           useFingerprint:false,
           usePin:true,
           sessionId: general_enrolar_DocsP.operator.sessionId,
           identityDocument: {
               countryCode: general_enrolar_DocsP.countryCode,
               type: general_enrolar_DocsP.type,
               personalNumber: general_enrolar_DocsP.rutoperador	
           }
         },
     
           digitalIdentity: {
               personalData: {
                   givenNames: '',
                   surnames: '',
                   dob: 20000101,
                   gender: "NOT_KNOWN"
               },
               emailAddresses: [
                 {
                     type: 'BUSINESS',
                     address: '',
                     primary: true
                 },
                 {
                     type: 'PERSONAL',
                     address: '' ,
                     primary: false
                 }
             ],
             contactPhones: [
                 {
                     number: '',
                     primary: true,
                     type: 'HOME'
                 },
                 {
                     number: '',
                     primary: false,
                     type: 'PERSONAL'
                 }

               ],
               identityDocuments: [{
                 countryCode: general_enrolar_DocsP.countryCode,
                 type: general_enrolar_DocsP.type,
                 personalNumber: general_enrolar_DocsP.rutaenrolar
               }]
           }
       };
   };

window.getParams = function()
{
     if( $("#accion_enrolar").val() === 'SI' ){
         //$("#accion_enrolar").val('');
         return getParamsCreate_DocsP();
     }else{
          return getParamsVerify_DocsP();
     }
}
 function enrolarse_DocsP()
 {
      $('#myModalx').modal('hide');
     $("#accion_enrolar").val('SI');	
     consulta_sesion_enrolar_DocsP();
     
     
 }

 /*templates\documentos_FirmaTercero_firma.html*/

let general_DocsFT = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
        id: "",
        companyId: "",
        username: ""
    },
    currentAction: "VERIFY",
    operator: {
        sessionId: null
    }
};


let popupPage_DocsFT;
let openCheckId_DocsFT = function (action) {
    general_DocsFT.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_DocsFT = window.open("checkid/checkid/", "libPage", opciones);
    // Puts focus on the popupPage_DocsFT
    if (window.focus) {
        popupPage_DocsFT.focus();
    }
};


let getParamsVerify_DocsFT = function () {
    return {
        action: "VERIFY",
        method: general_DocsFT.method,
        apiKey: general_DocsFT.apiKey,
        sessionId: general_DocsFT.session.id,
        companyId: general_DocsFT.session.companyId,
        operationId: 1,
        baseUrl: general_DocsFT.baseUrl,
        operator: {
            sessionId: general_DocsFT.operator.sessionId,
            identityDocument: {
                countryCode: general_DocsFT.countryCode,
                type: general_DocsFT.type,
                personalNumber: general_DocsFT.rutaverificar
            }
        },
        digitalIdentity: {
            identityDocuments: [{
                countryCode: general_DocsFT.countryCode,
                type: general_DocsFT.type,
                personalNumber: general_DocsFT.rutaverificar
            }]
        }
    };
};


window.getParams = function () {
    return getParamsVerify_DocsFT();
}

//respuesta del formulario checkid
window.callback = function (result) {
    popupPage_DocsFT.close();
    console.log("callback", result);

    if (result.numError == 0) {
        if (result.action == "VERIFY") {
            let resultMessage = "Validación Errónea";
            if (result.data.authenticated) {
                resultMessage = "Validación Exitosa!";

                general_DocsFT.lastVerify = result.data;

                document.getElementById('id').value = general_DocsFT.lastVerify.id;
                document.getElementById('type').value = general_DocsFT.lastVerify.type;
                document.getElementById('subtype').value = general_DocsFT.lastVerify.subtype;
                document.getElementById('authenticated').value = general_DocsFT.lastVerify.authenticated;
                document.getElementById('sign').value = general_DocsFT.lastVerify.sign;
                document.getElementById("formulario").submit();

            }
        }
    }

    return false;
}

function verificar_DocsFT() {
    consulta_sesion_DocsFT();
}


var conexion_DocsFT;

function consulta_sesion_DocsFT() {
    conexion_DocsFT = crearXMLHttpRequest();

    conexion_DocsFT.open('POST', 'consulta_sesion.php', false);

    conexion_DocsFT.send(null);
    // Devolvemos el resultado
    respuesta = conexion_DocsFT.responseText;
    arr_resp = respuesta.split('|');
    if (arr_resp[0] == 'ok') {
        general_DocsFT.baseUrl = arr_resp[1];
        general_DocsFT.session.companyId = arr_resp[2];
        general_DocsFT.username = arr_resp[4];
        general_DocsFT.session.id = arr_resp[6];
        general_DocsFT.countryCode = arr_resp[7];
        general_DocsFT.type = arr_resp[8];
        general_DocsFT.apiKey = arr_resp[9];

        document.getElementById('sessionid').value = general_DocsFT.session.id; //token de la sesion

        var rut = document.getElementById('inVerifyDocPersonalNumber').value;
        general_DocsFT.rutaverificar = rut.replace("-", "");

        general_DocsFT.method = document.getElementById('inVerifyType').value;

        openCheckId_DocsFT("VERIFY");
    }
    else {
        alert(respuesta);
    }

}

/*templates\documentos_FirmaTercero_firma_rbk.html*/

var swpopup_FT = 0;
   
let general_FT = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "VERIFY",
     operator: {
       sessionId: null
     }
   };

 
 let popupPage_FT;
   let openCheckId_FT = function(action) 
 {
     MostrarCargando();
     general_FT.currentAction = action;
     // Create popup window
     var w = 850;
     var h = 580;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_FT = window.open("checkid/index.html?v12", "libPage", opciones);

     var timer = setInterval(function() { 
       if(popupPage_FT.closed) {
         clearInterval(timer);
         if( swpopup_FT == 0 )
         {
             document.getElementById('cargando').style.display='none';
         }
       }
     }, 1000);

     // Puts focus on the popupPage_FT
     if (window.focus) {
       popupPage_FT.focus();
     }
   };


   let getParamsVerify_FT = function() {
     return {
       action: "VERIFY",
       method: general_FT.method,
       apiKey: general_FT.apiKey,
       sessionId: general_FT.session.id,
       companyId: general_FT.session.companyId,
       operationId: 1,
       baseUrl: general_FT.baseUrl,
     useFingerprint: true,//si pide huella al que esta verificando
     usePin: true,//si pide pin al que esta verificando
     pinRestore: true,//cambio de pin
     pinRestoreMethod:'ACEP',
       operator: {
         useFingerprint:true,
         usePin:true,
         pinRestore: true,//cambio de pin
         pinRestoreMethod:'ACEP',
         sessionId: general_FT.operator.sessionId,
         identityDocument: {
             countryCode: general_FT.countryCode,
             type: general_FT.type,
             personalNumber: general_FT.rutoperador
         }
       },
       digitalIdentity: {
         identityDocuments: [{
           countryCode: general_FT.countryCode,
           type: general_FT.type,
           personalNumber: general_FT.rutaverificar
         }]
       }
       };
   };
 
 
window.getParams = function()
{
 return getParamsVerify_FT();
}
 
//respuesta del formulario checkid
window.callback = function(result) 
{
 swpopup_FT = 1;
 popupPage_FT.close();
 console.log("callback", result);
 //document.getElementById('cargando').style.display='none';

 if (result.numError == 0) 
 {
   if (result.action == "VERIFY") 
   {
     
     try{
     
         let resultMessage = "Validación Errónea";
         if (result.data.authenticated)
         {
           resultMessage = "Validación Exitosa!";
       
           general_FT.lastVerify = result.data;
   
           document.getElementById('id').value       = general_FT.lastVerify.id;
           document.getElementById('type').value       = general_FT.lastVerify.type;
           document.getElementById('subtype').value      = general_FT.lastVerify.subtype;
           document.getElementById('authenticated').value  = general_FT.lastVerify.authenticated;
           document.getElementById('sign').value       = general_FT.lastVerify.sign;      
           document.getElementById("formulario").submit();
         
         }
         else
         {	
             document.getElementById('cargando').style.display='none';
         }
     }catch(err){
         //alert(err);
         document.getElementById('cargando').style.display='none';
     }
     
     
   }
 }
 
 if (result.numError == 0) 
 {
     //alert ("OK");
 }
 else
 {	
     document.getElementById('cargando').style.display='none';
     alert (result.numError +  " " + result.msError);
 }

 return false;
}
 
function verificar_FT()
{ 

 buscar_tipofirma_FT();

 var error = document.getElementById("mensajeError").innerHTML.length;
 
 if ( error == 0){
   consulta_sesion_FT();
 }
}
 
 
var conexion_FT;
  


function consulta_sesion_FT()
{
 conexion_FT=crearXMLHttpRequest();

 conexion_FT.open('POST', 'consulta_sesion.php', false);

 conexion_FT.send(null);

 // Devolvemos el resultado
 respuesta =  conexion_FT.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
   general_FT.baseUrl       = arr_resp[1];
   general_FT.session.companyId   = arr_resp[2];
   general_FT.username      = arr_resp[4];
   general_FT.session.id      = arr_resp[6];
   general_FT.countryCode     = arr_resp[7];
   general_FT.type        = arr_resp[8];
   general_FT.apiKey        = arr_resp[9];
   general_FT.rutoperador        = arr_resp[10];

   document.getElementById('sessionid').value =   general_FT.session.id; //token de la sesion

   var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
   general_FT.rutaverificar   = rut.replace ("-","");
   
   general_FT.method        = document.getElementById('inVerifyType').value;

   openCheckId_FT("VERIFY");
 }
 else
 {
   alert (respuesta);
 }
}


 function buscar_tipofirma_FT(){

     var rut = document.getElementById('inVerifyDocPersonalNumber').value;
     var id = document.getElementById('idDocumento').value;
     conexion_FT = crearXMLHttpRequest();
     conexion_FT.onreadystatechange = funcionCallback_btf;
     conexion_FT.open('POST', 'Documentos_FirmaTercero_ajax.php?usuarioid=' + rut + '&idDocumento=' + id, false);
     conexion_FT.send(null);
     
 }

 function funcionCallback_btf(){

   if( conexion_FT.readyState == 4 )
   {

     if( conexion_FT.status == 200 )
     {

       respuesta =  conexion_FT.responseText; 
       if ( respuesta.length < 13 )
         document.getElementById("inVerifyType").value = respuesta;
       else{
         mensajeValidacion(respuesta);
       }
     }
   }
 }


 /*templates\documentos_Firmas_firma_rbk.html*/
 
 var swpopup_FRBK = 0;
	
 let general_FRBK = {
      baseUrl: "",
      apiKey: "",
      digitalSignature: 0,
      session: {
        id: "",
        companyId: "",
        username: ""
      },
      currentAction: "VERIFY",
      operator: {
        sessionId: null
      }
    };
 
  
  let popupPage_FRBK;
    let openCheckId_FRBK = function(action) 
  {
      MostrarCargando();
      general_FRBK.currentAction = action;
      // Create popup window
      var w = 850;
      var h = 580;

      // Fixes dual-screen position                         Most browsers      Firefox
      var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
      var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

      var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
      var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

      var left = ((width / 2) - (w / 2)) + dualScreenLeft;
      var top = ((height / 2) - (h / 2)) + dualScreenTop;

      var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
      popupPage_FRBK = window.open("checkid/", "libPage", opciones);

      var timer = setInterval(function() { 
        if(popupPage_FRBK.closed) {
          clearInterval(timer);
          if( swpopup_FRBK == 0 )
          {
              document.getElementById('cargando').style.display='none';
          }
        }
      }, 1000);

      // Puts focus on the popupPage_FRBK
      if (window.focus) {
        popupPage_FRBK.focus();
      }
    };

 
    let getParamsVerify_FRBK = function() {
      return {
        action: "VERIFY",
        method: general_FRBK.method,
        apiKey: general_FRBK.apiKey,
        sessionId: general_FRBK.session.id,
        companyId: general_FRBK.session.companyId,
        operationId: 1,
        baseUrl: general_FRBK.baseUrl,
      useFingerprint: true,//si pide huella al que esta enrolando
      usePin: true,//si pide pin al que esta enrolando
      useKBA: false,//si se utiliza la pregunta de seguridad
        operator: {
          sessionId: general_FRBK.operator.sessionId,
          identityDocument: {
              countryCode: general_FRBK.countryCode,
              type: general_FRBK.type,
              personalNumber: general_FRBK.rutaverificar
          }
        },
        digitalIdentity: {
          identityDocuments: [{
            countryCode: general_FRBK.countryCode,
            type: general_FRBK.type,
            personalNumber: general_FRBK.rutaverificar
          }]
        }
        };
    };
  
  
window.getParams = function()
{
  return getParamsVerify_FRBK();
}
  
//respuesta del formulario checkid
window.callback = function(result) 
{
  swpopup_FRBK = 1;
  popupPage_FRBK.close();
  console.log("callback", result);
  

  if (result.numError == 0) 
  {
    if (result.action == "VERIFY") 
    {
      try
      {
          let resultMessage = "Validación Errónea";
          if (result.data.authenticated)
          {
            resultMessage = "Validación Exitosa!";
        
            general_FRBK.lastVerify = result.data;
    
            document.getElementById('id').value       = general_FRBK.lastVerify.id;
            document.getElementById('type').value       = general_FRBK.lastVerify.type;
            document.getElementById('subtype').value      = general_FRBK.lastVerify.subtype;
            document.getElementById('authenticated').value  = general_FRBK.lastVerify.authenticated;
            document.getElementById('sign').value       = general_FRBK.lastVerify.sign;      
            document.getElementById("formulario").submit();
           // document.getElementById('cargando').style.display='none';
          }
          else
          {
              document.getElementById('cargando').style.display='none';
          }
      }catch(err){
          //alert(err);
          document.getElementById('cargando').style.display='none';
      }
    }
  }
  else
  {	
      document.getElementById('cargando').style.display='none';
      alert (result.numError +  " " + result.msError);
  }
  return false;
}
  
function verificar_FRBK()
{ 
  consulta_sesion_FRBK();
}
  
  
var conexion_FRBK;
    

function consulta_sesion_FRBK()
{
  conexion_FRBK=crearXMLHttpRequest();

  conexion_FRBK.open('POST', 'consulta_sesion.php', false);

  conexion_FRBK.send(null);
  // Devolvemos el resultado
  respuesta =  conexion_FRBK.responseText;   
  arr_resp  = respuesta.split('|'); 
  if (arr_resp[0] == 'ok')
  {
    general_FRBK.baseUrl       = arr_resp[1];
    general_FRBK.session.companyId   = arr_resp[2];
    general_FRBK.username      = arr_resp[4];
    general_FRBK.session.id      = arr_resp[6];
    general_FRBK.countryCode     = arr_resp[7];
    general_FRBK.type        = arr_resp[8];
    general_FRBK.apiKey        = arr_resp[9];

    document.getElementById('sessionid').value =   general_FRBK.session.id; //token de la sesion

    var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
    general_FRBK.rutaverificar   = rut.replace ("-","");
    
    general_FRBK.method        = document.getElementById('inVerifyType').value;

    openCheckId_FRBK("VERIFY");
  }
  else
  {
    alert (respuesta);
  }
  
}

/*templates\Documentos_FirmaMasiva_rbk.html*/

var swpopup_FM = 0;
  
let general_FM = {
     baseUrl: "",
     apiKey: "",
     digitalSignature: 0,
     session: {
       id: "",
       companyId: "",
       username: ""
     },
     currentAction: "VERIFY",
     operator: {
       sessionId: null
     }
   };

 
 let popupPage_FM;
   let openCheckId_FM = function(action) 
 {
     MostrarCargando();
     general_FM.currentAction = action;
     // Create popup window
     var w = 850;
     var h = 580;

     // Fixes dual-screen position                         Most browsers      Firefox
     var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
     var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

     var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
     var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

     var left = ((width / 2) - (w / 2)) + dualScreenLeft;
     var top = ((height / 2) - (h / 2)) + dualScreenTop;

     var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
     popupPage_FM = window.open("checkid/index.html?v12", "libPage", opciones);

     var timer = setInterval(function() { 
       if(popupPage_FM.closed) {
         clearInterval(timer);
         if( swpopup_FM == 0 )
         {
             document.getElementById('cargando').style.display='none';
         }
       }
     }, 1000);
     
     // Puts focus on the popupPage_FM
     if (window.focus) {
       popupPage_FM.focus();
     }
   };


   let getParamsVerify_FM = function() {
     return {
       action: "VERIFY",
       method: general_FM.method,
       apiKey: general_FM.apiKey,
       sessionId: general_FM.session.id,
       companyId: general_FM.session.companyId,
       operationId: 1,
       baseUrl: general_FM.baseUrl,
       pinRestore: true,//cambio de pin
       pinRestoreMethod:'ACEP',//pide nro documento
       operator: {
         sessionId: general_FM.operator.sessionId,
         identityDocument: {
             countryCode: general_FM.countryCode,
             type: general_FM.type,
             personalNumber: general_FM.rutaverificar
         }
       },
       digitalIdentity: {
         identityDocuments: [{
           countryCode: general_FM.countryCode,
           type: general_FM.type,
           personalNumber: general_FM.rutaverificar
         }]
       }
       };
   };
 
 
window.getParams = function()
{
 return getParamsVerify_FM();
}
 
//respuesta del formulario checkid
window.callback = function(result) 
{
 swpopup_FM = 1;
 popupPage_FM.close();
 console.log("callback", result);
 document.getElementById('cargando').style.display='none';

 if (result.numError == 0) 
 {
   if (result.action == "VERIFY") 
   {
     try{
         let resultMessage = "Validación Errónea";
         if (result.data.authenticated)
         {
           resultMessage = "Validación Exitosa!";
       
           general_FM.lastVerify = result.data;
   
           document.getElementById('id').value       = general_FM.lastVerify.id;
           document.getElementById('type').value       = general_FM.lastVerify.type;
           document.getElementById('subtype').value      = general_FM.lastVerify.subtype;
           document.getElementById('authenticated').value  = general_FM.lastVerify.authenticated;
           document.getElementById('sign').value       = general_FM.lastVerify.sign;      
           //document.getElementById("formulario").submit(); 
           MostrarCargando();			  
           firmaMasiva_FM();
         }
         else
             {
                 document.getElementById('cargando').style.display='none';
             }
     }catch(err){
         //alert(err);
         document.getElementById('cargando').style.display='none';
     }
     
   }
 }
 
 if (result.numError == 0) 
 {
     //alert ("OK")
 }
 else
 {
     alert (result.numError +  " " + result.msError);
     document.getElementById('cargando').style.display='none';
 }

 return false;
}
 
function verificar_FM()
{ 
 MostrarCargando();
 consulta_sesion_FM();
}
 
 
var conexion_FM;

function consulta_sesion_FM()
{
 conexion_FM=crearXMLHttpRequest();

 conexion_FM.open('POST', 'consulta_sesion.php', false);

 conexion_FM.send(null);
 // Devolvemos el resultado
 respuesta =  conexion_FM.responseText;   
 arr_resp  = respuesta.split('|'); 
 if (arr_resp[0] == 'ok')
 {
   general_FM.baseUrl       = arr_resp[1];
   general_FM.session.companyId   = arr_resp[2];
   general_FM.username      = arr_resp[4];
   general_FM.session.id      = arr_resp[6];
   general_FM.countryCode     = arr_resp[7];
   general_FM.type        = arr_resp[8];
   general_FM.apiKey        = arr_resp[9];

   document.getElementById('sessionid').value =   general_FM.session.id; //token de la sesion

   var rut           = document.getElementById('inVerifyDocPersonalNumber').value;
   general_FM.rutaverificar   = rut.replace ("-","");
   
   general_FM.method        = document.getElementById('inVerifyType').value;

   openCheckId_FM("VERIFY");
 }
 else
 {
   alert (respuesta);
 }
 
}

var ajax_FM;
var salida_FM;
var fila_FM = 0;
var proceso_FM;
var cant_FM = 0;
var i_FM = 0;
var documento_FM = 0;
var indices_FM = [];

function InicioDocumentosFirmaMasiva(){

 $("#pin").val("");
 $("#pin").css("display","block");
 $("#FIRMAR").prop("disabled",false);

 if( $("#cant_docs").html() == '' ){
   var fil = 0;
   fil = parseInt($("#example tr").length);
   $("#cant_docs").html(fil-2);
 }

};

 function eliminarDoc_FM(i){

     var fila_FM = $("#example tr").length;
     if( fila_FM == 3 ){
       $("#formulario").submit();
     }else if( fila_FM > 3){
       $("#fila_" + i).remove();

       var fil = 0;
       fil = parseInt($("#example tr").length);
       $("#cant_docs").html(fil-2);
      }        
 }

function firmaMasiva_FM(){
   
 //Limpiamos errores anteriores
 $("#mensajeError").removeClass("callout callout-warning");
 $("#mensajeError").html("");

 //Bloquear
 $("#btn-verify").prop("disabled",true);

 //Cantidad de filas 
 cant_FM = $("#example tr").length;
 cant_FM = cant_FM - 2;

 //Buscar los indices_FM de las filas de la tabla 
 $('#example tbody tr').each(function () {
    var indice = $(this).attr("id");
    var res = indice.split("_");
    indices_FM.push(res[1]);
 });
 
 documento_FM = $("#doc_" + indices_FM[i_FM]).val();

 //Buscamos los datos que falten
 proceso_FM = setInterval(function(){ firma_pin_FM(documento_FM,indices_FM[i_FM]) }, 1000);

};

//Firmar un documento_FM
function firma_pin_FM(idDocumento,i){
 
 MostrarCargando();
 clearInterval(proceso_FM);// poner esto cuando llega respuesta

 var usuarioid = $("#inVerifyDocPersonalNumber").val();
 var formulario = $("#formulario").serialize();

 var url = "Documentos_firmaMasiva3_ajax.php";
 var parametros  ="idDocumento=" + idDocumento + "&formulario="+ formulario + "&usuarioid=" + usuarioid;

 fila_FM = i;
 console.log(url);

  // Creamos el control XMLHttpRequest segun el navegador en el que estemos 
 if( window.XMLHttpRequest )
    ajax_FM = new XMLHttpRequest(); // No Internet Explorer
 else
    ajax_FM = new ActiveXObject("Microsoft.XMLHTTP"); // Internet Explorer
 
    // Almacenamos en el control al funcion que se invocara cuando la peticion
 ajax_FM.onreadystatechange = funcionCallback_firma_pin;
 ajax_FM.addEventListener("load", transferComplete);

 // Enviamos la peticion
 ajax_FM.open( "POST", url, false);
 ajax_FM.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 ajax_FM.send(parametros);

 function transferComplete(evt) {
   mostrarEstadoFirma_FM(salida_FM,fila_FM);
 }
}

function funcionCallback_firma_pin(){

 // Comprobamos si la peticion se ha completado (estado 4)
 if( ajax_FM.readyState == 4 )
 {
   // Comprobamos si la respuesta ha sido correcta (resultado HTTP 200)
   if( ajax_FM.status == 200 )
   {
     // Escribimos el resultado en la pagina HTML mediante DHTML
     salida_FM = '';
     salida_FM = ajax_FM.responseText;
   }
 }
}

//Mostrar estado de Firma en el modal
function mostrarEstadoFirma_FM(salida_FM, fila_FM){

 //Error de login
 var error = salida_FM.substring(0, 33);

 //Incrementar la fila_FM de la tabla a recorrer
 i_FM++;

 //Actualizamos iconos segun la respuesta 

 //Si es error de login
 if ( error == 'Error en Login del Usuario400 Rut' ){
   
   //Desbloquear boton de firma 
   $("#btn-verify").prop("disabled",false);

   //Enviamos mensaje
   $("#mensajeError").addClass("callout callout-warning");
   $("#mensajeError").html(salida_FM);

   //Asignar el mismo valor y se cancela la firma para los proximos documentos
   i_FM = cant_FM;
   //Ocultar el gif de cargando
   OcultarCargando();

 }

 //Si es otro error 
 else if( salida_FM != 0 ){

   $("#icon_" + fila_FM).removeClass();
   $("#icon_" + fila_FM).addClass('fa fa-exclamation-triangle text-warning');
   $("#icon_" + fila_FM).prop('title',salida_FM); 
   $("#borrar_" + fila_FM).removeAttr('onclick');
   
   //Si termino de recorrer las filas 
   if( !(i_FM < cant_FM) ){
       //Ocultar el gif de cargando
       OcultarCargando();

   }//Si no, no se limpia el campo pin, para que continue con los siguientes documentos 

 }else if (salida_FM){
   $("#icon_" + fila_FM).removeClass();
   $("#icon_" + fila_FM).addClass('fa fa-check-circle text-success');
   $("#icon_" + fila_FM).prop('title','Firmado correctamente');
   $("#borrar_" + fila_FM).removeAttr('onclick');
   
   //Si termino de recorrer las filas 
   if( !(i_FM < cant_FM) ){
       //Limpiar campo de pin
       $("#pin").val("");

         //Ocultar el gif de cargando
         OcultarCargando();

   }//Si no, no se limpia el campo pin, para que continue
   //Continua con los siguientes documentos 
 }

 //Si quedan filas que recorrer 
 if( i_FM < cant_FM ){
     //Pasar al siguiente 
     documento_FM = $("#doc_"+ indices_FM[i_FM]).val();
     proceso_FM = setInterval(function(){ firma_pin_FM(documento_FM,indices_FM[i_FM]) }, 1000);
 }
}

/*templates\correos_FormularioModificar.html*/

function validarcorreo_CFMod(){
    var resultado = validarEmail(obtenerElemento('CC').value);
    var cc = obtenerElemento('CC').value;
    
    if( cc != ''  ){
         if (resultado == false) 
          { 
            document.getElementById('mensajeError').innerHTML ;
            mensajeValidacion("Campo debe ser un email");
            return false;
          }
    }
    
    var resultado = validarEmail(obtenerElemento('CCo').value);
    var cco = obtenerElemento('CCo').value;
    
    if( cco != ''  ){
         if (resultado == false) 
          { 
            document.getElementById('mensajeError').innerHTML;
            mensajeValidacion("Campo debe ser un email");
            return false;
          }
    }
    return true;
  }

  /*templates\crearpin.html*/
  let general_crearpin = {
    baseUrl: "",
    apiKey: "",
    digitalSignature: 0,
    session: {
      id: "",
      companyId: "",
      username: ""
    },
    currentAction: "CREATE",
    operator: {
      sessionId: null
    }
  };

  
  let popupPage_crearpin;
  let openCheckId_crearpin = function(action) 
  {
    general_crearpin.currentAction = action;
    // Create popup window
    var w = 900;
    var h = 620;

    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;

    var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" + w + ", height=" + h + ", top=" + top + ", left=" + left;
    popupPage_crearpin = window.open("checkid/index.html?v13", "libPage", opciones);
    

    var timer = setInterval(function() { 
        if(popupPage_crearpin.closed) {
            clearInterval(timer);
            document.getElementById('cargandof').style.display='none';
        }
    }, 1000);

    
    // Puts focus on the popupPage_crearpin
    if (window.focus) {
      popupPage_crearpin.focus();
    }
  };

 
let getParamsCreate_crearpin = function() {
    return {
        action: "CREATE",
          apiKey: general_crearpin.apiKey,
          sessionId: general_crearpin.session.id,
          companyId: general_crearpin.session.companyId,
          operationId: 1,
          baseUrl: general_crearpin.baseUrl,
          useFingerprint: false,//si pide huella al que esta enrolando
          usePin: true,//si pide pin al que esta enrolando
          pinRestore: false,//cambio de pin
          pinRestoreMethod:'ACEP',
          operator: {
          useFingerprint:false,
          usePin:true,
          sessionId: general_crearpin.operator.sessionId,
          identityDocument: {
              countryCode: general_crearpin.countryCode,
              type: general_crearpin.type,
              personalNumber: general_crearpin.rutoperador	
          }
        },
          digitalIdentity: {
              personalData: {
                  givenNames: "desmond",
                  surnames: "miles",
                  dob: 20000101,
                  gender: "NOT_KNOWN"
              },
              emailAddresses: [
                {
                    type: 'BUSINESS',
                    address: '',
                    primary: true
                },
                {
                    type: 'PERSONAL',
                    address: '',
                    primary: false
                }
            ],
            contactPhones: [
                {
                    number: '',
                    primary: false,
                    type: 'HOME'
                },
                {
                    number: '',
                    primary: true,
                    type: 'PERSONAL'
                }

              ],
              identityDocuments: [{
                countryCode: general_crearpin.countryCode,
                type: general_crearpin.type,
                personalNumber: general_crearpin.rutaenrolar
              }]
          }
      };
  };


  
  
window.getParams = function()
{
  return getParamsCreate_crearpin();
}
  
//respuesta del formulario checkid
window.callback = function(result) 
{
    popupPage_crearpin.close();
    console.log("callback", result);
    document.getElementById('cargandof').style.display='none';
    if (result.numError == 0) 
    {
        //alert ("OK")
        
    }
    else
    {
        
        alert (result.numError +  " " + result.msError);
        document.getElementById('cargandof').style.display='none';
    }

    return false;
}
  
function crearpin()
{
    consulta_sesion_crearpin();
}
  
  
var conexion_crearpin;
        

function consulta_sesion_crearpin()
{
    conexion_crearpin=crearXMLHttpRequest();

    conexion_crearpin.open('POST', './consulta_sesion.php', false);

    conexion_crearpin.send(null);
    // Devolvemos el resultado
    respuesta =  conexion_crearpin.responseText;		
    arr_resp  = respuesta.split('|');
    if (arr_resp[0] == 'ok')
    {
        general_crearpin.baseUrl 			= arr_resp[1];
        general_crearpin.session.companyId 	= arr_resp[2];
        general_crearpin.username 			= arr_resp[4];
        general_crearpin.session.id 			= arr_resp[6];
        general_crearpin.countryCode 		= arr_resp[7];
        general_crearpin.type 				= arr_resp[8];
        general_crearpin.apiKey 				= arr_resp[9];
        
        
        var rut						= document.getElementById("usuarioid_crearpin").value;
        general_crearpin.rutoperador			= rut.replace ("-","");
        
        var rut						= document.getElementById("usuarioid_crearpin").value;
        general_crearpin.rutaenrolar			= rut.replace ("-","");
        
        
        openCheckId_crearpin("CREATE");
        
    }
    else
    {
        alert (respuesta);
    }
        
}

//crearpin();

/*templates\fij_piedepagina.html*/
function ventanax(mensaje,tipo)
{	
  if (tipo == 'error')
  {
    document.getElementById('msjepopupx').innerHTML = mensaje;
    document.getElementById('modal-headerx').style = "background:#CD5C5C";
    document.getElementById('myModalLabelx').innerHTML =  "Mensaje";
    $('#myModalx').modal()
  }
  else
  {
    document.getElementById('msjepopupx').innerHTML = mensaje
    document.getElementById('myModalLabelx').innerHTML =  "Ok";
    $('#myModalx').modal()		
  }
  
}

/*templates\fij_piedepagina.html*/
function muestramenu() {
    var cookie_dc = document.cookie;
    var cookie_prefix = "muestramenu" + "=";
    var cookie_begin = cookie_dc.indexOf("; " + cookie_prefix);

    if (cookie_begin == -1) {
        setCookie("muestramenu", "cerrado", "365");
    }
    else {
        var rescookie = getCookie("muestramenu");
        if (rescookie == "cerrado") {
            setCookie("muestramenu", "abierto", "365");
        }
        else {
            setCookie("muestramenu", "cerrado", "365");
        }
    }
}

function envia(){ 
    document.getElementById('RutEmpresa').value=document.getElementById('Empresa').value;
}

function mayus(elemento) {
    elemento.value = elemento.value.toUpperCase();
}