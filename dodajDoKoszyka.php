<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: logowanie.php");
    exit;
}

$status = $_SESSION['user_id'];

$id_produktu = $_GET['id'] ?? 0;

$db = mysqli_connect('localhost','root','','zegowskaszama');

mysqli_query($db,"INSERT INTO koszyk VALUES (NULL, $id_produktu, $status)");

header("Location: sklep.php");
?>