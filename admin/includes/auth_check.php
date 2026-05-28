<?php
require_once __DIR__ . '/../../config/sessao.php';
require_once __DIR__ . '/../../config/conexao.php';

$p = dirname($_SERVER['SCRIPT_NAME']);
$idx = strpos($p, '/admin');
$admin_login = ($idx !== false ? substr($p, 0, $idx) : $p) . '/admin/login.php';

if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['perfil'])) {
    header('Location: ' . $admin_login . '?msg=acesso_negado');
    exit;
}

$stmt = $pdo->prepare("SELECT id, nome, email, perfil, ativo FROM utilizadores WHERE id = ?");
$stmt->execute([$_SESSION['utilizador_id']]);
$user = $stmt->fetch();

if (!$user || !$user['ativo']) {
    session_destroy();
    header('Location: ' . $admin_login . '?msg=conta_inativa');
    exit;
}

$_SESSION['perfil'] = $user['perfil'];
