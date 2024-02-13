<?php
	function linkfotos($depositoid, $fecha,$contenedor,$model,$serial,$manufacturer,$owner,$nomusuario) {

		return "<a href='imagenes.php?&depositoid=".$depositoid."&fecha=".$fecha."&contenedor=".$contenedor."&model=".$model."&serial=".$serial."&manufacturer=".$manufacturer."&owner=".$owner."&nomusuario=".$nomusuario."'>".$contenedor."</a>";
	}
	
	function alertaproceso($fhinicio, $fhtermino, $fhdiferencia) {
		$maxproceso = "00:00:01.0000";
//		$interval = 00:00:00.0000;

		if ($fhdiferencia > $maxproceso)
			return '<FONT COLOR="red">'.$fhdiferencia.'</FONT>';

		return $fhdiferencia;

	}


	function alertatipov($tipovisita) {
		if ( substr($tipovisita,0,9) == 'Restricci')
			return '<FONT COLOR="red">';

		return '';

	}

	function linkxnombre($nombre_visita, $rutvisita,$fecha_desde,$fecha_hasta,$edificioid,$dptoid,$patente,$tipovisita,$estacionid,$nombre,$rutempresa) {

		if ( $rutvisita > 0){
			return "<a href='visitas_edificio.php?visitasxrut="."&rutvisita=".$rutvisita."&fecha_desde=".$fecha_desde."&fecha_hasta=".$fecha_hasta."&edificioid=".$edificioid."&dptoid=".$dptoid."&patente=".$patente."&tipovisita=".$tipovisita."&estacionid=".$estacionid."&nombre=".$nombre."&rutempresa=".$rutempresa."' >".$nombre_visita."</a>";
		} else {
			if ( substr($tipovisita,0,9) == 'Restricci') {
				return "<FONT COLOR='red'>".$nombre_visita;
			}
		}

		return $nombre_visita;;

	}

	function linkxnombresup($nombre_visita, $rutvisita,$fecha_desde,$fecha_hasta,$edificioid,$dptoid,$patente,$tipovisita,$estacionid,$nombre) {

		if ( $rutvisita > 0){
			return "<a href='visitas_empresa.php?visitasxrut="."&rutvisita=".$rutvisita."&fecha_desde=".$fecha_desde."&fecha_hasta=".$fecha_hasta."&edificioid=".$edificioid."&dptoid=".$dptoid."&patente=".$patente."&tipovisita=".$tipovisita."&estacionid=".$estacionid."&nombre=".$nombre."'>".$nombre_visita."</a>";
		} else {
			if ( substr($tipovisita,0,9) == 'Restricci') {
				return "<FONT COLOR='red'>".$nombre_visita;
			}
		}

		return $nombre_visita;;

	}

	function alertasinsalida($fpermanencia, $fmarcasinsalida) {


		if ($fmarcasinsalida == 'sinsalida')
			return '<FONT COLOR="red">'.$fpermanencia.'</FONT>';

		return $fpermanencia;

	}


	function permanencia($fhinicio, $fhtermino) {

		if (trim($fhinicio) != '' && trim($fhtermino) != '')
		{
			$ihora = substr($fhinicio,13,2);
			$iminutos = substr($fhinicio,16,2);
			$isegundos = 0;
			$imes = 0;
			$idia = 0;
			$iano = 0;

			$fmes = 0;
			$fdia = 0;
			$fano = 0;

			if ($fhinicio != $fhtermino){
				$imes = substr($fhinicio,3,2);
				$idia = substr($fhinicio,0,2);
				$iano =  substr($fhinicio,6,4);

				$fmes = substr($fhtermino,3,2);
				$fdia = substr($fhtermino,0,2);
				$fano = substr($fhtermino,6,4);
			}

			$fhora = substr($fhtermino,13,2);
			$fminutos = substr($fhtermino,16,2);
			$fsegundos = 0;

			$segundos = @mktime($fhora,$fminutos,$fsegundos,$fmes,$fdia,$fano) - @mktime($ihora,$iminutos,$isegundos,$imes,$idia,$iano);

			$horas = floor($segundos/3600);
			if (strlen($horas) < 2){$horas = '0'.$horas;}
			$minutos = floor(($segundos-($horas*3600))/60);
			if (strlen($minutos) < 2){$minutos = '0'.$minutos;}

			return ' '.$horas.':'.$minutos;
		}

	}

	function log_handler($errno, $errstr, $errfile, $errline)
	{
	    ContenedorUtilidades::logPrint("Error No".$errno." ".$errstr." en el archivo ".$errfile.", linea:".$errline);
	    return true;
	}

	function siesfactura($tipo, $factura) {
		if ($tipo=="FA" || $tipo=="FC") {
			$enlace = "facturas/DTE33-F".$factura.".pdf";
			if (file_exists($enlace)) {
				return "<a href='detfac.php?accion=Detalles&tipdoc=".$tipo."&numnot_ped=".$factura."'>".$factura."</a>";
			}
		}
		if ($tipo=="NC") {
			$enlace = "facturas/DTE61-F".$factura.".pdf";
			if (file_exists($enlace)) {
				return "<a href='detfac.php?accion=Detalles&tipdoc=".$tipo."&numnot_ped=".$factura."'>".$factura."</a>";
			}
		}
		return $factura;
	}

