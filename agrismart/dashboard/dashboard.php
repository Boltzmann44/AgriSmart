<?php
    session_start();
    if(!isset($_SESSION["user"])){
        header("Location: /agrismart/main-page/login.php");
    }
    require_once "../main-page/scripts/database.php";
    require_once "scripts/icons.php";
    $colture = [
        "Mais" => 0,
        "Pomodori" => 0,
        "Grano" => 0,
        "Mele" => 0,
        "Arance" => 0,
        "Patate" => 0,
        "Uva" => 0,
        "Pere" => 0,
        "Peperoni" => 0,
        "Piselli" => 0,
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agrismart - Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/agrismart/img/favicon.ico">
    <link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css" >
    <link rel="stylesheet" href="styles/style_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="upbar">
                <div class="cards upbar-cards">
                    <div class="card" id="temp-card">
                        <div class="card-content">
                            <h3 class="card-name">Temperatura:</h3>
                            <div class="number" id="temp-num">
                                <?php                            
                                    $sql = "SELECT utenti.comune, utenti.cap, province.nome_provincia 
                                            FROM utenti 
                                            JOIN province ON utenti.id_provincia = province.id_provincia 
                                            WHERE utenti.id_utente = " . $_SESSION['idUtente'];
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $comune = $row['comune'];
                                        $cap = $row['cap'];
                                        $provincia = $row['nome_provincia'];
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="icon-box">
                            <i class="fa fa-cloud-sun"></i>
                        </div>
                    </div>
                    <div class="card" id="exp-card">
                        <div class="card-content">
                            <h3 class="card-name">In scadenza:</h3>
                            <div class="exp-num" id="exp-num">
                                <?php
                                    $sql = "SELECT * FROM campi WHERE id_utente = ?";
                                    $stmt = mysqli_stmt_init($conn);
                                    $campo_prossimo = null;
                                    $total_ettari = 0;
                                    if (mysqli_stmt_prepare($stmt, $sql)) {
                                        mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $coltura = $row["coltura"];
                                                $ettari = (float)$row["dimensione"];
                                                if (array_key_exists($coltura, $colture)) {
                                                    $colture[$coltura] += $ettari; // Aggiungi gli ettari per la coltura
                                                    $total_ettari += $ettari; // Aggiorna il totale degli ettari
                                                }
                                                // Confronta le date di raccolta e verifica se è prossima                                
                                                $data_raccolta = new DateTime($row["data_raccolta"]);
                                                $data_corrente = new DateTime();
                                        
                                                // Controlla se il campo è già passato o è prossimo alla scadenza
                                                if ($data_raccolta >= $data_corrente) {
                                                    if ($campo_prossimo === null || $data_raccolta < new DateTime($campo_prossimo["data_raccolta"])) {
                                                        $campo_prossimo = $row; // Aggiorna il campo più prossimo
                                                        $iconClass = isset($icons[$row["coltura"]]) ? $icons[$row["coltura"]] : "fa fa-leaf";
                                                    }
                                                }
                                            }
                                            if ($campo_prossimo) {
                                                echo $campo_prossimo["nome_campo"];
                                            } else {
                                                echo "Nessun campo";
                                            }
                                        } else {
                                            echo "Nessun campo";
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="icon-box">
                            <i <?php if($campo_prossimo){echo $iconClass;}?>></i>
                        </div>     
                    </div>
                    <div class="card" id="colture-card">
                        <div class="card-content">
                            <h3 class="card-name">Percentuale colture:</h3>
                            <?php
                            $percentuali = [];
                            if ($total_ettari > 0) {
                                foreach ($colture as $nome_coltura => $totale_ettari) {
                                    $percentuali[$nome_coltura] = ($totale_ettari / $total_ettari) * 100;
                                }
                            }
                            $valoriPerc = json_encode(array_values($percentuali));
                            ?>
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bar">
                <div class="fields-bar">
                    <div class="title">Campi</div>
                    <div class="slider">
                        <div class="cards fields-cards">
                        <?php
                        $sql = "SELECT * FROM campi WHERE id_utente = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            require_once "scripts/icons.php";
                            $numRow=0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $numRow++;
                                $iconClass = isset($icons[$row["coltura"]]) ? $icons[$row["coltura"]] : "fa fa-leaf";

                                $dataSemina = DateTime::createFromFormat('Y-m-d', $row["data_semina"]);
                                $dataRaccolta = DateTime::createFromFormat('Y-m-d', $row["data_raccolta"]);
                                
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
                                    "</div>";
                            }
                            if($numRow>0){
                                echo "<button class='prev'>&#10094;</button><button class='next'>&#10095;</button>";
                            }
                        }
                    ?>
                        </div>
                    </div>
                </div>
                <div class="equip-bar">
                    <div class="title">Equipaggiamento</div>
                    <div class="slider">
                    <div class="cards equip-cards">
                            <?php 
                                $sql = "SELECT * FROM veicoli WHERE id_utente = ?";
                                $stmt = mysqli_stmt_init($conn);
                                if (mysqli_stmt_prepare($stmt, $sql)) {
                                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["idUtente"]);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $numRow=0;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    $numRow++;
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
                                            </div></div>";
                                    }
                                    if($numRow>0){
                                        echo "<button class='prev'>&#10094;</button><button class='next'>&#10095;</button>";
                                    }
                                }
                            ?>    
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script>
        var city = <?php echo "'$comune'"; ?>;      
    </script>
    <script src="scripts/temperature.js"></script>
    <script src="scripts/dashboard.js"></script>
    <script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const data = {
        labels: [''],
        datasets: [
            <?php
            $coltureColors = [
                "Mais" => "#D9B510",
                "Pomodori" => "#DF0D0D",
                "Grano" => "#ebc200",
                "Mele" => "#BE391F",
                "Arance" => "#DF8018",
                "Patate" => "#BEA21F",
                "Uva" => "#530C51",
                "Pere" => "#BEA21F",
                "Peperoni" => "#DF0D0D",
                "Piselli" => "#52923a"
            ];
            foreach ($percentuali as $coltura => $percentuale) {
                if ($percentuale > 0) {
                    $colore = isset($coltureColors[$coltura]) ? $coltureColors[$coltura] : sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                    echo "{
                        label: '$coltura',
                        data: [$percentuale],
                        backgroundColor: '$colore',
                        borderRadius: 15
                    },";
                }
            }
            ?>
        ]
    };

    const myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false // Disattiva la legenda
                },
                tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        // Ritorna il valore percentuale con il simbolo di percentuale
                        const percent = tooltipItem.raw.toFixed(1);
                        return ' ' +tooltipItem.dataset.label + ': ' + percent + '%';
                    }
                }
            }
            },
            scales: {
                x: {
                    stacked: true,
                    max: 100, // Totale ettari per normalizzare
                    ticks: {
                        display: false // Nasconde i valori dell'asse X
                    },
                    grid: {
                        display: false, // Disattiva la griglia sull'asse X
                        borderWidth: 0
                    }
                },
                y: {
                    stacked: true,
                    grid: {
                        display: false, // Disattiva la griglia sull'asse X
                        borderWidth: 0
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>
</body>
</html>