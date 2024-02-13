function validarNumerosLetras(id) 
{ 
    tecla = (document.all) ? id.keyCode : id.which;
    if (tecla==8) return true; 
    patron = /\w/; // Acepta nmeros y letras
    te = String.fromCharCode(tecla);
    return patron.test(te); 
} 



function obtenerElemento(id)
{
	var elemento;
	if(document.all) // IE5 
		elemento = document.all[id];
	if(document.getElementById) // IE6+, Mozila, Opera
		elemento = document.getElementById(id);
	return elemento;

}

function obtenerTexto(elemento)  
{
	if (elemento.text != undefined)
		return elemento.text;
	if (elemento.textContent != undefined)
		return elemento.textContent;
	return elemento.value;
}

function escribirTexto(elemento,texto)  
{
	if (elemento.innerText != undefined) 
	{
		elemento.innerText=texto;
		return true;
	}
	elemento.textContent=texto;
	return true;

}

function validarFecha(dtStr)	
{
	var dtCh= "/";
	var minYear=1900;
	var maxYear=2100;

	var daysInMonth = DaysArray(12);
	var pos1=dtStr.indexOf(dtCh);
	var pos2=dtStr.indexOf(dtCh,pos1+1);
	var strDay=dtStr.substring(0,pos1);
	var strMonth=dtStr.substring(pos1+1,pos2);
	var strYear=dtStr.substring(pos2+1);
	var strYr=strYear;
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1);
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1);
	}
	var xmonth=parseInt(strMonth);
	var xday=parseInt(strDay);
	var xyear=parseInt(strYr);
	if (pos1==-1 || pos2==-1){
		//mensajeValidacion("The date format should be : dd/mm/yyyy");
		return false;
	}
	if (strMonth.length<1 || xmonth<1 || xmonth>12){
		//mensajeValidacion("Please enter a valid month");
		return false;
	}
	if (strDay.length<1 || xday<1 || xday>31 || (xmonth==2 && xday>daysInFebruary(xyear)) || xday > daysInMonth[xmonth]){
		//mensajeValidacion("Please enter a valid day");
		return false;
	}
	if (strYear.length != 4 || xyear==0 || xyear<minYear || xyear>maxYear){
		//mensajeValidacion("Please enter a valid 4 digit year between "+minYear+" and "+maxYear);
		return false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		//mensajeValidacion("Please enter a valid date");
		return false;
	}
	return true;
}

function validarEmail(email)
{
  //  var re = /^[A-Za-z][A-Za-z0-9_\.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/
	var re = /^[0-9A-Za-z][A-Za-z0-9_\.-]*@[A-Za-z0-9_.-]+\.[A-Za-z0-9_.]+[A-za-z]$/
	var re = /^[0-9A-Za-z][A-Za-z0-9_\.-]*@[A-Za-z0-9_\.-]+\.[A-Za-z0-9_\.]+[A-za-z]$/ // Linde actual corregido
    return re.test(email);
}


function validarHora(value)
{
        var re = /^(0[1-9]|1\d|2[0-3]):([0-5]\d)$/
        return re.test(value);
}

function isInteger(value)
{
	var re = /^\-?[0-9]+$/
	return re.test(value);
}

function stripCharsInBag(s, bag){

    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (var i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year)
{
    // February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31;
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30;}
		if (i==2) {this[i] = 29;}
   } 
   return this;
}

function trim(str)
{
  return( (""+str).replace(/^\s*([\s\S]*\S+)\s*$|^\s*$/,'$1') );
}

function isNumeric(strString) //  check for valid numeric strings	
{
	var strValidChars = "0123456789.-";
	var blnResult = true;

	if (strString.length == 0) return false;
	//  test strString consists of valid characters listed above
	for (var i = 0; i < strString.length && blnResult == true; i++)
		if (strValidChars.indexOf(strString.charAt(i)) == -1)
			blnResult = false;
	return blnResult;
}

function deshabilitarFila(cual)
{
	var fila = cual.parentNode.parentNode;
	for (var a=0;a<fila.cells.length;a++)
	{
		for (var b=0;b<fila.cells[a].childNodes.length;b++)
		{
			if (fila.cells[a].childNodes[b].type!="checkbox")
				continue;
			if (fila.cells[a].childNodes[b]==cual)
				continue;

			if (!cual.checked)
			{
				fila.cells[a].childNodes[b].disabled=true;
				fila.cells[a].childNodes[b].checked=false;
			}
			else
			{
				fila.cells[a].childNodes[b].disabled=false;
			}
		}
	}
	
}

function URLEncode(clearString) {
  var output = '';
  var x = 0;
  clearString = clearString.toString();
  var regex = /(^[a-zA-Z0-9_.]*)/;
  while (x < clearString.length) {
    var match = regex.exec(clearString.substr(x));
    if (match != null && match.length > 1 && match[1] != '') {
    	output += match[1];
      x += match[1].length;
    } else {
      if (clearString[x] == ' ')
        output += '+';
      else {
        var charCode = clearString.charCodeAt(x);
        var hexVal = charCode.toString(16);
        output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
      }
      x++;
    }
  }
  return output;
}


