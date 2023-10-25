<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

require_once('../db.php');





$allowed = ['lokal','adres_admin','nr_admin','kontakt_klient','data_klient','uwagi_inwestora','SPW','typ_niezgodnosci','opis_niezgodnosci','termin_zgloszenia','klasyfikacja','komentarz_serwisu','status','komentarz_budowy','project_id','plan_id','x','y'];


//replace
$dane = json_decode(file_get_contents('php://input'));


print_r($dane->dane);

$params = [];
// $params['token'] = $dane->token;
$params['id'] = $dane->id;


$setStr = "";
foreach ($allowed as $key) {
    if (property_exists($dane->dane, $key) && $key != "id" && $key != "token") {
        $setStr .= "`".str_replace("`", "``", $key)."` = :".$key.",";
        $params[$key] = $dane->dane->$key;
    }
}
$setStr = rtrim($setStr, ",");


echo $setStr;



$query = "UPDATE usterki SET $setStr WHERE id = :id";
echo $query;
$sth = $dbh->prepare($query)->execute($params);


?>



