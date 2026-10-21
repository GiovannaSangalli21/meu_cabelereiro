<?php
// Inclui o arquivo de conexão com o banco de dados
include "../database/connect.php";

// Função para cadastrar um novo agendamento no banco
function cadastrar($conexao, $nome, $especialidade, $telefone, $email, $horario_inicio, $data_agendamento)
{
    // Escreve o comando SQL para salvar os dados na tabela
    $sql = "INSERT INTO agendamentos (nome_cliente, servico, telefone_cliente, email_cliente, data_agendamento, horario_inicio) 
            VALUES ('$nome', '$especialidade', '$telefone', '$email', '$data_agendamento', '$horario_inicio')";

    // Executa o comando no banco de dados
    $resultado = mysqli_query($conexao, $sql);
    
    // Retorna se deu certo ou errado
    return $resultado;
}

// Função para apagar um agendamento pelo número do ID
function apagar($conexao, $id)
{
    // Escreve o comando SQL para deletar o registro pelo ID
    $sql = "DELETE FROM agendamentos WHERE id = $id";
    
    // Manda o banco rodar o comando de apagar
    $resultado = mysqli_query($conexao, $sql);
    
    // Retorna se conseguiu apagar
    return $resultado;
}

// Função para buscar e mostrar todos os agendamentos na tela
function relatorio($conexao)
{
    // Escreve o comando SQL para pegar todos os agendamentos organizados por data e hora
    $sql = "SELECT * FROM agendamentos ORDER BY data_agendamento, horario_inicio";
    
    // Executa a busca no banco
    $resultado = mysqli_query($conexao, $sql);

    // Se não encontrar nenhum agendamento cadastrado
    if (mysqli_num_rows($resultado) == 0) {
        echo "<p>Nenhum agendamento cadastrado.</p>";
        return; // Sai da função
    }

    // Enquanto houver agendamentos na lista, pega um por um e exibe na tela
    while ($agendamento = mysqli_fetch_assoc($resultado)) {
        echo '<section class="agendamento">';
        echo '<strong>ID:</strong> ' . $agendamento['id'] . '<br>';
        echo '<strong>Nome:</strong> ' . $agendamento['nome_cliente'] . '<br>';
        echo '<strong>Serviço:</strong> ' . $agendamento['servico'] . '<br>';
        echo '<strong>Telefone:</strong> ' . $agendamento['telefone_cliente'] . '<br>';
        echo '<strong>E-mail:</strong> ' . $agendamento['email_cliente'] . '<br>';
        echo '<strong>Data:</strong> ' . $agendamento['data_agendamento'] . '<br>';
        echo '<strong>Horário:</strong> ' . $agendamento['horario_inicio'] . '<br><br>';
        
        // Botão para ir para a página de edição
        echo '<a href="update.php?id=' . $agendamento['id'] . '">Editar</a> ';
        
        // Formulário com botão para apagar o agendamento
        echo '<form action="delete.php" method="post" style="display:inline;">';
        echo '<input type="hidden" name="id" value="' . $agendamento['id'] . '">';
        echo '<button type="submit" onclick="return confirm(\'Deseja excluir?\')">Excluir</button>';
        echo '</form>';
        echo '</section><hr>';
    }
}

// Função para buscar as informações de um único agendamento pelo ID
function consultar($conexao, $id)
{
    // Comando SQL para buscar apenas o registro com o ID selecionado
    $sql = "SELECT id, nome_cliente AS nome, servico AS especialidade, telefone_cliente AS telefone, email_cliente AS email, data_agendamento, horario_inicio FROM agendamentos WHERE id = $id";
    
    // Executa a busca
    $resultado = mysqli_query($conexao, $sql);
    
    // Retorna os dados do agendamento organizados em uma lista
    return mysqli_fetch_assoc($resultado);
}

// Função para alterar as informações de um agendamento existente
function atualizar($conexao, $id, $nome, $especialidade, $telefone, $email, $horario_inicio, $data_agendamento)
{
    // Escreve o comando SQL para atualizar os campos no banco
    $sql = "UPDATE agendamentos SET 
            nome_cliente = '$nome', 
            servico = '$especialidade', 
            telefone_cliente = '$telefone', 
            email_cliente = '$email', 
            horario_inicio = '$horario_inicio', 
            data_agendamento = '$data_agendamento' 
            WHERE id = $id";

    // Manda o banco atualizar os dados
    $resultado = mysqli_query($conexao, $sql);
    
    // Retorna se deu certo a atualização
    return $resultado;
}