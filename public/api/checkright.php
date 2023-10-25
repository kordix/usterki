<?php


// session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

require('../cred.php');
$dbh = new PDO("mysql:host=$hostname;dbname=$dbname;charset=UTF8", $user, $pass);

$id = $_SESSION['id'];
$projectid = $_GET['id'];

$query = "select distinct p.* from projects p
join rights r on p.id = r.project_id
where r.user_id = $id and r.project_id = ?
";

$sth = $dbh->prepare($query);
$sth->execute([$projectid]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
