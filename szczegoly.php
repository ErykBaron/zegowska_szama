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

// Blokada dla admina - admin nie powinien edytować profilu "ucznia" w tym miejscu
if ($status === 'admin') {
    header("Location: konto.php");
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');
if (!$db) {
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8mb4");

$komunikat = "";
$status_komunikatu = "";

// --- OBSŁUGA AKTUALIZACJI DANYCH (Po kliknięciu "Zapisz zmiany") ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zapisz_dane'])) {
    $nowe_imie = trim($_POST['imie']);
    $nowe_nazwisko = trim($_POST['nazwisko']);
    $nowy_email = trim($_POST['email']);

    if (!empty($nowe_imie) && !empty($nowe_nazwisko) && !empty($nowy_email)) {
        $sql_update = "UPDATE użytkownicy SET 
                       Imie = '" . mysqli_real_escape_string($db, $nowe_imie) . "', 
                       Nazwisko = '" . mysqli_real_escape_string($db, $nowe_nazwisko) . "', 
                       Email = '" . mysqli_real_escape_string($db, $nowy_email) . "' 
                       WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";

        if (mysqli_query($db, $sql_update)) {
            $komunikat = "Dane zostały pomyślnie zaktualizowane!";
            $status_komunikatu = "success";
        } else {
            $komunikat = "Błąd podczas zapisu danych: " . mysqli_error($db);
            $status_komunikatu = "danger";
        }
    } else {
        $komunikat = "Wszystkie pola muszą być wypełnione!";
        $status_komunikatu = "warning";
    }
}

// --- POBIERANIE AKTUALNYCH DANYCH UŻYTKOWNIKA ---
$sql = "SELECT Imie, Nazwisko, Email FROM użytkownicy WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
$wynik = mysqli_query($db, $sql);

if ($wynik && mysqli_num_rows($wynik) == 1) {
    $uzytkownik = mysqli_fetch_assoc($wynik);
    $imie_uzytkownika = $uzytkownik['Imie'];
    $nazwisko_uzytkownika = $uzytkownik['Nazwisko'];
    $email_uzytkownika = $uzytkownik['Email'];
} else {
    die("Nie znaleziono użytkownika w bazie.");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Konta - Zegowska Szama</title>
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
        @media (max-width: 991.98px) {
            .desktop-sidebar { display: none !important; }
        }
        .hamburger-btn {
            background: none; border: 1px solid #dee2e6; padding: 8px 12px; border-radius: 12px; color: #333; transition: all 0.2s ease;
        }
        .hamburger-btn:hover { background-color: #f8f9fa; }
    </style>
</head>

<body style="background-image: url(background.png);">

    <header class="topbar sticky-top bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center gap-2">
                <button class="hamburger-btn d-lg-none shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    ☰ <span class="ms-1 small fw-bold">Menu</span>
                </button>
                <a href="sklep.php"><img src="logo.png" alt="logo" class="logo" style="max-height: 45px; width: auto;"></a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="w-auto rounded-4 bg-light fw-bold border-0 shadow-sm px-3 py-2 btn btn-sm">
                    <a href="konto.php" class="text-decoration-none text-dark"><?php echo htmlspecialchars($imie_uzytkownika); ?></a>
                </button>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" style="width: 280px;">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold text-muted">NAWIGACJA</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light d-flex flex-column justify-content-between">
            <div class="d-flex flex-column gap-2">
                <a href="sklep.php" class="text-decoration-none mb-3">
                    <button class="btn menu-btn w-100 text-start">🛒 Powrót do sklepu</button>
                </a>
                <a href="konto.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100">🏠 Profil główny</button>
                </a>
                <a href="szczegoly.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100 active">Anuluj edycję profilu</button>
                </a>
                <a href="historiaZamowien.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100">📋 Historia zamówień</button>
                </a>
                <a href="zmianaHasla.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100">🔑 Zmień hasło</button>
                </a>
                <button class="btn menu-btn text-start text-danger w-100 mt-2 mobile-logout-btn">↪ Wyloguj się</button>
            </div>
            <div class="d-flex flex-column mt-4">
                <button class="btn btn-danger rounded-4 py-2 fw-bold shadow-sm w-100 mobile-delete-btn">❌ Usuń konto</button>
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
                    <a href="konto.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start">🏠 Profil główny</button>
                    </a>
                </div>
                <hr class="my-4">
                <div class="d-flex flex-column gap-2">
                    <a href="szczegoly.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100 active">👤 Szczegóły konta</button>
                    </a>
                    <a href="historiaZamowien.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">📋 Historia zamówień</button>
                    </a>
                    <a href="zmianaHasla.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100">🔑 Zmień hasło</button>
                    </a>
                    <button class="btn menu-btn text-start text-danger w-100 mt-2 desktop-logout-btn">↪ Wyloguj się</button>
                </div>
                <hr class="my-4">
                <div class="d-flex flex-column">
                    <button class="btn btn-danger rounded-4 py-2 fw-bold shadow-sm" id="usun">❌ Usuń konto</button>
                </div>
            </aside>

            <section class="col-xl-9 col-lg-8 p-3 p-sm-4 p-md-5">
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">Moje Dane</h1>

                <?php if (!empty($komunikat)): ?>
                    <div class="alert alert-<?php echo $status_komunikatu; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <strong><?php echo $komunikat; ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 p-4 p-sm-5 bg-white mx-auto" style="max-width: 700px;">
                    <div class="text-center mb-4">
                        <div class="text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm avatar-circle mx-auto mb-3">
                            <span class="fs-2 fw-bold"><?php echo mb_substr($imie_uzytkownika, 0, 1, 'utf-8'); ?></span>
                        </div>
                        <h4 class="fw-bold text-dark m-0">Edycja profilu użytkownika</h4>
                        <p class="text-muted small mt-1">Zmień swoje dane w formularzu poniżej i zatwierdź przyciskiem.</p>
                    </div>

                    <form action="szczegoly.php" method="POST" class="needs-validation">
                        
                        <div class="mb-3">
                            <label for="imie" class="form-label fw-bold text-muted small">Imię</label>
                            <input type="text" class="form-control rounded-3 p-3 shadow-sm bg-light" id="imie" name="imie" 
                                   value="<?php echo htmlspecialchars($imie_uzytkownika); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="nazwisko" class="form-label fw-bold text-muted small">Nazwisko</label>
                            <input type="text" class="form-control rounded-3 p-3 shadow-sm bg-light" id="nazwisko" name="nazwisko" 
                                   value="<?php echo htmlspecialchars($nazwisko_uzytkownika); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-muted small">Adres E-mail</label>
                            <input type="email" class="form-control rounded-3 p-3 shadow-sm bg-light" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($email_uzytkownika); ?>" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <button type="submit" name="zapisz_dane" class="btn btn-success w-100 rounded-4 py-3 fw-bold shadow-sm" style="background-color: #8db63f; border: none;">
                                    💾 Zapisz zmiany
                                </button>
                            </div>
                            <div class="col-12 col-sm-6">
                                <a href="konto.php" class="btn btn-outline-secondary w-100 rounded-4 py-3 fw-bold shadow-sm">
                                    Anuluj
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

        document.querySelector('.desktop-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        document.querySelector('.mobile-logout-btn')?.addEventListener('click', ObslugaWylogowania);
        
        document.getElementById('usun')?.addEventListener('click', ObslugaUsuwaniaKonta);
        document.querySelector('.mobile-delete-btn')?.addEventListener('click', ObslugaUsuwaniaKonta);
    </script>
</body>
</html>