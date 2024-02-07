<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username']; //obtém o valor do campo "username" enviado através do POST
    $password_hash = $_POST['password_hash'];

    $user = loginUser($username, $password_hash);

    if ($user) { //verifica se user não é nulo
        echo json_encode(['message' => 'Login com sucesso', 'user' => $user]);
    } else {
        echo json_encode(['error' => 'Username ou password invalidos.']);
    }
}

?>