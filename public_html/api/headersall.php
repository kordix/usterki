<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /logowanie.php');
}

if($_SESSION['group'] == 'admin'){
    
}else{
    return;
}


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$query = "select * from headers";
$sth = $dbh->prepare($query);

$sth->execute();

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);
