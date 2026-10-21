<?php
require_once __DIR__ . '/../includes/functions.php';

$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    $mensagem = 'Acesso inválido. Use o botão de exclusão no relatório.';
} else {
    $id = filter_var(
        $_POST['id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($id === false) {
        http_response_code(400);
        $mensagem = 'O identificador do agendamento é inválido.';
    } else {
        try {
            $sucesso = apagar($conexao, $id);
            if ($sucesso) {
                $mensagem = 'Agendamento excluído com sucesso.';
            } else {
                http_response_code(404);
                $mensagem = 'Agendamento não encontrado.';
            }
        } catch (PDOException $e) {
            error_log('Falha ao excluir agendamento: ' . $e->getMessage());
            http_response_code(500);
            $mensagem = 'Não foi possível excluir o agendamento. Tente novamente mais tarde.';
        }
    }
}

$escapar = static fn (string $valor): string => htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Agendamento</title>
    <link rel="stylesheet" href="<?= $escapar(app_url('css/style.css')) ?>">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h1>Excluir Agendamento</h1>
        <p class="<?= $sucesso ? 'mensagem' : 'mensagem-erro' ?>"><?= $escapar($mensagem) ?></p>
        <p><a href="<?= $escapar(app_url('app/select.php')) ?>">Voltar ao relatório</a></p>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
