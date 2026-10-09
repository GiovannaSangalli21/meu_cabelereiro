<?php
// Lê as variáveis de ambiente da nuvem ou usa o localhost como padrão local
$host   = getenv('DB_HOST') ?: "192.168.10.48";
$port   = getenv('DB_PORT') ?: "5432";
$dbname = getenv('DB_NAME') ?: "roupa";
$user   = getenv('DB_USER') ?: "roupa";
$pass   = getenv('DB_PASS') ?: "giovanna";

try {
    $conexao = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}