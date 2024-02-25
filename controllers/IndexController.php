<?php

class IndexController
{
    public function __construct()
    {
        $this->callIndexView();
    }

    public function callIndexView(){
        include './view/index.html';
    }
}