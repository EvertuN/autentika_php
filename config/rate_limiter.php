<?php
require_once __DIR__ . '/db.php';

// Configuração: 5 tentativas em 15 minutos
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutos em segundos

function check_rate_limit($ip, $username)
{
    global $pdo;

    // Conta tentativas falhas nos últimos 15 minutos
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM login_attempts 
        WHERE ip = ? AND username = ? AND attempt_time > (NOW() - INTERVAL 15 MINUTE)
    ");
    $stmt->execute([$ip, $username]);
    $attempts = $stmt->fetchColumn();

    return $attempts >= MAX_LOGIN_ATTEMPTS;
}

function record_failed_login($ip, $username)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO login_attempts (ip, username) VALUES (?, ?)");
    $stmt->execute([$ip, $username]);
}

function clear_failed_logins($ip, $username)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip = ? AND username = ?");
    $stmt->execute([$ip, $username]);
}