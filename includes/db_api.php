<?php

include_once 'config.php';

function registerUser($name, $username, $password_hash, $user_code, $date_of_creation, $points, $isAdmin = false) {
    // recebe vários parâmetros
    
    global $conn; //usa uma conexão global com o banco de dados

    
    $name = mysqli_real_escape_string($conn, $name);
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = mysqli_real_escape_string($conn, $password_hash); 
    $user_code = mysqli_real_escape_string($conn, $user_code);
    $points = mysqli_real_escape_string($conn, $points);
    $date_creation = mysqli_real_escape_string($conn, $date_of_creation);

    
    $role_id_role = $isAdmin ? 0 : 1; 

    
    $query = "INSERT INTO users (name, username, password_hash, user_code, date_creation, points, role_id_role) 
              VALUES ('$name', '$username', '$password_hash', '$user_code', '$date_creation', $points, $role_id_role)";
    //executa uma consulta SQL para inserir os dados do usuário
    
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
    $code = '';

    // Generate a random code
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    // Check if the generated code already exists in the database
    // This ensures uniqueness, assuming user codes must be unique
    global $conn;
    $query = "SELECT user_code FROM users WHERE user_code = '$code'";
    $result = mysqli_query($conn, $query);

    // If the code already exists, generate a new one
    while (mysqli_num_rows($result) > 0) {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        $result = mysqli_query($conn, "SELECT user_code FROM users WHERE user_code = '$code'");
    }

    return $code;
}




function loginUser($username, $password_hash) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = mysqli_real_escape_string($conn, $password_hash);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password_hash, $user['password_hash'])) {
            
            unset($user['password_hash']);
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


?>
