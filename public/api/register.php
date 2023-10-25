<?php
if($_SERVER['REQUEST_METHOD'] != 'POST') return;


require_once('../db.php');

$dane = json_decode(file_get_contents('php://input'));

$login = $dane->login;
$password = password_hash($dane->password , PASSWORD_DEFAULT);



$query = "INSERT INTO users (login,password) VALUES (?,?);";





$sth = $dbh->prepare($query);
$sth->execute([$login,$password]);

?>



