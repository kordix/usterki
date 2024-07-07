<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    // echo 'NIEZALOGOWANY';
    //return;
}


require($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR.'db.php');


// $id = $_SESSION['id'];

$projectid = $_GET['projectid'];

$query = "select header from headers where project_id = ?";


$sth = $dbh->prepare($query);
$sth->execute([$projectid]);


$headers = [];
while ($row = $sth->fetch(PDO::FETCH_NUM)) {
    $headers[] = $row[0];
}


// $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($headers);
