<?php
require_once __DIR__ . '/../database/connect.php';

// =========================================================================
// --- FUNÇÕES DE CABELEIREIRAS ---
// =========================================================================

function cadastrar($conexao, $nome, $especialidade, $telefone, $email, $horario_inicio)
{
    $sql = "INSERT INTO cabeleireiras (nome, especialidade, telefone, email, horario_inicio) 
            VALUES (:nome, :especialidade, :telefone, :email, :horario_inicio)";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":especialidade", $especialidade);
        $stmt->bindValue(":telefone", $telefone);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":horario_inicio", $horario_inicio);

        $stmt->execute();
        echo "<p style='color:green; text-align:center;'>Cabeleireira cadastrada com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
    }
}

function apagar($conexao, $id)
{
    $sql = "DELETE FROM cabeleireiras WHERE id = :id";
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        echo "<p style='color:green; text-align:center;'>Registro deletado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Erro ao apagar: " . $e->getMessage() . "</p>";
    }
}

function relatorio($conexao)
{
    $sql = "SELECT * FROM cabeleireiras ORDER BY id";
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        $cabeleireiras = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cabeleireiras)) {
            echo "<p style='text-align:center;'>Nenhuma cabeleireira cadastrada.</p>";
            return;
        }

        foreach ($cabeleireiras as $c) {
            $id = $c['id'];
            $nome = htmlspecialchars($c['nome']);
            $especialidade = htmlspecialchars($c['especialidade']);
            $telefone = htmlspecialchars($c['telefone']);
            $email = htmlspecialchars($c['email']);
            $horario = htmlspecialchars($c['horario_inicio']);

            echo "<div style='background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #f0e1e6;'>";
            echo "<strong>ID:</strong> $id<br>";
            echo "<strong>Nome:</strong> $nome<br>";
            echo "<strong>Especialidade:</strong> $especialidade<br>";
            echo "<strong>Telefone:</strong> $telefone<br>";
            echo "<strong>E-mail:</strong> $email<br>";
            echo "<strong>Horário:</strong> $horario<br><br>";
            echo "<a href='update.php?id=$id' style='background: #2196f3; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none;'>Editar</a> ";
            echo "<a href='delete.php?id=$id' onclick=\"return confirm('Deseja realmente excluir?');\" style='background: #e53935; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none;'>Excluir</a>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Erro: " . $e->getMessage() . "</p>";
    }
}

function atualizar($conexao, $id, $nome, $especialidade, $telefone, $email, $horario_inicio)
{
    $sql = "UPDATE cabeleireiras SET 
                nome = :nome, 
                especialidade = :especialidade, 
                telefone = :telefone, 
                email = :email, 
                horario_inicio = :horario_inicio 
            WHERE id = :id";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":especialidade", $especialidade);
        $stmt->bindValue(":telefone", $telefone);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":horario_inicio", $horario_inicio);

        $stmt->execute();
        echo "<p style='color:green; text-align:center;'>Cadastro atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Erro ao atualizar: " . $e->getMessage() . "</p>";
    }
}

// =========================================================================
// --- FUNÇÕES DE AGENDAMENTO (CLIENTE) ---
// =========================================================================

function salvar_agendamento($conexao, $nome, $telefone, $cabeleireira_id, $servico, $data, $horario)
{
    $sql = "INSERT INTO agendamentos (nome_cliente, telefone_cliente, cabeleireira_id, servico, data_agendamento, horario) 
            VALUES (:nome, :telefone, :cabeleireira_id, :servico, :data, :horario)";

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":telefone", $telefone);
        $stmt->bindValue(":cabeleireira_id", $cabeleireira_id, PDO::PARAM_INT);
        $stmt->bindValue(":servico", $servico);
        $stmt->bindValue(":data", $data);
        $stmt->bindValue(":horario", $horario);

        $stmt->execute();
        echo "<p style='color:green; text-align:center;'>✨ Agendamento realizado com sucesso! Aguardamos por si.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Erro ao agendar: " . $e->getMessage() . "</p>";
    }
}

function buscar_cabeleireiras($conexao)
{
    $sql = "SELECT id, nome, especialidade FROM cabeleireiras ORDER BY nome";
    try {
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}