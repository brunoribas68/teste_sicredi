<?php

function dd($variable)
{
    echo "<pre>";

    var_dump($variable);

    echo "</pre>";

    die;
}

function not_found(){

    header("HTTP/1.1 404 Not Found");
    return exit(json_encode(['response' => 'Rota nao encontrada']));
}