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

$query = "delete from rights where id = ?";

$sth = $dbh->prepare($query);
$sth->execute([$id]);

