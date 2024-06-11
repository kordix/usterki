<?php

require_once($_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'db.php');


// $dane = new stdClass();
// $dane->category_id = '1';
// $dane->description = 'fdsa';
// $dane->filename = 'testnowe.txt';

$kwerenda='';
$kolumnystring = '';
$wartosci = [];


$allowed = ['usterka_id', 'description','filename','service'];

$pytajniki = '';

foreach ($allowed as $key) {
    if (property_exists($dane, $key) && $key != "id") {
        $kolumnystring .= '`'.$key.'`';
        $kolumnystring .= ',';
        $pytajniki .= '?';
        $pytajniki .= ',';
        array_push($wartosci, $dane->$key);
    }
}


    $kolumnystring = substr($kolumnystring, 0, -1);
    $pytajniki = substr($pytajniki, 0, -1);


    $query = "INSERT INTO files ($kolumnystring ) values ($pytajniki) ";
    $sth = $dbh->prepare($query);
    $sth->execute($wartosci);

?>



