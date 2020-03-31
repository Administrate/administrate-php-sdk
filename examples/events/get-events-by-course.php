<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $courseCode Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$eventObj = new Event();
$eventObj->setWeblinkparams($weblinkActivationParams);
$events = $eventObj->loadByCourseCode(1, 50, $courseCode);

echo $events;
