<?php

require_once 'SongModel.php'; // Replace 'SongModel.php' with the actual filename if different.

class SongControllerpublic function createAction()
  {    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if (strtoupper($requestMethod) == 'POST') {
      $postData = json_decode(file_get_contents('php://input'), true);
      $postUser = $postData['username'];
      $postTitle = $postData['title'];
      $postArtist = $postData['artist'];
      $postRating = $postData['rating'];

      if (isset($postUser, $postTitle, $postArtist, $postRating)) {
        // Use the $config array to create a Database instance
        // Use the $config array to create a Database instance
        require_once 'Database.php';

        $database = new Database($config);
        $songModel = new SongModel($database);

        if ($songModel->checkSong($postUser, $postTitle) == 0) {
          $songModel->__addSong($postUser, $postTitle, $postArtist, $postRating);
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

  public function editSong()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['id'], $data['title'], $data['artist'], $data['rating'])) {
        $this->songModel->__editSong($data['id'], $data['title'], $data['artist'], $data['rating']);
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

  public function deleteSong()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['id'])) {
        $this->songModel->__deleteSong($data['id']);
        http_response_code(200); // OK
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

$songController = new SongController($db);

// Define your API endpoints
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  if ($action === 'add') {
    $songController->addSong();
  } elseif ($action === 'edit') {
    $songController->editSong();
  } elseif ($action === 'delete') {
    $songController->deleteSong();
  } else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid action']);
  }
} else {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Action not specified']);
}

?>