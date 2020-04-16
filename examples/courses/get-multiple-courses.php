<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php
// $return type defined in client Class 'array' -> PHP array, 'obj' -> PHP Object and 'json' -> JSON

$courseClass = new Course($weblinkActivationParams);

$keyword = "safe";
$fields = [];
$returnType = 'array'; //array, obj, json
$paging = ['page' => 1, 'perPage' => 25];
$sorting = ['field' => 'name', 'direction' => 'asc'];
$filters = ['categoryId' => $categoryIds[0], 'keyword' => $keyword];

$allCourses = $courseClass->loadAll($filters, $paging, $sorting, $fields, $returnType);

print_r($allCourses);
