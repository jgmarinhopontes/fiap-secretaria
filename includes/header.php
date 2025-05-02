<?php
// Buscar o email do admin logado
$stmt = $pdo->prepare("SELECT email FROM administradores WHERE id = :id");
$stmt->execute(['id' => $_SESSION['admin_id']]);
$adminData = $stmt->fetch(PDO::FETCH_ASSOC);

$emailCompleto = $adminData ? $adminData['email'] : '';
$usuarioLogado = explode('@', $emailCompleto)[0]; // pega só antes do @

$timestampInicio = $_SESSION['inicio'] ?? time();
$flashMessage = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secretaria FIAP</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/fiap-secretaria/public/assets/css/style.min.css?v=1.0">
    <script> window.timestampInicio = <?= $timestampInicio ?>; </script>
</head>
<body>

<?php if ($flashMessage): ?>
    <div class="alert alert-success text-center" id="flash-message" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999;">
        <?= $flashMessage ?>
    </div>
    <script>
        setTimeout(() => { document.getElementById('flash-message').style.display = 'none'; }, 3000);
    </script>
<?php endif; ?>

<!-- BOTÃO MENU MOBILE -->
<button class="btn btn-primary d-lg-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
    ☰ Menu
</button>

<!-- SIDEBAR: DESKTOP (fixo) + MOBILE (offcanvas) -->
<div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header d-lg-none">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <h4>FIAP Admin</h4>
    <a href="/fiap-secretaria/public/index">🏠 Início</a>
    <a href="/fiap-secretaria/views/alunos/list.php">👨‍🎓 Alunos</a>
    <a href="/fiap-secretaria/views/turmas/list.php">📚 Turmas</a>
    <a href="/fiap-secretaria/views/matriculas/list.php">🏫 Matrículas</a>
    <hr>
    <div class="user-info mt-auto">
        <strong>Usuário logado:</strong><br>
        <?= htmlspecialchars($usuarioLogado) ?><br>
        <small id="hora-atual"><?= date('d/m/Y H:i:s') ?></small><br>
        <small>Sessão ativa: <span id="tempo-sessao">00m 00s</span></small>
    </div>
    <hr>
    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmLogout">🔓 Sair</a>
  </div>
</div>

<div class="main-content">
