<?php

include_once '../includes/config.php';
include '../includes/db_api.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password_hash'];

  // obter um codigo de utilizador unico
  $user_code = generateUserCode();

  $date_creation = date('Y-m-d');
  //pontos definido como zero
  $points = 0;
  $role = isset($_POST['role']) ? $_POST['role'] : "user"; // role default

  if (createUser($name, $username, $password, $user_code, $date_creation, $points, $role)) {
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
  <button type="submit">Registo</button>
</form>

</body>
</html>
