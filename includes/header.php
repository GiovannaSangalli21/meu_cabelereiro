<?php
// Deteta automaticamente a URL base do projeto (funciona tanto em localhost:8000 como em subpastas)
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
if (strpos($_SERVER['REQUEST_URI'], '/meu_cabelereiro') !== false) {
    $base_url .= '/meu_cabelereiro';
}
?>
<!-- Ligação ao ficheiro CSS Global -->
<link rel="stylesheet" href="<?= $base_url ?>/css/style.css">

<header>
    <nav>
        <a href="<?= $base_url ?>/index.php">Início</a>
        <a href="<?= $base_url ?>/app/create.php">Cadastrar Cabeleireira</a>
        <a href="<?= $base_url ?>/app/select.php">Relatório</a>
    </nav>
</header>