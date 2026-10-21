<?php
$diretorio_script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
if (basename($diretorio_script) === 'app') {
    $diretorio_script = dirname($diretorio_script);
}
$base_url = rtrim($diretorio_script, '/');
$link_inicio = $base_url . '/index.php';
$link_agendar = $base_url . '/app/agendar.php';
$link_select = $base_url . '/app/select.php';
?>

<header>
    <h1>Studio Bella</h1>
    <nav>
        <a href="<?= htmlspecialchars($link_inicio, ENT_QUOTES, 'UTF-8') ?>">Início</a> |
        <a href="<?= htmlspecialchars($link_agendar, ENT_QUOTES, 'UTF-8') ?>">Cadastrar Agendamento</a> |
        <a href="<?= htmlspecialchars($link_select, ENT_QUOTES, 'UTF-8') ?>">Relatório</a>
    </nav>
</header>