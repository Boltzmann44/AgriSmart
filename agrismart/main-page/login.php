<?php
    session_start();
    if(isset($_SESSION["user"])){
        header("Location: /agrismart/dashboard/dashboard.php");
    }

    if(isset($_POST['login'])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        require_once "scripts/database.php";
        $sql = "SELECT * FROM utenti WHERE email = '$email'";
        $result = mysqli_query($conn,$sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriSmart - Login</title>
    <link rel="icon" type="image/x-icon" href="/agrismart/img/favicon.ico">
    <link rel="stylesheet" href="/agrismart/main-page/styles/style_login.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <a id="link-logo" href="/agrismart/index.html">
                <img src="/agrismart/img/leaf.png">
                <h1 style="color: #52923a;">Agri</h1>
                <h1 style="color: #435e78;">Smart</h1>
            </a>
        </div>
        <div class="left">
            <div class="welcome">Bentornato!</div>
            <div class="subtitle">
                Non hai un account? <a href="signup.php" style="color:#666">Registrati qui</a>
            </div>
            <form action="login.php" method="post">
                <input type="email" class="form-field form-email" name="email" placeholder="Email" required>
                <input type="password" class="form-field form-password" name="password" placeholder="Password" required>
                <input type="submit" class="login-btn" name="login" value="Accedi"></input>
            </form>
            <?php
                if(isset($_POST['login'])){
                    if($user){
                        if(password_verify($password, $user["password"])){
                            session_start();
                            $_SESSION["user"]="yes";
                            $_SESSION['nomeAzienda']= $user['nome_azienda'];
                            $_SESSION['idUtente']= $user['id_utente'];
                            header("Location: /agrismart/dashboard/dashboard.php");
                            die();
                        }
                        else{
                            echo "<div class='error'>La password non è corretta!</div>";
                        }
                    }
                    else{
                        echo "<div class='error'>Non esiste questo utente!</div>";
                    }
                }
            ?>
        </div>
        <div class="right">
            <video id="background-video" autoplay loop muted>
                <source src="/agrismart/img/prova3.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</body>
</html>
