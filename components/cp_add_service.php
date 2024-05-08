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

//fetch dos generos
$genres = fetchGenres();

// verifica se forms foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //verifica se todos os campos estao preenchidos
    if (isset($_POST['genre_id'], $_POST['service_name'], $_POST['description'], $_POST['price'], $_POST['points_awarded'])) {
        $genre_id = $_POST['genre_id'];
        $service_name = $_POST['service_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $points_awarded = $_POST['points_awarded'];

        if (addService($genre_id, $service_name, $description, $price, $points_awarded)) {
            header("Location: cp_master.php");
            echo "Service added successfully!";
            exit();
        } else {
            echo "Failed to add service. Please try again.";
        }
    } else {
        echo "All fields are required.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Service</title>
</head>
<body>

<h2>Add Service</h2>

<?php if ($isAdmin): ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="genre_id">Genre:</label><br>
        <select id="genre_id" name="genre_id">
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo $genre['id_genres']; ?>"><?php echo $genre['genre_name']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="service_name">Service Name:</label><br>
        <input type="text" id="service_name" name="service_name"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price"><br>
        <label for="points_awarded">Points Awarded:</label><br>
        <input type="text" id="points_awarded" name="points_awarded"><br>
        <input type="submit" value="Submit">
    </form>
<?php else: ?>
    <p>You don't have permission to add a service.</p>
<?php endif; ?>

</body>
</html>

