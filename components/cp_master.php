<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


$genres = fetchGenres(); // Call the function to get genres

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Genres List</title>
</head>
<body>

<h1>Genres</h1>

<?php
if (count($genres) > 0) {
  // Loop through the fetched genres and display them
  foreach ($genres as $genre) {
    $genreId = $genre['id_genres']; // Assuming genre ID is stored in this key

    // Construct the image path using the genre ID
    $imagePath = "../imgs/genres/genre_" . $genreId . ".jpg";  // Adjust extension if needed

    // Display the image with the constructed path
    echo "<h3>" . $genre['genre_name'] . "</h3>";
    echo "<img src='" . $imagePath . "' alt='" . $genre['genre_name'] . " Genre Photo'>";
  }
} else {
  echo "<p>No genres found.</p>";
}
?>

</body>
</html>
