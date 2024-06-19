<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}



require($_SERVER['DOCUMENT_ROOT'] . '/db.php');;


$id = $_GET['id'];
$query = "delete from usterki where id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


$query = "delete from logs where usterka_id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


$query = "delete from files where usterka_id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);




