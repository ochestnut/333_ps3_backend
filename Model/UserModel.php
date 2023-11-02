<?php
require_once 'Database.php';
class UserModel extends Database
{
  public function checkUsernameAvailability($enteredUsername)
  {
    $query = "SELECT * FROM users WHERE username = ? ";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->num_rows < 1;
  }
  public function checkPassword($enteredUsername, $enteredPassword)
  {
    $query = "SELECT password FROM users WHERE username = ? ";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $hashedPassword = $stmt->get_result();
    $stmt->close();
    if (isset($hashedPassword)) { //username exists in database
      if (password_verify($enteredPassword, $hashedPassword)) { //checking password equality
        return ['status' => 'success', 'username' => $enteredUsername];
      } else {
        return ['status' => 'error', 'message' => 'passwords do not match'];
      }
    } else {
      return ['status' => 'error', 'message' => 'username does not have an account'];
    }
  }

  public function addUser($userData)
  {
    if ($this->checkUsernameAvailability($userData['username'])) {
      if (($userData['password']) === $userData['c_password']) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $storePassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $userData['username'], $storePassword);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result) {
          return ['status' => 'success', 'message' => 'Welcome new user', 'username' => $userData['username']];
        } else {
          return ['status' => 'error', 'message' => 'Error adding user : ' . $stmt->error];
        }

      } else {
        //chatGPT for status error message syntax
        return ['status' => 'error', 'message' => 'Username already exists for this user.'];
      }

    }
  }

  public function loginUser($userData)
  {
    $result = $this->checkPassword($userData['username'], $userData['password']);
    if ($result) {
      return ['status' => 'success', 'message' => 'Welcome back', 'username' => $result['username']];
    } else {
      return ['status' => 'error', 'message' => $result['message']];
    }
  }

}
?>