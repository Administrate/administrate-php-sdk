<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $weblinkActivationParams Set this value in config.php

$categoryClass = new Category($weblinkActivationParams);
$allCategories = $categoryClass->loadAll();

echo($allCategories);
