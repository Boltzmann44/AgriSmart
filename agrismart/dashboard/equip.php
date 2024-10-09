<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: /agrismart/main-page/login.php");
    }
    $error=0;
    require_once "../main-page/scripts/database.php";
    if(isset($_POST['add-equip-submit'])){
        $marchio = $_POST["marchioVeicolo"];
        $modello =  $_POST["modelloVeicolo"];
        $potenza =  $_POST["potenzaVeicolo"];
        $dataAcquisto =  $_POST["dataAcquisto"];
        $dataRevisione =  $_POST["dataRevisione"];
        require_once "scripts/upload.php";
        if($error==0 OR $error==4){
            if($error==0){
                $percorso=$fileDestination;
            }
            else{
                $percorso = "./img-users/default.jpg";
            }
            $sql = "INSERT INTO veicoli (marchio, modello, potenza, data_acquisto, data_revisione, percorso_img ,id_utente) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt,$sql)) {
                mysqli_stmt_bind_param($stmt,"ssssssi", $marchio, $modello, $potenza, $dataAcquisto, $dataRevisione, $percorso, $_SESSION["idUtente"]);
                mysqli_stmt_execute($stmt);
            }else{
                die("Something went wrong");
            }
        }
    }
    if(isset($_POST['delete-equip-submit'])){
        $idVeicolo = $_POST['id-equip-submit'];

        $sql = "DELETE FROM veicoli WHERE id_veicolo = ? AND id_utente = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $idVeicolo, $_SESSION["idUtente"]);
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
    <link rel="stylesheet" href="styles/style_equip.css">
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
                <h2>Inserimento Veicolo</h2>
                <form action="equip.php" method="post" id="popupForm" enctype="multipart/form-data">
                    <div class="form-field">
                        <label for="marchioVeicolo">Marchio: </label>
                        <input type="text" id="marchioVeicolo" name="marchioVeicolo" required>
                    </div>
                    <div class="form-field">
                        <label for="modelloVeicolo">Modello:</label>
                        <input type="text" id="modelloVeicolo" name="modelloVeicolo" required>
                    </div>
                    <div class="form-field">
                        <label for="potenzaVeicolo">Potenza (kW):</label>
                        <input type="text" id="potenzaVeicolo" name="potenzaVeicolo" required>
                    </div>
                    <div class="form-field">
                        <label for="dataAcquisto">Data Acquisto:</label>
                        <input type="date" id="dataAcquisto" name="dataAcquisto" required>
                    </div>
                    <div class="form-field">
                        <label for="dataRevisione">Data prossima revisione:</label>
                        <input type="date" id="dataRevisione" name="dataRevisione" required>
                    </div>
                    <div class="form-field" id="popup-photo-field">
                        <label for="popup-photo-input">Foto:</label>
                        <input type="file" id="popup-photo-input" name="popup-photo-input" accept="image/*">
                    </div>
                    <div class="form-field">
                        <input type="submit" name="add-equip-submit" value="Salva">
                    </div>
                </form>
            </div>
            <div class="upbar">
                <div class="upbar-title">Equipaggiamento</div>
                <div class="upbar-buttons">
                    <button id="upbar-add-btn">Aggiungi veicolo</button>
                    <button id="upbar-remove-btn">Rimuovi veicolo</button>
                </div>
            </div>
            <div class="cards">
                <?php
                    $sql = "SELECT * FROM veicoli WHERE id_utente = ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (mysqli_stmt_prepare($stmt, $sql)) {
                                mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                
                                while ($row = mysqli_fetch_assoc($result)) {
                                echo    "<div class='card'>
                                            <div class='card-first-part'>
                                                <div class='card-content'>
                                                    <div class='card-text'>
                                                        <div class='card-title'>Veicolo:</div>
                                                        <div class='card-label'>Marchio:</div>
                                                        <div>" . $row['marchio'] ."</div>
                                                        <div class='card-label'>Modello:</div>
                                                        <div>" . $row['modello'] ."</div>
                                                    </div>
                                                    <img class='card-photo' src='" . $row['percorso_img'] ."' alt=''>
                                                </div>
                                                <form action='equip.php' method='post'>" .
                                                    "<input type='hidden' name='id-equip-submit' value='" . $row["id_veicolo"] . "'>" .
                                                    "<button type='submit' name='delete-equip-submit' class='card-delete disabled'>-</button>" . 
                                                "</form>
                                            </div>";
                                echo     "<div class='card-second-part disabled'>" .   
                                            "<div class='card-right-content'>
                                                        <div>
                                                            <div class='card-label'>Potenza:</div>
                                                            <div>" . $row['potenza'] . " Kw" . "</div>
                                                            <div class='card-label' style='margin-top:10px;'>Data revisione:</div>
                                                            <div>" . $row['data_revisione'] ."</div>
                                                        </div>
                                                        <div style='text-align: right;'>
                                                            <div class='card-label'>Data acquisto:</div>
                                                            <div>" . $row['data_acquisto'] ."</div>
                                                        </div>
                                                    </div>
                                             </div>" .
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
                    <form action='equip.php'>
                        <button id="delete-error" class="delete-error">x</button>
                    </form>
            </div>
            <button id='info-btn'><i class='fa fa-info'></i></button>
        </div>
    </div> 
    <script src="scripts/popup-cards.js"></script>
    <script>
        const infoBtn = document.getElementById('info-btn');
        const cardSecond = document.getElementsByClassName('card-second-part'); 
        infoBtn.addEventListener('click', function() {
        Array.from(cardSecond).forEach(btn => {
            btn.classList.toggle('disabled');
        });
    });
    </script>
</body>
</html>