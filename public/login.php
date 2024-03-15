<?php

session_start();

include_once '../includes/config.php';
include_once '../includes/db_api.php';


if (isset($_POST['username']) && isset($_POST['password'])) {
  
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = $_POST['password'];

  $user = loginUser($username, $password); //devolve a informação do user e valida password
  if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['role_id_role'] = $user['role_id_role']; // guarda o role id
    $_SESSION['user_info'] = $user; // guarda a info do user

    $redirect_url = ($user['role_id_role'] === 0) ? 'admin_account.php' : 'user_account.php';
    header("Location: $redirect_url"); // redireciona baseada no user id
    exit();
  } else {
    // falhou login
    echo '<p class="error">Invalid username or password.</p>';
  }
}
?>

<form method="post">
  <label for="username">Username:</label>
  <input type="text" name="username" id="username">
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password">
  <br>
  <button type="submit">Login</button>
</form>

