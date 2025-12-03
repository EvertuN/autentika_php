<?php
require '../auth/check.php';
if ($_SESSION['tipo'] !== 'usuario') die('Acesso negado');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Painel do Usuário</h2>
        <a href="../auth/logout.php" class="btn btn-danger">Sair</a>
    </div>

    <p class="lead">Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?>!</p>

    <!-- Conteúdo específico do usuário pode ser adicionado aqui -->

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>