<?php
// Source: http://stackoverflow.com/a/8232171/195722
$key = 'MyKey';
$text = 'test';
$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB);
$encoded = base64_encode($output);
echo $encoded;