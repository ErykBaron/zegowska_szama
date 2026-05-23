<?php
session_start();

// Sprawdzamy błędy
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Brak sesji = natychmiastowy wypad do logowania
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
    $sql = "SELECT Imie FROM uzytkownicy WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
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
    <title>Zmień hasło - Zegowska Szama</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Styl ukrywania klasycznego sidebaru na małych ekranach */
        @media (max-width: 991.98px) {
            .desktop-sidebar {
                display: none !important;
            }
        }
        
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

        /* Ładne przejścia kolorów dla pól input (Twoja walidacja live) */
        .inpat {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body style="background-image: url(background.png);">

    <!-- TOPBAR -->
    <header class="topbar sticky-top bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center gap-2">
                <!-- Hamburger dla wersji mobilnej -->
                <button class="hamburger-btn d-lg-none shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    ☰ <span class="ms-1 small fw-bold">Menu</span>
                </button>
                <a href="sklep.php"><img src="logo.png" alt="logo" class="logo" style="max-height: 45px; width: auto;"></a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="w-auto rounded-4 bg-light fw-bold border-0 shadow-sm px-3 py-2 btn btn-sm">
                    <?php echo htmlspecialchars($imie_uzytkownika); ?>
                </button>
            </div>
        </div>
    </header>

    <!-- WYSUWANE MENU MOBILNE (Offcanvas) -->
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
                
                <a href="konto.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100">
                        👤 Moje konto
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
                        <button class="btn menu-btn text-start w-100 active">
                            🔑 Zmień hasło
                        </button>
                    </a>
                <?php endif; ?>

                <?php if ($status === 'admin'): ?>
                    <a href="panel_admina.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">
                            🛠️ Panel Administratora
                        </button>
                    </a>
                <?php endif; ?>

                <button class="btn menu-btn text-start text-danger w-100 mt-2 mobile-logout-btn">
                    ↪ Wyloguj się
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <main class="container-fluid">
        <div class="row">

            <!-- SIDEBAR STACJONARNY (Desktop) -->
            <aside class="col-xl-3 col-lg-4 sidebar desktop-sidebar p-4 bg-light shadow-sm min-vh-100">
                <h5 class="sidebar-title mb-4 fw-bold text-muted">NAWIGACJA</h5>

                <div class="d-flex flex-column gap-2">
                    <a href="sklep.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start">
                            🛒 Powrót do sklepu
                        </button>
                    </a>
                    <a href="konto.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start">
                            👤 Moje konto
                        </button>
                    </a>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-column gap-2">
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
                            <button class="btn menu-btn text-start w-100 active">
                                🔑 Zmień hasło
                            </button>
                        </a>
                    <?php endif; ?>

                    <?php if ($status === 'admin'): ?>
                        <a href="panel_admina.php" class="text-decoration-none">
                            <button class="btn menu-btn text-start w-100">
                                🛠️ Panel Administratora
                            </button>
                        </a>
                    <?php endif; ?>

                    <button class="btn menu-btn text-start text-danger w-100 mt-2 desktop-logout-btn">
                        ↪ Wyloguj się
                    </button>
                </div>
            </aside>

            <!-- CONTENT (Formularz zmiany hasła) -->
            <section class="col-xl-9 col-lg-8 p-3 p-sm-4 p-md-5">
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">
                    Bezpieczeństwo konta
                </h1>

                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white mx-auto" style="max-width: 600px;">
                    <h3 class="fw-bold mb-4 text-dark fs-4 text-center">Zmień swoje hasło</h3>
                    
                    <!-- Formularz ze strukturą Bootstrapa -->
                    <div class="row g-3 justify-content-center">
                        <div class="col-12">
                            <label for="stareHaslo" class="form-label fw-semibold text-muted">Aktualne hasło</label>
                            <input type="password" placeholder="Podaj stare hasło" name="starehaslo" id="stareHaslo" class="form-control form-control-lg bg-light border-1 rounded-3">
                        </div>

                        <div class="col-12">
                            <label for="nowehaslo1" class="form-label fw-semibold text-muted">Nowe hasło</label>
                            <input type="password" placeholder="Podaj nowe hasło" name="nowehaslo" id="nowehaslo1" class="form-control form-control-lg bg-light border-1 rounded-3">
                        </div>

                        <div class="col-12">
                            <label for="nowehaslo2" class="form-label fw-semibold text-muted">Powtórz nowe hasło</label>
                            <input type="password" placeholder="Potwierdź nowe hasło" name="potwierdznowe" id="nowehaslo2" class="form-control form-control-lg bg-light border-1 rounded-3">
                        </div>

                        <!-- Alert na błędy / sukcesy -->
                        <div class="col-12">
                            <div class="alert p-3 rounded-3 d-none text-center fw-bold shadow-sm" id="alert" role="alert"></div>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button id="zmien" type="submit" class="btn btn-lg w-100 rounded-3 fw-bold py-2 shadow-sm" style="background-color: #8db63f; color: white;">
                                Zaktualizuj hasło
                            </button>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Wymagany plik JS Bootstrapa -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Skrypty JS zachowujące Twoją logikę sprawdzania pól oraz asynchroniczny FETCH -->
    <script>
        const alertBox = document.getElementById('alert');
        const zmien = document.getElementById('zmien');
        const n1 = document.getElementById('nowehaslo1');
        const n2 = document.getElementById('nowehaslo2');

        // Twoja walidacja zgodności haseł w czasie rzeczywistym
        n2.addEventListener('input', () => {
            if (n1.value !== n2.value) {
                n2.style.backgroundColor = "#ffcccc"; // delikatniejszy czerwony pasujący do nowoczesnego UI
                n2.style.color = "#b30000";
            } else {
                n2.style.backgroundColor = "#d4edda"; // delikatny zielony
                n2.style.color = "#155724";
            }
        });
        
        // Czyszczenie stylów, gdy pole 2 staje się puste
        n2.addEventListener('blur', () => {
            if(n2.value === "") {
                n2.style.backgroundColor = "";
                n2.style.color = "";
            }
        });

        // Twoja asynchroniczna wysyłka żądania
        zmien.addEventListener('click', (e) => {
            e.preventDefault();

            const stareHaslo = document.getElementById('stareHaslo').value;
            const nowehaslo1 = n1.value;
            const nowehaslo2 = n2.value;

            if (!stareHaslo || !nowehaslo1 || !nowehaslo2) {
                alertBox.className = "alert alert-danger text-center fw-bold";
                alertBox.innerHTML = "Wypełnij wszystkie pola!";
                return;
            }

            fetch('zmienHaslo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: "status=" + encodeURIComponent("<?php echo $status; ?>") + "&stareHaslo=" + encodeURIComponent(stareHaslo) + "&noweHaslo1=" + encodeURIComponent(nowehaslo1) + "&noweHaslo2=" + encodeURIComponent(nowehaslo2)
            })
            .then(res => res.text())
            .then(data => {
                alertBox.classList.remove('d-none');
                
                if (data.trim().startsWith("Hasło")) {
                    // Sukces
                    alertBox.className = "alert alert-success text-center fw-bold shadow-sm";
                    alertBox.innerHTML = data;
                    
                    // Czyszczenie formularza
                    document.getElementById('stareHaslo').value = "";
                    n1.value = "";
                    n2.value = "";
                    n2.style.backgroundColor = "";
                    n2.style.color = "";
                } else {
                    // Błąd z pliku PHP
                    alertBox.className = "alert alert-danger text-center fw-bold shadow-sm";
                    alertBox.innerHTML = data;
                }
            });
        });

        // Obsługa wylogowania ze starego projektu
        const ObslugaWylogowania = () => {
            fetch('wyloguj.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(() => {
                window.location.href = 'logowanie.php';
            });
        };

        document.querySelector('.desktop-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        document.querySelector('.mobile-logout-btn')?.addEventListener('click', ObslugaWylogowania);
    </script>
</body>
</html>