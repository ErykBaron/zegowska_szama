<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Zamówień - Zegowska Szama</title>
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
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: logowanie.php");
    exit;
}

$db = mysqli_connect('localhost', 'root', '' ,'zegowskaszama');

$status = $_SESSION['user_id'];

$sql0 = "SELECT Imie FROM użytkownicy WHERE id = $status";

$wynik0 = mysqli_query($db, $sql0);

$user = mysqli_fetch_array($wynik0);

$sql1 = "SELECT zamówienia.data, zamówienia.indeks, zamówienia.status, produkty.Nazwa, produkty.Cena, produkty.Kategoria FROM zamówienia JOIN produkty ON zamówienia.id_produktu = produkty.id WHERE id_użytkownika = $status ORDER BY indeks DESC";
$wynik1 = mysqli_query($db, $sql1);


if(mysqli_num_rows($wynik1) < 1){
    $stan_zamowien = 0;
}else{
    $stan_zamowien = 1;
}
?>
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
                    <a href="konto.php" class="text-decoration-none text-dark"><?php echo htmlspecialchars($user['Imie']); ?></a>
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
                    <button class="btn menu-btn text-start w-100">👤 Szczegóły konta</button>
                </a>
                <a href="historia.php" class="text-decoration-none">
                    <button class="btn menu-btn text-start w-100 active">📋 Historia zamówień</button>
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
                        <button class="btn menu-btn text-start w-100">👤 Szczegóły konta</button>
                    </a>
                    <a href="historia.php" class="text-decoration-none">
                        <button class="btn menu-btn text-start w-100 active">📋 Historia zamówień</button>
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
                <h1 class="fw-bold text-center mb-4 mb-md-5 text-dark fs-2">Historia Zamówień</h1>

                <div class="mx-auto" style="max-width: 800px;">

                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <?php if($stan_zamowien == 0){
                            // 1. Wyświetli się, jeśli baza zwróci 0 wierszy
            echo '<div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center text-secondary fs-4">📋 Nie masz żadnych zamówień</div>';
        } else {
            // 2. Jeśli zamówienia są, pętla rusza tutaj:
            $obecny_indeks = ""; 
            $suma_zamowienia = 0;
            $czy_calosc_dostarczona = true;

            while($skladnik = mysqli_fetch_array($wynik1)) {
                
                // Sprawdzamy, czy to zupełnie nowe zamówienie, czy kolejny produkt z tego samego koszyka
                if($skladnik['indeks'] !== $obecny_indeks) {
                    
                    // Jeśli to nie jest pierwsza paczka, zamykamy poprzedni boks w HTML
                    if($obecny_indeks !== "") {
                        $tekst_statusu = ($czy_calosc_dostarczona) ? "Dostarczone" : "W drodze";
                        $kolor_statusu = ($czy_calosc_dostarczona) ? "text-success" : "text-warning";

                        echo '</div> <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <span class="fw-bold text-muted">Status: <span class="' . $kolor_statusu . '">' . $tekst_statusu . '</span></span>
                                    <div>
                                        <span class="fs-6 text-muted me-2">Suma:</span>
                                        <span class="fs-4 fw-bold text-success">' . number_format($suma_zamowienia, 2, ',', '') . ' zł</span>
                                    </div>
                                </div>
                            </div> ';
                    }
                    $suma_zamowienia = 0;
                    $obecny_indeks = $skladnik['indeks'];
                    $czy_calosc_dostarczona = true;
                    
                    // OTWIERAMY NOWĄ KARTĘ ZAMÓWIENIA
                    echo '
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-3 gap-2">
                            <div>
                                <span class="text-muted small d-block">NUMER ZAMÓWIENIA (INDEKS)</span>
                                <span class="fw-bold text-dark font-monospace">' . htmlspecialchars($skladnik['indeks']) . '</span>
                            </div>
                            <div class="text-sm-end">
                                <span class="text-muted small d-block">DATA ZŁOŻENIA</span>
                                <span class="fw-bold text-dark">' . $skladnik['data'] . '</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3">'; // Tu wlecą produkty
                }

                if($skladnik['status'] != 1) {
                    $czy_calosc_dostarczona = false;
                }

                $suma_zamowienia += $skladnik['Cena'];
                // TUTAJ RENDERUJE SIĘ KAŻDY PRODUKT (Warto tu dopisać JOIN do tabeli produkty)
                echo '
                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                    <div>
                        <h6 class="fw-bold m-0">' . htmlspecialchars($skladnik['Nazwa']) . '</h6>
                        <span class="badge bg-secondary">'.$skladnik['Kategoria'].'</span>
                    </div>
                    <span class="fw-bold text-dark">'. number_format($skladnik['Cena'], 2, ',', '').' zł</span>
                </div>';
            }

            if($obecny_indeks !== "") {
                $tekst_statusu = ($czy_calosc_dostarczona) ? "Dostarczone" : "W drodze";
                $kolor_statusu = ($czy_calosc_dostarczona) ? "text-success" : "text-warning";
            }
            
            // Zamykamy ostatnią otwartą kartę po wyjściu z pętli while
            echo '</div> <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <span class="fw-bold text-muted">Status: <span class="' . $kolor_statusu . '">' . $tekst_statusu . '</span></span>
                        <div>
                            <span class="fs-6 text-muted me-2">Suma:</span>
                            <span class="fs-4 fw-bold text-success">' . number_format($suma_zamowienia, 2, ',', '') . ' zł</span>
                        </div>
                    </div>
                </div>';
        }
        ?>
                    </div>


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