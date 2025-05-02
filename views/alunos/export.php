<?php
include '../../includes/auth.php';
include '../../includes/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=alunos.csv');

$output = fopen('php://output', 'w');

// Cabeçalhos da tabela
fputcsv($output, ['ID', 'Nome', 'Idade', 'Data Nasc.', 'CPF', 'Email', 'Telefone', 'Cadastrado em']);

// Query
$stmt = $pdo->query("SELECT *, TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) AS idade FROM alunos ORDER BY nome ASC");
while ($aluno = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $aluno['id'],
        $aluno['nome'],
        $aluno['idade'],
        date('d/m/Y', strtotime($aluno['nascimento'])),
        $aluno['cpf'],
        $aluno['email'],
        $aluno['telefone'],
        date('d/m/Y H:i:s', strtotime($aluno['data_cadastro']))
    ]);
}
fclose($output);
exit;
