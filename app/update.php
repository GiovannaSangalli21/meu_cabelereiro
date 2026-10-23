<?php
// 1. CARREGA AS FUNÇÕES PRINCIPAIS DO SISTEMA
require_once __DIR__ . '/../includes/functions.php';

// 2. PEGA E VALIDA O ID DA URL (Ex: update.php?id=5)
$id = filter_var($_GET['id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

// Se não enviou um ID válido, envia a pessoa de volta para a lista de agendamentos
if ($id === false) {
    header('Location: ' . app_url('app/select.php'));
    exit;
}

// 3. BUSCA OS DADOS ATUAIS DO AGENDAMENTO NO BANCO
try {
    $agendamento = consultar($conexao,$id);
} catch (PDOException $e) {
    error_log('Falha ao consultar agendamento para edição: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível carregar o agendamento. Tente novamente mais tarde.');
}

// Se o agendamento não existir no banco, redireciona para a lista
if ($agendamento === null) {
    header('Location: ' . app_url('app/select.php'));
    exit;
}

// 4. PREPARA OS DADOS QUE VAN PREENCHER O FORMULÁRIO NA TELA
$dados = [
    'nome'             => (string) $agendamento['nome'],
    'especialidade'    => (string) $agendamento['especialidade'],
    'telefone'         => (string) $agendamento['telefone'],
    'email'            => (string) ($agendamento['email'] ?? ''),
    'data_agendamento' => substr((string) $agendamento['data_agendamento'], 0, 10), 'horario_inicio'   => substr((string)$agendamento['horario_inicio'], 0, 5),
];

$mensagem = '';$mensagem_erro = false;

// 5. QUANDO O USUÁRIO CLICA NO BOTÃO DE SALVAR (ENVIA O FORMULÁRIO)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os novos dados que o usuário digitou no formulário
    foreach ($dados as$campo => $valor) {$dados[$campo] = campo_post($campo);
    }

    // Testa se todos os dados foram preenchidos corretamente (e-mail, data, etc)
    if (!validar_dados_agendamento(
        $dados['nome'],$dados['especialidade'],
        $dados['telefone'],$dados['email'],
        $dados['data_agendamento'],$dados['horario_inicio']
    )) {
        $mensagem = 'Verifique os dados informados e preencha todos os campos corretamente.';$mensagem_erro = true;
    } else {
        // Tenta salvar as mudanças no banco de dados
        try {
            if (atualizar(
                $conexao,$id,
                $dados['nome'],$dados['especialidade'],
                $dados['telefone'],$dados['email'],
                $dados['horario_inicio'],$dados['data_agendamento']
            )) {
                $mensagem = 'Agendamento atualizado com sucesso!';
            } else {
                $mensagem = 'Não foi possível atualizar o agendamento.';
                $mensagem_erro = true;
            }
        } catch (PDOException $e) {
            error_log('Falha ao atualizar agendamento: ' . $e->getMessage());
            http_response_code(500);
            $mensagem = 'Ocorreu um erro ao atualizar o agendamento. Tente novamente mais tarde.';
            $mensagem_erro = true;
        }
    }
}

// 6. FUNÇÃO DE SEGURANÇA (Evita injeção de códigos maliciosos no HTML)
$escapar = static fn (string $valor): string => htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="<?= $escapar(app_url('css/style.css')) ?>">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <h1>Editar Agendamento</h1>

        <!-- Exibe mensagem de sucesso (verde) ou erro (vermelho) -->
        <?php if ($mensagem !== ''): ?>
            <p class="<?= $mensagem_erro ? 'mensagem-erro' : 'mensagem' ?>">
                <?= $escapar($mensagem) ?>
            </p>
        <?php endif; ?>

        <!-- Formulario preenchido com os dados do agendamento -->
        <form action="<?= $escapar(app_url('app/update.php?id=' . $id)) ?>" method="post">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" maxlength="100" value="<?= $escapar($dados['nome']) ?>" required>

            <label for="especialidade">Serviço:</label>
            <select id="especialidade" name="especialidade" required>
                <option value="">Selecione uma opção...</option>
                <?php foreach (servicos_disponiveis() as $servico): ?>
                    <option value="<?= $escapar($servico) ?>" <?= $dados['especialidade'] ===$servico ? 'selected' : '' ?>>
                        <?= $escapar($servico) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" maxlength="20" value="<?= $escapar($dados['telefone']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" maxlength="100" value="<?= $escapar($dados['email']) ?>" required>

            <label for="data_agendamento">Data do Atendimento:</label>
            <input type="date" id="data_agendamento" name="data_agendamento" value="<?= $escapar($dados['data_agendamento']) ?>" required>

            <label for="horario_inicio">Horário de Atendimento:</label>
            <input type="time" id="horario_inicio" name="horario_inicio" value="<?= $escapar($dados['horario_inicio']) ?>" required>

            <button type="submit">Sal