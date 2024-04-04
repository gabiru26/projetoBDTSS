<?php

include_once 'config.php';

function createUser($name, $username, $password, $user_code, $date_creation, $points, $role = "user") {
    global $conn;
  

    $name = mysqli_real_escape_string($conn, $name);
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = password_hash($password, PASSWORD_DEFAULT); 
  
  
  
    $points = mysqli_real_escape_string($conn, $points);
    $date_creation = mysqli_real_escape_string($conn, $date_creation);
  
    $query_check_username = "SELECT COUNT(0) as count FROM users WHERE username = '$username'";
    $result_check_username = mysqli_query($conn, $query_check_username);
    $row = mysqli_fetch_assoc($result_check_username);
    if ($row['count'] > 0) {
      return false; 
    }
  
    $query = "INSERT INTO users (name, username, password, user_code, date_creation, points, role)
               VALUES ('$name', '$username', '$password_hash', '$user_code', '$date_creation', $points, '$role')";
  
    $result = mysqli_query($conn, $query);
  
    if ($result) {
      return true;
    } else {
      error_log("Error in createUser: " . mysqli_error($conn));
      return false;
    }
  }
  


function generateUserCode($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    do {
       // gera codigo aleatorio
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // verifica se codigo é unico
        global $conn;
        $query = "SELECT user_code FROM users WHERE user_code = '$code'";
        $result = mysqli_query($conn, $query);
    } while (mysqli_num_rows($result) > 0);  // Loop until a unique code is found

    mysqli_free_result($result);  // Free the result set to avoid memory leaks

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
        unset($user['password']); // Remove password from returned data
        $user['role'] = $user['role'];
        return $user;
      }
    }
  
    return false;
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
      $user = mysqli_fetch_assoc($result);
      $user['role'] = $user['role']; // Assign role explicitly
      return $user;
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

    if ($role !== null) {
        $query .= ", role = '$role'"; // Update role if provided
      }

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



