<?php
include '../includes/auth.php';
include '../includes/db.php';

// Contadores
$totalAlunos = $pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
$totalTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$totalMatriculas = $pdo->query("SELECT COUNT(*) FROM matriculas")->fetchColumn();

// Passa variáveis para o header
include '../includes/header.php';
?>

<div class="container py-4">
    <h2>Bem-vindo à Secretaria FIAP</h2>
    <p>Use o menu à esquerda para gerenciar os alunos, turmas e matrículas.</p>

    <h3 class="mt-4">Dashboard</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Alunos</h5>
                    <p class="card-text display-4 contador" data-total="<?= $totalAlunos ?>">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Turmas</h5>
                    <p class="card-text display-4 contador" data-total="<?= $totalTurmas ?>">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Matrículas</h5>
                    <p class="card-text display-4 contador" data-total="<?= $totalMatriculas ?>">0</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
