<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

$userid = $_GET['userid'];
$projectid = $_GET['projectid'];

require($_SERVER['DOCUMENT_ROOT'] . '/usterki/db.php');


$query = "INSERT INTO rights (user_id,project_id) values (?,?)";

$sth = $dbh->prepare($query);
$sth->execute([$userid,$projectid]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
