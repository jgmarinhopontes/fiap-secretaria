<?php
include '../../includes/auth.php';
include '../../includes/db.php';

// Parâmetros de ordenação
$colunasValidas = ['nome'];
$ordenar = $_GET['ordenar'] ?? 'nome';
$sentido = $_GET['sentido'] ?? 'asc';

if (!in_array($ordenar, $colunasValidas)) $ordenar = 'nome';
if (!in_array($sentido, ['asc', 'desc'])) $sentido = 'asc';

// Paginação
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $porPagina;

// Busca
$busca = $_GET['busca'] ?? '';
$parametros = [];

$sqlBase = "FROM turmas t
            LEFT JOIN matriculas m ON t.id = m.turma_id";

$where = "";
if (!empty($busca)) {
    $where = " WHERE t.nome LIKE :busca";
    $parametros['busca'] = "%$busca%";
}

$sqlContagem = "SELECT COUNT(DISTINCT t.id) $sqlBase $where";
$contaStmt = $pdo->prepare($sqlContagem);
$contaStmt->execute($parametros);
$totalTurmas = $contaStmt->fetchColumn();
$totalPaginas = ceil($totalTurmas / $porPagina);

$sql = "SELECT t.*, COUNT(m.id) AS total_alunos
        $sqlBase
        $where
        GROUP BY t.id
        ORDER BY $ordenar $sentido
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($parametros as $k => $v) {
    $stmt->bindValue(":$k", $v);
}
$stmt->bindValue(":limit", $porPagina, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<div class="container py-4">
    <h2>Lista de Turmas</h2>
    <a href="create.php" class="btn btn-success mb-3">➕ Adicionar Turma</a>

    <!-- Campo de busca automática -->
    <input type="text" name="busca" id="campoBusca" class="form-control mb-3" placeholder="Buscar por nome..." value="<?= htmlspecialchars($busca) ?>">

    <?php if (count($turmas) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <a href="?ordenar=nome&sentido=<?= ($ordenar == 'nome' && $sentido == 'asc') ? 'desc' : 'asc' ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">Nome <?= ($ordenar == 'nome') ? ($sentido == 'asc' ? '↑' : '↓') : '' ?></a>
                    </th>
                    <th>Descrição</th>
                    <th>Total de Alunos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turmas as $turma): ?>
                    <tr>
                        <td><?= htmlspecialchars($turma['nome']) ?></td>
                        <td><?= htmlspecialchars($turma['descricao']) ?></td>
                        <td><?= $turma['total_alunos'] ?></td>
                        <td>
                            <a href="view_alunos.php?id=<?= $turma['id'] ?>" class="btn btn-info btn-sm">👥 Ver Alunos</a>
                            <a href="edit.php?id=<?= $turma['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                            <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $turma['id'] ?>" data-nome="<?= htmlspecialchars($turma['nome']) ?>">🗑️ Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>&ordenar=<?= $ordenar ?>&sentido=<?= $sentido ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="alert alert-info">Nenhuma turma cadastrada<?= $busca ? " com o nome \"$busca\"" : "" ?>.</div>
    <?php endif; ?>
</div>

<!-- Modal de exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmação de Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir a turma <strong id="turmaNome"></strong>?
      </div>
      <div class="modal-footer">
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Sim, excluir</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
