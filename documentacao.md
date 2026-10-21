# Meu Cabeleireiro

Aplicação PHP para cadastrar, consultar, editar e excluir agendamentos de um salão. A aplicação usa PostgreSQL e PDO; não inclui autenticação de usuários.

## Requisitos

- PHP 8.1 ou superior, com `PDO` e `pdo_pgsql` habilitados.
- PostgreSQL.
- Servidor web configurado para executar PHP.

## Configuração

1. Crie o banco de dados `meu_cabelereiro` (ou configure outro nome).
2. Configure estas variáveis de ambiente no processo do servidor PHP:

   | Variável | Padrão | Descrição |
   | --- | --- | --- |
   | `DB_HOST` | `127.0.0.1` | Endereço do PostgreSQL |
   | `DB_PORT` | `5432` | Porta do PostgreSQL |
   | `DB_NAME` | `meu_cabelereiro` | Nome do banco |
   | `DB_USER` | `postgres` | Usuário do banco |
   | `DB_PASSWORD` | vazio | Senha do banco |

   Não salve senhas de produção nos arquivos do projeto. Configure-as no ambiente do servidor.

3. Em uma instalação nova, execute os scripts SQL nesta ordem:

   1. [`database/schema.pgsql`](./database/schema.pgsql)
   2. [`database/indexes.pgsql`](./database/indexes.pgsql)

   Se o banco já tiver a tabela `agendamentos` criada pelo esquema antigo do projeto, execute também [`database/migrate_legacy_schema.pgsql`](./database/migrate_legacy_schema.pgsql) entre os passos 1 e 2. Dados antigos na tabela `cabeleireiras` não são migrados automaticamente.

4. Habilite o projeto no servidor web e acesse `index.php`.

## Funcionalidades

- Cadastro de agendamentos com nome, serviço, telefone, e-mail, data e horário.
- Relatório ordenado por data e horário.
- Edição e exclusão de agendamentos.
- Validação dos dados também no servidor, consultas preparadas e proteção CSRF nos formulários de alteração.

## Estrutura

- `app/`: páginas de cadastro, listagem, edição e exclusão.
- `includes/`: cabeçalho, rodapé e funções da aplicação.
- `database/`: conexão PDO e scripts de criação do esquema e índice.
- `css/`: estilos.
