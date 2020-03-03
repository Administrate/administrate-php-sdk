<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Administrate\PhpSdk\Course;

require_once '../config.php';
require_once '../vendor/autoload.php';

$id = "Q291cnNlVGVtcGxhdGU6MTE2OTk0MQ==";
$courseClass = new Course();
$courses = $courseClass->loadAll(1, 5, "TGVhcm5pbmdDYXRlZ29yeTox", "safe");

echo "<pre>";
print_r($courses);
echo "</pre>";
