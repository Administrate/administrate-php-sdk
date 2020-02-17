<?php
define('OAUTH2_CLIENT_ID', '');
define('OAUTH2_CLIENT_SECRET', '');
define('DATE_FORMAT', 'Y-m-d H:i:s');

global $APP_ENV;
$APP_ENV = 'dev';

global $APP_ENVIRONMENT_VARS;
$APP_ENVIRONMENT_VARS = array(
    'prod' => array(
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
    ),
    'dev' => array(
        'instance' => '',
        'aouthServer' => '',
        'apiUri' => '',
        'redirectUri' => '',
    )
);


