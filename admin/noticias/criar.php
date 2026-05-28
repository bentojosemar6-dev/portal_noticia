<?php
require_once __DIR__ . '/../includes/auth_check.php';

$erro = $sucesso = '';

$stmt = $pdo->query("SELECT id, nome FROM utilizadores WHERE ativo = 1 ORDER BY nome");
$autores = $stmt->fetchAll();

$stmt = $pdo->query("SELECT id, nome, cor FROM categorias WHERE ativa = 1 ORDER BY nome");
$categorias = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publicar'])) {
    $titulo = trim($_POST['titulo']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($titulo)));
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));
    $resumo = trim($_POST['resumo']);
    $conteudo = $_POST['conteudo'];
    $autor_id = (int) $_POST['autor_id'];
    $categoria_id = (int) $_POST['categoria_id'];
    $status = $_POST['status'];
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $tags = trim($_POST['tags']);
    $alt_imagem = trim($_POST['alt_imagem']);
    $publicado_em = ($status === 'publicado') ? date('Y-m-d H:i:s') : null;
    $permitir_comentarios = isset($_POST['permitir_comentarios']) ? 1 : 0;
    $imagem_capa = '';

    if (empty($titulo)) $erro = 'O título é obrigatório.';
    elseif (empty($resumo)) $erro = 'O resumo é obrigatório.';
    elseif (empty($conteudo)) $erro = 'O conteúdo é obrigatório.';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE slug = ? AND id != ?");
    $stmt->execute([$slug, 0]);
    if ($stmt->fetchColumn() > 0) $erro = 'Este slug já existe.';

    if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem_capa'];
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $erro = 'Formato de imagem inválido. Use JPG, PNG ou WebP.';
        elseif ($arquivo['size'] > 5242880) $erro = 'Imagem muito grande. Máximo 5MB.';
        else {
            $nome = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $arquivo['name']);
            move_uploaded_file($arquivo['tmp_name'], __DIR__ . '/../../assets/img/uploads/' . $nome);
            $imagem_capa = $nome;
        }
    }

    if (empty($erro)) {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, slug, resumo, conteudo, imagem_capa, alt_imagem, autor_id, categoria_id, status, destaque, permitir_comentarios, tags, publicado_em)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $slug, $resumo, $conteudo, $imagem_capa, $alt_imagem, $autor_id, $categoria_id, $status, $destaque, $permitir_comentarios, $tags, $publicado_em]);

        if ($destaque) {
            $novo_id = $pdo->lastInsertId();
            $pdo->exec("UPDATE noticias SET destaque = 0 WHERE id != $novo_id AND destaque = 1");
        }

        $sucesso = 'Notícia publicada com sucesso!';
    }
}

$titulo = 'Criar Notícia';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if ($erro): ?><div class="msg msg-error"><i data-lucide="alert-circle" class="lucide-icon"></i> <?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso): ?><div class="msg msg-success"><i data-lucide="check-circle" class="lucide-icon"></i> <?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <div class="form-grid">
    <div>
      <div class="form-group">
        <label for="titulo">Título *</label>
        <input type="text" id="titulo" name="titulo" maxlength="255" required oninput="gerarSlug(this.value)">
      </div>
      <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug">
        <div class="help-text">Gerado automaticamente a partir do título</div>
      </div>
      <div class="form-group">
        <label for="resumo">Resumo * <span class="char-count" id="resumo-count">0/300</span></label>
        <textarea id="resumo" name="resumo" maxlength="300" required oninput="document.getElementById('resumo-count').textContent = this.value.length + '/300'"></textarea>
      </div>
      <div class="form-group">
        <label for="conteudo">Conteúdo *</label>
        <textarea id="conteudo" name="conteudo" style="min-height:300px"></textarea>
      </div>
      <div class="form-group">
        <label for="tags">Tags (separadas por vírgula)</label>
        <input type="text" id="tags" name="tags" placeholder="exame,2025,curso">
      </div>
    </div>
    <div>
      <div class="form-group">
        <label for="imagem_capa">Imagem de capa</label>
        <input type="file" id="imagem_capa" name="imagem_capa" accept="image/jpeg,image/png,image/webp" onchange="previewImagem(this)">
        <div id="img-preview" class="help-text" style="margin-top:8px;max-width:200px;border-radius:8px;overflow:hidden;display:none">
          <img id="preview-img" style="width:100%">
        </div>
      </div>
      <div class="form-group">
        <label for="alt_imagem">Texto alternativo (acessibilidade)</label>
        <input type="text" id="alt_imagem" name="alt_imagem" placeholder="Descreva a imagem">
      </div>
      <div class="form-group">
        <label for="autor_id">Autor *</label>
        <select id="autor_id" name="autor_id" required>
          <?php foreach ($autores as $a): ?>
          <option value="<?= $a['id'] ?>" <?= $a['id'] === $_SESSION['utilizador_id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="categoria_id">Categoria *</label>
        <select id="categoria_id" name="categoria_id" required>
          <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="rascunho">Rascunho</option>
          <option value="pendente">Pendente</option>
          <option value="publicado" selected>Publicado</option>
          <option value="arquivado">Arquivado</option>
        </select>
      </div>
      <div class="checkbox-group">
        <label><input type="checkbox" name="destaque"> Notícia em destaque</label>
        <label><input type="checkbox" name="permitir_comentarios" checked> Permitir comentários</label>
      </div>
      <button type="submit" name="publicar" class="btn btn-primary">Publicar Notícia</button>
    </div>
  </div>
</form>

<script src="../../assets/js/admin.js"></script>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
