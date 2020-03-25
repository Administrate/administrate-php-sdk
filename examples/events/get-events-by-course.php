<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $courseCode Set this value in config.php
// $activationParams Set this value in config.php

$activationParams = $activationParams['weblink2'];

if ($_SESSION['portal_token']) {
    $activationParams['accessToken'] = $_SESSION['portal_token'];
}

$eventObj = new Event($activationParams);
$events = $eventObj->loadByCourseCode(1, 50, $courseCode);
echo($events);
