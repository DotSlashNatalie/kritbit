<?php

use \application\models\Sessions;

abstract class base extends \system\engine\HF_Controller {

    protected $session = null;
    protected $sessionData = null;
    public function isLoggedIn() {
        if (!$this->sessionData && !isset($this->sessionData->userId)) {
            header("Location: /login");
            return false;
        } else {
            return true;
        }
    }
    public function __construct($config, $core, $tpl)
    {
        parent::__construct($config, $core, $tpl);

        if ($this->config["DATABASE_TYPE"] == "SQLITE") {
            $this->pdo = new PDO("sqlite:kritbot.sqlite3");
            \vendor\DB\DB::$c = $this->pdo;
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
            \vendor\DB\DB::$c = $this->pdo;
        }

        if (isset($_COOKIE["session"])) {
            $validSession = Sessions::getByField("sessionid", $_COOKIE["session"]);
            if ($validSession) {
                try {
                    $this->session = $validSession[0];
                    $this->sessionData = json_decode($this->session->data);
                    if ($this->sessionData == null) {
                        return;
                    }
                    $this->user = \application\models\Users::getByField("id", $this->sessionData->userId)[0];
                } catch (\Exception $e) {
                    setcookie("session", "", time() - 3600);
                    header("Location: /login");
                }
            } else {
                setcookie("session", "", time() - 3600);
                header("Location: /login");
            }
        } else {
            $bool = true;
            $bytes = openssl_random_pseudo_bytes (10, $bool);
            $sessionId = bin2hex($bytes);
            $this->session = new Sessions();
            $this->session->ip = $_SERVER["REMOTE_ADDR"];
            $this->session->userAgent = $_SERVER["HTTP_USER_AGENT"];
            $this->session->sessionid = $sessionId;
            $this->session->save();
            setcookie("session", $sessionId, 2147483647);
        }

    }
}