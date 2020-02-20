<?php
// Environment Variable
// Values: 'dev','stag' or 'prod'
// Default: 'dev'
define('PHP_SDK_ENV', 'dev');

global $APP_ENVIRONMENT_VARS;
$APP_ENVIRONMENT_VARS = array(
    'prod' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
    ),
    'stag' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
    ),
    'dev' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
    )
);


