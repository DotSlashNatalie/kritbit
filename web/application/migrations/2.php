<?php

use \vendor\DB\DB;

echo "Creating job table..." . PHP_EOL;
DB::query("CREATE TABLE jobs (
          id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
          jobName VARCHAR(255),
          runType VARCHAR(255),
          runScript TEXT,
          cron VARCHAR(255),
          failScript TEXT,
          last_run DATETIME,
          last_result INTEGER,
          hash VARCHAR(255),
          sharedkey VARCHAR(32),
          view_private TINYINT(1),
          user_id INTEGER,
          comments TEXT,
          force_run TINYINT(1)
);");

//DB::query("INSERT INTO jobs VALUES (null, 'test', 1, 'TESTING', '*/5 * * * *', 'TESTING', '2015-01-01', 0, '123', '123', 0, 1, 'TEST COMMENT', 0)");
//DB::query("INSERT INTO jobs VALUES (null, 'test2', 1, 'TESTING', '*/5 * * * *', 'TESTING', '2015-01-01', 0, '321', '321', 1, 1, 'TEST COMMENT2', 0)");