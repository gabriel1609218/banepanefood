<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/auth.php';
if (adminLogado()) {
    header('Location: dashboard.php');
    exit;
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $stmt = $conn->prepare('SELECT id, nome, email, senha FROM administradores WHERE email = ? AND ativo = 1 LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    if ($admin && password_verify($senha, $admin['senha'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_nome'] = $admin['nome'];
        $_SESSION['admin_email'] = $admin['email'];
        header('Location: dashboard.php');
        exit;
    }
    $erro = 'E-mail ou senha inválidos.';
}
$titulo='Login do Administrador';
require '../templates/header.php';
?>
<div class="row justify-content-center py-5">
<div class="col-md-6 col-lg-5">
<div class="card shadow">
<div class="card-body p-4">
<h1 class="h3 mb-2">Área administrativa</h1>
<p class="text-muted">Entre com sua conta para acessar o dashboard e os cadastros.</p>
<?php
if($erro):
?>
<div class="alert alert-danger">
<?=
htmlspecialchars($erro)
?>
</div>
<?php
endif;
?>
<form method="post" autocomplete="off">
<div class="mb-3">
<label class="form-label">E-mail</label>
<input type="email" name="email" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Senha</label>
<input type="password" name="senha" class="form-control" required>
</div>
<button class="btn btn-warning w-100">Entrar</button>
</form>
</div>
</div>
</div>
</div>
<?php
require '../templates/footer.php';
?>
