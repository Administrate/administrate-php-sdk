<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $weblinkActivationParams Set this value in config.php

//$weblinkParams = $weblinkActivationParams['weblink2'];

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$categoryClass = new Category();
$categoryClass->setWeblinkParams($weblinkActivationParams);
$allCategories = $categoryClass->loadAll(3);

echo($allCategories);
