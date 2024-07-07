<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


$id = $_GET['id'];




require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

$query = "select count(*) as ile from logs where usterka_id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


$rows = $sth->fetchAll(PDO::FETCH_ASSOC);


if($rows[0]['ile'] > 1) {
   echo 'Z USTERKĄ SĄ POWIĄZANE AKCJE NIE MOŻNA USUNĄĆ';
} else { 
    $query = "delete from usterki where id = ?";
    $sth = $dbh->prepare($query);
    $sth->execute([$id]);


    $query = "delete from logs where usterka_id = ?";
    $sth = $dbh->prepare($query);
    $sth->execute([$id]);


    $query = "delete from files where usterka_id = ?";
    $sth = $dbh->prepare($query);
    $sth->execute([$id]);
}
