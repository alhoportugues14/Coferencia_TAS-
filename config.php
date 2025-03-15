<?php
// Configuração da base de dados
$host = 'localhost';
$db = 'conferencia_tas';
$user = 'root'; // Altere para o utilizador da sua base de dados
$pass = ''; // Altere para a palavra-passe do seu utilizador

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
