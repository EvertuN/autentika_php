<?php
require '../config/session.php';


if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}


if (time() - $_SESSION['ultimo_uso'] > 900) { // 15 minutos
    session_destroy();
    header("Location: ../auth/login.php?msg=expirou");
    exit;
}


$_SESSION['ultimo_uso'] = time();