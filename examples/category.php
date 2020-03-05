<?php

header('Content-Type: application/json');

require_once 'config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Category;

//$categoriesIds Set this value in config.php

$categoryClass = new Category();
$category = $categoryClass->load($categoriesIds[0]);
//echo $category;

$allCategories = $categoryClass->loadAll(3);
echo($allCategories);

