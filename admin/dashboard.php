<?php
require_once __DIR__ . '/includes/auth_check.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM noticias WHERE status = 'publicado'");
$publicadas = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM noticias WHERE status = 'rascunho'");
$rascunhos = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(views), 0) FROM noticias");
$total_views = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM comentarios WHERE aprovado = 0");
$comentarios_pendentes = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT n.id, n.titulo, n.status, n.publicado_em, u.nome AS autor
                      FROM noticias n JOIN utilizadores u ON n.autor_id = u.id
                      ORDER BY n.criado_em DESC LIMIT 5");
$ultimas = $stmt->fetchAll();

$stmt = $pdo->query("SELECT c.*, n.titulo AS noticia_titulo
                      FROM comentarios c JOIN noticias n ON c.noticia_id = n.id
                      WHERE c.aprovado = 0 ORDER BY c.criado_em DESC LIMIT 5");
$comentarios = $stmt->fetchAll();

$titulo = 'Dashboard';
include __DIR__ . '/includes/header_admin.php';
?>

<div class="stats-grid">
  <div class="stat-card">
    <span class="stat-icon" style="background:rgba(34,197,94,0.1);color:#16a34a"><i data-lucide="check-circle" class="lucide-icon"></i></span>
    <span class="stat-label">Publicadas</span>
    <span class="stat-value" style="color:#16a34a"><?= $publicadas ?></span>
  </div>
  <div class="stat-card">
    <span class="stat-icon" style="background:rgba(249,115,22,0.1);color:#ea580c"><i data-lucide="edit" class="lucide-icon"></i></span>
    <span class="stat-label">Rascunhos</span>
    <span class="stat-value" style="color:#ea580c"><?= $rascunhos ?></span>
  </div>
  <div class="stat-card">
    <span class="stat-icon" style="background:rgba(59,130,246,0.1);color:#2563eb"><i data-lucide="eye" class="lucide-icon"></i></span>
    <span class="stat-label">Visualizações</span>
    <span class="stat-value" style="color:#2563eb"><?= number_format($total_views, 0, ',', '.') ?></span>
  </div>
  <div class="stat-card">
    <span class="stat-icon" style="background:<?= $comentarios_pendentes > 0 ? 'rgba(239,68,68,0.1)' : 'rgba(34,197,94,0.1)' ?>;color:<?= $comentarios_pendentes > 0 ? '#ef4444' : '#16a34a' ?>"><i data-lucide="message-square" class="lucide-icon"></i></span>
    <span class="stat-label">Comentários pendentes</span>
    <span class="stat-value" style="color:<?= $comentarios_pendentes > 0 ? '#ef4444' : '#16a34a' ?>"><?= $comentarios_pendentes ?></span>
  </div>
</div>

<div class="dashboard-grid">
  <div>
    <h2 class="dashboard-section-title">Últimas notícias</h2>
    <?php if (empty($ultimas)): ?>
      <div class="empty-state"><h3>Nenhuma notícia</h3><p>Ainda não há notícias publicadas.</p></div>
    <?php else: ?>
      <div class="table-card">
        <div class="table-wrap">
          <table>
            <thead><tr><th>Título</th><th>Autor</th><th>Status</th><th>Data</th></tr></thead>
            <tbody>
              <?php foreach ($ultimas as $n): ?>
              <tr>
                <td><a href="noticias/editar.php?id=<?= $n['id'] ?>"><?= htmlspecialchars(mb_substr($n['titulo'], 0, 50)) ?></a></td>
                <td><?= htmlspecialchars($n['autor']) ?></td>
                <td><?php
                  $mc = ['rascunho' => 'badge-warning', 'pendente' => 'badge-muted', 'publicado' => 'badge-success', 'arquivado' => 'badge-muted'];
                  echo '<span class="badge ' . ($mc[$n['status']] ?? 'badge-muted') . '">' . ucfirst($n['status']) . '</span>';
                ?></td>
                <td><?= $n['publicado_em'] ? date('d/m/Y', strtotime($n['publicado_em'])) : '-' ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div>
    <h2 class="dashboard-section-title">Comentários pendentes</h2>
    <?php if (empty($comentarios)): ?>
      <div class="empty-state"><h3>Nenhum comentário</h3><p>Todos os comentários foram moderados.</p></div>
    <?php else: ?>
      <div class="comment-list">
        <?php foreach ($comentarios as $c): ?>
        <div class="comment-card">
          <div class="comment-card-author"><?= htmlspecialchars($c['nome']) ?></div>
          <div class="comment-card-meta">em "<?= htmlspecialchars(mb_substr($c['noticia_titulo'], 0, 40)) ?>"</div>
          <div class="comment-card-text"><?= htmlspecialchars(mb_substr($c['mensagem'], 0, 100)) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/includes/footer_admin.php'; ?>
