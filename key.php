<?php

if (isset($_GET["bits"])) {
   $bits = $_GET["bits"];
} else {
   $bits = 128;
}

$bytes = ceil($bits/8);

$fd = fopen("/dev/urandom","rb");
$data = fread($fd,$bytes);
fclose($fd);

echo "<br>" . bin2hex($data) . " &mdash; (" . ($bytes*8) . " bits)</br>";

