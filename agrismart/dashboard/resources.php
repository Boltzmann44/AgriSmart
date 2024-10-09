<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: /agrismart/main-page/login.php");
    }
    require_once "../main-page/scripts/database.php";
    $error=0;
    if(isset($_POST['add-res-submit'])){   
        $risorsa = $_POST["risorsa"];

        $sql = "SELECT * FROM risorse WHERE id_utente = ? AND nome_risorsa = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt,$sql)) {
            mysqli_stmt_bind_param($stmt,"is", $_SESSION['idUtente'], $risorsa);
            mysqli_stmt_execute($stmt);
        }else{
            die("Something went wrong");
        }
        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);

        if($rowCount>0){
            $error=1;
        }
        else{
            $peso =  $_POST["peso"];
            $unit =  $_POST["unit"];

            $pesoEff = 0;
            switch($unit){
                case "Kg":
                    $pesoEff = $peso;
                    break;
                case "Q":
                    $pesoEff = $peso*100;
                    break;
                case "T":
                    $pesoEff = $peso*1000;
                    break;
            }
            $sql = "INSERT INTO risorse (nome_risorsa, qnt, id_utente) VALUES ( ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt,$sql)) {
                mysqli_stmt_bind_param($stmt,"sii", $risorsa,$pesoEff, $_SESSION["idUtente"]);
                mysqli_stmt_execute($stmt);
            }
            else{
                die("Something went wrong");
            }
        }
    }
    if(isset($_POST['delete-res-submit'])){
        $idRisorsa = $_POST['id-res-submit'];

        $sql = "DELETE FROM risorse WHERE id_risorsa = ? AND id_utente = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $idRisorsa, $_SESSION["idUtente"]);
            mysqli_stmt_execute($stmt);
        } 
        else {
            die("Something went wrong");
        }
    }
    if(isset($_POST['add-qnt-submit'])){
        $idRisorsa = $_POST['id-res-submit'];
        $peso = $_POST['peso'];
        $unit = $_POST['unit'];

        if($peso>0){
            $pesoEff = 0;
            switch($unit){
                case "Kg":
                    $pesoEff = $peso;
                    break;
                case "Q":
                    $pesoEff = $peso*100;
                    break;
                case "T":
                    $pesoEff = $peso*1000;
                break;
            }

            $sql = "UPDATE risorse SET qnt = qnt + ? WHERE id_risorsa = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ii", $pesoEff, $idRisorsa);
                mysqli_stmt_execute($stmt);
            } 
            else{
                die("Something went wrong during the update");
            }
        }
    }
    if(isset($_POST['rmv-qnt-submit'])){
        $idRisorsa = $_POST['id-res-submit'];
        $peso = $_POST['peso'];
        $unit = $_POST['unit'];

        if($peso>0){
            $pesoEff = 0;
            switch($unit){
                case "Kg":
                    $pesoEff = $peso;
                    break;
                case "Q":
                    $pesoEff = $peso*100;
                    break;
                case "T":
                    $pesoEff = $peso*1000;
                    break;
            }

            // Verifica se la quantità attuale è sufficiente per la sottrazione
            $sql = "SELECT qnt FROM risorse WHERE id_risorsa = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $idRisorsa);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);

                // Verifica se la sottrazione porterebbe il valore sotto zero
                if ($row['qnt'] >= $pesoEff) {
                    $sql = "UPDATE risorse SET qnt = qnt - ? WHERE id_risorsa = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ii", $pesoEff, $idRisorsa);
                        mysqli_stmt_execute($stmt);
                    } else {
                        die("Something went wrong during the update");
                    }
                } else {
                    $error = 2;
                }
            } else {
                die("Something went wrong");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agrismart - Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/agrismart/img/favicon.ico">
    <link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" >
    <link rel="stylesheet" href="styles/style_resources.css">
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="topbar-logo">
                <a id="topbar-link-logo" href="dashboard.php">
                    <img src="/agrismart/img/leaf.png">
                    <h2 style="color: #52923a;">Agri</h2>
                    <h2 style="color: #435e78;">Smart</h2>
                </a>
            </div>
            <div class="topbar-session"> 
                <h3 id="topbar-farm-name"><?php echo $_SESSION['nomeAzienda']?></h3>   
                <a href="scripts/logout.php"><button id="topbar-logout-btn"><i class='fa-regular fa-arrow-right-from-bracket'></i></button></a>
            </div>
        </div>
        <div class="sidebar">
            <div class="sidebar-button" id="sidebar-dashboard-button">
                <a href="dashboard.php">
                    <i class="fa fa-chart-pie"></i>
                    <div>Dashboard</div>
                </a>
            </div>
            <div class="sidebar-button" id="sidebar-fields-button">
                <a href="fields.php">
                    <i class="fa fa-layer-group"></i>
                    <div>Campi</div>
                </a>
            </div>
            <div class="sidebar-button" id="sidebar-resources-button">
                <a href="resources.php">
                    <i class="fa fa-building-wheat"></i>
                    <div>Risorse</div>
                </a>
            </div>
            <div class="sidebar-button" id="sidebar-equip-button">
                <a href="equip.php">
                    <i class="fa fa-garage"></i>
                    <div>Equipaggiamento</div>
                </a>
            </div>
            <div class="sidebar-button" id="sidebar-market-button">
                <a href="market.php">
                    <i class="fa fa-shop"></i>
                    <div>Marketplace</div>
                </a>
            </div>
            </div>
        </div>
        <div class="main">
            <div class="popup disabled" id="popup">
                <h2>Inserimento Risorsa</h2>
                <form action="resources.php" id="popupForm" method="post">
                    <div class="form-field">
                        <label for="risorsa">Risorsa: </label>
                        <select name="risorsa" id="risorsa" required>
                            <option value="" selected disabled>Scegli risorsa</option>
                            <option value="Mais">Mais</option>
                            <option value="Pomodori">Pomodori</option>
                            <option value="Grano">Grano</option>
                            <option value="Mele">Mele</option>
                            <option value="Arance">Arance</option>
                            <option value="Patate">Patate</option>
                            <option value="Uva">Uva</option>
                            <option value="Pere">Pere</option>
                            <option value="Peperoni">Peperoni</option>
                            <option value="Piselli">Piselli</option>
                        </select>   
                    </div>
                    <div class="form-columns">
                        <div class="form-field">
                            <label for="peso">Peso:</label>
                            <input type="text" id="peso" name="peso" required>
                        </div>
                        <div class="form-field">
                            <label for="unit">Unità:</label>
                            <select name="unit" id="unit" required>
                                <option value="Kg">Kg</option>
                                <option value="Q">Q</option>
                                <option value="T">T</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-field">
                        <input type="submit" name="add-res-submit" id="add-res-submit" value="Salva">
                    </div>
                </form>
            </div>
            <div class="upbar">
                <div class="upbar-title">Risorse</div>
                <div class="upbar-buttons">
                    <button id="upbar-add-btn">Aggiungi risorsa</button>
                    <button id="upbar-remove-btn">Rimuovi risorsa</button>
                </div>
            </div>
            <div class="first-card">
                <div class="name">Risorsa:</div>
                <div class="quantity">Quantità:</div>
                <div class="act">Azioni:</div>
            </div>
            <?php       
                $sql = "SELECT * FROM risorse WHERE id_utente = ?";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $rowCount = mysqli_num_rows($result);
                    $i=1;

                    require_once "scripts/icons.php";
                    if($rowCount>0){
                        echo "<div class='cards'>";
                    }
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='card'>" .
                                "<div class='card-name'>" . $row['nome_risorsa'] . "</div>" .
                                "<div class='card-quantity'>";
                                    $t = intdiv($row['qnt'], 1000);
                                    $q = intdiv($row['qnt'] % 1000, 100);
                                    $kg = $row['qnt'] % 100;
                                    echo  ($t > 0 ? " $t T" : "") . ($t>0 & $kg>0 & $q==0? "," : "").($t>0 & $q>0 ? "," : "") . ($q > 0 ? " $q Q" : "") . ($q>0 & $kg>0 ? "," : "") . ($kg > 0 ? " $kg Kg" : "") . (($q==0 & $kg==0 & $t==0 ? "Vuoto" : ""));
                        echo    "</div>" .
                                "<div class='card-actions'>" .
                                "<form action='resources.php' method='post'>" .
                                    "<input type='text' id='peso' name='peso' required>" .
                                    "<select name='unit' id='unit' required>
                                        <option value='Kg'>Kg</option>
                                        <option value='Q'>Q</option>
                                        <option value='T'>T</option>
                                    </select>" .
                                    "<input type='hidden' name='id-res-submit' value='" . $row["id_risorsa"] . "'>" .
                                    "<button type='submit' name='add-qnt-submit' id='card-add-btn'>  +  </button>" .
                                    "<button type='submit' name='rmv-qnt-submit'id='card-remove-btn'>  -  </button>" .
                                "</form>" .
                                "</div>";
                        echo "<form action='resources.php' method='post'>" .
                                    "<input type='hidden' name='id-res-submit' value='" . $row["id_risorsa"] . "'>" .
                                    "<button type='submit' name='delete-res-submit' class='card-delete disabled'>-</button>" . 
                              "</form>
                            </div>";
                        if($rowCount>$i){
                            echo "<hr class='card-separator'>" ;
                        }
                        $i++;
                    }
                    if($rowCount>0){
                        echo "</div>";
                    }
                }
            ?>
            <div class="cards error-card <?php if ($error==0) echo 'disabled'; ?>">
                    <div class="card-content">
                        <?php
                            if($error==1)
                                echo "Questa risorsa è già presente!";
                            else
                                echo "Quantità troppo elevata da sottrarre!";
                        ?>
                    </div>
                    <form action='resources.php'>
                        <button id="delete-error" class="delete-error">x</button>
                    </form>
            </div>
        </div>
    </div>
    <script src="scripts/popup-cards.js"></script>
</body>
</html>