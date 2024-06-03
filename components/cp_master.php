<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

//recuperar a lista de generos da db e armazena-la
$genres = fetchGenres(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Generos</title>
</head>
<body>

<h1>Géneros</h1>

<?php
if (count($genres) > 0) {
  // loop usado para iterar sobre cada genero
  foreach ($genres as $genre) {
    $genreId = $genre['id_genres']; 

    // o caminho da imagem é construido uao user id genero
    $imagePath = "../imgs/genres/genre_" . $genreId . ".jpg";  
    $genreName = $genre['genre_name'];

    echo "<a href='cp_services.php?genre_id=$genreId'>$genreName</a><br>";
    echo "<img src='" . $imagePath . "' alt='" . $genre['genre_name'] . " Genre Photo'>";
  }
} else {
  echo "<p>Nenhum género encontrado.</p>";
}
?>

</body>
</html>
