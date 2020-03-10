<?php
require_once 'config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activate = new Activate();
//$activate = new Activate($activationParams);

$response = $activate->handleAuthorizeCallback($_GET);

if ($response) {
    $accessToken = $response['body']->access_token;
    $refreshToken = $response['body']->refresh_token;

    // Save access_token & refresh_token in session
    $_SESSION = array(
        'access_token' => $accessToken,
        'refresh_token' => $refreshToken
    );

    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

    $refreshLink =  BASE_URL."/examples/oauth-refreshToken.php";
    $refreshLink .= "?token=$refreshToken";

    echo "<a href='$refreshLink' target='_blank'>Refresh token<a/>";
}
