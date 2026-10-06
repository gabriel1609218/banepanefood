<?php
require_once __DIR__.'/../config/auth.php';
exigirAdmin();
?>
<?php
$titulo='Administração';
require '../templates/header.php';
?>
<h1>Administração</h1>

<ul class="nav nav-tabs" role="tablist">
<li class="nav-item">
<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#prod">Produtos</button>
</li>
<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#cat">Categorias</button>
</li>
<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#cli">Clientes</button>
</li>
</ul>
<div class="tab-content border border-top-0 p-3">
<div class="tab-pane fade show active" id="prod">
<form id="produtoForm" class="row g-2 mb-3">
<input type="hidden" name="id">
<div class="col-md-4">
<input class="form-control" name="nome" placeholder="Nome" required>
</div>
<div class="col-md-3">
<select class="form-select" name="categoria_id" id="categoriaSelect" required>
</select>
</div>
<div class="col-md-2">
<input class="form-control" name="preco" type="number" step="0.01" min="0" placeholder="Preço" required>
</div>
<div class="col-md-2">
<input class="form-control" name="estoque" type="number" min="0" placeholder="Estoque" required>
</div>
<div class="col-md-12">
<input class="form-control" name="descricao" placeholder="Descrição" required>
</div>
<div class="col-md-12">
<input class="form-control" name="imagem" placeholder="Imagem, ex.: imagem/tradicional.jpeg">
</div>
<div>
<button class="btn btn-warning">Salvar produto</button>
<button type="button" id="cancelarProduto" class="btn btn-secondary">Limpar</button>
</div>
</form>
<div id="prodTabela">
</div>
</div>
<div class="tab-pane fade" id="cat">
<form id="categoriaForm" class="row g-2 mb-3">
<input type="hidden" name="id">
<div class="col-md-4">
<input class="form-control" name="nome" placeholder="Nome" required>
</div>
<div class="col-md-6">
<input class="form-control" name="descricao" placeholder="Descrição">
</div>
<div class="col-md-2">
<button class="btn btn-warning w-100">Salvar</button>
</div>
</form>
<div id="catTabela">
</div>
</div>
<div class="tab-pane fade" id="cli">
<form id="clienteForm" class="row g-2 mb-3">
<input type="hidden" name="id">
<div class="col-md-4">
<input class="form-control" name="nome" placeholder="Nome" required>
</div>
<div class="col-md-3">
<input class="form-control" name="email" type="email" placeholder="E-mail" required>
</div>
<div class="col-md-3">
<input class="form-control" name="telefone" placeholder="Telefone" required>
</div>
<div class="col-md-2">
<button class="btn btn-warning w-100">Salvar</button>
</div>
</form>
<div id="cliTabela">
</div>
</div>
</div>
<script src="js/admin.js">
</script>
<?php
require '../templates/footer.php';
?>
