<?php

include_once 'config.php';


//classe encapsula as operaçoes relacionadas aos users
class User {
  //armazena a conexao com a bd
  // garantir a encapsulaçao, segurança e flexibilidade do codigo
  private $conn;

  //recebe a conexao com a bd como parametro e a armazena na propriedade $conn
  public function __construct($conn) {
      $this->conn = $conn;
  }

  //criar um novo user na bd
  //dados do user como parametros
  public function createUser($name, $username, $password, $user_code, $date_creation, $points, $role = "user") {
    //evitar injeções de SQL
      $name = mysqli_real_escape_string($this->conn, $name);
      $username = mysqli_real_escape_string($this->conn, $username);
      $password_hash = password_hash($password, PASSWORD_DEFAULT); 
      $points = mysqli_real_escape_string($this->conn, $points);
      $date_creation = mysqli_real_escape_string($this->conn, $date_creation);

      $query_check_username = "SELECT COUNT(0) as count FROM users WHERE username = '$username'";
      $result_check_username = mysqli_query($this->conn, $query_check_username);
      $row = mysqli_fetch_assoc($result_check_username);
      if ($row['count'] > 0) {
          return false; 
      }

      $query = "INSERT INTO users (name, username, password, user_code, date_creation, points, role)
                 VALUES ('$name', '$username', '$password_hash', '$user_code', '$date_creation', $points, '$role')";
      $result = mysqli_query($this->conn, $query);

      if ($result) {
          return true;
      } else {
          error_log("Error in createUser: " . mysqli_error($this->conn));
          return false;
      }
  }

  //gera um codigo unico
  public function generateUserCode($length = 8) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $code = '';

      do {
          $code = '';
          for ($i = 0; $i < $length; $i++) {
              $code .= $characters[rand(0, strlen($characters) - 1)];
          }

          $query = "SELECT user_code FROM users WHERE user_code = '$code'";
          $result = mysqli_query($this->conn, $query);
      } while (mysqli_num_rows($result) > 0);

      mysqli_free_result($result);  

      return $code;
  }

  //verifica as credenciais de login
  public function loginUser($username, $password) {
      $username = mysqli_real_escape_string($this->conn, $username);

      $query = "SELECT * FROM users WHERE username = '$username'";
      $result = mysqli_query($this->conn, $query);

      if ($result && mysqli_num_rows($result) > 0) {
          $user = mysqli_fetch_assoc($result);

          if (password_verify($password, $user['password'])) {
              unset($user['password']);
              $user['role'] = $user['role'];
              return $user;
          }
      }

      return false;
  }

  //obtem info dum user com base no nome de user fornecido
  public function getUserInfo($username) {
      $username = mysqli_real_escape_string($this->conn, $username);

      $query = "SELECT * FROM users WHERE username = '$username'";

      $result = mysqli_query($this->conn, $query);

      if ($result && mysqli_num_rows($result) === 1) {
          $user = mysqli_fetch_assoc($result);
          $user['role'] = $user['role'];
          return $user;
      } else {
          return false;
      }
  }

  //atualiza as informaçoes de um user
  public function updateUserInfo($username, $name, $points) {
      $username = mysqli_real_escape_string($this->conn, $username);
      $name = mysqli_real_escape_string($this->conn, $name);
      $points = mysqli_real_escape_string($this->conn, $points);

      $query = "UPDATE users SET name = '$name', points = $points WHERE username = '$username'";

      $result = mysqli_query($this->conn, $query);

      if ($result && mysqli_affected_rows($this->conn) === 1) {
          return true;
      } else {
          return false;
      }
  }

  //realiza uma pesquisa de user com base num termo de pesquisa
  public function searchUsers($search_term) {
      $search_term = mysqli_real_escape_string($this->conn, $search_term);

      $query = "SELECT * FROM users WHERE name LIKE '%$search_term%' OR username LIKE '%$search_term%' OR user_code LIKE '%$search_term%'";

      $result = mysqli_query($this->conn, $query);

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

 
    public function getUserInfoByCode($user_code) {
    $user_code = mysqli_real_escape_string($this->conn, $user_code);

    $query = "SELECT * FROM users WHERE user_code = '$user_code'";

    $result = mysqli_query($this->conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        return $user;
    } else {
        return false;
    }
    }

    public function updateUserPoints($user_code, $points) {
    $user_code = mysqli_real_escape_string($this->conn, $user_code);
    $points = mysqli_real_escape_string($this->conn, $points);

    $query = "UPDATE users SET points = $points WHERE user_code = '$user_code'";

    $result = mysqli_query($this->conn, $query);

    return $result;
    }
}
  

