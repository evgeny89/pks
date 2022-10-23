<?php

namespace App\Kernel;

use Exception;

class App
{
    public static ?Request $request = null;
    public static ?Router $router = null;
    private static ?App $instance = null;

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = self::init();
        }

        return self::$instance;
    }

    private static function init(): App
    {
        self::$request = new Request();
        self::$router = new Router();
        self::includes();

        return new self();
    }

    protected static function getController(): ?array
    {
        return self::$router->getController(self::$request->method);
    }

    public static function callController()
    {
        [$controller, $action] = self::getController();

        if ($controller && $action) {
            $instance = new $controller();

            return $instance->{$action}(self::$instance);
        }
        return null;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @throws Exception
     */
    public function __unserialize()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    protected static function includes()
    {
        require_once dirname(__DIR__) . "/config/routes.php";
    }
}