<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /usterki/logowanie.php');
}

if($_SESSION['group'] == 'admin'){
    
}else{
    return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/usterki/db.php');


$query = "SELECT id,login,`group` from users";
$sth = $dbh->prepare($query);

$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);
