<?php
// Liga o sistema de sessão (usado para salvar dados do usuário)

// Puxa o arquivo que tem as funções do sistema
require_once __DIR__ . '/../includes/functions.php';
iniciar_sessao();

// Cria uma variável vazia para guardar mensagens de aviso ou erro
$mensagem = '';

// Cria uma lista (array) para guardar os dados digitados no formulário
$dados = [
    'nome' => '',
    'especialidade' => '',
    'telefone' => '',
    'email' => '',
    'data_agendamento' => '',
    'horario_inicio' => '',
];

// Checa se a pessoa clicou no botão para enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pega o que a pessoa digitou em cada campo do formulário
    foreach ($dados as $campo => $valor) {
        $dados[$campo] = campo_post($campo);
    }

    // Testa se o código de segurança do formulário está certo
    if (!csrf_token_valido()) {
        http_response_code(403); // Código de erro de acesso negado
        $mensagem = 'A sessão do formulário expirou. Atualize a página e tente novamente.';

        // Testa se todos os campos foram preenchidos corretamente
    } elseif (!validar_dados_agendamento(
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['data_agendamento'],
        $dados['horario_inicio']
    )) {
        $mensagem = 'Verifique os dados informados e preencha todos os campos corretamente.';

        // Tenta salvar os dados no banco de dados
    } elseif (cadastrar(
        $conexao,
        $dados['nome'],
        $dados['especialidade'],
        $dados['telefone'],
        $dados['email'],
        $dados['horario_inicio'],
        $dados['data_agendamento']
    )) {
        // Se deu tudo certo ao salvar, envia a pessoa para a página de relatório
        header('Location: ' . app_url('app/select.php'));
        exit(); // Para o código por aqui
    } else {
        // Se deu algum erro ao salvar no banco
        $mensagem = "Não foi possível cadastrar o agendamento. Verifique a estrutura da tabela e tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8"> <!-- Permite usar acentos e ç sem quebrar o texto -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajusta a página na tela do celular -->
    <title>Cadastrar Agendamento</title> <!-- Nome que aparece na aba do navegador -->
    <link rel="stylesheet" href="<?= htmlspecialchars(app_url('css/style.css'), ENT_QUOTES, 'UTF-8') ?>"> <!-- Conecta o arquivo de visual (CSS) -->
</head>

<body>
    <!-- Coloca o cabeçalho do site aqui -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h2>Novo Agendamento</h2>

        <!-- Se tiver alguma mensagem de erro, mostra ela aqui -->
        <?php if ($mensagem !== ''): ?>
            <p class="mensagem-erro"><strong><?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?></strong></p>
        <?php endif; ?>

        <!-- Formulário para mandar as informações quando clicar em enviar -->
        <form action="<?= htmlspecialchars(app_url('app/agendar.php'), ENT_QUOTES, 'UTF-8') ?>" method="post">
            <!-- Campo escondido para guardar o código de segurança -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

            <!-- Campo do Nome -->
            <label for="nome">Nome Completo:</label><br>
            <input type="text" name="nome" id="nome" maxlength="100" value="<?= htmlspecialchars($dados['nome'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <!-- Caixinha para escolher o Serviço -->
            <label for="especialidade">Serviço Desejado:</label><br>
            <select name="especialidade" id="especialidade" required>
                <option value="">Selecione uma opção...</option>
                <!-- Deixa marcado a opção que o usuário escolheu se a página recarregar -->
                <?php foreach (servicos_disponiveis() as $servico): ?>
                    <option value="<?= htmlspecialchars($servico, ENT_QUOTES, 'UTF-8') ?>" <?= $dados['especialidade'] === $servico ? 'selected' : '' ?>>
                        <?= htmlspecialchars($servico, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <!-- Campo do Telefone -->
            <label for="telefone">Telefone / WhatsApp:</label><br>
            <input type="tel" name="telefone" id="telefone" maxlength="20" value="<?= htmlspecialchars($dados['telefone'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <!-- Campo do E-mail -->
            <label for="email">E-mail:</label><br>
            <input type="email" name="email" id="email" maxlength="100" value="<?= htmlspecialchars($dados['email'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <!-- Campo de escolher a Data -->
            <label for="data_agendamento">Data do Atendimento:</label><br>
            <input type="date" name="data_agendamento" id="data_agendamento" value="<?= htmlspecialchars($dados['data_agendamento'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <!-- Campo de escolher o Horário -->
            <label for="horario_inicio">Horário de Atendimento:</label><br>
            <input type="time" name="horario_inicio" id="horario_inicio" value="<?= htmlspecialchars($dados['horario_inicio'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <!-- Botões para Enviar ou Limpar o formulário -->
            <div style="margin-top: 10px;">
                <button type="submit">Cadastrar agendamento</button>
                <input type="reset" value="Limpar">
            </div>
        </form>
    </main>

    <!-- Coloca o rodapé do site aqui -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>