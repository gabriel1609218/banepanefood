USE banepanefoods;

DROP PROCEDURE IF EXISTS sp_listar_produtos;

DELIMITER $$

CREATE PROCEDURE sp_listar_produtos(
  IN p_busca VARCHAR(150),
  IN p_limite INT,
  IN p_offset INT
)
BEGIN
  SELECT
    p.id,
    p.categoria_id,
    p.nome,
    p.descricao,
    p.preco,
    p.estoque,
    p.imagem,
    p.ativo,
    c.nome AS categoria
  FROM produtos p
  INNER JOIN categorias c ON c.id = p.categoria_id
  WHERE p.ativo = 1
    AND (
      p_busca IS NULL
      OR p_busca = ''
      OR p.nome LIKE CONCAT('%', p_busca, '%')
      OR p.descricao LIKE CONCAT('%', p_busca, '%')
      OR c.nome LIKE CONCAT('%', p_busca, '%')
    )
  ORDER BY p.id DESC
  LIMIT p_offset, p_limite;
END$$

DELIMITER ;

-- Teste: deve listar os produtos da categoria cujo nome contenha "recheados".
CALL sp_listar_produtos('recheados', 50, 0);
