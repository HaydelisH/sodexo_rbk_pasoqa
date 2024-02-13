<?php

class Paginas {

	// variable para mostrar a medida que se va procesando
	public $escribirAltiro=true;
	// para juntar el texto si es que no se escribe altiro
	private $textoPagina="";

	private $datos;	// Datos para remplazar
	private $parametros; // Paramentros Acumulados de una funcion
	private $entidadActual="";
	private $padre;

	// Parsea los atributos de un tag
	private function parsearAtributos($atributos)
	{
		// Cambia los espacios por & para pocesarlo como si fuera una url
		$atributos=str_replace(" ","&",$atributos);
		// Eliminamos las comillas simples y dobles
		$atributos=str_replace("'","",$atributos);
		$atributos=str_replace("\"","",$atributos);
		// Ocupamos esta funcion que sirve para parsear las urls
		parse_str($atributos, $atributos);
		// retornamos los atributos como arreglo
		return $atributos;
	}

	// Funcion que escribe en pantalla o guarda
	private function escribir($texto)
	{
		// si deseamos escribir inmediatamente
		if ($this->escribirAltiro)
		{
			// genera el texto
			$texto = utf8_encode($texto);
			echo $texto;
			return;
		}
		// lo vamos juntando en la variable
		$this->textoPagina.=$texto;
	}

