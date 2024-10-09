<?php
    session_start();
    if(!isset($_SESSION["registration"])){
        header("Location: /agrismart/main-page/login.php");
    }
    $email= $_SESSION['email'];
    $password = $_SESSION['password'];
    
    if(isset($_POST['submit'])){
        $nomeAzienda = $_POST["nomeAzienda"];
        $provincia =  $_POST["province"];
        $comune =  $_POST["comune"];
        $cap =  $_POST["cap"];

        require_once "scripts/database.php";
        $sql = "INSERT INTO utenti (email, password, nome_azienda, comune, cap, id_provincia) VALUES ( ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
        if($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"ssssss", $email, $password, $nomeAzienda, $comune, $cap, $provincia);
            mysqli_stmt_execute($stmt);
            $idUtente = $conn->insert_id;
            session_unset();
            $_SESSION["nomeAzienda"]=$nomeAzienda;
            $_SESSION["idUtente"]=$idUtente;
            $_SESSION["user"]="yes";
            header("Location: /agrismart/dashboard/dashboard.php");
            die();
        }else{
            die("Something went wrong");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSmart - Registrati</title>
    <link rel="icon" type="image/x-icon" href="/agrismart/img/favicon.ico">
    <link rel="stylesheet" href="/agrismart/main-page/styles/style_signup-form.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <a id="link-logo" href="/agrismart/index.html">
                <img src="/agrismart/img/leaf.png">
                <h1 style="color:#52923a;">Agri</h1>
                <h1 style="color:#435e78;">Smart</h1>
            </a>
        </div>
        <div class="left">
            <div class="welcome">Facci sapere chi sei</div>
            <form action="signup-form.php" method="post">
                <input type="text" class="form-field form-namefarm" name="nomeAzienda" placeholder="Nome azienda" required>
                <select name="regioni" id="regioni" class="form-field form-regioni" required>
                    <option value="" selected disabled>Regione</option>
                    <option value="1">Abruzzo</option>
                    <option value="2">Basilicata</option>
                    <option value="3">Calabria</option>
                    <option value="4">Campania</option>
                    <option value="5">Emilia Romagna</option>
                    <option value="6">Friuli-Venezia Giulia</option>
                    <option value="7">Lazio</option>
                    <option value="8">Liguria</option>
                    <option value="9">Lombardia</option>
                    <option value="10">Marche</option>
                    <option value="11">Molise</option>
                    <option value="12">Piemonte</option>
                    <option value="13">Puglia</option>
                    <option value="14">Sardegna</option>
                    <option value="15">Sicilia</option>
                    <option value="16">Toscana</option>
                    <option value="17">Trentino-Alto Adige</option>
                    <option value="18">Umbria</option>
                    <option value="19">Valle d'Aosta</option>
                    <option value="20">Veneto</option>
                  </select>
                <select name="province" id="province" class="form-field form-province" required>
                    <option value="" selected disabled>Provincia</option>
                </select>   
                <input type="text" class="form-field form-city" name="comune" placeholder="Città" required>
                <input type="text" class="form-field form-cap" name="cap" placeholder="Codice Postale (CAP)" required>
                <input type="submit" class="setup-btn" name="submit" value="Iniziamo"></input>
            </form>
        </div>
        <div class="right">
            <video id="background-video" autoplay loop muted>
                <source src="/agrismart/img/prova3.mp4" type="video/mp4">
            </video>
        </div>
    </div>
    <script src="/agrismart/main-page/scripts/signup-form-script.js"></script>
</body>
</html>