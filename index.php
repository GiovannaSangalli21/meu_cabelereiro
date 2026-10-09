<?php 
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Salão de Beleza</title>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?> 

    <main>
        <h1>Gestão de Salão de Beleza</h1>
        <h2>Seja bem-vindo(a)!</h2>
        <p>Este sistema foi desenvolvido para a gestão de cadastros e horários de cabeleireiras.</p>

        <section>
            <h3>Ações Rápidas</h3>
            <ul>
                <li><a href="app/create.php">Cadastrar Nova Cabeleireira</a></li>
                <li><a href="app/select.php">Ver Relatório de Cabeleireiras</a></li>
            </ul>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>