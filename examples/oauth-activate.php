<?php

require_once 'config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activate = new Activate();
//$activate = new Activate($activationParams); //$activationParams is set in config.php

echo "<a href='" . $activate->getAuthorizeUrl() . "'>Activate SDK<a/><br>";






