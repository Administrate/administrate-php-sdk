<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Administrate\PhpSdk\Course;

require_once '../config.php';
require_once '../vendor/autoload.php';

echo "<pre>";
$id = "Q291cnNlVGVtcGxhdGU6MTE2OTk0MQ==";
$courseClass = new Course();
$course = $courseClass->load($id);
print_r($course);
echo "</pre>";
