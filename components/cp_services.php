<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


//// verifica se o id do genero está no URL
if(isset($_GET['genre_id'])) {
    $genre_id = $_GET['genre_id'];

    $services = fetchServicesByGenreId($genre_id);

    header('Content-Type: application/json');
    echo json_encode($services);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Genre ID not provided']);
}

?>
