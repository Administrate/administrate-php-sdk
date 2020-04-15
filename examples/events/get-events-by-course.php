<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $courseCode Set this value in config.php
// $weblinkActivationParams Set this value in config.php

// $weblinkActivationParams Set this value in config.php
$fields = [];
$returnType = 3; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON
$paging = ['page' => 1, 'perPage' => 25];
$sorting = ['field' => 'title', 'direction' => 'asc'];
$filters = ['courseCode' => $courseCode];

$eventObj = new Event($weblinkActivationParams);
$events = $eventObj->loadByCourseCode($filters, $paging, $sorting, $fields, $returnType);

print_r($events);
