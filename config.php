<?php
session_start();
$base = 'http://localhost:8000';

$db_name = 'devsbook';
$db_user = 'root';
$db_pass = 'Clarinha1408';
$db_host = 'localhost';

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);