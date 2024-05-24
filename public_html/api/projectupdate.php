<?php
echo 'fasdfdsafds';

session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$allowed = ['nazwa_projektu','adres','numer_referencyjny','inwestor','generalny_wykonawca','data_start','data_end','project_manager','handlowiec','przedstawiciel','spw','created_at','updated_at'];


//replace
$dane = json_decode(file_get_contents('php://input'));

echo 'fasfafdsaf';
print_r($dane->dane);



$params = [];
// $params['token'] = $dane->token;
$params['id'] = $dane->id;


$setStr = "";
foreach ($allowed as $key) {
    if (property_exists($dane->dane, $key) && $key != "id" && $key != "token") {
        $setStr .= "`" . str_replace("`", "``", $key) . "` = :" . $key . ",";
        $params[$key] = $dane->dane->$key;
    }
}
$setStr = rtrim($setStr, ",");




echo $setStr;
$query = "UPDATE projects SET $setStr WHERE id = :id";
echo $query;
$sth = $dbh->prepare($query)->execute($params);



?>



