<?php
global $pdo;
require '../config/db.php';
require '../config/session.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        die('Erro de segurança: Token inválido.');
    }

    $sql = $pdo->prepare("SELECT * FROM users WHERE usuario = ? AND ativo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    $user = $sql->fetch();


    if ($user && password_verify($senha, $user['senha'])) {
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];
        $_SESSION['ultimo_uso'] = time();


        if ($user['tipo'] === 'admin')
            header("Location: ../admin");
        else
            header("Location: ../usuario");
        exit;
    }


    $erro = "Usuário ou senha inválidos.";
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">
<form method="POST" class="p-4 bg-white shadow rounded" style="min-width:300px;">
    <?= csrf_input() ?>
    <h4 class="mb-3">Login</h4>


    <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>


    <div class="mb-3">
        <label>Usuário</label>
        <input type="text" name="usuario" class="form-control" required>
    </div>


    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="senha" class="form-control" required>
    </div>


    <button class="btn btn-primary w-100">Entrar</button>
</form>
</body>
</html>