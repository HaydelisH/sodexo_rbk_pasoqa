<?php
		$this->pagina->imprimirTemplate('templates/fij_cierrecuerpo.html');
		$this->pagina->imprimirTemplate('templates/fij_infoadicional.html');
		$this->pagina->imprimirTemplate('templates/fij_piedepagina2.html');

		// desconectamos
		$this->bd->desconectar();
?>