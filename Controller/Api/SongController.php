<?php // Replace 'SongModel.php' with the actual filename if different.

class SongController extends BaseController
{


  public function listAction()
  {
    $strErrorDesc = '';
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $arrQueryStringParams = $this->getQueryStringParams();
    if (strtoupper($requestMethod) == 'GET') {
      try {
        $userModel = new SongModel();
        $intLimit = 10;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
          $intLimit = $arrQueryStringParams['limit'];
        }
        $arrUsers = $userModel->getSong($intLimit);
        $responseData = json_encode($arrUsers);
      } catch (Error $e) {
        $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }
    // send output 
    if (!$strErrorDesc) {
      $this->sendOutput(
        $responseData,
        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
      );
    } else {
      $this->sendOutput(
        json_encode(array('error' => $strErrorDesc)),
        array('Content-Type: application/json', $strErrorHeader)
      );
    }
  }
  public function createAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if (strtoupper($requestMethod) == 'POST') {

      $postData = json_decode(file_get_contents('php://input'), true);

      if (is_array($postData) && isset($postData['user'], $postData['title'], $postData['artist'], $postData['rating'])) {
        $songModel = new SongModel();
        $result = $songModel->addSong($postData);
        if ($result['status'] === 'success') {
          http_response_code(201); // Created
          echo json_encode(['message' => 'Song added successfully', 'song_id' => $result['song_id']]);
        } else {
          http_response_code(400); // Bad Request
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


  public function editAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == 'PUT') {
      $postData = json_decode(file_get_contents('php://input'), true);


      if (isset($postData['id'], $postData['title'], $postData['artist'], $postData['rating'])) {

        $songModel = new SongModel();
        $songModel->editSong($postData);

        http_response_code(200); // OK
        echo json_encode(['message' => 'Song updated successfully']);
      } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid data']);
      }
    } else {
      http_response_code(405); // Method Not Allowed
      echo json_encode(['error' => 'Invalid request method']);
    }
  }

  public function deleteAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == 'DELETE') {

      $postData = json_decode(file_get_contents('php://input'), true);

      if (isset($postData['id'])) {

        $songModel = new SongModel();
        $songModel->deleteSong($postData['id']);

        http_response_code(200);
        echo json_encode(['message' => 'Song deleted successfully']);
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