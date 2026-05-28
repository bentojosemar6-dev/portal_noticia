<?php
require_once 'config/conexao.php';
require_once 'config/constantes.php';
require_once 'includes/funcoes.php';

$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$offset = ($pagina - 1) * ITEMS_PER_PAGE;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE status = 'publicado'");
$stmt->execute();
$total_noticias = $stmt->fetchColumn();
$total_paginas = max(1, ceil($total_noticias / ITEMS_PER_PAGE));

$stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome, c.nome AS categoria_nome, c.cor AS categoria_cor
                        FROM noticias n
                        JOIN utilizadores u ON n.autor_id = u.id
                        JOIN categorias c ON n.categoria_id = c.id
                        WHERE n.status = 'publicado'
                        ORDER BY n.destaque DESC, n.publicado_em DESC
                        LIMIT ? OFFSET ?");
$stmt->bindValue(1, ITEMS_PER_PAGE, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome, c.nome AS categoria_nome, c.cor AS categoria_cor
                        FROM noticias n
                        JOIN utilizadores u ON n.autor_id = u.id
                        JOIN categorias c ON n.categoria_id = c.id
                        WHERE n.status = 'publicado' AND n.destaque = 1
                        LIMIT 1");
$stmt->execute();
$destaque = $stmt->fetch();

$titulo = 'Início';
$descricao = SITE_DESC;
include 'includes/header.php';
?>

<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <span class="hero-badge">Em destaque</span>
    <h1 class="hero-title"><?= $destaque ? htmlspecialchars($destaque['titulo']) : 'Bem-vindo ao Portal IPIL' ?></h1>
    <p class="hero-summary"><?= $destaque ? htmlspecialchars($destaque['resumo']) : 'Informações e notícias da instituição.' ?></p>
    <?php if ($destaque): ?>
    <a href="noticia.php?id=<?= $destaque['id'] ?>" class="hero-cta">Ler notícia completa →</a>
    <?php endif; ?>
  </div>
</section>

<div class="main-layout">
  <main class="main-content">
    <div class="news-grid">
      <?php foreach ($noticias as $n): ?>
        <?php $noticia = $n; include 'includes/card_noticia.php'; ?>
      <?php endforeach; ?>
    </div>

    <?php if ($total_paginas > 1): ?>
    <div class="pagination">
      <?php if ($pagina > 1): ?>
        <a href="?pagina=<?= $pagina - 1 ?>">« Anterior</a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?pagina=<?= $i ?>" class="<?= $i === $pagina ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if ($pagina < $total_paginas): ?>
        <a href="?pagina=<?= $pagina + 1 ?>">Próximo »</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </main>

  <?php include 'includes/sidebar.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
