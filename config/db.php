<?php
$pdo = new PDO("mysql:host=localhost;dbname=auto", "root", "arikas123", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);