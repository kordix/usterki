<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


require('../db.php');

$id = $_SESSION['id'];

$query = "select distinct p.* from projects p
join rights r on p.id = r.project_id
where r.user_id = $id
";



$sth = $dbh->prepare($query);
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
