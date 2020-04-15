<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $courseId defined in config.php
// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php
// $return type defined in client Class 'array' -> PHP array, 'obj' -> PHP Object and 'json' -> JSON
$courseClass = new Course($weblinkActivationParams);

$filters = [
    'id' => $courseId
];
$fields = [];
$returnType = 'json'; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON

$course = $courseClass->load($filters, $fields, $returnType);

print_r($course);
