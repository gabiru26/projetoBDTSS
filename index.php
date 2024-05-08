<?php

include 'includes/config.php';
$conn = OpenCon();
echo "Connected Successfully";

include 'includes/db_api.php';

$limit = 3; // Set the limit for the number of most liked services to display
$mostLikedServices = fetchMostLikedServices($limit);

// Now you can iterate through $mostLikedServices array to display each service's details
foreach ($mostLikedServices as $service) {
    // Check if the 'genre_id' key exists in the array
    if (array_key_exists('genre_id', $service)) {
        $serviceId = $service['id_services'];
        $genreId = $service['genre_id'];

        // Construct the dynamic image path based on service ID and genre ID
        $imagePath = "imgs/services/service_${genreId}/service_${genreId}_${serviceId}.jpg";

        echo "<div>";
        echo "<h2>" . $service['service_name'] . "</h2>";
        echo "<img src='" . $imagePath . "' alt='" . $service['service_name'] . "'>";
        echo "<p>Likes: " . $service['likes'] . "</p>";
        echo "</div>";
    } else {
        // Handle the case where 'genre_id' key is not present in the array
        echo "Error: Genre ID not found for service " . $service['id_services'];
    }
}

?>

