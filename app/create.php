<?php 
session_start();
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nome           = !empty($_POST['nome'])           ? trim($_POST['nome'])           : null;
    $especialidade  = !empty($_POST['especialidade'])  ? trim($_POST['especialidade'])  : null;
    $telefone       = !empty($_POST['telefone'])       ? trim($_POST['telefone'])       : null;
    $email          = !empty($_POST['email'])          ? trim($_POST['email'])          : null;
    $horario_inicio = !empty($_POST['horario_inicio']) ? $_POST['horario_inicio']       : null;

    cadastrar($conexao, $nome, $especialidade, $telefone, $email, $horario_inicio);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Novo Cadastro de Cliente</h2>
        <form action="" method="post">
            <label for="nome">Nome Completo: </label><br>
            <input type="text" name="nome" id="nome" required><br><br>

            <label for="serviço que você deseja">serviço que você deseja: </label><br>
            <select name="especialidade" id="serviço que você deseja" required>
                <option value="">Selecione uma opção...</option>
                <option value="Escova">Escova</option>
                <option value="Corte">Corte</option>
                <option value="Luzes">Luzes</option>
            </select><br><br>

            <label for="telefone">Telefone / WhatsApp: </label><br>
            <input type="text" name="telefone" id="telefone" required><br><br>

            <label for="email">E-mail: </label><br>
            <input type="email" name="email" id="email" required><br><br>

            <label for="horario_inicio">Horário de Atendimento: </label><br>
            <input type="time" name="horario_inicio" id="horario_inicio" required><br><br>

            <input type="reset" value="Limpar">
            <input type="submit" value="Cadastrar">
        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>