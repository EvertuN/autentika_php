<?php
require_once __DIR__ . '/db.php';

function log_event($action, $details = [], $userId = null)
{
    global $pdo;

    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $detailsJson = json_encode($details, JSON_UNESCAPED_UNICODE);

        // Se userId não for passado, tenta pegar da sessão
        if ($userId === null && isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
        }

        $stmt = $pdo->prepare("INSERT INTO logs (user_id, action, details, ip) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $detailsJson, $ip]);
    } catch (Exception $e) {
        // Silently fail logging to not disrupt application flow
        error_log("Failed to log event: " . $e->getMessage());
    }
}
