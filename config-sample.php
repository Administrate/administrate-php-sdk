<?php
// Environment Variable
// Values: 'dev','stag' or 'prod'
// Default: 'dev'
define('PHP_SDK_ENV', 'dev');

// USED In Examples (can be removed if not used)
$categoriesIds = array();
$learnerId = '';
$accessToken = '';

global $APP_ENVIRONMENT_VARS;
$APP_ENVIRONMENT_VARS = array(
    'prod' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
        )
    ),
    'stag' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
        )
    ),
    'dev' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
        )
    )
);