function validarFormulario(formulario,numericos,optativos)
{
	try{

	if (formulario == null)
		formulario = document.formulario.form;


	for (var a=0;a<document.formulario.elements.length;a++)
	{

		if (optativos)
		{
			var salir=false;

			for (b=0; b<optativos.length; b++)
			{
				if (optativos[b]==document.formulario.elements[a].name)
				{
					salir=true;
					break;
				}
			}
			if (salir)
				continue;
		}
	
		if (document.formulario.elements[a].name)
		{
		
			if (document.formulario.elements[a].value=="")
			{ 
				if (document.formulario.elements[a].type=="text")
				{
					
					document.formulario.elements[a].className="form-control has-error";
					document.formulario.elements[a].focus();

	                mensajeValidacion("Campo no debe estar vacio");
   				
					
					return false;
				}

			if (document.formulario.elements[a].type=="password")
				{
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Campo no debe estar vacio");
					return false;
				}


				if (document.formulario.elements[a].type=="select-one")
				{
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Seleccione para continuar");
					return false;
				}
			}
			
			if (document.formulario.elements[a].name=="email" || document.formulario.elements[a].name=="emailnew" || document.formulario.elements[a].name=="correo")
			{
				if (!validarEmail(document.formulario.elements[a].value))
				{
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Campo debe ser un email");
					return false;
				}
			}


			if (document.formulario.elements[a].name.substring(document.formulario.elements[a].name.length-5,document.formulario.elements[a].name.length)=="fecha")
			{
				if (!validarFecha(document.formulario.elements[a].value))
				{
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Campo debe ser una fecha");
					return false;
				}
			}
			if (document.formulario.elements[a].name.substring(document.formulario.elements[a].name.length-4,document.formulario.elements[a].name.length)=="hora")
			{
				if (!validarHora(document.formulario.elements[a].value))
				{
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Campo debe ser una hora");
					return false;
				}
			}
			
			if (document.formulario.elements[a].type=="select-one") {
			
				var nomelemento = document.formulario.elements[a].name;
				var element = document.getElementById(nomelemento);
				if (element != null){
					var texto = element.options[element.selectedIndex].text;
					if (texto == "(Seleccione)") {
						document.formulario.elements[a].className="erroneo form-control";
						document.formulario.elements[a].focus();
						mensajeValidacion("Debe Seleccionar");
						return false;				
					}
				}
			}			

			
		}
		if (numericos)
		{
			for (var b=0; b<numericos.length; b++)
			{
				if (numericos[b]==document.formulario.elements[a].name)
				{
					if (!isNumeric(document.formulario.elements[a].value))
					{
						document.formulario.elements[a].className="erroneo form-control";
						document.formulario.elements[a].focus();
						mensajeValidacion("Campo " + document.formulario.elements[a].name + " debe ser numerico");
						return false;
					}
				}
			}
		}
		
		if (document.formulario.elements[a].type=="select-one") {
		
			var nomelemento = document.formulario.elements[a].name;
			var element = document.getElementById(nomelemento);
			if (element != null){
				var texto = element.options[element.selectedIndex].text;
				if (texto == "(Seleccionar)") {
					document.formulario.elements[a].className="erroneo form-control";
					document.formulario.elements[a].focus();
					mensajeValidacion("Debe Seleccionar");
					return false;				
				}
			}
		}		
	}
	return true;
 }
 catch(err) {
 	err.message;
 }

}

// Relacion Laboral
function validarFormulario2(formulario,numericos,optativos)
{

	if (formulario2 == null)
		formulario2 = document.formulario2.form;

	
	for (var a=0;a<document.formulario2.elements.length;a++)
	{

		if (optativos)
		{
			var salir=false;

			for (b=0; b<optativos.length; b++)
			{
				if (optativos[b]==document.formulario2.elements[a].name)
				{
					salir=true;
					break;
				}
			}
			if (salir)
				continue;
		}
	
		if (document.formulario2.elements[a].name)
		{
		
			if (document.formulario2.elements[a].value=="")
			{
				if (document.formulario2.elements[a].type=="text")
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Campo no debe estar vacio");
					return false;
				}

			if (document.formulario2.elements[a].type=="password")
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Campo no debe estar vacio");
					return false;
				}


				if (document.formulario2.elements[a].type=="select-one")
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Seleccione para continuar");
					return false;
				}
			}
			
			if (document.formulario2.elements[a].name=="email" || document.formulario2.elements[a].name=="emailnew")
			{
				if (!validarEmail(document.formulario2.elements[a].value))
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Campo debe ser un email");
					return false;
				}
			}
			if (document.formulario2.elements[a].name.substring(document.formulario2.elements[a].name.length-5,document.formulario2.elements[a].name.length)=="fecha")
			{
				if (!validarFecha(document.formulario2.elements[a].value))
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Campo debe ser una fecha");
					return false;
				}
			}
			if (document.formulario2.elements[a].name.substring(document.formulario2.elements[a].name.length-4,document.formulario2.elements[a].name.length)=="hora")
			{
				if (!validarHora(document.formulario2.elements[a].value))
				{
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Campo debe ser una hora");
					return false;
				}
			}
			
			if (document.formulario2.elements[a].type=="select-one") {
			
				var nomelemento = document.formulario2.elements[a].name;
				var element = document.getElementById(nomelemento);
				if (element != null){
					var texto = element.options[element.selectedIndex].text;
					if (texto == "(Seleccione)") {
						document.formulario2.elements[a].className="erroneo form-control input-sm";
						document.formulario2.elements[a].focus();
						alert("Debe Seleccionar");
						return false;				
					}
				}
			}			

			
		}
		if (numericos)
		{
			for (var b=0; b<numericos.length; b++)
			{
				if (numericos[b]==document.formulario2.elements[a].name)
				{
					if (!isNumeric(document.formulario2.elements[a].value))
					{
						document.formulario2.elements[a].className="erroneo form-control input-sm";
						document.formulario2.elements[a].focus();
						alert("Campo " + document.formulario2.elements[a].name + " debe ser numerico");
						return false;
					}
				}
			}
		}
		
		if (document.formulario2.elements[a].type=="select-one") {
		
			var nomelemento = document.formulario2.elements[a].name;
			var element = document.getElementById(nomelemento);
			if (element != null){
				var texto = element.options[element.selectedIndex].text;
				if (texto == "(Seleccionar)") {
					document.formulario2.elements[a].className="erroneo form-control input-sm";
					document.formulario2.elements[a].focus();
					alert("Debe Seleccionar");
					return false;				
				}
			}
		}		
	}
	return true;
}

