<?php
require_once __DIR__.'/../config/auth.php';
exigirAdminApi();
?>
<?php
require_once __DIR__.'/../config/database.php';
header('Content-Type: application/json; charset=utf-8');
function allRows($conn,$sql) {
    $r=$conn->query($sql);
    return $r?$r->fetch_all(MYSQLI_ASSOC):[];
}
$estoque=allRows($conn,'SELECT * FROM vw_produtos_estoque ORDER BY estoque ASC');
$diario=allRows($conn,'SELECT * FROM vw_dashboard_vendas ORDER BY dia DESC LIMIT 30');
$ranking=allRows($conn,'SELECT * FROM vw_vendas_produtos ORDER BY quantidade_vendida DESC LIMIT 10');
$r=$conn->query("SELECT COUNT(*) pedidos, COALESCE(SUM(fn_total_item(iv.quantidade,iv.preco_unitario)),0) faturamento FROM vendas v JOIN itens_venda iv ON iv.venda_id=v.id WHERE v.status<>'CANCELADO'");
$res=$r->fetch_assoc();
$clientes=$conn->query('SELECT COUNT(*) total FROM clientes')->fetch_assoc()['total'];
$produtos=$conn->query('SELECT COUNT(*) total FROM produtos WHERE ativo=1')->fetch_assoc()['total'];
echo json_encode(['resumo'=>['pedidos'=>(int)$res['pedidos'],'faturamento'=>(float)$res['faturamento'],'clientes'=>(int)$clientes,'produtos'=>(int)$produtos],'estoque'=>$estoque,'diario'=>$diario,'ranking'=>$ranking]);
?>
