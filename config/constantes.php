<?php
/* ─── Configuração da Base de Dados ─── */
define('DB_HOST', 'localhost');     // InfinityFree: mysql.seudominio.com
define('DB_NAME', 'portal_noticia');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/* ─── Configuração do Site ─── */
define('SITE_NAME', 'Portal de Notícias IPIL');
define('SITE_DESC', 'Portal institucional do Instituto Politécnico Industrial de Luanda');
define('SITE_URL', 'http://localhost/dashboard/portal_noticia'); // Mudar para o domínio real
define('UPLOAD_PATH', __DIR__ . '/../assets/img/uploads/');
define('UPLOAD_URL', 'assets/img/uploads/');
define('ITEMS_PER_PAGE', 9);
