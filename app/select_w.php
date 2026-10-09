<?php 
session_start();


$mensagem = '';

// Verifica se o ID do cadastro foi enviado pela URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: select.php');
    exit;
}

// Lógica de atualização quando o formulário é enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome              = $_POST['nome'] ?? '';
    $especialidade     = $_POST['especialidade'] ?? '';
    $telefone          = $_POST['telefone'] ?? '';
    $email             = $_POST['email'] ?? '';
    $horario_inicio    = $_POST['horario_inicio'] ?? '';
    $horario_fim       = $_POST['horario_fim'] ?? '';

    // Query preparada para atualizar os dados com segurança
    $sql = "UPDATE clientes SET 
                nome = ?, 
                especialidade = ?, 
                telefone = ?, 
                email = ?, 
                horario_inicio = ?, 
                horario_fim = ? 
            WHERE id = ?";
            
    $stmt = mysqli_prepare($conexao, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssi", $nome, $especialidade, $telefone, $email, $horario_inicio, $horario_fim, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Cadastro do cliente atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar o cadastro: " . mysqli_error($conexao);
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Busca os dados atuais do cliente para preencher o formulário
$sql_select = "SELECT * FROM clientes WHERE id = ?";
$stmt_select = mysqli_prepare($conexao, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$resultado = mysqli_stmt_get_result($stmt_select);
$cliente = mysqli_fetch_assoc($resultado);

if (!$cliente) {
    header('Location: select.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro de Clientes</title>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <main>
        <h1>Atualizar Cadastro de Clientes</h1>

        <?php if (!empty($mensagem)): ?>
            <p><strong><?= htmlspecialchars($mensagem) ?></strong></p>
        <?php endif; ?>

        <form action="update.php?id=<?= $id ?>" method="POST">
            <div>
                <label for="nome">Nome Completo:</label><br>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cabeleireira['nome'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="especialidade">serviço que você deseja (ex: Corte, Coloração, Penteados):</label><br>
                <input type="text" id="serviço que você deseja" name="serviço que você deseja" value="<?= htmlspecialchars($cliente['serviço que você deseja'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="telefone">Telefone / WhatsApp:</label><br>
                <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="email">E-mail:</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="horario_inicio">Horário de Início do Atendimento:</label><br>
                <input type="time" id="horario_inicio" name="horario_inicio" value="<?= htmlspecialchars($cliente['horario_inicio'] ?? '') ?>" required>
            </div>
            <br>
            <div>
                <label for="horario_fim">Horário de Término do Atendimento:</label><br>
                <input type="time" id="horario_fim" name="horario_fim" value="<?= htmlspecialchars($cliente['horario_fim'] ?? '') ?>" required>
            </div>
            <br>
            <button type="submit">Salvar Alterações</button>
            <a href="select.php">Voltar para a lista</a>
        </form>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>