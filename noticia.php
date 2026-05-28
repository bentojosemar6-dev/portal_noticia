<?php
require_once 'config/conexao.php';
require_once 'config/constantes.php';
require_once 'includes/funcoes.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome, u.bio AS autor_bio, c.nome AS categoria_nome, c.slug AS categoria_slug, c.cor AS categoria_cor
                        FROM noticias n
                        JOIN utilizadores u ON n.autor_id = u.id
                        JOIN categorias c ON n.categoria_id = c.id
                        WHERE n.id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Notícia não encontrada</h1>';
    exit;
}

$pdo->prepare("UPDATE noticias SET views = views + 1 WHERE id = ?")->execute([$id]);

$stmt = $pdo->prepare("SELECT n.*, c.nome AS categoria_nome, c.cor AS categoria_cor
                        FROM noticias n
                        JOIN categorias c ON n.categoria_id = c.id
                        WHERE n.categoria_id = ? AND n.id != ? AND n.status = 'publicado'
                        ORDER BY n.publicado_em DESC LIMIT 3");
$stmt->execute([$noticia['categoria_id'], $id]);
$relacionadas = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM comentarios WHERE noticia_id = ? AND aprovado = 1 ORDER BY criado_em DESC");
$stmt->execute([$id]);
$comentarios = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentar']) && $noticia['permitir_comentarios']) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['mensagem']);
    if ($nome && $email && $mensagem) {
        $stmt = $pdo->prepare("INSERT INTO comentarios (noticia_id, nome, email, mensagem, ip) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $nome, $email, $mensagem, $_SERVER['REMOTE_ADDR']]);
        $comentario_enviado = true;
    }
}

$titulo = $noticia['titulo'];
$descricao = $noticia['resumo'];
include 'includes/header.php';
?>

<div class="news-page">
  <div class="breadcrumb">
    <a href="index.php">Início</a> &gt;
    <a href="categoria.php?slug=<?= htmlspecialchars($noticia['categoria_slug']) ?>"><?= htmlspecialchars($noticia['categoria_nome']) ?></a> &gt;
    <span style="color:var(--color-text-muted)"><?= htmlspecialchars($noticia['titulo']) ?></span>
  </div>

  <?php if ($noticia['imagem_capa']): ?>
  <img class="news-cover" src="assets/img/uploads/<?= htmlspecialchars($noticia['imagem_capa']) ?>" alt="<?= htmlspecialchars($noticia['alt_imagem'] ?? $noticia['titulo']) ?>">
  <?php endif; ?>

  <h1 class="news-title"><?= htmlspecialchars($noticia['titulo']) ?></h1>

  <div class="news-meta">
    <span><i data-lucide="user"></i> <?= htmlspecialchars($noticia['autor_nome']) ?></span>
    <span><i data-lucide="calendar"></i> <?= $noticia['publicado_em'] ? formatar_data($noticia['publicado_em']) : '' ?></span>
    <span><i data-lucide="folder"></i> <a href="categoria.php?slug=<?= htmlspecialchars($noticia['categoria_slug']) ?>" style="color:<?= htmlspecialchars($noticia['categoria_cor']) ?>"><?= htmlspecialchars($noticia['categoria_nome']) ?></a></span>
    <span><i data-lucide="clock"></i> <?= tempo_leitura($noticia['conteudo']) ?></span>
    <span><i data-lucide="eye"></i> <?= number_format($noticia['views'], 0, ',', '.') ?> visualizações</span>
  </div>

  <div class="news-content">
    <?= $noticia['conteudo'] ?>
  </div>

  <?php if ($noticia['tags']): ?>
  <div class="news-tags">
    <?php foreach (explode(',', $noticia['tags']) as $tag): ?>
      <a href="pesquisa.php?q=<?= urlencode(trim($tag)) ?>">#<?= htmlspecialchars(trim($tag)) ?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="news-share">
    <a href="https://api.whatsapp.com/send?text=<?= urlencode($noticia['titulo'] . ' - ' . SITE_URL . '/noticia.php?id=' . $id) ?>" target="_blank"><i data-lucide="message-circle"></i> WhatsApp</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/noticia.php?id=' . $id) ?>" target="_blank"><i data-lucide="share-2"></i> Facebook</a>
    <a href="#" onclick="navigator.clipboard.writeText('<?= SITE_URL ?>/noticia.php?id=<?= $id ?>');alert('Link copiado!');return false"><i data-lucide="link"></i> Copiar link</a>
  </div>

  <?php if (!empty($relacionadas)): ?>
  <div class="related-section">
    <h2>Notícias relacionadas</h2>
    <div class="related-grid">
      <?php foreach ($relacionadas as $r): ?>
        <?php $noticia = $r; include 'includes/card_noticia.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($noticia['permitir_comentarios']): ?>
  <div class="comments-section">
    <h2>Comentários</h2>

    <?php if (isset($comentario_enviado)): ?>
      <p style="background:rgba(34,197,94,0.1);color:var(--color-success);padding:12px 16px;border-radius:var(--radius-md);margin-bottom:var(--space-md);font-size:var(--text-small)">Comentário enviado para aprovação.</p>
    <?php endif; ?>

    <form method="POST" class="comment-form">
      <input type="text" name="nome" placeholder="Seu nome" required>
      <input type="email" name="email" placeholder="Seu email" required>
      <textarea name="mensagem" placeholder="Seu comentário..." required></textarea>
      <button type="submit" name="comentar">Enviar comentário</button>
    </form>

    <?php if (!empty($comentarios)): ?>
    <ul class="comment-list">
      <?php foreach ($comentarios as $c): ?>
      <li class="comment-item">
        <div class="comment-author"><?= htmlspecialchars($c['nome']) ?></div>
        <div class="comment-date"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></div>
        <div class="comment-text"><?= nl2br(htmlspecialchars($c['mensagem'])) ?></div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
