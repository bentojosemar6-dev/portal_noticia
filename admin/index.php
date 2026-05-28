<?php
require_once __DIR__ . '/../config/sessao.php';
if (!empty($_SESSION['utilizador_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
