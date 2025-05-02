<?php
include '../../includes/auth.php';
include '../../includes/db.php';

// Consulta todas as matrículas com JOIN para mostrar nome e CPF do aluno e da turma
$sql = "SELECT m.*, a.nome AS nome_aluno, a.cpf AS cpf_aluno, t.nome AS nome_turma, m.data_matricula
        FROM matriculas m
        JOIN alunos a ON m.aluno_id = a.id
        JOIN turmas t ON m.turma_id = t.id
        ORDER BY m.data_matricula DESC";
$stmt = $pdo->query($sql);
$matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Lista de Matrículas</h2>
    <a href="create.php" class="btn btn-success mb-3">➕ Nova Matrícula</a>

    <?php if (count($matriculas) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Aluno (CPF)</th>
                    <th>Turma</th>
                    <th>Data da Matrícula</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $matricula): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($matricula['nome_aluno']) ?>
                            <br><small class="text-muted"><?= htmlspecialchars($matricula['cpf_aluno']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($matricula['nome_turma']) ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($matricula['data_matricula'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $matricula['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                            <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $matricula['id'] ?>" data-nome="<?= htmlspecialchars($matricula['nome_aluno']) ?>">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal de confirmação de exclusão -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Confirmação de Desmatrícula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
              </div>
              <div class="modal-body">
                Tem certeza que deseja desmatricular o aluno <strong id="matriculaAlunoNome"></strong>?
              </div>
              <div class="modal-footer">
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Sim, desmatricular</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info">Nenhuma matrícula encontrada.</div>
    <?php endif; ?>
</div>

<script>
// Modal exclusão
var confirmDeleteModal = document.getElementById('confirmDeleteModal');
confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var matriculaId = button.getAttribute('data-id');
  var alunoNome = button.getAttribute('data-nome');

  var modalBodyNome = confirmDeleteModal.querySelector('#matriculaAlunoNome');
  var confirmDeleteBtn = confirmDeleteModal.querySelector('#confirmDeleteBtn');

  modalBodyNome.textContent = alunoNome;
  confirmDeleteBtn.href = "delete.php?id=" + matriculaId;
});
</script>

<?php include '../../includes/footer.php'; ?>
