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
    $r=$conn->query('SELECT id,nome,email,telefone FROM clientes ORDER BY id DESC');
    echo json_encode($r->fetch_all(MYSQLI_ASSOC));
    exit;
}
if($method==='POST') {
    $s=$conn->prepare('INSERT INTO clientes(nome,email,telefone) VALUES(?,?,?)');
    $s->bind_param('sss',$data['nome'],$data['email'],$data['telefone']);
    echo json_encode(['ok'=>$s->execute(),'id'=>$conn->insert_id,'mensagem'=>$s->error?:'Cliente cadastrado']);
    exit;
}
if($method==='PUT') {
    $s=$conn->prepare('UPDATE clientes SET nome=?,email=?,telefone=? WHERE id=?');
    $s->bind_param('sssi',$data['nome'],$data['email'],$data['telefone'],$data['id']);
    echo json_encode(['ok'=>$s->execute(),'mensagem'=>$s->error?:'Cliente atualizado']);
    exit;
}
if($method==='DELETE') {
    $s=$conn->prepare('DELETE FROM clientes WHERE id=?');
    $s->bind_param('i',$data['id']);
    echo json_encode(['ok'=>$s->execute(),'mensagem'=>$s->error?:'Cliente excluído']);
    exit;
}
?>
