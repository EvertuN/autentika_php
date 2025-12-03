<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';

// Buscar logs apenas do usuário logado e apenas LOGIN/LOGOUT
$sql = $pdo->prepare("
    SELECT * FROM logs 
    WHERE user_id = ? AND action IN ('LOGIN_SUCCESS', 'LOGOUT')
    ORDER BY created_at DESC 
    LIMIT 20
");
$sql->execute([$_SESSION['id']]);
$logs = $sql->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meus Acessos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Meus Acessos Recentes</h2>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Ação</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                                    <td>
                                        <?php if ($log['action'] === 'LOGIN_SUCCESS'): ?>
                                            <span class="badge bg-success">Login</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Logout</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($log['ip']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>