<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $courseId defined in config.php
// $categoryIds Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$courseClass = new Course();
$courseClass->setWeblinkParams($weblinkActivationParams);
$course = $courseClass->load($courseId);

echo $course;
