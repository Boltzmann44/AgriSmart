<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: /agrismart/main-page/login.php");
    }
    require_once "../main-page/scripts/database.php";
    if(isset($_POST['add-field-submit'])){
        $nomeCampo = $_POST["nomeCampo"];
        $coltura =  $_POST["colture"];
        $dim =  $_POST["dimensione"];
        $mesiRaccolta =  $_POST["mesiRaccolta"];

        $dataSemina = new DateTime();
        if ($mesiRaccolta > 0) {
            $dataSemina->modify("+$mesiRaccolta months");
            $dataRaccolta= $dataSemina->format('Y-m-d');
        } else {
            $dataRaccolta = null;
        }

        $sql = "INSERT INTO campi (nome_campo, coltura, dimensione, data_raccolta, id_utente) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt,$sql)) {
            mysqli_stmt_bind_param($stmt,"ssssi", $nomeCampo, $coltura, $dim, $dataRaccolta, $_SESSION["idUtente"]);
            mysqli_stmt_execute($stmt);
        }else{
            die("Something went wrong");
        }
    }
    if(isset($_POST['delete-field-submit'])){
        $idCampo = $_POST['id-campo-submit'];

        $sql = "DELETE FROM campi WHERE id_campo = ? AND id_utente = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $idCampo, $_SESSION["idUtente"]);
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
    <link rel="stylesheet" href="styles/style_fields.css">
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
                <h2>Inserimento Campo</h2>
                <form action="fields.php" id="popupForm" method="post">
                    <div class="form-field">
                        <label for="nomeCampo">Nome campo: </label>
                        <input type="text" id="nomeCampo" name="nomeCampo" required>
                    </div>
                    <div class="form-field">
                        <label for="colture">Coltura: </label>
                        <select name="colture" id="colture" required>
                            <option value="" selected disabled>Scegli coltura</option>
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
                    <div class="form-field">
                        <label for="dimensione">Dimensione (Ettari): </label>
                        <input type="text" id="dimensione" name="dimensione" required>
                    </div>
                    <div class="form-field">
                        <label for="raccolta">Raccolta tra: </label>
                        <select name="mesiRaccolta" id="raccolta" required>
                            <option value="" selected disabled>Scegli scadenza</option>
                            <option value="1">1 Mese</option>
                            <option value="2">2 Mesi</option>
                            <option value="3">3 Mesi</option>
                            <option value="6">6 Mesi</option>
                            <option value="9">9 Mesi</option>
                            <option value="12">12 Mesi</option>
                            <option value="18">18 Mesi</option>
                            <option value="24">24 Mesi</option>
                            <option value="0">Indefinito</option>
                        </select>   
                    </div>
                    <div class="form-field">
                        <input name="add-field-submit" type="submit" value="Salva">
                    </div>
                </form>
            </div>
            <div class="upbar">
                <div class="upbar-title">Campi</div>
                <div class="upbar-buttons">
                    <button id="upbar-add-btn">Aggiungi campo</button>
                    <button id="upbar-remove-btn">Rimuovi campo</button>
                </div>
            </div>
            <div class="cards">               
                <?php
                    $sql = "SELECT * FROM campi WHERE id_utente = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        require_once "scripts/icons.php";
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            $iconClass = isset($icons[$row["coltura"]]) ? $icons[$row["coltura"]] : "fa fa-leaf";

                            $dataSemina = DateTime::createFromFormat('Y-m-d', $row["data_semina"]);
                            $dataSeminaFormattata = $dataSemina->format('d/m/Y');
                            $dataRaccolta = DateTime::createFromFormat('Y-m-d', $row["data_raccolta"]);
                            $dataRaccoltaFormattata = $dataRaccolta ? $dataRaccolta->format('d/m/Y') : 'Non indicata';
                            
                            $scaduto = false;
                            if ($dataRaccolta) {
                                $dataAttuale = new DateTime();
                                $differenza = $dataAttuale->diff($dataRaccolta);
                                if ($differenza->invert) {
                                    $scaduto = true;
                                }
                            }
                            $scadutoClass = $scaduto ? 'scaduto' : '';

                            echo "<div class='card $scadutoClass'>" .
                                    "<div class='card-name'>".$row["nome_campo"] ."</div>" .
                                    "<i" . $iconClass ."></i>" .
                                    "<div class='card-content'>" .
                                        "<div class='card-label'><div class='card-title-label'>Coltura:</div>" . $row["coltura"] ."</div>" .
                                        "<div class='card-label'><div class='card-title-label'>Dimensione:</div>" . $row["dimensione"] . ' ha'. "</div>" .           
                                        "<div class='card-label'><div class='card-title-label'>Data semina:</div>" . $dataSeminaFormattata . "</div>" .  
                                        "<div class='card-label'><div class='card-title-label'>Data raccolta:</div>" . $dataRaccoltaFormattata . "</div>" .
                                        "<div class='card-label' style='margin-top:10px' ><div class='card-title-label'>Raccolta tra:</div>";
                                        if ($dataRaccolta) {        
                                            if($scaduto){
                                                echo "<div style='color:#db060a;font-weight:500;'>Scaduto</div>";
                                            }
                                            else{
                                                $mesi = $differenza->m + ($differenza->y * 12);
                                                $giorni = $differenza->d;
                                                echo "<div>" . ($mesi > 0 ? " $mesi mesi" : "") . ($giorni > 0 ? " $giorni giorni" : "") . "</div>";
                                            }
                                        }
                                        else{
                                            echo "<div> Non si sa</div>";
                                        }
                                    echo "</div>" .
                                    "</div>" .
                                    "<form action='fields.php' method='post'>" .
                                        "<input type='hidden' name='id-campo-submit' value='" . $row["id_campo"] . "'>" .
                                        "<button type='submit' name='delete-field-submit' class='card-delete disabled'>-</button>" . 
                                    "</form>" .
                                 "</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <script src="scripts/popup-cards.js"></script>
</body>
</html>
