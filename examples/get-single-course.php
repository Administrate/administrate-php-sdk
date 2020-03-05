<?php

header('Content-Type: application/json');

require_once 'config.php';
require_once '../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $courseId defined in config.php
// $categoriesIds Set this value in config.php
// $activationParams Set this value in config.php

$activationParams = $activationParams['weblink2'];

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$courseClass = new Course($activationParams);
$course = $courseClass->load($courseId);
echo $course;
