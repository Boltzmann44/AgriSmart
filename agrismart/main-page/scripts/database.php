<?php
    $hostname = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "agrismart_db";
    $conn = mysqli_connect($hostname,$dbusername,$dbpassword,$dbname);
    if(!$conn){
        die("Qualcosa è andato storto");
    }
?>