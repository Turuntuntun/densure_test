<?php
/**
 * Created by PhpStorm.
 * User: Юра
 * Date: 18.10.2020
 * Time: 23:22
 */

namespace core;

use PDO;
class Model
{
    private $connect;

    public function __construct()
    {
        $db = require $_SERVER['DOCUMENT_ROOT'] . '/configs/db.php';
        $this->connect = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . '', $db['user'], $db['password']);
    }

    public function selectAll()
    {
        $sth = $this->connect->prepare("SELECT * FROM `passports` ");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function selectPasport($series,$pasport)
    {
        $sth = $this->connect->prepare("SELECT * FROM `passports` WHERE `series`=:series AND `number`=:number ");
        $sth->execute(array('series'=>$series,'number'=>$pasport));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    private function fillData($data)
    {
        $sth = $this->connect->prepare("INSERT INTO `passports` SET `series` = :series, `number` =:number");
        foreach ($data as $key => $value) {
            $sth->execute($value);
        }
    }

    private function tableExists($table)
    {
        try {
            $sth = $this->connect->prepare("SELECT 1 FROM $table LIMIT 1");
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return FALSE;
        }

        return $result !== FALSE;
    }

    private function createTable()
    {
        $table = $this->connect->prepare("CREATE TABLE `passports` ( `id` INT NOT NULL AUTO_INCREMENT , `series` VARCHAR(32) NOT NULL , `number` VARCHAR(32) NOT NULL , PRIMARY KEY (`id`))");
        $table->execute();
    }

    public function prepareTable($data)
    {
        $check = $this->tableExists('`passports`');
        if (!$check) {
            $this->createTable();
            $this->fillData($data);
        }
    }
}