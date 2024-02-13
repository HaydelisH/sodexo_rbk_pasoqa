<?php


if (!defined('__CSRF_PROTECTOR_LOGGER_INTERFACE__')) {
    // to avoid multiple declaration errors
    define('__CSRF_PROTECTOR_LOGGER_INTERFACE__', true);

   
    interface LoggerInterface {
   
        public function log($message, $context = array());
    }
}