<?php
require('includes/password.php');

class passwordRBK{
    // Conjunto de variables para generar token encriptados
    // variables de generacion de token para cliente solicitante de servicio
    //private $palabraSecreta = 'nolotokenCPT';
    // Variables de generacion de token por usuario
    private $metodoEncriptacion = "AES-256-CBC";
    private $llaveSecreta = 'Esta es mi llave secreta';// Esta variable puede ser modificada sin perjuicio de dañar el acceso a los usuarios, solo afectara a los token de usuario ya generados y que aun no caduquen
    private $ivSecreto = 'Esta es mi llave secreta iv';// Esta variable puede ser modificada sin perjuicio de dañar el acceso a los usuarios, solo afectara a los token de usuario ya generados y que aun no caduquen
    private $iv = '';
    private $llave = '';
    // Otras variables
    private $deltaTokenTempo = 3; // minutos
    private $palabraAleatoria = '';

	function __construct($palabraSecreta)
	{
        $this->iv = substr(hash('sha256', $this->ivSecreto), 0, 16);
        $this->llave = hash('sha256', $this->llaveSecreta);
        $this->palabraAleatoria = "ESTO ES UNA CADENA ALEATORIA A LA QUE SE LE ASIGNARA ANTE EL AHORA PRESENTE EL {$this->deltaTokenTempo} EN EL RANGO TEMPORAL PASADO COMO INICIO DE CICLO, ADEMAS CONTIENE ESTE TEXTO {$palabraSecreta} QUE ES PARTICULAR DE LA INSTACIA DE LA CLASE";
    }

    // Crea la encriptacion de corroboracion de integridad en la llamada de redireccionamiento con el servidor de autenticacion
    public function generaTokenState() {
        $stringDinamico = $this->calculoTokenDinamico();
        //$this->graba_log_error("	StringDinamico: {$stringDinamico}");
        //$this->graba_log_error("	this->hash: {$this->hash($stringDinamico)}");
        //$this->graba_log_error("	openssl_encrypt(hash): " . openssl_encrypt($this->hash($stringDinamico), $this->metodoEncriptacion, $this->llave, 0, $this->iv));
        //$this->graba_log_error("	base64_encode(cript): " . base64_encode(openssl_encrypt($this->hash($stringDinamico), $this->metodoEncriptacion, $this->llave, 0, $this->iv)));
        return base64_encode(openssl_encrypt($this->hash($stringDinamico), $this->metodoEncriptacion, $this->llave, 0, $this->iv));
    }

    // Valida la integridad de coneccion con el servidor de autenticacion
    public function validaTokenState($estado) {
        

        $hash = openssl_decrypt(base64_decode($estado), $this->metodoEncriptacion, $this->llave, 0, $this->iv);
        $stringDinamico = $this->calculoTokenDinamico();
        //$this->graba_log_error("	base64_decode(this->state): " . base64_decode($this->state));
        //$this->graba_log_error("	openssl_decrypt->hash: {$hash}");
        //$this->graba_log_error("	StringDinamico: {$stringDinamico}");
        return $this->verify($stringDinamico, $hash);
    }

    // Devuelve una contruccion valida de token
    private function calculoTokenDinamico() {
        $ahora = time();
        $minAhora = trim(strftime('%M', $ahora), "\0");
        $moduloDeltaActual = $minAhora % $this->deltaTokenTempo;
        $correccionAhora = $minAhora - $moduloDeltaActual;
        return $correccionAhora.$this->palabraAleatoria.($correccionAhora + $this->deltaTokenTempo);
    }

    /*** Genera el hash ***/
    private function hash($texto) {
        return password_hash($texto, PASSWORD_DEFAULT);
    }

    /*** Valida el hash ***/
    private function verify($texto, $hash) {
        return password_verify($texto, $hash);
    }
}
?>