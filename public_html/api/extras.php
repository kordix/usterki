<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

$id = $_GET['id'];
$query = "select '' as editable,extras.* from extras where project_id = ?";



$sth = $dbh->prepare($query);
$sth->execute([$id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
