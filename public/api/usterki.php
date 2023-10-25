<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}


require('../db.php');
$id = $_GET['id'];
$query = "select u.*,typy_niezgodnosci.description as typ_niezgodnosci_opis , u.termin_zgloszenia as termin_zgloszenia_opis , k.description as klasyfikacja_opis 
from usterki u 
left outer join typy_niezgodnosci on typy_niezgodnosci.code = u.typ_niezgodnosci
left outer join klasyfikacje k on k.code = u.klasyfikacja
where project_id = ? order by id asc";



$sth = $dbh->prepare($query);
$sth->execute([$id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
