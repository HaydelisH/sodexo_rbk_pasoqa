<?php


if (!defined('__CSRF_PROTECTOR_COOKIE_CONFIG__')) {
    // to avoid multiple declaration errors.
    define('__CSRF_PROTECTOR_COOKIE_CONFIG__', true);

    class csrfpCookieConfig
    {
       
        public $path = '';

       
        public $domain = '';

      
        public $secure = false;

     
        public $expire = 28800;

      
        function __construct($cfg) {
            if ($cfg !== null) {
                if (isset($cfg['path'])) {
                    $this->path = $cfg['path'];
                }
                
                if (isset($cfg['domain'])) {
                    $this->domain = $cfg['domain'];
                }

                if (isset($cfg['secure'])) {
                    $this->secure = (bool) $cfg['secure'];
                }

                if (isset($cfg['expire']) && $cfg['expire']) {
                    $this->expire = (int)$cfg['expire'];
                }
            }
        }
    }
}