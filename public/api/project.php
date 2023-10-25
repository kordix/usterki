<?php

require('../db.php');
$id = $_GET['id'];
$query = "select * from projects where id = ?";


header('Location: /login.php');



$sth = $dbh->prepare($query);
$sth->execute([$id]);

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
