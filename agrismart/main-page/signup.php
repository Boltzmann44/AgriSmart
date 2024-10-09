<?php
    if(isset($_POST['submit'])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat_password"];

        $passwordHash= password_hash($password,PASSWORD_BCRYPT);

        $errors = array();
        if(strlen($password)<8){
            array_push($errors,"La password deve essere lunga almeno 8 caratteri");
        }
        if($password!==$passwordRepeat){
            array_push($errors,"Le password non corrispondono");
        }
        require_once "scripts/database.php";
        $sql = "SELECT * FROM utenti WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt,$sql)) {
            mysqli_stmt_bind_param($stmt,"s", $email);
            mysqli_stmt_execute($stmt);
        }else{
            die("Something went wrong");
        }
        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);
        if($rowCount>0){
            array_push($errors,"Utente già registrato!");
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
    <link rel="stylesheet" href="/agrismart/main-page/styles/style_signup.css">
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
            <div class="welcome">Benvenuto!</div>
            <div class="subtitle">
                Hai già un account? <a href="login.php" style="color:#666">Accedi qui</a>
            </div>
            <form action="signup.php" method="POST">
                <input type="email" class="form-field form-email" name="email" placeholder="Email" required>
                <input type="password" class="form-field form-password" name="password" placeholder="Password" required>
                <input type="password" class="form-field form-password" name="repeat_password" placeholder="Ripeti password" required>
                <input type="submit" class="login-btn" name="submit" id="submit-btn" value="Registrati"></input>
            </form>
            <?php
                if(isset($_POST['submit'])){
                    if(count($errors)>0){
                        foreach($errors as $error){
                            echo "<div class='error'>$error</div>";
                        }
                    }
                    if(count($errors)===0){
                        session_start();
                        $_SESSION['email']=$email;
                        $_SESSION['password']=$passwordHash;
                        $_SESSION["registration"]="yes";
                        echo "<div class='success'>
                                <div class='success-text'>Ci siamo quasi!</div>
                                <a href='signup-form.php'><button class='success-btn'>Avanti</button></a>
                                <script> 
                                    var button = document.getElementById('submit-btn');
                                    button.classList.add('disabled');
                                </script>
                            </div>";
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