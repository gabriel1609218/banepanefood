CREATE DATABASE IF NOT EXISTS banepanefoods CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE banepanefoods;

DROP VIEW IF EXISTS vw_dashboard_vendas;
DROP VIEW IF EXISTS vw_produtos_estoque;
DROP PROCEDURE IF EXISTS sp_listar_produtos;
DROP FUNCTION IF EXISTS fn_total_item;
DROP TRIGGER IF EXISTS trg_produtos_valores_positivos;
DROP TRIGGER IF EXISTS trg_itens_venda_positivos;
DROP TABLE IF EXISTS itens_venda;
DROP TABLE IF EXISTS administradores;
DROP TABLE IF EXISTS vendas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;

CREATE TABLE administradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO administradores (nome,email,senha) VALUES
('Administrador','admin@banepane.com','$2y$12$.ffCK24ukrwinUkx2Ng.luk7Q.TtNVfJT8BouBDHZVHNQv9wFVX8a');

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL UNIQUE,
  descricao VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT NOT NULL,
  nome VARCHAR(150) NOT NULL,
  descricao VARCHAR(255) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT NOT NULL DEFAULT 0,
  imagem VARCHAR(255) NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  telefone VARCHAR(30) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE vendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  data_venda DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('PENDENTE','PAGO','CANCELADO') NOT NULL DEFAULT 'PENDENTE',
  CONSTRAINT fk_venda_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
) ENGINE=InnoDB;

CREATE TABLE itens_venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_item_venda FOREIGN KEY (venda_id) REFERENCES vendas(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_produto FOREIGN KEY (produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;

INSERT INTO categorias (nome, descricao) VALUES
('Tradicionais','Pães de queijo clássicos e receitas especiais'),
('Recheados','Pães de queijo com recheios'),
('Especiais','Sabores diferenciados');

INSERT INTO produtos (categoria_id,nome,descricao,preco,estoque,imagem) VALUES
(1,'Pão de Queijo Tradicional','Pacote congelado de 1kg, receita tradicional.',30.00,50,'imagem/tradicional.jpeg'),
(3,'Chipa','Pacote congelado de 1kg, receita tradicional.',30.00,40,'imagem/chipa.jpeg'),
(2,'Pão de Queijo de Frango','Pacote congelado de 1kg recheado com frango.',34.00,35,'imagem/frango.jpeg'),
(2,'Pão de Queijo de Requeijão','Pacote congelado de 1kg com requeijão.',34.00,30,'imagem/requeijao.jpeg'),
(2,'Pão de Queijo de Calabresa','Pacote congelado de 1kg com calabresa.',34.00,25,'imagem/calabresa.jpeg'),
(3,'Pão de Queijo de Goiabada','Pacote congelado de 1kg com goiabada.',34.00,20,'imagem/goiabada.jpeg');

INSERT INTO clientes (nome,email,telefone) VALUES
('Cliente Exemplo','cliente@exemplo.com','44999999999');

DELIMITER $$
CREATE FUNCTION fn_total_item(p_quantidade INT, p_preco DECIMAL(10,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
  RETURN IFNULL(p_quantidade,0) * IFNULL(p_preco,0);
END$$

CREATE TRIGGER trg_produtos_valores_positivos
BEFORE UPDATE ON produtos
FOR EACH ROW
BEGIN
  IF NEW.preco < 0 THEN SET NEW.preco = 0; END IF;
  IF NEW.estoque < 0 THEN SET NEW.estoque = 0; END IF;
END$$

CREATE TRIGGER trg_itens_venda_positivos
BEFORE INSERT ON itens_venda
FOR EACH ROW
BEGIN
  IF NEW.quantidade < 1 THEN SET NEW.quantidade = 1; END IF;
  IF NEW.preco_unitario < 0 THEN SET NEW.preco_unitario = 0; END IF;
END$$

CREATE PROCEDURE sp_listar_produtos(
  IN p_busca VARCHAR(150),
  IN p_limite INT,
  IN p_offset INT
)
BEGIN
  SELECT p.id,p.nome,p.descricao,p.preco,p.estoque,p.imagem,p.ativo,c.id AS categoria_id,c.nome AS categoria
  FROM produtos p INNER JOIN categorias c ON c.id=p.categoria_id
  WHERE p.ativo=1
    AND (
      p_busca IS NULL OR p_busca=''
      OR p.nome LIKE CONCAT('%',p_busca,'%')
      OR p.descricao LIKE CONCAT('%',p_busca,'%')
      OR c.nome LIKE CONCAT('%',p_busca,'%')
    )
  ORDER BY p.id DESC
  LIMIT p_limite OFFSET p_offset;
END$$
DELIMITER ;

CREATE OR REPLACE VIEW vw_produtos_estoque AS
SELECT p.id,p.nome,c.nome AS categoria,p.preco,p.estoque,
       CASE WHEN p.estoque <= 10 THEN 'CRÍTICO' WHEN p.estoque <= 20 THEN 'BAIXO' ELSE 'NORMAL' END AS nivel_estoque
FROM produtos p JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1;

CREATE OR REPLACE VIEW vw_dashboard_vendas AS
WITH totais AS (
  SELECT v.id AS venda_id, v.data_venda, v.status,
         fn_total_item(iv.quantidade,iv.preco_unitario) AS total_item
  FROM vendas v JOIN itens_venda iv ON iv.venda_id=v.id
)
SELECT DATE(data_venda) AS dia, COUNT(DISTINCT venda_id) AS pedidos,
       ROUND(SUM(total_item),2) AS faturamento
FROM totais WHERE status <> 'CANCELADO'
GROUP BY DATE(data_venda);

CREATE OR REPLACE VIEW vw_vendas_produtos AS
SELECT p.id,p.nome,SUM(iv.quantidade) AS quantidade_vendida,
       ROUND(SUM(fn_total_item(iv.quantidade,iv.preco_unitario)),2) AS faturamento
FROM itens_venda iv JOIN produtos p ON p.id=iv.produto_id
JOIN vendas v ON v.id=iv.venda_id WHERE v.status <> 'CANCELADO'
GROUP BY p.id,p.nome;
