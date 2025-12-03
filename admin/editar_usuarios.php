<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';
require '../config/logger.php';

if ($_SESSION['tipo'] !== 'admin') die('Acesso negado');

$id = $_GET['id'] ?? null;
if (!$id) die("ID inválido");

// Buscar dados do usuário
$sql = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$sql->execute([$id]);
$user = $sql->fetch();

if (!$user) die("Usuário não encontrado");

$msg = "";

// Atualizar informações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        die('Erro de segurança: Token inválido.');
    }

    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $tipo = $_POST['tipo'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    // Atualizar sem senha primeiro
    $update = $pdo->prepare("
        UPDATE users SET nome=?, usuario=?, tipo=?, ativo=? WHERE id=?
    ");
    $update->execute([$nome, $usuario, $tipo, $ativo, $id]);

    // Se senha nova foi enviada
    if (!empty($_POST['senha'])) {
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $updateSenha = $pdo->prepare("UPDATE users SET senha=? WHERE id=?");
        $updateSenha->execute([$senhaHash, $id]);
    }

    $msg = "Alterações salvas com sucesso!";
    log_event('UPDATE_USER', ['target_user_id' => $id, 'changes' => $_POST]);
    
    // Recarregar dados do usuário para exibir informações atualizadas no formulário
    $sql->execute([$id]);
    $user = $sql->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Usuário</h2>
        <a href="listar_usuarios.php" class="btn btn-secondary">Voltar para a Lista</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <?= csrf_input() ?>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="usuario" class="form-label">Usuário</label>
            <input type="text" id="usuario" name="usuario" class="form-control" value="<?= htmlspecialchars($user['usuario']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select id="tipo" name="tipo" class="form-select">
                <option value="usuario" <?= $user['tipo'] == 'usuario' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= $user['tipo'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" id="ativo" name="ativo" class="form-check-input" <?= $user['ativo'] == 1 ? 'checked' : '' ?>>
            <label for="ativo" class="form-check-label">Usuário Ativo</label>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Nova Senha (opcional)</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para não alterar">
        </div>

        <button type="submit" class="btn btn-success">Salvar Alterações</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>