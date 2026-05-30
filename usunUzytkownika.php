<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 'admin'){
    header("Location: sklep.php");
    exit;
}

$db = mysqli_connect('localhost','root','','zegowskaszama');

$id = $_POST['id'] ?? '';

if($id != ''){
    $sql = "DELETE FROM użytkownicy WHERE id = $id";

    mysqli_query($db, $sql);
}

header("Location: admin_uzytkownicy.php");
?>