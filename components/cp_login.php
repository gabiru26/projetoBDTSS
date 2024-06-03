<?php

session_start();

include_once '../includes/config.php';
include_once '../includes/db_api.php'; 

if (isset($_POST['username']) && isset($_POST['password'])) {
    // obtem os valores enviados via POST do forms login
    $username = $_POST['username'];
    $password = $_POST['password'];

    //nova instancia da classe User
    $user = new User($conn);

    // chama metodo loginUser para verificar credenciais
    $user_info = $user->loginUser($username, $password);

    if ($user_info) {
        $_SESSION['username'] = $user_info['username'];
        $_SESSION['role'] = $user_info['role'];

        // dependendo do role reedireciona para diferentes
        $redirect_url = ($user_info['role'] === "admin") ? 'cp_admin_account.php' : 'cp_user_account.php';
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
