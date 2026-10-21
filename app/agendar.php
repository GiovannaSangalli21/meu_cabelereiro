<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';
$dados = [
    'nome' => '',
    'especialidade' => '',
    'telefone' => '',
    'email' => '',
    'data_agendamento' => '',
    'horario_inicio' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valor) {
        $dados[$campo] = campo_post($campo);
    }

    if (!csrf_token_valido()) {
        http_response_code(403);
        $mensagem = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } elseif (!validar_dados_agendamento(
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['data_agendamento'],
        $dados['horario_inicio']
    )) {
        $mensagem = 'Verifique os dados informados e preencha todos os campos corretamente.';
    } elseif (cadastrar(
        $conexao,
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['horario_inicio'],
        $dados['data_agendamento']
    )) {
        header('Location: select.php');
        exit();
    } else {
        $mensagem = "Não foi possível cadastrar o agendamento. Verifique a estrutura da tabela e tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Agendamento</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Novo Agendamento</h2>

        <?php if ($mensagem !== ''): ?>
            <p class="mensagem-erro"><strong><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <?php endif; ?>

        <form action="agendar.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <label for="nome">Nome Completo:</label><br>
            <input type="text" name="nome" id="nome" maxlength="100" value="<?= htmlspecialchars($dados['nome'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="especialidade">Serviço Desejado:</label><br>
            <select name="especialidade" id="especialidade" required>
                <option value="">Selecione uma opção...</option>
                <option value="Escova" <?= $dados['especialidade'] === 'Escova' ? 'selected' : '' ?>>Escova</option>
                <option value="Corte" <?= $dados['especialidade'] === 'Corte' ? 'selected' : '' ?>>Corte</option>
                <option value="Luzes" <?= $dados['especialidade'] === 'Luzes' ? 'selected' : '' ?>>Luzes</option>
                <option value="Hidratação" <?= $dados['especialidade'] === 'Hidratação' ? 'selected' : '' ?>>Hidratação</option>
                <option value="Manicure" <?= $dados['especialidade'] === 'Manicure' ? 'selected' : '' ?>>Manicure</option>
            </select><br><br>

            <label for="telefone">Telefone / WhatsApp:</label><br>
            <input type="tel" name="telefone" id="telefone" maxlength="20" value="<?= htmlspecialchars($dados['telefone'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" name="email" id="email" maxlength="100" value="<?= htmlspecialchars($dados['email'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="data_agendamento">Data do Atendimento:</label><br>
            <input type="date" name="data_agendamento" id="data_agendamento" value="<?= htmlspecialchars($dados['data_agendamento'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="horario_inicio">Horário de Atendimento:</label><br>
            <input type="time" name="horario_inicio" id="horario_inicio" value="<?= htmlspecialchars($dados['horario_inicio'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <div style="margin-top: 10px;">
                <input type="submit" value="Cadastrar">
                <input type="reset" value="Limpar">
            </div>
        </form>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>