<?php
// Environment Variable
// Values: 'dev','stag' or 'prod'
// Default: 'dev'
define('PHP_SDK_ENV', 'dev');

// Global Environment Variables used by the SDK
global $APP_ENVIRONMENT_VARS;
$APP_ENVIRONMENT_VARS = array(
    'prod' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'oauthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
            'accessToken' => ''
        )
    ),
    'stag' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'oauthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
            'accessToken' => ''
        )
    ),
    'dev' => array(
        'clientId' => '',
        'clientSecret' => '',
        'instance' => '',
        'oauthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
        'weblink2' => array(
            'uri' => '',
            'portal' => '',
            'accessToken' => ''
        )
    )
);

// USED In Examples (can be removed if not used)
$categoriesIds = array();
$learnerId = '';
$activationParams = array(
    'clientId' => '',
    'clientSecret' => '',
    'instance' => '',
    'oauthServer' => '',
    'apiUri' => '',
    'redirectUri' => '',
    'weblink2' => array(
        'uri' => '',
        'portal' => '',
        'accessToken' => ''
    )
);


