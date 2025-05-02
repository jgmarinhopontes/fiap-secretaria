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

// Buscar matrícula atual
$stmt = $pdo->prepare("SELECT * FROM matriculas WHERE id = :id");
$stmt->execute(['id' => $id]);
$matricula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$matricula) {
    $_SESSION['flash'] = "Matrícula não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$erros = [];
$aluno_id = $matricula['aluno_id'];
$turma_id = $matricula['turma_id'];

// Carregar listas
$alunos = $pdo->query("SELECT id, nome FROM alunos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? '';
    $turma_id = $_POST['turma_id'] ?? '';

    if (empty($aluno_id) || empty($turma_id)) {
        $erros[] = "Selecione um aluno e uma turma.";
    } else {
        // Verificar se já existe outra matrícula do mesmo aluno na nova turma
        $stmt = $pdo->prepare("SELECT id FROM matriculas WHERE aluno_id = :aluno_id AND turma_id = :turma_id AND id != :id");
        $stmt->execute(['aluno_id' => $aluno_id, 'turma_id' => $turma_id, 'id' => $id]);
        if ($stmt->fetch()) {
            $erros[] = "Este aluno já está matriculado nesta turma.";
        }
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare("UPDATE matriculas SET aluno_id = :aluno_id, turma_id = :turma_id WHERE id = :id");
        $stmt->execute(['aluno_id' => $aluno_id, 'turma_id' => $turma_id, 'id' => $id]);

        $_SESSION['flash'] = "Matrícula atualizada com sucesso!";
        $_SESSION['redirect_to'] = 'list.php';
        header('Location: list.php');
        exit;
    }
}

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Editar Matrícula</h2>
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
            <label>Aluno:</label>
            <select name="aluno_id" class="form-select" required>
                <option value="">-- Selecione um aluno --</option>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?= $aluno['id'] ?>" <?= $aluno['id'] == $aluno_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($aluno['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Turma:</label>
            <select name="turma_id" class="form-select" required>
                <option value="">-- Selecione uma turma --</option>
                <?php foreach ($turmas as $turma): ?>
                    <option value="<?= $turma['id'] ?>" <?= $turma['id'] == $turma_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($turma['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
