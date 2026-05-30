<?php
session_start();

// Sprawdzamy błędy
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// TYLKO ADMIN MA TU WSTĘP
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: konto.php");
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');
if (!$db) {
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8mb4");

// ========================================================
// 1. ASYNCHRONICZNA AKTUALIZACJA REKORDU (DLA FETCH API)
// ========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'aktualizuj') {
    header('Content-Type: application/json');
    
    $id = intval($_POST['id']);
    $nazwa = mysqli_real_escape_string($db, trim($_POST['nazwa']));
    $cena = floatval($_POST['cena']);
    $kategoria = mysqli_real_escape_string($db, $_POST['kategoria']);

    if ($id > 0 && !empty($nazwa) && $cena > 0) {
        $sql_update = "UPDATE produkty SET Nazwa = '$nazwa', Cena = $cena, Kategoria = '$kategoria' WHERE id = $id";
        if (mysqli_query($db, $sql_update)) {
            echo json_encode(['status' => 'success', 'message' => 'Zapisano zmianę!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Błąd bazy: ' . mysqli_error($db)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błędne dane wejściowe.']);
    }
    exit; // Przerywamy wykonywanie skryptu, żeby nie generować HTML
}

$komunikat = "";
$status_komunikatu = "";

// ========================================================
// 2. TRADYCYJNA OBSŁUGA AKCJI (DODAWANIE / USUWANIE)
// ========================================================

// DODAWANIE PRODUKTU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_produkt'])) {
    $nazwa = trim($_POST['nazwa']);
    $cena = floatval($_POST['cena']);
    $kategoria = $_POST['kategoria'];

    if (!empty($nazwa) && $cena > 0) {
        $nazwa = mysqli_real_escape_string($db, $nazwa);
        $kategoria = mysqli_real_escape_string($db, $kategoria);
        
        $sql_insert = "INSERT INTO produkty (Nazwa, Cena, Kategoria) VALUES ('$nazwa', $cena, '$kategoria')";
        if (mysqli_query($db, $sql_insert)) {
            $komunikat = "Produkt został dodany pomyślnie!";
            $status_komunikatu = "success";
        } else {
            $komunikat = "Błąd dodawania: " . mysqli_error($db);
            $status_komunikatu = "danger";
        }
    } else {
        $komunikat = "Wprowadź poprawną nazwę i cenę!";
        $status_komunikatu = "warning";
    }
}

// USUWANIE PRODUKTU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usun_produkt'])) {
    $id_produktu = intval($_POST['id_produktu']);
    
    $sql_delete = "DELETE FROM produkty WHERE id = $id_produktu";
    if (mysqli_query($db, $sql_delete)) {
        $komunikat = "Produkt został usunięty!";
        $status_komunikatu = "success";
    } else {
        $komunikat = "Błąd usuwania: " . mysqli_error($db);
        $status_komunikatu = "danger";
    }
}

// POBIERANIE PRODUKTÓW DO LISTY
$sql_produkty = "SELECT id, Nazwa, Cena, Kategoria FROM produkty ORDER BY id DESC";
$wynik_produkty = mysqli_query($db, $sql_produkty);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Produktami - Zegowska Szama</title>
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
        
        /* Styl dla czystszych inputów w tabeli edycji */
        .table-input {
            border: 1px solid transparent; background: transparent; padding: 4px 8px; border-radius: 6px; transition: all 0.2s;
        }
        .table-input:focus, .table-input:hover {
            border-color: #ced4da; background: #fff; outline: none;
        }
    </style>
</head>

<body style="background-image: url(background.png);">

    <!-- TOPBAR -->
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
                    <a href="konto.php" class="text-decoration-none text-dark">Admin</a>
                </button>
            </div>
        </div>
    </header>

    <!-- WYSUWANE MENU MOBILNE -->
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
                <a href="panel_admina.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100 active">⬅ Panel Główny Admina</button>
                </a>
                <button class="btn menu-btn text-start text-danger w-100 mt-2 mobile-logout-btn">↪ Wyloguj się</button>
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
                        <button class="btn menu-btn w-100 text-start">🛒 Powrót do sklepu</button>
                    </a>
                    <a href="panel_admina.php" class="text-decoration-none">
                        <button class="btn menu-btn w-100 text-start active">⬅ Panel Główny Admina</button>
                    </a>
                </div>
                <hr class="my-4">
                <div class="d-flex flex-column gap-2">
                    <button class="btn menu-btn text-start text-danger w-100 desktop-logout-btn">↪ Wyloguj się</button>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <section class="col-xl-9 col-lg-8 p-3 p-sm-4 p-md-5">
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">Asortyment Sklepiku</h1>

                <!-- Powiadomienia sukcesu/błędu tradycyjnego dodawania/usuwania -->
                <?php if (!empty($komunikat)): ?>
                    <div class="alert alert-<?php echo $status_komunikatu; ?> alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <strong><?php echo $komunikat; ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    
                    <!-- LEWA STRONA: FORMULARZ DODAWANIA -->
                    <div class="col-12 col-xl-4">
                        <h3 class="fw-bold mb-3 text-dark fs-5">Dodaj nowy produkt</h3>
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <form method="POST" action="admin_produkty.php">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Nazwa produktu</label>
                                    <input type="text" name="nazwa" class="form-control rounded-3 p-2" required placeholder="np. Zapiekanka z salami">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Cena (w zł)</label>
                                    <input type="number" step="0.01" name="cena" class="form-control rounded-3 p-2" required placeholder="8.50">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Kategoria</label>
                                    <select name="kategoria" class="form-select rounded-3 p-2">
                                        <option value="jedzenie">Jedzenie</option>
                                        <option value="przekąski">Przekąski</option>
                                        <option value="napoje">Napoje</option>
                                    </select>
                                </div>
                                <button type="submit" name="dodaj_produkt" class="btn btn-success w-100 rounded-4 py-2.5 fw-bold" style="background-color: #8db63f; border:none;">
                                    ➕ Dodaj produkt
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- PRAWA STRONA: INTERAKTYWNA TABELA EDYCJI -->
                    <div class="col-12 col-xl-8">
                        <h3 class="fw-bold mb-3 text-dark fs-5">Aktualna oferta (Edycja bezpośrednia)</h3>
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover align-middle m-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Nazwa</th>
                                        <th style="width: 130px;">Kategoria</th>
                                        <th style="width: 110px;">Cena (zł)</th>
                                        <th class="text-end" style="width: 140px;">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($wynik_produkty) == 0): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Brak produktów w bazie.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php while ($prod = mysqli_fetch_array($wynik_produkty)): ?>
                                            <!-- Każdy wiersz to osobny miniformularz kontrolowany przez JS za pomocą unikalnego ID -->
                                            <tr id="row-<?php echo $prod['id']; ?>">
                                                <td>
                                                    <input type="text" class="form-control table-input fw-bold small text-dark" 
                                                           value="<?php echo htmlspecialchars($prod['Nazwa']); ?>" id="nazwa-<?php echo $prod['id']; ?>">
                                                </td>
                                                <td>
                                                    <select class="form-select table-input small" id="kategoria-<?php echo $prod['id']; ?>">
                                                        <option value="jedzenie" <?php echo ($prod['Kategoria'] === 'jedzenie') ? 'selected' : ''; ?>>Jedzenie</option>
                                                        <option value="przekąski" <?php echo ($prod['Kategoria'] === 'przekąski') ? 'selected' : ''; ?>>Przekąski</option>
                                                        <option value="napoje" <?php echo ($prod['Kategoria'] === 'napoje') ? 'selected' : ''; ?>>Napoje</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" class="form-control table-input fw-bold text-success small" 
                                                           value="<?php echo $prod['Cena']; ?>" id="cena-<?php echo $prod['id']; ?>">
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex gap-1 justify-content-end">
                                                        <!-- Przycisk Zapisz (Wywołuje AJAX) -->
                                                        <button type="button" onclick="zapiszProdukt(<?php echo $prod['id']; ?>)" 
                                                                class="btn btn-sm btn-success rounded-3 px-2 py-1 small fw-bold" style="background-color:#8db63f; border:none;">
                                                            Zapisz
                                                        </button>
                                                        
                                                        <!-- Przycisk Usuń (Standardowy POST) -->
                                                        <form method="POST" action="admin_produkty.php" onsubmit="return confirm('Czy na pewno chcesz usunąć ten produkt?');" class="m-0">
                                                            <input type="hidden" name="id_produktu" value="<?php echo $prod['id']; ?>">
                                                            <button type="submit" name="usun_produkt" class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1 small">Usuń</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // FUNKCJA ZAPISUJĄCA ZMIANY PRZEZ FETCH API (BEZ PRZEŁADOWANIA)
        function zapiszProdukt(id) {
            // Pobieramy wartości z pól z konkretnego wiersza
            const nazwa = document.getElementById(`nazwa-${id}`).value;
            const kategoria = document.getElementById(`kategoria-${id}`).value;
            const cena = document.getElementById(`cena-${id}`).value;

            // Przygotowanie danych do wysyłki formularza
            const formData = new FormData();
            formData.append('id', id);
            formData.append('nazwa', nazwa);
            formData.append('kategoria', kategoria);
            formData.append('cena', cena);

            // Wysyłamy żądanie w tle pod ten sam adres z parametrem akcji
            fetch('admin_produkty.php?action=aktualizuj', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Wizualny efekt sukcesu (mignięcie wiersza na zielono)
                    const row = document.getElementById(`row-${id}`);
                    const oryginalneTlo = row.style.backgroundColor;
                    row.style.backgroundColor = '#d1e7dd';
                    row.style.transition = 'background-color 0.3s ease';
                    
                    setTimeout(() => {
                        row.style.backgroundColor = oryginalneTlo;
                    }, 800);
                } else {
                    alert('Błąd: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Wystąpił błąd podczas komunikacji z serwerem.');
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