<?php
$titulo='Carrinho e Checkout';
require '../templates/header.php';
?>
<h1 class="mb-4">Seu carrinho</h1>
<div id="carrinho">
</div>
<div class="row g-4 mt-2">
<div class="col-md-6">
<div class="card">
<div class="card-body">
<h3>Dados do cliente</h3>
<form id="checkoutForm">
<input class="form-control mb-2" name="nome" placeholder="Nome" required>
<input class="form-control mb-2" name="email" type="email" placeholder="E-mail" required>
<input class="form-control mb-2" name="telefone" placeholder="Telefone" required>
<button class="btn btn-warning w-100">Finalizar pedido</button>
</form>
<div id="resultado" class="mt-3">
</div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="card">
<div class="card-body">
<h3>Total</h3>
<p class="display-6" id="total">R$ 0,00</p>
<small>O estoque é atualizado automaticamente ao finalizar o pedido.</small>
</div>
</div>
</div>
</div>
<script src="js/checkout.js">
</script>
<?php
require '../templates/footer.php';
?>
