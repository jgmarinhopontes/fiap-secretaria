<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$erros = [];
$aluno_id = '';
$turma_id = '';

// Carregar alunos (agora com CPF) e turmas
$alunos = $pdo->query("SELECT id, nome, cpf FROM alunos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$turmas = $pdo->query("SELECT id, nome FROM turmas ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? '';
    $turma_id = $_POST['turma_id'] ?? '';

    if (empty($aluno_id) || empty($turma_id)) {
        $erros[] = "Selecione um aluno e uma turma.";
    } else {
        // Verificar se já existe matrícula desse aluno na turma
        $stmt = $pdo->prepare("SELECT id FROM matriculas WHERE aluno_id = :aluno_id AND turma_id = :turma_id");
        $stmt->execute(['aluno_id' => $aluno_id, 'turma_id' => $turma_id]);
        if ($stmt->fetch()) {
            $erros[] = "O aluno já está matriculado nesta turma.";
        }
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO matriculas (aluno_id, turma_id) VALUES (:aluno_id, :turma_id)");
        $stmt->execute(['aluno_id' => $aluno_id, 'turma_id' => $turma_id]);

        $_SESSION['flash'] = "Aluno matriculado com sucesso!";
        $_SESSION['redirect_to'] = 'list.php';
        header('Location: list.php');
        exit;
    }
}

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Realizar Matrícula</h2>
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
                        <?= htmlspecialchars($aluno['nome']) ?> - <?= htmlspecialchars($aluno['cpf']) ?>
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

        <button type="submit" class="btn btn-success">Matricular Aluno</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
