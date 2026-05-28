<?php
require_once __DIR__ . '/../config/sessao.php';
require_once __DIR__ . '/../config/conexao.php';

if (isset($_SESSION['utilizador_id'])) {
    $stmt = $pdo->prepare("UPDATE sessoes_admin SET logout_em = NOW() WHERE utilizador_id = ? AND logout_em IS NULL ORDER BY login_em DESC LIMIT 1");
    $stmt->execute([$_SESSION['utilizador_id']]);
}

session_destroy();
header('Location: ../index.php');
exit;
