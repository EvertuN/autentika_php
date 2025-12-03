<?php
global $pdo;
require '../auth/check.php';
require '../config/db.php';

if ($_SESSION['tipo'] !== 'admin')
    die('Acesso negado');

// Paginação simples
$limit = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Buscar logs
$sql = $pdo->prepare("
    SELECT l.*, u.nome as user_name 
    FROM logs l 
    LEFT JOIN users u ON l.user_id = u.id 
    ORDER BY l.created_at DESC 
    LIMIT $limit OFFSET $offset
");
$sql->execute();
$logs = $sql->fetchAll();

// Contar total para paginação
$total = $pdo->query("SELECT COUNT(*) FROM logs")->fetchColumn();
$pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Logs do Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Logs do Sistema</h2>
            <a href="index.php" class="btn btn-secondary">Voltar ao Painel</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>IP</th>
                                <th>Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($log['user_name'] ?? 'Sistema/Visitante') ?></td>
                                    <td><span class="badge bg-info"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td><?= htmlspecialchars($log['ip']) ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($log['details']) ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>