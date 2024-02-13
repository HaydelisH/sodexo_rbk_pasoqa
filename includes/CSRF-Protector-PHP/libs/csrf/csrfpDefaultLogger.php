<?php

include __DIR__ ."/LoggerInterface.php";

if (!defined('__CSRF_PROTECTOR_DEFAULT_LOGGER_')) {
    // to avoid multiple declaration errors
    define('__CSRF_PROTECTOR_DEFAULT_LOGGER_', true);

  
    class csrfpDefaultLogger implements LoggerInterface {
       
        public function log($message, $context = array()) {
            $context['timestamp'] = time();
            $context['message'] = $message;

            // Convert log array to JSON format to be logged
            $contextString = "OWASP CSRF Protector PHP " 
                .json_encode($context) 
                .PHP_EOL;
            error_log($contextString, /* message_type= */ 0);
        }
    }
}
