<?php
    session_start();
    unset($_SESSION['master_login']);
    unset($_SESSION['admin_login']);
    header("location: login.php");
?>