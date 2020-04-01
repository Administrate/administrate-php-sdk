<?php

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

//$coreApiActivationParams is set in config.php

$activate = new Activate($coreApiActivationParams);

echo "<a href='" . $activate->getAuthorizeUrl() . "'>Activate SDK<a/><br>";
