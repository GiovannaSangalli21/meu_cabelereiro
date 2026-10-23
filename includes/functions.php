<?php
// =============================================================================
// 1. IMPORTAÇÕES DE OUTROS ARQUIVOS
// =============================================================================

// Conecta com o banco de dados
require_once __DIR__ . '/../database/connect.php';

// Carrega as configurações de caminhos/links do site
require_once __DIR__ . '/paths.php';

// =============================================================================
// 2. SEGURANÇA E SESSÃO DO USUÁRIO
// =============================================================================

// Liga a sessão do PHP com opções de segurança ativadas (protege os cookies)
function iniciar_sessao(): void
{
    // Se a sessão já estiver aberta, não faz nada
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    // Configura os cookies da sessão para serem bem seguros
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true, // Impede o JavaScript de acessar o cookie
        'samesite' => 'Lax',
    ]);

    // Tenta iniciar a sessão. Se falhar, avisa
    if (!session_start()) {
        throw new RuntimeException('Não foi possível iniciar a sessão.');
    }
}

// Cria um código secreto único (Token) para evitar envios falsos de formulários
function csrf_token(): string
{
    iniciar_sessao();

    // Se ainda não existir um token para o usuário, cria um aleatório
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Confirma se o código enviado pelo formulário bate com o código salvo no servidor
function csrf_token_valido(): bool
{
    iniciar_sessao();

    $token = $_POST['csrf_token'] ?? null;
    
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token); // Compara de forma segura
}

// =============================================================================
// 3. FUNÇÕES AUXILIARES E CONFERÊNCIA DE DADOS
// =============================================================================

// Pega um dado enviado pelo formulário e limpa espaços vazios nas pontas
function campo_post(string $campo): string
{
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

// Retorna a lista de serviços que o sistema aceita
function servicos_disponiveis(): array
{
    return ['Escova', 'Corte', 'Luzes', 'Hidratação', 'Manicure'];
}

// Conta o tamanho de um texto sem dar erro em letras com acento ou emojis
function comprimento_utf8(string $valor): ?int
{
    $quantidade = preg_match_all('/./us', $valor, $correspondencias);
    return $quantidade === false ? null : $quantidade;
}

// Testa se todas as informações do agendamento são válidas
function validar_dados_agendamento(
    string $nome,
    string $especialidade,
    string $telefone,
    string $email,
    string $data_agendamento,
    string $horario_inicio
): bool {
    // Calcula o tamanho de cada texto
    $comprimento_nome     = comprimento_utf8($nome);
    $comprimento_telefone = comprimento_utf8($telefone);
    $comprimento_email    = comprimento_utf8($email);

    // Confera se a data existe e está no formato certo (Ano-Mês-Dia)
    $data        = DateTimeImmutable::createFromFormat('!Y-m-d', $data_agendamento);
    $erros_data  = DateTimeImmutable::getLastErrors();
    $data_valida = $data !== false
        && $data->format('Y-m-d') === $data_agendamento
        && ($erros_data === false || ($erros_data['warning_count'] === 0 && $erros_data['error_count'] === 0));

    // Retorna verdadeiro se TUDO estiver correto
    return $nome !== ''
        && $comprimento_nome !== null
        && $comprimento_nome <= 100 // Nome no máximo com 100 caracteres
        && in_array($especialidade, servicos_disponiveis(), true) // Serviço dentro da lista permitida
        && $telefone !== ''
        && $comprimento_telefone !== null
        && $comprimento_telefone <= 20 // Telefone com no máximo 20 caracteres
        && filter_var($email, FILTER_VALIDATE_EMAIL) !== false // E-mail válido
        && $comprimento_email !== null
        && $comprimento_email <= 100
        && $data_valida // Data válida
        && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D', $horario_inicio) === 1; // Horário no formato HH:MM
}

// =============================================================================
// 4. AÇÕES NO BANCO DE DADOS (C.R.U.D.)
// =============================================================================

// Salva um novo agendamento no banco de dados
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
        // Prepara o comando SQL de inserção
        $stmt = $conexao->prepare(
            'INSERT INTO agendamentos
                (nome_cliente, servico, telefone_cliente, email_cliente, data_agendamento, horario_inicio)
             VALUES
                (:nome, :especialidade, :telefone, :email, :data_agendamento, :horario_inicio)'
        );

        // Executa enviando os dados informados
        return $stmt->execute([
            ':nome'             => $nome,
            ':especialidade'    => $especialidade,
            ':telefone'         => $telefone,
            ':email'            => $email,
            ':data_agendamento' => $data_agendamento,
            ':horario_inicio'   => $horario_inicio,
        ]);
    } catch (PDOException $e) {
        // Se der erro no banco, grava no log do servidor
        error_log('Falha ao cadastrar agendamento: ' . $e->getMessage());
        return false;
    }
}

