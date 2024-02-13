<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);

// incluimos la clase para armar las paginas mediante plantillas
include_once('includes/Paginas.php');
// y la seguridad
include_once('includes/Seguridad.php');

include_once('firma.php');
// creamos la instacia de esta clase
$page = new pruebafirmarbk();

class pruebafirmarbk {

	// Para armas la pagina
	private $pagina;
	// para la conexion a la base de datos
	private $bd;
	// para juntar los mensajes de error
	private $mensajeError='';

	public $seguridad;
	
	private $valor_arr;

	// funcion contructora, al instanciar
	function __construct()
	{

		// hacemos una instacia del manejo de plantillas (templates)
		$this->pagina = new Paginas();
		// creamos una instacia de la base de datos
		$this->bd = new ObjetoBD();

		// nos conectamos a la base de datos
		if (!$this->bd->conectar())
		{
			// si no se pudo mostramos un mensaje de error
			$this->mensajeError=$this->bd->accederError();
			// lo agregamos a la pagina
			$this->pagina->agregarDato('mensajeError',$this->mensajeError);

			// mostramos el encabezado
			$this->pagina->imprimirTemplate('templates/encabezado.html');
			$this->pagina->imprimirTemplate('templates/encabezadoFin.html');


			// imprimimos el template
			$this->pagina->imprimirTemplate('templates/puroError.html');
			// Imprimimos el pie
			$this->pagina->imprimirTemplate('templates/piedepagina.html');
			// y nos vamos
			return;
		}

		$this->firma = new firma();
		
		//status proceso firma
		/*
		$datos["sessionId"] = "160";
		$this->firma->Status($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
		*/
		
		//lista roles
		/*$datos["personalNumber"] = "123824660";
		/*$this->firma->ObtenerRoles($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
	
		
		
		//agrega rol
		/*
		$datos["personalNumber"] = "123824660";
		$datos["rol"] = "REPRESENTANTE2";
		$this->firma->AgregarRol($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
		*/
		
		//elimina rol
		/*
		$datos["personalNumber"] = "123824660";
		$datos["rol"] = "REPRESENTANTE2";
		$this->firma->EliminarRol($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
		*/
		//"code": 247,
        //"transactionId":2471555450977050
		
		//$datos["sessionId"] = "160";
		//$datos["id"] = "1601552656531596";
		
		//creado con json de nuevo objeto
		//$datos["sessionId"] = "275";
		//$datos["id"] = "2751555522757267"
		
		//descargar documento
		/*
		$datos["sessionId"] = "287";
		$datos["id"] = "2871555599284506";
		$this->firma->DescargarDocumento($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;
		if ($this->mensajeError == "")
		{
			print ("no es error<br>");
			$this->valor_arr = "";
			$this->obtener_dato_arr($dt,"data");
			
			if ($this->valor_arr != "")
			{
				$rutayarch = fopen("documento.pdf", "wb" ); 
				fwrite($rutayarch, base64_decode($this->valor_arr)); 
				fclose($rutayarch); 	
			}
		}
		*/
		
		//cargar documento
		/*
		$datos["firmantesdatos"][0]["firmanteid"] 			= '123824660';
		$datos["firmantesdatos"][0]["firmanterol"] 			= '';
		$datos["firmantesdatos"][0]["firmantetipofirma"]	= 'PIN';
		$datos["firmantesdatos"][0]["firmanteorden"] 		= '1';
		
		$archivo = "ANEXO DE CONTRATO";
		$archivoaux = $archivo.".pdf";
		$archivoaux = file_get_contents($archivoaux);
		$arch64 	= base64_encode($archivoaux);
		
		$datos["documentosdatos"][0]["documentobase64"]		= $arch64;
		$datos["documentosdatos"][0]["documentotipo"] 		= 'Anexo Contrato';
		
		//$datos["documentosdatos"][1]["documentotipo"] 	= 'Anexo Contrato';
				
		$this->firma->CargarDocumento($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;		
		*/
		
		//firmar documento
		$datos["authenticated"] = "true";
		$datos["id"] 			= "155629948382700157";
		$datos["sign"] 			= "67H+HoH/4c3Q9jL+seSvAfobJif6Ocsf7kjBPM/ckhk=";
		$datos["subtype"] 		= "PIN";
		$datos["type"] 			= "PIN";
		
		$datos["operadorid"] 	= "123824660";
		$datos["firmanteid"] 	= "123824660";
		$datos["sessionId"] 	= "330";
	
		$this->firma->FirmarDocumento($datos,$dt);
		$this->mensajeError.=$this->firma->mensajeError;		
		

		

		/*/*Array ( [idDocumento] => 193 

[verif_id] => 155622643591500161 
[verif_type] => PIN 
[verif_subtype] => PIN 
[verif_authenticated] => true 
[verif_sign] => TF71dXZiDhzwarRmV0kpkhUPNpkNdumitrChIDQKBKw= 
[inVerifyDocPersonalNumber] => 12382466-0 
[inVerifyType] => PIN 
accion] => FIRMA_RBK 

) Array ( [idDocumento] => 193 [verif_id] => 155622643591500161 [verif_type] => PIN [verif_subtype] => PIN [verif_authenticated] => true [verif_sign] => TF71dXZiDhzwarRmV0kpkhUPNpkNdumitrChIDQKBKw= [inVerifyDocPersonalNumber] => 12382466-0 [inVerifyType] => PIN [accion] => FIRMA_RBK )*/
		
	
		if ($this->mensajeError != "")
		{
			print ($this->mensajeError."<br><br>");
		}
		
		//print_r ($dt);
	
	}
	
	
	private function obtener_dato_arr($matriz,$variable)
	{
	
		foreach($matriz as $key=>$value)
		{
			if (is_array($value))
			{
				$this->obtener_dato_arr($value,$variable);
			}
			else
			{  
				if ($key == $variable)
				{	
					$this->valor_arr = $value;
					break;
				}
			
			}
		}
				
	} 

}
?>