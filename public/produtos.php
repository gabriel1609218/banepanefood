<?php
$titulo='Produtos';
require '../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
<h1>Produtos</h1>
<div class="input-group search-box">
<input id="busca" class="form-control" placeholder="Pesquisar produto ou categoria">
<button id="btnBuscar" class="btn btn-dark">Buscar</button>
</div>
</div>
<div id="alerta">
</div>
<div id="listaProdutos" class="row g-4">
<div class="text-center">Carregando...</div>
</div>
<script src="js/produtos.js">
</script>
<?php
require '../templates/footer.php';
?>
