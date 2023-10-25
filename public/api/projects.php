<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


require('../db.php');

$query = "select * from projects

";



$sth = $dbh->prepare($query);
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
