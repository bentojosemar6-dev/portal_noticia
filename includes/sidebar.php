<?php
$stmt_side_cat = $pdo->query("SELECT c.id, c.nome, c.slug, c.cor, c.icone, (SELECT COUNT(*) FROM noticias WHERE categoria_id = c.id AND status = 'publicado') AS total FROM categorias c WHERE c.ativa = 1 ORDER BY total DESC");
$sidebar_categorias = $stmt_side_cat->fetchAll();

$stmt_side_pop = $pdo->query("SELECT id, titulo, views FROM noticias WHERE status = 'publicado' ORDER BY views DESC LIMIT 5");
$sidebar_populares = $stmt_side_pop->fetchAll();
?>
<aside class="sidebar">
  <div class="sidebar-section">
    <h3 class="sidebar-title">Categorias</h3>
    <ul class="sidebar-categories">
      <?php foreach ($sidebar_categorias as $cat): ?>
      <li>
        <a href="categoria.php?slug=<?= htmlspecialchars($cat['slug']) ?>">
          <span><i data-lucide="<?= htmlspecialchars($cat['icone'] ?? 'hash') ?>" class="lucide-icon"></i> <?= htmlspecialchars($cat['nome']) ?></span>
          <span class="count"><?= $cat['total'] ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="sidebar-section">
    <h3 class="sidebar-title">Mais lidas</h3>
    <ul class="sidebar-popular">
      <?php foreach ($sidebar_populares as $pop): ?>
      <li>
        <a href="noticia.php?id=<?= $pop['id'] ?>">
          <?= htmlspecialchars(mb_substr($pop['titulo'], 0, 60)) ?>
          <span class="views"><?= number_format($pop['views'], 0, ',', '.') ?> visualizações</span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</aside>
