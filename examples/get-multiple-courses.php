<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

use Administrate\PhpSdk\Course;

require_once 'config.php';
require_once '../vendor/autoload.php';

$courseClass = new Course();

// $categoriesIds defined in config.php
$courses = $courseClass->loadAll(1, 5, $categoriesIds[0], "safe");

echo "<pre>";
print_r($courses);
echo "</pre>";
