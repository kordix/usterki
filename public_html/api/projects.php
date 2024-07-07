<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$id = $_SESSION['id'];

$query = "select p.*, (SELECT count(*) from usterki where usterki.project_id = p.id) as ilerazem, (SELECT count(*) from usterki where usterki.project_id = p.id and status = 'Zgłoszona' and hidden = '') as ile  from projects p";
if($_SESSION['group'] != 'admin') {
    $query = "select distinct p.* from projects p
    join rights r on p.id = r.project_id
    where r.user_id = $id
    ";
}



$sth = $dbh->prepare($query);
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
