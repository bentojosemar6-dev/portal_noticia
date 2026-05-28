<article class="card">
  <?php if ($noticia['imagem_capa']): ?>
  <img class="card-img" src="assets/img/uploads/<?= htmlspecialchars($noticia['imagem_capa']) ?>" alt="<?= htmlspecialchars($noticia['alt_imagem'] ?? $noticia['titulo']) ?>" loading="lazy">
  <?php else: ?>
  <div class="card-img" style="background:var(--color-bg-soft);display:flex;align-items:center;justify-content:center;color:var(--color-text-muted);font-size:var(--text-label)">Sem imagem</div>
  <?php endif; ?>
  <div class="card-body">
    <?php if (isset($noticia['categoria_nome'])): ?>
    <span class="card-badge" style="background:<?= htmlspecialchars($noticia['categoria_cor'] ?? 'var(--color-primary)') ?>22;color:<?= htmlspecialchars($noticia['categoria_cor'] ?? 'var(--color-primary-dark)') ?>">
      <?= htmlspecialchars($noticia['categoria_nome']) ?>
    </span>
    <?php endif; ?>
    <h3 class="card-title"><a href="noticia.php?id=<?= $noticia['id'] ?>"><?= htmlspecialchars($noticia['titulo']) ?></a></h3>
    <p class="card-summary"><?= htmlspecialchars($noticia['resumo']) ?></p>
    <div class="card-meta">
      <span><?= htmlspecialchars($noticia['autor'] ?? $noticia['autor_nome'] ?? '') ?></span>
      <span><?= $noticia['publicado_em'] ? date('d/m/Y', strtotime($noticia['publicado_em'])) : '' ?></span>
    </div>
    <a href="noticia.php?id=<?= $noticia['id'] ?>" class="card-link">Ler mais →</a>
  </div>
</article>
