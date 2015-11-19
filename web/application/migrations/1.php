<?php

use \vendor\DB\DB;

echo "Creating session table..." . PHP_EOL;
DB::query("CREATE TABLE sessions (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          sessionid VARCHAR(255),
          ip VARCHAR(255),
          userAgent VARCHAR(255),
          data TEXT
)");

echo "Creating users table..." . PHP_EOL;
DB::query("CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255)
)");

echo "Adding user " . PHP_EOL;
DB::insert("users", ["email" => "adamsna@datanethost.net"]);