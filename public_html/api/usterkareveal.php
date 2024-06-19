<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}



require($_SERVER['DOCUMENT_ROOT'] . '/db.php');;


$id = $_GET['id'];

$project_id = $_GET['projectid'];

$query1 = "SELECT IFNULL(MAX(usterka_numer) + 1, 1) as maxusterka FROM usterki u WHERE u.project_id = ? and hidden = 0 limit 1";
$sth = $dbh->prepare($query1);
$sth->execute([$project_id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$maxusterka = $rows[0]['maxusterka'];


$query = "update usterki set hidden = '', usterka_numer = ? , created_at = NOW() where id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$maxusterka,$id]);


