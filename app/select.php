<?php
// Garante a inclusão das funções e da ligação à base de dados
require_once __DIR__ . '/../includes/functions.php';
iniciar_sessao();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Agendamentos</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('css/style.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Relatório de Agendamentos</h2>

        <p><a href="<?= htmlspecialchars(app_url('app/agendar.php'), ENT_QUOTES, 'UTF-8') ?>">+ Novo Agendamento</a></p>

        <?php
        // Executa a função do relatório se a variável de conexão existir
        if (isset($conexao)) {
            relatorio($conexao);
        } else {
            echo "<p>Erro: Conexão com a base de dados não encontrada.</p>";
        }
        ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>