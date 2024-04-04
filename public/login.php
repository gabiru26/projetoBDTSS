<?php

session_start();

include_once '../includes/config.php';
include_once '../includes/db_api.php';


if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $user = loginUser($username, $password); // No need for escaping here

  if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // store role directly

    $redirect_url = ($user['role'] === "admin") ? 'admin_account.php' : 'user_account.php';
    header("Location: $redirect_url"); // redirect based on user role
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