<?php
include '../config/conexao.php';

if(isset($_POST['publicar'])) {

$titulo = $_POST['titulo'];
$resumo = $_POST['resumo'];
$conteudo = $_POST['conteudo'];
$autor = $_POST['autor'];
$categoria = $_POST['categoria'];

$imagem = $_FILES['imagem']['name'];
$tmp = $_FILES['imagem']['tmp_name'];

move_uploaded_file($tmp, '../assets/uploads/'.$imagem);

$sql = "INSERT INTO noticias
(titulo,resumo,conteudo,imagem,autor,categoria)

VALUES
('$titulo','$resumo','$conteudo','$imagem','$autor','$categoria')";

if($conexao->query($sql)) {
echo "Notícia publicada com sucesso";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Criar Notícia</title>
</head>
<body>

<h1>Nova Notícia</h1>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="titulo" placeholder="Título">

<textarea name="resumo" placeholder="Resumo"></textarea>

<textarea name="conteudo" placeholder="Conteúdo"></textarea>

<input type="text" name="autor" placeholder="Autor">

<input type="text" name="categoria" placeholder="Categoria">

<input type="file" name="imagem">

<button type="submit" name="publicar">
Publicar
</button>

</form>

</body>
</html>
