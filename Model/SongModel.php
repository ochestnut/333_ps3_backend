<?php
require_once 'Database.php';
class SongModel extends Database
{
  public function allSongs()
  {
    $query = "SELECT * FROM ratings";
    $result = $this->connection->query($query);

    if ($result) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
  }

  public function checkSongExists($songUser, $songTitle)
  {
    $query = "SELECT * FROM ratings WHERE user = ? AND title = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("ss", $songUser, $songTitle);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->num_rows < 1;
  }

  public function addSong($userData)
  {
    if ($this->checkSongExists($userData['user'], $userData['title'])) {
      $sql = "INSERT INTO ratings (user, title, artist, rating) VALUES (?, ?, ?, ?)";
      $stmt = $this->connection->prepare($sql);
      $stmt->bind_param("sssi", $userData['user'], $userData['title'], $userData['artist'], $userData['rating']);

      if ($stmt->execute()) {
        $newSongId = $stmt->insert_id;
        $stmt->close();
        return ['status' => 'success', 'message' => 'Song added successfully', 'song_id' => $newSongId];
      } else {
        return ['status' => 'error', 'message' => 'Error adding song : ' . $stmt->error];
      }

    } else {
      //chatGPT for status error message syntax
      return ['status' => 'error', 'message' => 'Song already exists for this user.'];
    }
  }

  public function editSong($userData)
  {
    $sql = "UPDATE ratings SET title = ?, artist = ?, rating = ? WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("ssii", $userData['title'], $userData['artist'], $userData['rating'], $userData['id']);

    if ($stmt->execute()) {
      $newSongId = $stmt->insert_id;
      $stmt->close();
      return ['status' => 'success', 'message' => 'Song updated successfully @', 'song_id' => $newSongId];
    } else {
      return ['status' => 'error', 'message' => 'Error updating song : ' . $stmt->error];
    }
  }

  public function deleteSong($id)
  {
    $sql = "DELETE FROM ratings WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      return "Song with ID $id deleted successfully.";
    } else {
      return "Error deleting song with ID $id: " . $stmt->error;
    }
  }
}
?>