// Relacion Laboral
function validarFormulario3(formulario,numericos,optativos)
{

	if (formulario3 == null)
		formulario3 = document.formulario3.form;

	
	for (var a=0;a<document.formulario3.elements.length;a++)
	{

		if (optativos)
		{
			var salir=false;

			for (b=0; b<optativos.length; b++)
			{
				if (optativos[b]==document.formulario3.elements[a].name)
				{
					salir=true;
					break;
				}
			}
			if (salir)
				continue;
		}
	
		if (document.formulario3.elements[a].name)
		{
		
			if (document.formulario3.elements[a].value=="")
			{
				if (document.formulario3.elements[a].type=="text")
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Campo no debe estar vacio");
					return false;
				}

			if (document.formulario3.elements[a].type=="password")
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Campo no debe estar vacio");
					return false;
				}


				if (document.formulario3.elements[a].type=="select-one")
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Seleccione para continuar");
					return false;
				}
			}
			
			if (document.formulario3.elements[a].name=="email" || document.formulario3.elements[a].name=="emailnew")
			{
				if (!validarEmail(document.formulario3.elements[a].value))
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Campo debe ser un email");
					return false;
				}
			}
			if (document.formulario3.elements[a].name.substring(document.formulario3.elements[a].name.length-5,document.formulario3.elements[a].name.length)=="fecha")
			{
				if (!validarFecha(document.formulario3.elements[a].value))
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Campo debe ser una fecha");
					return false;
				}
			}
			if (document.formulario3.elements[a].name.substring(document.formulario3.elements[a].name.length-4,document.formulario3.elements[a].name.length)=="hora")
			{
				if (!validarHora(document.formulario3.elements[a].value))
				{
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Campo debe ser una hora");
					return false;
				}
			}
			
			if (document.formulario3.elements[a].type=="select-one") {
			
				var nomelemento = document.formulario3.elements[a].name;
				var element = document.getElementById(nomelemento);
				if (element != null){
					var texto = element.options[element.selectedIndex].text;
					if (texto == "(Seleccione)") {
						document.formulario3.elements[a].className="erroneo form-control input-sm";
						document.formulario3.elements[a].focus();
						alert("Debe Seleccionar");
						return false;				
					}
				}
			}			

			
		}
		if (numericos)
		{
			for (var b=0; b<numericos.length; b++)
			{
				if (numericos[b]==document.formulario3.elements[a].name)
				{
					if (!isNumeric(document.formulario3.elements[a].value))
					{
						document.formulario3.elements[a].className="erroneo form-control input-sm";
						document.formulario3.elements[a].focus();
						alert("Campo " + document.formulario3.elements[a].name + " debe ser numerico");
						return false;
					}
				}
			}
		}
		
		if (document.formulario3.elements[a].type=="select-one") {
		
			var nomelemento = document.formulario3.elements[a].name;
			var element = document.getElementById(nomelemento);
			if (element != null){
				var texto = element.options[element.selectedIndex].text;
				if (texto == "(Seleccionar)") {
					document.formulario3.elements[a].className="erroneo form-control input-sm";
					document.formulario3.elements[a].focus();
					alert("Debe Seleccionar");
					return false;				
				}
			}
		}		
	}
	return true;
}

function validaRut(texto)
{ 
	//csb 25-11-2019
	resp = texto.indexOf (" ")
	if (resp > -1)
	{
		mensajeValidacion('Rut en formato invalido, no debe contener espacios');
		return false;		
	}
	//fin
	
	/// EXPRESIONES REGULARES
	//mensajeValidacion('x' + texto);
	var reg_rut=/[0-9]{7,8}\-[k|K|0-9]/;
	// si no pasa el test entonces no es un rut valido
	if(!reg_rut.test(texto)) {
		mensajeValidacion('Rut en formato invalido, debe contener a lo menos 7 numeros, un guion y el digito verificador, ej: 12345678-9');
		return false;
	}
	// Separamos el rut del digito verificador
	var array_rut=texto.split("-");
	var rut=array_rut[0];
	var dv=array_rut[1];
	// si es K minuscula la agrandamos
	if(dv == 'k') dv = 'K';	

	// calculamos y si son iguales esta todo bien
	if (dv!=calcularDigito(rut)) {
		mensajeValidacion('Rut Incorrecto');
		return false;
	}
	return true;
}

// Relacion Laboral
function validaRut3(texto,nomelem)
{ 
	//csb 25-11-2019
	resp = texto.indexOf (" ")
	if (resp > -1)
	{
		alert('Rut en formato invalido, no debe contener espacios');
		return false;		
	}
	// fin
	
	/// EXPRESIONES REGULARES
	var reg_rut=/[0-9]{7,8}\-[k|K|0-9]/;
	// si no pasa el test entonces no es un rut valido
	if(!reg_rut.test(texto)) {
		alert('Rut en formato invalido, debe contener a lo menos 7 numeros, un guion y el digito verificador, ej: 12345678-9');
		document.getElementById(nomelem).focus();
		return false;
	}
	// Separamos el rut del digito verificador
	var array_rut=texto.split("-");
	var rut=array_rut[0];
	var dv=array_rut[1];
	// si es K minuscula la agrandamos
	if(dv == 'k') dv = 'K';	

	// calculamos y si son iguales esta todo bien
	if (dv!=calcularDigito(rut)) {
		document.getElementById(nomelem).focus();
		alert('Rut Incorrecto');
		return false;
	}
	return true;
}

function validaRut2(obj)
{ 	
	var texto = obj.value;
	
	//csb 25-11-2019
	resp = texto.indexOf (" ")
	if (resp > -1)
	{
		mensajeValidacion('Rut en formato invalido, no debe contener espacios');
		return false;		
	}
	//fin

	/// EXPRESIONES REGULARES
	var reg_rut=/[0-9]{7,8}\-[k|K|0-9]/;
	// si no pasa el test entonces no es un rut valido
	if(!reg_rut.test(texto)) {
		mensajeValidacion('Rut en formato invalido, debe contener a lo menos 7 numeros, un guion y el digito verificador, ej: 12345678-9');
		return false;
	}
	// Separamos el rut del digito verificador
	var array_rut=texto.split("-");
	var rut=array_rut[0];
	var dv=array_rut[1];
	// si es K minuscula la agrandamos
	if(dv == 'k') dv = 'K';	

	// calculamos y si son iguales esta todo bien
	if (dv!=calcularDigito(rut)) {
		mensajeValidacion('RUT invalido');
		obj.focus();
		return false;
	}
	return true;
}

function calcularDigito(rut)
{
	var largo=rut.length;
	var mult=2;
	var suma=0;
	largo--;
	while(largo>=0)
	{
		suma=suma+(rut.charAt(largo)*mult);
		if(mult>6)
			mult=2;
		else
			mult++;
		largo--;
	}

	var resto = suma%11;
	var digito = 11-resto
	if(digito==10)
		digito="K";
	else
		if(digito==11)
			digito=0;

	return digito;
}

function actualizarRut(cual) 
{
	escribirTexto(obtenerElemento(cual + '_rut'),calcularDigito(obtenerElemento(cual).value));
}

