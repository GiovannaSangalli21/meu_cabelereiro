<?php
// Liga a sessão para salvar e usar dados do usuário
session_start();

// Puxa o arquivo com as funções de consultar e atualizar no banco
include "../includes/functions.php";

// Cria uma variável vazia para guardar a mensagem de retorno
$mensagem = "";

// Pega o ID que veio na URL da página (ex: update.php?id=2)
if (isset($_GET['id'])) {
    $id =$_GET['id'];
} else {
    // Se não tiver ID na URL, manda a pessoa de volta para a lista
    header("Location: select.php");
    exit;
}

// Verifica se a pessoa clicou no botão para enviar o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega as informações alteradas no formulário
    $nome =$_POST['nome'];
    $especialidade =$_POST['especialidade'];
    $telefone =$_POST['telefone'];
    $email =$_POST['email'];
    $data_agendamento =$_POST['data_agendamento'];
    $horario_inicio =$_POST['horario_inicio'];

    // Chama a função que altera os dados no banco de dados
    $sucesso = atualizar($conexao,$id, $nome,$especialidade, $telefone,$email, $horario_inicio,$data_agendamento);

    // Se salvou no banco com sucesso
    if ($sucesso) {$mensagem = "Agendamento atualizado com sucesso!";
    } else {
        // Se deu algum erro ao salvar no banco
        $mensagem = "Erro ao atualizar os dados.";
    }
}

// Busca os dados antigos do agendamento para preencher os campos do formulário
$agendamento = consultar($conexao,$id);

// Se o agendamento não existir no banco, volta para a lista
if (!$agendamento) {
    header("Location: select.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Permite usar acentos no texto -->
    <title>Editar Agendamento</title> <!-- Título da aba do navegador -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Arquivo de estilo CSS -->
</head>
<body>
    <!-- Coloca o cabeçalho do site na página -->
    <?php include "../includes/header.php"; ?>

    <main>
        <h1>Editar Agendamento</h1>

        <!-- Se tiver alguma mensagem (sucesso ou erro), mostra ela aqui -->
        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <!-- Formulário para mandar os dados atualizados para esta mesma página -->
        <form action="update.php?id=<?php echo $id; ?>" method="post">
            <!-- Campo do Nome -->
            <div>
                <label for="nome">Nome Completo:</label><br>
                <input type="text" id="nome" name="nome" value="<?php echo $agendamento['nome']; ?>" required>
            </div>
            <br>
            <!-- Caixinha para escolher o Serviço -->
            <div>
                <label for="especialidade">Serviço:</label><br>
                <select id="especialidade" name="especialidade" required>
                    <!-- Compara o serviço do banco com cada opção para deixar marcado o serviço correto -->
                    <option value="Escova" <?php if ($agendamento['especialidade'] == 'Escova') echo 'selected'; ?>>Escova</option>
                    <option value="Corte" <?php if ($agendamento['especialidade'] == 'Corte') echo 'selected'; ?>>Corte</option>
                    <option value="Luzes" <?php if ($agendamento['especialidade'] == 'Luzes') echo 'selected'; ?>>Luzes</option>
                    <option value="Hidratação" <?php if ($agendamento['especialidade'] == 'Hidratação') echo 'selected'; ?>>Hidratação</option>
                    <option value="Manicure" <?php if ($agendamento['especialidade'] == 'Manicure') echo 'selected'; ?>>Manicure</option>
                </select>
            </div>
            <br>
            <!-- Campo do Telefone -->
            <div>
                <label for="telefone">Telefone:</label><br>
                <input type="tel" id="telefone" name="telefone" value="<?php echo $agendamento['telefone']; ?>" required>
            </div>
            <br>
            <!-- Campo do E-mail -->
            <div>
                <label for="email">E-