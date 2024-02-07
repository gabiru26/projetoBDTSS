<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


$genres = fetchGenres();

//conteúdo enviado de volta ao cliente é do tipo JSON
header('Content-Type: application/json');

// converte o array $genres para o formato JSON
echo json_encode($genres);

?>
