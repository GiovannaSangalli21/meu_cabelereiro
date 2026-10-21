<?php
// Liga o sistema de sessão para identificar o usuário
session_start();

// Puxa o arquivo com as funções do sistema e a conexão do banco
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Aceita acentos e ç no texto -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajusta a tela no celular -->
    <title>Relatório de Agendamentos</title> <!-- Título da aba do navegador -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Arquivo de estilo CSS -->
</head>
<body>
    <!-- Coloca o cabeçalho do site na página -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Relatório de Agendamentos</h2>
        <!-- Link para ir para a página de cadastrar um novo agendamento -->
        <p><a href="agendar.php">+ Novo Agendamento</a></p>

        <!-- Chama a função que busca no banco e mostra todos os agendamentos na tela -->
        <?php relatorio($conexao); ?>
    </main>

    <!-- Coloca o rodapé do site na página -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>