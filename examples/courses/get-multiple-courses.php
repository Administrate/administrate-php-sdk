<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$courseClass = new Course();
$courseClass->setWeblinkParams($weblinkActivationParams);
$allCourses = $courseClass->loadAll(1, 5, $categoryIds[0], "safe");

echo $allCourses;
