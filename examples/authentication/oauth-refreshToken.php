<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activate = new Activate($coreApiActivationParams);

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $response = $activate->refreshTokens($_GET['token']);

    $accessToken = $response['body']->access_token;
    $refreshToken = $response['body']->refresh_token;

    echo json_encode(array('access_token' => $accessToken, 'refresh_token' => $refreshToken));
}
