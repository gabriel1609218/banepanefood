<?php
$titulo=$titulo??'Banepane Foods';
require_once __DIR__.'/../config/auth.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?=
htmlspecialchars($titulo)
?>
</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<script src="js/app.js">
</script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-warning shadow-sm">
<div class="container">
<a class="navbar-brand" href="index.php">
<img src="../imagem/logo.png" alt="Banepane Foods">
</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon">
</span>
</button>
<div class="collapse navbar-collapse" id="menu">
<div class="navbar-nav ms-auto">
<a class="nav-link text-dark" href="index.php">Home</a>
<a class="nav-link text-dark" href="produtos.php">Produtos</a>
<a class="nav-link text-dark" href="checkout.php">Carrinho <span id="cartCount" class="badge text-bg-dark">0</span>
</a>
<?php
if(adminLogado()):
?>
<a class="nav-link text-dark fw-bold" href="dashboard.php">Dashboard</a>
<a class="nav-link text-dark" href="admin.php">Administração</a>
<a class="nav-link text-dark" href="logout.php">Sair</a>
<?php
else:
?>
<a class="nav-link text-dark fw-bold" href="login.php">Admin</a>
<?php
endif;
?>
</div>
</div>
</div>
</nav>
<main class="container py-4">
