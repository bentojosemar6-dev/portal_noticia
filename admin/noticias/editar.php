<?php
require_once __DIR__ . '/../includes/auth_check.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch();

if (!$noticia) { header('Location: listar.php'); exit; }

$erro = $sucesso = '';

$stmt = $pdo->query("SELECT id, nome FROM utilizadores WHERE ativo = 1 ORDER BY nome");
$autores = $stmt->fetchAll();

$stmt = $pdo->query("SELECT id, nome, cor FROM categorias WHERE ativa = 1 ORDER BY nome");
$categorias = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    $titulo = trim($_POST['titulo']);
    $slug = trim($_POST['slug']);
    $resumo = trim($_POST['resumo']);
    $conteudo = $_POST['conteudo'];
    $autor_id = (int) $_POST['autor_id'];
    $categoria_id = (int) $_POST['categoria_id'];
    $status = $_POST['status'];
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $tags = trim($_POST['tags']);
    $alt_imagem = trim($_POST['alt_imagem']);
    $permitir_comentarios = isset($_POST['permitir_comentarios']) ? 1 : 0;
    $publicado_em = $noticia['publicado_em'];
    if ($status === 'publicado' && !$publicado_em) $publicado_em = date('Y-m-d H:i:s');

    if (empty($titulo)) $erro = 'O título é obrigatório.';
    elseif (empty($resumo)) $erro = 'O resumo é obrigatório.';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE slug = ? AND id != ?");
    $stmt->execute([$slug, $id]);
    if ($stmt->fetchColumn() > 0) $erro = 'Este slug já está em uso.';

    $imagem_capa = $noticia['imagem_capa'];
    if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem_capa'];
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $erro = 'Formato de imagem inválido.';
        elseif ($arquivo['size'] > 5242880) $erro = 'Imagem muito grande. Máximo 5MB.';
        else {
            $nome = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $arquivo['name']);
            move_uploaded_file($arquivo['tmp_name'], __DIR__ . '/../../assets/img/uploads/' . $nome);
            if ($noticia['imagem_capa'] && file_exists(__DIR__ . '/../../assets/img/uploads/' . $noticia['imagem_capa'])) {
                unlink(__DIR__ . '/../../assets/img/uploads/' . $noticia['imagem_capa']);
            }
            $imagem_capa = $nome;
        }
    }

    if (empty($erro)) {
        $stmt = $pdo->prepare("UPDATE noticias SET titulo=?, slug=?, resumo=?, conteudo=?, imagem_capa=?, alt_imagem=?, autor_id=?, categoria_id=?, status=?, destaque=?, permitir_comentarios=?, tags=?, publicado_em=? WHERE id=?");
        $stmt->execute([$titulo, $slug, $resumo, $conteudo, $imagem_capa, $alt_imagem, $autor_id, $categoria_id, $status, $destaque, $permitir_comentarios, $tags, $publicado_em, $id]);

        if ($destaque) $pdo->exec("UPDATE noticias SET destaque = 0 WHERE id != $id AND destaque = 1");

        $sucesso = 'Notícia atualizada com sucesso!';
    }
}

$titulo = 'Editar Notícia';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if ($erro): ?><div class="msg msg-error"><i data-lucide="alert-circle" class="lucide-icon"></i> <?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso): ?><div class="msg msg-success"><i data-lucide="check-circle" class="lucide-icon"></i> <?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <div class="form-grid">
    <div>
      <div class="form-group">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" maxlength="255" required value="<?= htmlspecialchars($noticia['titulo']) ?>">
      </div>
      <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($noticia['slug']) ?>">
      </div>
      <div class="form-group">
        <label for="resumo">Resumo *</label>
        <textarea id="resumo" name="resumo" maxlength="300" required><?= htmlspecialchars($noticia['resumo']) ?></textarea>
      </div>
      <div class="form-group">
        <label for="conteudo">Conteúdo *</label>
        <textarea id="conteudo" name="conteudo" style="min-height:300px"><?= htmlspecialchars($noticia['conteudo']) ?></textarea>
      </div>
      <div class="form-group">
        <label for="tags">Tags (separadas por vírgula)</label>
        <input type="text" id="tags" name="tags" value="<?= htmlspecialchars($noticia['tags'] ?? '') ?>">
      </div>
    </div>
    <div>
      <div class="form-group">
        <label>Imagem atual</label>
        <?php if ($noticia['imagem_capa']): ?>
          <img src="../../assets/img/uploads/<?= htmlspecialchars($noticia['imagem_capa']) ?>" style="max-width:150px;border-radius:8px;display:block;margin-bottom:8px">
        <?php else: ?>
          <p style="font-size:0.75rem;color:var(--color-text-muted);margin-bottom:8px">Nenhuma imagem</p>
        <?php endif; ?>
        <input type="file" id="imagem_capa" name="imagem_capa" accept="image/jpeg,image/png,image/webp">
      </div>
      <div class="form-group">
        <label for="alt_imagem">Texto alternativo</label>
        <input type="text" id="alt_imagem" name="alt_imagem" value="<?= htmlspecialchars($noticia['alt_imagem'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="autor_id">Autor *</label>
        <select id="autor_id" name="autor_id" required>
          <?php foreach ($autores as $a): ?>
          <option value="<?= $a['id'] ?>" <?= $a['id'] === $noticia['autor_id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="categoria_id">Categoria *</label>
        <select id="categoria_id" name="categoria_id" required>
          <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $c['id'] === $noticia['categoria_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <?php foreach (['rascunho','pendente','publicado','arquivado'] as $s): ?>
          <option value="<?= $s ?>" <?= $s === $noticia['status'] ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="checkbox-group">
        <label><input type="checkbox" name="destaque" <?= $noticia['destaque'] ? 'checked' : '' ?>> Notícia em destaque</label>
        <label><input type="checkbox" name="permitir_comentarios" <?= $noticia['permitir_comentarios'] ? 'checked' : '' ?>> Permitir comentários</label>
      </div>
      <button type="submit" name="atualizar" class="btn btn-primary">Atualizar Notícia</button>
    </div>
  </div>
</form>

<script src="../../assets/js/admin.js"></script>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
