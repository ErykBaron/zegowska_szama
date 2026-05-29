<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: logowanie.php");
    exit;
}

$status = $_SESSION['user_id'];

$indeks = uniqid();
$db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');

if (!$db) {
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8mb4");

$sql1 = "SELECT id_produktu FROM koszyk WHERE koszyk.id_uzytkownika = '" . mysqli_real_escape_string($db, $status) . "'";

$data = date("Y-m-d");
$wynik1 = mysqli_query($db, $sql1);

    while($d = mysqli_fetch_array($wynik1)){
        $id_prod = $d['id_produktu'];
        
        $sql = "INSERT INTO zamówienia VALUES (NULL, '" . mysqli_real_escape_string($db, $status) . "', '" . mysqli_real_escape_string($db, $id_prod) . "', 
                        '$data', 
                        '$indeks')";
                        
        mysqli_query($db, $sql);
    }

    mysqli_query($db, "DELETE FROM koszyk WHERE koszyk.id_uzytkownika = '" . mysqli_real_escape_string($db, $status) . "'");
    

mysqli_close($db);
?>