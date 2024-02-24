<?php

namespace Controller;

class Router{

    protected $excepts = [
        'CurlController'
    ];

    public function __construct()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"])['path'];
        $uri = str_replace("/", "", $uri);
        $this->routeToController($uri != "" ? $uri : "document");
    }

    private function routeToController(string $uri = "document")
    {
        $uri = ucwords($uri);
        if (file_exists(__DIR__."/{$uri}Controller.php") || array_key_exists("{$uri}Controller.php", $this->excepts)){
            require "{$uri}Controller.php";
            $classe = "{$uri}Controller";
            return new $classe();

        }

        return not_found();
    }

}