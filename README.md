EduConnect — Sistema de Gestão de Mentorias (MVP)

O **EduConnect** é uma plataforma web desenvolvida para conectar estudantes da rede pública de ensino a mentores voluntários para reforço escolar. Este repositório contém o Mínimo Produto Viável (MVP) operacional, focado em gerenciamento de alunos e registro de atendimentos.

---

1. Links de Gestão e Governança

*   **Link Público do Trello (Quadro de Tarefas):** [Acesse o Quadro do Trello Aqui]([https://trello.com](https://trello.com/b/Y6ctqSr5/projeto-3e-solucoes)) 
*   **Ambiente de Produção (URL Pública):** [Acesse o Sistema EduConnect](https://onrender.com) 

---

2. Diário de Bordo e Histórico de Engenharia

Este diário registra o fluxo de tomada de decisão técnica e as fases do ciclo de desenvolvimento do MVP:

### Fase 1: Alinhamento de Escopo e Arquitetura Banco de Dados
*   **Decisão:** Optou-se por PHP Estruturado nativo com PDO para máxima portabilidade em servidores de nuvem gratuitos.
*   **Desafio:** Garantir integridade de dados ao deletar alunos.
*   **Solução:** Implementação de constraint `ON DELETE CASCADE` na chave estrangeira das mentorias.

### Fase 2: Desenvolvimento do Front-End e Segurança
*   **Decisão:** Interface responsiva em CSS Grid/Flexbox puro, eliminando dependências externas (Bootstrap/Tailwind) para agilizar o carregamento em conexões lentas da rede pública.
*   **Desafio:** Risco de segurança por dados inseridos por usuários.
*   **Solução:** Implementação de validações JavaScript no cliente e uso obrigatório de `htmlspecialchars` e `Prepared Statements` no servidor contra ataques XSS e SQL Injection.

### Fase 3: Homologação e Deploy Continuo
*   **Decisão:** Utilização de variáveis de ambiente (`getenv`) para desacoplar credenciais locais das credenciais de produção.
*   **Desafio:** Upload de arquivos de configuração contendo senhas.
*   **Solução:** Criação de rotinas que buscam strings de conexão injetadas diretamente pelo container Linux (Render/Railway).

---

3. Manual de Suporte Técnico (Instalação Local)

Siga o passo a passo abaixo para rodar o projeto do zero em sua máquina local para fins de manutenção ou desenvolvimento.

### Pré-requisitos
*   Servidor local instalado (**XAMPP**, **WampServer** ou **Laragon** com PHP 8.0+ e MySQL).
*   **Git** instalado na máquina.

### Passo 1: Clonar o Repositório
Abra o seu terminal ou prompt de comando na pasta do seu servidor local (ex: `C:/xampp/htdocs/`) e execute:
```bash
git clone https://github.com
cd educonnect
```

### Passo 2: Configurar o Banco de Dados Local
1. Abra o painel de controle do XAMPP e inicialize os módulos **Apache** e **MySQL**.
2. Acesse o painel do **phpMyAdmin** pelo navegador em: `http://localhost/phpmyadmin/`.
3. Clique em **Novo** no menu lateral esquerdo para criar um novo banco.
4. Defina o nome exatamente como `educonnect` e clique em **Criar**.
5. Clique na aba **SQL** no topo da página, cole o script de estrutura contido na seção 4 deste manual e clique em **Executar**.

### Passo 3: Executar a Aplicação
1. Certifique-se de que a pasta `educonnect` está dentro do diretório raiz do servidor web local.
2. Acesse no seu navegador a URL: `http://localhost/educonnect/index.php`.
3. O sistema está pronto para uso e testes locais.

---

4. Modelagem e Estrutura do Banco de Dados

O banco de dados foi projetado seguindo as regras de integridade referencial da **3ª Forma Normal (3FN)**. O relacionamento é de **1 para Muitos (1:N)**, onde um aluno pode possuir múltiplas mentorias vinculadas, mas uma mentoria pertence a apenas um aluno.

### Script de Inicialização DDL (Data Definition Language)

```sql
-- Criação do banco de dados com codificação para suportar acentuação em português
CREATE DATABASE IF NOT EXISTS educonnect
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE educonnect;

-- Tabela de Alunos (Entidade Independente)
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE, -- Restrição de e-mail único no sistema
    serie VARCHAR(50) NOT NULL,
    data_nascimento DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de Mentorias (Entidade Dependente)
CREATE TABLE IF NOT EXISTS mentorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL, -- Chave Estrangeira ligada à tabela alunos
    mentor VARCHAR(100) NOT NULL,
    data_mentoria DATE NOT NULL,
    resumo TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Restrição de Integridade: Se o aluno for excluído, seu histórico de mentorias é apagado automaticamente
    FOREIGN KEY (aluno_id) 
        REFERENCES alunos(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB;
```

### Dicionário de Dados Resumido

#### Tabela `alunos`
*   `id`: Chave primária de auto-incremento.
*   `nome`: Armazena o nome completo do estudante (Limite: 100 caracteres).
*   `email`: Campo de texto indexado como único para impedir cadastros duplicados.
*   `serie`: Armazena o ano letivo selecionado na caixa de listagem.
*   `data_nascimento`: Campo do tipo `DATE` utilizado para o cálculo em tempo real da idade na interface.

#### Tabela `mentorias`
*   `id`: Chave primária de auto-incremento.
*   `aluno_id`: Chave estrangeira que garante que nenhuma mentoria seja registrada sem um aluno válido existente.
*   `mentor`: Armazena o nome do voluntário responsável pelo atendimento.
*   `data_mentoria`: Data em que o encontro de reforço aconteceu.
*   `resumo`: Tipo `TEXT` para permitir relatórios detalhados sem limite severo de caracteres.
