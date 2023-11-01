<?php // Replace 'SongModel.php' with the actual filename if different.

class SongController extends BaseController
{

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