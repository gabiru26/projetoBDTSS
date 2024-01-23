<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP and MySQL Project</title>
</head>
<body>
    <?php
    // Include the configuration file
    require_once('../includes/config.php');

    // Your PHP code goes here

    // Example: Connect to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected to the database successfully";

    $conn->close();
    ?>
</body>
</html>