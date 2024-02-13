//******************************************
var Autentia = new plgAutentiaJS();

function $(elementId) {
var result = document.getElementById(elementId);
return result;
}

function $V(elementId) {
  var result;
  result = $(elementId).value;
  return result;
}

function generaToken() {
  var valToken;
  var d = new Date();
  var valToken = d.getTime();
  return valToken;
}

function validaToken(SToken1, SToken2) {
  return SToken1 == SToken2;
}

function verificarM()
{
  var tToken = generaToken();
  var rutFirmante = $V("__RUTFIRMANTE");
  Autentia.IniciarSesion(rutFirmante,tToken,function(response) // aca se pasa el rut para que abra el fpsensor, no pedira huella al operador
  {           
    if ( validaToken(tToken, response.token))
    {
      if (response.ParamsGet.LoginResult == 0) 
      {
        try {
            tToken = generaToken(); // funcion que genera token, fue enviada cada vez que se llama al multibroser se debe generar un token
            var focoAutentia = true;
            var RUT = rutFirmante.split("-");
            var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
               Rut: RUT[0],
               DV: RUT[1]
            };
            
            var salidas = ["Erc","ErcDesc","NroAudit","Nomb","Inst"];   //<-- paramentros de salida de la transacción
                
            Autentia.Transaccion2('../RUBRIKA/verifica',entradas,salidas,focoAutentia,tToken,
            function(resultado)
            {
                if (validaToken(tToken, resultado.token)) 
                {							
                  if(resultado.ParamsGet.Erc == "0")        //verifiacion exitosa
                  {					
					document.getElementById('cargando').style.display='block'; 
                    __doPostBackEncoding("Firmado", resultado.ParamsGet.NroAudit);
                  } else { // Veriricacion no exitosa
						document.getElementById('cargando').style.display='none';
                      alert(resultado.ParamsGet.ErcDesc);
                  }
                }
                else
                  alert("Token invalido");
            })								
          }catch (ex) {
            alert(ex.message);
          }				
      }
    }
  });
    return false;
}

function verificar_DsSim()
{
	var rut = $V('__RUTFIRMANTE');
	
  __doPostBack("Firmado", "RBKA-P2BG-688P-2YRS");
}

function verificar()
{
  var tToken = generaToken();
  var rutFirmante = $V("__RUTFIRMANTE");
  try {
      //Asignacion de parametros de entrada
      var RUT = rutFirmante.split("-");
      var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
         Rut: RUT[0],
         DV: RUT[1]
      };
      //Definicion de parametros de salida
      var salidas = ["Erc", "NroAudit", "ErcDesc", "oNombres", "oSexo", "oFchNac"];
      //Asignacion a variable focoAutentia, la cual puede ser
      //true (siempre mantiene el foco la ventana Autentia) o false (puede perder el foco la ventana Autentia)
      var focoAutentia = true;
      //Llamada de transaccion
      Autentia.Transaccion2('../RUBRIKA/verifica', entradas, salidas, focoAutentia, tToken, 
      function(resultado){
          //Obtencion de los valores de retorno de la transaccion
          if (validaToken(tToken, resultado.token)) 
		  {
			  if(resultado.ParamsGet.Erc == "0"  )        //verifiacion exitosa
			  {
				document.getElementById('cargando').style.display='block';			  
				__doPostBackEncoding("Firmado", resultado.ParamsGet.NroAudit);				
			  } else { // Veriricacion no exitosa
			  				document.getElementById('cargando').style.display='none';
				alert('Error : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.ErcDesc);				
			  }
			  //Terminar();
          }
      });
  } catch (ex) {
      alert(ex.message);
  }
}

/**************************************************************/
function LoginOperador(eventTarget, eventArgument)
{
  var Ok = false;
  var RutOper = $V("__RUTFUNCIONARIO");
  var Ttoken = generaToken();

  Autentia.IniciarSesionLogin(RutOper, Ttoken, function(response){
    if (validaToken(Ttoken, response.token)) {
      //if (response.ParamsGet.hasOwnProperty('LoginResult')) {
      //  alert(response.ParamsGet.LoginResult);
     //}
      if (response.ParamsGet.LoginResult == "0")
      {
        loggedIN = true;
        Ok = true;
        if ( eventTarget != null && eventArgument != null )
          __doPostBack(eventTarget,eventArgument);
        //alert("Inicio Sesion");
      }
    } else {
      alert('Token invalido...');
    };
  })
  return Ok;
}

function Terminar()
{
  var Ttoken = generaToken();

  Autentia.CerrarSesion(Ttoken);
}

