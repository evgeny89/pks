<?php

use App\Controllers\PageController;
use App\Controllers\UploadController;
use App\Kernel\Router;

Router::get("/", [PageController::class, "home"]);
Router::get("upload", [PageController::class, "upload"]);

Router::post("upload", [UploadController::class, "index"]);
