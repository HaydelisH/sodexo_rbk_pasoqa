<?php
		//el nombre del script php nos servirá como identificador de la opción
		$this->nroopcion = "";
		$scriptarr 	= explode ("\\",$_SERVER["SCRIPT_FILENAME"]);
		//$scriptarr 	= explode ("/",$_SERVER["SCRIPT_FILENAME"]);
		$this->nroopcion = end($scriptarr);
		
		//
				
		$this->pagina->agregarDato("usuario_nombre",$this->seguridad->nombre);
		
		$nombreperfil =  substr($this->seguridad->nombreperfil, 0, 20);
		$this->pagina->agregarDato("nombreperfil",$nombreperfil);
		
		$this->pagina->agregarDato("opcion",$this->opcion);
		$this->pagina->agregarDato("opciondetalle",$this->opciondetalle);
		$this->pagina->agregarDato("opcionicono",$this->opcionicono);
		$this->pagina->agregarDato("opcionnivel1",$this->opcionnivel1);
		$this->pagina->agregarDato("opcionnivel2",$this->opcionnivel2);
		
		// mostramos el encabezado
		$this->pagina->imprimirTemplate('templates/fij_encabezado.html');
		$this->pagina->imprimirTemplate('templates/fij_perfil.html');
		
		//para mostrar opciones en el menu
		$datos["tipousuarioid"]=$this->seguridad->tipousuarioid;
		$this->opcionesxtipousuarioBD->Listado($datos,$dt1);
		//var_dump($dt1);

		// Formularios
		$this->opcionesxtipousuarioBD->getFormularios($dt11);
		if (count($dt11->data) > 0)
		{
			for ($i = 0; $i < count($dt11->data); $i++)
			{
				//var_dump($dt11->data[$i]['opcionid']);
				//var_dump($dt11->data[$i]['oculta']);
				for ($j = 0; $j < count($dt1->data); $j++)
				{
					if ($dt11->data[$i]['opcionid'] == $dt1->data[$j]['opcionid'])
					{
						if ($dt11->data[$i]['oculta'] == 1)
						{
							$dat["usuarioid"] = $this->seguridad->usuarioid;
							$dat["opcionid"] = $dt11->data[$i]['opcionid'];
							$this->opcionesxtipousuarioBD->getFormulariosUsuario($dat,$dt10);
							if (count($dt10->data) == 0)
							{
								unset($dt1->data[$j]);
							}
//							var_dump($dt10->data);
						}
					}
				}
			}
		}
		
		//csb para deducir si muestra opcion pendiente de firma en RRLL
		$pendientesrrll = 0;
		if (strtoupper(trim($nombreperfil)) == 'TRABAJADOR')
		{
			$datos["usuarioid"]=$this->seguridad->usuarioid;
			$this->opcionesxtipousuarioBD->rlpendientefirma($datos,$dtp);	
			if($dtp->leerFila())	
			{
				if ($dtp->obtenerItem("pendiente") > 0)
				{
					$pendientesrrll = count($dt1->data);
					$pendientesrrll++;
					$dt1->data[$pendientesrrll]["opcionid"] = "rl_Documentos_Pendientes.php";
					$dt1->data[$pendientesrrll]["consulta"] = "1";
				}
			}
		}
		// fin 
		
		$formulario[0]=$datos;
		$formulario[0]["opciones"]=$dt1->data;	
		$this->pagina->agregarDato("formulario",$formulario);
		$this->pagina->imprimirTemplate('templates/fij_menu.html');
		//fin
		
		$this->pagina->imprimirTemplate('templates/fij_titulo.html');
		
		//csb para deducir si muestra opcion pendiente de firma en RRLL
		if ($pendientesrrll > 0)
		{
			return;
		}
		//fin
		
		//validamos si tiene acceso a esta opcion
		if ($this->nroopcion != ""){
			$dt = new DataTable();
			
			$datos["opcionid"]=$this->nroopcion;
			$this->opcionesxtipousuarioBD->Obtener($datos,$dt);
			$this->mensajeError.=$this->opcionesxtipousuarioBD->mensajeError;
			if(!$dt->leerFila())
			{
				$this->pagina->agregarDato("mensajeError","NO TIENE ACCESO A ESTA OPCI&Oacute;N, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA");
				$this->pagina->imprimirTemplate('templates/puroError.html');	
				$this->imprimirFin();
				exit;
			}
			
			$this->consulta = $dt->obtenerItem("consulta");
			$this->modifica = $dt->obtenerItem("modifica");
			$this->crea = $dt->obtenerItem("crea");
			$this->elimina = $dt->obtenerItem("elimina");
			$this->ver = $dt->obtenerItem("ver");
			if($this->consulta == 0 && $this->modifica == 0 &&  $this->crea == 0 && $this->elimina == 0 && $this->ver == 0)
			{
				$this->pagina->agregarDato("mensajeError","NO TIENE ACCESO A ESTA OPCI&Oacute;N, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA");
				$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
				$this->imprimirFin();
				exit;
			}
			
			if(isset($_REQUEST["accion"]))
			{
				$this->pagina->agregarDato("mensajeError","NO TIENE ACCESO A ESTA OPCI&Oacute;N, CONSULTE CON EL ADMINISTRADOR DEL SISTEMA");
				if ($this->ver == 0	&& $_REQUEST["accion"] == "VER DOCUMENTO"){
					$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
					$this->imprimirFin();	
					exit;
				}
				
				if ($this->consulta == 0	&& $_REQUEST["accion"] == "BUSCAR"){
					$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
					$this->imprimirFin();	
					exit;
				}
				
				if ($this->crea == 0	&& $_REQUEST["accion"] == "AGREGAR"){
					$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
					$this->imprimirFin();	
					exit;
				}

				if ($this->modifica == 0	&& $_REQUEST["accion"] == "MODIFICAR"){
					$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
					$this->imprimirFin();
					exit;
				}
				
				if ($this->elimina == 0	&& $_REQUEST["accion"] == "ELIMINAR"){
					$this->pagina->imprimirTemplate('templates/sinaccesoopcion.html');	
					$this->imprimirFin();		
					exit;
				}
			}
		
		$this->pagina->agregarDato("mensajeError","");
		}
		//	

			
?>