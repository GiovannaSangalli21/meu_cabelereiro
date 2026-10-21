<?php
require_once __DIR__ . '/../database/connect.php';

function csrf_token()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_token_valido()
{
    return isset($_SESSION['csrf_token'], $_POST['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function campo_post($nome)
{
    $valor = $_POST[$nome] ?? '';

    return is_string($valor) ? trim($valor) : '';
}

function comprimento_texto($texto)
{
    $comprimento = preg_match_all('/./us', $texto);

    return $comprimento === false ? PHP_INT_MAX : $comprimento;
}

function validar_dados_agendamento($nome, $servico, $telefone, $email, $data, $horario)
{
    $servicos = ['Escova', 'Corte', 'Luzes', 'Hidratação', 'Manicure'];
    $data_tem_formato = preg_match('/^\d{4}-\d{2}-\d{2}$/D', $data) === 1;
    $data_valida = $data_tem_formato ? DateTimeImmutable::createFromFormat('!Y-m-d', $data) : false;
    $erros_data = DateTimeImmutable::getLastErrors();

    return $nome !== ''
        && comprimento_texto($nome) <= 100
        && in_array($servico, $servicos, true)
        && $telefone !== ''
        && comprimento_texto($telefone) <= 20
        && filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        && comprimento_texto($email) <= 100
        && $data_tem_formato
        && $data_valida !== false
        && $data_valida->format('Y-m-d') === $data
        && ($erros_data === false || ($erros_data['warning_count'] === 0 && $erros_data['error_count'] === 0))
        && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $horario) === 1;
}

function cadastrar($conexao, $nome, $especialidade, $telefone, $email, $horario_inicio, $data_agendamento)
{
    $sql = 'INSERT INTO agendamentos
                (nome_cliente, servico, telefone_cliente, email_cliente, data_agendamento, horario_inicio)
            VALUES
                (:nome, :servico, :telefone, :email, :data_agendamento, :horario_inicio)';

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':servico' => $especialidade,
            ':telefone' => $telefone,
            ':email' => $email,
            ':data_agendamento' => $data_agendamento,
            ':horario_inicio' => $horario_inicio,
        ]);

        return true;
    } catch (PDOException $e) {
        error_log('Erro ao cadastrar agendamento: ' . $e->getMessage());
        return false;
    }
}

function apagar($conexao, $id)
{
    try {
        $stmt = $conexao->prepare('DELETE FROM agendamentos WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log('Erro ao apagar agendamento: ' . $e->getMessage());
        return false;
    }
}

function relatorio($conexao)
{
    try {
        $stmt = $conexao->query(
            'SELECT id, nome_cliente AS nome, servico AS especialidade,
                    telefone_cliente AS telefone, email_cliente AS email,
                    data_agendamento, horario_inicio
             FROM agendamentos
             ORDER BY data_agendamento, horario_inicio, id'
        );
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao carregar agendamentos: ' . $e->getMessage());
        echo '<p class="mensagem-erro">Não foi possível carregar os agendamentos. Verifique a conexão e a estrutura do banco de dados.</p>';
        return;
    }

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
        $data = htmlspecialchars((string) ($agendamento['data_agendamento'] ?? ''), ENT_QUOTES, 'UTF-8');
        $horario = htmlspecialchars(substr((string) ($agendamento['horario_inicio'] ?? ''), 0, 5), ENT_QUOTES, 'UTF-8');

        echo '<section class="agendamento">';
        echo '<strong>ID:</strong> ' . $id . '<br>';
        echo '<strong>Nome:</strong> ' . $nome . '<br>';
        echo '<strong>Serviço:</strong> ' . $servico . '<br>';
        echo '<strong>Telefone:</strong> ' . $telefone . '<br>';
        echo '<strong>E-mail:</strong> ' . $email . '<br>';
        echo '<strong>Data:</strong> ' . $data . '<br>';
        echo '<strong>Horário:</strong> ' . $horario . '<br><br>';
        echo '<a href="update.php?id=' . $id . '">Editar</a> ';
        echo '<form class="form-excluir" action="delete.php" method="post" onsubmit="return confirm(\'Deseja realmente excluir este agendamento?\');">';
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<button type="submit">Excluir</button>';
        echo '</form>';
        echo '</section>';
    }
}

function consultar($conexao, $id)
{
    $stmt = $conexao->prepare(
        'SELECT id, nome_cliente AS nome, servico AS especialidade,
                telefone_cliente AS telefone, email_cliente AS email,
                data_agendamento, horario_inicio
         FROM agendamentos
         WHERE id = :id'
    );
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function atualizar($conexao, $id, $nome, $especialidade, $telefone, $email, $horario_inicio, $data_agendamento)
{
    $sql = 'UPDATE agendamentos
            SET nome_cliente = :nome,
                servico = :servico,
                telefone_cliente = :telefone,
                email_cliente = :email,
                horario_inicio = :horario_inicio,
                data_agendamento = :data_agendamento
            WHERE id = :id';

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':servico' => $especialidade,
            ':telefone' => $telefone,
            ':email' => $email,
            ':horario_inicio' => $horario_inicio,
            ':data_agendamento' => $data_agendamento,
        ]);

        return true;
    } catch (PDOException $e) {
        error_log('Erro ao atualizar agendamento: ' . $e->getMessage());
        return false;
    }
}
