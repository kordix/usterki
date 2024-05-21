<?php
session_start();

if(!isset($_SESSION['zalogowany'])) {
   echo 'NIEZALOGOWANY';
   return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

$id = $_GET['id'];
$query = "select 1 as rowspan, (select created_at from logs where usterka_id = u.id order by id desc limit 1) as akcja,u.*,typy_niezgodnosci.description as typ_niezgodnosci_opis , u.termin_zgloszenia as termin_zgloszenia_opis , k.description as klasyfikacja_opis 
from usterki u 
left outer join typy_niezgodnosci on typy_niezgodnosci.code = u.typ_niezgodnosci
left outer join klasyfikacje k on k.code = u.klasyfikacja
where project_id = ? 
order by (SELECT MIN(created_at) FROM usterki AS ui WHERE ui.lokal = u.lokal) ASC

";



$sth = $dbh->prepare($query);
$sth->execute([$id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
