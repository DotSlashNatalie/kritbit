<?php

use \vendor\DB\DB;

echo "Creating history table...";
DB::query("CREATE TABLE histories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            output TEXT,
            jobs_id INTEGER,
            run_date DATETIME,
            time_taken INTEGER,
            result INTEGER
  );");

DB::query("INSERT INTO histories VALUES (null, 'THIS IS ONLY A TEST', 1, '2015-01-01', 10, 0)");