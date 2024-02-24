<?php

namespace Controller;

class Router{

    public function __construct()
    {
        $uri = parse_url($_SERVER["REQUEST_URI"])['path'];
        $uri = str_replace("/", "", $uri);
        $this->routeToController($uri != "" ? $uri : "document");
    }

    private function routeToController(string $uri = "document")
    {
        $uri = ucwords($uri);
        if (file_exists(__DIR__."/{$uri}Controller.php")){
            require "{$uri}Controller.php";
            return die;
        }

        return not_found();
    }

}