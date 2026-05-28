<?php
require_once __DIR__ . '/../includes/auth_check.php';

if (!in_array($_SESSION['perfil'], ['admin', 'super_admin'])) {
    header('Location: ../dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];
    $perfil = $_POST['perfil'];
    $bio = trim($_POST['bio']);

    if (empty($nome)) $erro = 'O nome é obrigatório.';
    elseif (empty($email)) $erro = 'O email é obrigatório.';
    elseif (strlen($senha) < 8) $erro = 'A senha deve ter no mínimo 8 caracteres.';
    elseif ($senha !== $confirmar) $erro = 'As senhas não coincidem.';
    else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) $erro = 'Este email já está em uso.';
        else {
            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, senha, perfil, bio) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $hash, $perfil, $bio]);
            header('Location: listar.php?msg=criado');
            exit;
        }
    }
}

$titulo = 'Novo Utilizador';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if ($erro): ?><div class="msg msg-error"><i data-lucide="alert-circle" class="lucide-icon"></i> <?= htmlspecialchars($erro) ?></div><?php endif; ?>

<form method="POST" style="max-width:500px">
  <div class="form-group">
    <label for="nome">Nome completo *</label>
    <input type="text" id="nome" name="nome" required>
  </div>
  <div class="form-group">
    <label for="email">Email *</label>
    <input type="email" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="senha">Senha * (mín. 8 caracteres)</label>
    <input type="password" id="senha" name="senha" minlength="8" required>
  </div>
  <div class="form-group">
    <label for="confirmar_senha">Confirmar senha *</label>
    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
  </div>
  <div class="form-group">
    <label for="perfil">Perfil</label>
    <select id="perfil" name="perfil">
      <option value="autor">Autor</option>
      <option value="editor">Editor</option>
      <option value="admin">Admin</option>
    </select>
  </div>
  <div class="form-group">
    <label for="bio">Biografia</label>
    <textarea id="bio" name="bio"></textarea>
  </div>
  <button type="submit" name="criar" class="btn btn-primary">Criar Utilizador</button>
</form>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
