<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

// $weblinkActivationParams Set this value in config.php

$activate = new Activate();
$activate->setParams($weblinkActivationParams);

$response = $activate->getWeblinkCode();
if ($response) {
    $portalToken = $response['body']->portal_token;

    // Save portal_token in session
    $_SESSION = array(
        'portal_token' => $portalToken
    );

    echo json_encode($_SESSION);
}
