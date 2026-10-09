<?php 
session_start();
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Cabeleireiras</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h1>Relatório de Cabeleireiras</h1>
        <?php relatorio($conexao); ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>