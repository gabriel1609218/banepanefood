<?php
$titulo='Banepane Foods - Pão de Queijo Congelado';
require '../templates/header.php';
?>
<section class="hero rounded-4 p-5 mb-5">
<div class="hero-content text-center text-white">
<h1 class="display-4 fw-bold">Pão de queijo congelado</h1>
<p class="lead">Prático, saboroso e pronto para assar.</p>
<a href="produtos.php" class="btn btn-warning btn-lg">Comprar agora</a>
</div>
</section>
<section class="row g-4 mb-5">
<div class="col-md-4">
<div class="card h-100 shadow-sm">
<div class="card-body">
<h3>🧀 Qualidade</h3>
<p>Ingredientes selecionados e sabores para toda a família.</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card h-100 shadow-sm">
<div class="card-body">
<h3>❄️ Congelado</h3>
<p>Conserve no freezer e asse quando quiser.</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card h-100 shadow-sm">
<div class="card-body">
<h3>🚚 Pedido fácil</h3>
<p>Escolha os produtos, monte o carrinho e finalize.</p>
</div>
</div>
</div>
</section>
<section id="sobre" class="row align-items-center g-4">
<div class="col-md-6">
<h2>Sobre a Banepane Foods</h2>
<p>Somos uma loja de pão de queijo congelado.</p>
</div>
<div class="col-md-6">
<img src="../imagem/sobre.png" class="img-fluid rounded-4" alt="Produtos Banepane">
</div>
</section>
<?php
require '../templates/footer.php';
?>
