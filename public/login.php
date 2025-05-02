<?php
session_start();
include '../includes/db.php';

// ✅ Verifica se já existe um administrador
$stmt = $pdo->query("SELECT COUNT(*) FROM administradores");
$existeAdmin = $stmt->fetchColumn();

// 👉 Se não existir nenhum admin, redireciona pro primeiro acesso
if ($existeAdmin == 0) {
    header('Location: primeiro_acesso.php');
    exit;
}

// ✅ Se já está logado e sem mensagem flash, vai direto pro index
if (isset($_SESSION['admin_id']) && !isset($_SESSION['flash'])) {
    header('Location: index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM administradores WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        if (password_verify($senha, $admin['senha'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['inicio'] = time();
            $_SESSION['flash'] = "Login realizado com sucesso! Redirecionando em 3 segundos...";
            $_SESSION['redirect_to'] = 'index.php';
        } else {
            $erro = 'Senha incorreta.';
        }
    } else {
        $erro = 'E-mail não encontrado.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Secretaria FIAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/fiap-secretaria/public/assets/css/style.css">
</head>
<body class="login-page">

<div class="login-card">
    <h2>Login Administrativo</h2>

    <?php if (isset($_SESSION['admin_id']) && isset($_SESSION['flash'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['flash'] ?></div>
        <script>
            setTimeout(() => { window.location.href = '<?= $_SESSION['redirect_to'] ?>'; }, 3000);
        </script>
        <?php unset($_SESSION['flash'], $_SESSION['redirect_to']); ?>
    <?php else: ?>

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
            </div>

            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>

        <small>&copy; Secretaria FIAP</small>
    <?php endif; ?>
</div>

</body>
</html>
