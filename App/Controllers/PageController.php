<?php

namespace App\Controllers;

use App\Kernel\View;

class PageController
{
    public function home(): string
    {
        return View::view("home", ["name" =>"Evgeny"]);
    }

    public function upload(): string
    {
        return View::view("upload");
    }
}