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
    $add = "INSERT INTO ratings (title, artist) VALUES (?, ?)";
    $add2 = $this->db->prepare($add);
    $add2->execute([$username, $title, $artist, $rating]);
  }

}
?>