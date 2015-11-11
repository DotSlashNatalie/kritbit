<?php

foreach (glob("system/vendor/*.php") as $filename)
{
    include $filename;
}

require('system/engine/core.php');

$core = new HF_Core();
$core->run();