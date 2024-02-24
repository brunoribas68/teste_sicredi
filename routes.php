<?php

use Controllers\Request;
use Controllers\Router;

$router = new Router(new Request);

$router->get('/', function($request) {
    return <<<HTML
  <h1>HOME</h1>
HTML;
});


$router->get('/profile', function($request) {
    return <<<HTML
  <h1>Profile</h1>
HTML;
});

$router->post('/data', function($request) {

    return json_encode($request->getBody());
});