<?php require_once __DIR__ . '/paths.php'; ?>

<header>
    <h1>Studio Bella</h1>
    <nav>
        <a href="<?= htmlspecialchars(app_url('index.php'), ENT_QUOTES, 'UTF-8') ?>">Início</a> |
        <a href="<?= htmlspecialchars(app_url('app/agendar.php'), ENT_QUOTES, 'UTF-8') ?>">Cadastrar Agendamento</a> |
        <a href="<?= htmlspecialchars(app_url('app/select.php'), ENT_QUOTES, 'UTF-8') ?>">Relatório</a>
    </nav>
</header>