// Busca apenas um agendamento específico usando o ID
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

    // Se encontrou devolve os dados, se não encontrou devolve nulo
    return $agendamento === false ? null : $agendamento;
}

// Salva as alterações feitas em um agendamento existente
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
         SET nome_cliente     = :nome,
             servico          = :especialidade,
             telefone_cliente = :telefone,
             email_cliente    = :email,
             horario_inicio   = :horario_inicio,
             data_agendamento = :data_agendamento
         WHERE id = :id'
    );

    // Retorna verdadeiro se o comando rodar e alterar pelo menos 1 linha no banco
    return $stmt->execute([
        ':id'               => $id,
        ':nome'             => $nome,
        ':especialidade'    => $especialidade,
        ':telefone'         => $telefone,
        ':email'            => $email,
        ':horario_inicio'   => $horario_inicio,
        ':data_agendamento' => $data_agendamento,
    ]) && $stmt->rowCount() > 0;
}

// Deleta um agendamento do banco usando o ID
function apagar(PDO $conexao, int $id): bool
{
    $stmt = $conexao->prepare('DELETE FROM agendamentos WHERE id = :id');
    return $stmt->execute([':id' => $id]) && $stmt->rowCount() > 0;
}

// =============================================================================
// 5. EXIBIÇÃO DA LISTA DE AGENDAMENTOS NA TELA
// =============================================================================

// Busca todos os agendamentos e desenha os cartões na tela em HTML
function relatorio(PDO $conexao): void
{
    // Busca tudo ordenado por data e horário
    $stmt = $conexao->query(
        'SELECT id, nome_cliente AS nome, servico AS especialidade,
                telefone_cliente AS telefone, email_cliente AS email,
                data_agendamento, horario_inicio
         FROM agendamentos
         ORDER BY data_agendamento, horario_inicio'
    );
    $agendamentos = $stmt->fetchAll();

    // Se não tiver nenhum agendamento cadastrado
    if (!$agendamentos) {
        echo '<p>Nenhum agendamento cadastrado.</p>';
        return;
    }

    // Passa de um por um exibindo as informações
    foreach ($agendamentos as $agendamento) {
        // Limpa os dados contra injeção de código malicioso no navegador
        $id       = (int) $agendamento['id'];
        $nome     = htmlspecialchars($agendamento['nome'], ENT_QUOTES, 'UTF-8');
        $servico  = htmlspecialchars($agendamento['especialidade'], ENT_QUOTES, 'UTF-8');
        $telefone = htmlspecialchars($agendamento['telefone'], ENT_QUOTES, 'UTF-8');
        $email    = htmlspecialchars((string) ($agendamento['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $data     = htmlspecialchars((string) $agendamento['data_agendamento'], ENT_QUOTES, 'UTF-8');
        $horario  = htmlspecialchars(substr((string) $agendamento['horario_inicio'], 0, 5), ENT_QUOTES, 'UTF-8');

        // Imprime o cartão do agendamento
        echo '<section class="agendamento">';
        echo '<strong>ID:</strong> ' . $id . '<br>';
        echo '<strong>Nome:</strong> ' . $nome . '<br>';
        echo '<strong>Serviço:</strong> ' . $servico . '<br>';
        echo '<strong>Telefone:</strong> ' . $telefone . '<br>';
        echo '<strong>E-mail:</strong> ' . $email . '<br>';
        echo '<strong>Data:</strong> ' . $data . '<br>';
        echo '<strong>Horário:</strong> ' . $horario . '<br><br>';
        
        // Link para a página de edição
        echo '<a href="' . htmlspecialchars(app_url('app/update.php?id=' . $id), ENT_QUOTES, 'UTF-8') . '">Editar</a> ';
        
        // Formulário com botão para apagar (com token de segurança e aviso de confirmação)
        echo '<form class="form-excluir" action="' . htmlspecialchars(app_url('app/delete.php'), ENT_QUOTES, 'UTF-8') . '" method="post" onsubmit="return confirm(\'Deseja realmente excluir este agendamento?\');">';
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<button type="submit">Excluir</button>';
        echo '</form>';
        echo '</section>';
    }
}