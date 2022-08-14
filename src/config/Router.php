<?php

namespace Config;

class Router
{
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';
    private $handlers = array();


  private $notFoundHandler;


    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }


    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }


    public function delete(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_DELETE, $path, $handler);
    }

    public function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
        'path' => $path,
        'method' => $method,
        'handler' => $handler
        ];
    }

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function run(): void
    {
        $requestedUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestedUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;

        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $requestPath &&
            $method === $handler['method']) {
                $callback = $handler['handler'];
              echo "callback  $callback";
            }
        }

        if (!$callback) {
            header("HTTP/1.0 404 Not Found!");
            if (!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }
        call_user_func_array($callback, [
        array_merge($_GET, $_POST)
        ]);
    }
}
