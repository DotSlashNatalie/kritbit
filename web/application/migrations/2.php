<?php

use \vendor\DB\DB;

echo "Creating job table..." . PHP_EOL;
DB::query("CREATE TABLE jobs (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          jobName VARCHAR(255),
          runType VARCHAR(255),
          runScript TEXT,
          cron VARCHAR(255),
          failScript TEXT,
          last_run DATETIME,
          last_result INTEGER,
          api_key VARCHAR(255),
          view_private INTEGER,
          user_id INTEGER
);");

DB::query("INSERT INTO jobs VALUES (null, 'test', 1, 'TESTING', '*/5 * * *', 'TESTING', '2015-01-01', 0, '', 0, 1)");
DB::query("INSERT INTO jobs VALUES (null, 'test2', 1, 'TESTING', '*/5 * * *', 'TESTING', '2015-01-01', 0, '', 1, 1)");