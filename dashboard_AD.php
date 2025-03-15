<?php
session_start();

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['utilizador_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Verificar se o utilizador é administrador
try {
    $stmt = $pdo->prepare("SELECT nome, tipo FROM utilizadores WHERE id = ?");
    $stmt->execute([$_SESSION['utilizador_id']]);
    $utilizador = $stmt->fetch();

    if (!$utilizador) {
        die("Utilizador não encontrado.");
    }

    // Armazenar o nome do utilizador para exibição
    $nome = $utilizador['nome'];
} catch (PDOException $e) {
    die("Erro ao obter dados do utilizador: " . $e->getMessage());
}
?>

s
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo (Admin)</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Painel de Administração, <?php echo htmlspecialchars($nome); ?>!</h1>
        <p>Login realizado com sucesso.</p>
        <nav>
            <ul>
                <li><a href="gestao_utilizadores.php">Gerir Utilizadores</a></li>
                <li><a href="estatisticas.php">Ver Estatísticas</a></li>
                <li><a href="configuracoes.php">Configurações do Sistema</a></li>
                <li><a href="perfil.php">Editar Perfil</a></li>
                <li><a href="logout.php">Terminar Sessão</a></li>
            </ul>
        </nav>
        <p>Utilize o menu acima para administrar o sistema.</p>
    </div>
</body>
</html>
