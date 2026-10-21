<!-- Avisa ao navegador que o documento é em HTML5 -->
<!DOCTYPE html>
<!-- Começa a página e define o idioma como português do Brasil -->
<html lang="pt-br">
<head>
    <!-- Garante que acentos e ç apareçam do jeito certo -->
    <meta charset="UTF-8">
    
    <!-- Faz a página se adaptar ao tamanho da tela do celular -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Nome que fica escrito lá em cima na aba do navegador -->
    <title>Início - Studio Bella</title>
    
    <!-- Puxa os estilos de cores e formatação do arquivo CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Puxa o cabeçalho (menu topo) que está em outro arquivo -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Conteúdo principal que fica no meio da tela -->
    <main>
        <!-- Título principal da página -->
        <h2>Bem-vindo ao Studio Bella</h2>
        
        <!-- Texto explicativo para quem está acessando -->
        <p>Acesse o menu acima para realizar agendamentos, consultar relatórios ou gerir o seu sistema.</p>
    </main>

    <!-- Puxa o rodapé que fica lá embaixo na página -->
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>