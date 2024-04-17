<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== "admin") {
  // Redirect to login if not logged in or role is not admin
  header("Location: cp_login.php");
  exit();
}

// Retrieve logged-in user's info (username should be sufficient)
$username = $_SESSION['username'];
$user_info = getUserInfo($username);

if (isset($_POST['update_user'])) {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $points = $_POST['points'];
  $role = isset($_POST['role_id']) ? $_POST['role_id'] : null; // Optional role update

  if (updateUserInfo($user_info['id'], $name, $username, $points, $role)) {
    echo "<p>Usuário atualizado com sucesso!</p>";
  } else {
    echo "<p>Erro ao atualizar usuário.</p>";
  }
}

?>

<h1>Editar Usuário</h1>

<form method="post" action="cp_admin_account.php">
  <input type="text" name="name" value="<?php echo $user_info['name']; ?>">
  <input type="text" name="username" value="<?php echo $user_info['username']; ?>">
  <input type="text" name="points" value="<?php echo $user_info['points']; ?>">
  <select name="role_id">
    <option value="0">Administrador</option>
    <option value="1">Usuário Normal</option>
  </select>
  <button type="submit" name="update_user">Atualizar </button>
</form>

