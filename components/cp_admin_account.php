<?php
include_once '../includes/config.php';
include_once '../includes/db_api.php'; 

session_start();

// verifica se user esta logado e se possui role de admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== "admin") {
    header("Location: cp_login.php");
    exit();
}

$user = new User($conn);

$username = $_SESSION['username'];

if (isset($_POST['update_points'])) {
    $user_code = $_POST['user_code'];
    $points_to_add = $_POST['points_to_add'];

    // Retrieve user info by user code
    $user_info = $user->getUserInfoByCode($user_code);

    if (!$user_info) {
        echo "<p>Utilizador não encontrado.</p>";
    } else {
        $current_points = $user_info['points'];
        $total_points = $current_points + $points_to_add;

        if ($user->updateUserPoints($user_code, $total_points)) {
            echo "<p>Pontos do utilizador atualizados com sucesso!</p>";

            // Retrieve user info again after updating points
            $user_info = $user->getUserInfoByCode($user_code);

            // Display updated points
            echo "<p>O utilizador " . $user_info['name'] . " agora tem " . $user_info['points'] . " pontos.</p>";
        } else {
            echo "<p>Erro ao atualizar pontos do utilizador.</p>";
        }
    }
}
?>

<h1>Atualizar Pontos de Utilizador</h1>

<form method="post" action="cp_admin_account.php">
    <input type="text" name="user_code" placeholder="Código do Utilizador">
    <input type="number" name="points_to_add" placeholder="Pontos a Adicionar">
    <button type="submit" name="update_points">Atualizar Pontos</button>
</form>
