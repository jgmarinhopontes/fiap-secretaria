<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash'] = "Matrícula não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Verificar se matrícula existe
$stmt = $pdo->prepare("SELECT id FROM matriculas WHERE id = :id");
$stmt->execute(['id' => $id]);
$matricula = $stmt->fetch();

if (!$matricula) {
    $_SESSION['flash'] = "Matrícula não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Excluir matrícula
$stmt = $pdo->prepare("DELETE FROM matriculas WHERE id = :id");
$stmt->execute(['id' => $id]);

$_SESSION['flash'] = "Matrícula excluída com sucesso!";
$_SESSION['redirect_to'] = 'list.php';
header('Location: list.php');
exit;
