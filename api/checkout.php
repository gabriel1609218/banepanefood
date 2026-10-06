<?php
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');
if($_SERVER['REQUEST_METHOD']!=='POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'mensagem'=>'Método não permitido']);
    exit;
}
$data=json_decode(file_get_contents('php://input'),true)??[];
$cliente=$data['cliente']??[];
$itens=$data['itens']??[];
if(empty($cliente['nome'])||empty($cliente['email'])||empty($itens)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'mensagem'=>'Preencha cliente e carrinho.']);
    exit;
}
$conn->begin_transaction();
try {
    $s=$conn->prepare('INSERT INTO clientes(nome,email,telefone) VALUES(?,?,?) ON DUPLICATE KEY UPDATE nome=VALUES(nome),telefone=VALUES(telefone)');
    $s->bind_param('sss',$cliente['nome'],$cliente['email'],$cliente['telefone']);
    if(!$s->execute()) throw new Exception($s->error);
    $id=$conn->insert_id;
    if($id===0) {
        $q=$conn->prepare('SELECT id FROM clientes WHERE email=?');
        $q->bind_param('s',$cliente['email']);
        $q->execute();
        $id=$q->get_result()->fetch_assoc()['id'];
    }
    $s=$conn->prepare('INSERT INTO vendas(cliente_id,status) VALUES(?,"PAGO")');
    $s->bind_param('i',$id);
    if(!$s->execute())throw new Exception($s->error);
    $venda=$conn->insert_id;
    $item=$conn->prepare('INSERT INTO itens_venda(venda_id,produto_id,quantidade,preco_unitario) VALUES(?,?,?,?)');
    $estoque=$conn->prepare('UPDATE produtos SET estoque=estoque-? WHERE id=? AND estoque>=?');
    foreach($itens as $i) {
        $pid=(int)$i['id'];
        $qtd=max(1,(int)$i['quantidade']);
        $p=$conn->prepare('SELECT preco,estoque FROM produtos WHERE id=? FOR UPDATE');
        $p->bind_param('i',$pid);
        $p->execute();
        $produto=$p->get_result()->fetch_assoc();
        if(!$produto||$produto['estoque']<$qtd)throw new Exception('Estoque insuficiente para um produto.');
        $preco=(float)$produto['preco'];
        $item->bind_param('iiid',$venda,$pid,$qtd,$preco);
        if(!$item->execute())throw new Exception($item->error);
        $estoque->bind_param('iii',$qtd,$pid,$qtd);
        if(!$estoque->execute())throw new Exception($estoque->error);
    }
    $conn->commit();
    echo json_encode(['ok'=>true,'venda_id'=>$venda,'mensagem'=>'Pedido realizado com sucesso!']);
}catch(Throwable $e) {
    $conn->rollback();
    http_response_code(400);
    echo json_encode(['ok'=>false,'mensagem'=>$e->getMessage()]);
}
?>
