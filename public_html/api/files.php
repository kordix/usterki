<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
   // echo 'NIEZALOGOWANY';
    //return;
}


require($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR.'db.php');


// $id = $_SESSION['id'];

$projectid = $_GET['projectid'];

$query = "select f.* from files f
join usterki u on f.usterka_id = u.id
where u.project_id = ?";


$sth = $dbh->prepare($query);
$sth->execute([$projectid]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
