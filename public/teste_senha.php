<?php
include '../includes/db.php';

$email = 'admin@fiap.com.br';
$senhaDigitada = 'Admin123!';

$stmt = $pdo->prepare("SELECT * FROM administradores WHERE email = :email");
$stmt->execute(['email' => $email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    if (password_verify($senhaDigitada, $admin['senha'])) {
        echo "Senha válida!";
    } else {
        echo "Senha inválida!";
    }
} else {
    echo "Usuário não encontrado.";
}
