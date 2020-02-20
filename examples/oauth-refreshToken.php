<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config.php' ;
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activate = new Activate();
//$activate = new Activate($activationParams);

if (isset($_GET['token']) && !empty($_GET['token'])) {
  $response = $activate->refreshTokens($_GET['token']);
  echo "<pre>";
  var_dump($response);
  echo "</pre>";
}