function fetchGenres() {
    global $conn;
  
    //armazena os generos recuperados da db
    $genres = array();
  
    $query = "SELECT id_genres, genre_name, genre_photo FROM genres ORDER BY genre_name ASC";
  
    $result = mysqli_query($conn, $query);
  
    //loop while e adiciona cada linha ao array
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $genres[] = $row;
      }
    } else {
      echo "Error fetching genres: " . mysqli_error($conn);
    }
  
    mysqli_free_result($result);
    return $genres;
}


function fetchGenreName($genre_id) {
    global $conn;

    $query = "SELECT genre_name FROM genres WHERE id_genres = '$genre_id'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['genre_name'];
    } else {
        return "Unknown Genre";
    }
}


//recebe um id de gênero como parâmetro e procura serviços associados
function fetchServicesByGenreId($genre_id) {
    global $conn;

    $services = array();

    $query = "SELECT id_services, service_name, service_photo FROM services WHERE genres_id_genres = '$genre_id' ORDER BY service_name ASC";

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

//parametro $limit que define quantos serviços sao retornados
function fetchMostLikedServices($limit) {
    global $conn;

    //armazenar resultados
    $services = array();

    $query = "SELECT s.id_services, s.service_name, s.service_photo, s.genres_id_genres AS genre_id, g.genre_photo, COUNT(uhs.users_id_users) AS likes 
              FROM services s
              LEFT JOIN users_has_services uhs ON s.id_services = uhs.services_id_services
              LEFT JOIN genres g ON s.genres_id_genres = g.id_genres
              GROUP BY s.id_services
              ORDER BY likes DESC
              LIMIT $limit";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }
    }

    return $services;
}

//adiciona novo serviço à bd
function addService($id_genres, $service_name, $description, $price, $points_awarded) {
    global $conn;
    
    $id_genres = mysqli_real_escape_string($conn, $id_genres);
    $service_name = mysqli_real_escape_string($conn, $service_name);
    $description = mysqli_real_escape_string($conn, $description);
    $price = mysqli_real_escape_string($conn, $price);
    $points_awarded = mysqli_real_escape_string($conn, $points_awarded);


    $query = "INSERT INTO services (genres_id_genres, service_name, description, price, points_awarded)
              VALUES ('$id_genres', '$service_name', '$description', '$price', '$points_awarded')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        return true;
    } else {
        error_log("Error in addService: " . mysqli_error($conn));
        return false;
    }
}

//editar um serviço
function updateService($service_id, $new_service_name, $new_description, $new_price, $new_points_awarded) {
    global $conn;

    $service_id = mysqli_real_escape_string($conn, $service_id);
    $new_service_name = mysqli_real_escape_string($conn, $new_service_name);
    $new_description = mysqli_real_escape_string($conn, $new_description);
    $new_price = mysqli_real_escape_string($conn, $new_price);
    $new_points_awarded = mysqli_real_escape_string($conn, $new_points_awarded);

    $query = "UPDATE services 
              SET service_name = '$new_service_name', description = '$new_description', 
                  price = '$new_price', points_awarded = '$new_points_awarded' 
              WHERE id_services = '$service_id'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) === 1) {
        return true;
    } else {
        error_log("Error in updateService: " . mysqli_error($conn));
        return false;
    }
}

//eliminar um serviço
function deleteService($service_id) {
    global $conn;

    //excluir registros associados na tabela users_has_services
    $query_delete_associated_records = "DELETE FROM users_has_services WHERE services_id_services = '$service_id'";
    $result_delete_associated_records = mysqli_query($conn, $query_delete_associated_records);

    if (!$result_delete_associated_records) {
        error_log("Error deleting associated records from users_has_services table: " . mysqli_error($conn));
        return false;
    }

    //eliminar serviço da tabela services
    $query_delete_service = "DELETE FROM services WHERE id_services = '$service_id'";
    $result_delete_service = mysqli_query($conn, $query_delete_service);

    if ($result_delete_service) {
        return true;
    } else {
        error_log("Error deleting service: " . mysqli_error($conn));
        return false;
    }
}


?>



