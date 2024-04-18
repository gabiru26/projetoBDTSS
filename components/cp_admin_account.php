<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


session_start();

//verifica se o utilizador esta autenticado e se possui role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== "admin") {
  header("Location: cp_login.php");
  exit();
}


$username = $_SESSION['username'];
$user_info = getUserInfo($username);

if (isset($_POST['update_user'])) {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $points = $_POST['points'];

  if (updateUserInfo($user_info['id'], $name, $username, $points, $role)) {
    echo "<p>Utilizador atualizado com sucesso!</p>";
  } else {
    echo "<p>Erro ao atualizar utilizador.</p>";
  }
}

?>

<h1>Editar Usuário</h1>

<form method="post" action="cp_admin_account.php">
  <input type="text" name="name" value="<?php echo $user_info['name']; ?>">
  <input type="text" name="username" value="<?php echo $user_info['username']; ?>">
  <input type="text" name="points" value="<?php echo $user_info['points']; ?>">
  <button type="submit" name="update_user">Atualizar </button>
</form>

