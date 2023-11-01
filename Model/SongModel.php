<?php
class songModel extends Database
{
  public function checkSong($songData)
  {
    $query = "SELECT * FROM ratings WHERE user = ? AND title = ?";
    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("ss", $songData['username'], $songData['title']);
    $stmt->execute();
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $stmt->close();

    return $numRows;
  }
  public function addSong($username, $title, $artist, $rating)
  {
    $insert_add = "INSERT INTO ratings (user, title, artist, rating) VALUES (?, ?, ?, ?)";
    $prep_add = $this->connection->prepare($insert_add);
    $prep_add->bind_param("sssi", $username, $title, $artist, $rating);

    if ($prep_add->execute()) {
      echo "Song updated successfully.";

    } else {
      echo "Error updating song: " . $prep_add->error;
    }
  }

  public function editSong($id, $title, $artist, $rating)
  {
    $update_edit = "UPDATE ratings SET title = ?, artist = ?, rating = ? WHERE id = ?";
    $prep_edit = $this->connection->prepare($update_edit);
    $prep_edit->bind_param("ssii", $title, $artist, $rating, $id);

    if ($prep_edit->execute()) {
      echo "Song updated successfully.";

    } else {
      echo "Error updating song: " . $prep_edit->error;
    }
  }

  public function deleteSong($id)
  {
    $delete = "DELETE FROM ratings WHERE id = ?";
    $prep_delete = $this->connection->prepare($delete);
    $prep_delete->bind_param("i", $id);

    if ($prep_delete->execute()) {
      echo "Row deleted successfully.";
    } else {
      echo "Error deleting song: " . $prep_delete->error;
    }
  }
}
?>