<?php

foreach (glob("system/vendor/*.php") as $filename)
{
    include $filename;
}

require('system/engine/HF_Core.php');

$core = new HF_Core();
$core->runMigrations();