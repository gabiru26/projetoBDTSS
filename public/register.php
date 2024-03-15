<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password_hash'];
    $user_code = generateUserCode();
    $date_creation = date('Y-m-d');
    $points = 0;
    $role_id_role = isset($_POST['role_id_role']) ? filter_var($_POST['role_id_role'], FILTER_VALIDATE_BOOLEAN) : false; 
    // filter_var - garantir que o valor seja interpretado como um booleano


    if (registerUser($name, $username, $password, $user_code, $date_creation, $points, $role_id_role)) {
        //insere os dados do novo usuário (argumentos) na bd através da função registerUser na db_api
        echo json_encode(['message' => 'Registo efetuado com sucesso']);
    } else {
    
        echo json_encode(['error' => 'Registo falhou']);
    }
}

?>

<form method="post" action="register.php">
    <input type="text" name="name">
    <input type="text" name="username">
    <input type="password" name="password_hash">
    <button type="submit">Register</button>
</form>
    
</body>
</html>