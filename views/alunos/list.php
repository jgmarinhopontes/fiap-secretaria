<?php
include '../../includes/auth.php';
include '../../includes/db.php';

// Parâmetros de ordenação
$colunasValidas = ['nome', 'idade', 'data_cadastro'];
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

$sql = "SELECT *, TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) AS idade FROM alunos";
if (!empty($busca)) {
    $sql .= " WHERE nome LIKE :busca";
    $parametros['busca'] = "%$busca%";
}
$sql .= " ORDER BY $ordenar $sentido LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($parametros as $k => $v) {
    $stmt->bindValue(":$k", $v);
}
$stmt->bindValue(":limit", $porPagina, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Conta total para paginação
if (!empty($busca)) {
    $contaStmt = $pdo->prepare("SELECT COUNT(*) FROM alunos WHERE nome LIKE :busca");
    $contaStmt->execute(['busca' => "%$busca%"]);
} else {
    $contaStmt = $pdo->query("SELECT COUNT(*) FROM alunos");
}
$totalAlunos = $contaStmt->fetchColumn();
$totalPaginas = ceil($totalAlunos / $porPagina);

// Passar variáveis de ordenação pro JS
$jsOrdenar = json_encode($ordenar);
$jsSentido = json_encode($sentido);

include '../../includes/header.php';
?>

<!-- Variáveis JS globais -->
<script>
    window.jsOrdenar = <?= $jsOrdenar ?>;
    window.jsSentido = <?= $jsSentido ?>;
</script>

<div class="container py-4">
    <h2>Lista de Alunos</h2>
    <a href="create.php" class="btn btn-success mb-3">➕ Adicionar Aluno</a>
    <a href="export.php" class="btn btn-outline-success mb-3">⬇️ Exportar CSV</a>

    <!-- Busca automática -->
    <input type="text" name="busca" id="campoBusca" class="form-control mb-3" placeholder="Buscar por nome..." value="<?= htmlspecialchars($busca) ?>">

    <?php if (count($alunos) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <a href="?ordenar=nome&sentido=<?= ($ordenar == 'nome' && $sentido == 'asc') ? 'desc' : 'asc' ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">Nome <?= ($ordenar == 'nome') ? ($sentido == 'asc' ? '↑' : '↓') : '' ?></a>
                    </th>
                    <th>
                        <a href="?ordenar=idade&sentido=<?= ($ordenar == 'idade' && $sentido == 'asc') ? 'desc' : 'asc' ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">Idade <?= ($ordenar == 'idade') ? ($sentido == 'asc' ? '↑' : '↓') : '' ?></a>
                    </th>
                    <th>Data Nasc.</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>
                        <a href="?ordenar=data_cadastro&sentido=<?= ($ordenar == 'data_cadastro' && $sentido == 'asc') ? 'desc' : 'asc' ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>">Cadastrado em <?= ($ordenar == 'data_cadastro') ? ($sentido == 'asc' ? '↑' : '↓') : '' ?></a>
                    </th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td><?= $aluno['idade'] ?></td>
                        <td><?= date('d/m/Y', strtotime($aluno['nascimento'])) ?></td>
                        <td><?= htmlspecialchars($aluno['cpf']) ?></td>
                        <td><?= htmlspecialchars($aluno['email']) ?></td>
                        <td><?= htmlspecialchars($aluno['telefone']) ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($aluno['data_cadastro'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $aluno['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                            <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $aluno['id'] ?>" data-nome="<?= htmlspecialchars($aluno['nome']) ?>">🗑️ Excluir</a>
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
        <div class="alert alert-info">Nenhum aluno cadastrado<?= $busca ? " com o nome \"$busca\"" : "" ?>.</div>
    <?php endif; ?>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmação de Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir o aluno <strong id="alunoNome"></strong>?
      </div>
      <div class="modal-footer">
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Sim, excluir</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
