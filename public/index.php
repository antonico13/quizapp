<?php

use Framework\Application;
use Framework\Http\Request;

$baseDir = dirname(__DIR__);

require $baseDir.'/vendor/autoload.php';

$container = require $baseDir.'/config/services.php';

$application = Application::create($container);

$request = Request::createFromGlobals();
$response = $application->handle($request);
$response->send();
