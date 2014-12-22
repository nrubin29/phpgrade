<?php
    session_start();

    $_SESSION["username"] = null;
    $_SESSION["type"] = null;

    header("Location: index.php");
?>