<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}



require($_SERVER['DOCUMENT_ROOT'] . '/db.php');;


$id = $_GET['id'];

$project_id = $_GET['projectid'];

$query = "update usterki set hidden = '', usterka_numer =  (SELECT IFNULL(MAX(usterka_numer) + 1, 1) FROM usterki u WHERE u.project_id = $project_id and hidden = 0 limit 1) where id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


