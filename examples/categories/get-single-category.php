<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$categoryClass = new Category($weblinkActivationParams);
$category = $categoryClass->load($categoryIds[0]);

echo $category;
