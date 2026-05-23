<?php
session_start();
$komunikat = "";
$klasa_komunikatu = "d-none";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['zaloguj'])) {
    
    $db = mysqli_connect('localhost', 'root', '', 'zegowskaszama');

    if (!$db) {
        die("Błąd połączenia z bazą: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $haslo = trim($_POST['password']);

    if (empty($email) || empty($haslo)) {
        $komunikat = "Uzupełnij wszystkie pola!";
        $klasa_komunikatu = "alert-danger";
    } else {
        $sql = "SELECT * FROM użytkownicy WHERE Email = '$email'";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($haslo, $user['Haslo'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_imie'] = $user['Imie'];

                $komunikat = "Zalogowano pomyślnie!";
                $klasa_komunikatu = "alert-success";
                header("refresh:1; url=sklep.php");
            } else {
                $komunikat = "Nieprawidłowe hasło!";
                $klasa_komunikatu = "alert-danger";
            }
        } else {
            $komunikat = "Nie znaleziono użytkownika o podanym adresie e-mail!";
            $klasa_komunikatu = "alert-danger";
        }
    }
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styl.css
    ">
</head>
<body style="background-image: url(background.png);">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        
        <div class="bg-white border rounded-4 shadow-lg p-5 text-center" style="max-width: 400px; width: 100%;">
            
            <form action="logowanie.php" method="post">
                <div class="mb-4">
                    <img src="logo.png" alt="logo" class="img-fluid" style="max-height: 80px;">
                </div>
                
                <div class="mb-4">
                    <h2 class="fw-bold">Zaloguj się</h2>
                </div>
                
                <div class="mb-3">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Adres e-mail..." id="email_logowanie">
                </div>
                
                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Hasło" id="haslo_logowanie">
                </div>

                <div class="text-end mb-4">
                    <a id="zapomniales" class="text-decoration-none text-muted small">Zapomniałeś hasła?</a>
                </div>
                
                <div class="alert <?php echo $klasa_komunikatu; ?>">
                    <?php echo $komunikat; ?>
                </div>

                <input type="submit" name="zaloguj" class="btn btn-success btn-lg w-100 mb-3" value="Zaloguj się" style="background-color: #8db63f; border: none;" id="login_button">
                </input>

                <div class="small">
                    Nie masz konta? <a href="rejestracja.html"  class="text-success text-decoration-none fw-bold">Zarejestruj się</a>
                </div>
            </form>
            
        </div>
    </div>

    <script>
        const zapomniales = document.getElementById('zapomniales');

        zapomniales.addEventListener('click',()=>{
            alert("To kiepso :(");
        })
    </script>
</body>
</html>