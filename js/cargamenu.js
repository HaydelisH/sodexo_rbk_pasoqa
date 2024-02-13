	if( document.getElementById("mensajeOK")){

		var elementoOK = document.getElementById("mensajeOK");

		if (elementoOK.innerHTML != "") {
			elementoOK.className += "callout callout-success";
			
			if(document.getElementById("mensajeError")){

				var elementoError = document.getElementById("mensajeError");
				elementoError.innerHTML = "";
				elementoError.className = "";
			}
		}
	}
	
	if(document.getElementById("mensajeError")){

		var elementoError = document.getElementById("mensajeError");

		if (elementoError.innerHTML != "") {
			elementoError.className += "callout callout-danger";
			
			if( document.getElementById("mensajeOK")){

				var elementoOK = document.getElementById("mensajeOK");
				elementoOK.innerHTML = "";
				elementoOK.className = "";
			}
		}
	}
	
	if(document.getElementById("mensajeAd")){

		var elementoAd = document.getElementById("mensajeAd");

		if (elementoAd.innerHTML != "") {
			elementoAd.className += "callout callout-warning";
		}
	}
		
	var opcionsel2 = getCookie("opcion");


	if (opcionsel2 == "Generar_Documento_PorFicha.php" || opcionsel2 == "Generar_Documentos_Masivo.php" || opcionsel2 == "importacionpdf.php") {
		//document.getElementById("li_generardocumentos").className = "active";
		document.getElementById("ul_generardocumentos").style.display = "block";
	}

	if (opcionsel2 == "Documentos_Aprobar.php" || opcionsel2 == "Documentos_Firmas.php" || opcionsel2 == "Documentos_FirmaMasiva.php" || opcionsel2 == "Documentos_FirmaTercero.php") {
		document.getElementById("ul_firmardocumentos").style.display = "block";
	}

	if (opcionsel2 == "MisDocumentos.php" || opcionsel2 == "Documentos_Pendientes.php" || opcionsel2 == "Documentos_Vigentes.php" || opcionsel2 == "Documentos_PorProcesos.php") {
		document.getElementById("ul_verdocumentos").style.display = "block";
	}

	if (opcionsel2 == "usuariosmant.php" || opcionsel2 == "tiposusuarios.php") {
		document.getElementById("ul_gestionusuarios").style.display = "block";
	}

	if (
		opcionsel2 == "TiposDocumentos.php" || opcionsel2 == "PlantillasPorEmpresas.php" || opcionsel2 == "Plantillas.php" || opcionsel2 == "Clausulas.php"
		|| opcionsel2 == "Categorias.php" || opcionsel2 == "Turnos.php" || opcionsel2 == "Jornadas.php" || opcionsel2 == "Causales.php"
	) {
		document.getElementById("ul_gestionplantilla").style.display = "block";
	}

	if (opcionsel2 == "Procesos.php" || opcionsel2 == "Correo.php" || opcionsel2 == "Feriados.php") {
		document.getElementById("ul_procesos").style.display = "block";
	}

	if (opcionsel2 == "Empresas.php" || opcionsel2 == "LugaresPago.php" || opcionsel2 == "CentrosCosto.php") {
		document.getElementById("ul_estructura").style.display = "block";
	}

	if
		(
		opcionsel2 == "flujofirma.php" || opcionsel2 == "Cargos.php" || opcionsel2 == "enrolar.php" || opcionsel2 == "crearpin.php"
		|| opcionsel2 == "asignacionroles.php" || opcionsel2 == "VerificarIdentidad.php" || opcionsel2 == "asignacionperfiles.php"
		|| opcionsel2 == "ObtenerEnrolado.php"
	) {
		document.getElementById("ul_gestionfirmas").style.display = "block";
	}

	/*document.getElementById(opcionsel2).className = "active";

	OcultarCargando();

/*

<php:repeticion id="formulario">
<php:repeticion id="opciones">
	if (document.getElementById('<php:item id="opcionid" />'))
	{
		document.getElementById('<php:item id="opcionid" />').style.display = "block";
	}
</php:repeticion>
</php:repeticion>

function HabilitaMenu(idmenu)
{
$(document).ready(function(){
	var contenido = "#ul_" + idmenu + " li";
	$(contenido).each(function(){
		var estilo = $(this).attr('style');
		if (estilo.indexOf("block") > -1)
		{	
			document.getElementById('li_' + idmenu).style.display = "block";
		}
	});
});
}

HabilitaMenu("gestionusuarios");
HabilitaMenu("gestionplantilla");
HabilitaMenu("gestionfirmas");
HabilitaMenu("rl_gestionfirmas");
HabilitaMenu("firmardocumentos");
HabilitaMenu("estructura");		   
HabilitaMenu("generardocumentos");
HabilitaMenu("rl_generardocumentos");
HabilitaMenu("rl_gestionplantilla");
HabilitaMenu("rl_relacionesLaborales");
HabilitaMenu("rl_firmardocumentos");
HabilitaMenu("rl_verdocumentos");
HabilitaMenu("verdocumentos");
HabilitaMenu("procesos");
HabilitaMenu("flujoautomatizacion");
HabilitaMenu("formularios");
HabilitaMenu("formulariosMantenedor");
$(document).ready(function(){
ocultaPrincipal();
});
function ocultaPrincipal()
{
var contadorVisible = 0;
$(".relacionLaboral").each(function(i,e){
	if ($(e).css("display") == 'block')
	{
		contadorVisible++;
	}
});
if (contadorVisible == 0)
{
	$("#relacionLaboral").hide();
}
contadorVisible = 0;
$(".gestionProyectos").each(function(i,e){
	if ($(e).css("display") == 'block')
	{
		contadorVisible++;
	}
});
if (contadorVisible == 0)
{
	$("#gestionProyectos").hide();
}
contadorVisible = 0;
$(".mantenedores").each(function(i,e){
	if ($(e).css("display") == 'block')
	{
		contadorVisible++;
	}
});
if (contadorVisible == 0)
{
	$("#mantenedores").hide();
}
}
function OpcionMenu(p_opcion)
{
MostrarCargando();
setCookie ("opcion",p_opcion,"365");

}
</script>

//Variables globales
var conexion;
var salida;
var parametros;

function crearXMLHttpRequest() {
	var xmlHttp = null;
	if (window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	else
	if (window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	return xmlHttp;
}

function cargaMenu(url, parametros) {

	conexion = crearXMLHttpRequest();
	conexion.onreadystatechange = procesaMenu;
	conexion.open('POST', url, false);
	conexion.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	conexion.send(parametros);
}

function procesaMenu() {
	if (conexion.readyState == 4) {
		// Escribimos el resultado en la pagina HTML mediante DHTML
		salida = conexion.responseText;
        let listado = JSON.parse(salida);
        let num = listado.length;

        if( num > 0 ){
            $.each(listado, function( index, value ) {
                if(document.getElementById('li_'+value.opcionid))			
                {
					if ('li_'+value.opcionid == "li_2" || 'li_'+value.opcionid == "li_3" || 'li_'+value.opcionid == "li_17" || 'li_'+value.opcionid == "li_13" ){
						document.getElementById('li_reportes').classList.remove("ocultarMenu");
					}
				
					document.getElementById('li_'+value.opcionid).classList.remove("ocultarMenu");
                }
            });				
        }
	}
}

cargaMenu('cargaMenu_ajax.php','');
OcultarCargando();

*/

