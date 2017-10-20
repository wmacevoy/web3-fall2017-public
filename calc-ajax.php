<?php

$x = $_POST["x"];
$y = $_POST["y"];

$z  = floatval($x) + floatval($y);
$status = "ok";

header('Content-type: application/json');
echo json_encode( array ( 'z' => $z, 'status' => $status ) );
