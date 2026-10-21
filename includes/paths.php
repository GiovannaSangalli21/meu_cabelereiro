<?php

function app_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $baseDir = dirname($scriptName);

    if (basename($baseDir) === 'app') {
        $baseDir = dirname($baseDir);
    }

    if ($baseDir === '/' || $baseDir === '\\') {
        $baseDir = '';
    }

    return $scheme . '://' . $host . $baseDir;
}

function app_url(string $path = ''): string
{
    $base = rtrim(app_base_url(), '/');
    $relative = '/' . ltrim($path, '/\\');

    return $base . $relative;
}
