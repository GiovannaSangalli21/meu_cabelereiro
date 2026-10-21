<?php
// Inicia a sessão PHP para permitir a gestão de variáveis de sessão no sistema
session_start();

// Importa o arquivo de funções gerais contendo a conexão com o banco de dados ($conexao) e funções SQL
require_once __DIR__ . '/../includes/functions.php';

// Inicializa a variável de mensagem com valor vazio para ser exibida após a atualização
$mensagem = '';
$dados = [];

// Captura o ID passado via parâmetro GET na URL (ex: update.php?id=3), ou atribui null se não existir
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

// Se nenhum ID válido for fornecido na URL, redireciona o usuário para a lista/relatório
if (!$id) {
    header('Location: select.php');
    exit;
}

// Verifica se o formulário foi submetido via método POST para processar a alteração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome' => campo_post('nome'),
        'especialidade' => campo_post('especialidade'),
        'telefone' => campo_post('telefone'),
        'email' => campo_post('email'),
        'data_agendamento' => campo_post('data_agendamento'),
        'horario_inicio' => campo_post('horario_inicio'),
    ];

    if (!csrf_token_valido()) {
        http_response_code(403);
        $mensagem = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } elseif (!validar_dados_agendamento(
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['data_agendamento'],
        $dados['horario_inicio']
    )) {
        $mensagem = 'Verifique os dados informados e preencha todos os campos corretamente.';
    } elseif (atualizar(
        $conexao,
        $id,
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['horario_inicio'],
        $dados['data_agendamento']
    )) {
        $mensagem = "Agendamento atualizado com sucesso!";
    } else {
        $mensagem = "Não foi possível atualizar o cadastro. Verifique os dados e tente novamente.";
    }
}

// Busca os dados atuais do registro no banco de dados para preencher o formulário
try {
    $agendamento = consultar($conexao, $id);
} catch (PDOException $e) {
    error_log('Erro ao consultar registro: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível consultar o cadastro. Verifique a conexão com o banco de dados.');
}

// Se o registro não for encontrado no banco de dados (ID inexistente), redireciona para a lista
if (!$agendamento) {
    header('Location: select.php');
    exit;
}

if (!$dados) {
    $dados = $agendamento;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Define a codificação de caracteres como UTF-8 para suporte correto a acentos -->
    <meta charset="UTF-8">
    <!-- Configura a responsividade para adaptação em dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define o título exibido na aba do navegador -->
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Inclui o cabeçalho padrão com o menu de navegação da aplicação -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- Conteúdo principal da página de edição -->
    <main>
        <h1>Editar Agendamento</h1>

        <!-- Exibe a mensagem de sucesso caso as alterações tenham sido salvas -->
        <?php if (!empty($mensagem)): ?>
            <p class="<?= $mensagem === 'Agendamento atualizado com sucesso!' ? '' : 'mensagem-erro' ?>"><strong><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <?php endif; ?>

        <!-- Formulário para envio dos dados via POST apontando para a própria URL com o ID -->
        <form action="update.php?id=<?= htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8') ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <div>
                <label for="nome">Nome Completo:</label><br>
                <input type="text" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars($dados['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <br>
            <div>
                <label for="especialidade">Serviço:</label><br>
                <select id="especialidade" name="especialidade" required>
                    <?php foreach (['Escova', 'Corte', 'Luzes', 'Hidratação', 'Manicure'] as $servico): ?>
                        <option value="<?= htmlspecialchars($servico, ENT_QUOTES, 'UTF-8') ?>" <?= ($dados['especialidade'] ?? '') === $servico ? 'selected' : '' ?>><?= htmlspecialchars($servico, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <div>
                <label for="telefone">Telefone:</label><br>
                <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= htmlspecialchars($dados['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <br>
            <div>
                <label for="email">E-mail:</label><br>
                <input type="email" id="email" name="email" maxlength="100" value="<?= htmlspecialchars($dados['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <br>
            <div>
                <label for="data_agendamento">Data do Atendimento:</label><br>
                <input type="date" id="data_agendamento" name="data_agendamento" value="<?= htmlspecialchars($dados['data_agendamento'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <br>
            <div>
                <label for="horario_inicio">Horário do Atendimento:</label><br>
                <input type="time" id="horario_inicio" name="horario_inicio" value="<?= htmlspecialchars(substr($dados['horario_inicio'] ?? '', 0, 5), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <br>
            <button type="submit">Salvar Alterações</button>
            <a href="select.php">Voltar para a lista</a>
        </form>
    </main>

    <!-- Inclui o rodapé padrão da aplicação -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>