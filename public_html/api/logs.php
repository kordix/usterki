<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

$projectid = $_GET['projectid'];

require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

$query = "select l.user_id, l.usterka_id, l.action, l.kolumna,l.created_at, users.login from logs l
join usterki u on l.usterka_id = u.id
join users on l.user_id = users.id
where u.project_id = ?
"
;

$sth = $dbh->prepare($query);
$sth->execute([$projectid]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
