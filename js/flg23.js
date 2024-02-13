	document.getElementById("javascriptok").value = navigator.javaEnabled();
	document.getElementById("cookiesok").value = navigator.cookieEnabled;

	$( "#clave" ).keypress(function(evento) {
		if (evento.which == 13)
		{
			$('.flg23').click();
		}
	});
	$('.flg23').on('click', function(){
		$('#formularioLogin').submit();
	});

	// Coneccion con active directory de microsoft (INI)
	$('.flg23AD').on('click', function(){
		$('#formularioLoginAD').submit();
	});
	// Coneccion con active directory de microsoft (FIN)