class ContenedorUtilidades
{

	public static function dameRut($sub_rut) {

		$x=2;
		$s=0;
		for ( $i=strlen($sub_rut)-1;$i>=0;$i-- )
		{
			if ($x>7)
				$x=2;

			$s += $sub_rut[$i]*$x;
			$x++;
		}
		$dv=11-($s%11);

		if ( $dv==10 )
			$dv='K';

		if ( $dv==11 )
			$dv='0';

		return $dv;
	}

	public static function permanencia2($fhiniciox, $fhterminox) {
		if (trim($fhiniciox) != '' && trim($fhterminox) != '')
		{	$ihora = substr($fhiniciox,13,2);
			$iminutos = substr($fhiniciox,16,2);
			$isegundos = 0;
			$imes = 0;
			$idia = 0;
			$iano = 0;

			$fmes = 0;
			$fdia = 0;
			$fano = 0;

			if ($fhiniciox != $fhterminox){
				$imes = substr($fhiniciox,3,2);
				$idia = substr($fhiniciox,0,2);
				$iano =  substr($fhiniciox,6,4);

				$fmes = substr($fhterminox,3,2);
				$fdia = substr($fhterminox,0,2);
				$fano = substr($fhterminox,6,4);
			}

			$fhora = substr($fhterminox,13,2);
			$fminutos = substr($fhterminox,16,2);
			$fsegundos = 0;

			$segundos = @mktime($fhora,$fminutos,$fsegundos,$fmes,$fdia,$fano) - @mktime($ihora,$iminutos,$isegundos,$imes,$idia,$iano);

			$horas = floor($segundos/3600);
			if (strlen($horas) < 2){$horas = '0'.$horas;}
			$minutos = floor(($segundos-($horas*3600))/60);
			if (strlen($minutos) < 2){$minutos = '0'.$minutos;}

			return ' '.$horas.':'.$minutos;
		}

	}

	public static function difhoramin($fhiniciox, $fhterminox) {
			if (trim($fhiniciox) != '' && trim($fhterminox) != '')
			{	$ihora = substr($fhiniciox,13,2);
				$iminutos = substr($fhiniciox,16,2);
				$isegundos = 0;
				$imes = 0;
				$idia = 0;
				$iano = 0;

				$fmes = 0;
				$fdia = 0;
				$fano = 0;

				if ($fhiniciox != $fhterminox){
					$imes = substr($fhiniciox,3,2);
					$idia = substr($fhiniciox,0,2);
					$iano =  substr($fhiniciox,6,4);

					$fmes = substr($fhterminox,3,2);
					$fdia = substr($fhterminox,0,2);
					$fano = substr($fhterminox,6,4);
				}

				$fhora = substr($fhterminox,13,2);
				$fminutos = substr($fhterminox,16,2);
				$fsegundos = 0;

				$segundos = @mktime($fhora,$fminutos,$fsegundos,$fmes,$fdia,$fano) - @mktime($ihora,$iminutos,$isegundos,$imes,$idia,$iano);

				$horas = floor($segundos/3600);
				if (strlen($horas) < 2){$horas = '0'.$horas;}
				$minutos = floor(($segundos-($horas*3600))/60);
				if (strlen($minutos) < 2){$minutos = '0'.$minutos;}

				return ' '.$horas.' horas y '.$minutos.' minutos';
			}

	}

