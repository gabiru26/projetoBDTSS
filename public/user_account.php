<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$user_info = getUserInfo($username);


if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $points = $_POST['points'];

    if (updateUserInfo($username, $name, $points)) {
        echo "<p>Perfil atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar perfil.</p>";
    }
}
?>


<h1>Perfil do Usuário</h1>

<p>Nome: <?php echo $user_info['name']; ?></p>
<p>Username: <?php echo $user_info['username']; ?></p>


<form method="post" action="user_account.php">
    <input type="text" name="name" value="<?php echo $user_info['name']; ?>">
</form>



