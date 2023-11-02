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
    $hashPassword = null;
    $stmt->bind_result($hashPassword);
    $stmt->fetch();
    $stmt->close();

    if ($hashPassword && password_verify($enteredPassword, $hashPassword)) {
      return ['status' => 'success', 'username' => $enteredUsername];
    } else {
      return ['status' => 'error', 'message' => 'Passwords do not match'];
    }
  }

  public function addUser($userData)
  {
    if ($this->checkUsernameAvailability($userData['username'])) {
      if (($userData['password']) === $userData['c_password']) {
        $storePassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $userData['username'], $storePassword);
        if ($stmt->execute()) {
          $stmt->close();
          return ['status' => 'success', 'message' => 'Welcome new user', 'username' => $userData['username']];
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
    $result = $this->checkPassword($userData['username'], $userData['password']);
    if ($result) {
      return ['status' => 'success', 'message' => 'Welcome back', 'username' => $result['username']];
    } else {
      return ['status' => 'error', 'message' => $result['message']];
    }
  }

}
?>