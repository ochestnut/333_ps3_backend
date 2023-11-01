<?php

require_once 'SongModel.php'; // Replace 'SongModel.php' with the actual filename if different.

class SongController
{

  public function createAction()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if (strtoupper($requestMethod) == 'POST') {
      $postData = json_decode(file_get_contents('php://input'), true);
      $postUser = $postData['username'];
      $postTitle = $postData['title'];
      $postArtist = $postData['artist'];
      $postRating = $postData['rating'];

      if (isset($postUser, $postTitle, $postArtist, $postRating)) {

        require_once 'Database.php';

        $database = new Database($config);
        $songModel = new SongModel($database);

        if ($songModel->checkSong($postUser, $postTitle) == 0) {
          $songModel->addSong($postUser, $postTitle, $postArtist, $postRating);
          http_response_code(201); // Created
          echo json_encode(['message' => 'Song added successfully']);
        } else {
          http_response_code(400); // Bad Request
          echo json_encode(['error' => 'Song already entered by user']);
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
      $postId = $postData['id'];
      $postTitle = $postData['title'];
      $postArtist = $postData['artist'];
      $postRating = $postData['rating'];

      if (isset($postId, $postTitle, $postArtist, $postRating)) {
        require_once 'Database.php';

        $database = new Database($config);
        $songModel = new SongModel($database);

        $songModel->editSong($postId, $postTitle, $postArtist, $postRating);

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
      $postId = $postData['id'];

      if (isset($postId)) {
        require_once 'Database.php';

        $database = new Database($config);
        $songModel = new SongModel($database);

        $songModel->deleteSong($postId);

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