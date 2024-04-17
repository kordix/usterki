<?php


session_start();


unset($_SESSION['zalogowany']);

session_destroy();

header('Location: /logowanie.php');







//echo json_encode($rows[0]);





?>