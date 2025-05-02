<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash'] = "Aluno não encontrado.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
$stmt->execute(['id' => $id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    $_SESSION['flash'] = "Aluno não encontrado.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

$erros = [];
$nome = $aluno['nome'];
$nascimento = $aluno['nascimento'];
$cpf = $aluno['cpf'];
$email = $aluno['email'];
$telefone = $aluno['telefone'];

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $nascimento = $_POST['nascimento'];
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    // Validação Nome
    if (strlen($nome) < 3) {
        $erros[] = "O nome deve ter no mínimo 3 caracteres.";
    }
    if (!preg_match('/^[A-Za-zÀ-ÿ ]+$/', $nome)) {
        $erros[] = "O nome não pode conter números ou caracteres inválidos.";
    }
    if (substr_count(trim($nome), ' ') < 1) {
        $erros[] = "O nome deve conter nome e sobrenome (use um espaço).";
    }

    // Validação Data de Nascimento (>=18 anos)
    if (!empty($nascimento)) {
        $dataNasc = DateTime::createFromFormat('Y-m-d', $nascimento);
        $hoje = new DateTime();
        $idade = $hoje->diff($dataNasc)->y;
        if ($idade < 18) {
            $erros[] = "O aluno deve ter no mínimo 18 anos.";
        }
    } else {
        $erros[] = "Data de nascimento é obrigatória.";
    }

    // Validação CPF
    $cpfNumeros = preg_replace('/\D/', '', $cpf);
    if (strlen($cpfNumeros) != 11) {
        $erros[] = "O CPF deve conter 11 dígitos.";
    } elseif (!validarCPF($cpf)) {
        $erros[] = "CPF inválido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM alunos WHERE cpf = :cpf AND id != :id");
        $stmt->execute(['cpf' => $cpf, 'id' => $id]);
        if ($stmt->fetch()) {
            $erros[] = "Este CPF já está cadastrado por outro aluno.";
        }
    }

    // Validação Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM alunos WHERE email = :email AND id != :id");
        $stmt->execute(['email' => $email, 'id' => $id]);
        if ($stmt->fetch()) {
            $erros[] = "Este Email já está cadastrado por outro aluno.";
        }
    }

    // Validação Telefone
    $telefoneNumeros = preg_replace('/\D/', '', $telefone);
    if (empty($telefone)) {
        $erros[] = "O telefone é obrigatório.";
    } elseif (strlen($telefoneNumeros) > 11) {
        $erros[] = "O telefone não pode ter mais de 11 dígitos.";
    }

    if (empty($erros)) {
        $stmt = $pdo->prepare("UPDATE alunos SET nome = :nome, nascimento = :nascimento, cpf = :cpf, email = :email, telefone = :telefone WHERE id = :id");
        $stmt->execute([
            'nome' => $nome,
            'nascimento' => $nascimento,
            'cpf' => $cpf,
            'email' => $email,
            'telefone' => $telefone,
            'id' => $id
        ]);

        $_SESSION['flash'] = "Aluno '" . htmlspecialchars($nome) . "' atualizado com sucesso!";
        $_SESSION['redirect_to'] = 'list.php';
        header('Location: list.php');
        exit;
    }
}

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Editar Aluno</h2>
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
            <input type="text" name="nome" class="form-control" placeholder="Ex: João Silva" value="<?= htmlspecialchars($nome) ?>" required>
        </div>

        <div class="mb-3">
            <label>Data de Nascimento:</label>
            <input type="date" name="nascimento" class="form-control" value="<?= htmlspecialchars($nascimento) ?>" required>
        </div>

        <div class="mb-3">
            <label>CPF:</label>
            <input type="text" name="cpf" id="cpf" class="form-control" value="<?= htmlspecialchars($cpf) ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label>Telefone / WhatsApp:</label>
            <input type="text" name="telefone" id="telefone" class="form-control" value="<?= htmlspecialchars($telefone) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
