<?php
include_once("includes/usuariosBD.php");
require('includes/passwordRBK.php');

class openIdCenter
{
	private $usuariosBD;
    private $bd;
    private $session;
    private $server_active_directory = server_active_directory;
    private $tenant_active_directory = tenant_active_directory;
    private $client_id_active_directory = client_id_active_directory;
    private $redirect_uri_active_directory = redirect_uri_active_directory;
    private $client_secret_active_directory = client_secret_active_directory;
    private $id_token = null;
    private $token_type = null;
    private $access_token = null;
    private $code = null;
    private $state = null;
    private $id_tokenDecode;
    private $keys;
    private $openid_configuration;
    private $mensajeError = '';
    private $passwordRBK;
	//csb ini
	private $usuarioidaux = '';
	//csb fin

	function __construct(&$session, &$bd) {//, $instancia) {
		$this->graba_log_error("function __construct");
		if ( LOGIN_ONE_SITE && !is_null($_REQUEST['LOGIN_ONE_SITE']) ) {
			if ( is_null($_COOKIE['LOGIN_ONE_SITE']) ) {
				setcookie("LOGIN_ONE_SITE", true,time() + 86400,'','',true,true);
			}
		} else {
            $this->passwordRBK = new passwordRBK(state_active_directory);
			$this->session = $session;
			$this->bd = $bd;
			$this->id_token = $_COOKIE['id_token_active_directory'];
			$this->token_type = $_COOKIE['token_type_active_directory'];
			$this->access_token = $_COOKIE['access_token_active_directory'];
			$this->code = $_REQUEST['code'];
			$this->state = $_REQUEST['state'];
			if (!is_null($_COOKIE['PHPSESSID'])) {
				// Obtene datos del servicio, sirve para automatizar la conexion con la API
				$this->openid_configuration_accessPoint();//(true);
			}
			if (!is_null($_COOKIE['PHPSESSID'])) { // || is_null($this->id_token)) {
				// Recupera las llaves de validacion de un tenant
				$this->getKeys_accessPoint();//(true);
			}
		}
	}

	// si se logeo el usuario con Rubrika devuelve , si se logeo con AD devuelve true
    public function loginAD() {
		$this->graba_log_error('function loginAD id_token:'.$this->id_token);
        return !is_null($this->id_token);
    }

