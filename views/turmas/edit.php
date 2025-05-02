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

$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = :id");
$stmt->execute(['id' => $id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    $_SESSION['flash'] = "Turma não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$erros = [];
$nome = $turma['nome'];
$descricao = $turma['descricao'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);

    if (strlen($nome) < 3) {
        $erros[] = "O nome da turma deve ter no mínimo 3 caracteres.";
    }

    // Verificar se já existe outra turma com mesmo nome
    $stmt = $pdo->prepare("SELECT id FROM turmas WHERE nome = :nome AND id != :id");
    $stmt->execute(['nome' => $nome, 'id' => $id]);
    if ($stmt->fetch()) {
        $erros[] = "Já existe outra turma com esse nome.";
    }

    // Validação do tamanho da descrição
    if (strlen($descricao) > 255) {
        $erros[] = "A descrição não pode ultrapassar 255 caracteres.";
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare("UPDATE turmas SET nome = :nome, descricao = :descricao WHERE id = :id");
        $stmt->execute(['nome' => $nome, 'descricao' => $descricao, 'id' => $id]);

        $_SESSION['flash'] = "Turma '$nome' atualizada com sucesso!";
        $_SESSION['redirect_to'] = 'list.php';
        header('Location: list.php');
        exit;
    }
}

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Editar Turma</h2>
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
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
        </div>
        <div class="mb-3">
            <label>Descrição (máx. 255 caracteres):</label>
            <textarea name="descricao" class="form-control" maxlength="255" placeholder="Ex: Turma de Programação Web 2024"><?= htmlspecialchars($descricao) ?></textarea>
            <small id="descricaoContador" class="text-muted">0 / 255 caracteres</small>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
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
