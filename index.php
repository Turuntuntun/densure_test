<?php
/**
 * Created by PhpStorm.
 * User: Юра
 * Date: 18.10.2020
 * Time: 18:49
 */

use core\Router;
ini_set('max_execution_time', 999999999);
ini_set('memory_limit', '10000M');
spl_autoload_register(function ($class_name) {
    $path = str_replace('\\','/',$class_name.'.php');
    if (file_exists($path)){
        require_once $path;
    }
});

session_start();
$class = new Router();
$class -> run();