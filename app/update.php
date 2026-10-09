<?php 
session_start();
require_once __DIR__ . '/../includes/functions.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: select.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome           = !empty($_POST['nome'])           ? trim($_POST['nome'])           : null;
    $especialidade  = !empty($_POST['especialidade'])  ? trim($_POST['especialidade'])  : null;
    $telefone       = !empty($_POST['telefone'])       ? trim($_POST['telefone'])       : null;
    $email          = !empty($_POST['email'])          ? trim($_POST['email'])          : null;
    $horario_inicio = !empty($_POST['horario_inicio']) ? $_POST['horario_inicio']       : null;

    atualizar($conexao, $id, $nome, $especialidade, $telefone, $email, $horario_inicio);
}

try {
    $sql_select = "SELECT * FROM clientes WHERE id = :id";
    $stmt_select = $conexao->prepare($sql_select);
    $stmt_select->execute([':id' => $id]);
    $cliente = $stmt_select->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        header('Location: select.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h1>Atualizar Cadastro de Clientes</h1>

        <form action="update.php?id=<?= $id ?>" method="POST">
            <div>
                <label for="nome">Nome Completo:</label><br>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cabeleireira['nome'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="especialidade">serviço que você deseja:</label><br>
                <select name="especialidade" id="especialidade" required>
                    <option value="Escova" <?= ($cliente['serviço que você deseja'] ?? '') === 'Escova' ? 'selected' : '' ?>>Escova</option>
                    <option value="Corte" <?= ($cliente['serviço que você deseja'] ?? '') === 'Corte' ? 'selected' : '' ?>>Corte</option>
                    <option value="Luzes" <?= ($cliente['serviço que você deseja'] ?? '') === 'Luzes' ? 'selected' : '' ?>>Luzes</option>
                </select>
            </div>
            <br>
            <div>
                <label for="telefone">Telefone:</label><br>
                <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($cabeleireira['telefone'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="email">E-mail:</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cabeleireira['email'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="horario_inicio">Horário de Atendimento:</label><br>
                <input type="time" id="horario_inicio" name="horario_inicio" value="<?= htmlspecialchars($cliente['horario_inicio'] ?? '') ?>" required>
            </div>
            <br>
            <button type="submit">Salvar Alterações</button>
            <a href="select.php">Voltar para a lista</a>
        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>