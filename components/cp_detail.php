<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

if (isset($_GET['service_id'])) {
  $service_id = $_GET['service_id'];

  $service = fetchServiceDetails($service_id);

  if ($service) {
    $serviceName = $service['service_name'];
    $description = $service['description'];
    $servicePhoto = $service['service_photo'];
    $pointsAwarded = $service['points_awarded'];
    $price = $service['price'];

    $imagePath = "../imgs/dep_laser/dep_laser_" . $service_id . ".jpg";  

    echo "<h2>Service Details</h2>";
    echo "<h3>$serviceName</h3>";
    echo "<img src='$imagePath' alt='$serviceName Service Photo'>";
    echo "<p>$description</p>";
    echo "<p>Points Awarded: $pointsAwarded</p>";
    echo "<p>Price: $price</p>";
  } else {
    echo "Erro: serviço não encontrado";
  }
} else {
  echo "Erro";
}

?>
