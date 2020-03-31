<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$categoryClass = new Category();

$categoryClass->setWeblinkParams($weblinkActivationParams);
$category = $categoryClass->load($categoryIds[0]);

echo $category;
