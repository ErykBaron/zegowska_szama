<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sklep</title>

    <link rel="icon" type="image/png" href="logo.png">
    <!-- Poprawiłem wersję bootstrapa na stabilną 5.3.3, ponieważ wersja 5.3.8 nie istnieje i mogła nie ładować stylów -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styl_sklep.css">
</head>
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("location: logowanie.php");
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
    $sql1 = "SELECT Imie, Email FROM użytkownicy WHERE id = '" . mysqli_real_escape_string($db, $status) . "'";
    $wynik = mysqli_query($db, $sql1);
    if ($wynik && mysqli_num_rows($wynik) == 1) {
        $uzytkownik = mysqli_fetch_assoc($wynik);
        $imie_uzytkownika = $uzytkownik['Imie'];
    }
} else {
    $imie_uzytkownika = "Admin";
}
?>

<!-- fajne tlo -->
<body style="background-image: url(background.png);">

    <!-- header zawiera logo i użytkownika -->
    <header class="topbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <img src="logo.png" alt="logo" class="logo">
            </div>

            <button class="w-auto rounded-4 bg-light fw-bold border-0 shadow-sm px-3 py-2 btn btn-sm">
                <!-- Usunąłem domyślny fioletowy/niebieski kolor linku z Bootstrapa za pomocą klasy text-decoration-none i text-dark -->
                <a href="konto.php" class="text-decoration-none text-dark"><?php echo htmlspecialchars($imie_uzytkownika); ?></a>
            </button>
        </div>
    </header>

    <!-- main - zawiera cala oferte i mozliwosc dodawania produktow do koszyka -->
    <main class="container py-5">
        <div class="shop-wrapper mx-auto">
            <!-- BUTTON -->
            <div class="text-center my-5">
                <button class="btn summary-btn shadow-sm" id="koszyk">
                    Podsumowanie Koszyka
                </button>
            </div>

            <!-- TYTUŁ -->
            <h1 class="text-center fw-bold mb-5">Nasza Oferta</h1>
            

            <!-- Kategorie -->
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <button class="btn category-btn active" id="Wszystko">Wszystko</button>
                <button class="btn category-btn" id="Jedzenie">🥪 Jedzenie</button>
                <button class="btn category-btn" id="Napoje">🧃 Napoje</button>
                <button class="btn category-btn" id="Przekaski">🍫 Przekąski</button>
            </div>

            <!-- PRODUKTY -->
            <div class="row justify-content-center g-4" id="produktyS">
                <?php
                $sql = "SELECT produkty.id, produkty.Nazwa, produkty.Cena, produkty.Kategoria, produkty.opis FROM produkty ORDER BY produkty.Nazwa ASC";
                $wynik1 = mysqli_query($db, $sql);

                while($d = mysqli_fetch_array($wynik1)){
                    if($d['Kategoria'] == "jedzenie"){
                        $zdjecie = "jedzenie.png";
                    } else if($d['Kategoria'] == "przekąski"){
                        $zdjecie = "przekaski.png";
                    } else if($d['Kategoria'] == "napoje"){
                        $zdjecie = "napoje.png";
                    } else {
                        $zdjecie = "jedzenie.png";
                    }

                    // POPRAWKA 1: W klasie diva brakowało spacji przed kategorią, sklejało się w "mx-3jedzenie". Dodałem spację.
                    echo '<div class="col-xl-3 col-lg-4 col-md-6 mx-sm-0 mx-3 ' . $d['Kategoria'] . '">
                    <div class="card border-0 shadow-sm rounded-4 h-100 product-card">

                        <div class="product-image text-center p-4">
                            <img src="' . $zdjecie . '" alt="" class="img-fluid">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h4 class="fw-bold">' . $d['Nazwa'] . '</h4>
                            <p class="text-secondary flex-grow-1">' . $d['opis'] . '</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-3 fw-bold">' . number_format($d['Cena'], 2, ',', '') . ' zł</span>
                                <a href="dodajDoKoszyka.php?id=' . (int)$d['id'] . '" class="btn add-btn p-0 d-flex justify-content-center align-items-center text-white text-decoration-none" style="width: 40px; height: 40px; font-size: 24px; line-height: 1; border-radius: 50%;">+</a>
                            </div>
                        </div>
                    </div>
                    </div>';
                }
                ?>
            </div>

            
        </div>

        <script>
            const Wszystko = document.getElementById('Wszystko');
            const Jedzenie = document.getElementById('Jedzenie');
            const Przekaski = document.getElementById('Przekaski');
            const Napoje = document.getElementById('Napoje');
            const produktyS = document.getElementById('produktyS');

            // Podpięcie przekierowania dla guzika koszyka na dole strony
            document.getElementById('koszyk').addEventListener('click', () => {
                window.location.href = 'koszyk.php'; // Lub inna nazwa Twojego pliku z koszykiem
            });

            Wszystko.addEventListener('click',()=>{
                fetch('wyswietl.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `kategoria=${encodeURIComponent('Wszystko')}`
                }).then(res => res.text())
                .then(data => {
                    document.getElementById('produktyS').innerHTML = data;
                    UsunKlasy();
                    Wszystko.classList.add('active');
                });
            });

            Jedzenie.addEventListener('click',()=>{
                fetch('wyswietl.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `kategoria=${encodeURIComponent('Jedzenie')}`
                }).then(res => res.text())
                .then(data => {
                    document.getElementById('produktyS').innerHTML = data;
                });
                UsunKlasy();
                Jedzenie.classList.add('active');
            });

            Przekaski.addEventListener('click',()=>{
                fetch('wyswietl.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `kategoria=${encodeURIComponent('Przekąski')}`
                }).then(res => res.text())
                .then(data => {
                    document.getElementById('produktyS').innerHTML = data;
                });
                UsunKlasy();
                Przekaski.classList.add('active');
            });

            Napoje.addEventListener('click',()=>{
                fetch('wyswietl.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `kategoria=${encodeURIComponent('Napoje')}`
                }).then(res => res.text())
                .then(data => {
                    document.getElementById('produktyS').innerHTML = data;
                });
                UsunKlasy();
                Napoje.classList.add('active');
            });

            function UsunKlasy(){
                Wszystko.classList.remove('active');
                Jedzenie.classList.remove('active');
                Napoje.classList.remove('active');
                Przekaski.classList.remove('active');
            }
        </script>
    </main>
</body>
</html>