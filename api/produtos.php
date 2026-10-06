<?php
require_once __DIR__.'/../config/auth.php';
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];
function body() {
    return json_decode(file_get_contents('php://input'), true) ?? $_POST;
}
if ($method === 'GET') {
    $busca = trim($_GET['busca'] ?? '');
    $limite = max(1, min(100, (int)($_GET['limite'] ?? 50)));
    $offset = max(0, (int)($_GET['offset'] ?? 0));
    $stmt = $conn->prepare('CALL sp_listar_produtos(?,?,?)');
    $stmt->bind_param('sii', $busca, $limite, $offset);
    $stmt->execute();
    $res = $stmt->get_result();
    $dados = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    while ($conn->more_results()) {
        $conn->next_result();
        $conn->use_result();
    }
    echo json_encode($dados);
    exit;
}
exigirAdminApi();
$data = body();
if ($method === 'POST') {
    $stmt = $conn->prepare('INSERT INTO produtos(categoria_id,nome,descricao,preco,estoque,imagem) VALUES(?,?,?,?,?,?)');
    $stmt->bind_param('issdis', $data['categoria_id'], $data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['imagem']);
    echo json_encode(['ok'=>$stmt->execute(), 'id'=>$conn->insert_id, 'mensagem'=>$stmt->error ?: 'Produto cadastrado']);
    exit;
}
if ($method === 'PUT') {
    $stmt = $conn->prepare('UPDATE produtos SET categoria_id=?,nome=?,descricao=?,preco=?,estoque=?,imagem=? WHERE id=?');
    $stmt->bind_param('issdisi', $data['categoria_id'], $data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['imagem'], $data['id']);
    echo json_encode(['ok'=>$stmt->execute(), 'mensagem'=>$stmt->error ?: 'Produto atualizado']);
    exit;
}
if ($method === 'DELETE') {
    $id = (int)($data['id'] ?? 0);
    $stmt = $conn->prepare('DELETE FROM produtos WHERE id=?');
    $stmt->bind_param('i', $id);
    echo json_encode(['ok'=>$stmt->execute(), 'mensagem'=>$stmt->error ?: 'Produto excluído']);
    exit;
}
http_response_code(405);
echo json_encode(['ok'=>false, 'mensagem'=>'Método não permitido']);