	public static function difdiahhmmss($fhiniciox, $fhterminox) {
			$fhiniciox = str_replace(" - "," ",$fhiniciox);
			$fhterminox = str_replace(" - "," ",$fhterminox);
			$fhiniciox = str_replace("/","-",$fhiniciox);
			$fhterminox = str_replace("/","-",$fhterminox);
			$fhiniciox=$fhiniciox.":00";
			$fhterminox=$fhterminox.":00";

			if (trim($fhiniciox) != '' && trim($fhterminox) != '')
			{
				$diff = @strtotime($fhterminox) - @strtotime($fhiniciox);
				$dias = $diff/(60*60*24);
				$horas = ($dias-intval($dias))*24;
				$min = ($horas-intval($horas))*60;
				$seg = ($min-intval($min))*60;

				$rdias=0;
				$rhoras=0;
				$rmin=0;

				if (intval($dias) > 0) { $rdias=intval($dias);;}
				if (intval($horas) > 0) { $rhoras=intval($horas);}
				if (intval($min) > 0) { $rmin=intval($min);}

				$scal="";
				if ($rdias==1){ $scal.=$rdias.' dia ';}
				if ($rdias > 1){ $scal.=$rdias.' dias ';}
				if ($rdias > 0 && $rhoras > 0 && $rmin > 0){ $scal.=', ';}
				if ($rdias > 0 && $rhoras > 0 && $rmin == 0){ $scal.='y ';}

				if ($rhoras==1){ $scal.=$rhoras.' hora ';}
				if ($rhoras > 1){ $scal.=$rhoras.' horas ';}
				if ($rmin > 0 && $scal!=""){ $scal.=' y ';}

				if ($rmin==1){ $scal.=$rmin.' minuto ';}
				if ($rmin > 1){ $scal.=$rmin.' minutos ';}

				return ' '.$scal;
			}
	}


	public static function sacaPuntos($fecha) {
		$fecha=str_replace(".","",$fecha);
		$fecha=str_replace(" ","",$fecha);
		$fecha=str_replace(":","",$fecha);
		$fecha=str_replace("-","",$fecha);
		return str_replace("","",$fecha);
	}

	public static function logPrint($texto)
	{
		date_default_timezone_set("America/Santiago");

		$archivo=fopen("error.log","a");
		fwrite($archivo, Date("Y-m-d H:n:s")." ".$texto."\r\n");
		fclose($archivo);
	}

	public static function validarEmail($email)
	{
		return preg_match("/^[A-Za-z][A-Za-z0-9_\.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/" , $email);
	}

	public static function validarRut($rut)
	{
		return preg_match("/^[kK0-9-]+$/" , $rut);
	}

	public static function validarFechaHora($fecha)
	{
		return preg_match("/^(19[0-9]{2}|[2-9][0-9]{3})-((0(1|3|5|7|8)|10|12)-(0[1-9]|1[0-9]|2[0-9]|3[0-1])|(0(4|6|9)|11)-(0[1-9]|1[0-9]|2[0-9]|30)|(02)-(0[1-9]|1[0-9]|2[0-9]))\x20(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}$/",$fecha);
	}
	public static function validarHora($fecha)
	{
		return preg_match("/^(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}$/",$fecha);
	}
	public static function validarFecha($fecha)
	{
		return preg_match("/^(19[0-9]{2}|[2-9][0-9]{3})-((0(1|3|5|7|8)|10|12)-(0[1-9]|1[0-9]|2[0-9]|3[0-1])|(0(4|6|9)|11)-(0[1-9]|1[0-9]|2[0-9]|30)|(02)-(0[1-9]|1[0-9]|2[0-9]))$/",$fecha);
	}
	public static function validarTexto($txt)
	{
		return $txt!="";
	}

