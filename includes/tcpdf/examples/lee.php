<?php
 error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  
require_once('tcpdf_include.php');

$page = new leecorreo();

class leecorreo {

	private $hostname = '{mail.rubrika.cl:110/pop3}INBOX';
	private $username = 'pruebavacaciones@rubrika.cl';
	private $password = 'pruebavacaciones2018';


	function __construct()
	{
		$inbox = imap_open($this->hostname,$this->username,$this->password) or die('Ha fallado la conexión: ' . imap_last_error());

		if( $inbox ) 
		{ 
    
			//Check no.of.msgs 
			$num = imap_num_msg($inbox); 
			//print ("num:".$num);
			 //if there is a message in your inbox 
			 if( $num >0 ) 
			 { 	
				  //read that mail recently arrived 
				  //$prueba =  imap_body($inbox,1); 
				$info =  imap_qprint(imap_body($inbox, $num));
				
				$pos1 = strpos($info, "<div class=WordSection1>");
				$pos2 = strpos($info, "</div>");
				
				$dif = $pos2- $pos1;
				
				$dato = substr($info,$pos1 + 24 ,$dif - 24);

				
				$this->graba_log($dato);
					
				/*para borrar correo
				$chequeo = imap_mailboxmsginfo($inbox);
				echo "Mensajes antes de borrar: " . $chequeo->Nmsgs . "<br />\n";

				imap_delete($inbox, 1);
				  
				$chequeo = imap_mailboxmsginfo($inbox);
				echo "Mensajes después de borrar: " . $chequeo->Nmsgs . "<br />\n";

				imap_expunge($inbox);

				$chequeo = imap_mailboxmsginfo($inbox);
				echo "Mensajes después de purgar: " . $chequeo->Nmsgs . "<br />\n";
				*/
			} 
		//print ($prueba);
     //close the stream 
			
			imap_close($inbox); 
		}
		
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('TCPDF Example 100');
		$pdf->SetSubject('TCPDF Tutorial');
	//	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 100', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
		//$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
	//	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	//	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
	/*	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}*/

		// ---------------------------------------------------------

		// set default font subsetting mode
		//$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		//$pdf->SetFont('dejavusans', '', 14, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
/*
		$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;
		*/
		// Set some content to print
		//$html = "<span style='".'font-size:7.5pt;font-family:"Arial",'."sans-serif'>Estimado(a): SERGIO ANDRES LEIVA CONTRERAS </span>";
		
		$fp = fopen("amano2.txt", "r");

		while(!feof($fp)) 
		{
			$linea.= fgets($fp);
		}

		fclose($fp);
		
		$html = "<b><span style='font-size:7.5pt;font-family:".'Arial",sans-serif;color:white'."'>&nbsp;COMPROBANTE DE VACACIONES: Nº 56504</span></b><b>";
		$this->graba_log("arch:".$linea);
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('example_101.pdf', 'I');

	} 


/*

$emails = imap_search($inbox,'ALL');

$salida = "";
foreach($emails as $email_number)
{
  $salida=$email_number." - ";
  $overview = imap_fetch_overview($inbox,$email_number,0);
  $salida.= 'Tema: '.$overview[0]->subject;
  $salida.= ' De: '.$overview[0]->from;
  print ($salida."<br><br>");
}
*/

	private function graba_log ($mensaje)
	{
		date_default_timezone_set('America/Santiago');
		$nomarchivo = 'log'.@date("Ymd").'.TXT';
		$ar=fopen($nomarchivo,"a") or
	       die("Problemas en la creacion");
	   	fputs($ar,@date("H:i:s")." ".$mensaje);
	   	fputs($ar,"\n");
  		fclose($ar);			
	}	

}
?>