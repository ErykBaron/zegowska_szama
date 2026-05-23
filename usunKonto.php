<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: logowanie.php");

    exit;
}
$status = $_SESSION['user_id'];

$db = mysqli_connect('localhost','root','','zegowskaszama');

$sql = "DELETE FROM użytkownicy WHERE użytkownicy.id = $status";

mysqli_query($db,$sql);

session_unset();
session_destroy();
?>