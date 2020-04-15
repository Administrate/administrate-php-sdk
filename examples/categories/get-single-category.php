<?php

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php
// $return type defined in client Class 1 -> PHP array, 2 -> PHP Object and 3 -> JSON
$returnType = 1;

$categoryClass = new Category($weblinkActivationParams);

$filters = [
    'id' => $categoryIds[0]
];
$fields = [];
$returnType = 3; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON

$category = $categoryClass->load($filters, $fields, $returnType);

print_r($category);
