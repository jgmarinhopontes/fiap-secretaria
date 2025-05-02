<?php
// Configurações de conexão
$host = 'localhost';
$dbname = 'fiap_secretaria2';
$username = 'root';
$password = ''; // sem senha no XAMPP por padrão

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Define o modo de erro para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
