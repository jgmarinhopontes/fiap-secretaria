DROP DATABASE IF EXISTS fiap_secretaria;
CREATE DATABASE fiap_secretaria;
USE fiap_secretaria;

CREATE TABLE administradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL
);

CREATE TABLE alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  nascimento DATE NOT NULL,
  cpf VARCHAR(14) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE turmas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL UNIQUE,
  descricao VARCHAR(255) NOT NULL
);

CREATE TABLE matriculas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id INT NOT NULL,
  turma_id INT NOT NULL,
  data_matricula TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
  FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
);

-- TURMAS
INSERT INTO turmas (nome, descricao) VALUES 
('Banco de Dados I', 'Introdução a bancos relacionais, SQL e modelagem de dados.'),
('Banco de Dados II', 'Tópicos avançados em bancos de dados, normalização e transações.'),
('Desenvolvimento Web I', 'HTML, CSS, JavaScript e introdução ao desenvolvimento front-end.'),
('Desenvolvimento Web II', 'Back-end com PHP e MySQL, autenticação e APIs.'),
('Análise de Sistemas', 'Levantamento de requisitos, UML e engenharia de software.'),
('Estrutura de Dados', 'Listas, pilhas, filas, árvores e algoritmos de ordenação.'),
('Programação Orientada a Objetos', 'Conceitos de OOP em Java, encapsulamento, herança e polimorfismo.');

-- ALUNOS (todas datas <= 2005-12-31)
INSERT INTO alunos (nome, nascimento, cpf, email, senha, telefone, data_cadastro) VALUES
('Lucas Silva', '1998-04-09', '857.988.270-71', 'lucas.silva@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 92353-6781', '2024-08-12 23:14:52'),
('Mariana Oliveira', '1999-06-18', '504.410.323-57', 'mariana.oliveira@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 91069-4562', '2024-05-16 23:14:52'),
('Pedro Santos', '2005-05-04', '107.112.304-67', 'pedro.santos@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 97202-5442', '2024-09-10 23:14:52'),
('Amanda Costa', '2005-10-31', '984.519.574-52', 'amanda.costa@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 94814-7827', '2024-11-17 23:14:52'),
('Bruno Almeida', '2005-12-31', '921.939.612-52', 'bruno.almeida@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 94513-4338', '2024-06-04 23:14:52'),
('Fernanda Lima', '1994-03-08', '927.323.801-90', 'fernanda.lima@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 96533-7745', '2025-02-28 23:14:52'),
('Gabriel Souza', '1995-02-19', '129.699.645-49', 'gabriel.souza@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 96420-6832', '2024-09-27 23:14:52'),
('Beatriz Rocha', '2001-09-10', '149.279.352-57', 'beatriz.rocha@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 98271-2053', '2024-06-27 23:14:52'),
('Rafael Pereira', '1996-04-29', '903.703.905-70', 'rafael.pereira@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 95532-4661', '2024-06-27 23:14:52'),
('Juliana Carvalho', '2005-10-01', '540.744.306-30', 'juliana.carvalho@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 98328-7566', '2024-12-21 23:14:52'),
('Ricardo Mendes', '1991-01-21', '965.270.461-16', 'ricardo.mendes@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 92054-2965', '2025-03-16 23:14:52'),
('Tatiane Ribeiro', '2002-11-26', '150.869.375-84', 'tatiane.ribeiro@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 98590-4248', '2025-01-31 23:14:52'),
('Larissa Vieira', '1990-01-16', '344.614.676-35', 'larissa.vieira@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 92183-8902', '2024-05-04 23:14:52'),
('André Barbosa', '1989-11-08', '677.229.778-11', 'andre.barbosa@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 97960-7982', '2024-05-29 23:14:52'),
('Vanessa Teixeira', '2004-04-22', '167.801.656-84', 'vanessa.teixeira@email.com', '$2y$10$ExemploHashSenhaFicticiaABCDE', '(11) 91351-3026', '2024-07-10 23:14:52');

-- MATRÍCULAS
INSERT INTO matriculas (aluno_id, turma_id, data_matricula) VALUES 
(1, 5, '2024-07-13 23:14:52'),
(2, 6, '2024-09-27 23:14:52'),
(3, 7, '2024-12-14 23:14:52'),
(4, 3, '2024-10-07 23:14:52'),
(5, 1, '2024-08-02 23:14:52'),
(6, 6, '2025-03-03 23:14:52'),
(6, 7, '2024-05-30 23:14:52'),
(7, 2, '2024-06-12 23:14:52'),
(7, 7, '2024-12-31 23:14:52'),
(8, 7, '2024-06-19 23:14:52'),
(9, 2, '2025-02-09 23:14:52'),
(10, 3, '2025-04-10 23:14:52'),
(11, 1, '2024-05-02 23:14:52'),
(12, 5, '2024-06-26 23:14:52'),
(13, 5, '2024-08-05 23:14:52'),
(14, 2, '2025-01-02 23:14:52'),
(15, 4, '2025-01-17 23:14:52');

