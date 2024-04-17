<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}



if($_SERVER['REQUEST_METHOD'] != 'POST') {
    return;
}

require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$dane = json_decode(file_get_contents('php://input'));

$allowed = ['nazwa_projektu','adres'];

$ok = 1;

$pytajniki = '';

$kwerenda = '';
$kolumnystring = '';
$wartosci = [];


foreach ($allowed as $key) {
    if (property_exists($dane, $key) && $key != "id") {
        $kolumnystring .= '`'.$key.'`';
        $kolumnystring .= ',';
        $pytajniki .= '?';
        $pytajniki .= ',';
        array_push($wartosci, $dane->$key);
    }
}

if(!$ok) {
    return;
}


if ($ok) {
    $kolumnystring = substr($kolumnystring, 0, -1);
    $pytajniki = substr($pytajniki, 0, -1);

    $query = "INSERT INTO projects ($kolumnystring , created_at ) values ($pytajniki , NOW()) ";
    echo $query;
    $sth = $dbh->prepare($query);
    print_r($wartosci);
    $sth->execute($wartosci);
}


?>



