<?php
    session_start();
    session_destroy();
    header("Location: /agrismart/index.html");
?>