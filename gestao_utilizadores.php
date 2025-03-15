<?php
session_start();

// Verificar se o utilizador está autenticado e é administrador
if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: acesso_negado.php");
    exit();
}

require 'config.php';

// Variáveis para mensagens e edição
$mensagem = "";
$editar_utilizador = null;

// Apagar utilizador
if (isset($_GET['apagar'])) {
    $id = $_GET['apagar'];

    try {
        $stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        $mensagem = "Utilizador apagado com sucesso!";
    } catch (PDOException $e) {
        $mensagem = "Erro ao apagar o utilizador: " . $e->getMessage();
    }
}

// Obter lista de utilizadores
try {
    $stmt = $pdo->query("SELECT id, nome, email, tipo FROM utilizadores");
    $utilizadores = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao obter utilizadores: " . $e->getMessage());
}

// Editar utilizador
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];

    // Obter dados do utilizador para edição
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, tipo FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        $editar_utilizador = $stmt->fetch();

        if (!$editar_utilizador) {
            $mensagem = "Utilizador não encontrado.";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro ao obter dados do utilizador: " . $e->getMessage();
    }
}

// Atualizar dados do utilizador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_utilizador'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    try {
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, email = ?, tipo = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $tipo, $id]);
        $mensagem = "Utilizador atualizado com sucesso!";
        // Atualizar lista de utilizadores
        $stmt = $pdo->query("SELECT id, nome, email, tipo FROM utilizadores");
        $utilizadores = $stmt->fetchAll();
        $editar_utilizador = null; // Fechar o formulário de edição
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar o utilizador: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Utilizadores</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <h1>Gestão de Utilizadores</h1>
        <?php if ($mensagem): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <?php if ($editar_utilizador): ?>
            <!-- Formulário de edição -->
            <h2>Editar Utilizador</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($editar_utilizador['id']); ?>">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($editar_utilizador['nome']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($editar_utilizador['email']); ?>" required>

                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="utilizador" <?php echo $editar_utilizador['tipo'] === 'utilizador' ? 'selected' : ''; ?>>Utilizador</option>
                    <option value="admin" <?php echo $editar_utilizador['tipo'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>

                <button type="submit" name="editar_utilizador">Guardar Alterações</button>
            </form>
            <a href="gestao_utilizadores.php">Cancelar</a>
        <?php else: ?>
            <!-- Listagem de utilizadores -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilizadores as $utilizador): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($utilizador['id']); ?></td>
                            <td><?php echo htmlspecialchars($utilizador['nome']); ?></td>
                            <td><?php echo htmlspecialchars($utilizador['email']); ?></td>
                            <td><?php echo htmlspecialchars($utilizador['tipo']); ?></td>
                            <td>
                                <a href="gestao_utilizadores.php?editar=<?php echo $utilizador['id']; ?>">Editar</a>
                                <a href="gestao_utilizadores.php?apagar=<?php echo $utilizador['id']; ?>" onclick="return confirm('Tem a certeza que deseja apagar este utilizador?');">Apagar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