function mostrarOcultar(este,cual)
{
	if (este.checked)
		obtenerElemento(cual).className="mostrar";
	else
		obtenerElemento(cual).className="nomostrar";
}
function enviarActualizandoOculto(formulario,campo,conque)
{
	obtenerElemento(campo).value=conque;
	obtenerElemento(formulario).submit();
}


function fillSelectFromArray(selectCtrl, itemArray, selectednado, goodPrompt, badPrompt, defaultItem) { 
	var i, j, g; 
	var prompt; // empty existing items 
	for (i = selectCtrl.options.length; i >= 0; i--) { 
		selectCtrl.options[i] = null; 
	} 
	prompt = (itemArray != null) ? goodPrompt : badPrompt; 
	if (prompt == null) { 
		j = 0; 
	} else { 
		selectCtrl.options[0] = new Option(prompt); 
		j = 1; 
	} 
	g = 0;
	if (itemArray != null) { // add new items 
		for (i = 0; i < itemArray.length; i++) { 
			//mensajeValidacion(selectednado+" "+itemArray[i][0]);
			if (selectednado==itemArray[i][0]) {
				selectCtrl.options[j] = new Option(itemArray[i][2]); 
				if (itemArray[i][1] != null) { 
					selectCtrl.options[j].value = itemArray[i][1]; 
					g++;
				} 
				j++;
			}
		} // select first item (prompt) for sub list 
	} 
	
/*	selectCtrl.options[j] = new Option("Todas"); 
	selectCtrl.options[j].value = "-1"; 
	/*if (g != 0) {
		selectCtrl.disabled = false;
	} else {
		selectCtrl.disabled = true;
	}*/
	selectCtrl.selectedIndex=g;
}


function concero(que) {
	if (que<10) {
		return "0" + que;
	}
	return que;
}

