<?php
session_start();
include '../config/conexao.php';

if(isset($_POST['login'])) {

$email = $_POST['email'];
$senha = md5($_POST['senha']);

$sql = "SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'";

$resultado = $conexao->query($sql);

if($resultado->num_rows > 0) {
$_SESSION['admin'] = $email;
header('Location: dashboard.php');
} else {
echo "Login inválido";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>

<form method="POST">
<input type="email" name="email" placeholder="Email">
<input type="password" name="senha" placeholder="Senha">

<button type="submit" name="login">
Entrar
</button>
</form>

</body>
</html>
