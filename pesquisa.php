<?php
require_once 'config/conexao.php';
require_once 'config/constantes.php';
require_once 'includes/funcoes.php';

$q = trim($_GET['q'] ?? '');

$noticias = [];
$total = 0;

if ($q) {
    $termo = '%' . $q . '%';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE status = 'publicado' AND (titulo LIKE ? OR resumo LIKE ? OR tags LIKE ?)");
    $stmt->execute([$termo, $termo, $termo]);
    $total = $stmt->fetchColumn();

    $pagina = max(1, (int)($_GET['pagina'] ?? 1));
    $offset = ($pagina - 1) * ITEMS_PER_PAGE;
    $total_paginas = max(1, ceil($total / ITEMS_PER_PAGE));

    $stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome, c.nome AS categoria_nome, c.cor AS categoria_cor
                            FROM noticias n
                            JOIN utilizadores u ON n.autor_id = u.id
                            JOIN categorias c ON n.categoria_id = c.id
                            WHERE n.status = 'publicado' AND (n.titulo LIKE ? OR n.resumo LIKE ? OR n.tags LIKE ?)
                            ORDER BY n.publicado_em DESC
                            LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $termo, PDO::PARAM_STR);
    $stmt->bindValue(2, $termo, PDO::PARAM_STR);
    $stmt->bindValue(3, $termo, PDO::PARAM_STR);
    $stmt->bindValue(4, ITEMS_PER_PAGE, PDO::PARAM_INT);
    $stmt->bindValue(5, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $noticias = $stmt->fetchAll();
}

$titulo = $q ? "Pesquisa: $q" : 'Pesquisar';
$descricao = 'Resultados da pesquisa no portal IPIL';
include 'includes/header.php';
?>

<div class="main-layout">
  <main class="main-content">
    <div class="search-header">
      <h1>Pesquisar</h1>
      <form action="pesquisa.php" method="GET" style="display:flex;gap:var(--space-sm);margin:var(--space-md) 0">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Digite sua pesquisa..." style="flex:1;font-family:var(--font-family);font-size:var(--text-body);padding:12px;border:1px solid var(--color-border);border-radius:var(--radius-md);background:var(--color-bg);transition:border-color var(--duration-fast) var(--ease-out)">
        <button type="submit" style="background:var(--color-primary);color:var(--color-bg);border:none;padding:12px 24px;border-radius:var(--radius-md);cursor:pointer;font-weight:600;font-family:var(--font-family)">Buscar</button>
      </form>
      <?php if ($q): ?>
      <p class="result-count"><?= $total ?> resultado(s) para "<?= htmlspecialchars($q) ?>"</p>
      <?php endif; ?>
    </div>

    <?php if ($q && empty($noticias)): ?>
      <p style="color:var(--color-text-muted);font-size:var(--text-small)">Nenhum resultado encontrado. Tente outros termos.</p>
    <?php endif; ?>

    <?php if (!empty($noticias)): ?>
    <div class="news-grid">
      <?php foreach ($noticias as $n): ?>
        <?php $noticia = $n; include 'includes/card_noticia.php'; ?>
      <?php endforeach; ?>
    </div>

    <?php if ($total_paginas > 1): ?>
    <div class="pagination">
      <?php if ($pagina > 1): ?>
        <a href="?q=<?= urlencode($q) ?>&pagina=<?= $pagina - 1 ?>">« Anterior</a>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?q=<?= urlencode($q) ?>&pagina=<?= $i ?>" class="<?= $i === $pagina ? 'active' : '' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if ($pagina < $total_paginas): ?>
        <a href="?q=<?= urlencode($q) ?>&pagina=<?= $pagina + 1 ?>">Próximo »</a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </main>

  <?php include 'includes/sidebar.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
