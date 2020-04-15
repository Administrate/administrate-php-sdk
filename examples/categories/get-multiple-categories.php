<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Category;

// $weblinkActivationParams Set this value in config.php
$categoryClass = new Category($weblinkActivationParams);

$fields = [];
$returnType = 3; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON
$paging = ['page' => 1, 'perPage' => 25];
$sorting = ['field' => 'name', 'direction' => 'asc'];
$filters = [];

$allCategories = $categoryClass->loadAll($filters, $paging, $sorting, $fields, $returnType);

print_r($allCategories);
