<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $weblinkActivationParams Set this value in config.php

$eventObj = new Event();
$eventObj->setWeblinkParams($weblinkActivationParams);
$events = $eventObj->loadAll();

echo $events;
