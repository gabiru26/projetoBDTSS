<?php

include_once '../includes/config.php';
include '../includes/db_api.php';

session_start();

// verifica se user logado é admin
if(isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $isAdmin = true;
} else {
    $isAdmin = false;
}

if(isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];

    $service_details = fetchServiceDetails($service_id);

    // verifica se serviço existe
    if($service_details) {
        $service_name = $service_details['service_name'];
        $description = $service_details['description'];
        $price = $service_details['price'];
        $points_awarded = $service_details['points_awarded'];
    } else {
        header("Location: cp_master.php");
        exit();
    }
} else {
    header("Location: cp_master.php");
    exit();
}

// verifica se forms foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // verifica se todos os campos estao preenchidos
    if (isset($_POST['service_name'], $_POST['description'], $_POST['price'], $_POST['points_awarded'])) {
        // update serviço 
        $new_service_name = $_POST['service_name'];
        $new_description = $_POST['description'];
        $new_price = $_POST['price'];
        $new_points_awarded = $_POST['points_awarded'];

        if (updateService($service_id, $new_service_name, $new_description, $new_price, $new_points_awarded)) {
            header("Location: cp_master.php");
            exit();
        } else {
            echo "Failed to update service. Please try again.";
        }
    } else {
        echo "All fields are required.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
</head>
<body>

<h2>Edit Service</h2>

<?php if ($isAdmin): ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?service_id=$service_id"); ?>">
        <label for="service_name">Service Name:</label><br>
        <input type="text" id="service_name" name="service_name" value="<?php echo htmlspecialchars($service_name); ?>"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>"><br>
        <label for="points_awarded">Points Awarded:</label><br>
        <input type="text" id="points_awarded" name="points_awarded" value="<?php echo htmlspecialchars($points_awarded); ?>"><br>
        <input type="submit" value="Update">
    </form>
<?php else: ?>
    <p>You don't have permission to edit this service.</p>
<?php endif; ?>

</body>
</html>
