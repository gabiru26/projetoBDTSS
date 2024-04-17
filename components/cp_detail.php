<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

//// verifica se o id do service está no URL
if(isset($_GET['service_id'])) {
   
    $service_id = mysqli_real_escape_string($conn, $_GET['service_id']);

    $service = fetchServiceDetails($service_id);

    //definir o cabeçalho HTTP indicando que a resposta contém conteúdo JSON
    header('Content-Type: application/json');
    echo json_encode($service);
} else {

    //Se nenhum service_id for fornecido na URL
    //, o script retorna uma resposta de erro com o código de status HTTP 400
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Service_id not provided']);
}

?>
