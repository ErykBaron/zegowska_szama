<?php
session_start();

// Sprawdzamy błędy
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Brak sesji = natychmiastowy wypad do logowania (brak opcji gościa)
if (!isset($_SESSION['user_id'])) {
    header("Location: logowanie.php");
    
    exit;
}

$status = $_SESSION['user_id'];
$db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');

if (!$db) {
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8mb4");

// Pobieramy dane użytkownika z bazy, jeśli to nie jest admin
$imie_uzytkownika = "Użytkownik";
if ($status !== 'admin') {
    $sql = "SELECT Imie, Email FROM użytkownicy WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
    $wynik = mysqli_query($db, $sql);
    if ($wynik && mysqli_num_rows($wynik) == 1) {
        $uzytkownik = mysqli_fetch_assoc($wynik);
        $imie_uzytkownika = $uzytkownik['Imie'];
    }
} else {
    $imie_uzytkownika = "Admin";
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Konto</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <style>
        .avatar-circle {
            width: 70px; 
            height: 70px; 
            background-color: #8db63f !important;
            flex-shrink: 0;
        }

        /* Ukrywamy klasyczny sidebar na małych ekranach, pokazujemy od lg (992px) */
        @media (max-width: 991.98px) {
            .desktop-sidebar {
                display: none !important;
            }
        }
        
        /* Styl dedykowany dla przycisku menu (hamburgera) */
        .hamburger-btn {
            background: none;
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            border-radius: 12px;
            color: #333;
            transition: all 0.2s ease;
        }
        .hamburger-btn:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body style="background-image: url(background.png);">

    <!-- TOPBAR -->
    <header class="topbar sticky-top bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center py-2 px-3">
            
            <div class="d-flex align-items-center gap-2">
                <!-- PRZYCISK HAMBURGERA: Widoczny tylko na telefonie/tablecie (d-lg-none) -->
                <button class="hamburger-btn d-lg-none shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    ☰ <span class="ms-1 small fw-bold">Menu</span>
                </button>
                
                <!-- Logo -->
                <a href="sklep.php"><img src="logo.png" alt="logo" class="logo" style="max-height: 45px; width: auto;"></a>
            </div>

            <!-- Profil w topbarze -->
            <div class="d-flex align-items-center gap-2">
                <button class="w-auto rounded-4 bg-light fw-bold border-0 shadow-sm px-3 py-2 btn btn-sm">
                    <a href="konto.php"><?php echo htmlspecialchars($imie_uzytkownika); ?></a>
                </button>
            </div>
        </div>
    </header>

    <!-- WYSUWANE MENU MOBILNE (Offcanvas): Widoczne tylko po kliknięciu na smartfonie -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="width: 280px;">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold text-muted" id="mobileSidebarLabel">NAWIGACJA</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light d-flex flex-column justify-content-between">
            <div class="d-flex flex-column gap-2">
                <a href="sklep.php" class="text-decoration-none mb-3">
                    <button class="btn menu-btn w-100 text-start">
                        🛒 Powrót do sklepu
                    </button>
                </a>
                
                <?php if ($status !== 'admin'): ?>
                    <a href="szczegoly.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">
                            📄 Szczegóły konta
                        </button>
                    </a>
                    <a href="historiaZamowien.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">
                            📋 Historia zamówień
                        </button>
                    </a>
                    <a href="zmianaHasla.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">
                            🔑 Zmień hasło
                        </button>
                    </a>
                <?php endif; ?>

                <?php if ($status === 'admin'): ?>
                    <a href="panel_admina.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100 active">
                            🛠️ Panel Administratora
                        </button>
                    </a>
                <?php endif; ?>

                <button class="btn menu-btn text-start text-danger w-100 mt-2 mobile-logout-btn">
                    ↪ Wyloguj się
                </button>
            </div>

            <?php if ($status !== 'admin'): ?>
                <div class="d-flex flex-column mt-4">
                    <button class="btn btn-danger rounded-4 py-2 fw-bold shadow-sm w-100 mobile-delete-btn">
                        ❌ Usuń konto
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <main class="container-fluid">
        <div class="row">

            <!-- SIDEBAR STACJONARNY (Desktop): Widoczny tylko na dużych ekranach (d-none d-lg-block) -->
            <aside class="col-xl-3 col-lg-4 sidebar desktop-sidebar p-4 bg-light shadow-sm min-vh-100">
                <h5 class="sidebar-title mb-4 fw-bold text-muted">
                    NAWIGACJA
                </h5>

                <div class="d-flex flex-column gap-2">
                    <a href="sklep.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start">
                            🛒 Powrót do sklepu
                        </button>
                    </a>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-column gap-2">
                    
                    <?php if ($status === 'admin'): ?>
                        <a href="panel_admina.php" class="text-decoration-none">
                            <button class="btn menu-btn text-start w-100 active">
                                🛠️ Panel Administratora
                            </button>
                        </a>
                    <?php endif; ?>

                    <button class="btn menu-btn text-start text-danger w-100 mt-2 desktop-logout-btn">
                        ↪ Wyloguj się
                    </button>
                </div>

                <?php if ($status !== 'admin'): ?>
                    <hr class="my-4">
                    <div class="d-flex flex-column">
                        <button class="btn btn-danger rounded-4 py-2 fw-bold shadow-sm" class="desktop-delete-btn" id="usun">
                            ❌ Usuń konto
                        </button>
                    </div>
                <?php endif; ?>
            </aside>

            <!-- CONTENT (Główna zawartość strony - automatycznie dopasowuje szerokość) -->
            <section class="col-xl-9 col-lg-8 p-3 p-sm-4 p-md-5">
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">
                    Ustawienia Profilu
                </h1>

                <!-- Karta powitalna -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 mb-4 bg-white">
                    <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start gap-4">
                        <div class="text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm avatar-circle">
                            <span class="fs-2 fw-bold"><?php echo mb_substr($imie_uzytkownika, 0, 1, 'utf-8'); ?></span>
                        </div>
                        <div>
                            <h2 class="fw-bold m-0 text-dark fs-3">
                                <?php
                                if ($status === 'admin') {
                                    echo "Witaj w pracy, Administratorze!";
                                } else {
                                    echo "Cześć, " . htmlspecialchars($imie_uzytkownika) . "!";
                                }
                                ?>
                            </h2>
                            <p class="text-muted m-0 mt-1 small">
                                <?php
                                if ($status === 'admin') {
                                    echo "Masz pełny dostęp do zarządzania zamówieniami uczniów.";
                                } else {
                                    echo "Twój profil w serwisie Zegowska Szama jest aktywny.";
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sekcja akcji -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h3 class="fw-bold mb-4 text-dark fs-5 text-center text-sm-start">Dostępne operacje</h3>
                    
                    <div class="row g-3">
                        <?php if ($status === 'admin'): ?>
                            <div class="col-12">
                                <div class="p-4 border border-success rounded-4 bg-light text-center">
                                    <p class="fs-5 mb-3">Wszystkie systemy działają prawidłowo.</p>
                                    <a href="panel_admina.php" class="btn btn-success btn-lg rounded-3 px-4 w-100 w-sm-auto" style="background-color: #8db63f; border: none;">Przejdź do zarządzania użytkownikami</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Szybkie kafelki nawigacji dla zalogowanego ucznia -->
                            <div class="col-12 col-sm-4 text-center">
                                <a href="szczegoly.php" class="text-decoration-none text-dark d-block h-100">
                                    <div class="p-4 border rounded-4 bg-light h-100 shadow-sm border-0">
                                        <div class="fs-2 mb-2">👤</div>
                                        <div class="fw-bold">Moje Dane</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-sm-4 text-center">
                                <a href="historiaZamowien.php" class="text-decoration-none text-dark d-block h-100">
                                    <div class="p-4 border rounded-4 bg-light h-100 shadow-sm border-0">
                                        <div class="fs-2 mb-2">📦</div>
                                        <div class="fw-bold">Zamówienia</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-12 col-sm-4 text-center">
                                <a href="zmianaHasla.php" class="text-decoration-none text-dark d-block h-100">
                                    <div class="p-4 border rounded-4 bg-light h-100 shadow-sm border-0">
                                        <div class="fs-2 mb-2">🔐</div>
                                        <div class="fw-bold">Bezpieczeństwo</div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Wymagany plik JS Bootstrapa (odpowiedzialny za wysuwanie menu z boku bez pisania własnego JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Skrypty JS zachowujące Twoją asynchroniczną logikę wylogowania/usuwania dla obu wersji menu -->
    <script>
        const ObslugaWylogowania = () => {
            fetch('wyloguj.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(() => {
                window.location.href = 'logowanie.php';
            });
        };

        const ObslugaUsuwaniaKonta = () => {
            if (confirm("Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna!")) {
                fetch('usunKonto.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .then(() => {
                    window.location.href = 'logowanie.php';
                });
            }
        };

        // Podpięcie zdarzeń pod przyciski na komputerze (Id) i telefonie (Klasy)
        document.querySelector('.desktop-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        document.querySelector('.mobile-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        
        document.getElementById('usun')?.addEventListener('click', ObslugaUsuwaniaKonta);
        document.querySelector('.mobile-delete-btn')?.addEventListener('click', ObslugaUsuwaniaKonta);
    </script>
</body>
</html>