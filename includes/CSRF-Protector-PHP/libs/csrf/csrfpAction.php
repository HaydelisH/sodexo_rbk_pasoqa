<?php

if (!defined('__CSRF_PROTECTOR_ACTION__')) {
    // to avoid multiple declaration errors
    define('__CSRF_PROTECTOR_ACTION__', true);


    abstract class csrfpAction {
     
        const ForbiddenResponseAction = 0;

        const ClearParametersAction = 1;

    
        const RedirectAction = 2;

      
        const CustomErrorMessageAction = 3;

     
        const InternalServerErrorResponseAction = 4;
    }
}