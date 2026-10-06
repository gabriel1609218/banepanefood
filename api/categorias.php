<?php
require_once __DIR__.'/../config/auth.php';
exigirAdminApi();
?>
<?php
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');
$method=$_SERVER['REQUEST_METHOD'];
$data=json_decode(file_get_contents('php://input'),true)??$_POST;
if($method==='GET') {
    $r=$conn->query('SELECT id,nome,descricao FROM categorias ORDER BY nome');
    echo json_encode($r->fetch_all(MYSQLI_ASSOC));
    exit;
}
if($method==='POST') {
    $s=$conn->prepare('INSERT INTO categorias(nome,descricao) VALUES(?,?)');
    $s->bind_param('ss',$data['nome'],$data['descricao']);
    echo json_encode(['ok'=>$s->execute(),'id'=>$conn->insert_id,'mensagem'=>$s->error?:'Categoria cadastrada']);
    exit;
}
if($method==='PUT') {
    $s=$conn->prepare('UPDATE categorias SET nome=?,descricao=? WHERE id=?');
    $s->bind_param('ssi',$data['nome'],$data['descricao'],$data['id']);
    echo json_encode(['ok'=>$s->execute(),'mensagem'=>$s->error?:'Categoria atualizada']);
    exit;
}
if($method==='DELETE') {
    $s=$conn->prepare('DELETE FROM categorias WHERE id=?');
    $s->bind_param('i',$data['id']);
    echo json_encode(['ok'=>$s->execute(),'mensagem'=>$s->error?:'Categoria excluída']);
    exit;
}
?>
