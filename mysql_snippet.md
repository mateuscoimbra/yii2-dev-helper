# MySQL — Snippet Base Completo para Criação de Tabelas 🐘

Este guia fornece um snippet SQL **altamente comentado e abrangente** para a criação de tabelas MySQL, cobrindo os tipos de dados mais comuns, configurações de colunas, índices, chaves estrangeiras (Foreign Keys) e constraints. Ideal para desenvolvedores iniciantes no Yii2 que precisam de um modelo sólido para seus bancos de dados.

---

## 📌 Configurações Iniciais Importantes ⚙️

Antes de criar suas tabelas, é uma boa prática gerenciar a verificação de chaves estrangeiras.

```sql
-- Ativar a verificação de chaves estrangeiras (é o padrão, mas útil para explicitar)
SET FOREIGN_KEY_CHECKS = 1;

-- Desativar a verificação de chaves estrangeiras (útil para importação de dados ou reestruturação)
-- Geralmente usado antes de um bloco de CREATE TABLE / ALTER TABLE e reativado depois.
SET FOREIGN_KEY_CHECKS = 0;
````

-----

## 🏗️ Modelo de Tabela Completo (`CREATE TABLE`) 🚀

Este exemplo demonstra a criação de uma tabela `usuarios` com diversas configurações e tipos de dados.

```sql
-- Exclui a tabela se ela já existir para garantir uma criação limpa
DROP TABLE IF EXISTS `usuarios`;

