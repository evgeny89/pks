<?php

namespace App\Kernel;

class Request
{
    public ?string $method;
    public ?string $errors;
    protected ?array $query_params;
    protected ?array $files;

    const TYPES = ['application/json', 'image/jpeg'];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query_params = $this->parseQueryString($_REQUEST);
        $this->files = $this->prepareFiles($_FILES);
    }

    protected function parseQueryString(array $params): array
    {
        foreach ($params as &$param) {
            $param = htmlspecialchars($param);
        }

        return $params;
    }

    protected function prepareFiles(array $files): array
    {
        foreach ($files as $key => $file) {
            if (!in_array($file['type'], self::TYPES) || !$file['size']) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    public function all(): ?array
    {
        return $this->query_params;
    }

    public function allFiles(): ?array
    {
        return $this->files;
    }

    public function file(string $name): string
    {
        return trim(file_get_contents($this->files[$name]['tmp_name']), "\xEF\xBB\xBF");
    }

    public function __get(string $name)
    {
        return $this->query_params[$name] ?? null;
    }

    public function setError(string $key, string $value)
    {
        $this->errors[$key] = $value;
    }
}