<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


session_start();

if (!isset($_SESSION['username']) || $_SESSION['role_id_role'] === 0) {
    header("Location: login.php");
    exit();
  }
  
  // Retrieve logged-in admin's username from session
  $username = $_SESSION['username'];

// Get admin's info using username
$user_info = getUserInfo($user_id);

if (isset($_POST['update_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $points = $_POST['points'];
    $role_id = $_POST['role_id'];

    if (updateUserInfo($user_id, $name, $username, $points, $role_id)) {
        echo "<p>Usuário atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar usuário.</p>";
    }
}

?>

<h1>Editar Utiizador</h1>

<form method="post" action="admin_account.php"; ?>">
    <input type="text" name="name" value="<?php echo $user_info['name']; ?>">
    <input type="text" name="username" value="<?php echo $user_info['username']; ?>">
    <input type="text" name="points" value="<?php echo $user_info['points']; ?>">
    <select name="role_id">
        <option value="0">Administrador</option>
        <option value="1">Usuário Normal</option>
    </select>
    <button type="submit" name="update_user">Atualizar </button>
</form>



