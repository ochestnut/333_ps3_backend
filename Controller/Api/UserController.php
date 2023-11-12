<?php
class UserController extends BaseController
{
  public function createAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if (strtoupper($requestMethod) == 'POST') {

      $postData = json_decode(file_get_contents('php://input'), true);

      if (is_array($postData) && isset($postData['reg_username'], $postData['reg_password'], $postData['c_password'])) {
        $userModel = new UserModel();
        $result = $userModel->addUser($postData);
        if ($result['status'] === 'success') {
          http_response_code(201); // Created
          echo json_encode(['status' => 'success', 'message' => 'User added successfully', 'username' => $result['username']]);
        } else {
          http_response_code(400); // Bad Request
          echo json_encode(['error' => 'user could not be added']);
        }
      } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid data']);
      }
    } else {
      http_response_code(405); // Method Not Allowed
      echo json_encode(['error' => 'Invalid request method']);
    }
  }

  public function loginAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if (strtoupper($requestMethod) == 'POST') {

      $postData = json_decode(file_get_contents('php://input'), true);

      if (is_array($postData) && isset($postData['log_username'], $postData['log_password'])) {
        $userModel = new UserModel();
        $result = $userModel->loginUser($postData);

        if ($result['status'] === 'success') {
          // Successful login
          http_response_code(200); // OK
          echo json_encode(['status' => 'success', 'message' => 'User logged in successfully', 'username' => $result['username']]);
        } else {
          // Login failed
          http_response_code(401); // Unauthorized
          echo json_encode(['error' => $result['message']]);
        }
      } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid data']);
      }
    } else {
      http_response_code(405); // Method Not Allowed
      echo json_encode(['error' => 'Invalid request method']);
    }
  }

}



?>