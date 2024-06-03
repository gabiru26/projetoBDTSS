<?php

include_once '../includes/config.php';
include_once '../includes/db_api.php';

// codigo dentro do bloco é executado apenas quando o formulario for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password_hash'];

    // cria uma nova instancia da classe User
    $user = new User($conn);

    // gerar código
    $user_code = $user->generateUserCode(8);

    $date_creation = date('Y-m-d');
    $points = 0;
    $role = isset($_POST['role']) ? $_POST['role'] : "user"; 

    // chama método createUser para criar novo user na bd
    if ($user->createUser($name, $username, $password, $user_code, $date_creation, $points, $role)) {
        echo json_encode(['message' => 'Registo efetuado com sucesso']);
    } else {
        echo json_encode(['error' => 'Registo falhou']);
    }
}

?>

<form method="post" action="cp_register.php">
    <input type="text" name="name">
    <input type="text" name="username">
    <input type="password" name="password_hash">
    <button type="submit">Registo</button>
</form>

</body>
</html>
