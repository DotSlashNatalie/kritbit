<?php

abstract class base extends HF_Controller {

    public function __construct($config, $core, $tpl)
    {
        parent::__construct($config, $core, $tpl);

        if ($this->config["DATABASETYPE"] == "SQLITE") {
            $this->pdo = new PDO("sqlite:kritbot.sqlite3");
            DB::$c = $this->pdo;
        } else {
            $this->pdo = new PDO(
                "mysql:dbname={$this->config['MYSQL_DBNAME']};host={$this->config['MYSQL_HOST']}",
                $this->config['MYSQL_USER'],
                $this->config['MYSQL_PASS'],
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
            DB::$c = $this->pdo;
        }
    }
}