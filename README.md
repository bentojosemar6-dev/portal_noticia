# Portal de Notícias IPIL — v2.0

Portal institucional do Instituto Politécnico Industrial de Luanda.

## Stack

- PHP 8.1+ com PDO
- MySQL 8.0+
- HTML5 + CSS3 (com design system de variáveis CSS)
- JavaScript vanilla
- Fonte: Inter (Google Fonts)

## Instalação

1. Coloque a pasta em `htdocs` do XAMPP
2. Importe o banco:
   ```bash
   mysql -u root -p < database/portal_ipil.sql
   ```
3. Configure as credenciais em `config/constantes.php`
4. Acesse: http://localhost/portal_noticia/

## Admin

- URL: http://localhost/portal_noticia/admin/
- Email: admin@ipil.ao
- Senha: Admin@2025

## Estrutura

```
├── index.php              # Página inicial
├── noticia.php            # Notícia individual
├── categoria.php          # Por categoria
├── pesquisa.php           # Busca
├── config/                # Conexão, constantes, sessão
├── includes/              # Header, footer, sidebar, card, funções
├── admin/                 # Dashboard, login, CRUDs
│   ├── noticias/
│   ├── categorias/
│   ├── utilizadores/
│   └── comentarios/
├── assets/
│   ├── css/               # variables.css + style.css
│   ├── js/                # main.js + admin.js
│   └── img/uploads/       # Imagens enviadas
└── database/              # Script SQL
```