	public static function convertirAFechaSql($strFecha)
	{
		if(trim($strFecha) == "")
			return "";

		list($dia, $mes, $anio) = split('[/.-]', $strFecha);
		return $anio."/".$mes."/".$dia;
	}

	public static function convertirDesdeFechaSql($strFecha)
	{
		if(trim($strFecha) == "")
			return "";

		list($anio, $mes, $dia) = split('[/.-]', $strFecha);
		return $dia."/".$mes."/".$anio;
	}


	public static function realIP()
	{
		if (isset($_SERVER["HTTP_CLIENT_IP"]))
		{
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		{
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
		{
			return $_SERVER["HTTP_X_FORWARDED"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
		{
			return $_SERVER["HTTP_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED"]))
		{
			return $_SERVER["HTTP_FORWARDED"];
		}
		else
		{
			return $_SERVER["REMOTE_ADDR"];
		}
	}
	
	
	public static function realIP_RC()
	{
		$realip="";
		if ($_SERVER)
		{
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			{
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}

			if (isset($_SERVER["HTTP_CLIENT_IP"]))
			{
				//$realip .= " ".$_SERVER["HTTP_CLIENT_IP"];
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			}
			else
			{
				//$realip .= " ".$_SERVER["REMOTE_ADDR"];
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			}
			if (getenv( 'HTTP_CLIENT_IP'))
			{
				//$realip .= " ".getenv('HTTP_CLIENT_IP');
				$realip = getenv('HTTP_CLIENT_IP');
			}
			else
			{
				//$realip .= " ".getenv('REMOTE_ADDR');
				$realip = getenv('REMOTE_ADDR');
			}
		}
		return $realip;
	}

	public static function inventarSesion()
	{
		mt_srand((double)microtime()*100000);
		$sesion_inventada="";
		while (strlen($sesion_inventada)<15)
		{
			$a=mt_rand(48,122);
			if ($a<58 || ($a>64 && $a<91) || ($a>96 && $a<122))
			{
				$sesion_inventada.=chr($a);
			}
		}
		return $sesion_inventada;
	}

	public static function evaluarNumerico($tipo)
	{
		if (substr($tipo,0,4)=="varc") return false;
		if (substr($tipo,0,4)=="char") return false;
		if (substr($tipo,0,4)=="text") return false;
		if (substr($tipo,0,4)=="date") return false;
		if (substr($tipo,0,4)=="time") return false;
		if (substr($tipo,0,4)=="text") return false;
		if (substr($tipo,0,4)=="base") return false;//csb 30-08-2018 para tipo de datos base64
		return true;
	}

	public static function evaluarcomillas($valor, $tipo)
	{
		if (substr($tipo,0,6)=="base64") return "'".$valor."'";//csb 30-08-2018 nuevo tipo dato base 64 para no modficar el contenido
		
		if (substr($tipo,0,7)=="varchar") return "'".$valor."'";
		if (substr($tipo,0,4)=="char") return "'".$valor."'";
		if (substr($tipo,0,4)=="date" && $valor=="") return "NULL";
		if (substr($tipo,0,4)=="time" && $valor=="") return "NULL";
		if (substr($tipo,0,8)=="datetime") {
			$valor=substr($valor,6,4)."-".substr($valor,3,2)."-".substr($valor,0,2).substr($valor,10);
			return "'".$valor."'";
		}
		if ($tipo=="date") {
			$valor=substr($valor,6,4)."-".substr($valor,3,2)."-".substr($valor,0,2);
			return "'".$valor."'";
		}
		if (substr($tipo,0,4)=="time") return "'".$valor."'";
		if (substr($tipo,0,4)=="text") return "'".$valor."'";
		return $valor;
	}


	public static function validarDatos(&$datos, $definicion, &$mensaje, $forzar=false)
	{
		foreach ($definicion as $key => $valor)
		{
			if (!isset($datos[$key])) {$datos[$key]="";}
			if (!isset($datos[$key]) && $forzar)
			{
				if (ContenedorUtilidades::evaluarNumerico($definicion[$key]["Tipo"]))
					$datos[$key] = 0;
				else
					$datos[$key] = "";
			}
			if (ContenedorUtilidades::evaluarNumerico($definicion[$key]["Tipo"]))
			{
				if (!is_numeric($datos[$key]))
				{
					if (!$forzar) $mensaje .= MensajeUsuario::VALOR_NO_NUMERIC." (".$key.",".$datos[$key].")";
					if (!$forzar) return false;
					$datos[$key] = 0;
				}
			}
			if ($definicion[$key]["Tipo"]=="datetime")
			{
				if (ContenedorUtilidades::validarFechaHora($datos[$key]))
				{
					if (!$forzar) $mensaje .= MensajeUsuario::VALOR_NO_DATETIME;
					if (!$forzar) return false;
					$datos[$key] = "1911/01/01 00:00";
				}
			}
			if ($definicion[$key]["Tipo"]=="time")
			{
				if (ContenedorUtilidades::validarHora($datos[$key]))
				{
					if (!$forzar) $mensaje .= MensajeUsuario::VALOR_NO_TIME;
					if (!$forzar) return false;
					$datos[$key] = "00:00";
				}
			}
			if ($definicion[$key]["Tipo"]=="date")
			{
				if (ContenedorUtilidades::validarFecha($datos[$key]))
				{
					if (!$forzar) $mensaje .= MensajeUsuario::VALOR_NO_DATE;
					if (!$forzar) return false;
					$datos[$key] = "1911/01/01";
				}
			}
			if ($definicion[$key]["Largo"]!="")
			{
				if (strlen($datos[$key])>$definicion[$key]["Largo"])
				{
					if (!$forzar) $mensaje .= MensajeUsuario::VALOR_MUY_LARGO;
					if (!$forzar) return false;
					$datos[$key] = substr($datos[$key],0,$definicion[$key]["Largo"]);
				}
			}
		}
		
		return true;
	}

	public static function generarLlamado($datos, $definicion)
	{
		$resultado="";
		foreach ($definicion as $key => $valor)
			$resultado.=",".ContenedorUtilidades::evaluarcomillas($datos[$key],$definicion[$key]["Tipo"]);
		return $resultado;
	}
	
	public static function generarLlamado2($datos, $definicion)
	{
		$resultado="";
		foreach ($definicion as $key => $valor){
			$resultado.= ContenedorUtilidades::evaluarcomillas($datos[$key],$definicion[$key]["Tipo"]).",";
		}
		$resultado = substr ($resultado, 0, strlen($resultado) - 1);
		return $resultado;
	}

	public static function evaluarSeleccion($valor, $cual)
	{	
		//print ("valor:".$valor." cual:".$cual);
		if ($valor==$cual) {return " selected";}
		return "";
	}
	public static function evaluarSeleccionClase($valor, $cual)
	{
		if ($valor==$cual) {return " class='opcion_selecionada' ";}
		return "";
	}
	public static function evaluarCheckBox($valor)
	{
		if ($valor=="1") {return " checked";}
		return "";
	}
	public static function evaluarSiNo($valor)
	{
		if ($valor=="1") {return "Si";}
		return "";
	}
	public static function evaluarMostrar($valor)
	{
		if ($valor=="1") {return "mostrar";}
		return "nomostrar";
	}
	public static function listadoconserjes($arr) {
		$arr=$arr["conserjes_online"];
		if (count($arr)==0) {
			return "No hay operadores conectados al sistema";
		}
		if (count($arr)==1) {
			$listado="Conserje de turno: ";
		} else {
			$listado="Conserjes de turno: ";
		}

		for ($a=0; $a<count($arr); $a++)
			$listado.=$arr[$a]["nombre_conserje"].", ";
		$listado=substr($listado,0,-2);
		return $listado;
	}

	public static function getStamp(){
   		list($Mili, $bot) = explode(" ", microtime());
   		$DM=substr(strval($Mili),2,4);
  		return strval(@date("Y")."-".@date("m")."-".@date("d").' '.@date("H").':'.@date("i").':'.@date("s").".".$DM);
	}


	public static function realIP2()
	{
		if(!isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$_SERVER['HTTP_X_FORWARDED_FOR'] = "";
		}
	  
		if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
		{
		  $realip =
		 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
			$_SERVER['REMOTE_ADDR']
			:
			( ( !empty($_ENV['REMOTE_ADDR']) ) ?
			   $_ENV['REMOTE_ADDR']
			   :
			   "unknown" );

		  // los proxys van añadiendo al final de esta cabecera
		  // las direcciones ip que van "ocultando". Para localizar la ip real
		  // del usuario se comienza a mirar por el principio hasta encontrar
		  // una dirección ip que no sea del rango privado. En caso de no
		  // encontrarse ninguna se toma como valor el REMOTE_ADDR

		  $entries = preg_split('/[, ]/', $_SERVER['HTTP_X_FORWARDED_FOR']);

		  reset($entries);
		  while (list(, $entry) = each($entries))
		  {
		 $entry = trim($entry);
		 if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
		 {
			// http://www.faqs.org/rfcs/rfc1918.html
			$private_ip = array(
			  '/^0\./',
			  '/^127\.0\.0\.1/',
			  '/^192\.168\..*/',
			  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
			  '/^10\..*/');

			$found_ip = preg_replace($private_ip, $realip, $ip_list[1]);

			if ($realip != $found_ip)
			{
			   $realip = $found_ip;
			   break;
			}
		 }
		  }
		}
		else
		{
		  $realip =
		 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
			$_SERVER['REMOTE_ADDR']
			:
			( ( !empty($_ENV['REMOTE_ADDR']) ) ?
			   $_ENV['REMOTE_ADDR']
			   :
			   "unknown" );
		}

		return $realip;
		}

	//Funcion para devolver numeros ordinales 
	public static function numerosOrdinales ( $num, &$ordinal ){
		switch ($num) 
	    {
		    case 1:
		        return $ordinal = "PRIMERO";
		    case 2:
		        return $ordinal = "SEGUNDO";
		    case 3:
		        return $ordinal = "TERCERO";
		    case 4:
		        return $ordinal = "CUARTO";
		    case 5:
		        return $ordinal = "QUINTO";
		    case 6:
		        return $ordinal = "SEXTO";
		    case 7:
		        return $ordinal = "SEPTIMO";
		    case 8:
		        return $ordinal = "OCTAVO";
		    case 9:
		        return $ordinal = "NOVENO";
		    case 10:
		        return $ordinal = "DECIMO";
		    case 11:
		        return $ordinal = "DECIMO PRIMERO";
		    case 12:
		        return $ordinal = "DECIMO SEGUNDO";
		    case 13:
		        return $ordinal = "DECIMO TERCERO";
		    case 14:
		        return $ordinal = "DECIMO CUARTO";
		    case 15:
		        return $ordinal = "DECIMO QUINTO";
		    case 16:
		        return $ordinal = "DECIMO SEXTO";
		    case 17:
		        return $ordinal = "DECIMO SEPTIMO";
		    case 18:
		        return $ordinal = "DECIMO OCTAVO";
		    case 19:
		        return $ordinal = "DECIMO NOVENO";
		    case 20:
		        return $ordinal = "VIGESIMO";
		    case 21:
		        return $ordinal = "VIGESIMO PRIMERO";
		    case 22:
		        return $ordinal = "VIGESIMO SEGUNDO";
		    case 23:
		        return $ordinal = "VIGESIMO TERCERO";
		    case 24:
		        return $ordinal = "VIGESIMO CUARTO";
		    case 25:
		        return $ordinal = "VIGESIMO QUINTO";
		    case 26:
		        return $ordinal = "VIGESIMO SEXTO";
		    case 27:
		        return $ordinal = "VIGESIMO SEPTIMO";
		    case 28:
		        return $ordinal = "VIGESIMO OCTAVO";
		    case 29:
		        return $ordinal = "VIGESIMO NOVENO";
		    case 30:
		        return $ordinal = "TRIGESIMO";
		    default:    
		    	return $ordinal = " ";
	    }
	}

}
?>
