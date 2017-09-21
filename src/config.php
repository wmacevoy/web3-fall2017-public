<?php
require_once 'psr4.php';

$user = new model\User();

$db = new PDO('mysql:host=mysql;dbname=starting;charset=utf8mb4', 
              'user', 'phnz.jcnf.xmmd.nafp.xwna.rwfa.hfzn.cjpn');

session_start();


