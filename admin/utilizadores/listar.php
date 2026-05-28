<?php
require_once __DIR__ . '/../includes/auth_check.php';

if (!in_array($_SESSION['perfil'], ['admin', 'super_admin'])) {
    header('Location: ../dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    if ($_POST['id'] == $_SESSION['utilizador_id']) { $erro = 'Não pode eliminar a própria conta.'; }
    else {
        $stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: listar.php?msg=eliminado');
        exit;
    }
}

$stmt = $pdo->query("SELECT id, nome, email, perfil, ativo, ultimo_login, criado_em FROM utilizadores ORDER BY nome");
$utilizadores = $stmt->fetchAll();

$titulo = 'Utilizadores';
include __DIR__ . '/../includes/header_admin.php';
?>

<?php if (isset($_GET['msg'])): ?><div class="msg msg-success"><i data-lucide="check-circle" class="lucide-icon"></i> Utilizador <?= $_GET['msg'] === 'eliminado' ? 'eliminado' : 'criado' ?>.</div><?php endif; ?>

<div class="action-bar">
  <div class="action-bar-left"><i data-lucide="users" class="lucide-icon"></i> Total: <?= count($utilizadores) ?> utilizadores</div>
  <div class="action-bar-right"><a href="criar.php" class="btn btn-primary"><i data-lucide="user-plus" class="lucide-icon"></i> Novo Utilizador</a></div>
</div>

<div class="table-card">
  <div class="table-wrap">
    <table>
      <thead><tr><th>Nome</th><th>Email</th><th>Perfil</th><th>Estado</th><th>Último login</th><th style="text-align:center">Ações</th></tr></thead>
      <tbody>
        <?php foreach ($utilizadores as $u): ?>
        <tr>
          <td style="font-weight:500"><?= htmlspecialchars($u['nome']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?php
            $cp = ['super_admin'=>'badge-danger','admin'=>'badge-warning','editor'=>'badge-muted','autor'=>'badge-muted'];
            echo '<span class="badge ' . ($cp[$u['perfil']] ?? 'badge-muted') . '">' . ucfirst($u['perfil']) . '</span>';
          ?></td>
          <td><?= $u['ativo'] ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>' ?></td>
          <td><?= $u['ultimo_login'] ? date('d/m/Y H:i', strtotime($u['ultimo_login'])) : 'Nunca' ?></td>
          <td style="text-align:center">
            <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-ghost" title="Editar"><i data-lucide="pencil" class="lucide-icon"></i></a>
            <?php if ($u['id'] !== $_SESSION['utilizador_id']): ?>
            <form method="POST" style="display:inline" onsubmit="return confirm('Eliminar este utilizador?')">
              <input type="hidden" name="id" value="<?= $u['id'] ?>">
              <button type="submit" name="eliminar" class="btn btn-sm btn-danger" title="Eliminar"><i data-lucide="trash-2" class="lucide-icon"></i></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
