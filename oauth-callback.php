<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('vendor/autoload.php');

use Administrate\PhpSdk\Oauth\Activate;

var_dump('hello');

$activate = Activate::instance();

echo $activate->getAuthorizeUrl();

$activate->handleAuthorizeCallback();


