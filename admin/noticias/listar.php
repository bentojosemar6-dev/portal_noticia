<?php
require_once __DIR__ . '/../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("SELECT imagem_capa FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    $n = $stmt->fetch();
    if ($n && $n['imagem_capa']) {
        $caminho = __DIR__ . '/../../assets/img/uploads/' . $n['imagem_capa'];
        if (file_exists($caminho)) unlink($caminho);
    }
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: listar.php?msg=eliminado');
    exit;
}

$stmt = $pdo->query("SELECT n.*, u.nome AS autor, c.nome AS categoria_nome, c.cor AS categoria_cor
                      FROM noticias n
                      JOIN utilizadores u ON n.autor_id = u.id
                      JOIN categorias c ON n.categoria_id = c.id
                      ORDER BY n.criado_em DESC");
$noticias = $stmt->fetchAll();

$titulo = 'Listar Notícias';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if (isset($_GET['msg'])): ?>
  <?php if ($_GET['msg'] === 'criado'): ?><div class="msg msg-success"><i data-lucide="check-circle" class="lucide-icon"></i> Notícia criada com sucesso.</div><?php endif; ?>
  <?php if ($_GET['msg'] === 'eliminado'): ?><div class="msg msg-error"><i data-lucide="trash-2" class="lucide-icon"></i> Notícia eliminada.</div><?php endif; ?>
<?php endif; ?>

<div class="action-bar">
  <div class="action-bar-left"><i data-lucide="list" class="lucide-icon"></i> Total: <?= count($noticias) ?> notícias</div>
  <div class="action-bar-right"><a href="criar.php" class="btn btn-primary"><i data-lucide="plus" class="lucide-icon"></i> Nova Notícia</a></div>
</div>

<?php if (empty($noticias)): ?>
  <div class="empty-state">
    <div class="empty-state-icon"><i data-lucide="newspaper" class="lucide-icon"></i></div>
    <h3>Nenhuma notícia encontrada</h3>
    <p>Crie a primeira notícia do portal.</p>
    <a href="criar.php" class="btn btn-primary"><i data-lucide="plus" class="lucide-icon"></i> Criar Notícia</a>
  </div>
<?php else: ?>
<div class="table-card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>Título</th><th>Categoria</th><th>Autor</th><th>Status</th><th style="text-align:center">Destaque</th><th style="text-align:center"><i data-lucide="eye" class="lucide-icon" style="width:14px;height:14px"></i></th><th>Data</th><th style="text-align:center">Ações</th></tr></thead>
      <tbody>
        <?php foreach ($noticias as $n): ?>
        <tr>
          <td><a href="editar.php?id=<?= $n['id'] ?>"><?= htmlspecialchars(mb_substr($n['titulo'], 0, 60)) ?></a></td>
          <td><span class="cat-badge" style="background:<?= $n['categoria_cor'] ?>22;color:<?= $n['categoria_cor'] ?>"><?= htmlspecialchars($n['categoria_nome']) ?></span></td>
          <td><?= htmlspecialchars($n['autor']) ?></td>
          <td><?php
            $mc = ['rascunho'=>'badge-warning','pendente'=>'badge-muted','publicado'=>'badge-success','arquivado'=>'badge-muted'];
            echo '<span class="badge ' . ($mc[$n['status']] ?? 'badge-muted') . '">' . ucfirst($n['status']) . '</span>';
          ?></td>
          <td style="text-align:center"><?= $n['destaque'] ? '<i data-lucide="star" class="lucide-icon" style="color:#eab308;width:16px;height:16px"></i>' : '<span style="color:var(--color-text-muted)">-</span>' ?></td>
          <td style="text-align:center"><?= $n['views'] ?></td>
          <td><?= date('d/m/Y', strtotime($n['criado_em'])) ?></td>
          <td style="text-align:center">
            <a href="editar.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-ghost" title="Editar"><i data-lucide="pencil" class="lucide-icon"></i></a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Eliminar esta notícia?')">
              <input type="hidden" name="id" value="<?= $n['id'] ?>">
              <button type="submit" name="eliminar" class="btn btn-sm btn-danger" title="Eliminar"><i data-lucide="trash-2" class="lucide-icon"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
