<?php
include '../../includes/auth.php';
include '../../includes/db.php';

$turma_id = $_GET['id'] ?? null;
if (!$turma_id) {
    $_SESSION['flash'] = "Turma não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Buscar dados da turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = :id");
$stmt->execute(['id' => $turma_id]);
$turma = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$turma) {
    $_SESSION['flash'] = "Turma não encontrada.";
    $_SESSION['redirect_to'] = 'list.php';
    header('Location: list.php');
    exit;
}

// Buscar alunos matriculados
$sql = "SELECT a.* FROM matriculas m
        JOIN alunos a ON m.aluno_id = a.id
        WHERE m.turma_id = :turma_id
        ORDER BY a.nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['turma_id' => $turma_id]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Alunos da Turma: <?= htmlspecialchars($turma['nome']) ?></h2>
    <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($turma['descricao'])) ?></p>
    <a href="list.php" class="btn btn-secondary mb-3">← Voltar para Turmas</a>

    <?php if (count($alunos) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Data de Nascimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td><?= htmlspecialchars($aluno['email']) ?></td>
                        <td><?= htmlspecialchars($aluno['cpf']) ?></td>
                        <td><?= date('d/m/Y', strtotime($aluno['nascimento'])) ?></td>
                        <td>
                            <a href="#" class="btn btn-danger btn-sm"
                               data-bs-toggle="modal"
                               data-bs-target="#confirmDeleteModal"
                               data-id="<?= $aluno['id'] ?>"
                               data-nome="<?= htmlspecialchars($aluno['nome']) ?>"
                               data-turma="<?= $turma_id ?>">
                               🗑️ Desmatricular
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Nenhum aluno matriculado nesta turma.</div>
    <?php endif; ?>
</div>

<!-- Modal de confirmação de desmatrícula -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmação de Desmatrícula</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja desmatricular o aluno <strong id="alunoNome"></strong> desta turma?
      </div>
      <div class="modal-footer">
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Sim, desmatricular</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<?php include '../../includes/footer.php'; ?>
