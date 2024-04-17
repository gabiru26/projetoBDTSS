<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password_hash'];

  // Call generateUserCode to get a unique code
  $user_code = generateUserCode();

  $date_creation = date('Y-m-d');
  $points = 0;
  $role = isset($_POST['role']) ? $_POST['role'] : "user"; // default role is user

  if (createUser($name, $username, $password, $user_code, $date_creation, $points, $role)) {
    // inserts user data in DB using createUser function
    echo json_encode(['message' => 'Registo efetuado com sucesso']);
  } else {
    echo json_encode(['error' => 'Registo falhou']);
  }
}

?>

<form method="post" action="cp_register.php">
  <input type="text" name="name">
  <input type="text" name="username">
  <input type="password" name="password_hash">
  <button type="submit">Register</button>
</form>

</body>
</html>
