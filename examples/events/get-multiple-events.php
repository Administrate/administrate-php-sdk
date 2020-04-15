<?php

header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\Event;

// $weblinkActivationParams Set this value in config.php
$fields = [];
$returnType = 3; //1 for default PHP Array, 2 for PHP statndard object, 3 for JSON
$paging = ['page' => 1, 'perPage' => 25];
$sorting = ['field' => 'title', 'direction' => 'asc'];
$filters = [];

$eventObj = new Event($weblinkActivationParams);
$events = $eventObj->loadAll($filters, $paging, $sorting, $fields, $returnType);

print_r($events);
