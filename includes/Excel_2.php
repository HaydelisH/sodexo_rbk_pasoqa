<?php

require_once("php_writeexcel/class.writeexcel_workbook.inc.php");
require_once("php_writeexcel/class.writeexcel_worksheet.inc.php");
require_once("php_writeexcel/class.writeexcel_workbookbig.inc.php");


class Excel {

	private $workbook;
	private $worksheet;

	private $nombre;
	private $titulo;
	private $archivo;

	private $fila=0;
	private $columna=0;

	public function iniciar($nombre, $titulo) {

		  $this->nombre=$nombre;
		  $this->titulo=$titulo;

		  $this->archivo = tempnam("/tmp", $this->nombre.".xls");
		  $this->workbook = new writeexcel_workbookbig($this->archivo);
		  $this->worksheet = &$this->workbook->addworksheet();

		  $this->estilo_titulo  = &$this->workbook->addformat(array(
										"bold"    => 1,
                                        "size"    => 18,
                                        "merge"   => 1,
										"align"   => "center"
                                        ));
 		  $this->estilo_cabecera  = &$this->workbook->addformat(array(
										"bold"    => 1,
										'fg_color' => "silver",
										'color' 	=> "black",
                                        'pattern'  => 1,
                                        ));
 		  $this->estilo_cabecera2  = &$this->workbook->addformat(array(
										"bold"    => 1,
										'fg_color' => "silver",
										'color' 	=> "black",
                                        'pattern'  => 1,
										"align"   => "center"
                                        ));

		  $this->estilo["normal"]  = &$this->workbook->addformat(array("align"=>"left"));
		  $this->estilo["fecha"]  = &$this->workbook->addformat(array("align"=>"left"));
		  $this->estilo["centrado"]  = &$this->workbook->addformat(array("align"=>"center"));
		  $this->estilo["moneda"]  = &$this->workbook->addformat(array("align"=>"right"));
		  $this->estilo["moneda"]->set_num_format('$#,##0;[Red]-$#,##0;$#,##0');
		  $this->estilo["numerico"]  = &$this->workbook->addformat(array("align"=>"right"));

		  $this->estilo["hora"]  = &$this->workbook->addformat(array());
		  $this->estilo["hora"]->set_num_format('[h]:mm:ss');

		  $this->worksheet->write($this->fila, $this->columna, $this->titulo, $this->estilo_titulo);
		  $this->worksheet->merge_cells($this->fila,$this->columna,$this->fila,$this->columna+3);
		  $this->fila++;
	}

	public function cerrar() {

		  $this->workbook->close();

		  header("Content-Type: application/x-msexcel; name=\"".$this->nombre.".xls\"");
		  header("Content-Disposition: inline; filename=\"".$this->nombre.".xls\"");
		  $fh=fopen($this->archivo, "rb");
		  fpassthru($fh);
	}

	public function agregarDato($valores, $campos, $descripcion, $tipos, $ancho, $horizontal=true) {
		if ($horizontal) {
			$this->columna=0;
			$this->fila++;
			for($b=0; $b<count($campos); $b++) {
				$this->worksheet->write($this->fila, $this->columna, $descripcion[$b], $this->estilo_cabecera2);
				$this->worksheet->freeze_panes($this->fila+1, 0);
				$this->worksheet->set_column($this->columna, $this->columna, $ancho[$b]);
				$this->columna++;
			}
			for($a=0; $a<count($valores); $a++) {
				$this->fila++;
				$this->columna=0;
				for($b=0; $b<count($campos); $b++) {
					//esto es para poder realizar la formula SUMA en la planilla excel cuando el formato corresponde a hora
					if ($tipos[$b] == "hora"){
						if ($valores[$a][$campos[$b]] !="")
							$formula= '("'.$valores[$a][$campos[$b]].'") + 0';
						else
							$formula='("")';
						$this->worksheet->write_formula($this->fila, $this->columna++,$formula, $this->estilo["hora"]);
					}
					else
					{
						$this->worksheet->write($this->fila, $this->columna++, $valores[$a][$campos[$b]], $this->estilo[$tipos[$b]]);
					}
				}
			}
		} else {
			for($a=0; $a<count($campos); $a++) {
				$this->columna=0;
				$this->fila++;
				$this->worksheet->write($this->fila, $this->columna, $descripcion[$a], $this->estilo_cabecera);
				//$this->worksheet->merge_cells($this->fila,$this->columna,$this->fila,$this->columna+2);
				$this->columna+=1;
				$this->worksheet->write($this->fila, $this->columna, $valores[0][$campos[$a]], $this->estilo[$tipos[$a]]);
				$this->worksheet->merge_cells($this->fila,$this->columna,$this->fila,$this->columna+2);
			}
		}
		$this->fila++;
	}

}
?>