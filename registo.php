<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Inserir utilizador na base de dados
        $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $password]);

        echo "Registo efetuado com sucesso! Pode agora fazer login.";
    } catch (PDOException $e) {
        echo "Erro ao registar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Registo</h1>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Palavra-passe" required>
            <button type="submit">Registar</button>
        </form>
        <p class="message">Já tem conta? <a href="login.php">Faça login</a>.</p>
    </div>
</body>
</html>
