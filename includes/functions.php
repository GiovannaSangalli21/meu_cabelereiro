<?php
require_once __DIR__ . '/../database/connect.php';
require_once __DIR__ . '/paths.php';

function campo_post(string $campo): string
{
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

function iniciar_sessao(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    if (!session_start()) {
        throw new RuntimeException('Não foi possível iniciar a sessão.');
    }
}

function csrf_token(): string
{
    iniciar_sessao();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_token_valido(): bool
{
    iniciar_sessao();

    $token = $_POST['csrf_token'] ?? null;
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function servicos_disponiveis(): array
{
    return ['Escova', 'Corte', 'Luzes', 'Hidratação', 'Manicure'];
}

function comprimento_utf8(string $valor): ?int
{
    $quantidade = preg_match_all('/./us', $valor, $correspondencias);

    return $quantidade === false ? null : $quantidade;
}

function validar_dados_agendamento(
    string $nome,
    string $especialidade,
    string $telefone,
    string $email,
    string $data_agendamento,
    string $horario_inicio
): bool {
    $comprimento_nome = comprimento_utf8($nome);
    $comprimento_telefone = comprimento_utf8($telefone);
    $comprimento_email = comprimento_utf8($email);
    $data = DateTimeImmutable::createFromFormat('!Y-m-d', $data_agendamento);
    $erros_data = DateTimeImmutable::getLastErrors();
    $data_valida = $data !== false
        && $data->format('Y-m-d') === $data_agendamento
        && ($erros_data === false || ($erros_data['warning_count'] === 0 && $erros_data['error_count'] === 0));

    return $nome !== ''
        && $comprimento_nome !== null
        && $comprimento_nome <= 100
        && in_array($especialidade, servicos_disponiveis(), true)
        && $telefone !== ''
        && $comprimento_telefone !== null
        && $comprimento_telefone <= 20
        && filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        && $comprimento_email !== null
        && $comprimento_email <= 100
        && $data_valida
        && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $horario_inicio) === 1;
}

function cadastrar(
    PDO $conexao,
    string $nome,
    string $especialidade,
    string $telefone,
    string $email,
    string $horario_inicio,
    string $data_agendamento
): bool {
    try {
        $stmt = $conexao->prepare(
            'INSERT INTO agendamentos
                (nome_cliente, servico, telefone_cliente, email_cliente, data_agendamento, horario_inicio)
             VALUES
                (:nome, :especialidade, :telefone, :email, :data_agendamento, :horario_inicio)'
        );

        return $stmt->execute([
            ':nome' => $nome,
            ':especialidade' => $especialidade,
            ':telefone' => $telefone,
            ':email' => $email,
            ':data_agendamento' => $data_agendamento,
            ':horario_inicio' => $horario_inicio,
        ]);
    } catch (PDOException $e) {
        error_log('Falha ao cadastrar agendamento: ' . $e->getMessage());
        return false;
    }
}

function apagar(PDO $conexao, int $id): bool
{
    $stmt = $conexao->prepare('DELETE FROM agendamentos WHERE id = :id');
    return $stmt->execute([':id' => $id]) && $stmt->rowCount() > 0;
}

function relatorio(PDO $conexao): void
{
    $stmt = $conexao->query(
        'SELECT id, nome_cliente AS nome, servico AS especialidade,
                telefone_cliente AS telefone, email_cliente AS email,
                data_agendamento, horario_inicio
         FROM agendamentos
         ORDER BY data_agendamento, horario_inicio'
    );
    $agendamentos = $stmt->fetchAll();

    if (!$agendamentos) {
        echo '<p>Nenhum agendamento cadastrado.</p>';
        return;
    }

    foreach ($agendamentos as $agendamento) {
        $id = (int) $agendamento['id'];
        $nome = htmlspecialchars($agendamento['nome'], ENT_QUOTES, 'UTF-8');
        $servico = htmlspecialchars($agendamento['especialidade'], ENT_QUOTES, 'UTF-8');
        $telefone = htmlspecialchars($agendamento['telefone'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars((string) ($agendamento['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $data = htmlspecialchars((string) $agendamento['data_agendamento'], ENT_QUOTES, 'UTF-8');
        $horario = htmlspecialchars(substr((string) $agendamento['horario_inicio'], 0, 5), ENT_QUOTES, 'UTF-8');

        echo '<section class="agendamento">';
        echo '<strong>ID:</strong> ' . $id . '<br>';
        echo '<strong>Nome:</strong> ' . $nome . '<br>';
        echo '<strong>Serviço:</strong> ' . $servico . '<br>';
        echo '<strong>Telefone:</strong> ' . $telefone . '<br>';
        echo '<strong>E-mail:</strong> ' . $email . '<br>';
        echo '<strong>Data:</strong> ' . $data . '<br>';
        echo '<strong>Horário:</strong> ' . $horario . '<br><br>';
        echo '<a href="' . htmlspecialchars(app_url('app/update.php?id=' . $id), ENT_QUOTES, 'UTF-8') . '">Editar</a> ';
        echo '<form class="form-excluir" action="' . htmlspecialchars(app_url('app/delete.php'), ENT_QUOTES, 'UTF-8') . '" method="post" onsubmit="return confirm(\'Deseja realmente excluir este agendamento?\');">';
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<button type="submit">Excluir</button>';
        echo '</form>';
        echo '</section>';
    }
}

function consultar(PDO $conexao, int $id): ?array
{
    $stmt = $conexao->prepare(
        'SELECT id, nome_cliente AS nome, servico AS especialidade,
                telefone_cliente AS telefone, email_cliente AS email,
                data_agendamento, horario_inicio
         FROM agendamentos
         WHERE id = :id'
    );
    $stmt->execute([':id' => $id]);
    $agendamento = $stmt->fetch();

    return $agendamento === false ? null : $agendamento;
}

function atualizar(
    PDO $conexao,
    int $id,
    string $nome,
    string $especialidade,
    string $telefone,
    string $email,
    string $horario_inicio,
    string $data_agendamento
): bool {
    $stmt = $conexao->prepare(
        'UPDATE agendamentos
         SET nome_cliente = :nome,
             servico = :especialidade,
             telefone_cliente = :telefone,
             email_cliente = :email,
             horario_inicio = :horario_inicio,
             data_agendamento = :data_agendamento
         WHERE id = :id'
    );

    return $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':especialidade' => $especialidade,
        ':telefone' => $telefone,
        ':email' => $email,
        ':horario_inicio' => $horario_inicio,
        ':data_agendamento' => $data_agendamento,
    ]) && $stmt->rowCount() > 0;
}
