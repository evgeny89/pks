<?php

use App\Kernel\App;

spl_autoload_register(function ($class_name) {
    $filename = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }
});

$app = App::getInstance();

echo $app::callController();
