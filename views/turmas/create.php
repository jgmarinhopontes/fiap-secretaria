<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$erros = [];
$nome = '';
$descricao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);

    if (strlen($nome) < 3) {
        $erros[] = "O nome da turma deve ter no mínimo 3 caracteres.";
    }

    // Verificar se já existe turma com mesmo nome
    $stmt = $pdo->prepare("SELECT id FROM turmas WHERE nome = :nome");
    $stmt->execute(['nome' => $nome]);
    if ($stmt->fetch()) {
        $erros[] = "Já existe uma turma com esse nome.";
    }

    // Validação da descrição
    if (strlen($descricao) < 3) {
        $erros[] = "A descrição deve ter no mínimo 3 caracteres.";
    } elseif (strlen($descricao) > 255) {
        $erros[] = "A descrição não pode ultrapassar 255 caracteres.";
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO turmas (nome, descricao) VALUES (:nome, :descricao)");
        $stmt->execute(['nome' => $nome, 'descricao' => $descricao]);

        $_SESSION['flash'] = "Turma '$nome' cadastrada com sucesso!";
        $_SESSION['redirect_to'] = 'list.php';
        header('Location: list.php');
        exit;
    }
}

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Cadastrar Turma</h2>
    <a href="list.php" class="btn btn-secondary mb-3">← Voltar</a>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required placeholder="Ex: Turma A - 2024">
        </div>
        <div class="mb-3">
            <label>Descrição (mín. 3, máx. 255 caracteres):</label>
            <textarea name="descricao" class="form-control" maxlength="255" placeholder="Ex: Turma de Programação Web 2024"><?= htmlspecialchars($descricao) ?></textarea>
            <small id="descricaoContador" class="text-muted">0 / 255 caracteres</small>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar Turma</button>
    </form>
</div>

<script>
const textarea = document.querySelector('textarea[name="descricao"]');
const contador = document.getElementById('descricaoContador');

function atualizarContador() {
    contador.textContent = textarea.value.length + ' / 255 caracteres';
}

textarea.addEventListener('input', atualizarContador);
atualizarContador();
</script>

<?php include '../../includes/footer.php'; ?>