function verificarHuella()
{
  var Ok = false;
  var RutOper = $V("__RUTFUNCIONARIO");
  var Ttoken = generaToken(RutOper);

  Autentia.IniciarSesionLogin(RutOper, Ttoken, function(response){
    if (validaToken(Ttoken, response.token)) {
 //     if (response.ParamsGet.hasOwnProperty('LoginResult')) {
 //       alert(response.ParamsGet.LoginResult);
 //     }
      if (response.ParamsGet.LoginResult == "0")
      {
        loggedIN = true;        		
		verificar();
		Ok=true;
		
      }
    } else {
      alert('Token invalido...');
    };
  })
	
	return Ok;
}


function verificaP()
{
  var tToken = generaToken();
  var rutFirmante = $V("__Rut");
  var dvFirmante = $V("__DV");
  try {
      //Asignacion de parametros de entrada
      //var RUT = rutFirmante.split("-");
      var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
         Rut: rutFirmante,
         DV: dvFirmante
      };
      //Definicion de parametros de salida
      var salidas = ["Erc", "NroAudit", "ErcDesc", "oNombres", "oSexo", "oFchNac"];
      //var salidas = ["Erc","ErcDesc","NroAudit","Nomb","Inst"];   <-- paramentros de salida de la transacci?n
	  //Asignacion a variable focoAutentia, la cual puede ser
      //true (siempre mantiene el foco la ventana Autentia) o false (puede perder el foco la ventana Autentia)
      var focoAutentia = true;
      //Llamada de transaccion
      Autentia.Transaccion2('../RUBRIKA/verifica', entradas, salidas, focoAutentia, tToken, 
      function(resultado){
          //Obtencion de los valores de retorno de la transaccion
          if (validaToken(tToken, resultado.token)) 
		  {
			  if(resultado.ParamsGet.Erc == "0"  )        //verifiacion exitosa
			  {
				alert("Validacion correcta");
			  } else
					if ( resultado.ParamsGet.Erc == 11001 )
					{
					   alert("Enrrolamiento Correcto" );
					  // Ahora que tenemos la respuesta de autentia, en Params, nos enviamos por POST el numero de auditoria
					}
					  else { // Veriricacion no exitosa
						alert('Error : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.ErcDesc);
					  }
          }
      });
  } catch (ex) {
      alert(ex.message);
  }
  return false;
}

function verificar2() {
    var tToken = generaToken();
    var rutFirmante = document.getElementById('_rut').value;
    try {
        //Asignacion de parametros de entrada
        var RUT = rutFirmante.split("-");
        var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
            Rut: RUT[0],
            DV: RUT[1]
        };
        //Definicion de parametros de salida
        var salidas = ["Erc", "NroAudit", "ErcDesc", "oNombres", "oSexo", "oFchNac"];
        //Asignacion a variable focoAutentia, la cual puede ser
        //true (siempre mantiene el foco la ventana Autentia) o false (puede perder el foco la ventana Autentia)
        var focoAutentia = true;
        //Llamada de transaccion
        Autentia.Transaccion2('../RUBRIKA/verifica', entradas, salidas, focoAutentia, tToken,
        function (resultado) {
            //Obtencion de los valores de retorno de la transaccion
            if (validaToken(tToken, resultado.token)) {
                if (resultado.ParamsGet.Erc == "0")        //verifiacion exitosa
                {
                    alert('Erc : ' + resultado.ParamsGet.Erc + ' - Nro Auditoria : ' + resultado.ParamsGet.NroAudit + ' - ErcDesc : ' + resultado.ParamsGet.ErcDesc);
                    document.getElementById('nroauditoria2').value = resultado.ParamsGet.NroAudit;
                    //alert('Verificacion  : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.NroAudit + resultado.ParamsGet.ErcDesc);
                } else { // Veriricacion no exitosa
                    
                    alert('Error : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.ErcDesc);
                }
                //Terminar();
            }
        });
        //document.getElementById('nroauditoria2').value = resultado.ParamsGet.NroAudit;
        //var auditoria = resultado.ParamsGet.NroAudit;
        //return auditoria;
    } catch (ex) {
        alert(ex.message);
    }

}
/*----------------------------------------------------------------------------------------------------------------------------*/
var cargax;
function verificar3(tipo)
{
    cargax = setInterval(function () { cargando() }, 100);
    //clearInterval(cargax);
        var tToken = generaToken();
        var rutFirmante = document.getElementById('_rut').value;
        try {
            //Asignacion de parametros de entrada
            var RUT = rutFirmante.split("-");
            var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
                Rut: RUT[0],
                DV: RUT[1]
            };
            //Definicion de parametros de salida
            var salidas = ["Erc", "NroAudit", "ErcDesc", "oNombres", "oSexo", "oFchNac"];
            //Asignacion a variable focoAutentia, la cual puede ser
            //true (siempre mantiene el foco la ventana Autentia) o false (puede perder el foco la ventana Autentia)
            var focoAutentia = true;
            //Llamada de transaccion
            Autentia.Transaccion2('../RUBRIKA/verifica', entradas, salidas, focoAutentia, tToken,
            function (resultado) {
                //Obtencion de los valores de retorno de la transaccion
                if (validaToken(tToken, resultado.token)) {
                    if (resultado.ParamsGet.Erc == "0")        //verifiacion exitosa
                    {
                        //alert('Erc : ' + resultado.ParamsGet.Erc + ' - Nro Auditoria : ' + resultado.ParamsGet.NroAudit + ' - ErcDesc : ' + resultado.ParamsGet.ErcDesc);
                        document.getElementById('hddNumAuditoria').value = resultado.ParamsGet.NroAudit;
                        
                        var objAudit = document.getElementById("hddNumAuditoria");
                        
                        if (tipo == 0) {
                            if (objAudit.value != "" && objAudit.value != null)
                                document.getElementById('btnFirmaTrabajador').click();
                        } else {
                            if (objAudit.value != "" && objAudit.value != null)
                                document.getElementById('btnFirmaRRHH').click();
                        }

                        //alert('Verificacion  : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.NroAudit + resultado.ParamsGet.ErcDesc);
                    } else { // Veriricacion no exitosa
                          var cerrarx = setInterval(function () { cerrar() }, 100);
                        alert('Error : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.ErcDesc);
                    }
                    //Terminar();
                }
            });
            //document.getElementById('nroauditoria2').value = resultado.ParamsGet.NroAudit;
            //var auditoria = resultado.ParamsGet.NroAudit;
            //return auditoria;
        } catch (ex) {
            alert(ex.message);
        }
}
function cargando()
{
    document.getElementById('cargando').style.display = 'block';
}

