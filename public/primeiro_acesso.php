<?php
session_start();
include '../includes/db.php';

// Verifica se já existe um administrador
$stmt = $pdo->query("SELECT COUNT(*) FROM administradores");
$existeAdmin = $stmt->fetchColumn();

if ($existeAdmin > 0) {
    header('Location: login.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Validação simples
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha)) {
        $erro = "A senha deve ter no mínimo 8 caracteres, com maiúsculas, minúsculas, números e símbolos.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO administradores (email, senha) VALUES (:email, :senha)");
        $stmt->execute(['email' => $email, 'senha' => $hash]);

        // Recupera o ID do administrador recém-cadastrado
        $adminId = $pdo->lastInsertId();

        // Cria sessão já logado
        $_SESSION['admin_id'] = $adminId;
        $_SESSION['inicio'] = time();
        $_SESSION['flash'] = "Administrador criado e login realizado com sucesso!";

        // Redireciona direto para o index
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Primeiro Acesso - Secretaria FIAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/fiap-secretaria/public/assets/css/style.css">
</head>
<body class="login-page">

<div class="login-card">
    <h2>Criar Administrador</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Senha:</label>
            <input type="password" name="senha" class="form-control" required>
            <div class="form-text">Mínimo 8 caracteres, incluindo maiúsculas, minúsculas, números e símbolos.</div>
        </div>

        <button type="submit" class="btn btn-primary">Criar Administrador</button>
    </form>

    <small>&copy; Secretaria FIAP</small>
</div>

</body>
</html>
