##  Criação banco de dados usando SQL

CREATE DATABASE IF NOT EXISTS gustavo_db -- cria o banco de dados se ele não existir 
	DEFAULT CHARACTER SET utf8mb4 -- define o charset moderno (suporta acentos/emoji)
    COLLATE utf8mb4_general_ci; -- define collation (regras de ordenação)

## Apagar banco de dados    

DROP DATABASE IF EXISTS gustavo_db; -- deleta o banco de dados se ele existir

## Adicionando uma coluna
ALTER TABLE cadastros
ADD COLUMN foto VARCHAR(255) AFTER telefone;