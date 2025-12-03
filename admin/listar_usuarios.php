<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';

if ($_SESSION['tipo'] !== 'admin') die('Acesso negado');

// Buscar todos os usuários
$sql = $pdo->query("SELECT id, nome, usuario, tipo, ativo FROM users ORDER BY nome");
$usuarios = $sql->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuários</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Usuários</h2>
        <div>
            <a href="criar_usuario.php" class="btn btn-primary">Criar Novo Usuário</a>
            <a href="index.php" class="btn btn-secondary">Voltar ao Painel</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nome']) ?></td>
                        <td><?= htmlspecialchars($u['usuario']) ?></td>
                        <td><?= htmlspecialchars($u['tipo']) ?></td>
                        <td><?= $u['ativo'] ? 'Sim' : 'Não' ?></td>
                        <td>
                            <a href="editar_usuarios.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>