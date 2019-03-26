<?php

declare(strict_types=1);

define('TEST_ROOT_PATH', __DIR__);
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__DIR__) . '/src');
date_default_timezone_set('UTC');

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader('class_exists');