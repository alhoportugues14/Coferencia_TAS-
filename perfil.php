<?php
require 'config.php';

session_start();

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['utilizador_id'])) {
    header("Location: login.php");
    exit;
}

$utilizador_id = $_SESSION['utilizador_id'];
$mensagem = "";

// Obter informações atuais do utilizador
try {
    $stmt = $pdo->prepare("SELECT nome, email FROM utilizadores WHERE id = ?");
    $stmt->execute([$utilizador_id]);
    $utilizador = $stmt->fetch();

    if (!$utilizador) {
        echo "Utilizador não encontrado.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro ao obter informações do perfil: " . $e->getMessage();
    exit;
}

// Atualizar informações do perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = $_POST['nome'];
    $novo_email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$novo_nome, $novo_email, $utilizador_id]);

        $mensagem = "Perfil atualizado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar o perfil: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Perfil</h1>

    <?php if ($mensagem): ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form action="perfil.php" method="POST">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($utilizador['nome']); ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($utilizador['email']); ?>" required>
        </div>
        <button type="submit">Guardar Alterações</button>
    </form>

    <a href="dashboard.php">Voltar ao Dashboard</a>
</body>
</html>
