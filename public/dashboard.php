<?php
require_once __DIR__.'/../config/auth.php';
exigirAdmin();
$titulo='Dashboard';
require '../templates/header.php';
?>
<h1>Dashboard de vendas</h1>
<p class="text-muted">Indicadores calculados diretamente do banco de dados.</p>

<div id="dashboardErro"></div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Faturamento</div>
                <div id="totalFaturamento" class="fs-4 fw-bold">R$ 0,00</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Pedidos</div>
                <div id="totalPedidos" class="fs-4 fw-bold">0</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Clientes</div>
                <div id="totalClientes" class="fs-4 fw-bold">0</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Produtos ativos</div>
                <div id="totalProdutos" class="fs-4 fw-bold">0</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3>Ranking de produtos</h3>
                <p class="text-muted">Produtos ordenados pela quantidade vendida.</p>
                <div id="produtoMaisVendido" class="fw-bold mb-3"></div>
                <div id="ranking"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h3>Estoque</h3>
                <p class="text-muted">Quantidade disponível por produto.</p>
                <div id="estoque"></div>
                <div id="estoqueCritico"></div>
            </div>
        </div>
    </div>
</div>

<script src="js/dashboard.js"></script>
<?php require '../templates/footer.php'; ?>
