<?php
class MensajeUsuario
{
	const CONEXION_BD_NO_DISPONIBLE = "No existe una conexi&oacute;n disponible a la base de datos";
	const NO_EXISTE_BD = "No existe la base de datos";
	const ERROR_BD = "Se produjo un error al generar la instrucci&oacute;n";
	const REGISTRO_NO_DISPONIBLE = "La consulta no genero resultados";
	const REGISTRO_ACTUALIZADO = "El registro ha sido actualizado";
	const REGISTRO_AGREGADO = "El registro ha sido agregado";
	const REGISTRO_EXISTE = "El registro a ingresar ya existe";
	const LOGIN_INCORRECTO = "La combinaci&oacute;n email/clave no es correcta";
	const VALOR_NO_NUMERIC = "Valor no num&eacute;rico";
	const VALOR_NO_DATETIME = "Valor no es una fecha con hora v&aacute;lida";
	const VALOR_NO_TIME = "Valor no es una hora v&aacute;lida";
	const VALOR_NO_DATE = "Valor no es una fecha v&aacute;lida";
	const VALOR_MUY_LARGO = "El largo del valor del campo es muy largo";
	const RUT_VACIO = "Ingrese el usuario para continuar";
	const CONTRASENA_VACIA = "Ingrese la clave para continuar";
	const RUT_NO_EXISTE = "Usuario no existe";
	const CONTRASENA_INCORRECTA = "Clave incorrecta";
	const DEBE_INICIARSESION = "Debe iniciar sesi&oacute;n";
	const DISTINTA_IP = "La sesion tiene otra ip asociada, por favor inicie sesi&oacute;n nuevamente";
	const DEBE_SESIONTERMINADA = "Su sesi&oacute;n de usuario ha caducado, por favor ingrese nuevamente";
	const DEBE_NOSECREOSESION = "Hubo un error y no se pudo crear la sesi&oacute;n en la base de datos. ";
	const DNI_EXISTE = "El DNI ingresado ya existe en nuestros registros";
	const YA_EXISTE = "Ya existe en nuestros registros";
	const MONTO_NO_EXISTE = "Ya existe en nuestros registros";
	const NO_SE_PUEDE_BORRAR = "No se puede borrar porque, este registro esta relacionado con otros datos";
	const NOTIENEMPRESAASOCIADAS = "No tiene empresas asociadas";

	const CONST_SESSION="300"; // Dura 3 minutos la sesion
}
?>