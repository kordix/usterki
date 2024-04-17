<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

$query = "select r.*,u.login from rights r
join users u on u.id = r.user_id";

$sth = $dbh->prepare($query);
$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
