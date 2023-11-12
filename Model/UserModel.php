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

    $query = "SELECT password FROM users WHERE username = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();

    $binding = null;
    $stmt->bind_result($binding);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($enteredPassword, $binding)) {
      // Passwords match
      return true;
    } else {
      // Passwords do not match
      return false;
    }
  }

  public function addUser($userData)
  {
    if ($this->checkUsernameAvailability($userData['reg_username'])) {
      if (($userData['reg_password']) === $userData['c_password']) {
        $storePassword = password_hash($userData['reg_password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $userData['reg_username'], $storePassword);
        if ($stmt->execute()) {
          $stmt->close();
          return ['status' => 'success', 'message' => 'Welcome new user', 'username' => $userData['reg_username']];
        } else {
          $stmt->close();
          return ['status' => 'error', 'message' => 'Error adding user'];
        }

      } else {
        //chatGPT for status error message syntax
        return ['status' => 'error', 'message' => 'Username already exists for this user.'];
      }

    }
  }

  public function loginUser($userData)
  {
    if (($this->checkUsernameAvailability($userData['log_username']))) {
      return ['error' => 'invalid username', 'message' => 'username already exists in database'];
    } else {
      if (($this->checkPassword($userData['log_username'], $userData['log_password']))) {
        return ['status' => 'success', 'message' => 'Logged in successfully', 'username' => $userData['log_username']];
      }
    }

  }

}
?>