-- SGC - Schema completo (MySQL 8+)
-- Obs: você pode substituir/rodar este arquivo no MySQL.

CREATE DATABASE IF NOT EXISTS sgc_clinica
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE sgc_clinica;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS log_acoes;
DROP TABLE IF EXISTS historico_alteracoes;
DROP TABLE IF EXISTS atendimentos;
DROP TABLE IF EXISTS servicos;
DROP TABLE IF EXISTS especialidades;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS usuarios;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  senha_hash VARCHAR(255) NOT NULL,
  cpf CHAR(11) NULL,
  telefone VARCHAR(20) NULL,
  tipo ENUM('ADMIN','ADM','MEDICO') NOT NULL DEFAULT 'ADM',
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_email (email),
  UNIQUE KEY uq_usuarios_cpf (cpf)
) ENGINE=InnoDB;

CREATE TABLE pacientes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  cpf CHAR(11) NULL,
  email VARCHAR(160) NULL,
  telefone VARCHAR(20) NULL,
  data_nascimento DATE NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  observacoes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pacientes_cpf (cpf),
  KEY idx_pacientes_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE especialidades (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  descricao VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_especialidades_nome (nome)
) ENGINE=InnoDB;

CREATE TABLE servicos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  especialidade_id BIGINT UNSIGNED NOT NULL,
  duracao_minutos INT UNSIGNED NOT NULL DEFAULT 30,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_servicos_especialidade (especialidade_id),
  CONSTRAINT fk_servicos_especialidade
    FOREIGN KEY (especialidade_id) REFERENCES especialidades(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE atendimentos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  paciente_id BIGINT UNSIGNED NOT NULL,
  medico_id BIGINT UNSIGNED NOT NULL,
  servico_id BIGINT UNSIGNED NOT NULL,
  data_hora DATETIME NOT NULL,
  status ENUM('AGENDADO','CONFIRMADO','EM_ATENDIMENTO','CONCLUIDO','CANCELADO','FALTOU') NOT NULL DEFAULT 'AGENDADO',
  evolucao TEXT NULL,
  anamnese TEXT NULL,
  criado_por BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_atendimentos_data (data_hora),
  CONSTRAINT fk_atendimentos_paciente FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_atendimentos_medico FOREIGN KEY (medico_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_atendimentos_servico FOREIGN KEY (servico_id) REFERENCES servicos(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_atendimentos_criado_por FOREIGN KEY (criado_por) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE historico_alteracoes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  tabela VARCHAR(64) NOT NULL,
  registro_id BIGINT UNSIGNED NOT NULL,
  acao ENUM('INSERT','UPDATE','DELETE') NOT NULL,
  usuario_id BIGINT UNSIGNED NULL,
  dados_antes JSON NULL,
  dados_depois JSON NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE log_acoes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id BIGINT UNSIGNED NULL,
  acao VARCHAR(40) NOT NULL,
  tabela VARCHAR(64) NOT NULL,
  registro_id BIGINT UNSIGNED NULL,
  descricao VARCHAR(255) NULL,
  ip VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE OR REPLACE VIEW vw_atendimentos_detalhado AS
SELECT a.id, a.data_hora, a.status,
       p.nome AS paciente_nome, p.cpf AS paciente_cpf,
       u.nome AS medico_nome, u.email AS medico_email,
       e.nome AS especialidade, s.nome AS servico, s.duracao_minutos,
       a.created_at, a.updated_at
FROM atendimentos a
JOIN pacientes p ON p.id = a.paciente_id
JOIN usuarios u ON u.id = a.medico_id
JOIN servicos s ON s.id = a.servico_id
JOIN especialidades e ON e.id = s.especialidade_id;
