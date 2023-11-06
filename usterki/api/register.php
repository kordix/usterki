<?php


session_start();

if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}


if($_SERVER['REQUEST_METHOD'] != 'POST') return;


require($_SERVER['DOCUMENT_ROOT'] . '/usterki/db.php');


$dane = json_decode(file_get_contents('php://input'));

$login = $dane->login;
$password = password_hash($dane->password , PASSWORD_DEFAULT);



$query = "INSERT INTO users (login,password,`group`) VALUES (?,?,'klient');";





$sth = $dbh->prepare($query);
$sth->execute([$login,$password]);

?>



