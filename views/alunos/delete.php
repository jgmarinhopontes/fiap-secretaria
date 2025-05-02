<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash'] = "Aluno não encontrado.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Busca aluno pelo ID
$stmt = $pdo->prepare("SELECT nome FROM alunos WHERE id = :id");
$stmt->execute(['id' => $id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    $_SESSION['flash'] = "Aluno não encontrado.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Exclui aluno
$stmt = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
$stmt->execute(['id' => $id]);

$_SESSION['flash'] = "Aluno '" . htmlspecialchars($aluno['nome']) . "' excluído com sucesso!";
$_SESSION['redirect_to'] = 'list.php';
header('Location: list.php');
exit;
