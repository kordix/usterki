<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


if($_SERVER['REQUEST_METHOD'] != 'POST') return;


require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


$dane = json_decode(file_get_contents('php://input'));

$login = $dane->login;

$group = $dane->group;

// $password = password_hash(strtolower($dane->password) , PASSWORD_DEFAULT);



$query = "INSERT INTO users (login,`group`) VALUES (?,?);";





$sth = $dbh->prepare($query);
if($sth->execute([$login,$group])){
    echo 'ZAREJESTROWANO UŻYTKOWNIKA';
} else {
    echo 'COŚ POSZŁO NIE TAK';
}

?>



