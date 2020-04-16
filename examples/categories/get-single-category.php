<?php

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php
// $return type defined in client Class 'array' -> PHP array, 'obj' -> PHP Object and 'json' -> JSON
$categoryClass = new Category($weblinkActivationParams);

$filters = [
    'id' => $categoryIds[0]
];
$fields = [];
$returnType = 'json'; //array, obj, json

$category = $categoryClass->load($filters, $fields, $returnType);

print_r($category);
