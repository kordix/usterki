<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /login.php');
}


require('../db.php');


$id = $_SESSION['id'];

$query = "SELECT id,login,`group` from users where id = $id";
$sth = $dbh->prepare($query);

$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows[0]);







?>