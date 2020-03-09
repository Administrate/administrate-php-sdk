<?php

header('Content-Type: application/json');

require_once 'config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activationParams = $activationParams['weblink2'];

$activate = new Activate($activationParams);

//$activate = new Activate($activationParams);
//$activationParams is set in config.php

$filePath = realpath('domain.bin');
// Add this file to the examples folder should
// contain "{"domain":"[instance domain]"}"

$response = $activate->getWeblinkCode($filePath);
if ($response) {
    $portalToken = $response['body']->portal_token;

    // Save portal_token in session
    $_SESSION = array(
        'portal_token' => $portalToken
    );

    echo json_encode($_SESSION);
}