function cerrar()
{
    clearInterval(cargax);
    document.getElementById('cargando').style.display = 'none';
}


function verificarh()
{
	
   // cargax = setInterval(function () { cargando() }, 100);
    //clearInterval(cargax);
        var tToken = generaToken();
        var rutFirmante = document.getElementById('rut').value;
        try {
            //Asignacion de parametros de entrada
            var RUT = rutFirmante.split("-");
            var entradas = {           // asi se definene los paramentros de entrada, creo que tb debe ir el DV si va el rut separado, sino solo rut
                Rut: RUT[0],
                DV: RUT[1]
            };
            //Definicion de parametros de salida
            var salidas = ["Erc", "NroAudit", "ErcDesc", "oNombres", "oSexo", "oFchNac"];
            //Asignacion a variable focoAutentia, la cual puede ser
            //true (siempre mantiene el foco la ventana Autentia) o false (puede perder el foco la ventana Autentia)
            var focoAutentia = true;
            //Llamada de transaccion
            Autentia.Transaccion2('../RUBRIKA/verifica', entradas, salidas, focoAutentia, tToken,
            function (resultado) {
                //Obtencion de los valores de retorno de la transaccion
                if (validaToken(tToken, resultado.token)) {
                    if (resultado.ParamsGet.Erc == "0")        //verifiacion exitosa
                    {
                        //alert('Erc : ' + resultado.ParamsGet.Erc + ' - Nro Auditoria : ' + resultado.ParamsGet.NroAudit + ' - ErcDesc : ' + resultado.ParamsGet.ErcDesc);
                        document.getElementById('auditoria').value = resultado.ParamsGet.NroAudit;
						document.getElementById('cargando').style.display='block';
                        document.getElementById("actionForm").submit();
                       
                        //alert('Verificacion  : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.NroAudit + resultado.ParamsGet.ErcDesc);
                    } else { // Veriricacion no exitosa
                         // var cerrarx = setInterval(function () { cerrar() }, 100);
						 document.getElementById('cargando').style.display='none';
                        alert('Error : ' + resultado.ParamsGet.Erc + ' ' + resultado.ParamsGet.ErcDesc);
                    }
                    //Terminar();
                }
            });
            //document.getElementById('nroauditoria2').value = resultado.ParamsGet.NroAudit;
            //var auditoria = resultado.ParamsGet.NroAudit;
            //return auditoria;
        } catch (ex) {
            alert(ex.message);
        }
}