-- Criação da tabela 'usuarios'
CREATE TABLE `usuarios` (
    -- COLUNAS BÁSICAS
    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID único do usuário (Chave Primária)',
    `nome_completo` VARCHAR(255) NOT NULL COMMENT 'Nome completo do usuário',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Endereço de e-mail do usuário (único)',
    `senha_hash` VARCHAR(255) NOT NULL COMMENT 'Hash da senha do usuário',

    -- TIPOS DE DADOS COMUNS
    `idade` TINYINT(3) UNSIGNED NULL DEFAULT NULL COMMENT 'Idade do usuário (0-255, opcional)',
    `genero` ENUM('M', 'F', 'O') NULL DEFAULT NULL COMMENT 'Gênero do usuário (Masculino, Feminino, Outro)',
    `data_nascimento` DATE NULL DEFAULT NULL COMMENT 'Data de nascimento do usuário (apenas data)',
    `hora_cadastro` TIME NULL DEFAULT NULL COMMENT 'Hora exata do cadastro (apenas hora)',
    `ultimo_login` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data e hora do último login (padrão: data/hora atual)',
    `saldo` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Saldo financeiro do usuário (10 dígitos, 2 decimais)',
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Status de ativação do usuário (0=inativo, 1=ativo)',
    `biografia` TEXT NULL DEFAULT NULL COMMENT 'Biografia longa do usuário',
    `configuracoes_json` JSON NULL DEFAULT NULL COMMENT 'Configurações do usuário em formato JSON (MySQL 5.7+)',
    `ip_acesso` VARCHAR(45) NULL DEFAULT NULL COMMENT 'Endereço IP do último acesso (IPv4 ou IPv6)',
    `uuid_usuario` CHAR(36) UNIQUE NULL DEFAULT NULL COMMENT 'UUID único do usuário (se usado)',

    -- CHAVES PRIMÁRIAS E ÍNDICES
    PRIMARY KEY (`id`), -- Define 'id' como chave primária
    UNIQUE KEY `idx_email_unique` (`email`), -- Índice único para o e-mail
    INDEX `idx_nome_completo` (`nome_completo`(191)), -- Índice para pesquisa por nome (prefixo para VARCHAR longo)
    INDEX `idx_ativo_data_nascimento` (`ativo`, `data_nascimento`), -- Índice composto para otimizar WHERE com múltiplos campos

    -- TIMESTAMPS AUTOMÁTICOS (para rastreamento de criação/modificação)
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp de criação do registro',
    `atualizado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp da última atualização do registro',

    -- CONSTRAINTS (Exemplo de CHECK constraint - MySQL 8.0.16+)
    -- CONSTRAINT `chk_idade_positiva` CHECK (`idade` >= 0 AND `idade` <= 120),
    -- CONSTRAINT `chk_saldo_nao_negativo` CHECK (`saldo` >= 0),

    -- OPÇÕES DA TABELA
    ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela para armazenar informações dos usuários do sistema'
);

-- Exemplo de uso de um CHECK constraint em MySQL mais antigo (via trigger, se necessário)
-- Ou para versões de MySQL 8.0.16+ que suportam CHECK diretamente.
-- ALTER TABLE `usuarios` ADD CONSTRAINT `chk_idade_positiva` CHECK (`idade` >= 0 AND `idade` <= 120);
-- ALTER TABLE `usuarios` ADD CONSTRAINT `chk_saldo_nao_negativo` CHECK (`saldo` >= 0);

```

-----

## 🔑 Chaves Estrangeiras (Foreign Keys) 🔗

As Chaves Estrangeiras garantem a integridade referencial entre tabelas, ligando uma coluna em uma tabela à Chave Primária de outra tabela.

### 1\. **Criação da Tabela Referenciada (`cargos`)**

```sql
DROP TABLE IF EXISTS `cargos`;
CREATE TABLE `cargos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID único do cargo',
    `nome_cargo` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nome do cargo (ex: Administrador, Editor)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabela para armazenar cargos de usuários';

-- Inserindo alguns dados de exemplo
INSERT INTO `cargos` (`nome_cargo`) VALUES ('Administrador'), ('Gerente'), ('Colaborador');
```

### 2\. **Adicionando a Foreign Key na Tabela `usuarios`**

```sql
-- Primeiro, adicione a coluna que será a Foreign Key
ALTER TABLE `usuarios`
ADD COLUMN `cargo_id` INT(11) NULL DEFAULT NULL COMMENT 'ID do cargo do usuário';

-- Em seguida, adicione a restrição de Foreign Key
ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usuarios_cargo_id`
FOREIGN KEY (`cargo_id`) REFERENCES `cargos`(`id`)
ON DELETE SET NULL -- Se o cargo for deletado, o campo cargo_id em usuarios será NULO
ON UPDATE CASCADE; -- Se o ID do cargo for atualizado, a mudança será refletida em usuarios
```

### Opções de `ON DELETE` e `ON UPDATE`:

  * **`CASCADE`**: Se a linha pai for excluída/atualizada, as linhas filhas correspondentes também são excluídas/atualizadas.
  * **`SET NULL`**: Se a linha pai for excluída/atualizada, as colunas da chave estrangeira na linha filha são definidas como `NULL`. (A coluna deve aceitar `NULL`).
  * **`RESTRICT`** (Padrão): Impede a exclusão/atualização da linha pai se houver linhas filhas referenciando-a.
  * **`NO ACTION`**: Semelhante a `RESTRICT`, mas a verificação é adiada para o final da transação.

-----

## ➕ Outras Constraints e Opções de Colunas 📋

  * **`NOT NULL`**: A coluna deve sempre conter um valor.
  * **`NULL`**: A coluna pode conter um valor `NULL` (ausência de dados).
  * **`DEFAULT valor`**: Define um valor padrão para a coluna se nenhum valor for especificado durante a inserção.
  * **`AUTO_INCREMENT`**: Usado com chaves primárias numéricas para gerar automaticamente valores únicos incrementais.
  * **`UNIQUE`**: Garante que todos os valores em uma coluna (ou conjunto de colunas) sejam diferentes.
  * **`COMMENT 'seu comentário'`**: Adiciona um comentário descritivo à coluna ou tabela, útil para documentação no banco de dados.

-----

## 🔍 Exemplos de Índices Adicionais 🚀

Índices melhoram o desempenho de consultas `SELECT`.

```sql
-- Índice para pesquisa rápida em nome_completo e email juntos (composto)
CREATE INDEX `idx_nome_email` ON `usuarios` (`nome_completo`, `email`);

-- Índice para campos booleanos ou ENUM quando usados em WHERE para otimização
CREATE INDEX `idx_ativo` ON `usuarios` (`ativo`);

-- Índice de texto completo (FULLTEXT) para pesquisa em colunas TEXT/VARCHAR (requer MyISAM ou InnoDB com FULLTEXT habilitado)
-- ALTER TABLE `usuarios` ADD FULLTEXT `ft_biografia` (`biografia`);
-- SELECT * FROM `usuarios` WHERE MATCH(`biografia`) AGAINST ('palavra chave');
```

-----

## 🧹 Limpeza (Opcional) 🗑️

Para remover as tabelas criadas:

```sql
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `cargos`;
```

-----

## 📌 Notas Finais 💡

  - Sempre use `VARCHAR` em vez de `CHAR` para strings de comprimento variável para economizar espaço, a menos que o comprimento seja fixo e conhecido (ex: UUIDs).
  - `TEXT` é para strings longas. Use `VARCHAR(255)` para strings mais curtas.
  - Escolha o tipo de dado mais apropriado para cada coluna para otimizar armazenamento e desempenho.
  - Planeje seus índices com cuidado: muitos índices podem piorar o desempenho de `INSERT`/`UPDATE`/`DELETE`.

-----