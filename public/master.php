<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


$genres = fetchGenres();

while ($row = mysqli_fetch_assoc($result)) {
    $genre_id = $row['id_genres'];
    $genre_name = $row['genre_name'];
    echo "<a href=\"services.php?genre_id=$genre_id\">$genre_name</a>";
}

//conteúdo enviado de volta ao cliente é do tipo JSON
header('Content-Type: application/json');

// converte o array $genres para o formato JSON
echo json_encode($genres);

?>
