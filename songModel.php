<?php
class SongModel
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function __addSong($username, $title, $artist, $rating)
  {
    $insert_add = "INSERT INTO ratings (user, title, artist, rating) VALUES (?, ?, ?, ?)";
    $prep_add = $this->db->prepare($insert_add);
    $prep_add->execute([$username, $title, $artist, $rating]);

    if ($prep_add->execute()) {
      echo "Song updated successfully.";

    } else {
      echo "Error updating song: " . $prep_add->error;
    }
  }

  public function __editSong($id, $title, $artist, $rating)
  {
    $update_edit = "UPDATE ratings SET title = ?, artist = ?, rating = ? WHERE id = ?";
    $prep_edit = $this->db->prepare($update_edit);
    $prep_edit->execute("ssii", $title, $artist, $rating, $id);

    if ($prep_edit->execute()) {
      echo "Song updated successfully.";

    } else {
      echo "Error updating song: " . $prep_edit->error;
    }
  }
}
?>