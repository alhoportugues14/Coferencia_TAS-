<?php
session_start();

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['utilizador_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Obter informações do utilizador autenticado
try {
    $stmt = $pdo->prepare("SELECT nome FROM utilizadores WHERE id = ?");
    $stmt->execute([$_SESSION['utilizador_id']]);
    $utilizador = $stmt->fetch();
    $nome = $utilizador ? $utilizador['nome'] : "Utilizador";
} catch (PDOException $e) {
    die("Erro ao obter dados do utilizador: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Painel, <?php echo htmlspecialchars($nome); ?>!</h1>
        <p>Login realizado com sucesso.</p>
        <nav>
            <ul>
                <li><a href="sessoes.php">Ver Sessões</a></li>
                <li><a href="registos.php">Os Meus Registos</a></li>
                <li><a href="perfil.php">Editar Perfil</a></li>
                <li><a href="logout.php">Terminar Sessão</a></li>
            </ul>
        </nav>
        <p>Utilize o menu acima para navegar pelo sistema.</p>
    </div>
</body>
</html>
