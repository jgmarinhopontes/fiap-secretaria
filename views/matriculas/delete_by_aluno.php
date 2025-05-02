<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$aluno_id = $_GET['aluno_id'] ?? null;
$turma_id = $_GET['turma_id'] ?? null;

if (!$aluno_id || !$turma_id) {
    $_SESSION['flash'] = "Dados inválidos para desmatrícula.";
    $_SESSION['redirect_to'] = "../turmas/list.php";
    header('Location: ../turmas/list.php');
    exit;
}

// Verificar se matrícula existe
$stmt = $pdo->prepare("SELECT id FROM matriculas WHERE aluno_id = :aluno_id AND turma_id = :turma_id");
$stmt->execute(['aluno_id' => $aluno_id, 'turma_id' => $turma_id]);
$matricula = $stmt->fetch();

if (!$matricula) {
    $_SESSION['flash'] = "Matrícula não encontrada.";
    $_SESSION['redirect_to'] = "../turmas/view_alunos.php?id=$turma_id";
    header("Location: ../turmas/view_alunos.php?id=$turma_id");
    exit;
}

// Excluir matrícula
$stmt = $pdo->prepare("DELETE FROM matriculas WHERE id = :id");
$stmt->execute(['id' => $matricula['id']]);

$_SESSION['flash'] = "Aluno desmatriculado com sucesso!";
$_SESSION['redirect_to'] = "../turmas/view_alunos.php?id=$turma_id";
header("Location: ../turmas/view_alunos.php?id=$turma_id");
exit;
