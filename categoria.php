<?php
require_once 'config/conexao.php';
require_once 'config/constantes.php';
require_once 'includes/funcoes.php';

$slug = trim($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM categorias WHERE slug = ? AND ativa = 1");
$stmt->execute([$slug]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Categoria não encontrada</h1>';
    exit;
}

$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$offset = ($pagina - 1) * ITEMS_PER_PAGE;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE categoria_id = ? AND status = 'publicado'");
$stmt->execute([$categoria['id']]);
$total = $stmt->fetchColumn();
$total_paginas = max(1, ceil($total / ITEMS_PER_PAGE));

$stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome, c.nome AS categoria_nome, c.cor AS categoria_cor
                        FROM noticias n
                        JOIN utilizadores u ON n.autor_id = u.id
                        JOIN categorias c ON n.categoria_id = c.id
                        WHERE n.categoria_id = ? AND n.status = 'publicado'
                        ORDER BY n.publicado_em DESC
                        LIMIT ? OFFSET ?");
$stmt->bindValue(1, $categoria['id'], PDO::PARAM_INT);
$stmt->bindValue(2, ITEMS_PER_PAGE, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll();

$titulo = $categoria['nome'];
$descricao = $categoria['descricao'] ?? 'Notícias de ' . $categoria['nome'];
include 'includes/header.php';
?>

<div class="main-layout">
  <main class="main-content">
    <div class="category-header">
      <h1><i data-lucide="<?= htmlspecialchars($categoria['icone'] ?? 'hash') ?>" class="lucide-icon"></i> <?= htmlspecialchars($categoria['nome']) ?></h1>
      <?php if ($categoria['descricao']): ?>
      <p><?= htmlspecialchars($categoria['descricao']) ?></p>
      <?php endif; ?>
      <p style="color:var(--color-text-muted);font-size:var(--text-small);margin-top:var(--space-sm)"><?= $total ?> notícia(s) encontrada(s)</p>
    </div>

    <?php if (empty($noticias)): ?>
      <p style="color:var(--color-text-muted);font-size:var(--text-small)">Nenhuma notícia publicada nesta categoria.</p>
    <?php else: ?>
    <div class="news-grid">
      <?php foreach ($noticias as $n): ?>
        <?php $noticia = $n; include 'includes/card_noticia.php'; ?>
      <?php endforeach; ?>
    </div>

    <?php if ($total_paginas > 1): ?>
    <div class="pagination">
      <?php if ($pagina > 1): ?>
        <a href="?slug=<?= htmlspecialchars($slug) ?>&pagina=<?= $pagina - 1 ?>">« Anterior</a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?slug=<?= htmlspecialchars($slug) ?>&pagina=<?= $i ?>" class="<?= $i === $pagina ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if ($pagina < $total_paginas): ?>
        <a href="?slug=<?= htmlspecialchars($slug) ?>&pagina=<?= $pagina + 1 ?>">Próximo »</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </main>

  <?php include 'includes/sidebar.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
