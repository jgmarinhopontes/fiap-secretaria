<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash'] = "Turma não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nome FROM turmas WHERE id = :id");
$stmt->execute(['id' => $id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    $_SESSION['flash'] = "Turma não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM turmas WHERE id = :id");
$stmt->execute(['id' => $id]);

$_SESSION['flash'] = "Turma '" . htmlspecialchars($turma['nome']) . "' excluída com sucesso!";
$_SESSION['redirect_to'] = 'list.php';
header('Location: list.php');
exit;
