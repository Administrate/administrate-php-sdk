<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $eventId Set this value in config.php
// $weblinkActivationParams Set this value in config.php
// $return type defined in client Class 'array' -> PHP array, 'obj' -> PHP Object and 'json' -> JSON
$filters = [
    'id' => $eventId
];
$fields = [];
$returnType = 'obj'; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON

$eventObj = new Event($weblinkActivationParams);
$event = $eventObj->load($filters, $fields, $returnType);

print_r($event);
