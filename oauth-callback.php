<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('vendor/autoload.php');

use Administrate\PhpSdk\Oauth\Activate;

$activate = new Activate();
//$activate = new Activate($activationParams);

echo "<a href='" . $activate->getAuthorizeUrl() . "'>Activate SDK<a/><br>";

$response = $activate->handleAuthorizeCallback($_GET);
if ($response) {
  echo "<pre>";
  var_dump($response);
  echo "</pre>";
  $refreshToken = $response['body']->refresh_token;
  echo "<a href='/graphql-client/oauth-refreshToken.php?token=$refreshToken' target='_blank'>Refresh token<a/>";
}







