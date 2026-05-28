<footer class="footer">
  <div class="footer-grid">
    <div>
      <h3>IPIL</h3>
      <p>Instituto Politécnico Industrial de Luanda</p>
      <p>Portal oficial de notícias e comunicados</p>
    </div>
    <div>
      <h3>Links</h3>
      <p><a href="index.php">Início</a></p>
      <p><a href="#">Sobre o IPIL</a></p>
      <p><a href="#">Contactos</a></p>
    </div>
    <div>
      <h3>Categorias</h3>
      <?php if (isset($categorias_nav)): ?>
        <?php foreach ($categorias_nav as $cat): ?>
          <p><a href="categoria.php?slug=<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['nome']) ?></a></p>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; <?= date('Y') ?> Instituto Politécnico Industrial de Luanda. Todos os direitos reservados.
  </div>
</footer>

<div class="reading-progress" id="readingProgress">
  <div class="reading-progress-bar" id="readingProgressBar"></div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
