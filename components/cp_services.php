<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

// verifica se o id do genero foi fornecido na url através do método GET
if (isset($_GET['genre_id'])) {
  $genre_id = $_GET['genre_id'];

  $services = fetchServicesByGenreId($genre_id);

  
  echo "<h2>Services for Genre: $genre_id</h2>";
  if (count($services) > 0) {
    echo "<ul>";
    //loop foreach é usado para iterar sobre cada serviço
    foreach ($services as $service) {
      $serviceName = $service['service_name'];
      $servicePhoto = $service['service_photo']; 
      $serviceId = $service['id_services'];

      
      $imagePath = "../imgs/dep_laser/dep_laser_" . $serviceId . ".jpg";  

      echo "<li>
      <h3><a href='cp_detail.php?service_id=$serviceId'>$serviceName</a></h3>";
        echo "<img src='$imagePath' alt='$serviceName Service Photo'>";
      echo "</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Sem serviços neste género.</p>";
  }
} else {
  echo "Erro";
}

?>

