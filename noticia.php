<?php
include 'config/conexao.php';

$id = $_GET['id'];

$sql = "SELECT * FROM noticias WHERE id = $id";
$resultado = $conexao->query($sql);
$noticia = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title><?php echo $noticia['titulo']; ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="pagina-noticia">
<h1><?php echo $noticia['titulo']; ?></h1>

<img src="assets/uploads/<?php echo $noticia['imagem']; ?>" alt="">

<p><strong>Autor:</strong> <?php echo $noticia['autor']; ?></p>

<div>
<?php echo $noticia['conteudo']; ?>
</div>
</div>

</body>
</html>
