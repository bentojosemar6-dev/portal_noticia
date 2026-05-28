<?php
require_once __DIR__ . '/../includes/auth_check.php';

if (!in_array($_SESSION['perfil'], ['admin', 'super_admin'])) {
    header('Location: ../dashboard.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) { header('Location: listar.php'); exit; }

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $perfil = $_POST['perfil'];
    $bio = trim($_POST['bio']);
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if (empty($nome)) $erro = 'O nome é obrigatório.';
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetchColumn() > 0) $erro = 'Este email já está em uso.';
        else {
            $sql = "UPDATE utilizadores SET nome=?, email=?, perfil=?, bio=?, ativo=? WHERE id=?";
            $params = [$nome, $email, $perfil, $bio, $ativo, $id];

            if (!empty($_POST['senha'])) {
                if (strlen($_POST['senha']) < 8) $erro = 'A senha deve ter no mínimo 8 caracteres.';
                else {
                    $sql = "UPDATE utilizadores SET nome=?, email=?, senha=?, perfil=?, bio=?, ativo=? WHERE id=?";
                    $hash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
                    $params = [$nome, $email, $hash, $perfil, $bio, $ativo, $id];
                }
            }

            if (empty($erro)) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                header('Location: listar.php?msg=atualizado');
                exit;
            }
        }
    }
}

$titulo = 'Editar Utilizador';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if ($erro): ?><div class="msg msg-error"><i data-lucide="alert-circle" class="lucide-icon"></i> <?= htmlspecialchars($erro) ?></div><?php endif; ?>

<form method="POST" style="max-width:500px">
  <div class="form-group">
    <label for="nome">Nome *</label>
    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
  </div>
  <div class="form-group">
    <label for="email">Email *</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
  </div>
  <div class="form-group">
    <label for="senha">Nova senha (deixe em branco para manter)</label>
    <input type="password" id="senha" name="senha" minlength="8">
  </div>
  <div class="form-group">
    <label for="perfil">Perfil</label>
    <select id="perfil" name="perfil">
      <?php foreach (['autor','editor','admin'] as $p): ?>
      <option value="<?= $p ?>" <?= $p === $user['perfil'] ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="bio">Biografia</label>
    <textarea id="bio" name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
  </div>
  <div class="form-group">
    <label><input type="checkbox" name="ativo" <?= $user['ativo'] ? 'checked' : '' ?>> Conta ativa</label>
  </div>
  <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
</form>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
