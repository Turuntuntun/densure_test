<?php

namespace core;

class Router
{
    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        $arr = require_once $_SERVER['DOCUMENT_ROOT'].'/configs/routes.php';
        foreach ($arr as $key => $val){
            $this->add($key,$val);
        }
    }

    public function add($route,$params)
    {
        $this-> routes[$route] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'],'/');
        $newUrl = explode('?',$url)[0];
        foreach ($this->routes as $route => $params){
            if ($newUrl == $route) {
                $this -> params = 'core\\'.$params;
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $controller = new $this->params();
            $controller->main();
        }
    }
}