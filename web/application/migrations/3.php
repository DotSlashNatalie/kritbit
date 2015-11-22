<?php

use \vendor\DB\DB;

echo "Creating history table...";
DB::query("CREATE TABLE histories (
            id INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
            output TEXT,
            jobs_id INTEGER,
            run_date DATETIME,
            time_taken DECIMAL(10,5),
            result INTEGER,
            nonce VARCHAR(255)
  );");

//DB::query("INSERT INTO histories VALUES (null, 'THIS IS ONLY A TEST', 1, '2015-01-01', 10, 0, 'ABC')");