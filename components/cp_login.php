<?php

session_start();

include_once '../includes/config.php';
include_once '../includes/db_api.php';

//verifica se os campos foram enviados via POST
if (isset($_POST['username']) && isset($_POST['password'])) {
  //valores enviados pelos campos do formulario são atribuidos às variaveis 
  $username = $_POST['username'];
  $password = $_POST['password'];

  $user = loginUser($username, $password);

  if ($user) {
    //armazenados em variaveis de sessão
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    //dependendo do role do utilizador autenticado a url é determinada
    $redirect_url = ($user['role'] === "admin") ? 'cp_admin_account.php' : 'cp_user_account.php';
    header("Location: $redirect_url");
    exit();
  } else {
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