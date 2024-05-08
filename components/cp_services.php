<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

session_start();

// verifica se user é admin
if(isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
} else {
    $isAdmin = false;
}

$genre_id = isset($_GET['genre_id']) ? $_GET['genre_id'] : null;

// fetch nome do género se não for nulo
$page_title = "Services";
if(!is_null($genre_id)) {
  $genre_name = fetchGenreName($genre_id);
  $page_title = $genre_name;
}

$services = fetchServicesByGenreId($genre_id);

echo "<h2> $page_title </h2>";

// se admin estiver logado link para adicionar serviço
if ($isAdmin) {
    echo "<a href='cp_add_service.php'>Add Service</a>";
}

//lista dos serviços
if (count($services) > 0) {
  echo "<ul>";
  foreach ($services as $service) {
    $serviceName = $service['service_name'];
    $servicePhoto = $service['service_photo']; 
    $serviceId = $service['id_services'];
    // path de img dinamico
    $imagePath = "../imgs/services/service_$genre_id/service_${genre_id}_$serviceId.jpg";  
    echo "<li>
      <h3><a href='cp_detail.php?service_id=$serviceId'>$serviceName</a></h3>";
    echo "<img src='$imagePath' alt='$serviceName Service Photo'>";
    // se admin estiver logado link para editar e eliminar serviço
    if ($isAdmin) {
        echo "<br><a href='cp_edit_service.php?service_id=$serviceId'>Edit</a> | ";
        echo "<form method='post' action=''>
                <input type='hidden' name='service_id' value='$serviceId'>
                <button type='submit'>Delete</button>
              </form>";
    }
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Sem serviços neste género.</p>";
}

// verifica se forms foi submetido para eliminar serviço
if ($isAdmin && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    if (deleteService($service_id)) {
        echo "Service deleted successfully!";
        header("Location: cp_services.php?genre_id=$genre_id");
        exit();
    } else {
        echo "Failed to delete service. Please try again.";
    }
}

?>
