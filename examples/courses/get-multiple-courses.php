<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Course;

// $categoriesIds Set this value in config.php
// $activationParams Set this value in config.php

$activationParams = $activationParams['weblink2'];

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$courseClass = new Course($activationParams);
$allCourses = $courseClass->loadAll(1, 5, $categoriesIds[0], "safe");
echo($allCourses);
