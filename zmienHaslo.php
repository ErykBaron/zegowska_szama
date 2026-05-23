<?php
session_start();

// Sprawdzamy błędy
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo 'Błąd autoryzacji. Zaloguj się ponownie.';
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');

if (!$db) {
    echo 'Błąd połączenia z bazą danych.';
    exit;
}
mysqli_set_charset($db, "utf8mb4");

$status = $_SESSION['user_id'];

$stareHaslo = isset($_POST['stareHaslo']) ? $_POST['stareHaslo'] : '';
$noweHaslo1 = isset($_POST['noweHaslo1']) ? $_POST['noweHaslo1'] : '';
$noweHaslo2 = isset($_POST['noweHaslo2']) ? $_POST['noweHaslo2'] : '';

$sql = "SELECT Haslo FROM użytkownicy WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
$wynik = mysqli_query($db, $sql);

if (!$wynik || mysqli_num_rows($wynik) !== 1) {
    echo 'Nie znaleziono użytkownika w bazie.';
    exit;
}

$hasloUser = mysqli_fetch_assoc($wynik);

// LOGIKA ZWRACANIA KOMUNIKATÓW DLA ALERTU JS
if (empty($stareHaslo) || !password_verify($stareHaslo, $hasloUser['Haslo'])) {
    echo 'Nieprawidłowe aktualne hasło!';
} else {
    if (strlen($noweHaslo1) < 8) {
        echo 'Nowe hasło jest za krótkie! (musi mieć przynajmniej 8 znaków)';
    } else {
        if ($noweHaslo1 === $noweHaslo2) {
            
            $noweHasloZhashowane = password_hash($noweHaslo1, PASSWORD_DEFAULT);
            $noweHasloZhashowaneEscaped = mysqli_real_escape_string($db, $noweHasloZhashowane);

            $update_sql = "UPDATE użytkownicy SET Haslo = '$noweHasloZhashowaneEscaped' WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
            
            if (mysqli_query($db, $update_sql)) {
                // Tekst zaczyna się od "Hasło", więc JS pokaże zielony alert sukcesu
                echo 'Hasło zmieniono pomyślnie!';
            } else {
                echo 'Błąd podczas zapisu nowego hasła w bazie danych.';
            }
        } else {
            echo 'Potwierdzone hasło nie jest identyczne z nowym hasłem!';
        }
    }
}
?>