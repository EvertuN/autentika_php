<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';
if ($_SESSION['tipo'] !== 'admin')
    die('Acesso negado');

$successMessage = '';

function uuidv4()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        die('Erro de segurança: Token inválido.');
    }

    $id = uuidv4();
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $sql = $pdo->prepare("INSERT INTO users (id,nome,usuario,senha,tipo) VALUES (?,?,?,?,?)");
    if ($sql->execute([$id, $nome, $usuario, $senhaHash, $tipo])) {
        $successMessage = "Usuário criado com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Criar Novo Usuário</h2>
        <a href="index.php" class="btn btn-secondary">Voltar ao Painel</a>
    </div>

    <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <?= csrf_input() ?>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuário</label>
            <input type="text" id="usuario" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select id="tipo" name="tipo" class="form-select">
                <option value="usuario">Usuário</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Criar Usuário</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>