<?php
require_once __DIR__ . '/../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE categoria_id = ?");
    $stmt->execute([$_POST['id']]);
    if ($stmt->fetchColumn() > 0) {
        $erro = 'Não é possível eliminar uma categoria com notícias associadas.';
    } else {
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: listar.php?msg=eliminado');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar'])) {
    $nome = trim($_POST['nome']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($nome)));
    $cor = $_POST['cor'];
    $icone = $_POST['icone'];
    $descricao = trim($_POST['descricao']);
    if ($nome) {
        $stmt = $pdo->prepare("INSERT INTO categorias (nome, slug, cor, icone, descricao) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $slug, $cor, $icone, $descricao]);
        header('Location: listar.php?msg=criado');
        exit;
    }
}

$stmt = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM noticias WHERE categoria_id = c.id) AS total_noticias FROM categorias c ORDER BY c.nome");
$categorias = $stmt->fetchAll();

$titulo = 'Categorias';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if (!empty($erro)): ?><div class="msg msg-error"><i data-lucide="alert-circle" class="lucide-icon"></i> <?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if (isset($_GET['msg'])): ?>
  <div class="msg msg-success"><i data-lucide="check-circle" class="lucide-icon"></i> <?= $_GET['msg'] === 'criado' ? 'Categoria criada.' : 'Categoria eliminada.' ?></div>
<?php endif; ?>

<div class="section-title">Nova categoria</div>
<form method="POST" class="form-inline">
  <div class="form-group">
    <label>Nome</label>
    <input type="text" name="nome" required>
  </div>
  <div class="form-group">
    <label>Cor</label>
    <input type="color" name="cor" value="#F97316" style="width:40px;height:36px;padding:2px;cursor:pointer">
  </div>
  <div class="form-group">
    <label>Ícone</label>
    <input type="text" name="icone" placeholder="graduation-cap">
  </div>
  <div class="form-group">
    <label>Descrição</label>
    <input type="text" name="descricao" placeholder="Breve descrição">
  </div>
  <button type="submit" name="criar" class="btn btn-primary"><i data-lucide="plus" class="lucide-icon"></i> Adicionar</button>
</form>

<div class="section-title">Categorias existentes</div>
<div class="cat-grid">
  <?php foreach ($categorias as $c): ?>
  <div class="cat-card">
    <div class="cat-card-icon"><i data-lucide="<?= htmlspecialchars($c['icone'] ?? 'file-text') ?>" class="lucide-icon" style="width:24px;height:24px"></i></div>
    <h3><?= htmlspecialchars($c['nome']) ?></h3>
    <p><?= htmlspecialchars($c['descricao'] ?? '') ?></p>
    <div class="cat-card-footer">
      <span class="count"><?= $c['total_noticias'] ?> notícias</span>
      <span class="color-dot" style="background:<?= htmlspecialchars($c['cor']) ?>"></span>
    </div>
    <?php if ($c['total_noticias'] === 0): ?>
    <form method="POST" style="margin-top:8px" onsubmit="return confirm('Eliminar categoria?')">
      <input type="hidden" name="id" value="<?= $c['id'] ?>">
      <button type="submit" name="eliminar" class="btn btn-sm btn-danger">Eliminar</button>
    </form>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
