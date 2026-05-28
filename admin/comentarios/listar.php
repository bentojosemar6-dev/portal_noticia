<?php
require_once __DIR__ . '/../includes/auth_check.php';

if (isset($_GET['aprovar'])) {
    $stmt = $pdo->prepare("UPDATE comentarios SET aprovado = 1 WHERE id = ?");
    $stmt->execute([(int)$_GET['aprovar']]);
    header('Location: listar.php');
    exit;
}

if (isset($_GET['rejeitar'])) {
    $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->execute([(int)$_GET['rejeitar']]);
    header('Location: listar.php');
    exit;
}

$filtro = $_GET['filtro'] ?? 'pendentes';
$where = $filtro === 'aprovados' ? 'c.aprovado = 1' : 'c.aprovado = 0';

$stmt = $pdo->query("SELECT c.*, n.titulo AS noticia_titulo FROM comentarios c JOIN noticias n ON c.noticia_id = n.id WHERE $where ORDER BY c.criado_em DESC");
$comentarios = $stmt->fetchAll();

$titulo = 'Comentários';
include __DIR__ . '/../includes/header_admin.php';
?>

<div style="display:flex;gap:8px;margin-bottom:20px">
  <a href="?filtro=pendentes" class="btn btn-sm <?= $filtro === 'pendentes' ? 'btn-primary' : 'btn-secondary' ?>"><i data-lucide="clock" class="lucide-icon"></i> Pendentes</a>
  <a href="?filtro=aprovados" class="btn btn-sm <?= $filtro === 'aprovados' ? 'btn-primary' : 'btn-secondary' ?>"><i data-lucide="check-circle" class="lucide-icon"></i> Aprovados</a>
</div>

<?php if (empty($comentarios)): ?>
  <div class="empty-state">
    <div class="empty-state-icon"><i data-lucide="message-square" class="lucide-icon"></i></div>
    <h3>Nenhum comentário <?= $filtro === 'pendentes' ? 'pendente' : 'aprovado' ?></h3>
    <p>Não há comentários para mostrar nesta categoria.</p>
  </div>
<?php else: ?>
  <?php foreach ($comentarios as $c): ?>
  <div class="comment-card">
    <div class="comment-card-author"><?= htmlspecialchars($c['nome']) ?></div>
    <div class="comment-card-meta"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?> &middot; <?= htmlspecialchars($c['email']) ?><?= $c['ip'] ? ' &middot; IP: ' . htmlspecialchars($c['ip']) : '' ?></div>
    <div class="comment-card-meta">em <a href="../../noticia.php?id=<?= $c['noticia_id'] ?>" target="_blank" style="color:var(--color-primary)"><?= htmlspecialchars(mb_substr($c['noticia_titulo'], 0, 40)) ?></a></div>
    <div class="comment-card-text"><?= nl2br(htmlspecialchars($c['mensagem'])) ?></div>
    <?php if (!$c['aprovado']): ?>
    <div style="display:flex;gap:8px;margin-top:8px">
      <a href="?aprovar=<?= $c['id'] ?>" class="btn btn-sm btn-primary"><i data-lucide="check" class="lucide-icon"></i> Aprovar</a>
      <a href="?rejeitar=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Rejeitar comentário?')"><i data-lucide="x" class="lucide-icon"></i> Rejeitar</a>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
