<?php 
session_start();
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Registro</title>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Excluir Registro</h2>

        <?php
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
            apagar($conexao, $id);
            header("refresh:2;url=select.php");
            echo "<p>Redirecionando para a lista em instantes...</p>";
        } else {
            echo "<p style='color:orange;'>Nenhum registro selecionado! Escolha um item no <a href='select.php'>Relatório</a>.</p>";
        }
        ?>

        <br>
        <a href="select.php">Voltar para a lista</a>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>