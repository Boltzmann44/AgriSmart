<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: /agrismart/main-page/login.php");
    }
    $error=0;
    require_once "../main-page/scripts/database.php";
    if(isset($_POST['add-product-submit'])){
        $nomeProdotto = $_POST["nomeProdotto"];
        $descProdotto = $_POST['descProdotto'];
        $qnt = $_POST['qnt'];
        $prezzo = $_POST['prezzo'];
        $unit = $_POST['unit'];
        $tipoPrezzo = $_POST['unit2'];
        require_once "scripts/upload.php";
        $tipoQnt = 1;
        if($error==0 OR $error==4){
            if($error==0){
                $percorso=$fileDestination;
            }
            else{
                $percorso = "./img-users/default.jpg";
            }
            switch($unit){
                case "Kg":
                    $pesoEff = $qnt;
                    break;
                case "Q":
                    $pesoEff = $qnt*100;
                    break;
                case "T":
                    $pesoEff = $qnt*1000;
                    break;
                case "unit":
                    $pesoEff = $qnt;
                    $tipoQnt = 0;
                    break;
            }
            $sql = "INSERT INTO prodotti_market (nome_prodotto, descrizione_prodotto, qnt_prodotto, prezzo,tipo_qnt,tipo_prezzo,percorso_img, id_utente) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt,$sql)) {
                mysqli_stmt_bind_param($stmt,"ssiiiisi", $nomeProdotto, $descProdotto, $pesoEff , $prezzo, $tipoQnt, $tipoPrezzo, $percorso ,$_SESSION["idUtente"]);
                mysqli_stmt_execute($stmt);
            }
            else{
                die("Something went wrong");
            }
        }
    }
    if(isset($_POST['delete-product-submit'])){
        $idProdotto = $_POST['id-product-submit'];

        $sql = "DELETE FROM prodotti_market WHERE id_prodotto = ? AND id_utente = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $idProdotto, $_SESSION["idUtente"]);
            mysqli_stmt_execute($stmt);
        } 
        else {
            die("Something went wrong");
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
    <link rel="stylesheet" href="styles/style_market.css">
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
        <div class="main">
            <div class="popup disabled" id="popup">
                <h2>Inserimento prodotto</h2>
                <form action="market.php" id="popupForm" method="post" enctype="multipart/form-data">
                    <div class="form-field">
                        <label for="nomeProdotto">Nome prodotto: </label>
                        <input type="text" id="nomeProdotto" name="nomeProdotto" required>
                    </div>
                    <div class="form-field">
                        <label for="descProdotto">Descrizione (150 carat. max): </label>
                        <textarea name="descProdotto" class="descProdotto" rows="4" cols="30" maxlength="150"></textarea>
                    </div>
                    <div class="form-columns">
                        <div class="form-field">
                            <label for="qnt">Quantità:</label>
                            <input type="text" id="qnt" name="qnt" required>
                        </div>
                        <div class="form-field">
                            <label for="unit">In:</label>
                            <select name="unit" id="unit" required>
                                <option value="unit">Pezzi</option>
                                <option value="Kg">Kg</option>
                                <option value="Q">Q</option>
                                <option value="T">T</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-columns">
                        <div class="form-field">
                            <label for="prezzo">Prezzo (Eur):</label>
                            <input type="text" id="prezzo" name="prezzo" required>
                        </div>
                        <div class="form-field">
                            <label for="unit2">Al:</label>
                            <select name="unit2" id="unit2" required>
                                <option value="0">Pezzo</option>
                                <option value="1">Kg</option>
                                <option value="2">Q</option>
                                <option value="3">T</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-field" id="popup-photo-field">
                        <label for="popup-photo-input">Foto:</label>
                        <input type="file" id="popup-photo-input" name="popup-photo-input" accept="image/*">
                    </div>
                    <div class="form-field">
                        <input name="add-product-submit" type="submit" value="Salva">
                    </div>
                </form>
            </div>
            <div class="upbar">
                <div class="upbar-title">Prodotti in vendita</div>
                <div class="upbar-buttons">
                    <button id="upbar-add-btn">Aggiungi prodotto</button>
                    <button id="upbar-remove-btn">Rimuovi prodotto</button>
                </div>
            </div>
            <div class="cards">               
                <?php
                    $sql = "SELECT * FROM prodotti_market WHERE id_utente = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='card'>" .
                                    "<div class='card-name'>". $row["nome_prodotto"] ."</div>" .
                                    "<img class='card-photo' src='" . $row['percorso_img'] ."'>" .
                                    "<div class='card-label' style='margin-top:20px;'>Descrizione:</div>" .
                                    "<div class='card-label-text'>" . $row['descrizione_prodotto'] . "</div>" .
                                    
                                    "<div class='card-columns'>" .
                                        "<div class='first-column'>" .
                                            "<div class='card-label' style='margin-top:15px;'>Quantità:</div>";
                                                if($row['tipo_qnt'] == 0){
                                                    echo "<div>" . $row['qnt_prodotto']. " pz.</div>";
                                                }
                                                else{
                                                    $t = intdiv($row['qnt_prodotto'], 1000);
                                                    $q = intdiv($row['qnt_prodotto'] % 1000, 100);
                                                    $kg = $row['qnt_prodotto'] % 100;
                                                    echo "<div>";
                                                    echo  ($t > 0 ? " $t T" : "") . ($t>0 & $kg>0 & $q==0? "," : "").($t>0 & $q>0 ? "," : "") . ($q > 0 ? " $q Q" : "") . ($q>0 & $kg>0 ? "," : "") . ($kg > 0 ? " $kg Kg" : "") . (($q==0 & $kg==0 & $t==0 ? "Vuoto" : ""));
                                                    echo "</div>";
                                                }
                            echo            "</div>
                                                <div class='second-column'>
                                                    <div class='card-label' style='margin-top:15px;'>Prezzo:</div>
                                                    <div>" . $row['prezzo']. " Eur";
                                                    switch($row['tipo_prezzo']){
                                                        case 0:
                                                            echo " (al pz.)";
                                                            break;
                                                        case 1:
                                                            echo " (al Kg)";
                                                            break;
                                                        case 2:
                                                            echo " (al Q)";
                                                            break; 
                                                        case 3:
                                                            echo " (alla T)" ;
                                                            break;
                                                    }
                            echo                    "</div>
                                                </div>";
                            echo    "</div>";
                            echo    "<form action='market.php' method='post'>" .
                                        "<input type='hidden' name='id-product-submit' value='" . $row["id_prodotto"] . "'>" .
                                        "<button type='submit' name='delete-product-submit' class='card-delete disabled'>-</button>" . 
                                    "</form>" .
                                 "</div>";
                        }
                    }
                ?>
            </div>
            <div class="card error-card <?php if ($error==0) echo 'disabled'; ?>">
                    <div>
                        <?php
                            switch($error){
                                case 1:
                                    echo "Errore durante il caricamento del file";
                                    break;
                                case 2:
                                    echo "Il file è troppo grande. Dimensione massima consentita: 5Mb";
                                    break;   
                                case 3:
                                    echo "Tipo di file non valido. Solo JPG, JPEG, PNG sono consentiti.";
                                    break; 
                                case 4:
                                    echo "Nessun file caricato";
                                    break;
                            }
                        ?>
                    </div>
                    <form action='market.php'>
                        <button id="delete-error" class="delete-error">x</button>
                    </form>
            </div>
        </div>
    </div>
    <script src="scripts/popup-cards.js"></script>
</body>
</html>