function ultimaSemana() {
		var ahora = new Date();
		var myDate = new Date();
		myDate.setDate(myDate.getDate()-7);
		
		document.getElementById("fecha_desde").value = concero(myDate.getDate()) + "-" + concero(myDate.getMonth()+1) + "-" +  myDate.getFullYear();
		document.getElementById("fecha_hasta").value = concero(ahora.getDate()) + "-" + concero(ahora.getMonth()+1) + "-" + ahora.getFullYear();
	}
	
	function ultimoMes() {
		var ahora = new Date();
		var myDate = new Date();
		myDate.setMonth(myDate.getMonth()-1);
		
		document.getElementById("fecha_desde").value = concero(myDate.getDate()) + "-" + concero(myDate.getMonth()+1) + "-" + myDate.getFullYear();
		document.getElementById("fecha_hasta").value = concero(ahora.getDate()) + "-" + concero(ahora.getMonth()+1) + "-" + ahora.getFullYear();
		
	}
	
	function ultimoAno() {
		var ahora = new Date();
		var myDate = new Date();
		myDate.setMonth(myDate.getMonth()-3);
		
		document.getElementById("fecha_desde").value = concero(myDate.getDate()) + "-" + concero(myDate.getMonth()+1) + "-" + myDate.getFullYear();
		document.getElementById("fecha_hasta").value = concero(ahora.getDate()) + "-" + concero(ahora.getMonth()+1) + "-" + ahora.getFullYear();
		
	}

	function Todo() {
		document.getElementById("fecha_desde").value = "";
		document.getElementById("fecha_hasta").value = "";
		
	}	
	
	function pausecomp(millis)
	{
		var date = new Date();
		var curDate = null;

		do { curDate = new Date(); }
		while(curDate-date < millis);
	} 
	
	function achicarfoto(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		a++;
		if (a<516)
			imagen.style.width=a+'px';
	}
	function achicarfoto2(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		var b=parseInt(imagen.style.height);
		a++;
		b++;
		if (a<639) imagen.style.width=a+'px';
		if (b<479) imagen.style.height=b+'px';
	}
	function achicarfoto3(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		var b=parseInt(imagen.style.height);
		a++;
		b++;
		if (a<516) {
			imagen.style.width=a+'px';
			imagen.style.height=b+'px';
		}
	}
	function agrandarfoto(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		a--;
		if (a>199)
			imagen.style.width=a+'px';
	}
	function agrandarfoto2(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		var b=parseInt(imagen.style.height);
		a--;
		b--;
		if (a>319) imagen.style.width=a+'px';
		if (b>239) imagen.style.height=b+'px';
	}
	function agrandarfoto3(imagenid) {
		var imagen = document.getElementById(imagenid);
		var a=parseInt(imagen.style.width);
		var b=parseInt(imagen.style.height);
		a--;
		b--;
		if (a>15) {
			imagen.style.width=a+'px';
			imagen.style.height=b+'px';
		}
	}

	
	function tripleclick(imagen) {
		if (imagen.style.width=='200px') {
		
			imagen.style.float='none';
			imagen.style.marginTop='0px';
			var b=0;
			for (var a=200;a<416;a++) {
				b++;
				setTimeout('achicarfoto(\'' + imagen.id + '\')',a);
			}

		} else {

			var b=0;
			for (var a=415;a>199;a--) { 
				b++;
				setTimeout('agrandarfoto(\'' + imagen.id + '\')',b);
			}
			imagen.style.float='right';
			imagen.style.marginTop='0px';
		}
	}
	
	function tripleclick2(imagen) {
		if (imagen.style.width=='320px') {
		
			imagen.style.float='none';
			var b=0;
			for (var a=320;a<640;a++) {
				b++;
				setTimeout('achicarfoto2(\'' + imagen.id + '\')',a);
			}

		} else {

			var b=0;
			for (var a=639;a>319;a--) { 
				b++;
				setTimeout('agrandarfoto2(\'' + imagen.id + '\')',b);
			}
			imagen.style.float='right';
		}
	}

	function tripleclick3(imagen) {
		if (imagen.style.width=='16px') {
		
			imagen.style.float='none';
			var b=0;
			for (var a=16;a<416;a++) {
				b++;
				setTimeout('achicarfoto3(\'' + imagen.id + '\')',a);
			}
			imagen.style.position='absolute';
		} else {

			var b=0;
			for (var a=415;a>15;a--) { 
				b++;
				setTimeout('agrandarfoto3(\'' + imagen.id + '\')',b);
			}
			imagen.style.float='right';
			imagen.style.position='relative';
		}
	}


	function abrirVentanitaVisitas(edificioid,nombre_edificio,dptoid,fecha,rutvisita,nombre_visita,rutmorador,nombre_morador,observacion,rutconserje,nombre_conserje,patente,lugar_departamento,descripcion_departamento,nombrefoto,patenteinfo,estacionamiento,fecha2,descripcion_visita,descripcion_estado,observaciones,fechabase,rutproveedor,actualiza,pnombre,pdptoid,ppatente,pfecha_desde,pfecha_hasta,pingreso,pestacionid,prutempresa,ptipovisita,terminalentrada,terminalsalida,observacion_salida)
	{
		// creamos la nueva conversacion el el DIV
		var divPrincipal = document.createElement('div');
		divPrincipal.id = "ventana" + fecha;
		divPrincipal.className = "ventanas";
		divPrincipal.style.zOrder = 10;
		divPrincipal.style.left = Math.floor(Math.random()*200+50) + "px";
		divPrincipal.style.top = Math.floor(Math.random()*100) + "px";
		divPrincipal.style.width = "670px";
		divPrincipal.style.height = "550px";


		obtenerElemento('VentanasVisitas').appendChild(divPrincipal);
	
		var encabezado = document.createElement('div');
		encabezado.className = "encabezado";
		divPrincipal.appendChild(encabezado);
		
		// el link para moverlo
		var ahref = document.createElement('a');
		ahref.id="mover" + fecha;
		ahref.onmousedown = moverConversacion;
		ahref.title="Mover";
		ahref.className = "movedor";
		encabezado.appendChild(ahref);
			
		var aspan = document.createElement('span');
		aspan.className = "titulo";
		escribirTexto(aspan,"Detalle visita");
		ahref.appendChild(aspan);
		
		// puede cerrar una ventana
		var ahref = document.createElement('a');
		ahref.id="cerrar" + fecha;
		ahref.onclick=cerrarConversacion;
		ahref.title="Cerrar";
		ahref.className = "cerrar";
		encabezado.appendChild(ahref);
			
		// link para cerrar ventana
		var span = document.createElement('span');
		escribirTexto(span, 'X');
		ahref.appendChild(span);
			
		var cuerpo = document.createElement('div');
		cuerpo.className = "cuerpo";
		divPrincipal.appendChild(cuerpo);
		
		// Pie
		var div = document.createElement('div');
		div.className="pie";
		divPrincipal.appendChild(div);
			
		var aspan = document.createElement('span');
		aspan.className="izquerdo";
		div.appendChild(aspan);
		
		var aspan = document.createElement('span');
		aspan.className="centro";
		div.appendChild(aspan);
		
		
		var img = document.createElement('img');
		img.id="imagen" + fecha;
		img.onclick=function () {tripleclick2(this);}
		img.style.marginTop='0px'; 
		img.style.width='320px';
		img.style.height='240px';
		img.style.float='right';
		img.style.display='block';
		img.onerror=function(){this.style.display='none'};
		img.src='/deptoseguro/images/camara/' + nombrefoto + '.JPG';
	
		cuerpo.appendChild(img);
		cuerpo.appendChild(campito(lugar_departamento,nombre_edificio));
		cuerpo.appendChild(campito(descripcion_departamento,dptoid));

		fecyterment = fecha;
		fecytermsal = fecha2;
		if (terminalsalida != ''){
			if (terminalsalida != terminalentrada){	
				fecyterment = fecha + ' ' + terminalentrada;
				fecytermsal = fecha2 + ' ' + terminalsalida;			
			}
		}
		
		cuerpo.appendChild(campito("Fecha",fecyterment,fecytermsal));		
		if (rutvisita=="0-0") rutvisita="";
		cuerpo.appendChild(campito("Visita",rutvisita,nombre_visita));

		if (rutmorador=="0-0") rutmorador="";
		if (rutmorador=="-0") rutmorador="";
		cuerpo.appendChild(campito("Residente",rutmorador,nombre_morador));

		if (rutconserje=="0-0") rutconserje="";
		cuerpo.appendChild(campito("Operador",rutconserje,nombre_conserje));
		
		pag="/accesoseguro/modificatipovisita.php?edificioid=" + edificioid +"&recinto=" + nombre_edificio + "&empresa=" + dptoid + "&rutvisita=" + rutvisita + "&nomvisita=" + nombre_visita + "&fechahora=" + fecha + "&tipovisita=" + descripcion_visita + "&nombrefoto=" + nombrefoto + "&rutempresa=" + rutproveedor + "&fecha=" + fechabase;			
		pag=pag + "&pnombre=" + pnombre + "&pdptoid=" + pdptoid + "&ppatente=" + ppatente + "&pfecha_desde=" + pfecha_desde + "&pfecha_hasta=" + pfecha_hasta + "&pingreso=" + pingreso + "&pestacionid=" + pestacionid + "&prutempresa=" + prutempresa + "&ptipovisita=" + ptipovisita + "&pingreso=" + pingreso;
		var botonx = document.createElement('div');
		botonx.className = "botonx";
		divPrincipal.appendChild(botonx);		
		var bot = document.createElement("input");
		bot.setAttribute("type", "button");
                bot.setAttribute("value", "Modifica Tipo De Visita");
                bot.setAttribute("onclick", 'location.href=pag;' );		

		cuerpo.appendChild(campito("Patente",patenteinfo));
		cuerpo.appendChild(campito("Estacionamiento",estacionamiento));
		cuerpo.appendChild(campito("Tipo Visita",descripcion_visita));
		if (actualiza=="S"){cuerpo.appendChild(bot)};
		cuerpo.appendChild(campito("Estado",descripcion_estado));
		cuerpo.appendChild(campito("Comentario Entrada",observacion));
		cuerpo.appendChild(campito("Comentario Salida",observacion_salida));

		
	}

	function abrirVentanitaFoto(nombrefoto)
	{
		// creamos la nueva conversacion el el DIV
		var divPrincipal = document.createElement('div');
		divPrincipal.id = "ventana" + nombrefoto;
		divPrincipal.className = "ventanas";
		divPrincipal.style.zOrder = 10;
		divPrincipal.style.left = 200 + "px";
		divPrincipal.style.top = 50 + "px";
		divPrincipal.style.width = 675 + "px";
		divPrincipal.style.height = 560 + "px";
		divPrincipal.style.position= "absolute";
		divPrincipal.style.display = "";
		var ypos = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		divPrincipal.style.left = (screen.availWidth-675)/2 + 'px';
		divPrincipal.style.top = (screen.availHeight-560)/2 + ypos - 70 + 'px';

		obtenerElemento('VentanasVisitas').appendChild(divPrincipal);
	
		var encabezado = document.createElement('div');
		encabezado.className = "encabezado";
		divPrincipal.appendChild(encabezado);
		
		// el link para moverlo
		var ahref = document.createElement('a');
		ahref.id="mover" + nombrefoto;
		ahref.onmousedown = moverConversacion;
		ahref.title="Mover";
		ahref.className = "movedor";
		encabezado.appendChild(ahref);
			
		var aspan = document.createElement('span');
		aspan.className = "titulo";
		escribirTexto(aspan,"Detalle");
		ahref.appendChild(aspan);
		
		// puede cerrar una ventana
		var ahref = document.createElement('a');
		ahref.id="cerrar" + nombrefoto;
		ahref.onclick=cerrarConversacion;
		ahref.title="Cerrar";
		ahref.className = "cerrar";
		encabezado.appendChild(ahref);
			
		// link para cerrar ventana
		var span = document.createElement('span');
		escribirTexto(span, 'X');
		ahref.appendChild(span);
			
		var cuerpo = document.createElement('div');
		cuerpo.className = "cuerpo";
		divPrincipal.appendChild(cuerpo);
		
		// Pie
		var div = document.createElement('div');
		div.className="pie";
		divPrincipal.appendChild(div);
			
		var aspan = document.createElement('span');
		aspan.className="izquerdo";
		div.appendChild(aspan);
		
		var aspan = document.createElement('span');
		aspan.className="centro";
		div.appendChild(aspan);
		
		var img = document.createElement('img');
		img.id="imagen" + nombrefoto ;
		img.style.marginTop='0px'; 
		img.style.float='right';
		img.style.display='block';
		img.onerror=function(){this.style.display='none'};
		img.src='/deptoseguro/images/camara/' + nombrefoto + '.JPG';
		cuerpo.appendChild(img);
		
	}

	function abrirVentanitaFoto2(nombrefoto)
	{
		// creamos la nueva conversacion el el DIV
		var divPrincipal = document.createElement('div');
		divPrincipal.id = "ventana" + nombrefoto;
		divPrincipal.className = "ventanas";
		divPrincipal.style.zOrder = 10;
		divPrincipal.style.left = 200 + "px";
		divPrincipal.style.top = 50 + "px";
		divPrincipal.style.width = 675 + "px";
		divPrincipal.style.height = 560 + "px";
		divPrincipal.style.position= "absolute";
		divPrincipal.style.display = "";
		var ypos = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		divPrincipal.style.left = (screen.availWidth-675)/2 + 'px';
		divPrincipal.style.top = (screen.availHeight-560)/2 + ypos - 70 + 'px';

		obtenerElemento('VentanasVisitas').appendChild(divPrincipal);
	
		var encabezado = document.createElement('div');
		encabezado.className = "encabezado";
		divPrincipal.appendChild(encabezado);
		
		// el link para moverlo
		var ahref = document.createElement('a');
		ahref.id="mover" + nombrefoto;
		ahref.onmousedown = moverConversacion;
		ahref.title="Mover";
		ahref.className = "movedor";
		encabezado.appendChild(ahref);
			
		var aspan = document.createElement('span');
		aspan.className = "titulo";
		escribirTexto(aspan,"Mover Recuadro");
		ahref.appendChild(aspan);
		
		// puede cerrar una ventana
		var ahref = document.createElement('a');
		ahref.id="cerrar" + nombrefoto;
		ahref.onclick=cerrarConversacion;
		ahref.title="Cerrar";
		ahref.className = "cerrar";
		encabezado.appendChild(ahref);
			
		// link para cerrar ventana
		var span = document.createElement('span');
		escribirTexto(span, 'X');
		ahref.appendChild(span);
			
		var cuerpo = document.createElement('div');
		cuerpo.className = "cuerpo";
		divPrincipal.appendChild(cuerpo);
		
		// Pie
		var div = document.createElement('div');
		div.className="pie";
		divPrincipal.appendChild(div);
			
		var aspan = document.createElement('span');
		aspan.className="izquerdo";
		div.appendChild(aspan);
		
		var aspan = document.createElement('span');
		aspan.className="centro";
		div.appendChild(aspan);
		
		var img = document.createElement('img');
		img.id="imagen" + nombrefoto ;
		img.style.marginTop='0px';
		img.style.marginRight='170px'; 
		img.style.float='right';
		img.style.display='block';
		img.onerror=function(){this.style.display='none'};
		img.align="left";
		img.src='/deptoseguro/images/camara/' + nombrefoto + '.JPG';
		cuerpo.appendChild(img);
		
	}
	
	
	function abrirVentanitaFoto3(nombrefoto)
	{   
		// creamos la nueva conversacion el el DIV
		var divPrincipal = document.createElement('div');
		divPrincipal.id = "ventana" + nombrefoto;
		divPrincipal.className = "ventanas";
		divPrincipal.style.zOrder = 10;
		divPrincipal.style.left = 200 + "px";
		divPrincipal.style.top = 50 + "px";
		divPrincipal.style.width = 675 + "px";
		divPrincipal.style.height = 560 + "px";
		divPrincipal.style.position= "absolute";
		divPrincipal.style.display = "";
		var ypos = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		divPrincipal.style.left = (screen.availWidth-675)/2 + 'px';
		divPrincipal.style.top = (screen.availHeight-560)/2 + ypos - 70 + 'px';

		obtenerElemento('VentanasVisitas').appendChild(divPrincipal);

		var encabezado = document.createElement('div');
		encabezado.className = "encabezado";
		divPrincipal.appendChild(encabezado);
		
		// el link para moverlo
		var ahref = document.createElement('a');

		ahref.id="mover" + nombrefoto;
	
		moverConversacion="";
		ahref.onmousedown = moverConversacion;
	
		ahref.title="Mover";
	
		ahref.className = "movedor";
	
		encabezado.appendChild(ahref);
	
		var aspan = document.createElement('span');
		aspan.className = "titulo";
		escribirTexto(aspan,"Mover Recuadro");
		ahref.appendChild(aspan);
	
		// puede cerrar una ventana
		var ahref = document.createElement('a');
		
		ahref.id="cerrar" + nombrefoto;
	
		cerrarConversacion="";
		ahref.onclick=cerrarConversacion;
	
		ahref.title="Cerrar";
	
		ahref.className = "cerrar";
		
		encabezado.appendChild(ahref);
		
		// link para cerrar ventana
		var span = document.createElement('span');
		escribirTexto(span, 'X');
		ahref.appendChild(span);
			
		var cuerpo = document.createElement('div');
		cuerpo.className = "cuerpo";
		divPrincipal.appendChild(cuerpo);
		
		// Pie
		var div = document.createElement('div');
		div.className="pie";
		divPrincipal.appendChild(div);
		
		var aspan = document.createElement('span');
		aspan.className="izquerdo";
		div.appendChild(aspan);
		
		var aspan = document.createElement('span');
		aspan.className="centro";
		div.appendChild(aspan);
	
		var img = document.createElement('img');
		img.id="imagen" + nombrefoto ;
		img.style.marginTop='0px';
		img.style.marginRight='170px'; 
		img.style.float='right';
		img.style.display='block';
		img.onerror=function(){this.style.display='none'};
		img.align="left";
		img.src='fotos/' + nombrefoto + '.jpg';
		cuerpo.appendChild(img);
		
	}
	


	
	function campito(nombre, valor, valor2) {
	
		var div = document.createElement('div');
		var span = document.createElement('span');
		escribirTexto(span,nombre);
		span.className = "etiqueta";
		div.appendChild(span);
		var span = document.createElement('span');
		escribirTexto(span,valor);
		div.appendChild(span);

		if (valor2) {
			var span = document.createElement('br');
			div.appendChild(span);

			var span = document.createElement('span');
			escribirTexto(span,valor2);
			div.appendChild(span);
		}
		return div;
	}
	
	function preguntarPorBorrar() {
		return confirm("Esta seguro de eliminar?");
	}

	function preguntarPorClonar() {
		return confirm("Esta seguro de Clonar este Registro?");
	}
	
	
	function fillSelectFromArray2(selectCtrl, itemArray, selectednado, goodPrompt, badPrompt, defaultItem) { 
		var i, j, g,h; 
		var prompt; // empty existing items 
		
		
		for (i = selectCtrl.options.length; i >= 0; i--) { 
			selectCtrl.options[i] = null; 
		} 
		prompt = (itemArray != null) ? goodPrompt : badPrompt; 
		if (prompt == null) { 
			j = 0; 
		} else { 
			selectCtrl.options[0] = new Option(prompt); 
			j = 1; 
		} 
		
		g = 0;
		h = 0;
		if (itemArray != null) { // add new items 
			// primero ver con cuantos datos va cargar el combo
			for (i = 0; i < itemArray.length; i++) { 
				if (selectednado==itemArray[i][0]) {
					h++;
				}
			} 
			
			g = 0;
			for (i = 0; i < itemArray.length; i++) { 
				//mensajeValidacion(selectednado+" "+itemArray[i][0]);
				if (selectednado==itemArray[i][0]) {
					
					if (h > 1){ //si existe mas de un dato para llenar el combo, se salta un indice para dejar el todos como 0
						if (j==0){
						selectCtrl.options[j] = new Option(itemArray[j][1]); 
				   			j=1;
						} 				   			
					} 
					
					selectCtrl.options[j] = new Option(itemArray[i][2]); 
					if (itemArray[i][1] != null) { 
						selectCtrl.options[j].value = itemArray[i][1]; 
						g++;
					} 
					j++;
				}
			} // select first item (prompt) for sub list 			
		} 
		
	}


	function fillSelectFromArray3(selectCtrl, itemArray, selectednado, goodPrompt, badPrompt, defaultItem) {
		var i, j, g,h;
		var prompt; // empty existing items

		for (i = selectCtrl.options.length; i >= 0; i--) {
			selectCtrl.options[i] = null;
		}
		prompt = (itemArray != null) ? goodPrompt : badPrompt;
		if (prompt == null) {
			j = 0;
		} else {
			alert ("gg");
			selectCtrl.options[0] = new Option(prompt);
			j = 1;
		}

		g = 0;
		h = 0;
		if (itemArray != null) { // add new items
			// primero ver con cuantos datos va cargar el combo
			for (i = 0; i < itemArray.length; i++) {
				if (selectednado==itemArray[i][0]) {
					h++;
				}
			}

			g = 0;
			for (i = 0; i < itemArray.length; i++) {
				//mensajeValidacion(selectednado+" "+itemArray[i][0]);
				if (selectednado==itemArray[i][0]) {

					if (h > 1){ //si existe mas de un dato para llenar el combo, se salta un indice para dejar el todos como 0
						if (j==0){
					//	selectCtrl.options[j] = new Option(itemArray[j][1]);
				   			j=1;
						}
					}

					selectCtrl.options[j] = new Option(itemArray[i][2]);
					if (itemArray[i][1] != null) {
						selectCtrl.options[j].value = itemArray[i][1];
						g++;
					}
					j++;
				}
			} // select first item (prompt) for sub list

			if (g > 1){
				selectCtrl.options[0] = new Option("(Seleccione)");
				selectCtrl.selectedIndex=0;
			}
		}

	}
	
	function fillSelectFromArray2t(selectCtrl, itemArray, selectednado, goodPrompt, badPrompt, defaultItem) { 
		var i, j, g,h; 
			
		var prompt; // empty existing items 
		
		
		for (i = selectCtrl.options.length; i >= 0; i--) { 
			selectCtrl.options[i] = null; 
		} 
		prompt = (itemArray != null) ? goodPrompt : badPrompt; 
		if (prompt == null) { 
			j = 0; 
		} else { 
			selectCtrl.options[0] = new Option(prompt); 
			j = 1; 
		} 
		
		g = 0;
		h = 0;
		if (itemArray != null) { // add new items 
			// primero ver con cuantos datos va cargar el combo
			for (i = 0; i < itemArray.length; i++) { 
				if (selectednado==itemArray[i][0]) {
					h++;
				}
			} 
			
			g = 0;
			for (i = 0; i < itemArray.length; i++) { 
				//mensajeValidacion(selectednado+" "+itemArray[i][0]);
				if (selectednado==itemArray[i][0]) {
					
					if (h > 1){ //si existe mas de un dato para llenar el combo, se salta un indice para dejar el todos como 0
						if (j==0){
							selectCtrl.options[j] = new Option(itemArray[j][2]); 
				   			j=1;
						} 				   			
					} 
					
					selectCtrl.options[j] = new Option(itemArray[i][2]); 
					if (itemArray[i][1] != null) { 
						selectCtrl.options[j].value = itemArray[i][1]; 
						g++;
					} 
					j++;
				}
			} // select first item (prompt) for sub list 			
		} 
		
	}
	

	function fillSelectFromArray3t(selectCtrl, itemArray, selectednado, goodPrompt, badPrompt, defaultItem) { 
		var i, j, g,h; 
			
		var prompt; // empty existing items 
		
		
		for (i = selectCtrl.options.length; i >= 0; i--) { 
			selectCtrl.options[i] = null; 
		} 
		prompt = (itemArray != null) ? goodPrompt : badPrompt; 
		if (prompt == null) { 
			j = 0; 
		} else { 
			selectCtrl.options[0] = new Option(prompt); 
			j = 1; 
		} 
		
		g = 0;
		h = 0;
		if (itemArray != null) { // add new items 
			// primero ver con cuantos datos va cargar el combo
			for (i = 0; i < itemArray.length; i++) { 
				if (selectednado==itemArray[i][0]) {
					h++;
				}
			} 
			
			g = 0;
			for (i = 0; i < itemArray.length; i++) { 
				//mensajeValidacion(selectednado+" "+itemArray[i][0]);
				if (selectednado==itemArray[i][0]) {
					
					if (h > 1){ //si existe mas de un dato para llenar el combo, se salta un indice para dejar el todos como 0
						if (j==0){
							selectCtrl.options[j] = new Option(itemArray[j][2]); 
				   			j=1;
						} 				   			
					} 
					
					selectCtrl.options[j] = new Option(itemArray[i][2]); 
					if (itemArray[i][1] != null) { 
						selectCtrl.options[j].value = itemArray[i][1]; 
						g++;
					} 
					j++;
				}
			} // select first item (prompt) for sub list 			
			
			
			if (g > 1){
				selectCtrl.options[0] = new Option("(Todos)");
				selectCtrl.options[0].value = 0
				selectCtrl.selectedIndex=0;
			}
		} 
		
	}

	
	function validar_numero(e)
	{
	    tecla = (document.all) ? e.keyCode : e.which;
	    if (tecla==8) return true;
	    patron = /\d/; // Solo acepta n�meros
	    te = String.fromCharCode(tecla);
	    return patron.test(te);
	}	
	
	
	function checkCookie()
	{
		var cookieEnabled=(navigator.cookieEnabled)? true : false;
		if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){ 
			document.cookie="testcookie";
			cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
		}
		
		return cookieEnabled;
	}

	function validaSeleccion(obj)
	{
		var sel = obj.value;
		
		if (sel == 0){
			alert ("Debe Seleccionar");
			obj.className = "erroneo form-control input-sm";
			obj.focus();
			return false;
		}
		return true;
	}
	
	function validaCampoVacio(obj)
	{
		var val = obj.value;

		if (val == ""){
			alert ("Campo no debe estar vacio");
			obj.className = "erroneo form-control";
			obj.focus();
			return false;
		}
		return true;
	}

	function MarcarTodosCheck(form) {
		for (i = 0; i < form.elements.length; i++) {
			if (form.elements[i].type == "checkbox") {
			    despliega = form.elements[i].style.display;
				if (form.elements[i].disabled == false && despliega != "none") {
					form.elements[i].checked = 1;
				}
			}
		}
		return false;
	}

	function DesmarcarTodosCheck(form) {
		for (i = 0; i < form.elements.length; i++) {
			if (form.elements[i].type == "checkbox"){
				despliega = form.elements[i].style.display;
				if (despliega != "none"){
					form.elements[i].checked = 0
				}
			}
		}
		return false;
	}

	function SelectAllCheck(form) {
    
				
        if (document.getElementById("SelAll").checked == 0){
          for (i = 0; i < form.elements.length; i++) {
              if (form.elements[i].type == "checkbox") {
                  despliega = form.elements[i].style.display;
                  if (form.elements[i].disabled == false && despliega != "none") {
                      form.elements[i].checked = 0;
                  }
              }
          }      
        } else
        {         
          for (i = 0; i < form.elements.length; i++) {
              if (form.elements[i].type == "checkbox") {
                  despliega = form.elements[i].style.display;
                  if (form.elements[i].disabled == false && despliega != "none") {
                      form.elements[i].checked = 1;
                  }
              }
          } 
        }
      
        
		
		return false;
	}
	
	function LimpiarTexto(elemento){
		elemento.value = "";

	}
	
	function LimpiarTextoId(elemento){
		document.getElementById(elemento).value = "";

	}
	
	function proc_paginado(pagina){
		document.getElementById("pagina").value = pagina;
		document.getElementById("paginado").submit();
	}	
	
	function EliminaArch(Arch)
	{	
		var xmlhttp;
		if (window.XMLHttpRequest){

			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.open('POST', 'eliminararchivo.php?arch='+ Arch, true);
		xmlhttp.send();
	}	
	
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	}

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return "";
	}
	
	function MostrarCargando(){
		try{
			document.getElementById('cargando').style.display='block';
		}catch(e){}
	}
	
	function OcultarCargando(){
		try{
			document.getElementById('cargando').style.display='none';
		}catch(e){}
	}
	
	function IniFraseTextoId(elemento,Texto){
		document.getElementById(elemento).value = Texto;

	}

	/*Verificar que los campos solo acepten letras y numeros, solamente*/

	function NumText(string){//solo letras y numeros
	    var out = '';
	    //Se a�aden las letras validas
	    var filtro = 'abcdefghijklmn�opqrstuvwxyzABCDEFGHIJKLMN�OPQRSTUVWXYZ1234567890';//Caracteres validos
		
	    for (var i=0; i<string.length; i++)
	       if (filtro.indexOf(string.charAt(i)) != -1) 
		     out += string.charAt(i);
	    return out;
	}

function mensajeValidacion(mensaje)
{	
   var elemento = document.getElementById("mensajeError");
   elemento.innerHTML = mensaje;
   elemento.className += "callout callout-warning";
   
   var elementoOK = document.getElementById("mensajeOK");
   elementoOK.innerHTML = "";
   elementoOK.className = "";
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