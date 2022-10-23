<?php

namespace App\Kernel;

class View
{
    protected static string $layout = "layout";
    protected static string $marker = "==content==";

    public static function view(string $name, array $data = [])
    {
        $page = self::getPage($name);

        return self::fillData($page, $data);
    }

    public static function partials(string $name, array $data = [])
    {
        $template = self::getView($name);
        return self::fillData($template, $data);
    }

    protected static function getPage(string $name)
    {
        $layout = self::getLayout();
        $template = self::getView($name);

        return str_replace(self::$marker, $template, $layout);
    }

    public static function rawHtml(string $data)
    {
        $layout = self::getLayout();
        return str_replace(self::$marker, $data, $layout);
    }

    protected static function fillData(string $page, array $data)
    {
        foreach ($data as $key => $value) {
            $page = str_replace("{{ {$key} }}", $value, $page);
        }

        return $page;
    }

    protected static function getLayout(): string
    {
        return self::getView(self::$layout);
    }

    protected static function getView(string $name): string
    {
        return file_get_contents(dirname(__DIR__) . "/templates/{$name}.html");
    }
}