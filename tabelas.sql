CREATE TABLE IF NOT EXISTS anunciante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS anuncio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anunciante INT NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    ano INT NOT NULL,
    cor VARCHAR(30) NOT NULL,
    quilometragem INT NOT NULL,
    descricao TEXT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    estado CHAR(2) NOT NULL,
    cidade VARCHAR(60) NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anunciante) REFERENCES anunciante(id)
);

CREATE TABLE IF NOT EXISTS foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio INT NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_anuncio) REFERENCES anuncio(id)
);

CREATE TABLE IF NOT EXISTS interesse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_anuncio INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    mensagem TEXT NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anuncio) REFERENCES anuncio(id)
);

INSERT INTO anunciante (nome, cpf, email, senha_hash, telefone) VALUES
('João da Silva', '111.222.333-44', 'joao@vendacarros.com', '$2y$10$xfLOehF68iP9/Thm91SaKuEI6pw/OyH9qGoPQRo/8m.B4HTNRIBH.', '(34) 99999-0001');

INSERT INTO anunciante (nome, cpf, email, senha_hash, telefone) VALUES
('Guilherme Botelho', '222.333.444-55', 'guilherme.botelho@ufu.br', '$2y$10$W0pUyiHauUIin24m7LEpYeXG/fLAouMbubIq4l7B2wVzBp/2iZvxy', '(34) 99999-0002'),
('Kevin', '333.444.555-66', 'kevinmsb7@ufu.br', '$2y$10$W0pUyiHauUIin24m7LEpYeXG/fLAouMbubIq4l7B2wVzBp/2iZvxy', '(34) 99999-0003');

INSERT INTO anuncio (id_anunciante, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade) VALUES
(1, 'Fiat', 'Uno', 2018, 'Vermelho', 62000, 'Veículo em ótimo estado de conservação, único dono, revisões em dia na concessionária. Pneus novos, ar-condicionado gelando, sem batidas ou amassados. Aceito trocas por veículo de menor valor.', 28000.00, 'MG', 'Uberlândia'),
(1, 'Volkswagen', 'Gol', 2019, 'Branco', 45000, 'Carro revisado, pneus em bom estado, único dono.', 35000.00, 'MG', 'Uberlândia'),
(1, 'Chevrolet', 'Onix', 2020, 'Prata', 30000, 'Baixa quilometragem, todo revisado, garantia de fábrica.', 45000.00, 'MG', 'Uberlândia'),
(1, 'Fiat', 'Argo', 2021, 'Preto', 20000, 'Seminovo, completo, único dono.', 52000.00, 'MG', 'Uberlândia'),
(1, 'Ford', 'Ka', 2017, 'Cinza', 70000, 'Econômico, ótimo para o dia a dia.', 25000.00, 'MG', 'Uberlândia'),
(1, 'Hyundai', 'HB20', 2020, 'Azul', 40000, 'Bem conservado, revisões em dia.', 42000.00, 'MG', 'Uberlândia');

INSERT INTO interesse (id_anuncio, nome, telefone, mensagem) VALUES
(1, 'Carlos Souza', '(31) 99123-4567', 'Olá, tenho interesse no carro. Posso visitar no final de semana?'),
(1, 'Ana Lima', '(11) 98765-4321', 'Aceita trocar por moto? Tenho uma Honda CB 300 2019.');
