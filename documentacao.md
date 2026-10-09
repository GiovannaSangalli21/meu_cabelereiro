# 💈 Documentação: Meu Cabeleireiro

## Visão Geral
* **Nome do projeto:** Meu Cabeleireiro
* **Descrição:** Sistema web simples para gestão de clientes e autenticação de acessos em salões de beleza e cabeleireiros.
* **Tecnologias:** PHP, PostgreSQL, HTML5 e CSS3.

---

## ⚙️ 1. Funções do Sistema

* **Cadastrar Clientes (CREATE):** Permite registar novos clientes com nome, telefone/WhatsApp e e-mail no banco de dados.
* **Autenticar Acesso (READ_WHERE):** Valida e-mail e senha de acesso administrativo na página `login.php` para libertar o painel.
* **Consultar Clientes (READ):** Exibe o relatório e a lista com todos os clientes cadastrados.
* **Atualizar Dados do Cliente (UPDATE):** Permite editar as informações de contato do cliente.
* **Excluir Cliente (DELETE):** Remove definitivamente o registo de um cliente do sistema.

---

## 📌 2. Requisitos Funcionais

* **RF01 - Cadastro de Clientes:** Registar clientes recolhendo nome, telefone/WhatsApp e e-mail.
* **RF02 - Login de Acesso:** Validar as credenciais de acesso no ficheiro `login.php` através de consultas preparadas (`PDO`).
* **RF03 - Listagem de Clientes:** Consultar e carregar os dados dos clientes cadastrados diretamente do PostgreSQL.
* **RF04 - Edição de Cadastro:** Permitir alterar as informações de contato dos clientes.
* **RF05 - Exclusão de Cadastro:** Permitir apagar o registo de um cliente.

---

## 🛡️ 3. Requisitos Não Funcionais

* **Segurança dos Dados:** Criptografia de senhas (`password_hash`) para o utilizador administrativo e proteção contra injeção SQL nas consultas através de `PDO`.
* **Interface Responsiva:** Design simples, moderno e adaptável a ecrãs de computadores, tablets e telemóveis.
* **Gestão de Falhas:** Tratamento de exceções para erros de ligação ou de tabelas em falta no PostgreSQL, evitando mensagens confusas ao utilizador.
* **Compatibilidade:** Acesso direto pelos principais navegadores (Chrome, Edge, Firefox, Safari) sem necessidade de instalar extensões.

---

## 🗄️ 4. Estrutura da Base de Dados

### Tabela `usuarios` (Acesso do Administrador)
| Campo | Tipo de Dado | Restrições |
| :--- | :--- | :--- |
| `id` | Inteiro | Chave Primária, Auto Incremento (`SERIAL`) |
| `nome` | Texto (`VARCHAR(100)`) | Obrigatório (`NOT NULL`) |
| `email` | Texto (`VARCHAR(100)`) | Obrigatório, Único (`UNIQUE`) |
| `senha` | Texto (`VARCHAR(255)`) | Obrigatório (`NOT NULL`) |

### Tabela `clientes`
| Campo | Tipo de Dado | Restrições |
| :--- | :--- | :--- |
| `id` | Inteiro | Chave Primária, Auto Incremento (`SERIAL`) |
| `nome` | Texto (`VARCHAR(100)`) | Obrigatório (`NOT NULL`) |
| `telefone` | Texto (`VARCHAR(20)`) | Obrigatório (`NOT NULL`) |
| `email` | Texto (`VARCHAR(100)`) | Opcional |

---

## 🔄 5. Fluxo de Utilização

1. O administrador acede ao sistema e faz login com e-mail e senha no `login.php`.
2. O sistema valida as credenciais na tabela `usuarios` do PostgreSQL.
3. O administrador acede ao painel restrito para cadastrar novos clientes ou visualizar o relatório.
4. É possível editar ou remover qualquer cliente da lista a qualquer momento.
5. O administrador encerra a sessão com a opção de logout.