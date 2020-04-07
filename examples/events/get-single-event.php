<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $eventId Set this value in config.php
// $weblinkActivationParams Set this value in config.php

$eventObj = new Event($weblinkActivationParams);
$event = $eventObj->load($eventId);

echo $event;
