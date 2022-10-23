<?php

namespace App\Services;

use App\Kernel\View;

class JsonParser
{
    protected array $dataFile;

    public function __construct(string $file)
    {
        $this->dataFile = json_decode($file, true);
    }

    public function handler(): string
    {
        return $this->run($this->dataFile);
    }

    protected function run(array $data): string
    {
        $res = '';

        if (isset($data['children'])) {
            foreach ($data['children'] as $children) {
                $res .= $this->run($children);
            }
        }

        $method = $this->getMethodName($data['type']);

        return $this->{$method}($data, $res);
    }

    protected function getMethodName(string $name): string
    {
        return "get".ucwords($name);
    }

    protected function getContainer(array $container, string $payload): string
    {
        $data = [
            'children' => $payload
        ];

        return View::partials($this->currentView($container['type']), $data);
    }

    protected function getBlock(array $block, string $payload): string
    {
        $data = [
            'children' => $payload,
            'parameters' => $this->buildParameters($block['parameters'])
        ];

        return View::partials($this->currentView($block['type']), $data);
    }

    protected function getText(array $text): string
    {
        $data = [
            'text' => $text['payload']['text'],
            'parameters' => $this->buildParameters($text['parameters']),
        ];

        return View::partials($this->currentView($text['type']), $data);
    }

    protected function getButton(array $button): string
    {
        $data = [
            'link' => $button['payload']['link']['payload'],
            'text' => $button['payload']['text'],
            'parameters' => $this->buildParameters($button['parameters']),
        ];

        return View::partials($this->currentView($button['type']), $data);
    }

    protected function getImage(array $image): string
    {
        $data = [
            'url' => $image['payload']['image']['url'],
            'type' => $image['type'],
            'width' => $image['payload']['image']['meta']['width'],
            'height' => $image['payload']['image']['meta']['height'],
            'parameters' => $this->buildParameters($image['parameters']),
        ];

        return View::partials($this->currentView($image['type']), $data);
    }

    protected function currentView(string $name): string
    {
        return "partials/{$name}";
    }

    protected function buildParameters(array $parameters): string
    {
        $param = 'style="';

        foreach ($parameters as $key => $value) {
            $param .= "{$this->keyToKebab($key)}:{$value};";
        }

        return $param.'"';
    }

    protected function keyToKebab(string $key): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
    }
}