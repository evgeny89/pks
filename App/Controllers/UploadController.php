<?php

namespace App\Controllers;

use App\Kernel\App;
use App\Kernel\View;
use App\Services\JsonParser;

class UploadController
{
     public function index(App $app): string
     {
         $parser = new JsonParser($app::$request->file('source'));
         return View::rawHtml($parser->handler());
     }
}