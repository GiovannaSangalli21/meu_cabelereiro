<?php
// Guarda as informações de acesso ao banco de dados em variáveis
$host = getenv('DB_HOST') ?: '192.168.10.48';    // Endereço onde o banco está instalado
$port = getenv('DB_PORT') ?: '5432';             // Porta de entrada do PostgreSQL
$dbname = getenv('DB_NAME') ?: 'roupa';          // Nome do banco de dados
$user = getenv('DB_USER') ?: 'roupa';            // Nome do usuário de acesso
$password = getenv('DB_PASSWORD') ?: 'giovanna'; // Senha do usuário

// O bloco "try" tenta executar a conexão com o banco
try {
    // Cria a conexão com o banco de dados PostgreSQL usando o PDO
    $conexao = new PDO(
        "pgsql:host={$host};port={$port};dbname={$dbname}", // Junta o endereço, porta e nome do banco
        $user,                                              // Usuário
        $password === false ? '' : $password,               // Senha (se não tiver nada, envia em branco)
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Faz o PHP avisar na hora se der algum erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Organiza os resultados do banco em uma lista fácil de ler
            PDO::ATTR_EMULATE_PREPARES => false,          // Aumenta a segurança contra ataques no banco
        ]
    );
    
// O bloco "catch" entra em ação apenas se a conexão lá em cima der errado
} catch (PDOException $e) {
    error_log('Falha ao conectar ao PostgreSQL: ' . $e->getMessage()); // Salva o detalhe do erro em um arquivo de texto escondido no servidor
    http_response_code(500); // Informa ao navegador que deu erro interno no servidor
    exit('Não foi possível conectar ao banco de dados. Verifique as configurações da aplicação.'); // Mostra este aviso amigável na tela e para de rodar o código
}