<?php

require 'src/config.php';

$cipher = new Cipher();
$key=$_GET["key"];
$message=$_GET["message"];
    
echo "<html><br><pre>\n";
$encrypted=$cipher->encrypt($key,$message);
$decrypted=$cipher->decrypt($key,$encrypted);

echo "<br>message=" . $message;
echo "<br>encrypted=" . $encrypted;
echo "<br>decrypted=" . $decrypted;

