<?php
/**
 * Created by PhpStorm.
 * User: Юра
 * Date: 23.10.2020
 * Time: 18:19
 */

namespace core;


class Search
{
    public $model;
    public function main()
    {
        $this->model = new Model();
        $series = $_GET['series'];
        $number = $_GET['number'];
        $result = $this->model->selectPasport($series,$number);
        if (!empty($result)) {
            echo json_encode(['find'=>true]);
        } else {
            echo json_encode(['find'=>false]);
        }
    }
}