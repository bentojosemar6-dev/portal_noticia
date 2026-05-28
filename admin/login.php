<?php
require_once __DIR__ . '/../config/sessao.php';
require_once __DIR__ . '/../config/conexao.php';

$erro = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tentativas_login WHERE ip = ? AND tentada_em > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->execute([$ip]);
    $tentativas = $stmt->fetchColumn();

    if ($tentativas >= 5) {
        $erro = 'Muitas tentativas. Aguarde 15 minutos.';
    } else {
        $stmt = $pdo->prepare("SELECT id, nome, email, senha, perfil, ativo FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['ativo'] && password_verify($senha, $user['senha'])) {
            session_regenerate_id(true);
            $_SESSION['utilizador_id'] = $user['id'];
            $_SESSION['perfil'] = $user['perfil'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['logado'] = true;

            $stmt = $pdo->prepare("UPDATE utilizadores SET ultimo_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);

            $stmt = $pdo->prepare("INSERT INTO sessoes_admin (utilizador_id, ip, agente) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $ip, $_SERVER['HTTP_USER_AGENT'] ?? null]);

            header('Location: dashboard.php');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO tentativas_login (ip, email) VALUES (?, ?)");
        $stmt->execute([$ip, $email]);
        $erro = 'Email ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — <?= SITE_NAME ?></title>
<link rel="stylesheet" href="../assets/css/variables.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
  font-family: var(--font-family);
  background: var(--color-surface);
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: var(--space-lg);
}
.login-card {
  background: var(--color-bg);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-modal);
  padding: var(--space-xxl);
  width: 100%;
  max-width: 400px;
}
.login-card h1 {
  font-weight: 700;
  font-size: var(--text-headline);
  text-align: center;
  margin-bottom: var(--space-xs);
}
.login-card p {
  text-align: center;
  color: var(--color-text-muted);
  font-size: var(--text-small);
  margin-bottom: var(--space-xl);
}
.form-group { margin-bottom: var(--space-md); }
.form-group label {
  display: block;
  font-weight: 500;
  font-size: var(--text-label);
  color: var(--color-text);
  margin-bottom: var(--space-xs);
  letter-spacing: 0.02em;
}
.form-group input {
  width: 100%;
  font-family: var(--font-family);
  font-size: var(--text-body);
  color: var(--color-text);
  padding: 12px;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  background: var(--color-bg);
  transition: border-color var(--duration-fast) var(--ease-out),
              box-shadow var(--duration-fast) var(--ease-out);
}
.form-group input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
}
.btn {
  width: 100%;
  font-family: var(--font-family);
  font-weight: 600;
  font-size: var(--text-body);
  color: var(--color-bg);
  background: var(--color-primary);
  padding: 12px 24px;
  border: none;
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: background var(--duration-fast) var(--ease-out);
}
.btn:hover { background: var(--color-primary-dark); }
.erro {
  background: rgba(239,68,68,0.1);
  color: var(--color-danger);
  padding: var(--space-sm) var(--space-md);
  border-radius: var(--radius-md);
  font-size: var(--text-label);
  text-align: center;
  margin-bottom: var(--space-md);
}
</style>
</head>
<body>
<div class="login-card">
  <h1>Entrar</h1>
  <p>Painel administrativo do IPIL</p>
  <?php if ($erro): ?><div class="erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="seu@email.com" required>
    </div>
    <div class="form-group">
      <label for="senha">Senha</label>
      <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
    </div>
    <button type="submit" name="login" class="btn">Entrar</button>
  </form>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){if(typeof lucide!=='undefined'){lucide.createIcons();}});
</script>
</body>
</html>
