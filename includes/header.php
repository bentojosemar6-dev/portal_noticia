<?php
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../config/constantes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$stmt = $pdo->query("SELECT id, nome, slug, cor, icone FROM categorias WHERE ativa = 1 ORDER BY nome");
$categorias_nav = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $titulo ?? SITE_NAME ?> | <?= SITE_NAME ?></title>
<meta name="description" content="<?= $descricao ?? SITE_DESC ?>">
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<nav class="navbar">
  <a href="index.php" class="navbar-logo"><strong style="font-size:1.25rem;color:var(--color-text)">IPIL</strong></a>
  <div class="navbar-links">
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Início</a>
    <?php foreach ($categorias_nav as $cat): ?>
    <a href="categoria.php?slug=<?= htmlspecialchars($cat['slug']) ?>"><i data-lucide="<?= htmlspecialchars($cat['icone'] ?? 'hash') ?>" class="lucide-icon"></i> <?= htmlspecialchars($cat['nome']) ?></a>
    <?php endforeach; ?>
  </div>
  <div class="navbar-search">
    <form action="pesquisa.php" method="GET">
      <input type="text" name="q" placeholder="Pesquisar..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </form>
    <?php if (!empty($_SESSION['logado']) || !empty($_SESSION['utilizador_id'])): ?>
    <a href="admin/" class="navbar-admin-link"><i data-lucide="layout-dashboard" style="width:18px;height:18px"></i></a>
    <a href="admin/logout.php" class="navbar-logout-link"><i data-lucide="log-out" style="width:18px;height:18px"></i></a>
    <?php endif; ?>
  </div>
  <button class="navbar-toggle" onclick="document.querySelector('.navbar-links').classList.toggle('active')">
    <span></span><span></span><span></span>
  </button>
</nav>
