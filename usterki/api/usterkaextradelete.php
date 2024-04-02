<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}



require($_SERVER['DOCUMENT_ROOT'] . '/usterki/db.php');;


$id = $_GET['id'];
$query = "delete from extras where id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


