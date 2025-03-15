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

// Obter registos de check-in e check-out do utilizador
try {
    $query = "SELECT checkin_time, checkout_time FROM checkin_checkout WHERE user_id = ?";
    $stmtCheck = $pdo->prepare($query);
    $stmtCheck->execute([$_SESSION['utilizador_id']]);
    $registos = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao obter registos de check-in/check-out: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <!-- Boas-vindas -->
        <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h1>
        <p>O que gostaria de fazer hoje?</p>
        <nav>
            <ul>
                <li><a href="sessoes.php">Ver Sessões</a></li>
                <li><a href="perfil.php">Editar Perfil</a></li>
                <li><a href="logout.php">Terminar Sessão</a></li>
            </ul>
        </nav>

        <!-- Registos de check-in e check-out -->
        <h2>Os Meus Registos</h2>
        <table>
            <thead>
                <tr>
                    <th>Check-in</th>
                    <th>Check-out</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($registos)): ?>
                    <?php foreach ($registos as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['checkin_time']) ?></td>
                            <td><?= $row['checkout_time'] ? htmlspecialchars($row['checkout_time']) : 'Ainda não efetuado' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Nenhum registo encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