	// Procesa las peticiones de acceso de Seguridad->sesionar
    public function acceder() {
		$this->graba_log_error('function acceder');
        if (!is_null($this->code) && $this->passwordRBK->validaTokenState($this->state) && is_null($this->id_token)) {
            $this->graba_log_error('function acceder AUTENTICACION:');
            $this->graba_log_error("	function acceder Token de autenticacion de rubrika recibido como state: {$this->state}");
            $this->graba_log_error("		function acceder Validacion del Token: " . ($this->passwordRBK->validaTokenState($this->state) ? 'true' : 'false') );
            $this->graba_log_cookie(true);
            $this->graba_log_error("	function acceder Active Directory:");
            $this->graba_log_error("		function acceder code = {$this->code}");
            $this->graba_log_error("		function acceder state = {$this->state}");
            // Obtenemos el token de acceso
            $this->getToken_accessPoint(true);//(, true);
            // Verificamos el token y guardamos el contenido
            if ($this->verificarToken_accessPoint(true)) {
                if ( $this->tenant_active_directory != 'common') {
					//csb ini
                    //$this->getMe_accessPoint(true);
					//csb fin
					
					// Recuperar el usuario en RBK que tiene asignado el correo electronico
					$this->usuariosBD = new usuariosBD();
					$this->usuariosBD->usarConexion($this->bd->conexion);
					$this->usuariosBD->obtener(array(
						'usuarioid'=>$this->usuarioid
					), $dt);
					
					if (count($dt->data)) {
						if($this->usuarioid!='')
						{
							$this->session->crearSesionCCO($dt->data[0]["usuarioid"]);
						}
					} else{
						// echo 'El usuario no existe en rubrika';
						// $this->logout();
						$this->redirigir("logout.php?mensajeErrorRUBRIKA=El usuario no existe en RUBRIKA",0);
						
					}
					
                } else { // Solo en localhost usando tenant common
                    // Recuperar el usuario en RBK que tiene asignado el correo electronico
                    $this->usuariosBD = new usuariosBD();
                    $this->usuariosBD->usarConexion($this->bd->conexion);
                    $this->usuariosBD->obtenerCCO_email(array(
                        'usuarioid'=>'',
                        'clave'=>$this->id_tokenDecode['email']
                    ), $dt);
                    if (count($dt->data)) {
                        if (count($dt->data) == 1) {
                            $this->session->crearSesionCCO($dt->data[0]["usuarioid"]);
                            $redireccionarA = "Location: {$this->redirect_uri_active_directory}";
                            header($redireccionarA);
                        } else {
                            echo 'Solo en localhost usando tenant common: El correo electronico esta duplicado en rubrika';
                        }
                    } else {
                        echo 'Solo en localhost usando tenant common: El correo electronico no existe en rubrika';
                    }
                }
            } else {
                $this->getAuthorize_accessPoint();
            }
        } else if ( !is_null($this->id_token) ) {
            $this->graba_log_error('VALIDACION:');
            $this->graba_log_cookie(true);
            //$this->graba_log_error("Eureka:");
            //echo 'Eureka!!!... tenemos token en cookie</br>';
            //echo 'Falta refrescar el access_token</br>';
            /* Excepción capturada: Expired token -> 
            $aux = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6ImJXOFpjTWpCQ25KWlMtaWJYNVVRRE5TdHZ4NCJ9.eyJ2ZXIiOiIyLjAiLCJpc3MiOiJodHRwczovL2xvZ2luLm1pY3Jvc29mdG9ubGluZS5jb20vOTE4ODA0MGQtNmM2Ny00YzViLWIxMTItMzZhMzA0YjY2ZGFkL3YyLjAiLCJzdWIiOiJBQUFBQUFBQUFBQUFBQUFBQUFBQUFGd18xaXctVGdfMlhFeGN4R1NrWmNnIiwiYXVkIjoiNjczMWRlNzYtMTRhNi00OWFlLTk3YmMtNmViYTY5MTQzOTFlIiwiZXhwIjoxNjMxMzc4NTAwLCJpYXQiOjE2MzEyOTE4MDAsIm5iZiI6MTYzMTI5MTgwMCwiZW1haWwiOiJrb210YXZpbkBnbWFpbC5jb20iLCJ0aWQiOiI5MTg4MDQwZC02YzY3LTRjNWItYjExMi0zNmEzMDRiNjZkYWQiLCJhaW8iOiJEYVJpQXJDSXk2MG9icHJCTlV2KjZzZVZ5SyFHIUl3VnIqT2RBNjB6ZGJqQW1paiFhcXZydXRSOVRFeDI5QmRxUExuVSFtTkg2VDI3ZjJ3R2F3aVdzZHRJbGRtS1dXZVViMjRFUjNhdHBXY1FMU042c2hIVU1nVGl5ZGhkbVBQejUwY3dPRU1idHBaKnhveEhpVkJ1eSo2dDNvSWJoeGtIWWZrRGdQcTlTUnlnIn0.X197ZFjUecntCYX--r6XJolLAV2N7sRBFkAooXozgH78xAL4buXFwwr0K5w9mJVw-y9Ht6KS-fePoXu9tBdMv6YOWlmeBhw7FFp3YyktPxdqOPTsseGUjmHaYO01EKirfqDKz1NL3ViATKlN-vc6UzLWwhs35_NmX2nVOXsxWRcOJtOR4q9gpwMhUxGFlppp-cqFUxXH-O0ctOSlhO05Yhs8jR3IHhp4ZZ02hpSefZM4AbxrhqPz46yDj0J9scZK-dDrroFbOmBqKU5fNviIqgHMpgICr86FdFURhHuzfUA2fNnQrlwJfIKNrwKlJjoRlZYDgE527c_otoNnfWoJuw";
            $this->openIdCenter->setToken($aux, true);//(true);
            */
            /**/
            //$this->openIdCenter->setToken($_COOKIE['id_token_active_directory'], true);//(true);
            // Verificamos el token y guardamos el contenido
            if (!$this->verificarToken_accessPoint(true)) {
                // Dado que el id_token pudo haber caducado con error 
                switch ($this->mensajeError) {
                    case 'No se comprobo aud':
                    case 'No se comprobo issuer': {
						$this->graba_log_error("Falla en integridad de datos con el siguiente mensaje: $this->mensajeError");
						$this->graba_log_error("Accion: Redireccionamiento");
                        $this->getAuthorize_accessPoint();
                        break;
                    }
                    case 'Expired token':
                    case 'id_token expirado': {
                        // Cerramos la sesion de rubrika y la de active directory
						$this->graba_log_error("id_token expirado con el siguiente mensaje: $this->mensajeError");
						$this->graba_log_error("Accion: Logout");
                        $_REQUEST["accion"] = "cerrarSesion";
                        if (!$this->session->cerrarSesion()) {
                            //var_dump(123);
                            $this->logout();
                            //$this->session->mensajeError = "";
                            $this->session->datos["session"] = "";
                            $this->session->logear = true;
                            //if ($obligarlogear) $this->imprimeLogin();
                            //return false;
                        }
                        break;
                    }
                }
            }
            // SOLO PRUEBA
            //$this->getUserInfo_accessPoint(true);
            //$this->openid_configuration_accessPoint(true);
            //$this->getKeys_accessPoint(true);
        //OK } else if ( is_null($this->id_token) && (LOGIN_ONE_SITE ? ( !is_null($_REQUEST['LOGIN_ONE_SITE']) ? !$_REQUEST['LOGIN_ONE_SITE'] : true ) : !is_null($_REQUEST['OPEN_ID_CONNECT']) ) ) { // Esto solo ocurre cuando se ejecuta index.php
        } else if ( is_null($this->id_token) && (LOGIN_ONE_SITE ? ( !is_null($_REQUEST['LOGIN_ONE_SITE']) ? !$_REQUEST['LOGIN_ONE_SITE'] : ( !is_null($_COOKIE['LOGIN_ONE_SITE']) ? false : true ) ) : !is_null($_REQUEST['OPEN_ID_CONNECT']) ) ) { // Esto solo ocurre cuando se ejecuta index.php
            // Redireccionamiento a servidor de active directory de microsoft
            $this->graba_log_error('REDIRECIONADO:');
            $this->graba_log_cookie(true);
            $this->getAuthorize_accessPoint();
        }
    }

