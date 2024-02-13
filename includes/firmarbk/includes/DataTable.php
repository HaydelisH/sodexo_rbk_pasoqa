<?php
class DataTable {

	public $data = array();
	private $indiceFila;
	
	// constructor
	public function DataTable()
	{
		$this->indiceFila = 0;
	}
	
	// establecer indice pointer
	public function establecerIndiceFila($indice = 0)
	{
		$this->indiceFila = $indice;	
	}
	
	// eliminar coleccion de array
	public function limpiar()
	{
		$this->data = array();	
	}
	
	// agregar items al array
	public function agregar($fila, $columna)
	{	
		$this->data[$fila] = $columna;
	}
	
	// retornar el numero de filas en array
	public function contarFilas()
	{
		return sizeof($this->data);
	}
	
	// chequear si un elemento existe en el array, incrementar el incide para el siguiente elemento
	public function leerFila()
	{
		$existe = false;
		
		if($this->indiceFila < $this->contarFilas())
		{
			$this->indiceFila++;
			$existe = true;
		}	
				
		return $existe;
	}

	// obtener valor de un elemento en array
	public function obtenerItem($nombreColumna) 
	{		
		$valorItem = NULL;
		
		if(isset($this->data[$this->indiceFila - 1][$nombreColumna]))
		{		
	 		$valorItem = $this->data[$this->indiceFila - 1][$nombreColumna];
		}
		
 		return $valorItem;
 			
 	}
 	
	// busca un elemento en array
	public function buscarFila($nombreColumna, $item) 
	{		
		$fila = NULL;
			
		for($i=0;$i<$this->contarFilas();$i++)
		{
			if(isset($this->data[$i][$nombreColumna]))
			{		
		 		$valorItem = $this->data[$i][$nombreColumna];
		 		
		 		if($item == $valorItem)
		 		{
		 			$fila = $this->data[$i];
		 			return $fila;	
		 		}
			}
		
		}
		
 		return $fila;
 			
 	}
	
}

?>