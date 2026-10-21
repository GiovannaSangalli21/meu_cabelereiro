<?php
$host = '192.168.10.48';
$port = '5432';
$dbname = 'roupa';
$user = 'roupa';
$password = 'roupa';

try {
    $conexao = new PDO(
        "pgsql:host={$host};port={$port};dbname={$dbname}",
        $user,
        $password === false ? '' : $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Falha ao conectar ao PostgreSQL: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados. Verifique as configurações da aplicação.');
}