	private function procesarContenido($contenido, &$datos, $cuenta=0)
	{

		// Inicializamos el contador en 0 para empezar del princio a buscar
		$contador = 0;
		// guardamos el total del texto a buscar$page
		$totalBytes=strlen($contenido);
		// damos vueltas hasta que revisemos la totalidad el texto
		while ($totalBytes>$contador)
		{
			// buscamos la primera posicion de algun tag
			$posicion=strpos($contenido,"<php:",$contador);
			// si no encontramos tags nos vamos
			if ($posicion===false) break;
			// mostramos en pantalla lo que esta antes del tag
			$this->escribir(substr($contenido,$contador,$posicion-$contador));
			// buscamos el primer espacio para poder saber el nombre del tag
			$posicion2=strpos($contenido," ",$posicion);
			// si no hay espacio no seguimos buscando, porque no es un tag valido
			if ($posicion2===false)
			{
				// pero antes avanzamos
				$contador=$posicion+strlen("<php:");
				// ahora salimos
				break;
			}
			// cortamos el tag y lo guardamos en una variable
			$tag=substr($contenido,$posicion+strlen("<php:"),$posicion2-$posicion-strlen("<php:"));
			// buscamos el fin del tag
			$posicion3=strpos($contenido,">",$posicion2);
			// si no encontramos el fin no es un tag valido
			if ($posicion3===false)
			{
				// pero antes avanzamos
				$contador=$posicion2;
				// ahora nos vamos del loop
				break;
			}
			// tenemos un tag que nos sirve ahora parseamos los atributos y los guardamos en una variable
			$atributos=$this->parsearAtributos(substr($contenido,$posicion2+1,$posicion3-$posicion2-2));

			// El contador lo avanzamos hasta afuera del tag, con la intencion de seguir buscando despues desde ahi
			$contador=$posicion3+1;

			// pero si no es tag independiente
			if ($posicion4=substr($contenido,$posicion3-1,1)!="/")
			{
				// buscamos el tag que lo cierra
				$posicion4=strpos($contenido,"</php:".$tag.">",$posicion3);
				// y si no tiene nos vamos
				if ($posicion4===false)
				{
					// pero antes avanzamos
					$contador=$posicion3;
					// y ahora nos vamos del loop
					break;
				}

				// si el proximo tag que viene es anterior al que cierra entonces seguimos buscando el otro
				// este es un truco para repetir dentro de otro tag de repetir
				// mas adelante tendria que ser mas recursiva con el resto de la funciones
				$saltados=-1;
				$posicion31=$posicion3;
				// buscamos cuantos nos tenemos que saltar
				$cuantos=substr_count($contenido,"<php:".$tag,$posicion3,$posicion4-$posicion3);
				while ($cuantos>$saltados)
				{
					// nos saltamos uno
					$saltados++;
					// buscamos el proximo
					$posicion31=strpos($contenido,"</php:".$tag.">",$posicion31+1);
					// buscamos cuantos nos tenemos que saltar de nuevo
					$cuantos=substr_count($contenido,"<php:".$tag,$posicion3,$posicion31-$posicion3);
					// guardamos la nuevo posicion
					$posicion4=$posicion31;
				}
				// guardamos la posicion para procesar segun lo que diga el tag
				$posicion4=$posicion4+strlen("</php:".$tag.">");
				// lo avanzamos hasta afuera del tag que lo cierra, con la intencion de seguir buscando despues desde ahi
				$contador=$posicion4;
			}

			//  ahora evaluamos el remplazo segun el tag
			switch ($tag) {
			case "texto":
				// si es texto entonces, ocupamos los datos asignados
				if (isset($this->datos[$atributos["id"]])) 
					$this->escribir($this->datos[$atributos["id"]]);
				break;
			case "fila":
				// si es texto entonces, ocupamos los datos asignados
				$this->escribir($cuenta+1);
				break;
			case "fila2":
				// si es texto entonces, ocupamos los datos asignados
				$this->escribir($cuenta+3);
				break;
			case "filamenos":
				// si es texto entonces, ocupamos los datos asignados
				$this->escribir($cuenta);
				break;
			case "item":
				// si es item, ocupamos los datos asignados pero los que nos enviaron por los parametros
				if (isset($datos[$atributos["id"]]))
					$this->escribir($datos[$atributos["id"]]);
				break;
			case "repeticion":
				// cortamos el sector a repetir y lo guardamos en una variable
				$contenidoRepetir=substr($contenido,$posicion3+1,$posicion4-$posicion3-1-strlen("</php:".$tag.">"));
				$a=0;
				// esto es para saber donde nos encontramos en los datos y asi repetir recursivamente
				$datos["entidadActual"]=$atributos["id"];
				// dependiendo de cuantos datos tenemos son la veces que repetimos
				if (isset($datos[$atributos["id"]]))
					while ($a<count($datos[$atributos["id"]]))
					{
						// procesamos el texto a repetir, con los datos del arreglo, esta misma funcion recursivamente
						$this->procesarContenido($contenidoRepetir, $datos[$atributos["id"]][$a],$a);
						// avanzamos al siguiente registro
						$a++;
					}
				break;
			case "funcion":
				// Seteamos el texto de los parametros en nada por si no hay
				$contenidoParamentros="";
				// si no hay position4 entonces no hya parametros
				if ($posicion4===false)
				{
					// y llamamos la funcion que nos dicen
					$this->escribir(eval("return ".$atributos["id"]."();"));
					break;
				}
				// si hay entonces cortamos el texto y lo guardamos en la variable
				$contenidoParamentros=substr($contenido,$posicion3+1,$posicion4-$posicion3-1-strlen("</php:".$tag.">"));
				// borramos los parametros
				unset($this->parametros);
				// seteamos el primero en nada para que cueste menos procesar
				$this->parametros[0]="";
				// buscamos los parametros llamando recursivamente a esta funcion
				$this->procesarContenido($contenidoParamentros, $datos);

				$atributos['id']=str_replace('parent_','$this->padre->',$atributos['id']);
				// ejecutamos la funcion con los paramentros, convirtiendo el arreglo en un texto separado por comas
				$ejecutar='return '.$atributos['id'].'('.substr(implode("','",$this->parametros),2)."');";
				$ejecutar=str_replace("||'","",$ejecutar);
				$ejecutar=str_replace("'||","",$ejecutar);
				$this->escribir(eval($ejecutar));
				break;

			case "argumento":
				// si son argumentos y tienen id
				if (isset($atributos["id"])) {
					//entonces lo guardamos como parametro sacando el valor desde los datos
					if (!isset($datos[$atributos["id"]])) $datos[$atributos["id"]]="";
					array_push($this->parametros,$datos[$atributos["id"]]);
				} else if (isset($atributos["idvalor"])) {
					// este es un truquini para conseguir el valor del formulario principal
					// Cuando los valores van enganchados
					if(!isset($this->datos[$this->datos["entidadActual"]][0][$atributos["idvalor"]])) $this->datos[$this->datos["entidadActual"]][0][$atributos["idvalor"]]="";
					array_push($this->parametros,$this->datos[$this->datos["entidadActual"]][0][$atributos["idvalor"]]);
				} else if (isset($atributos["idprincipal"])) {
					// este es un truquini para conseguir el valor principal 
					// no sabria explicarlo, pero es cosa de hace un print_r de los valores
					array_push($this->parametros,$this->datos[$atributos["idprincipal"]]);
				} else if (isset($atributos["valor"]))
					// si no colocamos su valor
					array_push($this->parametros,$atributos["valor"]);
				else if (isset($atributos["datos"]))
					// si no colocamos su valor
					array_push($this->parametros,'||$'.$atributos["datos"].'||');
				break;
			case "esta_pagina":
				$esta_pagina=explode("/",$_SERVER["PHP_SELF"]);
				$this->escribir($esta_pagina[count($esta_pagina)-1]);
				break;
			}

		}
		// escribimos lo que queda
		$this->escribir(substr($contenido,$contador,$totalBytes-$contador));
	}


	// funcion para imprimir el template
	public function imprimirTemplate ($archivo)
	{
		// y el texto de la pagina en nada
		$this->textoPagina="";

		// si no existe el archivo nos vamos
		if (!file_exists($archivo)) return;

		// calculamos el total del archivo
		$totalBytes=filesize($archivo);

		// abrimos el archivo
		$fh = fopen($archivo, 'r');
		// y nos traemos todo el contenido

		$contenido = fread($fh, $totalBytes);
		// cerramos
		fclose($fh);

		// procesamos el texto
		$this->procesarContenido($contenido, $this->datos);

		// retornamos lo generado, si es que no lo escribimos altiro
		return $this->textoPagina;

	}

	// agregamos el texto a los datos
	public function agregarDato ($id,$array)
	{
		// agregamos el arreglo que nos entregan a nuestro arreglo interno;
		$this->datos[$id]=$array;
	}

	// agregamos el padre a los datos
	public function agregarPadre(&$parent)
	{
		// agregamos el arreglo que nos entregan a nuestro arreglo interno;
		$this->padre=$parent;
	}

}

?>
