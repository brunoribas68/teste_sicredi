<?php

namespace Controller;

class Router{

    protected $routesExcepts = [
        'CurlController',
        'SignController',
        'RouterController'
    ];

    public function __construct(){
        $uri = $this->fixRouteName();
        $this->routeToController($uri);
    }

    private function routeToController(string $uri = "index"){
        if ($this->controllerExistOrPermit($uri)){
            require "{$uri}Controller.php";
            $classe = "{$uri}Controller";
            return new $classe();
        }

        return not_found();
    }

    private function fixRouteName(){

        $uri = parse_url($_SERVER["REQUEST_URI"])['path'];
        $uri = str_replace("/", "", $uri);
        $uri = ucwords($uri);

        return $uri != "" ? $uri : "Index";
    }

    private function controllerExistOrPermit($uri){
        return file_exists(__DIR__."/{$uri}Controller.php") ||
            array_key_exists("{$uri}Controller.php", $this->routesExcepts);
    }

}