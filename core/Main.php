<?php
/**
 * Created by PhpStorm.
 * User: Юра
 * Date: 23.10.2020
 * Time: 20:17
 */

namespace core;

class Main
{
    public $model;
    public $data;

    public function main()
    {
        $this->data = explode("\r\n",mb_convert_encoding(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/upload/list.csv'),'UTF-8'));
        $this->model = new Model();
        $file = require $_SERVER['DOCUMENT_ROOT'].'/configs/config.php';
        if (!$this->checkFile($file['name'])) {
            $this->loadFile($file['link'],$file['name']);
            $this->uncompress($file['name'],$file['name_new']);
        }
        if ($this->checkFile($file['name_new'])) {
            $data = $this->changeData();
            $this->model->prepareTable($data);
            echo 'Файл загружен и готов';
        }
    }

    public function checkFile($file)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$file)) {
            return true;
        } else {
            return false;
        }
    }

    public function loadFile($link,$path)
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        copy($link, $_SERVER['DOCUMENT_ROOT'].$path,stream_context_create($arrContextOptions));
    }

    public function uncompress($name,$name_new)
    {
        $path = $_SERVER['DOCUMENT_ROOT'].$name;
        $bz = bzopen($path, "r") or die("Невозможно открыть $path");

        $decompressed_file = '';
        while (!feof($bz)) {
            $decompressed_file .= bzread($bz, 4096);
        }

        bzclose($bz);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].$name_new,$decompressed_file);
    }

    public function changeData()
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            $elem = explode(',',$value);
            $result[] = [
                'series' => $elem[0],
                'number' => $elem[1]
            ];
        }
        return $result;
    }
}