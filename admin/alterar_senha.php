<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';

if ($_SESSION['tipo'] !== 'admin')
    die('Acesso negado');

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        die('Erro de segurança: Token inválido.');
    }

    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Buscar senha atual do admin
    $sql = $pdo->prepare("SELECT senha FROM users WHERE id = ?");
    $sql->execute([$_SESSION['id']]);
    $admin = $sql->fetch();

    if ($admin && password_verify($senha_atual, $admin['senha'])) {
        if ($nova_senha === $confirmar_senha) {
            $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET senha = ? WHERE id = ?");
            $update->execute([$senhaHash, $_SESSION['id']]);
            $msg = "Senha alterada com sucesso!";
        } else {
            $error = "A nova senha e a confirmação não correspondem.";
        }
    } else {
        $error = "A senha atual está incorreta.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Alterar Senha</h2>
        <a href="index.php" class="btn btn-secondary">Voltar ao Painel</a>
    </div>

    <?php if ($msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <?= csrf_input() ?>
        <div class="mb-3">
            <label for="senha_atual" class="form-label">Senha Atual</label>
            <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nova_senha" class="form-label">Nova Senha</label>
            <input type="password" id="nova_senha" name="nova_senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Alterar Senha</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>