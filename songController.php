<?php
include 'connection.php';
require_once 'SongModel.php'; // Replace 'SongModel.php' with the actual filename if different.

class SongController
{

  public function addSong()
  {
    

      if (isset($data['username'], $data['title'], $data['artist'], $data['rating'])) {
        if ($this->songModel->checkSong($data['username'], $data['title']) == 0) {
          $this->songModel->__addSong($data['username'], $data['title'], $data['artist'], $data['rating']);
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