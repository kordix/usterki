<?php

//if($_SERVER['REQUEST_METHOD'] != 'POST') return;


require_once '../cred.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('mssql.charset', 'UTF-8');


try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname;charset=UTF8", $user, $pass);
    $query_run = $dbh->prepare("SET NAMES utf8");
    $query_run->execute();
} catch(PDOException $exception) {
    echo 'NOCONNECTION';
    return;
    //  echo "Connection error: " . $exception->getMessage();
}

// $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