    // Ejecuta el logout de la sesion CCO
    public function logout() {
		$this->graba_log_error("function logout");
        if ( !is_null($this->id_token) ) {
            $this->graba_log_error('LOGOUT:');
            $this->graba_log_cookie(true);
            // Ejecuta el logout den el servidor AD
            $this->getLogout_accessPoint();
        }
    }

    // Verificamos el token y guardamos el contenido (PHP-7.4)
    private function verificarToken_accessPoint($log_retorno = false) {
		//csb ini
		$this->usuarioidaux = '';
		//csb fin
		$this->graba_log_error("function verificarToken_accessPoint");
        $accessPointName = '/jwt/v1.0/decode';
        $url 			= str_replace('@accessPointName@', $accessPointName, SERVICIO_JWT);
        // Excepción capturada: Expired token -> $this->id_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6ImJXOFpjTWpCQ25KWlMtaWJYNVVRRE5TdHZ4NCJ9.eyJ2ZXIiOiIyLjAiLCJpc3MiOiJodHRwczovL2xvZ2luLm1pY3Jvc29mdG9ubGluZS5jb20vOTE4ODA0MGQtNmM2Ny00YzViLWIxMTItMzZhMzA0YjY2ZGFkL3YyLjAiLCJzdWIiOiJBQUFBQUFBQUFBQUFBQUFBQUFBQUFGd18xaXctVGdfMlhFeGN4R1NrWmNnIiwiYXVkIjoiNjczMWRlNzYtMTRhNi00OWFlLTk3YmMtNmViYTY5MTQzOTFlIiwiZXhwIjoxNjMxMzc4NTAwLCJpYXQiOjE2MzEyOTE4MDAsIm5iZiI6MTYzMTI5MTgwMCwiZW1haWwiOiJrb210YXZpbkBnbWFpbC5jb20iLCJ0aWQiOiI5MTg4MDQwZC02YzY3LTRjNWItYjExMi0zNmEzMDRiNjZkYWQiLCJhaW8iOiJEYVJpQXJDSXk2MG9icHJCTlV2KjZzZVZ5SyFHIUl3VnIqT2RBNjB6ZGJqQW1paiFhcXZydXRSOVRFeDI5QmRxUExuVSFtTkg2VDI3ZjJ3R2F3aVdzZHRJbGRtS1dXZVViMjRFUjNhdHBXY1FMU042c2hIVU1nVGl5ZGhkbVBQejUwY3dPRU1idHBaKnhveEhpVkJ1eSo2dDNvSWJoeGtIWWZrRGdQcTlTUnlnIn0.X197ZFjUecntCYX--r6XJolLAV2N7sRBFkAooXozgH78xAL4buXFwwr0K5w9mJVw-y9Ht6KS-fePoXu9tBdMv6YOWlmeBhw7FFp3YyktPxdqOPTsseGUjmHaYO01EKirfqDKz1NL3ViATKlN-vc6UzLWwhs35_NmX2nVOXsxWRcOJtOR4q9gpwMhUxGFlppp-cqFUxXH-O0ctOSlhO05Yhs8jR3IHhp4ZZ02hpSefZM4AbxrhqPz46yDj0J9scZK-dDrroFbOmBqKU5fNviIqgHMpgICr86FdFURhHuzfUA2fNnQrlwJfIKNrwKlJjoRlZYDgE527c_otoNnfWoJuw";
        if (isset($_REQUEST['test_id_token'])) {
            $parametros = array(
                'id_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6ImJXOFpjTWpCQ25KWlMtaWJYNVVRRE5TdHZ4NCJ9.eyJ2ZXIiOiIyLjAiLCJpc3MiOiJodHRwczovL2xvZ2luLm1pY3Jvc29mdG9ubGluZS5jb20vOTE4ODA0MGQtNmM2Ny00YzViLWIxMTItMzZhMzA0YjY2ZGFkL3YyLjAiLCJzdWIiOiJBQUFBQUFBQUFBQUFBQUFBQUFBQUFGd18xaXctVGdfMlhFeGN4R1NrWmNnIiwiYXVkIjoiNjczMWRlNzYtMTRhNi00OWFlLTk3YmMtNmViYTY5MTQzOTFlIiwiZXhwIjoxNjMyNDYyNjE3LCJpYXQiOjE2MzIzNzU5MTcsIm5iZiI6MTYzMjM3NTkxNywibmFtZSI6Ikd1c3Rhdm8gRGlheiIsInByZWZlcnJlZF91c2VybmFtZSI6ImtvbXRhdmluQGdtYWlsLmNvbSIsIm9pZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC1kMTE2LWE2NmQ2YjUxOTZkMSIsImVtYWlsIjoia29tdGF2aW5AZ21haWwuY29tIiwidGlkIjoiOTE4ODA0MGQtNmM2Ny00YzViLWIxMTItMzZhMzA0YjY2ZGFkIiwiYWlvIjoiRFFKd1RqUHFVNUZDa3pLOHJsQlVqaTY5NzNINnNQaEdXYTF5bSFkMnRDYzQ0d25nOHZTTWtXKkNlbWRSdW1TZU1EWXNwSXNKTmcqU3lSU1QxWExmNlZuQnlFZ1Y0QkJqYW1ZbnFPa3B3QlIqa1BUcFQqeFhBYU9ZM1o4MGVaYSpKU1poY3khdGVEV1pXNUc5SWNYRzJWOCQifQ.X-XuWgjDCCk0_0wuYwshzDf6rZByorgW0ZOGyNO1JatkBtflprXLv-t44YN3PUuF_31gyo9LbzTeTB-HMkbOn3CRKoAiXOqvBNGGVUGfuWmvlrcPOewj9kTsdCPOL6tTjcYMZ36YYtzozFXquCOLqAIlOPYws-AUH-TtOOw3aQCFNmZ8gEUyX4CfzWZ359jWf-GCkU406jPNOH4s5XgwCA0vU3mTzSeaEaykvyQ_DJaGHEru1gLo4_qNkFezqn7zOYxls1uPuwLNQnc30arIBwSBLwuSmUmtKyDlQ9AF6VTbmHV0Lc5HfaTmGHi26bFI_O_I3Xdt0XMAb7D7S2kGSA',
                'tenant_active_directory' => $this->tenant_active_directory,
                'server_active_directory' => $this->server_active_directory
            );
        } else {
            $parametros = array(
                'id_token' => $this->id_token,
                'tenant_active_directory' => $this->tenant_active_directory,
                'server_active_directory' => $this->server_active_directory
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $this->prepara_parametros($parametros));
        if (substr($url, 0, 8) == 'https://'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
            curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $respuesta = curl_exec($ch);
        if( $respuesta === false ) {
            $mensaje = "Hubo un error en la coneccion con el servicio local JWT";
            $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
        } else {
            $res = json_decode($respuesta, true);
            if ($log_retorno) {
                $this->graba_log_error("	function verificarToken_accessPoint Punto de acceso JWT ({$accessPointName}):");
                $this->graba_log_error("	function verificarToken_accessPoint	LLAMADA: {$url}?" . $this->prepara_parametros($parametros));
                $this->log_error_dump($parametros, '	function verificarToken_accessPoint	parametros		');
                $this->graba_log_error("	function verificarToken_accessPoint	RESPUESTA:");
                $this->log_error_dump($res, '	function verificarToken_accessPoint	res: ');
            }
            // Validamos el token
			$this->graba_log_error(" function verificarToken_accessPoint antes de res[estado] ");
            if ($res['estado']) {
                $this->id_tokenDecode = $res['id_tokenDecode'];
				$this->graba_log_error("function verificarToken_accessPoint jwt:",$this->id_tokenDecode);
				//csb ini obtener rut_persona
				$this->graba_log_error(" function verificarToken_accessPoint antes de res[rut_persona] ");
				
				if ($this->usuarioidaux != '' )
				{
					$this->usuarioid = $this->usuarioidaux;
					$this->graba_log_error(" function verificarToken_accessPoint existe rut_persona ".$this->usuarioid);
				}
				//csb fin obtener rut_persona
                return $this->verificar_id_token();
            } else {
                $this->mensajeError = $res['mensaje'];
                return false;
            }
        }
        curl_close($ch);
    }

    // Aplica validaciones segun normativa openId connect
    private function verificar_id_token() {
		$this->graba_log_error("function verificar_id_token");
        if ($this->id_tokenDecode['aud'] !== $this->client_id_active_directory) {
            $this->mensajeError = 'No se comprobo aud';
            return false;
        }
        if ( $this->tenant_active_directory != 'common' ) {
            for ($i = 0; $i < count($this->keys['keys']); $i++) {
                if ($this->id_tokenDecode['iss'] !== $this->keys['keys'][$i]['issuer']) {
                    $this->mensajeError = 'No se comprobo issuer';
                    return false;
                }
            }
        } else { // Solo en localhost usando tenant common
            // Si falla revisar la data en: https://login.microsoftonline.com/common/discovery/v2.0/keys
            for ($i = count($this->keys['keys']) - 2; $i < count($this->keys['keys']); $i++) {
                if ($this->id_tokenDecode['iss'] !== $this->keys['keys'][$i]['issuer']) {
                    $this->mensajeError = 'No se comprobo issuer';
                    return false;
                }
            }
        }
        $ahora = new DateTime();
        $this->graba_log_error('	function verificar_id_token Segundos restantes: ' . ($this->id_tokenDecode['exp'] - $ahora->getTimestamp()));
        if ($this->id_tokenDecode['exp'] < $ahora->getTimestamp()) {
            $this->mensajeError = 'id_token expirado';
            return false;
        }
        return true;
    }

    // Prepara los parametros que se enviaran via cURL
    private function prepara_parametros($parametros) {
		$this->graba_log_error("function prepara_parametros");
        foreach ( $parametros as $key => $value)  
        {  
          $post_items[] = $key . '=' . $value;  
        }  
        return implode('&', $post_items);
    }

    // Obtener datos del usuario identificado en microsoft
    private function getMe_accessPoint($log_retorno = false) {
		$this->graba_log_error("function pgetMe_accessPoint");
        $accessPointName = '/v1.0/me';
        $parametros = array(		
			'$select' => 'userPrincipalName,onPremisesSamAccountName,extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1'
        );
		//'$select' => 'onPremisesExtensionAttributes'
		//A2021
        $URL_me = str_replace('/oidc/userinfo', '', $this->openid_configuration['userinfo_endpoint']) . $accessPointName . '?' . $this->prepara_parametros($parametros);
        $ch = curl_init();
		$headers = array(
			"Authorization: {$this->token_type} {$this->access_token}"
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $URL_me);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $respuesta = curl_exec($ch);
		$this->graba_log_error("function pgetMe_accessPoint Respuesta ".$respuesta);
        if( $respuesta === false ) {
            $mensaje = "Hubo un error en la coneccion con microsoft al intentar obtener informacion del usuario";
            $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
			
			
        } else {
            $res = json_decode($respuesta, true);
			
			//if (isset($res['onPremisesExtensionAttributes']) ? (isset($res['onPremisesExtensionAttributes']['extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1']) ? true : false ): false) {
                //$this->usuarioid = $res['onPremisesExtensionAttributes']['extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1'];
              if (isset($res['extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1'])) {
				   $this->usuarioid = $res['extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1'];
            }	               

			else {
                $mensaje = "La peticion no devuelve valor para onPremisesExtensionAttributes->extension_497087e715e04236bcc6e4bf3cfcf18a_extensionAttribute1";
                $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
				
            }
            if ($log_retorno) {
                $this->graba_log_error("		LLAMADA: {$URL_me}?" . $this->prepara_parametros($parametros));
                //$this->graba_log_error("		LLAMADA: {$URL_me}");
                $this->log_error_dump($parametros, '			');
                $this->graba_log_error("		RESPUESTA:");
                $this->log_error_dump($res, '			');
            }
        }
        curl_close($ch);
    }

    // Obtener datos generales del usuario
    private function getUserInfo_accessPoint($log_retorno = false) {
		$this->graba_log_error("function getUserInfo_accessPoint");
        $accessPointName = '/oidc/userinfo';
        $parametros = array(
            'token_type' => $this->token_type,
            'access_token' => $this->access_token
        );
        $URL_userInfo = $this->openid_configuration['userinfo_endpoint'];
        /*
        // 2021-09-09 06:50:58
        //$parametros .= "&access_token=EwBwA8l6BAAUwihrrCrmQ4wuIJX5mbj7rQla6TUAAf2coXqjWS906IDcVPvyJ5+BrXhnswfwu3KQ5ZEs1yR4eqjAusyjgQbUKYGykoV6zgUna9xHl98jGaqMd17dlWpqJnn7Uba9/fH2Ohk51teaRun7KfqzkoAQ+mreKodTbvI6wE/tqsjYHmXivC/EbFalghLIgezKvoWHVIijpcaWgqiy0rQ+12QiUDtlsJabwpEeRc6AjxWGm6hLCHerQ90MaXt7NtVtuNkbtkXKtC61+aa9/O2m4Kp+jYK0+xr2LmB6zpsSLUelMu2rfflTsViBx5f2nT1lApMegCjc0c5Ix4d+5MpwZNATE0gEvn/MYBQ2BtHMQ21Mp9Eb3cRYdVoDZgAACOrVpsIDCogRQAJMS3gUmafM+EvTK4xWMiFeg/mSrMgSJYYLzegGYDnR3ZhMutn9PuHKoxshGo3XqrHI1jB6oC9zSvyXw43njgDBp1ja1w7uZDpdWIpIT7HbhJ5dnEP2orhKmX3roBCm4JZTEX+pf61E1qYTqNIlZ75bAJMK8rzzur98XJEwKSJfLX9KXCJmlvq4sVlNY+s6qa64/J8AKoUvg8atLPSjIVbyGsSbKr2M31PFexHKwafm4j6SS32i+GqFXpR4au3ie6ompd32KSZlkBJmmRMQ4nGBUUrs7i6HBcu4b5h2GD6Yojz9wjQxN40iW2OxZvRhWHtQ+dW6fIKXrWDKCZbXCtqCJXkQBWVylvv53/5nQ+BnbyLc5R1OhpdvbfWex3wmiyaQKhO9Yixu087awMqsCo90JTl/9D4AjtcHsH+ScexkD7iczsdMC8OOqDUXlbNieWbLCWc+HJeBdqpXxfHvkq7dF80WK13y0jNyBDFEUvGI4OTd9Bjk2lvJ/cUkSOM6P9KMhsNTc3qpvLFFCnw4bM6iO/Ix71V1VIF8s2wchRZpm9nZBMxZg9fEHoV01CUw9ix6QPZbV/0+8RyfFaLuCa8GlQRDeNBGDv5Xb05rqJb8Z3DwGsfEp0nYrdj6sVOmUxvAjQILoyIWhoeKsDlqr/sWmsw4UZ7ul7MUv2LapE9v/3wUW/5Ro75ohmqlpG+YZf3LQlLIZMfMKpJA/2dmXo8rzIHaw8eU5flQARZ5ekFgml6OY52aBAGjG7KVBqnZwJZ5Ag==";
        //14:12:08 	RESPUESTA:
        //14:12:08 		error:
        //14:12:08 			code = InvalidAuthenticationToken
        //14:12:08 			message = CompactToken validation failed with reason code: 80049228.
        //14:12:08 			innerError:
        //14:12:08 				date = 2021-09-10T17:15:04
        //14:12:08 				request-id = a9a56f09-0d37-4bbc-a6bd-c2694b7afd32
        //14:12:08 				client-request-id = a9a56f09-0d37-4bbc-a6bd-c2694b7afd32
        // TESTIMONIO
        //$parametros .= "&access_token=EwBoA8l6BAAUwihrrCrmQ4wuIJX5mbj7rQla6TUAAWmAp3KfaniJij7zz2hCojgCOBimj8nmBaw+28h1n8l/iNRw6e7CpCUjvDy4SXBjdcneGWfJ6F8DMgb8szGHnl5pUjSJSNhEztG++J8cCP20t6e6Rb+Rv6P0vkZpwo/ha3COKHIvry5jrym9OKidGDO3dS07d/2Lmf78ZT8EJqBgFZ3quQKv0KWSZgl2rUaldX8elzF+icj5SJ3+jKQA0mIHmbzzr55oxBM8/ZTwYHfqhiZWNzrQnyhwPreGZrxRuh1FdN5zd1RpYHwwnDkE+xFnmXkV6a4sygIREMPYkJzRbCtSXTebOj8qftX8tv1swAWcOKgQWXhBj96AiSPCSx8DZgAACB0gLIJAyks8OAJNU9OnojApSA4O5DyIJpYcsYGiiTnut1dKD4COEqMbj1YwPpFxGfOxWdOi+LAlb2TBI1VyUt4CNQNQiXbB8WhyawityLB2O63/l56jnH5ycjDiJOVL+fjb/dCdP/7ZLrdNp4RsuAISFiTK1K2M/WE/aM7oPChj7p2MQqwbutTomvnYXOAUuKabCSe5c9QyyLgidvYVhOCCF/qW8q08AVt0SFsJJB4sHv+Qx7z0UZv/ihhVLCDeZC1YUMzvq8x2dcG5EMSgIEgx0iUdaypGPRKiNTURe3ODacjSsb2OK8ypRcKSbZGlQEsDB6nxMsl4WgM0N5n6G1FcYO2zckvYwc76JqFkUY9DkxkaQruMlmZC9UAT6b1js/FMt8gRLRQJMSmkim8K93GHO0XfBVpEbwAe17fEPTfQMqNg5OjwnTslnzKavYGlA1QzC8H5FUOZhQJhSJg4AM26jta5tzKng1dWRqPYJTc5V0KtyIp8eqBXuUzzZ7kXHTyMLLTpj607tslhupHkBbEtxgKK3pYEClOozr8ncalvtAay/BmDo+7SUDbPFRcyHsbepQDcqTBKAvPZsT3qujizN6c3ly6wAXw0xD43U0WZBhPbL1NqzH37Iv0UnYd07zY73g1IingfC2j07EQY4ddfePLM3wVZGtIkBVxJ7vdAGm31NhinUNN6yZoKCf4F1LLVDSMLys70ky3BxGQNcHPyHWzA2dQT/O4Xcjtr11qE5jZqSzWuJokwUFctLdnKyYD/cQI=";
        //$parametros .= "&id_token={$res['id_token']}";*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL_userInfo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->prepara_parametros($parametros));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $respuesta = curl_exec($ch);
        if( $respuesta === false ) {
            $mensaje = "Hubo un error en la coneccion con microsoft al intentar obtener informacion del usuario [2]";
            $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
        } else {
            $res = json_decode($respuesta, true);
            if (!isset($res['error'])) {
            } else {
            }
            if ($log_retorno) {
                $this->graba_log_error("	function getUserInfo_accessPoint Punto de acceso AD ({$accessPointName}):");
                $this->graba_log_error("		function getUserInfo_accessPoint LLAMADA: {$URL_userInfo}?" . $this->prepara_parametros($parametros));
                $this->log_error_dump($parametros, '			');
                $this->graba_log_error("		function getUserInfo_accessPoint RESPUESTA:");
                $this->log_error_dump($res, '			');
            }
        }
        curl_close($ch);
    }

    // Recupera las llaves de validacion de un tenant
    private function getKeys_accessPoint($log_retorno = false) {
		$this->graba_log_error("function getKeys_accessPoint");
        $accessPointName = '/discovery/v2.0/keys';
        $url = $this->openid_configuration['jwks_uri'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $respuesta = curl_exec($ch);
        if( $respuesta === false ) {
            $mensaje = "Hubo un error en la coneccion con microsoft al intentar obtener el punto de acceso {$accessPointName}";
            $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
        } else {
            $res = json_decode($respuesta, true);
            $this->keys = $res;
			if ($log_retorno) {
                $this->graba_log_error("	function getKeys_accessPoint Punto de acceso AD ({$accessPointName}):");
                $this->graba_log_error("		function getKeys_accessPoint LLAMADA: {$url}");
                $this->graba_log_error("		function getKeys_accessPoint RESPUESTA:");
                $this->log_error_dump($res, '			');
			}
        }
        curl_close($ch);
    }

    // Obtene datos del servicio, sirve para automatizar la conexion con la API
    private function openid_configuration_accessPoint($log_retorno = false) {
		$this->graba_log_error("function openid_configuration_accessPoint");
        $accessPointName = '/v2.0/.well-known/openid-configuration';
        $url = "{$this->server_active_directory}{$this->tenant_active_directory}{$accessPointName}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
        curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        $respuesta = curl_exec($ch);
        if( $respuesta === false ) {
            $mensaje = "Hubo un error en la coneccion con microsoft al intentar obtener el punto de acceso {$accessPointName}";
            $this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
        } else {
            $res = json_decode($respuesta, true);
            $this->openid_configuration = $res;
            if ($log_retorno) {
                $this->graba_log_error("	function openid_configuration_accessPoint Punto de acceso AD ({$accessPointName}):");
                $this->graba_log_error("		function openid_configuration_accessPoint LLAMADA: {$url}");
                $this->graba_log_error("		function openid_configuration_accessPoint RESPUESTA:");
                $this->log_error_dump($res, '			');
            }
        }
        curl_close($ch);
    }

    // Ejecuta el logout en el servidor AD
    private function getLogout_accessPoint($log_retorno = false) {
		$this->graba_log_error("function getLogout_accessPoint");
        $accessPointName = '/oauth2/v2.0/logout';
        $url = $this->openid_configuration['end_session_endpoint'];
        $redireccionarA = "Location : {$url}";
        $redireccionarA .= "?post_logout_redirect_uri={$this->redirect_uri_active_directory}";
        header($redireccionarA);
        setcookie("id_token_active_directory", "", time()-3600,'','',true,true);
        setcookie("token_type_active_directory", "", time()-3600,'','',true,true);
        setcookie("access_token_active_directory", "", time()-3600,'','',true,true);
        setcookie("refresh_token_active_directory", "", time()-3600,'','',true,true);
    }

    // Obtenemos el token de acceso
	private function getToken_accessPoint($log_retorno = false) {
		$this->graba_log_error("function getToken_accessPoint");
		$accessPointName = '/oauth2/v2.0/token';
        $url = $this->openid_configuration['token_endpoint'];
		$parametros = array(
            'grant_type' => 'authorization_code',
		    'code' => $this->code,
    		'client_id' => $this->client_id_active_directory,
            //'scope' => 'https://graph.microsoft.com/User.Read',
            //'scope' => 'https://graph.microsoft.com',
			//'scope' => 'https://graph.microsoft.com/mail.read',
			//'scope' => 'mail.read',
            //'scope' => 'https://graph.microsoft.com/User.Read.All',
            'redirect_uri' => $this->redirect_uri_active_directory,
            'client_secret' => $this->client_secret_active_directory
        );
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->prepara_parametros($parametros));
		//curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 4);
  		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
		$respuesta = curl_exec($ch);
		if( $respuesta === false ) {
			$mensaje = "Hubo un error en la coneccion con microsoft al intentar obtener el token de acceso";
			$this->graba_log_error("Curl : ".curl_errno($ch)." ".curl_error($ch)." ".$mensaje);
		} else {
			$res = json_decode($respuesta, true);
            //access_token | refresh_token | token_type | scope | expires_in | ext_expires_in | id_token
			// Agregamos el token de acceso y token de refresco al flujo de sesion
			setcookie('access_token_active_directory', $res['access_token'],time() + 86400,'','',true,true);
			$this->access_token = $res['access_token'];
			setcookie('token_type_active_directory', $res['token_type'],time() + 86400,'','',true,true);
			$this->token_type = $res['token_type'];
			setcookie('refresh_token_active_directory', $res['refresh_token'],time() + 86400,'','',true,true);
			setcookie('id_token_active_directory', $res['id_token'],time() + 86400,'','',true,true);
			$this->id_token = $res['id_token'];
			if ($log_retorno) {
				$this->graba_log_error("	function getToken_accessPoint Punto de acceso AD ({$accessPointName}):");
				$this->graba_log_error("		function getToken_accessPoint LLAMADA: {$url}?" . $this->prepara_parametros($parametros));
                $this->log_error_dump($parametros, '			');
				$this->graba_log_error("		function getToken_accessPoint RESPUESTA:");
				$this->log_error_dump($res, '			');
			}
		}
		curl_close($ch);
	}

    // Redireccionamiento a servidor de active directory de microsoft
    private function getAuthorize_accessPoint($log_retorno = false) {
		$this->graba_log_error("function getAuthorize_accessPoint");
        $url = $this->openid_configuration['authorization_endpoint'];
        $redireccionarA = "Location : {$url}";
        $redireccionarA .= "?response_type=code";
        $redireccionarA .= "&client_id={$this->client_id_active_directory}";
        $redireccionarA .= "&redirect_uri={$this->redirect_uri_active_directory}";
        $redireccionarA .= "&response_mode=query";
        $tkn = $this->passwordRBK->generaTokenState();
        $this->graba_log_error("	function getAuthorize_accessPoint Token de autenticacion de rubrika enviado como state getAuthorize_accessPoint: {$tkn}");
        $redireccionarA .= "&state={$tkn}";
        $redireccionarA .= "&scope=openid email profile offline_access"; # openid profile email offline_access
        header($redireccionarA);
    }
	public function redirigir($url,$tiempo) {
		$this->graba_log_error("function redirigir");
		header("Refresh:".$tiempo."; url=".$url);
		exit();
	}
    // Imprime las cookies de sesion de usuario
    private function graba_log_cookie($log_retorno = false) {
		$this->graba_log_error("function graba_log_cookie");
        if ($log_retorno) {
            $accessPointName = '_COOKIE';
            $this->graba_log_error(" function graba_log_cookie	{$accessPointName}:");
            foreach ($_COOKIE as $llave=>$valor){
                $this->graba_log_error(" function graba_log_cookie		{$llave} = {$valor}");
            }
        }
    }

    // Iterador en la respuesta de la API para informar en el log
	private function log_error_dump($res, $tab) {
		$this->graba_log_error("function log_error_dump");
		foreach ($res as $llave=>$valor){
			if (is_array($valor)) {
				$this->graba_log_error("{$tab}{$llave}:");
				$this->log_error_dump($valor, "{$tab}	");
			} else {
                $valor = (is_bool($valor) ? ( $valor === true ? 'true' : 'false' ) : $valor );
				$this->graba_log_error("function log_error_dump {$tab}{$llave} = {$valor}");
				
				//csb ini 
				$pos = strpos($llave, 'rut_persona');
				if ( $pos !== false ) 
				{
					$this->usuarioidaux = $valor;
				}
				//csb fin
			}
		}
	}

	private function graba_log_error($info)
	{
		date_default_timezone_set('America/Santiago');
		$time = time();
		$nomarchivo = 'logs\seguridad_error_'.@date("Ymd").'.TXT';
		$ar=@fopen($nomarchivo,"a");// or
	       //die("Problemas en la creacion");
	   	@fputs($ar,@date("H:i:s",$time)." ".$info);
	   	@fputs($ar,"\n");
        @fclose($ar);
	}
}
?>