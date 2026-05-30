<?php
session_start();

// Sprawdzamy błędy
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 'admin'){
    header("Location: logowanie.php");
    exit;
}

$db = mysqli_connect('localhost','root','','zegowskaszama');
if(!$db){
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baza Użytkowników - Zegowska Szama</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <style>
        @media (max-width: 991.98px) {
            .desktop-sidebar { display: none !important; }
        }
        .hamburger-btn {
            background: none; border: 1px solid #dee2e6; padding: 8px 12px; border-radius: 12px; color: #333; transition: all 0.2s ease;
        }
        .hamburger-btn:hover { background-color: #f8f9fa; }
        
        /* Styl wyszukiwarki */
        .search-box {
            border-radius: 12px; padding: 10px 15px; border: 1px solid #dee2e6; transition: all 0.2s;
        }
        .search-box:focus {
            border-color: #8db63f; box-shadow: 0 0 0 0.25rem rgba(141, 182, 63, 0.25); outline: none;
        }
    </style>
</head>

<body style="background-image: url(background.png);">

    <header class="topbar sticky-top bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center gap-2">
                <button class="hamburger-btn d-lg-none shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    ☰ <span class="ms-1 small fw-bold">Menu</span>
                </button>
                <a href="sklep.php"><img src="logo.png" alt="logo" class="logo" style="max-height: 45px; width: auto;"></a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="w-auto rounded-4 bg-light fw-bold border-0 shadow-sm px-3 py-2 btn btn-sm">
                    <span class="text-dark">Admin</span>
                </button>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" style="width: 280px;">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold text-muted">PANEL CONTROL</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light d-flex flex-column justify-content-between">
            <div class="d-flex flex-column gap-2">
                <a href="sklep.php" class="text-decoration-none mb-3">
                    <button class="btn menu-btn w-100 text-start">🛒 Powrót do sklepu</button>
                </a>
                <a href="panelAdmina.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100 active">⬅ Panel Główny Admina</button>
                </a>
                <button class="btn menu-btn text-start text-danger w-100 mt-2 mobile-logout-btn">↪ Wyloguj się</button>
            </div>
        </div>
    </div>

    <main class="container-fluid">
        <div class="row">

            <aside class="col-xl-3 col-lg-4 sidebar desktop-sidebar p-4 bg-light shadow-sm min-vh-100">
                <h5 class="sidebar-title mb-4 fw-bold text-muted">NAWIGACJA</h5>
                <div class="d-flex flex-column gap-2">
                    <a href="sklep.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start">🛒 Powrót do sklepu</button>
                    </a>
                    <a href="panelAdmina.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start active">⬅ Panel Główny Admina</button>
                    </a>
                </div>
                <hr class="my-4">
                <div class="d-flex flex-column gap-2">
                    <button class="btn menu-btn text-start text-danger w-100 desktop-logout-btn">↪ Wyloguj się</button>
                </div>
            </aside>

            <section class="col-xl-9 col-lg-8 p-3 p-sm-4 p-md-5">
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">Baza Użytkowników</h1>

                
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Imię i Nazwisko</th>
                                <th>Adres E-mail</th>
                                <th class="text-end" style="width: 150px;">Akcja</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <?php
                            $sql = "SELECT * FROM użytkownicy";

                            $wynik = mysqli_query($db, $sql);

                            while($d = mysqli_fetch_array($wynik)){
                                echo '<tr>
                                <td class="text-muted small">#12</td>
                                <td>
                                    <div class="fw-bold text-dark">'.$d['Imie'].' '. $d['Nazwisko'] . '</div>
                                </td>
                                <td class="small text-muted">'.$d['Email'].'</td>
                                <td class="text-end">
                                    <button type="button" onclick="usunUzytkownika('.$d['id'].')" class="btn btn-outline-danger btn-sm rounded-3 px-3">
                                        Usuń konto
                                    </button>
                                </td>
                            </tr>';
                            }
                            ?>
                            

                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        

        // ==========================================
        // USUWANIE UŻYTKOWNIKA (FETCH API)
        // ==========================================
        function usunUzytkownika(id) {
            fetch('usunUzytkownika.php',{
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: 'id=' + encodeURIComponent(id)
                                })
                                .then(()=>{
                                    window.location.href = 'admin_uzytkownicy.php';
                                });
        }

        // Obsługa wylogowania
        const ObslugaWylogowania = () => {
            fetch('wyloguj.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' } })
            .then(() => { window.location.href = 'logowanie.php'; });
        };
        document.querySelector('.desktop-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        document.querySelector('.mobile-logout-btn')?.addEventListener('click', ObslugaWylogowania);
    </script>
</body>
</html>