<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Agendamentos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Relatório de Agendamentos</h2>
        <p><a href="agendar.php">+ Novo Agendamento</a></p>

        <?php relatorio($conexao); ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>