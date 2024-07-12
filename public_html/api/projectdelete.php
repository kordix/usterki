<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


if($_SESSION['group'] != 'admin') {
    echo 'BRAK UPRAWNIEŃ';
    return;
}




require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$id = $_GET['id'];

$query = "delete from projects where id = ?";

echo $query;
$sth = $dbh->prepare($query);
$sth->execute([$id]);

$query = "delete from usterki where project_id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


