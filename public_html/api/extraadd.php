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

$allowed = ['nr_zlecenia','nr_pozycji','usterka_id','lokal','adres_admin','nr_admin','kontakt_klient','data_klient','uwagi_inwestora','typ_niezgodnosci','opis_niezgodnosci','termin_zgloszenia','klasyfikacja','komentarz_serwisu','status','komentarz_budowy','project_id','plan_id','x','y'];

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


$numerquery = "(SELECT IFNULL(MAX(e.extra_numer) + 1, 2) FROM extras e WHERE e.usterka_id = $dane->usterka_id limit 1)";


$query = "INSERT INTO extras ($kolumnystring , created_at , extra_numer ) values ($pytajniki , NOW() , $numerquery) ";
echo $query;
$sth = $dbh->prepare($query);
print_r($wartosci);
$sth->execute($wartosci);


$usterkaid = $dbh->lastInsertId();
$userid = $_SESSION['id'];
$query = "INSERT INTO logs (user_id,`action`,usterka_id,created_at) values ($userid,'addextra', $usterkaid, NOW())";
$sth = $dbh->prepare($query);
$sth->execute();





?>



