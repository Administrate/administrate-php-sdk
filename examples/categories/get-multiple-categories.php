<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $categoriesIds Set this value in config.php
// $activationParams Set this value in config.php

$activationParams = $activationParams['weblink2'];

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$categoryClass = new Category($activationParams);
$allCategories = $categoryClass->loadAll(3);
echo($allCategories);
