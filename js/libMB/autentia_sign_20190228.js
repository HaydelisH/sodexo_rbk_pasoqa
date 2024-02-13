/*Se busca el RUT de firma con el que se encuentra dentro del certificado*/
function toHex(str) {
    var hex = '';
    for (var i = 0; i < str.length; i++) {
        hex += '' + str.charCodeAt(i).toString(16);
    }
    
    var numhex = "";
    for(var i = 0; i<= hex.length; i++){    
        var c = hex.substr(i,1);        
        numhex = numhex + c;
        if(i%2 != 0 && i < hex.length-1){
            numhex = numhex+" ";
        }        
    }
    
    return numhex;
}

/*
function selectorCertificado(certificados, procesofirma){

    var rut = $('#actionForm').find('input[name="rut"]').val();
    var hexrut = toHex(rut);

    var fecha_actual = new Date().getTime();
    var expire = false;

    for (var n in certificados) {
        var val = certificados[n];
        if(val.issuerName !== null){
            if (val.subjectName.search(rut) > 0 || val.subjectAltName2.search(hexrut) > 0) {
              var fecha_certificado = new Date(val.notAfter).getTime();
              if(fecha_certificado >  fecha_actual){
                  procesofirma({ParamsGet: {erc: 0, idCert: val.certId, rut: rut}});
                  return;
              }else{
                  expire = true;
              }
            }
        }
    }

    if(expire == false)
        procesofirma({ParamsGet: {erc: -1, ercText: "El e-token no corresponde al RUT firmante: " + rut}});
    else
        procesofirma({ParamsGet: {erc: -1, ercText: "El certificado para el " + rut + " ha expirado"}});
}
*/

/*Se busca el RUT de firma con el que se encuentra dentro del certificado*/
function selectorCertificado(certificados, procesofirma){

   var rut = $('#actionForm').find('input[name="rut"]').val();
  
    for (var n in certificados) {
        var val = certificados[n];
        if(val.issuerName !== null){
            if (val.subjectName.search(rut) > 0) {
                procesofirma({ParamsGet: {erc: 0, idCert: val.certId, rut: rut}});
                return;
            }
        }
    }
    procesofirma({ParamsGet: {erc: -1, ercText: "El e-token no corresponde al RUT firmante: " + rut}});
}

/*FIRMA TOKEN*/
function firmarToken() {
	
	res = ObtenerSesion();
		
	if (res == false)
	{
		return false;
	}

    /*URI del Servicio de Firma*/
    var uri = $('#actionForm').find('input[name="uri"]').val();
    
    /*Session del DEC5 */
    var session_id = $('#actionForm').find('input[name="session_id"]').val();
    var autentia = new plgAutentiaJS();

    /*Codigo de Documento*/
    var docs = $('#actionForm').find('input[name="documentos"]').val();

    /*INSTITUCION*/
    var institucion = $('#actionForm').find('input[name="institucion"]').val();

    var arrNroDocto = new Array();
	
    /* Por cada código del documento a firmar se debe armar un array con cada URI que apunta al servicio de firma*/
    arrNroDocto[0] = uri + "?CodDocumento=" + docs + "&session_id=" + session_id + "&institucion=" + institucion;


    autentia.firmarDocumentos(arrNroDocto, selectorCertificado, function (exito, response) {
        if (exito != true)
		{
			document.getElementById('cargando').style.display='none';
        	alert(response.ParamsGet.ercText);
		}
        else
		{
			document.getElementById('status').value = "200";
			document.getElementById('message').value = "Su firma ha sido registrada con &eacute;xito";
			document.getElementById('cargando').style.display='block';
			document.getElementById("actionForm").submit();
        	//alert ("Firmado con exito");
		}
        	
    });

    return false;
}

function Verificar() {
     
	var verificación = selectorCertificado;
	
	alert (selectorCertificado);
   
    return false;
}


var conexion;
var intentossesion = 3;//cantidad de intentos para obtener sesion;
var intentos = 0;
						
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



function ObtenerSesion()
{	
	intentos++;
	var rut = $('#actionForm').find('input[name="rut"]').val();
	var pin = $('#actionForm').find('input[name="pin"]').val();
	
	respuesta = "";
	conexion=crearXMLHttpRequest();
	// Preparamos la petición con parametros
	conexion.open('POST', 'obtenersesion.php?rut=' + rut + "&pin=" + pin,false);
	// Realizamos la petición
	//alert ("antes de send");
	conexion.send(null);
	// Devolvemos el resultado
	respuesta =  conexion.responseText;	
	if (respuesta != "")
	{
		res = respuesta.split("|");
		if (res[0] != "200")
		{
			document.getElementById('mensajeError').innerHTML = "Problemas para obtener la sesion " + res[1];
			elementoError.className += "callout callout-danger";
			return false;
		}
		else
		{
			document.getElementById('session_id').value = res[1];
			return true;
		}
		
	}
	else
	{
		if (intentos < intentossesion)
		{
			ObtenerSesion();
		}
	}
	
	document.getElementById('mensajeError').innerHTML = "No fue posible obtener sesion";
	elementoError.className += "callout callout-danger";
	return false;
}	