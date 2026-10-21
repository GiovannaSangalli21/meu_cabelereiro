<?php
// Inicia a sessão para usar variáveis salvas no sistema
session_start();

// Puxa o arquivo que tem as funções de apagar e conectar no banco
include "../includes/functions.php";

// Cria variáveis para guardar a mensagem e saber se deu certo
$mensagem = "";
$sucesso = false;

// Checa se o formulário foi enviado pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifica se o ID do agendamento foi enviado no formulário
    if (isset($_POST['id'])) {
        // Pega o ID enviado e guarda na variável $id
        $id = $_POST['id'];
        
        // Chama a função para apagar no banco de dados usando o ID
        $sucesso = apagar($conexao, $id);
        
        // Se a função conseguiu apagar
        if ($sucesso) {
            $mensagem = "Agendamento excluído com sucesso!";
        } else {
            // Se ocorreu algum problema no banco
            $mensagem = "Erro ao excluir o agendamento.";
        }
    } else {
        // Se não mandaram o ID do agendamento
        $mensagem = "ID do agendamento não encontrado.";
    }
    
} else {
    // Se a pessoa tentou abrir a página direto sem ser pelo formulário
    $mensagem = "Acesso inválido.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Aceita acentos e caracteres especiais -->
    <title>Excluir Agendamento</title> <!-- Título da aba do navegador -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Arquivo de estilo CSS -->
</head>
<body>
    <!-- Coloca o cabeçalho do site na página -->
    <?php include "../includes/header.php"; ?>

    <main>
        <!-- Se deu certo, mostra a mensagem em verde -->
        <?php if ($sucesso): ?>
            <p style="color: green;"><?php echo $mensagem; ?></p>
        <!-- Se deu errado, mostra a mensagem em vermelho -->
        <?php else: ?>
            <p style="color: red;"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <br>
        <!-- Botão para voltar para a lista de agendamentos -->
        <a href="select.php">Voltar ao relatório</a>
    </main>

    <!-- Coloca o rodapé do site na página -->
    <?php include "../includes/footer.php"; ?>
</body>
</html>