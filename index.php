<?php include 'config/conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portal noticia</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
<h1>Portal de Notícias ipil</h1>
</header>

<nav>
<a href="index.php">Início</a>
<a href="#">Eventos</a>
<a href="#">Pesquisa</a>
<a href="#">Contato</a>
</nav>

<section class="banner">
<h2>Bem-vindo ao Portal de noticias do ipil</h2>
<p>Informações e notícias da instituição.</p>
</section>

<main class="container">

<?php
$sql = "SELECT * FROM noticias ORDER BY data_publicacao DESC";
$resultado = $conexao->query($sql);

while($noticia = $resultado->fetch_assoc()) {
?>

<div class="card-noticia">
<img src="assets/uploads/<?php echo $noticia['imagem']; ?>" alt="">

<div class="conteudo">
<h2><?php echo $noticia['titulo']; ?></h2>
<p><?php echo $noticia['resumo']; ?></p>

<a href="noticia.php?id=<?php echo $noticia['id']; ?>">
Ler mais
</a>
</div>
</div>

<?php } ?>

</main>

<footer>
<p>© 2026 Instituição ipli</p>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>
