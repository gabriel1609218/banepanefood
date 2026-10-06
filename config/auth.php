<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function adminLogado(): bool {
    return isset($_SESSION['admin_id'], $_SESSION['admin_email']);
}
function exigirAdmin(): void {
    if (!adminLogado()) {
        header('Location: login.php');
        exit;
    }
}
function exigirAdminApi(): void {
    if (!adminLogado()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok'=>false,'mensagem'=>'Acesso restrito. Faça login como administrador.']);
        exit;
    }
}
