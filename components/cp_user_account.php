<?php
include_once '../includes/config.php';
include_once '../includes/db_api.php'; 

session_start();

// Verifica se user esta logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// obtem o nome do user armazenado na sessao para identificar o que está logado
$username = $_SESSION['username'];

$user = new User($conn);

// obtem as infos do user atual
$user_info = $user->getUserInfo($username);


//verifica se o forms de atualizaçao de perfil foi enviado
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];

    // atualizar as infos de perfil
    if ($user->updateUserInfo($username, $name, $user_info['points'])) {
        echo "<p>Perfil atualizado com sucesso!</p>";
        // atualuiza variavel user_info
        $user_info = $user->getUserInfo($username);
    } else {
        echo "<p>Erro ao atualizar perfil.</p>";
    }
}
?>

<h1>Perfil do Utilizador</h1>

<p>Nome: <?php echo $user_info['name']; ?></p>
<p>Username: <?php echo $user_info['username']; ?></p>
<p>Pontos: <?php echo $user_info['points']; ?></p> <!-- Display points here -->

<form method="post" action="cp_user_account.php">
    <input type="text" name="name" value="<?php echo $user_info['name']; ?>">
    <button type="submit" name="update_profile">Atualizar Perfil</button>
</form>





