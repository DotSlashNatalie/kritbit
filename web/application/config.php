<?php
date_default_timezone_set("America/Chicago");
$config["ADMINS"] = array("adamsna@datanethost.net");

$config["DATABASE_TYPE"] = "SQLITE";
$config["DATABASE_FILE"] = "kritbot.sqlite3";

$config["GOOGLE_OAUTH_ID"] = "";
$config["GOOGLE_OAUTH_SECRET"] = "";

$config["ACCEPTED_IPS"] = ["192.168.128.36", "127.0.0.1", "::1"];

return $config;