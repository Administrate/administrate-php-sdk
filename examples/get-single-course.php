<?php

use Administrate\PhpSdk\Course;

require_once 'config.php';
require_once '../vendor/autoload.php';

echo "<pre>";
// $courseId defined in config.php
$courseClass = new Course();
$course = $courseClass->load($courseId);
print_r($course);
echo "</pre>";
