<?php

class Router
{

    private array $handlers = array();
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';

    private  $notFoundHandler;


    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
        // echo 'came here  ' ;


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
        // var_dump($this->handlers);

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
            // var_dump($handler);
            // echo ($handler['path']   == $requestPath)&&($handler['method']   == $method);
            if ($handler['path'] === $requestPath && $method === $handler['method']) {
                $callback = $handler['handler'];


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


