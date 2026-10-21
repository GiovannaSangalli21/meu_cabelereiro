<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    $mensagem = 'A exclusão deve ser solicitada pelo relatório.';
} elseif (!csrf_token_valido()) {
    http_response_code(403);
    $mensagem = 'A sessão expirou. Atualize o relatório e tente novamente.';
} else {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false || $id === null) {
        http_response_code(400);
        $mensagem = 'O identificador do agendamento é inválido.';
    } else {
        $sucesso = apagar($conexao, $id);
        $mensagem = $sucesso
            ? 'Agendamento excluído com sucesso.'
            : 'Não foi possível excluir o agendamento. Ele pode não existir ou ocorreu um erro no banco de dados.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Agendamento</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <p class="<?= $sucesso ? '' : 'mensagem-erro' ?>"><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></p>
        <p><a href="select.php">Voltar ao relatório</a></p>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
