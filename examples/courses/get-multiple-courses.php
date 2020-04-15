<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$courseClass = new Course($weblinkActivationParams);

$keyword = "safe";
$fields = [];
$returnType = 3; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON
$paging = ['page' => 1, 'perPage' => 25];
$sorting = ['field' => 'name', 'direction' => 'asc'];
$filters = ['categoryId' => $categoryIds[0], 'keyword' => $keyword];

$allCourses = $courseClass->loadAll($filters, $paging, $sorting, $fields, $returnType);

print_r($allCourses);
