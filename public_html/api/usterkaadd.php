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

$allowed = ['nr_oferty','usterka_id','lokal','adres_admin','nr_admin','kontakt_klient','data_klient','uwagi_inwestora','typ_niezgodnosci','opis_niezgodnosci','termin_zgloszenia','klasyfikacja','komentarz_serwisu','status','komentarz_budowy','project_id','plan_id','x','y','hidden'];


$pytajniki = '';

$kwerenda = '';
$kolumnystring = '';
$wartosci = [];


foreach ($allowed as $key) {
    if (property_exists($dane, $key) && $key != "id") {
        $kolumnystring .= '`' . $key . '`';
        $kolumnystring .= ',';
        $pytajniki .= '?';
        $pytajniki .= ',';
        array_push($wartosci, $dane->$key);
    }
}

$kolumnystring = substr($kolumnystring, 0, -1);
$pytajniki = substr($pytajniki, 0, -1);

print_r($dane);

$numerquery = "(SELECT IFNULL(MAX(usterka_numer) + 1, 1) FROM usterki u WHERE u.project_id = $dane->project_id and hidden = 0 limit 1)";

if($dane->hidden == 1){
    $numerquery = "(SELECT IFNULL(MAX(usterka_numer) + 1, 1) FROM usterki u WHERE u.project_id = $dane->project_id and hidden = 1 limit 1)";
}


$query = "INSERT INTO usterki ($kolumnystring , created_at , usterka_numer ) values ($pytajniki , NOW(), $numerquery ) ";
$sth = $dbh->prepare($query);
print_r($wartosci);
$sth->execute($wartosci);


$usterkaid = $dbh->lastInsertId();
$userid = $_SESSION['id'];
$query = "INSERT INTO logs (user_id,`action`,usterka_id,created_at) values ($userid,'add', $usterkaid, NOW())";
$sth = $dbh->prepare($query);
$sth->execute();





?>



