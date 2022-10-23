<?php

namespace App\Kernel;

class Router
{
    protected array $params = [];
    protected static ?string $uri = null;
    protected static array $routes = [];

    public function __construct()
    {
        $this->params = $this->parseRequest($_SERVER['REQUEST_URI']);
    }

    protected function parseRequest(string $uri)
    {
        self::$uri = self::cutUri($uri);
        $result = explode("/", self::$uri);
        return array_diff($result, ['']);
    }

    public function all(): array
    {
        return $this->params;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    protected static function cutUri(string $uri)
    {
        $res = stristr($uri, '?', true);
        return $res ?: $uri;
    }

    public static function __callStatic(string $name, array $params)
    {
        [$path, $controller] = $params;

        $value = [
            'path' => $path,
            'controller' => $controller
        ];

        self::$routes[strtoupper($name)][] = $value;
    }

    public static function getController(string $method): ?array
    {
        $result = "";
        $route_index = -1;

        foreach (self::$routes[$method] as $key => $route) {
            $path = $route['path'];
            $res = stripos(self::$uri, $path);

            if ($res !== false && (strlen($path) > strlen($result))) {
                $result = $res;
                $route_index = $key;
            }
        }

        return $route_index !== -1 ? self::$routes[$method][$route_index]['controller'] : null;
    }
}