<?php
/**
 * Created by PhpStorm.
 * User: Юра
 * Date: 23.10.2020
 * Time: 18:19
 */

namespace core;


class Status
{
    public $model;
    public function main()
    {
        $this->model = new Model();
        $result =  $this->model->selectAll();
        echo json_encode($result);
    }
}