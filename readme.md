## Studio Bella
 O Studio Bella é um sistema simples e fácil de usar, feito para organizar e agilizar a marcação de horários em um salão de beleza.

 O objetivo principal é ajudar a ver toda a agenda do salão em um só lugar, permitindo controlar desde o cadastro de um horário até o serviço que vai ser feito.

1.  (Funções do Projeto)
Cadastrar um horário (CREATE): Para começar, o usuário cadastra um novo agendamento. Nessa tela, dá para preencher:

Nome do cliente

Serviço que vai fazer (corte, escova, etc.)

Telefone / WhatsApp

E-mail

Data do atendimento

Horário de início


Depois de cadastrado, esse horário fica salvo no sistema e pode ser visto ou mudado a qualquer momento.

Ver todos os horários (READ): Nesta página, dá para ver uma lista com todos os agendamentos do salão e acompanhar os horários do dia.

Ver detalhes de um agendamento (READ_WHERE): Aqui dá para buscar e ver todas as informações de apenas um cliente ou horário específico.

Apagar um agendamento (DELETE): Serve para cancelar e apagar um horário da lista caso o cliente desista.

Mudar as informações (UPDATE): Serve para alterar os dados de um agendamento que já foi feito (como mudar o dia, o horário ou o serviço).

Com isso, o sistema faz todas as tarefas principais de um site (criar, ler, alterar e apagar).

2.1 O que o sistema precisa ter (Requisitos Funcionais)
RF01 - Cadastrar Horários
O sistema tem que deixar cadastrar novos atendimentos com nome, serviço, telefone, e-mail, data e horário.

RF02 - Listar Agendamentos
O sistema tem que mostrar uma lista com todos os horários marcados, organizados por data e hora.

RF03 - Buscar um Horário Específico
O sistema tem que conseguir achar e mostrar os detalhes de um agendamento usando o número de identificação (ID) dele.

RF04 - Editar Agendamento
O sistema tem que permitir mudar qualquer informação de um horário que já foi cadastrado.

RF05 - Excluir Agendamento
O sistema tem que permitir apagar um agendamento do banco de dados quando necessário.

2.2 Como o sistema deve ser (Requisitos Não Funcionais)
RNF01 - Fácil de Usar
O site tem que ser simples, bonito e funcionar bem tanto no computador quanto na tela do celular.

RNF02 - Funcionar em Qualquer Navegador
O site tem que abrir normalmente em navegadores comuns (Chrome, Firefox, Edge, Safari) sem precisar instalar nada no computador.


## tabela:
´´´ mermaid
---
Title: Studio Bella
---

erDiagram
    usuarios {
        int id PK "AUTO INCREMENT"
        VARCHAR(100) nome "NOT NULL"
        VARCHAR(100) email "NOT NULL"
        VARCHAR(255) senha "NOT NULL"
    }

    clientes {
        int id PK "AUTO INCREMENT"
        VARCHAR(100) nome "NOT NULL"
        VARCHAR(20) telefone "NOT NULL"
        VARCHAR(100) email "NOT NULL"
    }

    cabeleireiras {
        int id PK "AUTO INCREMENT"
        VARCHAR(100) nome "NOT NULL"
        VARCHAR(50) especialidade "NOT NULL"
    }

    agendamentos {
        int id PK "AUTO INCREMENT"
        int id_cliente FK "NOT NULL"
        int id_cabeleireira FK "NOT NULL"
        VARCHAR(50) servico "NOT NULL"
        DATE data_agendamento "NOT NULL"
        TIME horario_inicio "NOT NULL"
    }

    clientes ||--o{ agendamentos : "realiza"
    cabeleireiras ||--o{ agendamentos : "atende"
    usuarios ||--o{ clientes : "cadastra"
    ´´´