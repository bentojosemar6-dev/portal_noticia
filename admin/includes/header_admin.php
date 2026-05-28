<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $titulo ?? 'Dashboard' ?> — Admin IPIL</title>
<?php
  $p = dirname($_SERVER['SCRIPT_NAME']);
  $idx = strpos($p, '/admin');
  $admin_root = ($idx !== false ? substr($p, 0, $idx) : $p) . '/admin';
?>
<link rel="stylesheet" href="<?= $admin_root ?>/../assets/css/admin.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="sidebar-admin" id="sidebar-admin">
  <div class="sidebar-logo"><a href="<?= $admin_root ?>/dashboard.php">IPIL Admin</a></div>
  <nav class="sidebar-nav">
    <a href="<?= $admin_root ?>/dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>"><i data-lucide="layout-dashboard" class="lucide-icon"></i> Dashboard</a>

    <div class="group-label">Notícias</div>
    <a href="<?= $admin_root ?>/noticias/listar.php" class="<?= str_contains($_SERVER['PHP_SELF'], 'noticias') ? 'active' : '' ?>"><i data-lucide="newspaper" class="lucide-icon"></i> Listar</a>
    <a href="<?= $admin_root ?>/noticias/criar.php"><i data-lucide="plus-circle" class="lucide-icon"></i> Criar</a>

    <div class="group-label">Conteúdo</div>
    <a href="<?= $admin_root ?>/categorias/listar.php" class="<?= str_contains($_SERVER['PHP_SELF'], 'categorias') ? 'active' : '' ?>"><i data-lucide="tags" class="lucide-icon"></i> Categorias</a>
    <a href="<?= $admin_root ?>/comentarios/listar.php" class="<?= str_contains($_SERVER['PHP_SELF'], 'comentarios') ? 'active' : '' ?>"><i data-lucide="message-square" class="lucide-icon"></i> Comentários</a>

    <?php if (in_array($_SESSION['perfil'] ?? '', ['admin', 'super_admin'])): ?>
    <div class="group-label">Administração</div>
    <a href="<?= $admin_root ?>/utilizadores/listar.php" class="<?= str_contains($_SERVER['PHP_SELF'], 'utilizadores') ? 'active' : '' ?>"><i data-lucide="users" class="lucide-icon"></i> Utilizadores</a>
    <?php endif; ?>
  </nav>
  <div class="sidebar-footer">
    <a href="<?= $admin_root ?>/../index.php" target="_blank"><i data-lucide="globe" class="lucide-icon"></i> Ver site</a>
  </div>
</div>

<div class="topbar">
  <div class="topbar-left">
    <button class="topbar-toggle" id="sidebar-toggle" aria-label="Abrir menu"><i data-lucide="menu" class="lucide-icon"></i></button>
    <div class="topbar-title"><?= $titulo ?? 'Dashboard' ?></div>
  </div>
  <div class="topbar-right">
    <div class="topbar-user">
      <span class="topbar-user-name"><?= htmlspecialchars($_SESSION['nome'] ?? '') ?></span>
      <span class="topbar-user-badge"><?= htmlspecialchars($_SESSION['perfil'] ?? '') ?></span>
    </div>
    <a href="<?= $admin_root ?>/logout.php" class="topbar-logout"><i data-lucide="log-out" class="lucide-icon"></i> Sair</a>
  </div>
</div>

<div class="main-admin">
