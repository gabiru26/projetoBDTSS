<?php

include_once 'config.php';

function registerUser($name, $username, $password, $user_code, $date_creation, $points, $isAdmin = false) {
    global $conn;


    //parâmetros da função 
    $name = mysqli_real_escape_string($conn, $name); //é usado em todos os inputs de strings para prevenir sql injections
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // hashing
    $user_code = mysqli_real_escape_string($conn, $user_code);
    $points = mysqli_real_escape_string($conn, $points);
    $date_creation = mysqli_real_escape_string($conn, $date_creation);

    
    $role_id_role = $isAdmin ? 0 : 1;

    //verifica se user já existe
    $query_check_username = "SELECT COUNT(*) as count FROM users WHERE username = '$username'"; 
    $result_check_username = mysqli_query($conn, $query_check_username);
    $row = mysqli_fetch_assoc($result_check_username);
    if ($row['count'] > 0) {
        return false; // user já existe
    }

    $query = "INSERT INTO users (name, username, password, user_code, date_creation, points, role_id_role)
              VALUES ('$name', '$username', '$password_hash', '$user_code', '$date_creation', $points, $role_id_role)";

    $result = mysqli_query($conn, $query);

    if ($result) {
        return true;
    } else {
        error_log("Error in registerUser: " . mysqli_error($conn));
        return false;
    }
}


function generateUserCode($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = ''; //guardar código gerado

    // gera código random
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    
   
    global $conn;
    $query = "SELECT user_code FROM users WHERE user_code = '$code'"; // verifica se código é único
    $result = mysqli_query($conn, $query);

    // se código já for usado gerar novo em loop
    while (mysqli_num_rows($result) > 0) {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        $result = mysqli_query($conn, "SELECT user_code FROM users WHERE user_code = '$code'");
    }

    return $code;
}


function loginUser($username, $password) {
  global $conn;

  $username = mysqli_real_escape_string($conn, $username);

  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);


  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
      unset($user['password']); 
      return $user;
    }
  }

  return false;

  //executa uma consulta SQL para selecionar o 
  //usuário correspondente ao nome de usuário fornecido e verifica se a senha fornecida corresponde 
  //à senha armazenada no banco de dados
}


function fetchGenres() {
    global $conn;

    $genres = array();
    //retorna um array de gênero

   
    $query = "SELECT genre_name, genre_photo FROM genres";

    
    $result = mysqli_query($conn, $query);

   
    if ($result) {
       
        while ($row = mysqli_fetch_assoc($result)) {
            $genres[] = $row; //cada elemento do array contém o nome do gênero e a foto correspondente
        }
    }

    return $genres;
}


//recebe um id de gênero como parâmetro e procura serviços associados
function fetchServicesByGenreId($genre_id) {
    global $conn;

    $services = array();

    $query = "SELECT service_name, service_photo FROM services WHERE genres_id_genres = '$genre_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }

    return $services;
}

//id de serviço como parâmetro e procura detalhes desse serviço
function fetchServiceDetails($service_id) {
    global $conn;

    $service = array();

    $query = "SELECT service_name, description, service_photo, price, points_awarded FROM services WHERE id_services = '$service_id'";

    $result = mysqli_query($conn, $query);

    if ($result) {

        $service = mysqli_fetch_assoc($result);
    }

    return $service;
}


function getUserInfo($username) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $username);

    $query = "SELECT * FROM users WHERE username = '$username'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}


function updateUserInfo($username, $name, $points) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $username);
    $name = mysqli_real_escape_string($conn, $name);
    $points = mysqli_real_escape_string($conn, $points);

    $query = "UPDATE users SET name = '$name', points = $points WHERE username = '$username'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) === 1) {
        return true;
    } else {
        return false;
    }
}


function searchUsers($search_term) {
    global $conn;

    $search_term = mysqli_real_escape_string($conn, $search_term);

    $query = "SELECT * FROM users WHERE name LIKE '%$search_term%' OR username LIKE '%$search_term%' OR user_code LIKE '%$search_term%'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $users = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        return $users;
    } else {
        return false;
    }
}

?>



