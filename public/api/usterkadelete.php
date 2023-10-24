<?php

require('../db.php');

$id = $_GET['id'];
$query = "delete from usterki where id = ?";
$sth = $dbh->prepare($query);
$sth->execute([$id]);


