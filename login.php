<?php
require 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Verificar email e palavra-passe
        $stmt = $pdo->prepare("SELECT id, password, tipo FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $utilizador = $stmt->fetch();

        if ($utilizador && password_verify($password, $utilizador['password'])) {
            $_SESSION['utilizador_id'] = $utilizador['id'];
            $_SESSION['tipo'] = $utilizador['tipo'];

            // Redirecionar com base no tipo de utilizador
            if ($utilizador['tipo'] === 'admin') {
                header("Location: dashboard_AD.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            echo "Credenciais inválidas.";
        }
    } catch (PDOException $e) {
        echo "Erro ao fazer login: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Palavra-passe" required>
            <button type="submit">Login</button>
        </form>
        <p class="message">Ainda não tem conta? <a href="registo.php">Registe-se</a>.</p>
    </div>
</body>
</html>
