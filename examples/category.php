<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Category;

echo "<pre>";

//$categoriesIds Set this value in config.php

$categoryClass = new Category();
$category = $categoryClass->load($categoriesIds[0]);

var_dump($category);

$allCategories = $categoryClass->loadAll(3);

var_dump($allCategories);

echo "</pre>";
