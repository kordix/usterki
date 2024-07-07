<?php

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    return;
}
session_start();

require($_SERVER['DOCUMENT_ROOT'] . '/db.php');


// $dbh = null;

$dane = json_decode(file_get_contents('php://input'));

$login = $dane->login;
$password = $dane->password;

//REPLACE
$query_run = $dbh->prepare("SELECT * FROM users where login = ?");
$query_run->execute([$login]);

$rows = $query_run->fetchAll(PDO::FETCH_ASSOC);

#echo $password;

// echo password_verify('$2y$10$qN6UQsJXjT04OKR7Pp436eJDyDzW2d6eYE2oF.XkR8OEW9T1iT/0a', 'zasdfgh');

if(count($rows) > 0) {
    if(strlen($rows[0]['password']) == 0){
        // echo 'WIDZI PUSTE HASŁO';

        $password = password_hash(strtolower($dane->password) , PASSWORD_DEFAULT);

        $query = "update users set password = ? where login = ?";

        $sth = $dbh->prepare($query);
        $sth->execute([$password ,$login]);

        echo 'USTAWIONO HASŁO - KLIKNIJ JESZCZE RAZ ZALOGUJ';


    } else if (password_verify(strtolower($dane->password), $rows[0]['password'])) {
        $_SESSION['zalogowany'] = true;
        $_SESSION['id'] = $rows[0]['id'];
        $_SESSION['group'] = $rows[0]['group'];

        echo 'ZALOGOWANY';
        //header('Location: /');
    } else {
        echo 'ZŁY LOGIN LUB HASŁO';
    }
} else {
    echo 'BRAK TAKIEGO LOGINU';
}

//if(count($rows)>0){
//  $_SESSION['zalogowany'] = true;
// $_SESSION['id'] = $rows[0]->id;

//echo 'ZALOGOWANY';
//}else{
// return 0;



//echo json_encode($rows[0]);
